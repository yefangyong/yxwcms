<?php
/**
 * 图片相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
/**
 * 文章内容管理
 */
class ImageController extends CommonController{
    private $_uploadobj;
    public function __construct(){

    }
    public function ajaxuploadimage(){
        $upload = D('UploadImage');
        $res=$upload->imageupload();
        if($res === false){
            return show(0,'图片上传失败');
        }
        return show(1,'图片上传成功',$res);
    }

    public function kindupload(){
        $upload=D('UploadImage');
        $res=$upload->upload();
        if($res === false){
            return showkind(1,'上传失败');
        }else{
            return showkind(0,$res);
        }
    }
}

