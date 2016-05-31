<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"请输入栏目名称",onfocus:"不能大于30个字符"}).inputValidator({min:1,max:30,onerror:"栏目名称为1到30个字符"});
		$("#listorder").formValidator({onshow:"请输入一个整数，越大越靠前",onfocus:"请输入一个整数，越大越靠前"}).inputValidator({type:"number",onerror:"请输入一个整数，越大越靠前"});
})
//-->
</script>
<div class="common-form pad-10">
<form name="myform" id="myform" action="" method="post">
<input type="hidden" name="info[parentid]" value="<?php echo $id;?>"/>
<table width="100%" class="table_form contentWrap">
      <tr>
        <th width="80">栏目名称：</th>
        <td><input type="text" name="info[name]" id="name" class="input-text" value=""></td>
      </tr> 
      <tr>
        <th width="80">排序：</th>
        <td><input type="text" name="info[listorder]" id="listorder" class="input-text" value=""></td>
      </tr>	  		  	  
</table>  
<!--table_form_off-->
<input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
</form>
</div>
</body>
</html>


