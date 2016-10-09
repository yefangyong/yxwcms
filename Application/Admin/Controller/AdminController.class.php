<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class AdminController extends CommonController
{
    public function index()
    {
        $admins = D('Admin')->getAdmin();
        $this->assign('admins', $admins);
        $this->display();
    }

    public function add()
    {
        if ($_POST) {
            if (!$_POST['username'] || !isset($_POST['username'])) {
                return show(0, '用户名不能为空');
            }
            if (!$_POST['password'] || !isset($_POST['password'])) {
                return show(0, '密码不能为空');
            }
            $_POST['password'] = getMd5Password($_POST['password']);
            $admin = D('Admin')->getAdminByUsername($_POST['username']);
            if ($admin && $admin['status'] != -1) {
                return show(0, '用户存在');
            }

            //新增
            $id = D('Admin')->insert($_POST);
            if (!$id) {
                return show(0, '新增失败');
            } else {
                return show(1, '新增成功');
            }
        }
        $this->display();
    }

    public function setStatus()
    {
        $data = array(
            'admin_id' => intval($_POST['admin_id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($_POST, 'Admin');
    }

    public function personal()
    {
        $res = $this->getLoginUser();
        $user = D('Admin')->getAdminByAdminId($res['admin_id']);
        $this->assign('vo', $user);
        $this->display();
    }

    public function save()
    {
        $user = $this->getLoginUser();
        if (!$user) {
            return show(0, '该用户不存在');
        }
        $data['realname'] = $_POST['realname'];
        $data['email'] = $_POST['email'];
        try{
            $id = D('Admin')->updateByAdminId($user['admin_id'],$data);
            if($id){
                return show(1,'修改成功');
            }else{
                return show(0,'修改失败');
            }

        }catch (Exception $e){
            return show(0,$e->getMessage());
        }

    }
}