<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/30
 * Time: 21:42
 */
namespace Common\Model;
use Think\Model;

class CommentModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('comments');
    }


    //分页功能
    public function getComments($page, $pagesize)
    {
        $offset = ($page - 1) * $pagesize;
        $list = $this->_db->order('id desc')->limit($offset, $pagesize)->select();
        return $list;
    }

    //获得记录的总条数
    public function getCommentsCount(){
        return  $this->_db->count();

    }

    //获取评论数据
    public function getComment() {
        return $this->_db->select();
    }

}