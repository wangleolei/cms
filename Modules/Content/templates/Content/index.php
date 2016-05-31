<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>内容发布首页</title>
<link href="<?php echo C('CSS_PATH');?>reset.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo C('JS_PATH');?>bootstrap/css/bootstrap.min.css" />
<script type="text/javascript" type="text/css" src="<?php echo C('JS_PATH');?>jquery.min.1.11.2.js" ></script>
<script type="text/javascript" type="text/css" src="<?php echo C('JS_PATH');?>bootstrap/js/bootstrap.js" ></script>
<script type="text/javascript">
$(function(){
var obj=window.top.document;
$(obj).find("#display_center_id").show();
$(obj).find("#center_frame").attr('src','<?php echo U("public_category");?>');	
	})
</script>
<style type="text/css">
* {
	font-family: '微软雅黑';
}
.model-box {
	margin-top: 15px;
}
.model-box .box {
}
.model-box .box .panel-title {
	text-align: center;
}
.model-box .box .panel-body>ul {
	list-style: none;
	margin: 0px;
	padding: 0px;
}
.model-box .box .panel-body>ul li {
	width: 25%;
	float: left;
	text-align: center;
	background: #eee;
}
.search_input{ margin-top:15%;}
.model-manage{ margin-top:10px;}
</style>
</head>
<body>
<?php 
$style=cookie('style');
if($style=='styles2'){$theme='default';}elseif($style=='styles3'){$theme='success';}elseif($style=='styles4'){$theme='primary';}else{$theme='primary';}
?>
<div class="container-fluid model-box">
  <div class="row search_input">
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
      <div class="input-group">    
<div class="input-group-btn">
		
          <button type="button" class="btn btn-<?php echo $theme;?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="search-type">栏目搜索</span></button>
        </div> 
        <input type="text" class="form-control" name="keywords" id="keyword" aria-label="..." onkeydown="entersearch();">
        <div class="input-group-btn">
          <button type="button" class="btn btn-<?php echo $theme;?>" onclick="search();" onkeydown="entersearch(event);"><span class="glyphicon glyphicon-search"></span> 搜 索</button>
        </div>
        <!-- /btn-group --> 
      </div>
      <!-- /input-group --> 
    </div>
    <!-- /.col-lg-6 --> 
  </div>
 <div class="row model-manage" style="display:none;" id="search_box">
<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
<div class="panel panel-<?php echo $theme;?>">
  <div class="panel-heading">搜索结果</div>
    <table class="table">
      <tbody id="keyword_con">
      </tbody>
    </table>
</div></div>
  </div>
</div>
<script type="text/javascript">
  function search()
  {
   $("#search_box").slideUp();
    var keyword=$("#keyword").val();
    if(!keyword)
    {
	  window.top.art.dialog.alert('请输入栏目搜索关键字！');
      return false;
	}
	$.get('<?php echo U("public_search");?>?keyword='+keyword,function(data){
		if($.trim(data))
		{
			$("#keyword_con").html(data);
			$("#search_box").slideDown();
		}
		else
		{
			 window.top.art.dialog.alert('没有搜索到含该关键词的栏目！');
		}
	})
  }
function entersearch(){  
    var event = window.event || arguments.callee.caller.arguments[0];  
    if (event.keyCode == 13)  
       {  
          search();  
       }  
}
function entersearch1(event){  
    if (event.keyCode == 13)  
       {  
          search();  
       }  
}
</script>
</body>
</html>
