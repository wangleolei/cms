<?php
namespace Content\Model;
class SitemodelModel extends \Admin\Classes\CommonModel {
	protected $tableName='content_model';
	/**
	 * 删除表
	 * 
	 */
	public function drop_table($tablename) {
		$tablename = $this->tablePrefix.$tablename;
		$tablearr = $this->list_tables();
		if(in_array($tablename, $tablearr)) {
			return $this->execute("DROP TABLE $tablename");
		} else {
			return false;
		}
	}
	/*
	* 获取数据模型列表
	*/
	public function getModels()
	{
		$condition=array();
		$condition['disabled']=0;
		$lists=$this->where($condition)->select();
		return $lists;
	}
}
?>