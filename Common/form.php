<?php
class form {
	/**
	 * 编辑器
	 * @param int $textareaid
	 * @param int $toolbar 
	 * @param string $module 模块名称
	 * @param int $catid 栏目id
	 * @param int $color 编辑器颜色
	 * @param boole $allowupload  是否允许上传
	 * @param boole $allowbrowser 是否允许浏览文件
	 * @param string $alowuploadexts 允许上传类型
	 * @param string $height 编辑器高度
	 * @param string $disabled_page 是否禁用分页和子标题
	 */
	public static function editor($id,$type='all') {
		if(!defined('EDITOR_INIT')) {
			$str = '<script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'ueditor/ueditor.config.js"></script><script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'ueditor/ueditor.all.min.js"> </script><script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'ueditor/lang/zh-cn/zh-cn.js"></script>';
			define('EDITOR_INIT', 1);
		}
		if($type=='all')
		{
			$str.= "<script type=\"text/javascript\">
		$(function(){
			var ue = UE.getEditor('{$id}',{
				serverUrl :'".U('Plug/Public/ueditorUpload')."',toolbars:[[
				'fullscreen', 'source', '|', 'undo', 'redo', '|',
				'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
				'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
				'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
				'directionalityltr', 'directionalityrtl', 'indent', '|',
				'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
				'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
				'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
				'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
				'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
				'print', 'preview', 'searchreplace', 'help', 'drafts'
			]]
			});
		})
		</script>";
		}
        elseif($type=='simple')
        {
 		$str.= "<script type=\"text/javascript\">
			$(function(){
				var ue = UE.getEditor('{$id}',{
					serverUrl :'".U('Plug/Public/ueditorUpload')."',toolbars:[[\"fullscreen\",\"undo\",\"redo\",\"insertunorderedlist\",\"insertorderedlist\",\"unlink\",\"link\",\"|\",\"bold\",\"italic\",\"underline\",\"fontborder\",\"strikethrough\",\"forecolor\",\"backcolor\",\"superscript\",\"subscript\",\"justifyleft\",\"justifycenter\",\"justifyright\",\"justifyjustify\",\"inserttable\",\"deletetable\",\"mergeright\",\"mergedown\",\"splittorows\",\"splittocols\",\"splittocells\",\"mergecells\",\"insertcol\",\"insertrow\",\"deletecol\",\"deleterow\",\"insertparagraphbeforetable\",\"charts\"]]
				});
			})
			</script>"; 
		}
		return $str;
	}
	
	/**
	 * 
	 * @param string $name 表单名称
	 * @param int $value 表单值
	 * @param int $value 允许使用的图片数量
	 */
	public static function images($name,$value,$maxnum) {
			$str='
			<script type="text/javascript">
				function up_images_'.$name.'(arr)
				{
					var length=$(".img-list li").length;
					var str=\'\';
					for(var i=0;i<arr.length;i++){
						str+=\'<div class="atlas-box"><div class="img-box"><div class="close" onClick="remove_img_box(this)">X</div><input type="hidden" name="info['.$name.'][]" value="\'+arr[i][\'src\']+\'"><img src="\'+arr[i][\'src\']+\'" style="max-height: 135px; max-width:135px;"> </div></div>\';	
					}
					$("#img-boxs-atlas-'.$name.' .atlas-box").eq(length-1).before(str);
				}
				function remove_img_box(obj)
				{
					$(obj).parent().parent().remove();
				}		  
			</script>';	
		$str.='<div class="img-boxs-atlas" id="img-boxs-atlas-'.$name.'">';
			foreach($value as $val)
			{
				$str.='<div class="atlas-box">
				  <div class="img-box">
					<div class="close" onClick="remove_img_box(this)">X</div>
					<input type="hidden" name="info['.$name.'][]" value="'.$val.'">
					<img src="'.thumb_url($val,135,135).'" style=\' max-height: 135px; max-width:135px;\'> </div>
				</div>';				
			}
            $str.='<div class="atlas-box"><a class="add-image" href="javascript:void(0);" onclick="flashupload(up_images_'.$name.');return false;">
              <h1 class="plus">+</h1>
              添加图片 </a></div>
          </div>';
		 return $str; 	
	}

	/**
	 * 
	 * @param string $name 表单名称
	 * @param int $id 表单id
	 * @param string $value 表单默认值
	 * @param string $moudle 模块名称
	 * @param int $catid 栏目id
	 * @param int $size 表单大小
	 * @param string $class 表单风格
	 * @param string $ext 表单扩展属性 如果 js事件等
	 * @param string $alowexts 允许上传的文件格式
	 * @param array $file_setting 
	 */
	public static function upfiles($name, $id = '', $value = '', $moudle='', $catid='', $size = 50, $class = '', $ext = '', $alowexts = '',$file_setting = array() ) {
		if(!$id) $id = $name;
		if(!$size) $size= 50;
		if(!empty($file_setting) && count($file_setting)) $file_ext = $file_setting[0].','.$file_setting[1];
		else $file_ext = ',';
		if(!$alowexts) $alowexts = 'rar|zip';
		if(!defined('IMAGES_INIT')) {
			$str = '<script type="text/javascript" src="'.JS_PATH.'swfupload/swf2ckeditor.js"></script>';
			define('IMAGES_INIT', 1);
		}
		$authkey = upload_key("1,$alowexts,1,$file_ext");
		return $str."<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/>  <input type=\"button\" class=\"button\" onclick=\"javascript:flashupload('{$id}_files', '".L('attachmentupload')."','{$id}',submit_attachment,'1,{$alowexts},1,{$file_ext}','{$moudle}','{$catid}','{$authkey}')\"/ value=\"".L('filesupload')."\">";
	}
	
	/**
	 * 日期时间控件
	 * 
	 * @param $name 控件name，id
	 * @param $value 选中值
	 * @param $isdatetime 是否显示时间
	 * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
	 * @param $showweek 是否显示周，使用，true | false
	 */
	public static function date($name, $value = '', $isdatetime = 0, $loadjs = 0, $showweek = 'true', $timesystem = 1) {
		if($value == '0000-00-00 00:00:00') $value = '';
		$id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
		if($isdatetime) {
			$size = 21;
			$format = '%Y-%m-%d %H:%M:%S';
			if($timesystem){
				$showsTime = 'true';
			} else {
				$showsTime = '20';
			}
			
		} else {
			$size = 15;
			$format = '%Y-%m-%d';
			$showsTime = 'false';
		}
		$str = '';
		if($loadjs || !defined('CALENDAR_INIT')) {
			define('CALENDAR_INIT', 1);
			$str .= '<script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'calendar/WdatePicker.js"></script>';
		}
		$str .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="'.$size.'" class="Wdate input-text" onclick="WdatePicker()" readonly>&nbsp;';
		return $str;
	}
	/**
	 * 下拉选择框
	 */
	public static function select($array = array(), $id = 0, $str = '', $default_option = '') {
		$string = '<select '.$str.'>';
		$default_selected = (empty($id) && $default_option) ? 'selected' : '';
		if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
		if(!is_array($array) || count($array)== 0) return false;
		$ids = array();
		if(isset($id)) $ids = explode(',', $id);
		foreach($array as $key=>$value) {
			$selected = in_array($key, $ids) ? 'selected' : '';
			$string .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		$string .= '</select>';
		return $string;
	}
	
	/**
	 * 复选框
	 * 
	 * @param $array 选项 二维数组
	 * @param $id 默认选中值，多个用 '逗号'分割
	 * @param $str 属性
	 * @param $defaultvalue 是否增加默认值 默认值为 -99
	 * @param $width 宽度
	 */
	public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '') {
		$string = '';
		$id = trim($id);
		if($id != '') $id = strpos($id, ',') ? explode(',', $id) : array($id);
		if($defaultvalue) $string .= '<input type="hidden" '.$str.' value="-99">';
		$i = 1;
		foreach($array as $key=>$value) {
			$key = trim($key);
			$checked = ($id && in_array($key, $id)) ? 'checked' : '';
			if($width) $string .= '<label class="ib" style="width:'.$width.'px">';
			$string .= '<input type="checkbox" '.$str.' id="'.$field.'_'.$i.'" '.$checked.' value="'.new_html_special_chars($key).'"> '.new_html_special_chars($value);
			if($width) $string .= '</label>';
			$i++;
		}
		return $string;
	}

	/**
	 * 单选框
	 * 
	 * @param $array 选项 二维数组
	 * @param $id 默认选中值
	 * @param $str 属性
	 */
	public static function radio($array = array(), $id = 0, $str = '', $width = 0, $field = '') {
		$string = '';
		foreach($array as $key=>$value) {
			$checked = trim($id)==trim($key) ? 'checked' : '';
			if($width) $string .= '<label class="ib" style="width:'.$width.'px">';
			$string .= '<input type="radio" '.$str.' id="'.$field.'_'.new_html_special_chars($key).'" '.$checked.' value="'.$key.'"> '.$value;
			if($width) $string .= '</label>';
		}
		return $string;
	}
}

?>