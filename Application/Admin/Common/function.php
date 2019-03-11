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
	M('admin_log')->add($data);
}

function getLoginInfo($field = 'a.id, a.username, a.group_id, b.rules, b.disabled') {
	$info = M()->table(\config\MysqlConfig::Table_web_admin_member)->alias('a')
		->join('left join ' . \config\MysqlConfig::Table_web_admin_group . ' b on a.group_id = b.id ')
		->field($field)
		->where(['a.id ' => UID])
		->find();
	return $info;
}

/**
 * 记录配置日志
 */
function configure_record($uid,$_action,$roomID){
	$data = [
		'uid'		=>	$uid,
		'action'	=>	$_action,
        'roomID'    =>  $roomID,
		'time'		=>	time(),	
	];
	M('configurelog')->add($data);
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
