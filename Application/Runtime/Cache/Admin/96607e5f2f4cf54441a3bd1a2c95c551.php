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

	<div class="container-fluid">

		<!-- Page Heading -->
		<div class="row">
	<div class="col-lg-12">
		<a href="/admin.php?c=basic"><button type="button" class="btn <?php if($type == 1): ?>btn-primary<?php endif; ?>"> 基本配置</button></a>
		<a href="/admin.php?c=basic&a=cache"><button type="button" class="btn <?php if($type == 2): ?>btn-primary<?php endif; ?>"> 缓存配置</button></a>
	</div>
</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label for="inputname" class="col-sm-2 control-label">更新首页缓存:</label>
					<div class="col-sm-5">
						<button type="button" class="btn" id="cache-index">确定更新</button>
					</div>
				</div>

				

			</div>

		</div>
		<!-- /.row -->

	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<script>
	$('#cache-index').click(function(){
		var url = "/index.php?c=index&a=build_html";
		var jumpUrl = "/admin.php?c=basic&a=cache";
		var postData ={};
		$.post(url,postData,function(result){
			if(result.status == 1){
				return dialog.success(result.message,jumpUrl);
			}else{
				return dialog.error(result.message);
			}
		},"JSON");
	});
</script>
<script src="/Public/js/admin/common.js"></script>



</body>

</html>