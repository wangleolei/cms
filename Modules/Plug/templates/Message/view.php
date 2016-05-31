<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#reply").formValidator({onshow:"请输入回复内容",onfocus:"请输入回复内容"}).inputValidator({min:1,max:250,onerror:"回复内容为1到250个字符"});
})
//-->
</script>
<div class="common-form pad-10">
  <form name="myform" id="myform" action="" method="post">
    <table width="100%" class="table_form contentWrap">
      <tr>
        <th width="50">主题：</th>
        <td><?php echo $info['title'];?></td>
      </tr>
      <tr>
        <th width="50">内容：</th>
        <td><?php echo $info['content'];?></td>
      </tr>
      <tr>
        <th>回复：</th>
        <td><textarea cols="50" rows="5" name="reply" id="reply"><?php echo $info['reply'];?></textarea><br/></td>
      </tr>
    </table>
    <input type="submit" id="dosubmit" class="hidden" name="dosubmit" value="提交"/>
  </form>
</div>
</body>
</html>