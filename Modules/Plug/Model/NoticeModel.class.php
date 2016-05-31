<?php
namespace Plug\Model;
class NoticeModel extends \Admin\Classes\CommonModel {
	  protected $tableName='plug_notice';
	  //获取条处于发布状态的公告
	  function getXNotice($X=10)
	  {
		$condition=array();
		$condition['stauts']=1;
		$condition['begin_time']=array('lt',time());
		$condition['end_time']=array('gt',time());		  
		$values=$this->where($condition)->field('id,title,addtime')->select();
		return $values;
	  }
	  //获取公告详情
	  function getDetail($id)
	  {
		$condition=array();
		$condition['stauts']=1;
		$condition['begin_time']=array('lt',time());
		$condition['end_time']=array('gt',time());
		$condition['id']=$id;
		$info=$this->where($condition)->find();
		return $info?$info:false;
	  }
}