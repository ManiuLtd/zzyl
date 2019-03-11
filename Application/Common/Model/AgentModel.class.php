<?php
namespace Common\Model;
use config\EnumConfig;
use config\MysqlConfig;
use Think\Model;

//用户模型
class AgentModel extends Model
{
    //公共取数据
    public $tableName = 'agent_member';

    public function getMyUserByAgentID($agentID) {
        $where['b.agentID'] = $agentID;
        $notUserID = M()->query('SELECT userid from web_agent_member as a WHERE a.superior_agentid=' . $agentID);
        $notUserID = array_column($notUserID, 'userid');
        $notUserID = empty($notUserID) ? [0] : $notUserID;
        //找到不是代理的用户
        $where['userID'] = ['NOT IN', $notUserID];
        $count = M('agent_bind as b')->where($where)->count();
        $page = new \Think\Page($count,10);
        $data = M('agent_bind as b')->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        return ['userList' => $data, 'count' => $count, 'page' => $page];
    }

    /**
     * 获取直属下级
     */
    public function getUnderlingByAgentID($agentID) {

        return M()->table(MysqlConfig::Table_web_agent_member)->where(['superior_agentid' => $agentID])->select();
    }

    /**
     * 代理用户是否被禁用
     * @param $agentID
     * @return mixed bool
     */
    public function isDisabled($agentID) {
        $res = M()->table(MysqlConfig::Table_web_agent_member)->where(['id' => $agentID])->getField('disabled');
        if (EnumConfig::E_AgentStatus['DISABLED'] == $res) {
            return true;
        }
        return false;
    }
}