<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class SqllogsController extends CommonController{
   public function index() {
       $res = M('logs')->where("TYPE='sql'")->select();
       $this->assign('result',$res);
       $this->display();
   }
    /**
     * 缓存处理
     */
    public function cache(){
        $this->assign('type',2);
        $this->display();
    }
}
?>