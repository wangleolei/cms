<?php
namespace Plug\Model;
class LinkModel extends \Admin\Classes\CommonModel {
	protected $tableName='plug_link';
	function getLinks($isimage)
	{	
		$condition=array();
		$condition['status']=1;
		if($isimage)$condition['image']=array('neq','');
		return $this->where($condition)->order('listorder DESC')->select();
	}
}