<?php 
namespace helper;

/**
 * reids 帮助类
 * 一些方法还有待完善
 * 扩展文档
 * @see https://github.com/phpredis/phpredis#strings
 */
final class RedisHelper
{
    //reids连接对象
    public $oRedis = null;
    //地址
    private $host;
    //端口
    private $port;
    //密码
    private $password;
    //数据库名字
    private $dbname;
    //长链接
    private $pconnect;

    public function __construct($host, $port, $password = '', $dbname = '', $pconnect = false)
    {
        if (!class_exists('Redis')) {
            //强制使用
            die('This Lib Requires The Redis Extention!');
        }

        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->pconnect = $pconnect;

        $this->oRedis = new \Redis();
        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
    }

    public function connect()
    {
        if (!$this->oRedis) {
            return;
        }
        if ($this->pconnect) {
            $result = $this->oRedis->pconnect($this->host, $this->port);
        } else {
            $result = $this->oRedis->connect($this->host, $this->port);
        }
        if (!$result) {
            //抛出异常，并记录错误日志
            FunctionHelper::Err("redis连接失败", 500, 0, ['redis连接失败', [$this->host, $this->port, $this->password, $this->dbname, $this->pconnect]]);
        }
        if ($this->password != '') {
            $this->oRedis->auth($this->password);
        }
        if ($this->dbname != '') {
            $this->oRedis->select($this->dbname);
        }
        return $result;
    }

    public function checkConnect()
    {
        if (!$this->oRedis) {
            return;
        }
        $result = $this->oRedis->ping();
        if ($result != "+PONG") {
            $this->close();
            LogHelper::printError("redis ping fail:", [$this->host, $this->port, $this->password, $this->dbname, $this->pconnect]);
            return $this->connect();
        }
        return true;
    }

    public function close()
    {
        if (!$this->oRedis) {
            return;
        }
        $this->oRedis->close();
    }

    /**
     * 获取
     * @param String $key
     * @param Boolean $serial 存入时是否序列化了.如果存入时采取了压缩则一定序列化了.如incr和decr存入的就不需要serial
     * @return false/Mixed
     */
    public function get($key, $json = false)
    {
        $result = $this->oRedis->get($key);
        return $result === false ? false : ($json === true ? json_decode($result, true) : $result);
    }

    /**
     * 设置
     * @param String $key
     * @param Mixed $value
     * @return Boolean
     */
    public function set($key, $value, $json = false, $timeout = 0)
    {
        ($json === true) && $value = json_encode($value);
        if ($timeout > 0) {
            $flag = $this->oRedis->setex($key, $timeout, $value);
        } else {
            $flag = $this->oRedis->set($key, $value);
        }
        return $flag;
    }

    /**
     * 设置带过期时间的值
     * 等效于  SET mykey value
     *        EXPIRE mykey seconds
     * 相比连续执行上面两个命令，它更快，因为当Redis当做缓存使用时，这个操作更加常用。
     *
     */
    public function setex($key, $ttl, $value)
    {
        return $this->oRedis->setex($key, $ttl, $value);
    }

