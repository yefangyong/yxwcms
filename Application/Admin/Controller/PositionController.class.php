<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class PositionController extends Controller{
    public function index(){
        $data['status'] = array('neq',-1);
        $position = D('Position')->select($data);
        $this->assign('positions',$position);
        $this->assign('nav','推荐位管理');
        $this->display();
    }

    public function add(){
        if ($_POST) {
            if(!$_POST['name'] || !isset($_POST['name'])){
                return show(0,'推荐位名称不能为空');
            }
            if($_POST['id']){
                return $this->save($_POST);
            }
            try {
                $id = D('Position')->insert($_POST);
                if ($id) {
                    return show(1, '添加成功');
                } else {
                    return show(0, '添加失败');
                }
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
        } else {
            $this->display();
        }
    }

    public function edit(){
        $data = array('status'=>array('neq',-1));
        $id = $_GET['id'];
        $position = D('Position')->find($id);
        $this->assign('vo',$position);
        $this->display();
    }

    public function save($data){
        $id = $data['id'];
        unset($data['id']);
        try {
            $res = D('Position')->updataById($id, $data);
            if ($res) {
                return show(1, '修改成功');
            } else {
                return show(0, '修改失败');
            }
        }catch (Exception $e){
            return show(0,$e->getMessage());
        }
    }

    /**
     * 设置状态，1，正常，0，关闭，-1删除
     */
    public function setStatus(){
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                $res = D('Position')->updateNewsStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
            return show(0,'没有提交的数据');

        }
    }

