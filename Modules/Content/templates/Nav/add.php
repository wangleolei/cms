<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"请输入导航链接名称",onfocus:"不能大于30个字符"}).inputValidator({min:1,max:30,onerror:"名称为1到30个字符"});
		$("#url").formValidator({onshow:"请输入导航链接地址",onfocus:"请输入导航链接地址"});//.regexValidator({regexp:"url",datatype:"enum",onerror:"导航链接地址格式错误"});
		$("#image").formValidator({onshow:"请上传导航链接图标",onfocus:"请上传导航链接图标"});
})
//-->
</script>
<div class="common-form pad-10">
<form name="myform" id="myform" action="" method="post" enctype="multipart/form-data">
<table width="100%" class="table_form contentWrap">
      <tr>
        <th width="120">导航链接名称：</th>
        <td><input type="text" name="info[name]" id="name" class="input-text" value="" size="30"></td>
      </tr>
      <tr>
        <th width="120">导航链接地址：</th>
        <td><input type="text" name="info[url]" id="url" class="input-text" value="<?php echo $info['url'];?>" size="30"></td>
      </tr>       	   	  	  	  	  		  	  
</table>
<!--table_form_off-->
<input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
</form>
</div>
</body>
</html>