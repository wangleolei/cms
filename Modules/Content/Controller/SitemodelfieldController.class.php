<?php
namespace Content\Controller;
class SitemodelfieldController extends \Admin\Classes\AdminController{
	private $db,$model_db;
	function __construct() {
		parent::__construct();
		$this->db = D('Sitemodelfield');
		$this->model_db = D('Sitemodel');
	}
	public function index() {
		$modelid = $_GET['modelid'];
		$datas = $this->db->where(array(array('modelid'=>$modelid)))->order('listorder ASC')->select();
		$r = $this->model_db->where(array('modelid'=>$modelid))->find();
		require __DIR__.'/fields/fields.inc.php';
		include $this->admin_tpl();
	}
	public function add() {
		if(IS_POST) {
			$modelid = $_POST['info']['modelid'] = intval($_POST['info']['modelid']);
			$model_table = $this->model_db->where(array('modelid'=>$modelid))->getField('tablename');
			$tablename = $_POST['issystem'] ? C('DB_PREFIX').'content_'.$model_table : C('DB_PREFIX').'content_'.$model_table.'_data';

			$field = $_POST['info']['field'];
			$minlength = $_POST['info']['minlength'] ? $_POST['info']['minlength'] : 0;
			$maxlength = $_POST['info']['maxlength'] ? $_POST['info']['maxlength'] : 0;
			$field_type = $_POST['info']['formtype'];
			
			require 'fields'.DIRECTORY_SEPARATOR.$field_type.DIRECTORY_SEPARATOR.'config.inc.php';
			
			if(isset($_POST['setting']['fieldtype'])) {
				$field_type = $_POST['setting']['fieldtype'];
			}
			require 'fields'.DIRECTORY_SEPARATOR.'add.sql.php';
			//附加属性值
			$_POST['info']['setting'] = stripcslashes(array2string($_POST['setting']));
			$_POST['info']['unsetgroupids'] = isset($_POST['unsetgroupids']) ? implode(',',$_POST['unsetgroupids']) : '';
			$_POST['info']['unsetroleids'] = isset($_POST['unsetroleids']) ? implode(',',$_POST['unsetroleids']) : '';
			$this->db->data($_POST['info'])->add();
			$this->success('添加字段成功！',U('index',array('modelid'=>$modelid)));
		} else {
			//require(MODULE_PATH.'Classes/form.class.php');
			$show_header = $show_validator = $show_dialog = '';
			require 'fields/fields.inc.php';
			
			$modelid = $_GET['modelid'];
			$f_datas = $this->db->select(array('modelid'=>$modelid),'field,name',100,'listorder ASC');
			$m_r = $this->model_db->where(array('modelid'=>$modelid))->find();
			foreach($f_datas as $_k=>$_v) {
				$exists_field[] = $_v['field'];
			}

			$all_field = array();
			foreach($fields as $_k=>$_v) {
				if(in_array($_k,$not_allow_fields) || in_array($_k,$exists_field) && in_array($_k,$unique_fields)) continue;
				$all_field[$_k] = $_v;
			}

			$modelid = $_GET['modelid'];
			header("Cache-control: private");
			include $this->admin_tpl();
		}
	}
	public function edit() {
		if(isset($_POST['dosubmit'])) {
			$modelid = $_POST['info']['modelid'] = intval($_POST['info']['modelid']);
			$model_table = M('content_model')->where(array('modelid'=>$modelid))->getField('tablename');
			$tablename = $_POST['issystem'] ? C('DB_PREFIX').'content_'.$model_table : C('DB_PREFIX').'content_'.$model_table.'_data';

			$field = $_POST['info']['field'];
			$minlength = $_POST['info']['minlength'] ? $_POST['info']['minlength'] : 0;
			$maxlength = $_POST['info']['maxlength'] ? $_POST['info']['maxlength'] : 0;
			$field_type = $_POST['info']['formtype'];
			
			require __DIR__.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.$field_type.DIRECTORY_SEPARATOR.'config.inc.php';
			if(isset($_POST['setting']['fieldtype'])) {
				$field_type = $_POST['setting']['fieldtype'];
			}
			$oldfield = $_POST['oldfield'];
			require __DIR__.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'edit.sql.php';
			//附加属性值
			$_POST['info']['setting'] = stripcslashes(array2string($_POST['setting']));
			$fieldid = intval($_POST['fieldid']);
			$this->db->where(array('fieldid'=>$fieldid))->data($_POST['info'])->save();
			$this->success('修改字段成功！',U('index',array('modelid'=>$modelid)));
		} else {
			require __DIR__.DIRECTORY_SEPARATOR.fields.DIRECTORY_SEPARATOR.'fields.inc.php'; 
			$modelid = intval($_GET['modelid']);
			$fieldid = intval($_GET['fieldid']);
			
			$m_r = $this->model_db->where(array('modelid'=>$modelid))->find();
			$r = $this->db->where(array('fieldid'=>$fieldid))->find();
			extract($r);
			require __DIR__.DIRECTORY_SEPARATOR.fields.DIRECTORY_SEPARATOR.$formtype.DIRECTORY_SEPARATOR.'config.inc.php';
			$setting = string2array(stripslashes($setting));
			ob_start();
			include __DIR__.DIRECTORY_SEPARATOR.fields.DIRECTORY_SEPARATOR.$formtype.DIRECTORY_SEPARATOR.'field_edit_form.inc.php';
			$form_data = ob_get_contents();
			ob_end_clean();
			include $this->admin_tpl();
		}
	}
	public function disabled() {
		$fieldid = intval($_GET['fieldid']);
		$disabled = $_GET['disabled'] ? 0 : 1;
		$this->db->where(array('fieldid'=>$fieldid))->setField('disabled',$disabled);
		$modelid = intval($_GET['modelid']);
		$this->success('修改字段状态成功！',U('index',array('modelid'=>$modelid)));
	}
	public function delete() {
		$fieldid = intval($_GET['fieldid']);
		$r = $this->db->where(array('fieldid'=>$_GET['fieldid']))->find();
		//必须放在删除字段前、在删除字段部分，重置了 tablename
		$this->db->where(array('fieldid'=>$_GET['fieldid']))->delete();
		$modelid = intval($_GET['modelid']);
		$model_table = M('content_model')->where('modelid='.$modelid)->getField('tablename');
		$tablename = $r['issystem'] ? 'content_'.$model_table : 'content_'.$model_table.'_data';
		$this->db->drop_field($tablename,$r['field']);
		$this->success('删除字段成功！',U('index',array('modelid'=>$modelid)));
	}
	/**
	 * 排序
	 */
	public function listorder() {
		$modelid = intval($_GET['modelid']);
		if(IS_POST) {
			foreach($_POST['listorders'] as $id => $listorder) {
				$this->db->where(array('fieldid'=>$id))->setField('listorder',$listorder);
			}
			$this->success('更新字段排序成功！',U('index',array('modelid'=>$modelid)));
		} else {
			$this->error('排序失败！',U('index',array('modelid'=>$modelid)));
		}
	}
	/**
	 * 检查字段是否存在
	 */
	public function public_checkfield() {
		$field = strtolower($_GET['field']);
		$oldfield = strtolower($_GET['oldfield']);
		if($field==$oldfield) exit('1');
		$modelid = intval($_GET['modelid']);
		$tablename = $this->model_db->where(array('modelid'=>$modelid))->getField('tablename');
		$issystem = intval($_GET['issystem']);
		if($issystem) {
			$table_name = C('DB_PREFIX').'content_'.$tablename;
		} else {
			$table_name = C('DB_PREFIX').'content_'.$tablename.'_data';
		}
		$fields = $this->db->get_fields($table_name);
		
		if(in_array($field,$fields)) {
			exit('0');
		} else {
			exit('1');
		}
	}
	/**
	 * 字段属性设置
	 */
	public function public_field_setting() {
		$fieldtype = $_GET['fieldtype'];
		require 'fields'.DIRECTORY_SEPARATOR.$fieldtype.DIRECTORY_SEPARATOR.'config.inc.php';
		ob_start();
		include 'fields'.DIRECTORY_SEPARATOR.$fieldtype.DIRECTORY_SEPARATOR.'field_add_form.inc.php';
		$data_setting = ob_get_contents();
		//$data_setting = iconv('gbk','utf-8',$data_setting);
		ob_end_clean();
		$settings = array('field_basic_table'=>$field_basic_table,'field_minlength'=>$field_minlength,'field_maxlength'=>$field_maxlength,'field_allow_search'=>$field_allow_search,'field_allow_fulltext'=>$field_allow_fulltext,'field_allow_isunique'=>$field_allow_isunique,'setting'=>$data_setting);
		echo json_encode($settings);
		return true;
	}
	/**
	 * 预览模型
	 */
	public function public_priview() {
		//require(MODULE_PATH.'Classes/form.class.php');
		
		$modelid = intval($_GET['modelid']);
		require (MODULE_PATH.'Classes/content_form.class.php');
		$content_form = new \content_form($modelid);
		$r = $this->model_db->where(array('modelid'=>$modelid))->find();
		$forminfos = $content_form->get();
		include $this->admin_tpl();
	}
}
?>