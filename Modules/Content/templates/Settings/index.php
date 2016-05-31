<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();load_ueditor();?>
<script type="text/javascript">
<!--
	$(function(){
		SwapTab('setting','on','',5,1);
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:200,height:50}, function(){this.close();$(obj).focus();})}});		
		$("#seo_title").formValidator({onshow:"请输入SEO标题",empty:true,onfocus:"SEO标题应该为1-50个字符"}).inputValidator({min:1,max:50,onerror:"SEO标题应该1-50个字符"});
		$("#seo_keywords").formValidator({empty:true,onshow:"请输入SEO关键词，多个关键词用‘,’分开",onfocus:"SEO关键词应该为1-50个字符，多个关键词用‘,’分开"}).inputValidator({min:1,max:250,onerror:"SEO关键词应该为1-50位之间，多个关键词用‘,’分开"});
		$("#seo_description").formValidator({empty:true,onshow:"请输入SEO描述",onfocus:"SEO描述应该为1-250个字符"}).inputValidator({min:1,max:250,onerror:"SEO描述应该为2-250个字符"});
		$("#contact-phone").formValidator({empty:true,onshow:"请输入销售电话",onfocus:"请输入销售电话"});
		$("#contact-qq").formValidator({empty:true,onshow:"请输入联系QQ号码",onfocus:"请输入联系QQ号码"});
		$("#contact-weibo").formValidator({empty:true,onshow:"请输入微博地址",onfocus:"请输入微博地址"}).regexValidator({regexp:"url",datatype:"enum",onerror:"微博地址格式错误"});
		$("#pc_lists").formValidator({empty:true,onshow:"请输入路由规则，必须包含:catid且不能重复",onfocus:"例如：lists/:catid"});
		$("#pc_detail").formValidator({empty:true,onshow:"请输入路由规则，必须包含:catid,:id且不能重复",onfocus:"例如：lists/:catid/:id"});
		$("#mb_lists").formValidator({empty:true,onshow:"请输入路由规则，必须包含:catid且不能重复",onfocus:"例如：mlists/:catid"});
		$("#mb_detail").formValidator({empty:true,onshow:"请输入路由规则，必须包含:catid,:id且不能重复",onfocus:"例如：mlists/:catid/:id"});
	})
//-->
</script>
<form action="" method="post" id="myform">
<div class="pad-10">
<div class="col-tab">
<ul class="tabBut cu-li">
            <li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',3,1);">基本信息</li>
            <li id="tab_setting_2" onclick="SwapTab('setting','on','',3,2);">首页SEO</li>
            <li id="tab_setting_3" onclick="SwapTab('setting','on','',3,3);">路由规则</li>        
</ul>
<div id="div_setting_1" class="contentList pad-10">
	<table width="100%"  class="table_form">
  <tr>
    <th width="120">销售电话</th>
    <td class="y-bg">
    	<input type="text" class="input-text" name="baseinfo[phone]" id="contact-phone" value="<?php echo $baseinfo['phone'];?>">
	 </td>
  </tr> 
  <tr>
    <th width="120">售后服务热线</th>
    <td class="y-bg">
    	<input type="text" class="input-text" name="baseinfo[phone1]" id="contact-phone" value="<?php echo $baseinfo['phone1'];?>">
	 </td>
  </tr>
  <tr>
    <th width="120">人才招聘热线</th>
    <td class="y-bg">
    	<input type="text" class="input-text" name="baseinfo[phone2]" id="contact-phone" value="<?php echo $baseinfo['phone2'];?>">
	 </td>
  </tr> 
  <tr>
    <th width="120">传真</th>
    <td class="y-bg">
    	<input type="text" class="input-text"  name="baseinfo[fax]" id="contact-fax" value="<?php echo $baseinfo['fax'];?>">
	 </td>
  </tr>
  <tr>
    <th width="120">邮箱</th>
    <td class="y-bg">
    	<input type="text" class="input-text" name="baseinfo[email]" id="contact-email" value="<?php echo $baseinfo['email'];?>">
	 </td>
  </tr>               
  <tr>
    <th width="120">QQ客服号码</th>
    <td class="y-bg">
    	<input type="text" class="input-text" name="baseinfo[qq]" id="contact-qq" value="<?php echo $baseinfo['qq'];?>">
	 </td>
  </tr>
  <tr>
    <th width="120">微博地址</th>
    <td class="y-bg">
    	<input type="text" class="input-text" size="50" name="baseinfo[weibo]" id="contact-weibo" value="<?php echo $baseinfo['weibo'];?>">
	 </td>
  </tr>  
  <tr>
    <th width="120">联系地址</th>
    <td class="y-bg">
    	<textarea rows="3" cols="30" name="baseinfo[address]"><?php echo $baseinfo['address'];?></textarea>
	 </td>
  </tr>
  <tr>
    <th width="120">ICP备案号</th>
    <td class="y-bg">
    	<input type="text" class="input-text"  name="baseinfo[icp]" id="contact-weibo" value="<?php echo $baseinfo['icp'];?>">
	 </td>
  </tr>          
