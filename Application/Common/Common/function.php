<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/29
 * Time: 16:24
 */

/**
 * 公用的方法
 */
function show($status,$message,$data=array()){
   $result=array(
       'status'=>$status,
       'message'=>$message,
       'data'=>$data,
   );
    exit(json_encode($result));
}

//获得IP
function GetIP()
{
    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if(isset($_SERVER["HTTP_CLIENT_IP"]))
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if(isset($_SERVER["REMOTE_ADDR"]))
        $ip = $_SERVER["REMOTE_ADDR"];
    else if(@getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(@getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if(@getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}

function H($name,$value='')
{
    $name = _T($name);
    $option = M('option');
    $r = $option->where("`KEY`='{$name}'")->find();
    if(is_array($r))
    {
        if($value!='')
        {
            $option->assgin('VALUE', $value);
            $option->save("`KEY`='{$name}'");
        }
        return $r['VALUE'];
    }
    return false;
}

function _T($str) //Text
{
    $str = addslashes($str);
    $str = str_replace("%", "\%", $str);
    $str = str_replace("<", "&lt;", $str);
    $str = str_replace(">", "&gt;", $str);
    return $str;
}

function getMd5Password($password){
    return md5($password.C('MD5_PRE'));
}

function getMenuType($type){
    return $type == 1 ? '后台菜单' : '前端导航';
}

function status($status){
    if($status==0){
        $str='关闭';
    }elseif($status==1){
        $str='正常';
    }elseif($status==-1){
        $str='删除';
    }
    return $str;
}

function getActive($navc){
    $c = strtolower(CONTROLLER_NAME);
    if(strtolower($navc) == $c){
        return 'class="active"';
    }
    return '';
}

function getAdminMenuUrl($navc){
    $url='/admin.php?c='.$navc['c'].'&a='.$navc['f'];
    if($navc['f'] == 'index'){
        $url='/admin.php?c='.$navc['c'];
    }
    return $url;
}

function showkind($status,$data){
    header('Content/type:application/json;charset=utf-8');
    if($status == 0){
        exit(json_encode(array('error'=>0,'url'=>$data)));
    }
    exit(json_encode(array('error'=>1,'message'=>'上传失败')));
}

function getLoginUsername(){
    return isset($_SESSION['adminuser']['username'])?$_SESSION['adminuser']['username']:'null';
}

function getCatName($navs, $id) {
    foreach($navs as $nav) {
        $navList[$nav['menu_id']] = $nav['name'];
    }
    return isset($navList[$id])?$navList[$id]:'';

}

function getCopyFromById($id) {
    $copyFrom = C("COPY_FROM");
    return $copyFrom[$id] ? $copyFrom[$id] : '';
}

function isThumb($thumb){
    if($thumb){
        return '<span style="color:red">有</span>';
    }
    return '无';
}