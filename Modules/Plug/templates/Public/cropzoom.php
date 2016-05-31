<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo C('JS_PATH')?>cropzoom/css/jquery-ui-1.7.2.custom.css" rel="Stylesheet" type="text/css" /> 
<style type="text/css">
*{margin:0px;font-size:12px}
.crop{width:680px; margin:20px auto; border:1px solid #d3d3d3; padding:4px; background:#fff}
#cropzoom_container{float:left; width:500px}
#preview{float:left; width:150px; height:200px; border:1px solid #999; margin-left:10px; padding:4px; background:#f7f7f7;}
.page_btn{float:right; width:150px;  margin-top:20px; line-height:30px; text-align:center}
.clear{clear:both}
.btn{cursor:pointer}
</style>
<script type="text/javascript" src="<?php echo C('JS_PATH')?>cropzoom/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo C('JS_PATH')?>cropzoom/js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="<?php echo C('JS_PATH')?>cropzoom/js/jquery.cropzoom.js"></script>
<script type="text/javascript">
$(function() {
     var cropzoom = $('#cropzoom_container').cropzoom({
          width: 500,
          height: 360,
          bgColor: '#eee',
          enableRotation: true,
          enableZoom: true,
          selector: {
			   w:150,
			   h:100,
			   showPositionsOnDrag:true,
			   showDimetionsOnDrag:false,
               centered: true,
			   bgInfoLayer:'#fff',
               borderColor: 'blue',
			   animated: false,
			   //maxWidth:150,
			   //maxHeight:200,
               borderColorHover: 'red'
           },
           image: {
               source: '<?php echo $picurl;?>',
               width: <?php echo $picinfo['0'];?>,
               height: <?php echo $picinfo['1'];?>,
               minZoom: 10,
               maxZoom: 150
            }
      });
	 $("#crop").click(function(){
		  cropzoom.send('<?php echo U('Input/cropzoomUpload')?>', 'POST', {}, function(imgRet) {
               $("#generated").attr("src", imgRet);
          });			   
	 });
	 $("#restore").click(function(){
		  $("#generated").attr("src", "<?php echo C('JS_PATH')?>/cropzoom/tmp/head.gif");
		  cropzoom.restore();					  
	 });
});
</script>

</head>

<body>
<!--JS代码网头部-->
 
<div class="crop">
   <div id="cropzoom_container"></div>
   <div id="preview"><img id="generated" style="max-width:150px;" src=""/></div>
   <div class="page_btn">
      <input type="button" class="btn" id="crop" value="剪切照片" />
      <input type="button" class="btn" id="restore" value="照片复位" />
   </div>
   <div class="clear"></div>
</div>

</body>
</html>
