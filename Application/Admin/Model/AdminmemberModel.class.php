<?php
namespace Admin\Model;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use helper\LogHelper;
//use model\AppModel;
use model\AgentModel;
use Think\Model;
//用户模型
class AdminmemberModel extends Model{
    const LOGIN_STATUS = [
        'SUCCESS' => 1,
        'FAIL' => 0,
    ];
    const LOGIN_VERIFY = [
        'YES' => 1,
        'NO' => 0,
    ];
    const TRY_TIME = 300;
    public $tableName = 'admin_member';
    //注册自动验证
    protected $_validate = array(
        array('username','require','用户名不能为空'),
        array('username', 'checklength', '用户名长度必须在6-15位之间！', 0, 'callback', 3, array(6, 15)),
        array('username','','用户名称已经存在！',0,'unique',1),
        array('nickname','require','昵称不能为空'),
        array('phone','require','手机号不能为空'),
        array('phone','number','手机格式不正确'),
        array('phone','','手机号已经存在！',0,'unique',1),
        array('password','require','密码不能为空'),
        array('password', 'checklength', '密码长度必须在6-15位之间！', 0, 'callback', 3, array(6, 15)),
        array('repassword','require','确认密码不能为空'),
        array('repassword','password','两次密码不一致。',0,'confirm'),
    );
    //自动完成
    protected $_auto = array (
        array('password','md5',3,'function') ,
        array('register_time','time',1,'function'),
    );
    //验证用户名长度
    public  function checklength($str, $min, $max) {
        preg_match_all("/./u", $str, $matches);
        $len = count($matches[0]);
        if ($len < $min || $len > $max) {
            return false;
        } else {
            return true;
        }
    }
    //登录验证
    public function check_login($data){
        $data['password'] = md5($data['password']);
        $loginLimit = 5;
        $res = $this->limitLogin($data['username'], $loginLimit);
        $errMsg = $res['msg'];
        if ($res['code'] == ErrorConfig::ERROR_CODE) {
            $this->loginRecord($data['username'], self::LOGIN_STATUS['FAIL'], 0, self::LOGIN_VERIFY['NO']);
            $this->error = $errMsg;
            return false;
        }
        $user = $this->where(array('username'=>$data['username']))->find();
        if(!$user){
            $this->loginRecord($data['username'], self::LOGIN_STATUS['FAIL']);
            $this->error=$errMsg;//'用户名或密码错误';
            return false;
        }
        if($user['disabled'] == 1){$this->error='账号被禁用';return false;}
	 //判断该用户组是否被禁用
        if($user['id'] != 1){
            $group = M('Admin_group')->find($user['group_id']);
            if($group['disabled'] == 1){
                $this->error = '您所在的分组被禁止登录';   return false;
            }
        }
        if($user['password'] != $data['password']) {
            $this->loginRecord($user['username'], self::LOGIN_STATUS['FAIL'], $user['id']);
            $this->error=$errMsg;//'用户名或密码错误';
            return false;
        }
        $this->loginRecord($user['username'], self::LOGIN_STATUS['SUCCESS'], $user['id']);
        //修改用户登录次数 IP 时间
        //M('Admin_member')->where(['id'=>$user['id']])->setInc('login_count');    //登陆次数+1
	    $data['last_login_ip'] = get_client_ip();
        $data['last_login_time'] = time();
        M('Admin_member')->where(['id'=>$user['id']])->save($data);
        session('admin_user_id',$user['id']);
        session('admin_user_info',$user);
        S($user['id'],session_id());
	    return true;
    }

    protected function limitLogin($username, $limit = 5) {
        $loginTime = $this->getFailLoginRate($username)['data'];
        if ($loginTime > $limit) {
            $res = M()->table(MysqlConfig::Table_web_admin_login_history)->where(['username' => $username, 'loginVerify' => self::LOGIN_VERIFY['YES']])->order('createTime desc')->getField('createTime');
//            var_export((time() - (int)$res));
            $time = (self::TRY_TIME - (time() - (int)$res));
            $time = FunctionHelper::friendTime($time);
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '用户名或密码错误，该用户登录已锁定，' . ($time) . '后再尝试'];
        }
        return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => '用户名或密码错误，' . (FunctionHelper::friendTime(self::TRY_TIME)) . '内还可以尝试' . ($limit - $loginTime) . '次'];
    }

    protected function getFailLoginRate($username) {
        $where = [
            'username' => $username,
            'loginStatus' => self::LOGIN_STATUS['FAIL'],
            'createTime' => ['egt', (time() - self::TRY_TIME)],
        ];
        $where = AgentModel::getInstance()->makeWhere($where);
        $res = M()->query('select count(1) as cnt from ' . MysqlConfig::Table_web_admin_login_history . $where . ' group by username');
        return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => 'ok', 'data' => (int)$res['0']['cnt']];
    }

    protected function loginRecord($username, $status, $adminID = 0, $isVerify = self::LOGIN_VERIFY['YES']) {
        $res = M()->table(MysqlConfig::Table_web_admin_login_history)->add([
            'adminID' => $adminID,
            'username' => $username,
            'loginStatus' => $status,
            'loginVerify' => $isVerify,
            'createTime' => time(),
        ]);
        if (!$res) {
            LogHelper::printError('admin login record fail');
        }
    }
}
