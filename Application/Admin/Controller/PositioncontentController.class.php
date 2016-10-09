<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class PositioncontentController extends CommonController{
    public function index(){
        $position = D('Position')->getNormalPositions();
        //获取推荐位的内容
        $data['status'] = array('neq',-1);
        if($_GET['title']){
            $data['title'] = trim($_GET['title']);
        }
        $data['position_id'] = $_GET['position_id']?$_GET['position_id']:$position[0]['id'];
        $content = D('Positioncontent')->select($data);
        $this->assign('content',$content);
        $this->assign('positionId',$data['position_id']);
        $this->assign('positions',$position);
        $this->display();
    }

    public function add(){
        if($_POST){
            if(!$_POST['position_id'] || !isset($_POST['position_id'])){
                return show(0,'推荐位ID不能为空');
            }
            if(!$_POST['title'] || !isset($_POST['title'])){
                return show(0,'推荐位标题不能为空');
            }
            if(!$_POST['url'] && !$_POST['news_id']){
                return show(0,'url和news_id不能同时为空');
            }
            if(!$_POST['thumb'] || !isset($_POST['thumb'])){
                if($_POST['news_id']) {
                    //如果填写了news_id就与文章关联起来
                    $res = D("News")->find($_POST['news_id']);
                    if($res && is_array($res)) {
                        $_POST['thumb'] = $res['thumb'];
                    }
                }else{
                    return show(0,'图片不能为空');
                }
            }
            if($_POST['id']){
                return $this->save($_POST);
            }
            $id = D('Positioncontent')->insert($_POST);
            if($id){
                return show(1,'操作成功');
            }else{
                return show(0,'操作失败');
            }
        }else{
            $position = D('Position')->getNormalPositions();
            $this->assign('positions',$position);
            $this->display();
        }
    }

    public function edit(){
        $id = $_GET['id'];
        $position = D("Positioncontent")->find($id);
        $positions = D("Position")->getNormalPositions();
        $this->assign('positions', $positions);
        $this->assign('vo', $position);
        $this->display();
    }

    public function save($data){
        if(!$data['id'] || !isset($data['id'])){
            return show(0,'数据不合法');
        }
        $id = $data['id'];
        unset($data['id']);
        try{
            $res = D('Positioncontent')->updateById($id,$data);
            if($res){
                return show(1,'更新成功');
            }else{
                return show(0,'更新失败');
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

    public function setStatus(){
        $data = array(
            'id'=>intval($_POST['id']),
            'status'=>intval($_POST['status']),
        );
        return parent::setStatus($data,'Positioncontent');

    }

    public function listorder(){
        return parent::listorder('Positioncontent');
    }
}