<?php
namespace Home\Controller;
use Think\Controller;

class CatController extends CommonController{

    public function __construct(){
        parent::__construct();
        parent::filetr();
    }

    public function index(){
        if(!intval($_GET['id'])){
           return  $this->error('ID不合法');
        }
        $id = $_GET['id'];
        $nav = D('Menu')->find($id);
        if(!$nav || $nav['status']!=1){
            return $this->error('栏目ID不存在或者状态不正常');
        }
        //获取排行数据
        $rankNews = $this->getRank();
        //获取广告位
        $advNews = D("Positioncontent")->select(array('status'=>1,'position_id'=>5),2);
        $page = $_REQUEST['p']?$_REQUEST['p']:'1';
        $pagesize = 2;
        $conds = array(
            'status'=>1,
             'thumb'=>array('neq',''),
            'catid'=>$id,
        );
        $news = D('News')->getNews($conds,$page,$pagesize);
        $count = D('News')->getNewsCount($conds);
        $res = new \Think\Page($count,$pagesize);
        $pageres = $res->show();
        $this->assign('result',array(
            'rankNews'=>$rankNews,
            'advNews'=>$advNews,
            'catId'=>$id,
            'listNews'=>$news,
            'pageres'=>$pageres,
        ));
        $res = new DdosController();
        $res->protect();
        $this->display();
    }
}