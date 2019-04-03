<?php 
namespace helper;

/**
 * DB 帮助类
 * Class PDOHelper
 */
final class DBHelper
{
    //数据源名称
    private $dsn;
    //数据库类型
    private $dbtype;
    //用户
    private $username;
    //密码
    private $password;
    //编码
    private $charset;
    private $pdo = null;

    public function __construct($dbtype, $host, $dbname, $port, $username, $password, $charset = 'UTF-8', $pconnect = false)
    {
        $this->dsn = "{$dbtype}:host={$host};dbname={$dbname};port={$port}";
        $this->dbtype = $dbtype;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
        $this->pconnect = $pconnect;
        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * 连接数据库
     * @return bool
     */
    private function connect()
    {
        try {
            $this->pdo = new \PDO($this->dsn, $this->username, $this->password
                , array(
                    \PDO::ATTR_PERSISTENT => $this->pconnect,
                )
            );
            $this->pdo->exec('SET character_set_connection=' . $this->charset . ', character_set_results=' . $this->charset . ', character_set_client=binary');
        } catch (PDOException $e) {
            //抛出异常，并记录错误日志
            FunctionHelper::Err("DB Connection failed:", 500, 0, ['DB Connection failed: ' . $e->getMessage(), $this->dsn, $this->username, $this->password]);
        }
        return true;
    }

    /**
     * 检查数据库连接,是否有效，无效则重新建立
     */
    public function checkConnect()
    {
        if (!$this->pdo) {
            return $this->connect();
        }
        $result = $this->pdo->getAttribute(\PDO::ATTR_SERVER_INFO);;
        if (!$result) {
            $this->close();
            return $this->connect();
        }
        return true;
    }

    /**
     * 关闭数据库连接
     */
    public function close()
    {
        $this->pdo = null;
    }

    /**
     * exec sql语句
     * @param $strSql
     * @return mixed
     */
    public function execSql($strSql)
    {
        $this->log($strSql);
        $result = $this->pdo->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * query 查询所有
     * @param $strSql
     * @return mixed|array
     */
    public function queryAll($strSql)
    {
        $this->log($strSql);
        $record_set = $this->pdo->query($strSql);
        $this->getPDOError();
        $result = [];
        if ($record_set) {
            $record_set->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $record_set->fetchAll();
        }
        return $result;
    }

    /**
     * query 查询一行
     * @param $strSql
     * @return mixed|array
     */
    public function queryRow($strSql)
    {
        $this->log($strSql);
        $record_set = $this->pdo->query($strSql);
        $this->getPDOError();
        $result = [];
        if ($record_set) {
            $record_set->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $record_set->fetch();
        }
        return $result;
    }

    /**
     * update 更新表数据
     * @param $tableName
     * @param $arrayDataValue
     * @param string $where 不传更新所有
     * @return mixed
     */
    public function update($tableName, $arrayDataValue, $where = '')
    {
        $this->checkFields($tableName, $arrayDataValue);
        FunctionHelper::arrayStringToDB($arrayDataValue);
        $strSql = '';
        foreach ($arrayDataValue as $key => $value) {
            $strSql .= ", `$key`='$value'";
        }
        $strSql = substr($strSql, 1);
        if ($where == '') {
            $strSql = "UPDATE `$tableName` SET $strSql";
        } else {
            $strSql = "UPDATE `$tableName` SET $strSql WHERE $where";
        }
        $this->log($strSql);
        $result = $this->pdo->exec($strSql);
        LogHelper::printLog('PROMOTION', '保底sql'.$strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * insert 插入数据
     * @param $tableName
     * @param $arrayDataValue
     * @return mixed
     */
    public function insert($tableName, $arrayDataValue)
    {
        $this->checkFields($tableName, $arrayDataValue);
        FunctionHelper::arrayStringToDB($arrayDataValue);
//        if (false) {
        //record sql
        $strSql = "INSERT INTO `$tableName` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        $this->log($strSql);
//            $result = $this->pdo->exec($strSql);
//        } else {
        //use pdo
        $strSql = "INSERT INTO `$tableName` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES (" . implode(",", array_fill(0, count($arrayDataValue), '?')) . ")";
        $result = $this->pdo->prepare($strSql)->execute(array_values($arrayDataValue));
//        }
        $this->getPDOError();
        return $result;
    }

    /**
     * replace 覆盖方式插入
     * @param $tableName
     * @param $arrayDataValue
     * @return mixed
     */
    public function replace($tableName, $arrayDataValue)
    {
        $this->checkFields($tableName, $arrayDataValue);
        FunctionHelper::arrayStringToDB($arrayDataValue);
        $strSql = "REPLACE INTO `$tableName`(`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        $result = $this->pdo->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * delete 删除
     * @param $tableName
     * @param string $where 不传删除所有
     * @return mixed
     */
    public function delete($tableName, $where = '')
    {
        if ($where == '') {
            $strSql = "DELETE FROM `$tableName`";
        } else {
            $strSql = "DELETE FROM `$tableName` WHERE $where";
        }
        $this->log($strSql);
        $result = $this->pdo->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * select 查询数据 多条
     * @param $tableName
     * @param $arrayKeyValue
     * @param string $where 不传查询所有
     * @return mixed
     */
    public function selectAll($tableName, $arrayKeyValue = [], $where = '')
    {
        $this->checkFields($tableName, $arrayKeyValue);
        $strSql = '';
        if (!empty($arrayKeyValue)) {
            foreach ($arrayKeyValue as $key => $value) {
                $strSql .= ", `$value`";
            }
            $strSql = substr($strSql, 1);
        } else {
            $strSql = '*';
        }
        if ($where == '') {
            $strSql = "SELECT $strSql FROM `$tableName`";
        } else {
            $strSql = "SELECT $strSql FROM `$tableName` WHERE $where";
        }
        return $this->queryAll($strSql);
    }

    /**
     * select 查询数据 一条
     * @param $tableName
     * @param $arrayKeyValue
     * @param string $where 不传查询所有
     * @return mixed
     */
    public function selectRow($tableName, $arrayKeyValue = [], $where = '')
    {
        $this->checkFields($tableName, $arrayKeyValue);
        $strSql = '';
        if (!empty($arrayKeyValue)) {
            foreach ($arrayKeyValue as $key => $value) {
                $strSql .= ", `$value`";
            }
            $strSql = substr($strSql, 1);
        } else {
            $strSql = '*';
        }
        if ($where == '') {
            $strSql = "SELECT $strSql FROM `$tableName`";
        } else {
            $strSql = "SELECT $strSql FROM `$tableName` WHERE $where";
        }
        return $this->queryRow($strSql);
    }

    /**
     * max 获取字段最大值
     * @param $tableName
     * @param $fieldName
     * @param string $where
     * @return int
     */
    public function getMaxValue($tableName, $fieldName, $where = '')
    {
        $strSql = "SELECT MAX(" . $fieldName . ") AS MAX_VALUE FROM $tableName";
        if ($where != '') {
            $strSql .= " WHERE $where";
        }
        $arrTemp = $this->queryRow($strSql);
        $maxValue = $arrTemp["MAX_VALUE"];
        if ($maxValue == null || $maxValue == "") {
            $maxValue = 0;
        }
        return $maxValue;
    }

    /**
     * 获取指定列的数量
     * @param $tableName
     * @param $fieldName
     * @param string $where
     * @return mixed
     */
    public function getCount($tableName, $fieldName, $where = '')
    {
        $strSql = "SELECT COUNT($fieldName) AS NUM FROM $tableName";
        if ($where != '') $strSql .= " WHERE $where";
        $arrTemp = $this->queryRow($strSql);
        return $arrTemp['NUM'];
    }

    /**
     * 获取表引擎
     * @param $dbName
     * @param $tableName
     * @return mixed
     */
    public function getTableEngine($dbName, $tableName)
    {
        $strSql = "SHOW TABLE STATUS FROM $dbName WHERE Name='" . $tableName . "'";
        $arrayTableInfo = $this->queryAll($strSql);
        $this->getPDOError();
        return $arrayTableInfo[0]['Engine'];
    }

    /**
     * 检查指定字段是否在指定数据表中存在
     * @param $tableName
     * @param $arrayFields
     */
    private function checkFields($tableName, $arrayFields)
    {
        $fields = $this->getFields($tableName);
        foreach ($arrayFields as $key => $value) {
            if (is_string($key)) {
                if (!in_array($key, $fields)) {
                    $this->outputError("Unknown column `$key` in field list.");
                }
            } else if (is_string($value)) {
                if (!in_array($value, $fields)) {
                    $this->outputError("Unknown column `$value` in field list.");
                }
            }
        }
    }

    /**
     * 获取指定数据表中的全部字段名
     * @param $tableName
     * @return array
     */
    private function getFields($tableName)
    {
        $fields = array();
        $record_set = $this->pdo->query("SHOW COLUMNS FROM $tableName");
        if (!$record_set) {
            LogHelper::printError('SHOW COLUMN query fail');
        }
        $this->getPDOError();
        $record_set->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $record_set->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }

    /**
     * beginTransaction 事务开始
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * commit 事务提交
     */
    public function commit()
    {
        $this->pdo->commit();
    }

    /**
     * rollback 事务回滚
     */
    public function rollback()
    {
        $this->pdo->rollback();
    }

    /**
     * transaction 事务处理
     * 调用前需通过getTableEngine判断表引擎是否支持事务
     * @param $arraySql
     * @return bool
     */
    public function execTransaction($arraySql)
    {
        $ok = true;
        $this->beginTransaction();
        foreach ($arraySql as $key => $strSql) {
            if ($this->execSql($strSql) == 0) {
                $ok = false;
                $this->getPDOError();
            }
        }
        if ($ok) {
            $this->commit();
            return true;
        } else {
            $this->rollback();
            return false;
        }
    }

    public function insertID()
    {
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * 捕获PDO错误信息
     */
    private function getPDOError()
    {
        if ($this->pdo->errorCode() != '00000') {
            $arrayError = $this->pdo->errorInfo();
            $this->outputError($arrayError);
        }
    }

    private function outputError($msg)
    {
        LogHelper::printError($msg);
    }

    private function log($msg)
    {
        LogHelper::printLog(strtoupper($this->dbtype), $msg);
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function makeWhere($arrWhere){
        $where = [];
        if (empty($arrWhere)) {
            return '';
        }
        foreach ($arrWhere as $k => $v) {
            if (is_string($v) || is_numeric($v)) {
                $where[] = $k . '="' . $v . '"';
            } elseif (is_array($v)) {
                switch ($v[0]) {
                    case 'gt':
                        $where[] = $k . '>' . $v[1];
                        break;
                    case 'lt':
                        $where[] = $k . '<' . $v[1];
                        break;
                    case 'egt':
                        $where[] = $k . '>=' . $v[1];
                        break;
                    case 'lgt':
                        $where[] = $k . '<=' . $v[1];
                        break;
                    case 'between':
                        $where[] = $k . '>=' . $v[1][0];
                        $where[] = ' AND ' . $k . '<=' . $v[1][1];
                        break;
                }
            }
        }
        $res = ' where ' . implode(' and ', $where);
        return $res;
    }
    public function makePdoWhere($arrWhere){

    }
}