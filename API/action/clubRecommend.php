<?php
// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);
// joinStatus 状态 1 在里面 2 申请 3 没有
//俱乐部推荐数据

if (isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])) {
    $userID = $_REQUEST['userID'];
    $sql    = "select TOP 10 friendsGroupID,peopleCount,name,masterID from friendsGroup order by hotCount DESC,peopleCount DESC";
    $club   = $db->getAll($sql);
    foreach ($club as $k => &$v) {
        $v['peopleCount'] = (int) $v['peopleCount'];
        $v['friendsGroupID'] = (int) $v['friendsGroupID'];
        $v['masterID']       = (int) $v['masterID'];
        // 是否在俱乐部 默认不在
        $v['joinStatus'] = 3;

        // 在不在
        $sql = "select * from friendsGroupToUser where friendsGroupID={$v['friendsGroupID']} and userID = {$userID}";

        $find = $db->getRow($sql);
        if ($find) {
            $v['joinStatus'] = 1;
        }

        // 是否申请
        $sql = "select * from friendsGroupNotify where targetFriendsGroupID={$v['friendsGroupID']} and param1 = {$userID} and type=1";

        $isJoin = $db->getRow($sql);
        if ($isJoin) {
            $v['joinStatus'] = 2;
        }
    }

    $data['status']        = 1;
    $dat['msg']            = '请求成功';
    $data['clubRecommend'] = $club;
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
} else {
    $data = ['status' => 0, 'msg' => '缺少参数'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
