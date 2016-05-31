<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<div class="common-form pad-10">
<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
      <tr>
        <th width="120">SEO 标题：</th>
        <td><input type="text" name="info[seo_title]" id="name" class="input-text" value="<?php echo $info['seo_title'];?>" size="50"></td>
      </tr> 
      <tr>
        <th width="120">SEO 关键字：</th>
        <td><input type="text" name="info[seo_keywords]" id="name" class="input-text" value="<?php echo $info['seo_keywords'];?>"  size="50"></td>
      </tr>
      <tr>
        <th width="120">SEO 描述：</th>
        <td><textarea name="info[seo_description]" cols="49" rows="6"><?php echo $info['seo_description'];?></textarea></td>
      </tr>	   	   	  		  	  
</table>  
<!--table_form_off-->
<input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
</form>
</div>
</body>
</html>