<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class IndexController extends CommonController {

    public function __construct(){
        parent::__construct();
        parent::filetr();
    }

    public function index($type='')
    {
        //获取排行数据
        $rankNews = $this->getRank();
        //获取大图推荐
        $topPicNews = D("Positioncontent")->select(array('status' => 1, 'position_id' => 2), 1);
        //获取小图推荐
        $topSmallNews = D("Positioncontent")->select(array('status' => 1, 'position_id' => 3), 3);
        //获取列表新闻
        $listNews = D('News')->select(array('status' => 1, 'thumb' => array('neq', '')), 30);
        //获取广告位
        $advNews = D("Positioncontent")->select(array('status' => 1, 'position_id' => 5), 2);
        $this->assign('result', array(
            'topPicNews' => $topPicNews,
            'topSmallNews' => $topSmallNews,
            'rankNews' => $rankNews,
            'listNews' => $listNews,
            'advNews' => $advNews,
            'catId' => 0,
        ));
        /**
         * @buildhtml生成缓存文件
         */
        if ($type == 'build_html') {
            $this->buildhtml('index', HTML_PATH, 'Index/index');
        } else {
            $this->display();
        }
    }

    public function build_html(){
        $this->index('build_html');
        return show(1,'首页缓存成功！');
    }

    public function getCount(){
        if(!$_POST){
            return show(0,'没有任何信息');
        }
        $newsId = array_unique($_POST);
        try {
            $list = D('News')->getNewsByNewsIdIn($newsId);
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        if(!$list || !is_array($list)){
            return show(0,'notdata');
        }
        $data = array();
        foreach ($list as $k=>$v){
            $data[$v['news_id']]=$v['count'];
        }
        return show(1,'成功',$data);
    }
}