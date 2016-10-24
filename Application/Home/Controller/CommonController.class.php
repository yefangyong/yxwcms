<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller{
    public function __construct()
    {
        header("Content-type:text/html; charset=utf-8");
        parent::__construct();
    }

    /**
     * @return 获取排行返回的数据
     */
    public function getRank(){
        $conds['status']=1;
        $news = D('News')->getRank($conds,10);
        return $news;
    }

    public function filetr() {
        $res = new DdosController();
        $res->protect();
        $rel = new SqlController();
        $rel->protect();
    }
    public function error($message=''){
        $message?$message:'系统错误!';
        $this->assign('message',$message);
        $this->display("Index/error");
    }
}