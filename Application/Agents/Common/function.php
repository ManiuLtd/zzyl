<?php
/**
 * 判断是否登录
 */
function is_login(){
    if(session('agent_user_id')){
        return session('agent_user_id');
    }else{
        return false;
    }
}
?>