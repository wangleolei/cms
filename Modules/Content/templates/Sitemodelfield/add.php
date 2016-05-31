<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();?>
<script type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform"});
	$("#field").formValidator({onshow:"请输入字段名",onfocus:"字段名长度为1-20个字符"}).regexValidator({regexp:"^[a-zA-Z]{1}([a-zA-Z0-9]|[_]){0,19}$",onerror:"字段名格式错误"}).inputValidator({min:1,max:20,onerror:"字段名长度为1-20个字符"}).ajaxValidator({
	    type : "get",
		url : "<?php echo U('public_checkfield',array('modelid'=>$modelid));?>",
		data : "",
		datatype : "html",
		cached:false,
		getdata:{issystem:'issystem'},
		async:'false',
		success : function(data){	
            if( data == "1" ){
                return true;
			} else {
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "字段已经存在",
		onwait : "等待中..."
	});
	$("#formtype").formValidator({onshow:"请选择字段类型",onfocus:"请选择字段类型",oncorrect:"输入正确",defaultvalue:""}).inputValidator({min:1,onerror: "请选择字段类型"});
	$("#name").formValidator({onshow:"请输入字段名",onfocus:"请输入字段名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请输入字段名"});
})

//-->
</script>
<div class="pad_10">
<div class="subnav">
  <h2 class="title-1 line-x f14 fb blue lh28">模型管理--<?php echo $m_r['name'];?>字段管理</h2>
<div class="content-menu ib-a blue line-x"><a class="button button-tkp button-tiny" href="#"><em>添加字段</em></a>
　<a href="<?php echo U('index',array('modelid'=>$modelid));?>"><em>管理模型字段</em></a><span></span></div>
  <div class="bk10"></div>
</div>
<form name="myform" id="myform" action="" method="post">
<div class="common-form">

<table width="100%" class="table_form contentWrap">
	<tr> 
      <th><strong>字段类型</strong><br /></th>
      <td>
<?php echo form::select($all_field,'','name="info[formtype]" id="formtype" onchange="javascript:field_setting(this.value);"','选择字段类型');?>
	  </td>
    </tr>
	<tr> 
      <th><strong>作为主表字段</strong></th>
      <td>
      <input type="hidden" name="issystem" id="issystem" value="0">
      <input type="radio" name="info[issystem]" id="field_basic_table1" value="1" onclick="$('#issystem').val('1');"> 是 <input type="radio" id="field_basic_table0" name="info[issystem]" value="0" onclick="$('#issystem').val('0');" checked> 否</td>
    </tr>
	<tr> 
      <th width="25%"><font color="red">*</font> <strong>字段名称</strong><br />
	  只能由英文字母、数字和下划线组成，并且仅能字母开头，不以下划线结尾
	  </th>
      <td><input type="text" name="info[field]" id="field" size="20" class="input-text"></td>
    </tr>
	<tr> 
      <th><font color="red">*</font> <strong>字段别名</strong><br />例如：文章标题</th>
      <td><input type="text" name="info[name]" id="name" size="30" class="input-text"></td>
    </tr>
	<tr> 
      <th><strong>字段提示</strong><br />显示在字段别名下方作为表单输入提示</th>
      <td><textarea name="info[tips]" rows="2" cols="20" id="tips" style="height:40px; width:80%"></textarea></td>
    </tr>

	<tr> 
      <th><strong>相关参数</strong><br />设置表单相关属性</th>
      <td><div id="setting"></div></td>
    </tr>

	<tr id="formattribute"> 
      <th><strong>表单附加属性</strong><br />可以通过此处加入javascript事件</th>
      <td><input type="text" name="info[formattribute]" value="" size="50" class="input-text"></td>
    </tr>
	<tr id="css"> 
      <th><strong>表单样式名</strong><br />定义表单的CSS样式名</th>
      <td><input type="text" name="info[css]" value="" size="10" class="input-text"></td>
    </tr>

	<tr> 
      <th><strong>字符长度取值范</strong><br />系统将在表单提交时检测数据长度范围是否符合要求，如果不想限制长度请留空</th>
      <td>最小值：<input type="text" name="info[minlength]" id="field_minlength" value="0" size="5" class="input-text"> 最大值：<input type="text" name="info[maxlength]" id="field_maxlength" value="" size="5" class="input-text"></td>
    </tr>
	<tr> 
      <th><strong>数据校验正则</strong><br />系统将通过此正则校验表单提交的数据合法性，如果不想校验数据请留空</th>
      <td><input type="text" name="info[pattern]" id="pattern" value="" size="40" class="input-text"> 