    /**
     * 同set，但当Key存在时，什么也不做返回false.
     * @param String $key
     * @param Mixed $value
     * @return Boolean
     */
    public function setnx($key, $value, $timeout = 0)
    {
        $flag = $this->oRedis->setnx($key, $value);
        if ($flag && $timeout > 0) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    /**
     * 原子递加.不存在该key则基数为0,注意$value为 max(1, $value)
     * @param String $key
     * @param int $value
     * @return false/int 返回最新的值
     */
    public function incr($key, $value = 1, $timeout = 0)
    {
        $flag = $this->oRedis->incr($key, $value);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    /**
     * 原子递减.不存在该key则基数为0,注意$value为 max(1, $value).可以减成负数
     * @param String $key
     * @param int $value
     * @return false/int 返回最新的值
     */
    public function decr($key, $value = 1, $timeout = 0)
    {
        $flag = $this->oRedis->decr($key, $value);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    /**
     * 重命名某个Key.和renameKey不同: 如果目的key存在将不执行
     * @param String $srcKey
     * @param String $dstKey
     * @return Boolean 源key和目的key相同或者...
     */
    public function renameNx($srcKey, $dstKey)
    {
        return $this->oRedis->renameNx($srcKey, $dstKey);
    }

    /**
     * 设置某个key过期时间.只能设置一次
     * @param String $key
     * @param int $expire 过期秒数
     * @return Boolean
     */
    public function setTimeout($key, $expire)
    {
        return $this->oRedis->setTimeout($key, $expire);
    }

    /**
     * 设置某个key在特定的时间过期
     * @param String $key
     * @param int $timestamp 时间戳
     * @return Boolean
     */
    public function expireAt($key, $timestamp)
    {
        return $this->oRedis->expireAt($key, $timestamp);
    }

    /**
     * List章节 无索引序列 把元素加入到队列左边(头部).如果不存在则创建一个队列
     * @param String $key
     * @param Mixed $value
     * @return Boolean. 如果连接不上或者该key已经存在且不是一个队列
     */
    public function lPush($key, $value, $timeout = 0)
    {
        $flag = $this->oRedis->lPush($key, $value);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    /**
     * 把元素加入到队列右边(尾部)
     * @param String $key
     * @param Mixed $value
     * @return Boolean
     */
    public function rPush($key, $value, $timeout = 0)
    {
        $flag = $this->oRedis->rPush($key, $value);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    /**
     * 弹出队列头部元素
     * @param String $key
     * @return Mixed/false
     */
    public function lPop($key)
    {
        return $this->oRedis->lPop($key);
    }

    /**
     * 阻塞地弹出队列头部元素
     * @param array $key
     * @return Mixed/false
     */
    public function blPop($arrkey, $timeout = 0)
    {
        return $this->oRedis->blPop($arrkey, $timeout);
    }

    // zset 集合
    public function zRevrange($key, $start, $stop, $withscores = false)
    {
        return $this->oRedis->zrevrange($key, $start, $stop, $withscores);
    }

    /**
     * 返回队列里的元素个数.不存在则为0.不是队列则为false
     * @param String $key
     * @return int/false
     */
    public function lSize($key)
    {
        return $this->oRedis->lSize($key);
    }

    /**
     * 控制队列只保存某部分,即:删除队列的其余部分
     * @param String $key
     * @param int $start
     * @param int $end
     * @return Boolean
     */
    public function listTrim($key, $start, $end)
    {
        return $this->oRedis->listTrim($key, $start, $end);
    }

    /**
     * 获取队列的某个元素
     * @param String $key
     * @param int $index 0第一个1第二个...-1最后一个-2倒数第二个
     * @return Mixed/false 没有则为空字符串或者false
     */
    public function lGet($key, $index)
    {
        return $this->oRedis->lGet($key, $index);
    }

    /**
     * 修改队列中指定$index的元素
     * @param String $key
     * @param int $index
     * @param Mixed $value
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Boolean 该$index不存在为false
     */
    public function lSet($key, $index, $value)
    {
        return $this->oRedis->lSet($key, $index, $value);
    }

    /**
     * 取出队列的某一段
     * @param String $key
     * @param String $start 相当于$index:第一个为0...最后一个为-1
     * @param String $end
     * @return Array/Bool
     */
    public function lGetRange($key, $start, $end)
    {
        return $this->oRedis->lGetRange($key, $start, $end);
    }

    /**
     * 删掉队列中的某些值
     * @param String $key
     * @param Mixed $value 要删除的值
     * @param int $count 去掉的个数,>0从左到右去除;0为去掉所有;<0从右到左去除
     * @return Boolean
     */
    public function lRemove($key, $value, $count)
    {
        return $this->oRedis->lRemove($key, $value, $count);
    }

    /**
     * 返回名称为key的list中index位置的元素
     * @param String $key
     * @param String index
     * @return Array
     */
    public function lIndex($key, $index)
    {
        return $this->oRedis->lIndex($key, $index);
    }

    /**
     * 删掉队列中的某个值
     * @param String $key
     * @param Mixed $value 要删除的值
     * @param int index 第同个元素
     * @return Boolean
     */
    public function lRem($key, $value, $index)
    {
        return $this->oRedis->lRem($key, $value, $index);
    }

    /**
     * 给该key添加一个唯一值.相当于制作一个没有重复值的数组
     * @param String $key
     * @param Mixed $value
     * @return Boolean
     */
    public function sAdd($key, $value, $timeout = 0)
    {
        $flag = $this->oRedis->sAdd($key, $value);

        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }

        return $flag;
    }

    /**
     * 获取某key对象个数
     * @param String $key
     * @return int 不存在则为0
     */
    public function sSize($key)
    {
        return $this->oRedis->sSize($key);
    }

    /**
     * 随机弹出一个值.
     * @param String $key
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Mixed/false
     */
    public function sPop($key)
    {
        return $this->oRedis->sPop($key);
    }

    /**
     * 随机取出一个值.与sPop不同,它不删除值(暂不支持)
     * @param String $key
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Mixed/false
     */
    public function sRandMember($key)
    {
        return $this->oRedis->sRandMember($key);
    }

    /**
     * 集合数量
     * @param $key
     * @return int
     */
    public function sCard($key)
    {
        return $this->oRedis->sCard($key);
    }

    /**
     * 返回所给key列表都有的那些值,相当于求交集
     * $keys Array
     * @return Array
     */
    public function sInter($keys, $zip = false, $serial = true)
    {
        if (!$this->connect()) {
            return array();
        }
        $result = call_user_func_array(array($this->oRedis, 'sInter'), $keys);
        $result = is_array($result) ? $result : array();
        foreach ($result as $k => $v) {
            $aList[] = $zip ? unserialize(@gzuncompress($v)) : ($serial ? unserialize($v) : $v);
        }
        return (array)$aList;
    }

    /**
     * 把所给$keys列表都有的那些值存到$key指定的数组中.相当于执行sInter操作然后再存到另一个数组中
     * $key String 要存到的数组key 注意该数组如果存在会被覆盖
     * $keys Array
     * @return int
     */
    public function sInterStore($key, $keys)
    {
        if (!$this->connect()) {
            return 0;
        }
        return call_user_func_array(array($this->oRedis, 'sInterStore'), array_merge(array($key), $keys));
    }

    /**
     * 返回所给key列表所有的值,相当于求并集
     * @param Array $keys
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Array
     */
    public function sUnion($keys, $zip = false, $serial = true)
    {
        if (!$this->connect()) {
            return array();
        }
        $result = call_user_func_array(array($this->oRedis, 'sUnion'), $keys);
        $result = is_array($result) ? $result : array();
        foreach ($result as $k => $v) {
            $aList[] = $zip ? unserialize(@gzuncompress($v)) : ($serial ? unserialize($v) : $v);
        }
        return (array)$aList;
    }

    /**
     * 把所给key列表所有的值存储到另一个数组
     * @param String $key
     * @param Array $keys
     * @return int/false 并集的数量
     */
    public function sUnionStore($key, $keys)
    {
        if (!$this->connect()) {
            return 0;
        }
        return call_user_func_array(array($this->oRedis, 'sUnionStore'), array_merge(array($key), $keys));
    }

    /**
     * 返回所给key列表想减后的集合,相当于求差集
     * @param Array $keys 注意顺序,前面的减后面的
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Array
     */
    public function sDiff($keys, $zip = false, $serial = true)
    {
        if (!$this->connect()) {
            return array();
        }
        $result = call_user_func_array(array($this->oRedis, 'sDiff'), $keys);
        $result = is_array($result) ? $result : array();
        foreach ($result as $k => $v) {
            $aList[] = $zip ? unserialize(@gzuncompress($v)) : ($serial ? unserialize($v) : $v);
        }
        return (array)$aList;
    }

    /**
     * 把所给key列表差集存储到另一个数组
     * @param String $key 要存储的目的数组
     * @param Array $keys
     * @return int/false 差集的数量
     */
    public function sDiffStore($key, $keys)
    {
        if (!$this->connect()) {
            return 0;
        }
        return call_user_func_array(array($this->oRedis, 'sDiffStore'), array_merge(array($key), $keys));
    }

    /**
     * 删除该数组中对应的值
     * @param String $key
     * @param String $value
     * @return Boolean
     */
    public function sRem($key, $value)
    {
        return $this->oRedis->sRem($key, $value);
    }

    /**
     * 删除该数组中对应的值
     * @param String $key
     * @param String $value
     * @return Boolean
     */
    public function sRemove($key, $value)
    {
        return $this->oRedis->sRemove($key, $value);
    }

    /**
     * 把某个值从一个key转移到另一个key
     * @param String $srcKey
     * @param String $dstKey
     * @param Mixed $value
     * @return Boolean 源key不存在/目的key不存在/源值不存在/目的值存在->false
     */
    public function sMove($srcKey, $dstKey, $value)
    {
        return $this->oRedis->sMove($srcKey, $dstKey, $value);
    }

    /*
     *
     * 判断该数组中是否有对应的值
     * @paramString $key
     * @paramString $value
     * @return Boolean
     */

    public function sIsMember($key, $value)
    {
        return $this->oRedis->sIsMember($key, $value);
    }

    /*
 *
 * 判断该数组中是否有对应的值
 * @paramString $key
 * @paramString $value
 * @return Boolean
 */

    public function sContains($key, $value)
    {
        return $this->oRedis->sContains($key, $value);
    }

    /**
     * 获取某数组所有值
     * @param String $key
     * @return Array 顺序是不固定的
     */
    public function sGetMembers($key)
    {
        $result = $this->oRedis->sGetMembers($key);
        return is_array($result) ? $result : array();
    }

    // 获取 集合
    public function sMembers($key)
    {
        $result = $this->oRedis->sMembers($key);
        return $result;
    }

    /**
     * 添加一个指定了下标的数组单元(默认的数组下标从0开始)
     * @param $key
     * @param $score
     * @param $value
     * @param int $timeout
     * @return int
     */
    public function zAdd($key, $score, $value, $timeout = 0)
    {
        $flag = $this->oRedis->zAdd($key, $score, $value);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    /**
     * 获取指定单元的数据
     * @param String $key
     * @param int $start
     * @param int $end
     * @param Boolean $withscores 是否返回索引值.如果是则返回值=>索引的数组
     * @return Mixed
     */
    public function zRange($key, $start, $end, $withscores = false)
    {
        return $this->oRedis->zRange($key, $start, $end, $withscores);
    }

    /**
     * 获取指定单元的反序排列的数据
     * @param String $key
     * @param int $start
     * @param int $end
     * @param Boolean $withscores 是否返回索引值.如果是则返回值=>索引的数组
     * @return Mixed
     */
    public function zReverseRange($key, $start, $end, $withscores = false)
    {
        return $this->oRedis->zReverseRange($key, $start, $end, $withscores);
    }

    /**
     * 从key对应的有序集合中删除给定的成员
     *
     * @param String $key
     * @param int|string $member 数组下标
     */
    public function zRem($key, $member)
    {
        return $this->oRedis->zRem($key, $member);
    }

    public function zRemRangeByRank($key, $start, $end)
    {
        return $this->oRedis->zRemRangeByRank($key, $start, $end);
    }

    public function zRangeByScore($key, $min, $max)
    {
        return $this->oRedis->zRangeByScore($key, $min, $max);
    }

    public function zCount($key, $start, $end)
    {
        return $this->oRedis->zCount($key, $start, $end);
    }

    public function zDeleteRangeByScore()
    {

    }

    public function zSize()
    {

    }

    public function zCard($key)
    {
        return $this->oRedis->zCard($key);
    }

    /**
     * 返回有序集key中，成员member的score值
     *
     * @param String $key
     * @param int|string $index 数组下标
     */
    public function zScore($key, $index)
    {
        return $this->oRedis->zScore($key, $index);
    }

    /**
     *
     * 返回有序集key中成员member的排名，其中有序集成员按score值从大到小排列。排名以0为底，也就是说，score值最大的成员排名为0。
     * @param string $key
     * @param int|string $index
     */
    public function zRevRank($key, $index)
    {
        return $this->oRedis->zRevRank($key, $index);
    }

    /**
     * 为有序集key的成员member的score值加上增量increment
     * 你也可以通过传递一个负数值increment，让score减去相应的值
     */
    public function zIncrBy($key, $increment, $index, $timeout = 0)
    {
        $flag = $this->oRedis->zIncrBy($key, $increment, $index);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag;
    }

    public function zUnion()
    {

    }

    public function zInter()
    {

    }

    /**
     * 以下為HASH操作相關
     * @param $hashname
     * @param $key1 ....
     * @param $value1 ....
     * @return LONG 1 if value didn't exist and was added successfully, 0 if the value was already present and was replaced, FALSE if there was an error.
     * 向名稱為hashkey中添加key1->value1 将哈希表key中的域field的值设为value。如果key不存在，一个新的哈希表被创建并进行hset操作。如果域field已经存在于哈希表中，旧值将被覆盖。
     */
    public function hSet($key, $index, $value, $timeout = 0)
    {
        $flag = $this->oRedis->hSet($key, $index, $value);
        if ($flag && $timeout) {
            $this->oRedis->setTimeout($key, $timeout);
        }
        return $flag === false ? false : true;
    }

    /**
     * 從名稱為haskey中獲得key為$key的值
     * @param $hashname
     * @param $key
     */
    public function hGet($key, $index)
    {
        return $this->oRedis->hGet($key, $index);
    }

    /**
     * 返回名稱為hashname中的元素個數
     * @param $hashname
     */
    public function hLen($hashname)
    {
        return $this->oRedis->hLen($hashname);
    }

    /**
     * 刪除hash中鍵名為key的域
     *
     * @param unknown_type $hashname
     * @param unknown_type $key
     */
    public function hDel($hashname, $key)
    {
        return $this->oRedis->hDel($hashname, $key);
    }

    /**
     * 返回hash中所有的鍵名
     * @param $hashname
     */
    public function hKeys($hashname)
    {
        if (!$this->connect()) {
            return array();
        }
        return $this->oRedis->hKeys($hashname);
    }

    /**
     * 返回hash中所有對應的value
     * @param $hashname
     */
    public function hVals($hashname)
    {
        if (!$this->connect()) {
            return array();
        }
        return $this->oRedis->hVals($hashname);
    }

    /**
     * 名稱hashname中是否存在鍵名為key的域
     * @param $hashname
     * @param $key
     */
    public function hExists($hashname, $key)
    {
        return $this->oRedis->hExists($hashname, $key);
    }

    /**
     * 增加 key 指定的哈希集中指定字段的数值
     * @param $hashname
     * @param $key
     * @param $val 操作值
     */
    public function hIncrBy($hashname, $key, $val)
    {
        return $this->oRedis->hIncrBy($hashname, $key, $val);
    }

    /**
     * 批量獲取hash中鍵名對應的值
     * @param $hashname
     * @param array ('key1','key2')
     */
    public function hMget($hashname, $arrkeys)
    {
        return $this->oRedis->hMget($hashname, $arrkeys);
    }

    /**
     * 向hash中批量添加元素
     * @param $hashname
     * @param array (key1=>value1,key2=>value2.........)
     */
    public function hMset($hashname, $arr)
    {
        $flag = $this->oRedis->hMset($hashname, $arr);
        return $flag;
    }

    /**
     * 返回名称为h的hash中所有键值对
     * @param String $hashname
     * @return array
     */
    public function hGetAll($hashname)
    {
        return $this->oRedis->hGetAll($hashname);
    }

    /**
     * 删除对应的值
     * @param String $key
     * @param Mixed $value
     */
    public function zDelete($key, $value, $zip = false, $serial = true)
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->zDelete($key, $value);
    }

    /**
     * 移除KEY的过期时间
     * @param String $key
     */
    public function persist($key)
    {
        if (!$this->connect()) {
            return false;
        }

        return $this->oRedis->persist($key);
    }

    /**
     * 返回服务器信息
     * @return Array
     */
    public function info()
    {
        return $this->oRedis->info();
    }

    /**
     * 返回某key剩余的时间.单位是秒
     * @param String $key
     * @return int/false -1为没有设置过期时间
     */
    public function ttl($key)
    {
        return $this->oRedis->ttl($key);
    }

    /**
     * 批量设置
     * @param Array $pairs 索引数组,索引为key,值为...
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Boolean
     */
    public function mset($pairs)
    {
        return $this->oRedis->mset($pairs);
    }

    /**
     * 从源队列尾部弹出一项加到目的队列头部.并且返回该项
     * @param String $srcKey
     * @param String $dstKey
     * @param Boolean $zip
     * @param Boolean $serial
     * @return Mixed/false
     */
    public function rpoplpush($srcKey, $dstKey, $zip = false, $serial = true)
    {
        if (!$this->connect()) {
            return false;
        }
        $result = $this->oRedis->rpoplpush($srcKey, $dstKey);
        return $result === false ? $result : ($zip ? unserialize(@gzuncompress($result)) : ($serial ? unserialize($result) : $result));
    }

    /**
     * 判断key是否存在
     * @param String $key
     * @return Boolean
     */
    public function exists($key)
    {
        return $this->oRedis->exists($key); //BOOL: If the key exists, return TRUE, otherwise return FALSE.
    }

    /**
     * 获取符合匹配的key.仅支持正则中的*通配符.如->getKeys('*')
     * @param String $pattern
     * @return Array/false
     */
    public function getKeys($pattern)
    {
        return $this->oRedis->getKeys($pattern);
    }

    /**
     * 删除某key/某些key
     * @param String /Array $keys
     * @return int 被删的个数
     */
    public function del($keys)
    {
        $keys = (array)$keys;
        return $this->oRedis->del($keys);
    }

    /**
     * 返回当前key数量
     * @return int
     */
    public function dbSize()
    {
        if (!$this->connect()) {
            return 0;
        }
        return $this->oRedis->dbSize();
    }

    /**
     * 密码验证.密码明文传输
     * @param String $password
     * @return Boolean
     */
    public function auth($password)
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->auth($password);
    }

    /**
     * 强制把内存中的数据写回硬盘
     * @return Boolean 如果正在回写则返回false
     */
    public function save()
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->save();
    }

    /**
     * 执行一个后台任务: 强制把内存中的数据写回硬盘
     * @return Boolean 如果正在回写则返回false
     */
    public function bgsave()
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->bgsave();
    }

    /**
     * 返回最后一次写回硬盘的时间
     * @return int 时间戳
     */
    public function lastSave()
    {
        if (!$this->connect()) {
            return 0;
        }
        return $this->oRedis->lastSave();
    }

    /**
     * 返回某key的数据类型
     * @param String $key
     * @return int 存在于: REDIS_* 中
     */
    public function type($key)
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->type($key);
    }

    /**
     * 清空当前数据库.谨慎执行
     * @return Boolean
     */
    public function flushDB()
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->flushDB();
    }

    /**
     * 清空所有数据库.谨慎执行
     * @return Boolean
     */
    public function flushAll()
    {
        if (!$this->connect()) {
            return false;
        }
        return $this->oRedis->flushAll();
    }

    /**
     * 获取连接信息
     * @return String
     */
    public function ping()
    {
        $this->connect();

        return $this->oRedis->ping();
    }

    // 获取所有键
    public function keys($key)
    {
        return $this->oRedis->keys($key);
    }

}
