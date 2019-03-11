<?php
class Socket{
  static private $link;
	public $m_socket='';
	protected $m_ip='192.168.1.1';
	protected $m_port='4015';
	public $connet = '';
	private function __construct(){
		$this->create();
		$this->connet();
	}
	static public function get(){
			//判断seesion
//			if(session('socket')){			
//					return json_decode(session('socket'));
//			}else{
				self::$link = new self;
//				$socket = json_encode(self::$link);
//				session('socket',$socket);
				return self::$link;
//			}
	}
	//创建连接方法
	protected function create(){
			$this->m_socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	}
	//连接
	protected function connet(){
		socket_set_option($this->m_socket, SOL_SOCKET, SO_RCVTIMEO,array("sec"=>3, "usec"=>0));//接受超时
			$this->connet = socket_connect($this->m_socket, $this->m_ip, $this->m_port);
    }
   //发送数据
    public function send($messageId,$type,$code,$data){
    	if($this->connet == false){
    		//如果连接失败重连一次
    		$this->connet();
    	}
    	if($this->connet == false){
    		//再次连接失败写入缓存
    		return false;
    	}
		$send_data = $this->assemble_data($messageId,$type,$code,$data);
		$res = socket_write($this->m_socket, $send_data, strlen($send_data));
		if($res == strlen($send_data)){
			return true;
		}else{
			return false;
		}
    }
protected function assemble_data($messageId,$type,$code,$data){
     $size = 16 + strlen($data);
     $struct = '';
     $struct .= pack('i',$size);
     $struct .= pack('i',$messageId);
     $struct .= pack('i',$type);
     $struct .= pack('i',$code);
     $struct .= $data;
     return $struct;
}
  //测试连接性
protected function test_connetion(){
	$data = "";
	// 测试连接的包
	$test_data = pack("i*", 8, 1);
	$back = socket_write($this->m_socket,$test_data,strlen($test_data));
	if($back == strlen($test_data)){
	return  true;
	}else{
	return   false;
	}
}
   //发送数据
    public function read_data($length){
        $result = socket_read($this->m_socket,$length);
        if(!$result){
            return false;
        }else{
            return $result;
        }
    }
    //关闭socket
    public function close(){
    	socket_close($this->m_socket);
    }
}