<select name="pattern_select" onchange="javascript:$('#pattern').val(this.value)">
<option value="">常用正则</option>
<option value="/^[0-9.-]+$/">数字</option>
<option value="/^[0-9-]+$/">整数</option>
<option value="/^[a-z]+$/i">字母</option>
<option value="/^[0-9a-z]+$/i">数字+字母</option>
<option value="/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/">E-mail</option>
<option value="/^[0-9]{5,20}$/">QQ</option>
<option value="/^http:\/\//">超链接</option>
<option value="/^(1)[0-9]{10}$/">手机</option>
<option value="/^[0-9-]{6,13}$/">电话</option>
<option value="/^[0-9]{6}$/">邮政编码</option>
</select>
	  </td>
    </tr>
	<tr> 
      <th><strong>输入错误提示</strong></th>
      <td><input type="text" name="info[errortips]" value="" size="50" class="input-text"></td>
    </tr>
	<tr> 
      <th><strong>值唯一</strong></th>
      <td><input type="radio" name="info[isunique]" value="1" id="field_allow_isunique1"> 是 <input type="radio" name="info[isunique]" value="0" id="field_allow_isunique0" checked> 否</td>
    </tr>
	<tr> 
      <th><strong>作为基本信息</strong><br />基本信息将在添加页面左侧显示</th>
      <td><input type="radio" name="info[isbase]" value="1"  checked> 是 <input type="radio" name="info[isbase]" value="0"> 否 </td>
    </tr>
	<tr> 
      <th><strong>作为搜索条件</strong></th>
      <td><input type="radio" name="info[issearch]" value="1" id="field_allow_search1"> 是 <input type="radio" name="info[issearch]" value="0" id="field_allow_search0" checked> 否</td>
    </tr>
	<tr> 
      <th><strong>作为全站搜索信息</strong></th>
      <td><input type="radio" name="info[isfulltext]" value="1" id="field_allow_fulltext1" checked/> 是 <input type="radio" name="info[isfulltext]" value="0" id="field_allow_fulltext0" /> 否</td>
    </tr>
	<tr> 
      <th><strong>作为万能字段的附属字段</strong><br>必须与万能字段结合起来使用，否则内容添加的时候不会正常显示</th>
      <td><input type="radio" name="info[isomnipotent]" value="1" /> 是 <input type="radio" name="info[isomnipotent]" value="0" checked/> 否</td>
    </tr>
	<tr> 
      <th><strong>在推荐位标签中调用</strong></th>
      <td><input type="radio" name="info[isposition]" value="1" /> 是 <input type="radio" name="info[isposition]" value="0" checked/> 否</td>
    </tr>
</table>

</div>
    <div class="bk15"></div>
    <input name="info[modelid]" type="hidden" value="<?php echo $modelid?>">
    <div class="btn"><input name="dosubmit" type="submit" value="提交" class="button button-tkp button-tiny"></div>
	</form>

<script type="text/javascript">
	<!--
	function field_setting(fieldtype) {
	$('#formattribute').css('display','none');
	$('#css').css('display','none');
	$.each( ['<?php echo implode("','",$att_css_js);?>'], function(i, n){
		if(fieldtype==n) {
			$('#formattribute').css('display','');
			$('#css').css('display','');
		}
	}); 
		$.getJSON("<?php echo U('public_field_setting');?>?fieldtype="+fieldtype, function(data){
			if(data.field_basic_table=='1') {
				$('#field_basic_table0').attr("disabled",false);
				$('#field_basic_table1').attr("disabled",false);
			} else {
				$('#field_basic_table0').attr("checked",true);
				$('#field_basic_table0').attr("disabled",true);
				$('#field_basic_table1').attr("disabled",true);
			}
			if(data.field_allow_search=='1') {
				$('#field_allow_search0').attr("disabled",false);
				$('#field_allow_search1').attr("disabled",false);
			} else {
				$('#field_allow_search0').attr("checked",true);
				$('#field_allow_search0').attr("disabled",true);
				$('#field_allow_search1').attr("disabled",true);
			}
			if(data.field_allow_fulltext=='1') {
				$('#field_allow_fulltext0').attr("disabled",false);
				$('#field_allow_fulltext1').attr("disabled",false);
			} else {
				$('#field_allow_fulltext0').attr("checked",true);
				$('#field_allow_fulltext0').attr("disabled",true);
				$('#field_allow_fulltext1').attr("disabled",true);
			}
			if(data.field_allow_isunique=='1') {
				$('#field_allow_isunique0').attr("disabled",false);
				$('#field_allow_isunique1').attr("disabled",false);
			} else {
				$('#field_allow_isunique0').attr("checked",true);
				$('#field_allow_isunique0').attr("disabled",true);
				$('#field_allow_isunique1').attr("disabled",true);
			}
			$('#field_minlength').val(data.field_minlength);
			$('#field_maxlength').val(data.field_maxlength);
			$('#setting').html(data.setting);
	
		});
	}

//-->
</script>
</body>
</html>