<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>医讯网后台管理平台</title>
    <!-- Bootstrap Core CSS -->
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="/Public/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/Public/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="/Public/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/Public/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/Public/css/sing/common.css" />
    <link rel="stylesheet" href="/Public/css/party/bootstrap-switch.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/party/uploadify.css">

    <!-- jQuery -->
    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script src="/Public/js/dialog/layer.js"></script>
    <script src="/Public/js/dialog.js"></script>
    <script src="/Public/js/admin/common.js"></script>
    <script src="/Public/js/admin/image.js"></script>
    <script src="/Public/js/admin/login.js"></script>
    <script type="text/javascript" src="/Public/js/party/jquery.uploadify.js"></script>

</head>

    




<body>
<div id="wrapper">
  <?php
$navs = D('Menu')->getAdminMenu(); $index=''; ?>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    
    <a class="navbar-brand" >医讯网内容管理平台</a>
  </div>
  <!-- Top Menu Items -->
  <ul class="nav navbar-right top-nav">
    
    
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo getLoginUsername()?> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li>
          <a href="/admin.php?c=admin&a=personal"><i class="fa fa-fw fa-user"></i> 个人中心</a>
        </li>
       
        <li class="divider"></li>
        <li>
          <a href="/admin.php?m=admin&c=login&a=loginout"><i class="fa fa-fw fa-power-off"></i> 退出</a>
        </li>
      </ul>
    </li>
  </ul>
  <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav nav_list">
      <li <?php echo (getActive($index)); ?>>
        <a href="/admin.php"><i class="fa fa-fw fa-dashboard"></i> 首页</a>
      </li>
      <?php if(is_array($navs)): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav0): $mod = ($i % 2 );++$i;?><li <?php echo (getActive($nav0["c"])); ?>>
        <a href="<?php echo (getAdminMenuUrl($nav0)); ?>"><i class="fa fa-fw fa-bar-chart-o"></i><?php echo ($nav0["name"]); ?></a>
      </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
  <!-- /.navbar-collapse -->
</nav>
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">SQL注入拦截设置</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-warning alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          如果您对以下设置不了解，请保持默认设置，或者<a href="#" class="alert-link">联系我们</a>咨询。 </div>
        <div class="panel panel-default">
          <div class="panel-heading"> 基本设置 </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-6">
                <form name="ddos_form" method="post" id="singcms-form">
                  <div class="form-group">
                    <label>拦截处理</label>
                    <div class="radio">
                      <label>
                        <input type="radio" name="sqltype" id="SQLType1" value="block" <?php if($vo["sqltype"] == 'block'): ?>checked<?php endif; ?>>
                        仅拦截 </label>
                      <label>
                        <input type="radio" name="sqltype" id="SQLType2" value="lock" <?php if($vo["sqltype"] == 'lock'): ?>checked<?php endif; ?>>
                        拦截后锁定IP </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>统计时间阈值（秒）</label>
                    <input class="form-control" placeholder="统计时间阈值" name="sqluptime" value="<?php echo ($vo["sqluptime"]); ?>">
                    <p class="help-block">设置过高可能会造成数据库压力增大。</p>
                  </div>
                  <div class="form-group">
                    <label>单位时间注入上限（次）</label>
                    <input class="form-control" placeholder="单位时间访问上限" name="sqlupcount" value="<?php echo ($vo["sqlupcount"]); ?>">
                    <p class="help-block">每单位时间最高注入次数。</p>
                  </div>
                  <div class="form-group">
                    <label>单次IP锁定时间（分钟）</label>
                    <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>"/>
                    <input class="form-control" placeholder="单次IP锁定时间" name="sqllocktime" value="<?php echo ($vo["sqllocktime"]); ?>">
                    <p class="help-block">单次封锁IP时间，超时后恢复正常访问。</p>
                  </div>
                  <button type="button" id="singcms-button-submit" class="btn btn-default">提交</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="js/jquery-1.11.0.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/plugins/metisMenu/metisMenu.min.js"></script> 
<script src="js/sb-admin-2.js"></script>
<script>
    var SCOPE={
        'save_url':'admin.php?c=sql&a=add',
        'jump_url':'admin.php?c=sql',
    }
</script>
<script src="/Public/js/admin/common.js"></script>



</body>

</html>