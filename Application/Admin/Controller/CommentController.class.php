<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;

class CommentController extends CommonController {
     public function index(){
         $page=$_REQUEST['p']?$_REQUEST['p']:1;
         $pagesize=$_REQUEST['pagesize']?$_REQUEST['pagesize']:3;
         $rel=D('Comment')->getComments($page,$pagesize);
         $menuCount=D('Comment')->getCommentsCount();
         $res=new \Think\Page($menuCount,$pagesize);
         $pageRes=$res->show();
         $this->assign('pageRes',$pageRes);
         $this->assign('result',$rel);
         $this->assign('nav','评论列表');
         $this->display();
     }

    public function delete() {
        $id = $_POST['id'];
        $rel = M('comments')->where('id='.$id)->delete();
        if($rel) {
            return show(1,'删除成功');
        }else{
            return show(0,'删除失败');
        }
    }

}