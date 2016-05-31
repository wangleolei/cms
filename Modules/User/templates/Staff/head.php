<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<style type="text/css" media="screen">
#flashContent {
	width: 100%;
	height: 100%;
}
</style>
	<script type="text/javascript">
   function uploadevent(status){
	//alert(status);
        status += '';
     switch(status){
     case '1':
	 window.location="<?php echo U('Admin/Index/firstPage');?>";
	break;
     break;
     case '-1':
	  window.location.reload();
     break;
     default:
     window.location.reload();
    } 
   }
  </script>
<div id="altContent">
	<input  id="username" type="hidden" value="20130101232565" />
	<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
WIDTH="650" HEIGHT="450" id="myMovieName">
		<PARAM NAME=movie VALUE="/Public/js/headedit/avatar.swf">
		<PARAM NAME=quality VALUE=high>
		<PARAM NAME=bgcolor VALUE=#FFFFFF>
		<param name="flashvars" value="imgUrl=./default.jpg&uploadUrl=<?php echo U();?>&uploadSrc=true" />
		<EMBED src="/Public/js/headedit/avatar.swf" quality=high bgcolor=#FFFFFF WIDTH="650" HEIGHT="450" wmode="transparent" flashVars="imgUrl=./default.jpg&uploadUrl=<?php echo U();?>&uploadSrc=true"
NAME="myMovieName" ALIGN="" TYPE="application/x-shockwave-flash" allowScriptAccess="always"
PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"> </EMBED>
	</OBJECT>
</div>
</body></html>