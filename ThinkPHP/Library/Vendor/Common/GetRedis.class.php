<?php 
class GetRedis {
	static public $link = null;
	public  $redis = '';
	public  $connect = '';
	const IP = '192.168.1.1';
	const PORT = '6380';
	protected function __construct(){
		$this->redis = new redis();
		$this->connect = $this->redis->connect(self::IP,self::PORT,1);
		//$this->redis->auth('Yy304949708');
	}
	static public function get(){
			if(self::$link != null){
					return self::$link;
			}else{
					self::$link = new self;
					return self::$link;
			}
	}
}
 ?>
