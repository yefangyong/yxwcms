<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/3
 * Time: 14:38
 */
namespace Common\Model;
use Think\Model;
class NewsContentModel extends Model{
    private $_db='';

    public function __construct(){
        $this->_db=M('news_content');
    }

    public function insert($data=array()){
        if(!isset($data) || !is_array($data)){
            return 0;
        }
        $data['create_time'] = time();
        if(isset($data['content']) && $data['content']){
            $data['content'] = htmlspecialchars(($data['content']));
        }
        return  $this->_db->add($data);
    }

    public function find($id) {
        return $this->_db->where('news_id='.$id)->find();
    }

    public function updateNewsById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新的数据不合法');
        }
        return $this->_db->where('news_id='.$id)->save($data);
    }

}
