<?php
namespace API\Controller;

use Think\Controller;

vendor('Common.Socket', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.PconnectRedis', '', '.class.php');

class CommonController extends Controller
{
    // 错误码
    const SUCCESS_CODE = 0; // 成功
    const ERROR_CODE   = 1; // 失败

    // 错误消息
    const SUCCESS_MSG_DEFAULT      = '请求成功';
    const ERROR_MSG_DEFAULT        = '请求失败';
    const ERROR_MSG_SIGN           = '签名不正确';
    const ERROR_MSG_DOES_NOT_EXIST = '用户不存在';
    const ERROR_MSG_USER_NOT_FOUND = '用户不存在';
    const ERROR_MSG_ALREADY_SIGN   = '已经签到';
    const ERROR_MSG_SOCKET         = '与服务器通信错误';

    protected $redis; // redis
    protected $socket; // socket
    protected $send; // 结构体

    public function _initialize()
    {
        $this->redis  = \PconnectRedis::get()->redis;
        $this->socket = \Socket::get();
        $this->send   = new \SendFunction();
        $this->writeLog();
        // $this->checkSign();
    }

    // redis
    protected function commonRedis($action, $param)
    {
        switch ($action) {
            case 'keys':
                $res = $this->redis->keys($param[0]);
                break;
            case 'set':
                $res = $this->redis->set($param[0], $param[1]);
                break;
            case 'get':
                $res = $this->redis->get($param[0]);
                break;

            case 'hgetall':
                $res = $this->redis->hgetall($param[0]);
                break;

            case 'hmset':
                $res = $this->redis->hmset($param[0], $param[1]);
                break;
            default:
                break;
        }

        return $res;
    }

    // 公共发消息
    protected function sendMsg($action, $cost, $param)
    {
        if ($this->socket->connet == false) {
            return false;
        }

        $packet = $this->send->$action($param);
        $res    = $this->socket->send(\SendFunction::$cost, 1, 0, $packet);

        if (!$res) {
            // 请求失败，与服务器通信出现错误
            return false;
        }

        $read = unpack('i*', $this->socket->read_data(1024));
        if (!$read) {
            // 请求失败，接收服务器消息失败
            return false;
        }
        if ($read[4] != 0) {
            // 请求失败,错误码 $read[4];
            return false;
        }

        return true;
    }

    // rsa 签名
    final protected function rsa_encode($pwd)
    {
        $publicstr = file_get_contents('prikey.pem');
        $publickey = openssl_pkey_get_private($publicstr); // 读取私钥

        $r = openssl_sign($pwd, $sign, $publickey, OPENSSL_ALGO_SHA1);
        if ($r) {
            return base64_encode($sign);
        }
        return false;
    }

    // rsa 验签
    final protected function rsa_decode($str, $sign)
    {
        $publicstr = file_get_contents('pubkey.pem');
        $publickey = openssl_pkey_get_public($publicstr); // 读取私钥

        $r = openssl_verify($str, base64_decode($sign), $publickey, OPENSSL_ALGO_SHA1);
        if ($r) {
            return true;
        }
        return false;
    }

    // 参数拼接
    public function splicingAction($data)
    {
        $uuid   = $data['uuid'];
        $action = $data['action'];
        unset($data['uuid'], $data['action'], $data['sign']);

        $sort = [];
        foreach ($data as $k => $v) {
            $sort[] = ord($k{0});
        }

        array_multisort($sort, SORT_ASC, $data);
        $action = http_build_query($data) . '&uuid=' . $uuid . '&action=' . $action;
        return $action;
        // $sign = $this->rsa_encode($action);

        // if ($sign) {
        //     return $sign;
        // }

        // return false;
    }

    // 秘钥检测
    public function checkSign()
    {
        $data = I('post.');
        $sign = $data['sign'];
        unset($data['sign']);

        // 检测是否正确
        $action = $this->splicingAction($data);

        $res = $this->rsa_decode($action, $sign);

        if (!$res) {
            $this->returnJson(self::ERROR_CODE, self::ERROR_MSG_SIGN);
        }

        // 检测是否请求过
    }

    // 检测用户是否存在
    public function checkUser($userID)
    {
        $find = $this->redis->hgetall('userInfo|' . $userID);

        if (!$find) {
            return false;
        }

        return true;
    }

    protected function returnJson($status, $msg, $arr = [])
    {
        exit(json_encode(['status' => $status, 'msg' => $msg, 'data' => $arr], JSON_UNESCAPED_UNICODE));
    }

    protected function writeLog()
    {
        $log = '[' . date("Y-m-d H:i:s", time()) . '] RequestIp:' . get_client_ip() . ' RequestMethod: ' . $_SERVER['REQUEST_METHOD'] . " Data:" . json_encode($_REQUEST) . "\r\n";
        file_put_contents(REQUEST_WRITE_LOG_PATH . date('Y-m-d') . '.log', $log, FILE_APPEND);
    }

}