</table>
</div>
<div id="div_setting_2" class="contentList pad-10 hidden">
<table width="100%"  class="table_form">
  <tr>
    <th width="120">SEO标题</th>
    <td class="y-bg"><input type="text" class="input-text" name="index[seo_title]" id="seo_title" size="50" value="<?php echo $index['seo_title'];?>"/></td>
  </tr>
  <tr>
    <th width="120">SEO关键词</th>
    <td class="y-bg"><input type="text" class="input-text" name="index[seo_keywords]" id="seo_keywords" size="50" value="<?php echo $index['seo_keywords'];?>"/></td>
  </tr>
  <tr>
    <th width="120">SEO描述</th>
    <td class="y-bg">
    	<textarea name="index[seo_description]"  id="seo_description" rows="5" cols="50"><?php echo $index['seo_description'];?></textarea>
    </td>
  </tr>      
</table>
</div>
<div id="div_setting_3" class="contentList pad-10 hidden">
<table width="100%"  class="table_form">
  <tr>
    <th width="120">是否启用路由</th>
    <td class="y-bg">
	  <input name="route[open]" value="1" type="radio" <?php echo ($route['open']==1)?'checked':'';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
	  <input name="route[open]" value="0" type="radio" <?php echo ($route['open']==0)?'checked':'';?>> 否     </td>
  </tr>
  <tr>
    <th width="120">PC端文章列表页</th>
    <td class="y-bg"><input type="text" class="input-text" name="route[pc_lists]" id="pc_lists" size="30" value="<?php echo $route['pc_lists'];?>"/></td>
  </tr>
  <tr>
    <th width="120">PC端文章详细页</th>
    <td class="y-bg"><input type="text" class="input-text" name="route[pc_detail]" id="pc_detail" size="30" value="<?php echo $route['pc_detail'];?>"/></td>
  </tr>
  <tr>
    <th width="120">移动端文章列表页</th>
    <td class="y-bg"><input type="text" class="input-text" name="route[mb_lists]" id="mb_lists" size="30" value="<?php echo $route['mb_lists'];?>"/></td>
  </tr>
  <tr>
    <th width="120">移动端文章详细页</th>
    <td class="y-bg"><input type="text" class="input-text" name="route[mb_detail]" id="mb_detail" size="30" value="<?php echo $route['mb_detail'];?>"/></td>
  </tr>        
</table>
</div>
<div class="bk15"></div>
<input name="dosubmit" type="submit" value="提交" class="button button-tkp button-tiny">
</div>
</div>
</form>
</body>
<script type="text/javascript">
function SwapTab(name,cls_show,cls_hide,cnt,cur){
    for(i=1;i<=cnt;i++){
		if(i==cur){
			 $('#div_'+name+'_'+i).show();
			 $('#tab_'+name+'_'+i).attr('class',cls_show);
		}else{
			 $('#div_'+name+'_'+i).hide();
			 $('#tab_'+name+'_'+i).attr('class',cls_hide);
		}
	}
}
</script>
</html>