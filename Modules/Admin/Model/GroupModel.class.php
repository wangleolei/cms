<?php
namespace Admin\Model;
class GroupModel extends \Admin\Classes\CommonModel {
	protected $tableName='auth_group';
	//获取一个用户类别的所有权限组
	function get_group($userType)
	{
		$where=array();
		if(!empty($userType))$where['usertype']=$userType;
		$where['status']=1;
		return $this->where($where)->field('title,id')->order('id DESC')->select();
	}
}