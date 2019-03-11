<?php 
namespace model;
use config\EnumConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;

/**
 * 支付模块
 * Class PayModel
 */
class PayModel extends AppModel
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    //获得商品列表
    public function getGoodsList($goodsType)
    {
        $sql = "select buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,goodsID,is_New,is_Hot from "
            . MysqlConfig::Table_web_pay_goods
            . " where status =1 and buyType=" . $goodsType;
        $result = DBManager::getMysql()->queryAll($sql);
        $intKeyArray = array(
            'buyNum',
            'buyType',
            'consumeNum',
            'consumeType',
            'goodsID',
            'is_New',
            'is_Hot',
        );
        //转换int类型
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    //获得订单列表
    public function getOrdersList($userID)
    {
        $sql = "select * from " . MysqlConfig::Table_web_pay_orders
            . " where userID=" . $userID;
        $result = DBManager::getMysql()->queryAll($sql);
        $intKeyArray = array(
            'buyNum',
            'buyType',
            'consumeNum',
            'consumeType',
            'goodsID',
            'is_New',
            'is_Hot',
        );
        //转换int类型
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    //获得有效订单列表
    public function getPayOrdersList($userID)
    {
        $status = EnumConfig::E_OrderStatus['NEW'];
        $sql = "select * from " . MysqlConfig::Table_web_pay_orders
            . " where status <>{$status} and userID=" . $userID;
        $result = DBManager::getMysql()->queryAll($sql);
        $intKeyArray = array(
            'buyNum',
            'buyType',
            'consumeNum',
            'consumeType',
            'goodsID',
            'is_New',
            'is_Hot',
        );
        //转换int类型
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    public function getGoodsId($goodsID)
    {
        $sql = "select * from " . MysqlConfig::Table_web_pay_goods
            . " where goodsID='$goodsID'";
        $result = DBManager::getMysql()->queryRow($sql);
        $intKeyArray = array(
            'id',
            'buyNum',
            'buyType',
            'consumeNum',
            'consumeType',
            'buyCount',
            'create_time',
            'status',
            'goodsID',
            'is_New',
            'is_Hot',
            'picName',
        );
        //转换int类型
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    public function getPayOrder($order_sn)
    {
        $sql = "select * from " . MysqlConfig::Table_web_pay_orders
            . " where order_sn='{$order_sn}'";
        $result = DBManager::getMysql()->queryRow($sql);
        $intKeyArray = array(
            'id',
            'userID',
            'buyNum',
            'buyType',
            'consumeNum',
            'consumeType',
            'status',
            'create_time',
            'handle',
            'handleTime',
        );
        //转换int类型
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }


    public function updatePayOrderStatus($order_sn, $status)
    {
        $arrayDataValue = [
            'status' => $status,
            'handleTime' => time(),
        ];
        $where = "order_sn='{$order_sn}'";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_pay_orders, $arrayDataValue, $where);
    }

    public function addOrder($goods, $userID, $order_sn, $desc = '')
    {
        $status = EnumConfig::E_OrderStatus['NEW'];
        $create_time = time();
        $arrayData = array(
            "order_sn" => $order_sn,
            "userID" => $userID,
            "buyGoods" => $goods['buyGoods'],
            "buyNum" => $goods['buyNum'],
            "buyType" => $goods['buyType'],
            "consumeGoods" => $goods['consumeGoods'],
            "consumeNum" => $goods['consumeNum'],
            "consumeType" => $goods['consumeType'],
            "status" => $status,
            "create_time" => $create_time,
            "pay_desc" => $desc,
        );
        //订单统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'makeOrderCount');
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_pay_orders, $arrayData);
    }

    public function getPayConfig($type)
    {
        $sql = "select * from " . MysqlConfig::Table_web_pay_config
            . " where type={$type} and status=" . EnumConfig::E_PayTypeStatus['OPEN'];
        $result = DBManager::getMysql()->queryRow($sql);
        $intKeyArray = array(
            'type',
            'status',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    public function getPayTypeList($status, $third)
    {
        $sql = "select type from " . MysqlConfig::Table_web_pay_config
            . " where status={$status} and third = {$third}";
        $result = DBManager::getMysql()->queryAll($sql);
        $intKeyArray = array(
            'type',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }
}
