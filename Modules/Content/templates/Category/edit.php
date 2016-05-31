<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"请输入类栏目名称",onfocus:"不能大于30个字符"}).inputValidator({min:1,max:30,onerror:"栏目名称为1到30个字符"});
		$("#listorder").formValidator({onshow:"请输入一个整数，越大越靠前",onfocus:"请输入一个整数，越大越靠前"});
		$("#outlink").formValidator({onshow:"请输入要外部链接页面的网址",onfocus:"请输入要外部链接页面的网址"});
		$("#template1").formValidator({onshow:"外部链接无需输入",onfocus:"模版位置为/Modules/Home/View/Lists/"});
		$("#template2").formValidator({onshow:"只有最底及栏目需要填",onfocus:"模版位置为/Modules/Home/View/Detail/"});
		$("#template3").formValidator({onshow:"外部链接无需输入",onfocus:"模版位置为/Modules/Mobile/View/Lists/"});
		$("#template4").formValidator({onshow:"只有最底及栏目需要填",onfocus:"模版位置为/Modules/Mobile/View/Detail/"});
})
//-->
</script>
<div class="common-form pad-10">
<form name="myform" id="myform" action="" method="post">
<fieldset>
<legend>基本信息</legend>
<table width="100%" class="table_form contentWrap">
	  <tr>
	  	<th width="120">上级栏目：</th>
		<td>
			<select class="p_cat" name="info[parentid]">
                 <option value="0">作为一级栏目</option>
				 <?php echo $select_categorys ;?>
			</select>
		</td>
	  </tr>
      <tr>
        <th width="120">栏目名称：</th>
        <td><input type="text" name="info[name]" id="name" class="input-text" value="<?php echo $info['name'];?>"></td>
      </tr> 
      <tr>
        <th width="120">排序：</th>
        <td><input type="text" name="info[listorder]" id="listorder" class="input-text" value="<?php echo $info['listorder'];?>"></td>
      </tr>
      <tr>
        <th width="120">外部链接：</th>
        <td><input type="text" name="info[url]" id="listorder" size="30" class="input-text" value="<?php echo $info['url'];?>"></td>
      </tr>       	  
      <tr>
        <th width="120">附加属性：</th>
        <td>
			<?php foreach($this->category_type as $ko=>$vo){?>
        		<label>
        			<input type="checkbox" name="info[type][]" value="<?php echo $ko;?>" <?php if(preg_match("/{$ko},/",$info['type'])){echo 'checked';}?>>
        			<?php echo $vo;?></label>
			<?php }?>		
        		</td>
      </tr>
      <tr>
      	<th width="120">内容模型：</th>
        <td>
			<?php foreach($models as $val){?>
            <label>
                <input type="checkbox" name="info[modelids][]" value="<?php echo $val['modelid'];?>" <?php echo (strpos($info['modelids'],','.$val['modelid'].',')!==false)?'checked':'';?>>
                <?php echo $val['name'];?></label>
            <?php }?>
        </td>
      </tr>             
	  <tr>
        <th>可见：</th>
        <td><label><input type="radio" name="info[display]" value="1" <?php if($info['display']==1)echo 'checked';?>> 显示</label><label><input type="radio" name="info[display]" value="0" <?php if($info['display']==0)echo 'checked';?>> 隐藏</label></td>
      </tr>        
	  <tr>
        <th>状态：</th>
        <td><label><input type="radio" name="info[status]" value="1" <?php if($info['status']==1)echo 'checked';?>> 启用</label><label><input type="radio" name="info[status]" value="0" <?php if($info['status']==0)echo 'checked';?>> 禁用</label></td>
      </tr>	  	  		  	  
</table>
</fieldset>
<fieldset style=" margin-top:10px;">
<legend>模版设置</legend>
<table width="100%" class="table_form contentWrap">
      <tr>
        <th width="120">PC端列表页模版：</th>
        <td><input type="text" name="info[template1]" id="template1" class="input-text" value="<?php echo $info['template1'];?>"></td>
      </tr> 
      <tr>
        <th width="120">PC端内容页模版：</th>
        <td><input type="text" name="info[template2]" id="template2" class="input-text" value="<?php echo $info['template2'];?>"></td>
      </tr>
      <tr>
        <th width="120">移动端列表页模版：</th>
        <td><input type="text" name="info[template3]" id="template3" class="input-text" value="<?php echo $info['template3'];?>"></td>
      </tr> 
      <tr>
        <th width="120">移动端内容页模版：</th>
        <td><input type="text" name="info[template4]" id="template4" class="input-text" value="<?php echo $info['template4'];?>"></td>
      </tr>
 </table>   
</fieldset>  
<!--table_form_off-->
<input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
</form>
</div>
</body>
</html>