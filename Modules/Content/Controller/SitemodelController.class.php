<?php
namespace Content\Controller;
class SitemodelController extends \Admin\Classes\AdminController{
	private $db,$siteid=1;
	function __construct() {
		parent::__construct();
		$this->db = D('Sitemodel');
	}
	
	public function index() {
		$conditon=array();
		$datas=$this->db->where($conditon)->select();
		include($this->admin_tpl('Sitemodel/index'));
	}
	public function add() {
		if(IS_POST) {
			$_POST['info']['category_template'] = $_POST['setting']['category_template'];
			$modelid = $this->db->data($_POST['info'])->add();
			$model_sql = file_get_contents(__DIR__.'/fields/model.sql');
			$tablepre = C('DB_PREFIX').'content_';
			
			$tablename = $_POST['info']['tablename'];
			$model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
			$model_sql = str_replace('$table_data',$tablepre.$tablename.'_data', $model_sql);
			$model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
			$model_sql = str_replace('$modelid',$modelid,$model_sql);
			$this->db->execute($model_sql);
			$this->success('添加模型成功！');
		} else {
			include $this->admin_tpl();
		}
	}
	public function edit() {
		if(isset($_POST['dosubmit'])) {			
			$modelid = intval($_POST['modelid']);
			$this->db->where(array('modelid'=>$modelid))->data($_POST['info'])->save();
			$this->success('修改模型成功！');
		} else {
			$modelid = intval($_GET['modelid']);
			$r = $this->db->where(array('modelid'=>$modelid))->find();
			extract($r);
			include $this->admin_tpl();
		}
	}
	public function delete() {
		$this->sitemodel_field_db = D('sitemodelfield');
		
		$modelid = intval($_GET['modelid']);
		$tablename= $this->db->where(array('modelid'=>$modelid))->getField('tablename');
		$model_table='content_'.$tablename;
		$this->sitemodel_field_db->delete(array('modelid'=>$modelid));
		$this->db->drop_table($model_table);
		$this->db->drop_table($model_table.'_data');
		$this->db->where(array('modelid'=>$modelid))->delete();
		M('content_model_field')->where(array('modelid'=>$modelid))->delete();
		exit('1');
	}
	public function disabled() {
		$modelid = intval($_GET['modelid']);
		$r = $this->db->where(array('modelid'=>$modelid))->find();
		
		$status = $r['disabled'] == '1' ? '0' : '1';
		$this->db->where(array('modelid'=>$modelid))->data(array('disabled'=>$status))->save();
		$this->success('修改模型状态成功！');
	}
	/**
	 * 导出模型
	 */
	function export() {
		$modelid =  $_GET['modelid'];
		$tablename=M('content_model')->where(array('modelid'=>$modelid))->getField('name');
		$this->sitemodel_field_db = D('Sitemodelfield');
		$modelinfo = $this->sitemodel_field_db->where(array('modelid'=>$modelid))->select();
		foreach($modelinfo as $k=>$v) {
			$modelinfoarr[$k] = $v;
			$modelinfoarr[$k]['setting'] = string2array($v['setting']);
		}
		$res = var_export($modelinfoarr, TRUE);
		header('Content-Disposition: attachment; filename="'.$tablename.'.model"');
		echo $res;exit;
	}
	/**
	 * 导入模型
	 */
	function import(){
		if(IS_POST) {
			$info = array();
			$info['name'] = $_POST['info']['modelname'];
			$info['tablename']=$_POST['info']['tablename'];
			//主表表名
			$basic_table = 'content_'.$_POST['info']['tablename'];
			//从表表名
			$table_data = $basic_table.'_data';
			$info['description'] = $_POST['info']['description'];
			$info['type'] = 0;			
			if(!empty($_FILES['model_import']['tmp_name'])) {
				$model_import = @file_get_contents($_FILES['model_import']['tmp_name']);
				if(!empty($model_import)) {
					$model_import_data = string2array($model_import);				
				}
			}
			$is_exists = $this->db->table_exists($basic_table);
			if($is_exists)
			{
				$this->error('数据表已经存在！');
				exit;
			}
			$modelid = $this->db->data($info)->add();
			if($modelid){
				$tablepre =  C('DB_PREFIX');
				//建立数据表
				$model_sql = file_get_contents(__DIR__.'/fields/model.sql');
				$model_sql = str_replace('$basic_table', $tablepre.$basic_table, $model_sql);
				$model_sql = str_replace('$table_data',$tablepre.$table_data, $model_sql);
				$model_sql = str_replace('$table_model_field',$tablepre.'content_model_field', $model_sql);
				$model_sql = str_replace('$modelid',$modelid,$model_sql);
				$this->db->execute($model_sql);
				
				if(!empty($model_import_data)) {
					$this->sitemodel_field_db = D('Sitemodelfield');
					$system_field = array('title','style','catid','url','listorder','status','userid','username','inputtime','updatetime','pages','readpoint','template','groupids_view','posids','content','keywords','description','thumb','typeid','relation','islink','allow_comment');
					foreach($model_import_data as $v) {
						$field = $v['field'];
						if(in_array($field,$system_field)) {
							$v['siteid'] = $this->siteid;
							unset($v['fieldid'],$v['modelid'],$v['field']);
							$v = new_addslashes($v);
							$v['setting'] = array2string($v['setting']);
							
							$this->sitemodel_field_db->where(array('modelid'=>$modelid,'field'=>$field))->data($v)->save();
						} else {
							$tablename = $v['issystem'] ? $tablepre.$basic_table : $tablepre.$table_data;
							//重组模型表字段属性
							
							$minlength = $v['minlength'] ? $v['minlength'] : 0;
							$maxlength = $v['maxlength'] ? $v['maxlength'] : 0;
							$field_type = $v['formtype'];
							require 'fields'.DIRECTORY_SEPARATOR.$field_type.DIRECTORY_SEPARATOR.'config.inc.php';	
							if(isset($v['setting']['fieldtype'])) {
								$field_type = $v['setting']['fieldtype'];
							}
							require 'fields'.DIRECTORY_SEPARATOR.'add.sql.php';
							$v['tips'] = addslashes($v['tips']);
							$v['formattribute'] = addslashes($v['formattribute']);
							
							$v['setting'] = array2string($v['setting']);
							$v['modelid'] = $modelid;
							unset($v['fieldid']);
							$this->sitemodel_field_db->data($v)->add();
						}
					}
				}
				$this->success('导入内容模型成功！',U('index'));
			}
		} else {
			include $this->admin_tpl();
		}
	}
	/**
	 * 检查表是否存在
	 */
	public function public_check_tablename() {
		$r = $this->db->table_exists(strip_tags($_GET['tablename']));
		if(!$r) echo '1';
	}
}
?>