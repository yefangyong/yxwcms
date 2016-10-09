<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/2
 * Time: 11:51
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class ContentController extends Controller{

    public function index(){
        $conds=array();
        if($_GET['title']){
            $conds['title'] = $_GET['title'];
        }
        if($_GET['catid']){
            $conds['catid'] = intval($_GET['catid']);
        }
        $page = $_REQUEST['p']?$_REQUEST['p']:'1';
        $pagesize = 3;
        $news = D('News')->getNews($conds,$page,$pagesize);
        $count = D('News')->getNewsCount($conds);
        $position = D('Position')->getNormalPositions();
        $res = new \Think\Page($count,$pagesize);
        $pageres = $res->show();
        $this->assign('pageres',$pageres);
        $this->assign('positions',$position);
        $this->assign('news',$news);
        $abc = D("Menu")->getBarMenu();
        $this->assign('webSiteMenu',$abc);
        $this->display();
    }

    public function add(){
        if ($_POST) {
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']){
                return show(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']){
                return show(0,'content不存在');
            }
            if($_POST['news_id']){
              return  $this->save($_POST);
            }
            $newsId = D("News")->insert($_POST);
            if($newsId){
                $newsContentData['content']=$_POST['content'];
                $newsContentData['news_id']=$newsId;
                $cId=D('NewsContent')->insert($newsContentData);
                if($cId){
                    return show(1,'新增成功');
                }else{
                    return show(0,'主表插入成功，附表插入失败');
                }
            }else{
                return show(0,'新增失败');
            }

        } else {
            $websiteMenu = D('Menu')->getBarMenu();
            $titleFontColor = C('TITLE_FONT_COLOR');
            $copyFrom = C('COPY_FROM');
            $this->assign('websiteMenu', $websiteMenu);
            $this->assign('titleFontColor', $titleFontColor);
            $this->assign('copyFrom', $copyFrom);
            $this->display();
        }
    }

    public function edit(){
        $newId = $_GET['id'];
        if(!$newId){
            $this->redirect('/admin.php?c=content');
        }
        $news = D('News')->find($newId);
        if(!$news){
            $this->redirect('/admin.php?c=content');
        }
        $newsContent = D('NewsContent')->find($newId);
        if($newsContent){
            $news['content'] = $newsContent['content'];
        }
        $webSiteMenu = D('Menu')->getBarMenu();
        $this->assign('webSiteMenu',$webSiteMenu);
        $this->assign('news',$news);
        $this->assign('titleFontColor',C('TITLE_FONT_COLOR'));
        $this->assign('copyfrom',C('COPY_FROM'));
        $this->display();
    }

    public function save($data){
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try{
            $id = D('News')->updateById($newsId,$data);
            $newsContentData['content'] = $data['content'];
            $newsContent = D('NewsContent')->updateNewsById($newsId,$newsContentData);
            if($id === false || $newsContent === false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }

    }

    public function setStatus(){
        try{
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res = D('News')->updateStatusById($id,$status);
                if($res){
                    return show(1,'修改成功');
                }else{
                    return show(0,'修改失败');
                }
            }else{
                return show(0,'没有提交的内容');
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

    public function listorder(){
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $error = array();
        if($listorder){
          //执行更新
            try {
                foreach ($listorder as $newsId => $v) {
                    $id = D('News')->updateNewsListorderById($newsId,$v);
                    if ($id === false) {
                        $error[] = $newsId;
                    }
                }
                if ($error) {
                    return show(0, '排序失败',array('jump_url'=>$jumpUrl));
                } else {
                    return show(1, '排序成功',array('jump_url'=>$jumpUrl));
                }
            }catch(Exception $e){
                return show(0,$e->getMessage(),array('jump_url'=>$jumpUrl));
            }
        }
        return show(0,'排序数据失败',array('jump_url'=>$jumpUrl));
    }

    public function push(){
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $newsId = $_POST['push'];
        if(!$newsId || !is_array($newsId)){
            return show(0,'请选择文章进行推荐');
        }

        if(!$positionId){
            return show(0,'请选择推荐位');
        }
        try {
            $news = D('News')->getNewsByNewsIdIn($newsId);
            if (!$news) {
                return show(0, '没有相关内容');
            }
            foreach ($news as $new) {
                $data = array(
                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' => $new['create_time'],
                );
                $position = D('Positioncontent')->insert($data);
                if(!$position){
                    return show(0,'推荐失败!');
                }
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功!',array('jump_url'=>$jumpUrl));
    }
}