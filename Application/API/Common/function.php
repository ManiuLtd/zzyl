<?php
/**
 * 判断是否登录
 */
function is_login(){
    if(session('admin_user_id')){
        return session('admin_user_id');
    }else{
        return false;
    }
}
/**
 * 记录操作日志
 */
function operation_record($uid,$_action){
	$data = [
		'uid'		=>	$uid,
		'_action'	=>	$_action,
		'_time'		=>	time(),	
	];
	M('adminlog')->add($data);
}

// 回复类型
function applosType($k){
	$arr = ['text'=>'文本', 'image'=>'图片', 'music'=>'音乐', 'video'=>'视频', 'thumb'=>'缩略图', 'articleimage'=>'文章内容图片','news'=>'图文'];
	return $arr[$k];
}

// 图文排序
function newsNum($k){
	$arr = [1=>'一','二','三','四','五','六','七','八'];
	return $arr[$k];
}

function agentlevel($l){
	$arr = ['无上级代理','一级代理','二级代理','三级代理'];
	return $arr[$l];
}
?>
