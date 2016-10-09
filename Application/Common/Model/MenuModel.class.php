<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/30
 * Time: 21:42
 */
namespace Common\Model;
use Think\Model;

class MenuModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('menu');
    }

    //添加数据
    public function insert($data = array())
    {
        if (!$data || !is_array($data)) {
            return 0;
        }
        return $this->_db->add($data);
    }

    //分页功能
    public function getMenus($data, $page, $pagesize)
    {
        $data['status'] = array('neq', -1);
        $offset = ($page - 1) * $pagesize;
        $list = $this->_db->where($data)->order(' listorder desc,menu_id desc')->limit($offset, $pagesize)->select();
        return $list;
    }

    //获得记录的总条数
    public function getMenusCount($data=array()){
        $data['status'] = array('neq', -1);
       return  $this->_db->where($data)->count();

    }

    //查找数据
    public function find($id){
        if(!$id || !is_numeric($id)){
            return array();
        }
       return  $this->_db->where('menu_id='.$id)->find();
    }

    //修改数据
    public function updateMenuById($id,$data){
        if(!$id || !is_numeric($id)){
            E('ID数据不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新的数据不合法');
        }
        return $this->_db->where('menu_id='.$id)->save($data);
    }

    //删除操作
    public function updateStatusById($id,$status){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$status || !is_numeric($status)){
            throw_exception('状态不合法');
        }
        $data['status'] = $status;
        return $this->_db->where('menu_id='.$id)->save($data);
    }

    //排序操作
    public function updateMenuListorderById($menuId,$listorder){
        if(!$menuId || !is_numeric($menuId)){
            throw_exception('ID不合法');
        }
        $data = array('listorder'=>intval($listorder));
        return $this->_db->where('menu_id='.$menuId)->save($data);
    }

    //获取后台菜单管理
    public function getAdminMenu(){
        $data=array(
            'status'=>array('neq',-1),
            'type'=>1,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }

    public function getBarMenu(){
        $data = array(
            'status'=>1,
            'type'=>0,
        );
        $res=$this->_db->where($data)->order('listorder desc,menu_id desc')->select();
        return $res;
    }
}