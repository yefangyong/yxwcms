<?php
namespace Common\Model;
use Think\Model;
use ThinK\Exception;
/**
 * 推荐位内容管理
 */
class PositioncontentModel extends Model{
    private $_db = '';
    public function __construct(){
        $this->_db = M('Position_content');
    }

    public function select($data=array(),$limit=0){
        if($data['title']){
            //模糊查找技术
            $data['title'] = array('like','%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder desc,id desc');
        if($limit){
            $this->_db->limit($limit);
        }
        $list = $this->_db->select();
        return $list;
    }

    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return show(0,'数据不合法');
        }
        if(!$data['create_time']){
            $data['create_time'] = time();
        }
        return $this->_db->add($data);
    }

    public function find($id)
    {
        //查询数据集,即多条数据用select，查询单条数据用find
        $data = $this->_db->where('id=' . $id)->find();
        return $data;
    }

    public function updateById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('数据不合法');
        }
        $list = $this->_db->where('id='.$id)->save($data);
        return $list;
    }

    public function updateStatusById($id,$status){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!is_numeric($status)){
            throw_exception('状态值不合法');
        }
        $data['status'] = $status;
        return $this->_db->where('id='.$id)->save($data);//根据条件更新记录
    }

    public function updateListorderById($id,$listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('数据不合法');
        }
        $data = array('listorder'=>intval($listorder));
        $res = $this->_db->where('id='.$id)->save($data);
       return $res;
    }

}