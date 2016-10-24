<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class DetailController extends CommonController{

    public function __construct(){
        parent::__construct();
        parent::filetr();
    }

    public function index(){
        $id = intval($_GET['id']);
        if(!$id || !is_numeric($id)){
            $this->error('ID不合法');
        }
        $news = D('News')->find($id);
        if(!$news || $news['status']!=1){
            $this->error('ID不合法或者咨询被关闭');
        }
        $count =intval($news['count'])+1;
        D('News')->updateCount($id,$count);
        $content = D('NewsContent')->find($id);
        $news['content'] = htmlspecialchars_decode($content['content']);
        $advNews = D("Positioncontent")->select(array('status'=>1,'position_id'=>5),2);
        $rankNews = $this->getRank();
        $comments = D('Comments')->getComments($id);
        $this->assign('result',array(
            'rankNews'=>$rankNews,
            'advNews'=>$advNews,
            'catId'=>$news['id'],
            'news'=>$news,
            'comments'=>$comments
        ));
        $res = new DdosController();
        $res->protect();
        $this->display('Detail/index');
    }

    /**
     *评论模块
     */
    public function comments() {
        $data = array();
        try {
            if (!isset($_POST['id']) || !$_POST['id']) {
                return show(0, 'id值不得为空');
            } else {
                $data['news_id'] = $_POST['id'];
            }
            if (!$_POST['comment']) {
                return show(0, '评论内容不得为空!');
            }else{
                $data['comment'] = $_POST['comment'];
            }
            if (!$_POST['username']) {
                $data['username'] = '匿名用户';
            } else {
                $data['username'] = $_POST['username'];
            }
            $data['addtime'] = time();
            $res = D('Comments')->addComments($data);
        }catch (Exception $e) {
            return show(0,$e->getmessage());
        }
        $url = $_SERVER['HTTP_REFERER'];
        if($res) {
            return show(1,'评论成功!',array('url'=>$url));
        }else{
            return show(0,'评论失败!');
        }
    }

    public function view(){
        if(!getLoginUsername()){
            return $this->error('您没有权限访问该目录');
        }
        $this->index();
    }
}