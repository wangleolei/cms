<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();load_ueditor();load_calender();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#subject").formValidator({onshow:"请输入公告主题",onfocus:"请输入公告主题"}).inputValidator({min:1,max:50,onerror:"公告主题为1到50个字符"});
})
//-->
</script>
<div class="common-form pad-10">
<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
	  <tr>
        <th width="60">公告主题：</th>
        <td><input type="text" name="info[title]" id="subject" class="input-text" value="<?php echo $info['title'];?>" size="40"></td>
      </tr> 
	  <tr>
        <th width="60">起始日期：</th>
        <td><input type="text" name="info[begin_time]" id="begin_time" onclick="WdatePicker()" value="<?php echo date('Y-m-d',$info['begin_time']);?>" size="15" class="Wdate input-text" readonly></td>
      </tr>     
	  <tr>
        <th width="60">截止日期：</th>
        <td><input type="text" name="info[end_time]" id="end_time" onclick="WdatePicker()" value="<?php echo date('Y-m-d',$info['end_time']);?>" size="15" class="Wdate input-text" readonly></td>
      </tr>                  
      <tr>
        <th width="60">
        	公告内容：</th>
        <td><textarea id="content" name="content" style="width:550px; height: 200px;"><?php echo $info['content'];?></textarea>
        </td>
      </tr>
      <tr>
        <th width="60">公告状态：</th>
        <td>

        	<label>
        		<input type="radio" name="info[status]" value="1" <?php echo ($info['status']==1)?'checked':'';?>>
        		开启</label>

        	<label>
        		<input type="radio" name="info[status]" value="0" <?php echo ($info['status']==0)?'checked':'';?>>
        		关闭</label>
		
        </td>
      </tr> 	          
</table>  
<!--table_form_off-->
<input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
</form>
</div>
<?php ueditor('content','simple');?>
</body>
</html>