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
	const SupperID 		= 10007;	//超端
	const EmailID 		= 10008;	//邮件
	const TurntableID 	= 10009;	//抽奖
	const ShareID 		= 10010;	//分享
	const EggID 		= 10011;	//砸蛋	
	const AgentID		= 10013;
	const UserID 		= 10017;	//用户邀请
	const BindPhoneID   = 10016;        //绑定手机微信号获得领金币 
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
	

	function makeSendAgent($userID,$resourceType,$send){
		$packet = '';
		$packet.=pack('i',$userID);
		$packet.=pack('i',$resourceType);
		$packet.=pack('i',$send);
		return $packet;
	}
  
       	         // 绑定手机
        function makeBindPhone($userid,$type,$number,$phone){
           $packet = "";
           $packet.= pack('i',$userid);
           $packet.= pack('i',$type);
           $packet.= pack('i',$number);
	   $packet.= pack('a24',$phone);
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
	function makeEmailPacket($senderID,$sendName,$titleName,$senderPhoto,$content){
		$packet = "";
		$packet.= pack('i',$senderID);
		$packet.= pack('a64',$sendName);
		$packet.= pack('a64',$titleName);
		$packet.= pack('a256',$senderPhoto);
		$packet.= pack('a1024',$content);
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
	function makeEggPacket($userID,$resourceType,$number,$costMoney,$phone){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$resourceType);
		$packet.= pack('i',$number);
		$packet.= pack('i',$costMoney);
		$packet.= pack('C',$phone);
		return $packet;
	}
}
?>
