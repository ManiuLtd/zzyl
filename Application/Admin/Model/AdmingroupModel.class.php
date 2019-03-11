<?php
namespace Admin\Model;
use Think\Model;
//分组模型
class AdmingroupModel extends Model{
	//删除
	protected $tableName = 'admin_member';
	public function data_del(){
		$id = I('id',0);
		if(!$id){
			$this->error = '数据错误';return false;
		}
		//查询该分组下是否有用户
		$user = M('admin_member')->where(array('group_id'=>$id))->find();
		if($user){
			$this->error = '该分组下面有代理用户，不能直接删除！';
			return false;
		}
		if($this->delete($id)){
			return true;
		}else{
			$this->error = $this->getError();
			return false;
		}
	}

}
