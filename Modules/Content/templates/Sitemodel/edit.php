<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"请输入模型名称",onfocus:"请输入模型名称",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请输入模型名称"});
	})
//-->
</script>

<div class="pad-lr-10">
<form action="?m=content&c=sitemodel&a=edit" method="post" id="myform">
<fieldset>
	<legend>基本信息</legend>
	<table width="100%"  class="table_form">
  <tr>
     <th width="120">模型名称：</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[name]" id="name" size="30" value="<?php echo $name;?>"/></td>
  </tr>
  <tr>
    <th>模型表键名：</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[tablename]" id="tablename" size="30" value="<?php echo $tablename;?>" disabled/></td>
  </tr>
    <tr>
     <th>描述：</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[description]" id="description"  size="30" value="<?php echo $description;?>"/></td>
  </tr>
</table>
</fieldset>
<div class="bk15"></div>
	<input type="hidden" name="modelid" value="<?php echo $modelid;?>" />
    <input type="submit" class="dialog" id="dosubmit" name="dosubmit" value="<?php echo L('submit');?>" />
</form>
</div>
<script language="JavaScript">
<!--
function load_file_list(id) {
	$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id, function(data){$('#category_template').html(data.category_template);$('#list_template').html(data.list_template);$('#show_template').html(data.show_template);});
}
$("#other").click(function() {
	if ($('#other').attr('checked')) {
		$('#other_tab').show();
	} else {
		$('#other_tab').hide();
	}
})
if ($('#other').attr('checked')) {
	$('#other_tab').show();
} else {
	$('#other_tab').hide();
}
	//-->
</script>
</body>
</html>