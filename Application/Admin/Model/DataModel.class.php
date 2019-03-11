<?php
namespace Admin\Model;
use Think\Model;
//获取数据
class DataModel extends Model{
    protected $tableName = 'Admin_menu';
    //获取多列数据
    public function get_all_data($model='',$where='',$page='',$order=''){
        $m = M($model);
        $count = $m->where($where)->count();
        $page = new \Think\Page($count,$page);
        $data = $m->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
        $data = [
            '_page' =>  $page->show(),
            '_data'  =>  $data,
        ];
        return $data;
    }
    //获取单行数据
    public function get_row_data($model='',$where=''){
        $m = M($model);
        $data = $m->where($where)->find();
        return $data;
    }
    //公共删除操作
    public function data_del($model=''){
        $id = array_unique((array)I('id',0));
        if ( empty($id) || empty($id[0]) ) {
            $this->error='请选择要操作的数据!';
            return false;
        }
        $map = array('id' => array('in', $id) );
        if(M($model)->where($map)->delete()){
            return true;
        } else {
            $this->error = '操作数据时发生错误';
            return false;
        }
    }
    //获取配置文件
    public function get_config($key='',$model=''){
        if($key){
            $config = M($model)->where(array('key'=>$key))->find();
            if($config){
                return $config;
            }else{
                return false;
            }
        }
        $arr = M($model)->select();
        $config = [];
        foreach($arr as $key => $value){
            $config[$arr[$key]['key']] = $arr[$key]['value'];
        }
        return $config;
    }
}
