<?php
use Think\Controller;
class content_input extends Controller{
	var $modelid;
	var $fields;
	var $data;

    function __construct($modelid) {
		parent::__construct();
		$this->db = D('Sitemodelfield');
		$this->db_pre = $this->db->db_tablepre;
		$this->modelid = $modelid;
		$this->fields = $this->_get_fields($modelid);
    }
	private function _get_fields($modelid)
	{
		$data=M('content_model_field')->where(array('modelid'=>$modelid))->select();
		$fields=array();
		foreach($data as $v)
		{
			$fields[$v['field']]=$v;
		}
		return $fields;
	}
	
	function get($data,$isimport = 0) {
		$this->data = $data = trim_script($data);
		$info = array();
		foreach($data as $field=>$value) {
			if(!isset($this->fields[$field])) continue;
			
			$setting=string2array($this->fields[$field]['setting']);
			$this->fields[$field]=array_merge($this->fields[$field],$setting);
			unset($setting);
			
			$name = $this->fields[$field]['name'];
			$minlength = $this->fields[$field]['minlength'];
			$maxlength = $this->fields[$field]['maxlength'];
			$pattern = $this->fields[$field]['pattern'];
			$errortips = $this->fields[$field]['errortips'];
			if(empty($errortips)) $errortips = $name.' 不满足条件！';
			$length = empty($value) ? 0 : (is_string($value) ? strlen($value) : count($value));

			if($minlength && $length < $minlength) {
				if($isimport) {
					return false;
				} else {
					$this->error($name.' 不得少于 '.$minlength.' 个字符');
					exit;
				}
			}
			if($maxlength && $length > $maxlength) {
				if($isimport) {
					$value = str_cut($value,$maxlength,'');
				} else {
					$this->error($name.' 不得超过 '.$maxlength.' 个字符');
					exit;
				}
			} elseif($maxlength) {
				$value = str_cut($value,$maxlength,'');
			}
			if($pattern && $length && !preg_match($pattern, $value) && !$isimport) 
			{
				$this->error($errortips);
			}
            $this->db->table_name = $this->fields[$field]['issystem'] ? $this->db_pre.$MODEL[$this->modelid]['tablename'] : $this->db_pre.$MODEL[$this->modelid]['tablename'].'_data';
            if($this->fields[$field]['isunique'] && $this->db->get_one(array($field=>$value),$field) && ROUTE_A != 'edit') showmessage($name.L('the_value_must_not_repeat'));
			$func = $this->fields[$field]['formtype'];
			if(method_exists($this, $func)) $value = $this->$func($field, $value);
			if($this->fields[$field]['issystem']) {
				$info['system'][$field] = $value;
			} else {
				$info['model'][$field] = $value;
			}
			//颜色选择为隐藏域 在这里进行取值
			$info['system']['style'] = $_POST['style_color'] && preg_match('/^#([0-9a-z]+)/i', $_POST['style_color']) ? $_POST['style_color'] : '';
			if($_POST['style_font_weight']=='bold') $info['system']['style'] = $info['system']['style'].';'.strip_tags($_POST['style_font_weight']);
		}
		return $info;
	}
	function textarea($field, $value) {
		if(!$this->fields[$field]['enablehtml']) $value = strip_tags($value);
		return $value;
	}
	function editor($field, $value) {
		return $value;
	}
	function box($field, $value) {
		if($this->fields[$field]['boxtype'] == 'checkbox') {
			if(!is_array($value) || empty($value)) return false;
			array_shift($value);
			$value = ','.implode(',', $value).',';
			return $value;
		} elseif($this->fields[$field]['boxtype'] == 'multiple') {
			if(is_array($value) && count($value)>0) {
				$value = ','.implode(',', $value).',';
				return $value;
			}
		} else {
			return $value;
		}
	}
	function image($field, $value) {
		$value = remove_xss(str_replace(array("'",'"','(',')'),'',$value));
		$value  = safe_replace($value);
		return trim($value);
	}
	function images($field, $value) {
		return implode('@#|',safe_replace($value));
	}
	function datetime($field, $value) {
		$setting = string2array($this->fields[$field]['setting']);
		if($setting['fieldtype']=='int') {
			$value = strtotime($value);
		}
		return $value;
	}
	function posid($field, $value) {
		return ','.implode(',',$value).',';
	}
	function copyfrom($field, $value) {
		$field_data = $field.'_data';
		if(isset($_POST[$field_data])) {
			$value .= '|'.safe_replace($_POST[$field_data]);
		}
		return $value;
	}
	function groupid($field, $value) {
		$datas = '';
		if(!empty($_POST[$field]) && is_array($_POST[$field])) {
			$datas = implode(',',$_POST[$field]);
		}
		return $datas;
	}
	function downfile($field, $value) {
		//取得镜像站点列表
		$result = '';
		$server_list = count($_POST[$field.'_servers']) > 0 ? implode(',' ,$_POST[$field.'_servers']) : '';
		$result = $value.'|'.$server_list;
		return $result;
	}
	function downfiles($field, $value) {
		$files = $_POST[$field.'_fileurl'];
		$files_alt = $_POST[$field.'_filename'];
		$array = $temp = array();
		if(!empty($files)) {
			foreach($files as $key=>$file) {
					$temp['fileurl'] = $file;
					$temp['filename'] = $files_alt[$key];
					$array[$key] = $temp;
			}
		}
		$array = array2string($array);
		return $array;
	}
 } 
?>