<?php
namespace Admin\Model;
use Think\Model;
//用户模型
class AdminmenuModel extends Model{
    //自动验证

    public $tableName = 'admin_menu';
    protected $_validate = array(
        array('menu_name','require','请填写菜单名'),
        array('menu_name','','菜单名已经存在',0,'unique',1), 
        array('pid','number','数据格式不正确'),
        array('hide','number','数据格式不正确'),
    );
}
