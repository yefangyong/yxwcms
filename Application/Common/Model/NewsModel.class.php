<?php
namespace Common\Model;
use Think\Model;
/**
 * 文章操作内容
 */
class NewsModel extends Model{
    private $_db='';

    public function __construct(){
        $this->_db = M('news');
    }

    public function insert($data = array()) {
        if(!is_array($data) || !$data) {
            return 0;
        }

        $data['create_time']  = time();
        $data['username'] =  getLoginUsername();
        return $this->_db->add($data);
    }

    public function getNews($data,$page,$pagesize=10){
        $condition = $data;
        if($data['title'] && isset($data['title'])){
            $condition['title']=array('like','%'.$data['title'].'%');
        }
        if($data['catid'] && isset($data['catid'])){
            $condition['catid'] = intval($data['catid']);
        }
        $condition['status'] = array('neq',-1);
        $offset = ($page-1)*$pagesize;
        $list = $this->_db->where($condition)->order('listorder desc ,news_id desc')->limit($offset,$pagesize)->select();
        return $list;
    }

    public function getNewsCount($data=array()){
        $condition = $data;
        if($data['title'] && isset($data['title'])){
            $condition['title']=array('like','%'.$data['title'].'%');
        }
        if($data['catid'] && isset($data['catid'])){
            $condition['catid'] = intval($data['catid']);
        }
        $condition['status'] = array('neq',-1);
        return  $this->_db->where($condition)->count();
    }

    public function find($id){
        $data = $this->_db->where('news_id='.$id)->find();
        return $data;
    }

    public function updateById($id,$data) {
        if(!$id || !is_numeric($id) ) {
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新数据不合法');
        }

       $res = $this->_db->where('news_id='.$id)->save($data);
        return $res;
    }

    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception('$status不是一个数字');
        }
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data['status'] = $status;
        return  $this->_db->where('news_id='.$id)->save($data);
    }

    public function updateNewsListorderById($id,$listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('ID数据不合法');
        }
        $data = array('listorder'=>intval($listorder));
        return $this->_db->where('news_id='.$id)->save($data);
    }

    public function getNewsByNewsIdIn($newsIds){
        if(!$newsIds){
            throw_exception('数据不合法');
        }
        $data = array(
            'news_id' => array('in',implode(',',$newsIds)),
        );
        return $this->_db->where($data)->select();
    }

    public function select($data = array(), $limit = 100) {

        $conditions = $data;
        $list = $this->_db->where($conditions)->order('news_id desc')->limit($limit)->select();
        return $list;
    }

    public function getRank($data=array(),$limit=100){
        $list = $this->_db->where($data)->order('count desc,news_id desc')->limit($limit)->select();
        return $list;
    }

    public function updateCount($id,$count){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
       if(!is_numeric($count)){
           throw_exception('$count不能为非数字');
       }
        $data['count'] = $count;
       return  $this->_db->where('news_id='.$id)->save($data);
    }

    public function maxcount(){
        $data = array(
            'status'=>1
        );
        return $this->_db->where($data)->order('count desc')->limit(1)->find();
    }


}