<?php
//封装消息体 
class SendFunction {
	//定义消息ID
	const NoticeID		= 10001;	//公告
	const SignID 		= 10002;	//签到
	const ConverID 		= 10003;	//交换
	const RechargeID 	= 10004;	//充值
	const PosID 		= 10005;	//提现
	const PosBackID 	= 10006;	//提现返回
//	const SupperID 		= 10007;	//超端
	const EmailID 		= 10008;	//邮件
	const TurntableID 	= 10009;	//抽奖
	const ShareID 		= 10010;	//分享
	const EggID 		= 10011;	//砸蛋
	const DelNoticeID 	= 10012;	//删除公告
	const IdentUserID 	= 10007;	//用户身份
	const StopSaveDbID      = 10014; // 停服保存数据
        const StartOpenDbID     = 10015; // 开服恢复数据
        const AddAgentID        = 10016; // 添加代理
	
	    // 俱乐部操作
    	const CLUBDEL_MEMID  = 10018; // 删除成员
    	const CLUBAUTH_MEMID = 10019; // 授权和取消管理员
    	const CLUBFIRECOINID = 10020; // 充值以及兑换火
        const CLUB_HOT       = 10021; // 俱乐部热度


	//签到
	//	int userID;				// 玩家ID
	//	int resourceType;		// 资源类型    
	//	int changeValue;		// 资源的值    

	function sign($userID,$resourceType,$changeValue){
		$packet = '';
		$packet.=pack('i',$userID);
		$packet.=pack('i',$resourceType);
		$packet.=pack('i',$changeValue);
		return $packet;
	}

	function makeTurntablepacket($userID,$resourceType,$num){
		$packet = '';
		$packet.=pack('i',$userID);
		$packet.=pack('i',$resourceType);
		$packet.=pack('i',$num);
		return $packet;
	}
	
	//公告
  /*int		ID;				// 公告ID
	char	content[1024];	// 内容
	int		interval;		// 间隔
	int		times;			// 次数
	int		beginTime;		// 开始时间
	int		endTime;		// 结束时间
	int		type;			// 类型 (1:普通 2：特殊)*/
	function makeNoticePacket($id,$content,$interval,$times,$beginTime,$endTime,$type)
	{		
		$packet = "";
		$packet .= pack('i', $id);
		$packet .= pack('a1024', $content);
		$packet .= pack('i', $interval);
		$packet .= pack('i', $times);
		$packet .= pack('i', $beginTime);
		$packet .= pack('i', $endTime);
		$packet .= pack('i', $type);
		return $packet;
	}

	//删除公告
	function makeDelNoticePacket($noticeid){
		$packet = '';
		$packet .= pack('i',$noticeid);
		return $packet;
	}
	//交换
	function makeConverPacket($userID,$transNum,$transRatio,$transType)
	{
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$transNum);
		$packet.= pack('i',$transRatio);
		$packet.= pack('C',$transType);
		return $packet;
	}	

	//充值
	function makeRechargePacket($userID,$amount,$type){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$amount);
		$packet.= pack('C',$type);
		return $packet;
	}

	//提现   冻结操作
	function makePosPacket($userID,$amount,$type){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$amount);
		$packet.= pack('C',$type);
		return $packet;
	}
	//提现   拒绝返回
	function makePosBackPacket($userID,$status){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('C',$status);
		return $packet;		
	}

		//超端  
	function makeSupperPacket($userID,$status){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('C',$status);
		return $packet;		
	}

	//发送邮件
	function makeEmailPacket($senderID,$sendName,$titleName,$senderPhoto,$content,$recverID){
		$packet = "";
		$packet.= pack('i',$senderID);
		$packet.= pack('a64',$sendName);
		$packet.= pack('a64',$titleName);
		$packet.= pack('a256',$senderPhoto);
		$packet.= pack('a1024',$content);
		$packet.= pack('i',$recverID);
		return $packet;
	} 

	//分享
	function makeSharePacket($userID,$resourceType,$number){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$resourceType);
		$packet.= pack('i',$number);
		return $packet;
	}

	//砸蛋
	function makeEggPacket($userID,$resourceType,$number,$costMoney){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$resourceType);
		$packet.= pack('i',$number);
		$packet.= pack('i',$costMoney);
		return $packet;
	}


       
/**
	 * 设置用户身份
	 * @DateTime 2017-11-27
	 * @param    [INT]			$userID      [description]
	 * @param    [BYTE]			$type        [description]
	 * @param    [BYTE]			$statusValue [description]
	 * @param    [INT]			$otherValue  [description]
	 * @return   [Resources]	packet       [description]
	 */
	function makeIdentPacket($userID,$type,$statusValue,$otherValue){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('C',$type);
		$packet.= pack('C',$statusValue);
		$packet.= pack('i',$otherValue);
		return $packet;		
	}

     // 后台添加代理
    // 用户ID 类型:0 添加 1 删除
    public function makeAddAgent($userID, $type)
    {
        $packet = "";
        $packet .= pack('i', $userID);
        $packet .= pack('i', $type);
        return $packet;
    }

    // 充值以及兑换
    // 俱乐部id 用户id > 0 充值 < 0 兑换
    public function makeClubRecharge($friendsGroupID, $userID, $number)
    {
        $packet = "";
        $packet .= pack('i', $friendsGroupID);
        $packet .= pack('i', $userID);
        $packet .= pack('i', $number);
        return $packet;
    }

    // 删除俱乐部用户
    //struct PlatformFGDelMem
    // {
    //     int friendsGroupID;     //俱乐部id
    //     int userID;
    // };
    public function makeDelClubUser($friendsGroupID, $userID)
    {
	//var_dump($friendsGroupID);
	//var_dump($userID);die;
        $packet = "";
        $packet .= pack('i', $friendsGroupID);
        $packet .= pack('i', $userID);
        return $packet;
    }

    // //授权和取消管理员
    // struct PlatformFGAuthMem
    // {
    //     int friendsGroupID;     //俱乐部id
    //     int userID;
    //     int type;               //0：授权，1：取消
    // };
    public function makeClubAdministrators($friendsGroupID, $userID, $type)
    {
        $packet = "";
        $packet .= pack('i', $friendsGroupID);
        $packet .= pack('i', $userID);
        $packet .= pack('i', $type);
        return $packet;
    }

        public function makeServerMessage($type, $ip)
    {
        $packet = "";
        $packet .= pack('i', $type);
        $packet .= pack('a24', $ip);
        return $packet;
    }

        // 俱乐部热度 发送消息
    public function makeClubModify($friendsGroupID, $hot)
    {
        $packet = "";
        $packet .= pack('i', $friendsGroupID);
        $packet .= pack('i', $hot);
        return $packet;
    }

}
?>
