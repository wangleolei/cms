<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"请输入推荐位名称",onfocus:"不能大于30个字符"}).inputValidator({min:1,max:30,onerror:"推荐位名称为1到30个字符"});
		$("#modelid").formValidator({onshow:"请选择内容模型",onfocus:"请选择内容模型"}).inputValidator({min:1,onerror:"请选择内容模型"});
})
//-->
</script>
<div class="common-form pad-10">
<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
	 <tr>
        <th width="120">推荐位名称：</th>
        <td><input type="text" name="info[name]" id="name" class="input-text" value=""></td>
      </tr> 
      <tr>
        <th width="120">内容模型：</th>
        <td>
        	<select name="info[modelid]" id="modelid">
            	<option value="0"></option>
                <?php foreach($models as $vo){?>
                <option value="<?php echo $vo['modelid'];?>"><?php echo $vo['name'];?></option>
                <?php }?>
            </select>
        </td>
      </tr> 
	  <tr>
        <th>状态：</th>
        <td><input type="radio" name="info[status]" value="1"> 启用<input type="radio" name="info[status]" value="0" >&nbsp;&nbsp; 禁用</td>
      </tr>	      	  	  	  	  		  	  
</table>  
<!--table_form_off-->
<input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
</form>
</div>
</body>
</html>