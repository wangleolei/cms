<?php
class content_form {
	var $modelid;
	var $fields;
	var $id;
	var $formValidator;
    function __construct($modelid,$catid = 0,$categorys = array()) {
		$this->modelid = $modelid;
		$this->catid = $catid;
		$this->categorys = $categorys;
		$this->fields = $this->_get_fields($modelid);
    }
	private function _get_fields($modelid)
	{
		require MODULE_PATH.'Controller/fields/fields.inc.php';
		$data=M('content_model_field')->where(array('disabled'=>0,'modelid'=>$modelid,'field'=>array('not in',implode(',',$blank_fields))))->order('listorder ASC')->select();
		$fields=array();
		foreach($data as $v)
		{
			$fields[$v['field']]=$v;
		}
		return $fields;
	}
	function get($data = array()) {
		$this->data = $data;
		if(isset($data['id'])) $this->id = $data['id'];
		$info = array();
		$this->content_url = $data['url'];
		foreach($this->fields as $field=>$v) {
/*			if(defined('THINK_PATH')) {
				if($v['iscore'] || check_in($_SESSION['roleid'], $v['unsetroleids'])) continue;
			} else {
				if($v['iscore'] || !$v['isadd'] || check_in($_groupid, $v['unsetgroupids'])) continue;
			}*/
			$func = $v['formtype'];
			$value = isset($data[$field]) ? new_html_special_chars($data[$field]) : '';
			if($func=='pages' && isset($data['maxcharperpage'])) {
				$value = $data['paginationtype'].'|'.$data['maxcharperpage'];
			}
			if(!method_exists($this, $func)) continue;
			$form = $this->$func($field, $value, $v);
			if($form !== false) {
				if(defined('THINK_PATH')) {
					if($v['isbase']) {
						$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
						$info['base'][$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'isomnipotent'=>$v['isomnipotent'],'formtype'=>$v['formtype']);
					} else {
						$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
						$info['senior'][$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'isomnipotent'=>$v['isomnipotent'],'formtype'=>$v['formtype']);
					}
				} else {
					$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
					$info[$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'isomnipotent'=>$v['isomnipotent'],'formtype'=>$v['formtype']);
				}
			}
		}
		return $info;
	}
	function text($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		$size = $setting['size'];
		if(!$value) $value = $defaultvalue;
		$type = $ispassword ? 'password' : 'text';
		$errortips = $this->fields[$field]['errortips'];
		if($errortips || $minlength) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		return '<input type="text" name="info['.$field.']" id="'.$field.'" size="'.$size.'" value="'.$value.'" class="input-text" '.$formattribute.' '.$css.'>';
	}
	function textarea($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		extract($setting);
		if(!$value) $value = $defaultvalue;
		$allow_empty = 'empty:true,';
		if($minlength || $pattern) $allow_empty = '';
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({'.$allow_empty.'onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		$value = empty($value) ? $setting[defaultvalue] : $value;
		$str = "<textarea name='info[{$field}]' id='$field' style='width:{$width}%;height:{$height}px;' $formattribute $css";
		if($maxlength) $str .= " onkeyup=\"strlen_verify(this, '{$field}_len', {$maxlength})\"";
		$str .= ">{$value}</textarea>";
		if($maxlength) $str .= '可以输入<B><span id="'.$field.'_len">'.$maxlength.'</span></B> 个字符';
		return $str;
	}
	function editor($field,$value,$fieldinfo) {
		extract($fieldinfo);
		extract(string2array($setting));
		$disabled_page = isset($disabled_page) ? $disabled_page : 0;
		if(!$height) $height = 300;
		return "<div id='{$field}_tip'></div>".'<textarea style="height:200px; width:100%; z-index:0;" name="info['.$field.']" id="'.$field.'" boxid="'.$field.'">'.$value.'</textarea>'.form::editor($field,'all');
	}
	function catid($field, $value, $fieldinfo) {
		 $value=($_GET['catid'])?$_GET['catid']:$value;
		 $m_auth_group=M('content_category_priv');
		 if(!$value)
		 {
			$catids=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('add');
		 }
		 else
		 {
			$catids=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('edit');
		 }
		 $str='<select disabled="disabled">';
		 $str.=((session('userinfo.userid')==1) or (session('userinfo.groupid')==1))?D('category')->category2select(0,$value,array('modelids'=>array('like','%,'.$this->modelid.',%'))):D('category')->category2select(0,$value,array('id'=>array('in','0,'.$catids),'modelids'=>array('like','%,'.$this->modelid.',%')));
		 $str.='</select><input type="hidden" name="info['.$field.']" value="'.$value.'">';
		 return $str;
	}
	function title($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$style_arr = explode(';',$this->data['style']);
		$style_color = $style_arr[0];
		$style_font_weight = $style_arr[1] ? $style_arr[1] : '';

		$style = 'color:'.$this->data['style'];
		if(!$value) $value = $defaultvalue;
		$errortips = $this->fields[$field]['errortips'];
		$errortips_max = '标题不能为空！';
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips_max.'"});';
		$str = '<input type="text" style="width:400px;'.($style_color ? 'color:'.$style_color.';' : '').($style_font_weight ? 'font-weight:'.$style_font_weight.';' : '').'" name="info['.$field.']" id="'.$field.'" value="'.$value.'" style="'.$style.'" class="measure-input " onkeyup="strlen_verify(this, \'title_len\', '.$maxlength.');"/>';
		if(defined('THINK_PATH')) $str .= '<input type="button" class="button button-tkp button-tiny" id="check_title_alt" value="检测重复" onclick="$.get(\''.U('public_check_title',array('modelid'=>$_GET['modelid'])).'\', {data:$(\'#title\').val()}, function(data){if(data==\'1\') {$(\'#check_title_alt\').val(\''.L('title_repeat').'\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data==\'0\') {$(\'#check_title_alt\').val(\'标题没有重复\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}})"/>';
		$str .= ' 可以输入<B><span id="title_len">'.$maxlength.'</span></B> 个字符';
		return $str;
	}
	function box($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		if($value=='') $value = $setting['defaultvalue'];
		$options = explode("\n",$setting['options']);
		foreach($options as $_k) {
			$v = explode("|",$_k);
			$k = trim($v[1]);
			$option[$k] = $v[0];
		}
		$values = explode(',',$value);
		$value = array();
		foreach($values as $_k) {
			if($_k != '') $value[] = $_k;
		}
		$value = implode(',',$value);
		switch($setting['boxtype']) {
			case 'radio':
				$string = form::radio($option,$value,"name='info[$field]' $fieldinfo[formattribute]",$setting['width'],$field);
			break;

			case 'checkbox':
				$string = form::checkbox($option,$value,"name='info[$field][]' $fieldinfo[formattribute]",1,$setting['width'],$field);
			break;

			case 'select':
				$string = form::select($option,$value,"name='info[$field]' id='$field' $fieldinfo[formattribute]");
			break;

			case 'multiple':
				$string = form::select($option,$value,"name='info[$field][]' id='$field ' size=2 multiple='multiple' style='height:60px;' $fieldinfo[formattribute]");
			break;
		}
		return $string;
	}
	function image($field, $value, $fieldinfo) {
		$html='';
		$setting = string2array($fieldinfo['setting']);
		extract($setting);
			//图片上传
			$str = '<script type="text/javascript">
				function crert_str_'.$field.'(arr)
				{
					if(arr.length>=1)
					{
						$("#'.$field.'_preview").attr(\'src\',arr[0].src);
						$("#img_'.$field.'").val(arr[0].src);	
					}
				}</script>';
			//图片剪切
			$str.='<script type="text/javascript">function crop_cut_'.$field.'(src){
	if ($.trim(src)==\'\') { window.top.art.dialog.alert(\'请先上传缩略图\');return false;}
	window.top.art.dialog.open(\''.U('Plug/Public/cropzoom').'?picurl=\'+encodeURIComponent(src),{title:\'裁切图片\', id:\'crop\', width:690, height:420,lock:true,ok:function(){
		var iframe = this.iframe.contentWindow;
		var obj = iframe.document;
		var src=$(obj).find("#generated").attr(\'src\');
		if(src!=\'\')
		{
			$("#'.$field.'_preview").attr(\'src\',src);
			$("#img_'.$field.'").val(src);
		}
		return true;
		},cancel:function(){window.top.art.dialog({id:\'crop\'}).close()}});
}</script>';
		$src=empty($value)?'/Public/images/icon/upload-pic.png':$value;
		if (defined('THINK_PATH')) {
		$str.='<div class="upload-pic img-wrap" style="height: auto; background:#fff; padding-bottom: 10px;"><input type="hidden" name="info['.$field.']" id="img_'.$field.'" value="'.$value.'"/>
			<a href="javascript:void(0);" onclick="flashupload(crert_str_'.$field.');return false;">
			<img src="'.$src.'" id="'.$field.'_preview" width="135" height="113" style="cursor:hand" /></a><input type="button"  class="button button-tkp button-tiny" onclick="crop_cut_'.$field.'($(\'#img_'.$field.'\').val());return false;" value="裁切图片"><input type="button"  class="button button-tkp button-tiny" onclick="$(\'#'.$field.'_preview\').attr(\'src\',\'/Public/images/icon/upload-pic.png\');$(\'#img_'.$field.'\').val(\'\');return false;" value="取消图片"></div>';			 			
			}
		return $str;	
		//if($show_type && defined('THINK_PATH')) {} else {}
	}
	function images($field, $value, $fieldinfo) {
		$value=array_filter(explode('@#|',$value));
		return form::images($field,$value);
		}	
    function number($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		$size = $setting['size'];		
		if(!$value) $value = $defaultvalue;
		return "<input type='text' name='info[$field]' id='$field' value='$value' class='input-text' size='$size' {$formattribute} {$css}>";
	}
	function datetime($field, $value, $fieldinfo) {
		extract(string2array($fieldinfo['setting']));
		extract($fieldinfo);
		if($errortips || $minlength) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		$isdatetime = 0;
		$timesystem = 0;
		if($fieldtype=='int') {
			if(!$value) $value = SYS_TIME;
			$format_txt = $format == 'm-d' ? 'm-d' : $format;
			if($format == 'Y-m-d Ah:i:s') $format_txt = 'Y-m-d h:i:s';
			$value = date($format_txt,$value);
			
			$isdatetime = strlen($format) > 6 ? 1 : 0;
			if($format == 'Y-m-d Ah:i:s') {
				
				$timesystem = 0;
			} else {
				$timesystem = 1;
			}			
		} elseif($fieldtype=='datetime') {
			$isdatetime = 1;
			$timesystem = 1;
		} elseif($fieldtype=='datetime_a') {
			$isdatetime = 1;
			$timesystem = 0;
		}
		return form::date("info[$field]",$value,$isdatetime,1,'true',$timesystem);
	}
	function posid($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		$condition=array();
		$condition['modelid']=$fieldinfo['modelid'];
		$condition['status']=1;
		$position = M('content_position')->where($condition)->select();
		if(empty($position)) return '';
		$array = array();
		foreach($position as $_value) {
			if($_value['modelid'] &&  ($_value['catid'] && strpos(','.$this->categorys[$_value['catid']]['arrchildid'].',',','.$this->catid.',')===false)) continue;
			$array[$_value['id']] = $_value['name'];
		}
		if($value) {
			$posids = trim($value,',');
		} else {
			$posids = $setting['defaultvalue'];
		}
		$str='<input type="hidden" name="info['.$field.'][]" class="hidden" value="0">';
		return $str.form::checkbox($array,$posids,"name='info[$field][]'",'',$setting['width']);
	}
	function keyword($field, $value, $fieldinfo) {
		extract($fieldinfo);
		if(!$value) $value = $defaultvalue;
		return "<input type='text' name='info[$field]' id='$field' value='$value' style='width:280px' {$formattribute} {$css} class='input-text'>";
	}
	function author($field, $value, $fieldinfo) {
		return '<input type="text" name="info['.$field.']" value="'.$value.'" size="30">';
	}
	function copyfrom($field, $value, $fieldinfo) {
		$value_data = '';
		if($value && strpos($value,'|')!==false) {
			$arr = explode('|',$value);
			$value = $arr[0];
			$value_data = $arr[1];
		}
		$copyfrom_array = getcache('copyfrom','admin');
		$copyfrom_datas = array(L('copyfrom_tips'));
		if(!empty($copyfrom_array)) {
			foreach($copyfrom_array as $_k=>$_v) {
				if($this->siteid==$_v['siteid']) $copyfrom_datas[$_k] = $_v['sitename'];
			}
		}
		return "<input type='text' name='info[$field]' value='$value' style='width: 400px;' class='input-text'>".form::select($copyfrom_datas,$value_data,"name='{$field}_data' ");
	}
	function groupid($field, $value, $fieldinfo) {
		extract(string2array($fieldinfo['setting']));
		//$grouplist = getcache('grouplist','member');
/*		foreach($grouplist as $_key=>$_value) {
			$data[$_key] = $_value['name'];
		}*/
		return '<input type="hidden" name="info['.$field.']" value="1">'.form::checkbox($data,$value,'name="'.$field.'[]" id="'.$field.'"','','120');
	}
	function islink($field, $value, $fieldinfo) {
		if($value) {
			$url = $this->data['url'];
			$checked = 'checked';
			$_GET['islink'] = 1;
		} else {
			$disabled = 'disabled';
			$url = $checked = '';
			$_GET['islink'] = 0;
		}
		$size = $fieldinfo['size'] ? $fieldinfo['size'] : 25;
		return '<input type="hidden" name="info[islink]" value="0"><input type="text" name="linkurl" id="linkurl" value="'.$url.'" size="'.$size.'" maxlength="255" '.$disabled.' class="input-text"> <input name="info[islink]" type="checkbox" id="islink" value="1" onclick="ruselinkurl();" '.$checked.'> <font color="red">转向链接</font><script>function ruselinkurl() {if($(\'#islink\').attr(\'checked\')==\'checked\') {
$(\'#linkurl\').attr(\'disabled\',false); return false;} else {$(\'#linkurl\').attr(\'disabled\',\'true\');}}</script>';
	}
	function template($field, $value, $fieldinfo) {
		//$sitelist = getcache('sitelist','commons');
		$default_style = $sitelist[$this->siteid]['default_style'];
		return form::select_template($default_style,'content',$value,'name="info['.$field.']" id="'.$field.'"','show');
	}
	function pages($field, $value, $fieldinfo) {
		extract($fieldinfo);
		if($value) {
			$v = explode('|', $value);
			$data = "<select name=\"info[paginationtype]\" id=\"paginationtype\" onchange=\"if(this.value==1)\$('#paginationtype1').css('display','');else \$('#paginationtype1').css('display','none');\">";
			$type = array(L('page_type1'), L('page_type2'), L('page_type3'));
			if($v[0]==1) $con = 'style="display:"';
			else $con = 'style="display:none"';
			foreach($type as $i => $val) {
				if($i==$v[0]) $tag = 'selected';
				else $tag = '';
				$data .= "<option value=\"$i\" $tag>$val</option>";
			}
			$data .= "</select><span id=\"paginationtype1\" $con><input name=\"info[maxcharperpage]\" type=\"text\" id=\"maxcharperpage\" value=\"$v[1]\" size=\"8\" maxlength=\"8\">个字符每页</span>";
			return $data;
		} else {
			return "<select name=\"info[paginationtype]\" id=\"paginationtype\" onchange=\"if(this.value==1)\$('#paginationtype1').css('display','');else \$('#paginationtype1').css('display','none');\">
                <option value=\"0\">不分页</option>
                <option value=\"1\">自动分页</option>
                <option value=\"2\">手动分页</option>
            </select>
			<span id=\"paginationtype1\" style=\"display:none\"><input name=\"info[maxcharperpage]\" type=\"text\" id=\"maxcharperpage\" value=\"10000\" size=\"8\" maxlength=\"8\">".L('page_maxlength')."</span>";
		}
	}
	function readpoint($field, $value, $fieldinfo) {
		$paytype = $this->data['paytype'];
		if($paytype) {
			$checked1 = '';
			$checked2 = 'checked';
		} else {
			$checked1 = 'checked';
			$checked2 = '';
		}
		return '<input type="text" name="info['.$field.']" value="'.$value.'" size="5"><input type="radio" name="info[paytype]" value="0" '.$checked1.'> 积分 <input type="radio" name="info[paytype]" value="1" '.$checked2.'> 金钱';
	}
	function linkage($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		$linkageid = $setting['linkageid'];
		return menu_linkage($linkageid,$field,$value);
	}
	function downfile($field, $value, $fieldinfo) {
		$list_str = $str = '';
		extract(string2array($fieldinfo['setting']));
		if($value){
			$value_arr = explode('|',$value);
			$value = $value_arr['0'];
			$sel_server = $value_arr['1'] ? explode(',',$value_arr['1']) : '';
			$edit = 1;
		} else {
			$edit = 0;
		}
		//$server_list = getcache('downservers','commons');
		if(is_array($server_list)) {
			foreach($server_list as $_k=>$_v) {
				if (in_array($_v['siteid'],array(0,$fieldinfo['siteid']))) {
					$checked = $edit ? ((is_array($sel_server) && in_array($_k,$sel_server)) ? ' checked' : '') : ' checked';
					$list_str .= "<lable id='downfile{$_k}' class='ib lh24' style='width:25%'><input type='checkbox' value='{$_k}' name='{$field}_servers[]' {$checked}>  {$_v['sitename']}</lable>";
				}
			}
		}
	
		$string = '
		<fieldset class="blue pad-10">
        <legend>'.L('mirror_server_list').'</legend>';
		$string .= $list_str;
		$string .= '</fieldset>
		<div class="bk10"></div>
		';	
		if(!defined('IMAGES_INIT')) {
			/*
			$str = '<script type="text/javascript" src="'.C('JS_PATH').'swfupload/swf2ckeditor.js"></script>';
			define('IMAGES_INIT', 1);
			*/
		}	
		//$authkey = upload_key("$upload_number,$upload_allowext,$isselectimage");	
		$string .= $str."<input type='text' name='info[$field]' id='$field' value='$value' class='input-text' style='width:80%'/>  <input type='button' class='button button-tkp button-tiny' onclick=\"javascript:flashupload('{$field}_downfield', '".L('attachment_upload')."','{$field}',submit_files,'{$upload_number},{$upload_allowext},{$isselectimage}','content','$this->catid','{$authkey}')\"/ value='软件上传'>";
		return $string;
	}
	function downfiles($field, $value, $fieldinfo) {
		extract(string2array($fieldinfo['setting']));
		$list_str = '';
		if($value) {
			$value = string2array(new_html_entity_decode($value));
			if(is_array($value)) {
				foreach($value as $_k=>$_v) {
				$list_str .= "<div id='multifile{$_k}'><input type='text' name='{$field}_fileurl[]' value='{$_v[fileurl]}' style='width:310px;' class='input-text'> <input type='text' name='{$field}_filename[]' value='{$_v[filename]}' style='width:160px;' class='input-text'> <a href=\"javascript:remove_div('multifile{$_k}')\">".L('remove_out')."</a></div>";
				}
			}
		}
		$string = '<input name="info['.$field.']" type="hidden" value="1">
		<fieldset class="blue pad-10">
        <legend>'.L('file_list').'</legend>';
		$string .= $list_str;
		$string .= '<ul id="'.$field.'" class="picList"></ul>
		</fieldset>
		<div class="bk10"></div>
		';
		
		if(!defined('IMAGES_INIT')) {
			/*
			$str = '<script type="text/javascript" src="'.C('JS_PATH').'swfupload/swf2ckeditor.js"></script>';
			*/
			define('IMAGES_INIT', 1);
		}
		//$authkey = upload_key("$upload_number,$upload_allowext,$isselectimage");
		$string .= $str."<input type=\"button\"  class=\"button\" value=\"".L('multiple_file_list')."\" onclick=\"javascript:flashupload('{$field}_multifile', '".L('attachment_upload')."','{$field}',change_multifile,'{$upload_number},{$upload_allowext},{$isselectimage}','content','$this->catid','{$authkey}')\"/>    <input type=\"button\" class=\"button\" value=\"".L('add_remote_url')."\" onclick=\"add_multifile('{$field}')\">";
		return $string;
	}
	function omnipotent($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$formtext = str_replace('{FIELD_VALUE}',$value,$formtext);
		$formtext = str_replace('{MODELID}',$this->modelid,$formtext);
		preg_match_all('/{FUNC\((.*)\)}/',$formtext,$_match);
		foreach($_match[1] as $key=>$match_func) {
			$string = '';
			$params = explode('~~',$match_func);
			$user_func = $params[0];
			$string = $user_func($params[1]);
			$formtext = str_replace($_match[0][$key],$string,$formtext);
		}
		$id  = $this->id ? $this->id : 0;
		$formtext = str_replace('{ID}',$id,$formtext);
		$errortips = $this->fields[$field]['errortips'];
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips.'"});';

		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		return $formtext;
	}
	//资产公司
	function company($field, $value, $fieldinfo)
	{
		$catid=($_GET['catid'])?$_GET['catid']:$this->data['catid'];
		$parentid=M('content_category')->where(array('id'=>$catid))->getField('parentid');
		switch($parentid)
		{
			case 82:
			    $cid=86;
				break;
			case 87:
			    $cid=88;
				break;
			case 93:
			    $cid=94;
				break;				
		}
		$m_com=M('content_zcgs')->where(array('catid'=>$cid))->select();
		$str='<select name="info['.$field.']">';
		foreach($m_com as $v)
		{
			$selected=($v['id']==$value)?'selected':'';
			$str.='<option value="'.$v['id'].'" '.$selected.'>'.$v['title'].'</option>';
		}
		return $str.='</select>';
	}
 } 
?>