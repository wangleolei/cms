<?php
namespace Content\Model;
class NavModel extends \Admin\Classes\CommonModel {
	 protected $tableName='content_nav';
	 
	 //获取导航信息
	 function getNav($cacheTime=0)
	 {
		if($val=$this->getCache())return $val;
		$val=$this->where(array('status'=>1))->order('listorder DESC')->select();
		$this->setCache($val,$cacheTime,'PC站主导航缓存');
		return $val;
	 }
	 //删除缓存
	 function clearCache()
	 {
		 $this->delCache('getNav');
	 }
}