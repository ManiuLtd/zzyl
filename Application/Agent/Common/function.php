<?php
/**
 * 判断是否登录
 * return int|bool
 */
function is_login(){
    if(session('agentLoginInfo')){
        $loginInfo = session('agentLoginInfo');
        if (time() < 1800 + $loginInfo['login_time']) {
            return $loginInfo['agent_user_id'];
        }
        return false;
    }else{
        return false;
    }
}
?>