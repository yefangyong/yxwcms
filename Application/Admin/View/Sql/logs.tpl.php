<?php
if(!defined('RIVUAI'))
	exit('ACCESS DENIED!');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>控制台 - 科威PHP防火墙</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
<link href="css/sb-admin-2.css" rel="stylesheet">
<link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<div id="wrapper"> 
  
  <!-- Navigation -->
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <?php
	include('templates/Layout/toolbar.tpl.php');
	?>
    <?php
	include('templates/Layout/sidebar.tpl.php');
	?>
  </nav>
  
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">SQL注入拦截日志</h1>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    SQL注入拦截日志查询
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>拦截时间</th>
                                    <th>请求IP</th>
                                    <th>拦截类型</th>
                                    <th>处理结果</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
							    $logs = M('logs');
								$rel = $logs->where("TYPE='sql'")->get();
								foreach($rel as $key =>$val) {
							  ?>
                                <tr>
                                    <td><?php echo $val['ID']; ?></td>
                                    <td><?php echo $val['TIME']; ?></td>
                                    <td><?php echo $val['IP']; ?></td>
                                    <td><?php echo $val['TYPE']; ?></td>
                                    <td><?php echo $val['RESULT']; ?></td>
                                </tr>
                              <?php
								}
							  ?>
                            </tbody>
                        </table>
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
<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="js/sb-admin-2.js"></script>
<script>
$(document).ready(function() {
	$('#dataTable').dataTable();
});
</script>
</body>
</html>
