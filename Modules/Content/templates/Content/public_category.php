<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();load_calender();load_artdialog();?>
<div class="bk10"></div>
<link rel="stylesheet" href="/Public/css/jquery.treeview.css" type="text/css" />
<script type="text/javascript" src="/Public/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/Public/js/jquery.treeview.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).ready(function(){
    $("#category_tree").treeview({
			control: "#treecontrol",
			persist: "cookie",
			cookieId: "treeview-black"
	});
});
function open_list(obj) {

	window.top.$("#current_pos_attr").html($(obj).html());
}
//-->
</SCRIPT>
 <style type="text/css">
.filetree *{white-space:nowrap;}
.filetree span.folder, .filetree span.file{display:auto;padding:1px 0 1px 19px;}
 </style>
 <div id="treecontrol">
 <span style="display:none">
		<a href="#"></a>
		<a href="#"></a>
		</span>
		<a href="#">
			<!-- <img src="/Public/images/minus.gif" />  -->
			<img src="/Public/images/application_side_expand.png" /> 
			展开/收缩
		</a>
</div>
<ul class="filetree  treeview"><!--<li class="collapsable"><div class="hitarea collapsable-hitarea"></div><span><img src="<?php echo '/Public/images/icon/box-exclaim.gif';?>" width="15" height="14">&nbsp;<a href='' target='right'>审核内容</a></span></li>--></ul>
<?php echo $categorys; ?>