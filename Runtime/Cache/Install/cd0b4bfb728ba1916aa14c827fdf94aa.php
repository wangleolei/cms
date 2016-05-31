<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>ThinkerCMS安装</title>
<link rel="stylesheet" href="/Public/simpleboot/themes/flat/theme.min.css" />
<link rel="stylesheet" href="/Public/install/css/install.css" />
<link rel="stylesheet" href="/Public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css" />

<script src="/Public/js/jquery.min.js"></script>
</head>
<body>
	<div class="wrap">
		<div class="header">
	<h1 class="logo">ThinkerCMS 安装向导</h1>
	<div class="version"><?php echo (C("version")); ?></div>
</div>
		<section class="section">
			<div style="padding: 40px 20px;">
				<div class="text-center">
					<a style="font-size: 18px;">恭喜您，安装完成！</a>
					<br>
					<br>
					<div class="alert alert-danger" style="width: 350px;display: inline-block;">
						为了您站点的安全，安装完成后即可将网站Modules目录下的“Install”文件夹删除!
						另请对Conf/db.php文件做好备份，以防丢失！
					</div>
					<br>
					<a class="btn btn-success" href="/">进入前台</a> 
					<a class="btn btn-success" href="/admin">进入后台</a> 
				</div>
			</div>
		</section>
	</div>

	<div class="footer">
	&copy; 2014-<?php echo date('Y');?> <a href="http://www.thinkerphp.com" target="_blank">ThinkPHP</a>思想者网络
</div>
	<script>
		$(function() {
			return;
			$.ajax({
				type : "POST",
				url : "http://www.thinkerphp.com",
				data : {
					host : '<?php echo $host;?>',
					ip : '<?php echo $ip?>'
				},
				dataType : 'json',
				success : function() {
				}
			});
		});
	</script>
</body>
</html>