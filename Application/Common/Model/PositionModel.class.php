<?php
namespace Common\Model;
use Think\Model;
use ThinK\Exception;
/**
 * 推荐位管理
 */
class PositionModel extends Model{
    private $_db = '';
   public function __construct(){
       $this->_db = M('Position');
   }
    public function select($data=array()){
        $condition = $data;
        $list = $this->_db->where($condition)->select($data);
        return $list;
    }

    public function insert($data){
        if(!$data || !is_array($data)){
            throw_exception('插入数据有误');
        }
        $data['create_time'] = time();
        $res = $this->_db->add($data);
        return $res;
    }

    public function find($id){
        if(!$id || !is_numeric($id)){
            return show(0,'ID数据不合法');
        }
      return  $this->_db->where('id='.$id)->find();
    }

    public function updataById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception('$ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('数据不合法');
        }
        return $this->_db->where('id='.$id)->save($data);
    }

    public function updateNewsStatusById($id,$status){
        if(!$id || !is_numeric($id)){
            throw_exception('ID数据不合法');
        }
        if(!is_numeric($status)){
            throw_exception('状态值不合法');
        }
        $data['status'] = $status;
        return $this->_db->where('id='.$id)->save($data);
    }

    // 获取正常的推荐位内容
    public function getNormalPositions() {
        $conditions = array('status'=>1);
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
    }

    public function getCount($data=array()){
        $data = array(
            'status'=>1,
        );
        return $this->_db->where($data)->count();
    }

}