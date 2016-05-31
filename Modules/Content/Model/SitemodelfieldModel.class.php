<?php
namespace Content\Model;
class SitemodelfieldModel extends \Admin\Classes\CommonModel {
	protected $tableName='content_model_field';
	/**
	 * 删除字段
	 * 
	 */
	public function drop_field($tablename,$field) {
		$table_name = $this->tablePrefix.$tablename;
		$fields = $this->get_fields($table_name);
		if(in_array($field,$fields)) {
			return $this->execute("ALTER TABLE `$table_name` DROP `$field`;");
		} else {
			return false;
		}
	}
}
?>