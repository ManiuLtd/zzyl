<?php
namespace helper;

/**
 * socket 帮助类
 * Class SocketHelper
 */
final class SocketHelper
{
    public $oSocket = null;
    private $connect;
    private $host;
    private $port;
    private $ipv6;
    private $udp;
    private $nonblock;

    public function __construct($host, $port, $ipv6 = false, $udp = false, $nonblock = false)
    {
        $this->host = $host;
        $this->port = $port;
        $this->ipv6 = $ipv6;
        $this->udp = $udp;
        $this->nonblock = $nonblock;

        $this->create();
        $this->connect();
    }

    private function create()
    {
        if ($this->ipv6) {
            if ($this->udp) {
                $this->oSocket = socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP);
            } else {
                $this->oSocket = socket_create(AF_INET6, SOCK_STREAM, SOL_TCP);
            }
        } else {
            if ($this->udp) {
                $this->oSocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            } else {
                $this->oSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            }
        }
    }

    private function connect()
    {
        if (!$this->oSocket) {
            return;
        }
        socket_set_option($this->oSocket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0)); //接收超时 1秒
        socket_set_option($this->oSocket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 3, "usec" => 0)); //发送超时 3秒
        $this->connect = socket_connect($this->oSocket, $this->host, $this->port);
        if (!$this->connect) {
            //抛出异常，并记录错误日志
            FunctionHelper::Err("socket连接失败", 500, 0, ['socket连接失败', [$this->host, $this->port, $this->ipv6, $this->nonblock]]);
        }
        if ($this->nonblock) {
            socket_set_nonblock($this->oSocket);
        }
        return $this->connect;
    }

    public function checkConnect()
    {
        if (!$this->oSocket) {
            return;
        }
        if (!$this->connect) {
            $this->close();
            return $this->connect();
        }
        return true;
    }

    public function close()
    {
        if (!$this->oSocket) {
            return;
        }
        socket_close($this->oSocket);
    }

    /**
     * 发数据
     * @param $data
     * @return bool
     */
    public function send_data($data)
    {
        $result = socket_write($this->oSocket, $data, strlen($data));
        LogHelper::printInfo('strlen($data) = ' . strlen($data));
        if ($result != strlen($data)) {
            return false;
        }
        /**
         * TODO 后续完善读数据的问题
         */
        self::read_data(strlen($data));
        return true;
    }

    /**
     * 读数据
     * @param $length
     * @return bool|string
     */
    public function read_data($length)
    {
        $result = socket_read($this->oSocket, $length);
        if (!$result) {
            return false;
        }
        return $result;
    }
}
