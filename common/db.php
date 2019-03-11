<?php 

class db{
	
	private static $link = null;
	private static $_instance;
	protected $options = array(
		//PDO::ATTR_CASE              =>  PDO::CASE_LOWER,
		PDO::ATTR_ERRMODE           =>  PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_ORACLE_NULLS      =>  PDO::NULL_NATURAL,
		PDO::ATTR_STRINGIFY_FETCHES =>  false,
		//PDO::ATTR_PERSISTENT=>true,
		//PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"
	);
	
	/**
	* 初始化数据库连接
	* @date: 2016年12月6日 上午10:38:16
	* @author: CUI
	* @param: variable
	* @return:
	*/
	public function __construct(){
		global $config;
		$dsn = 'dblib:host='.$config['DB_HOST'].';dbname='.$config['DB_NAME'];
		if(!empty($config['DB_PORT'])) {
			$dsn  .= ','.$config['DB_PORT'];
		}
		
		try{
			self::$link = new PDO($dsn,$config['DB_USER'],$config['DB_PASS'],$this->options);
			//self::$link -> exec('set names utf-8');
		}catch (\PDOException $e) {
			echo $e->getMessage();
			exit();
		}
	}

	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
	} 	
	/**
	* 查询多条数据
	* @date: 2016年12月6日 上午10:38:08
	* @author: CUI
	* @param: variable
	* @return:
	*/
	public function getAll($sql){
		$result = self::$link->query($sql);
		$rs = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rs;
	}
	
	/**
	* 查询单条数据
	* @date: 2016年12月6日 上午10:38:53
	* @author: CUI
	* @param: variable
	* @return:
	*/
	public function getRow($sql){
		$result = self::$link->query($sql);
		$rs = $result->fetch(PDO::FETCH_ASSOC);
		return $rs;
	}
	
	/**
	* 插入数据
	* @date: 2016年12月6日 上午10:39:17
	* @author: CUI
	* @param: variable
	* @return:
	*/
	public function add($sql){
		try {
			$result = self::$link->exec($sql);
			return $result;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	/**
	* 执行存储过程
	* @date: 2016年12月6日 上午10:40:00
	* @author: CUI
	* @param: variable
	* @return:
	*/
	public function procedure($sql,$tag=true){
		try {
			$statement = self::$link->prepare($sql);
			$res = $statement->execute();
			if($tag){
				$statement->nextRowset();
				$rs = $statement->fetch(PDO::FETCH_ASSOC);
				return $rs;
			}else{
				return $res;
			}
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

}


?>
