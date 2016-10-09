<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/29
 * Time: 17:02
 */
namespace Common\Model;
use Think\Model;
class AdminModel extends Model{
    private $_db='';
    public function __construct()
    {
        $this->_db=M('admin');
    }
    public function getAdminByUsername($username){
       $ret= $this->_db->where('username="'.$username.'"')->find();
       return $ret;
    }

    public function getAdmin(){
        $data = array(
            'status' => array('neq',-1),
        );
        return $this->_db->where($data)->order('admin_id desc')->select();
    }

    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return show(0,'数据不合法');
        }
       return  $this->_db->add($data);
    }

    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception('状态值不合法');
        }
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data['status'] = $status;
        return  $this->_db->where('admin_id='.$id)->save($data);
    }

    public function updateByAdminId($id,$data=array()){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('数据不合法');
        }
        return
            $this->_db->where('admin_id='.$id)->save($data);
    }

    public function getAdminByAdminId($id=0){
        return $this->_db->where('admin_id='.$id)->find();
    }

    public function getLastLoginUser($data=array()){
        $time = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $data = array(
            'status'=>1,
            'lastlogintime'=>array('gt',$time),
        );
        $res = $this->_db->where($data)->count();
        return $res['tp_count'];
    }
}