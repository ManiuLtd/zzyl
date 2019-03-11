<?php
// 缓存写入
namespace API\Controller;

use Think\Controller;

class WriteCacheController extends Controller
{
    public function index()
    {
        $this->otherConfig();
        $this->privatedeskconfig();
        $this->roomBaseInfo();
        $this->logonBaseInfo();
    }

    // 配置信息
    public function otherConfig()
    {
        $count = count($this->commonRedis('keys', ['otherConfig|*']));

        if ($count > 1) {
            return;
        }

        $otherConfig = M()->table('otherConfig')->select();
        $config      = [];
        foreach ($otherConfig as $v) {
            $config[$v['keyconfig']] = $v['valueconfig'];
        }

        $this->commonRedis('hmset', ['otherConfig', $config]);

    }

    // 牌桌信息
    public function privatedeskconfig()
    {
        $count = count($this->commonRedis('keys', ['privateDeskConfig|*']));

        if ($count > 1) {
            return;
        }

        $deskconfig = M()->table('privateDeskConfig')->select();

        foreach ($deskconfig as $v) {
            $res = $this->commonRedis('hmset', ['privateDeskConfig|' . $v['gameid'] . ',' . $v['count'] . ',' . $v['roomtype'],
                [
                    'count'         => $v['count'],
                    'roomType'      => $v['roomtype'],
                    'costResType'   => $v['costrestype'],
                    'costNums'      => $v['costnums'],
                    'AAcostNums'    => $v['aacostnums'],
                    'otherCostNums' => $v['othercostnums'],
                    'peopleCount'   => $v['peoplecount'],
                ],
            ]);
        }
    }

    // 房间信息
    public function roomBaseInfo()
    {

        $count = count($this->commonRedis('keys', ['roomBaseInfo|*']));

        if ($count > 1) {
            return;
        }

        $roomBaseInfo = M()->table('roomBaseInfo')->select();

        foreach ($roomBaseInfo as $v) {
            $res = $this->commonRedis('hmset', ['roomBaseInfo|' . $v['gameid'] . ',' . $v['type'],
                [
                    'gameID'             => $v['gameid'],
                    'name'               => $v['name'],
                    'ip'                 => $v['ip'],
                    'port'               => $v['port'],
                    'serviceName'        => $v['servicename'],
                    'type'               => $v['type'],
                    'sort'               => $v['sort'],
                    'deskCount'          => $v['deskcount'],
                    'maxPeople'          => $v['maxpeople'],
                    'minPoint'           => $v['minpoint'],
                    'maxPoint'           => $v['maxpoint'],
                    'tax'                => $v['tax'],
                    'basePoint'          => $v['basepoint'],
                    'gameBeginCostMoney' => $v['gamebegincostmoney'],
                    'describe'           => $v['describe'],
                    'roomSign'           => $v['roomsign'],
                ],
            ]);
        }
    }

    // 网关信息
    public function logonBaseInfo()
    {
        $count = count($this->commonRedis('keys', ['logonBaseInfo|*']));

        if ($count > 1) {
            return;
        }

        $logonBaseInfo = M()->table('logonBaseInfo')->select();

        foreach ($logonBaseInfo as $v) {
            $res = $this->commonRedis('hmset', ['logonBaseInfo|' . $v['loginID'],
                [
                    'loginID'   => $v['loginid'],
                    'ip'        => $v['ip'],
                    'port'      => $v['port'],
                    'maxPeople' => $v['maxpeople'],
                    'curPeople' => $v['curpeople'],
                    'status'    => $v['status'],
                ],
            ]);
        }
    }

}
