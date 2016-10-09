<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class LoginController extends Controller {

    public function index(){
        if(session('adminuser')){
            $this->redirect('/admin.php?m=admin&c=index');
        }
    	return $this->display();
    }

    public function check(){
        $username=$_POST['username'];
        $password=$_POST['password'];
        if(!trim($username)){
          return  show(0,'用户名不能为空');
        }
        if(!trim($password)){
          return  show(0,'密码不得为空');
        }
       $ret=D('Admin')->getAdminByUsername($username);
        if(!$ret){
            return show(0,'该用户不存在');
        }
        if($ret['password'] != getMd5Password($password)){
            return show(0,'密码错误');
        }
        D('Admin')->updateByAdminId($ret['admin_id'],array('lastlogintime'=>time()));
        session('adminuser',$ret);
        return show(1,'登录成功');

    }
    public function loginout(){
        session('adminuser',null);
        $this->redirect('/admin.php&c=login');
    }

}