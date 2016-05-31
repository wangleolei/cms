<?php
/*
* 设置相关
*/
namespace Admin\Model;
class SettingModel extends \Admin\Classes\CommonModel {
	protected $tableName='admin_setting';
	/*
	* 获取设置
	*/
	function getSetting($id)
	{
		if($val=$this->getCache())return $val;
		$val=$this->where(array('id'=>$id))->find();
		$value=unserialize($val['value']);
		$this->setCache($value,0,$val['name'].'缓存');
		return $value;	
	}
	/*
	* 修改设置
	*/
	function setSetting($id,$value)
	{
		if(is_array($value))
		{
			foreach($value as $key=>$val)
			{
				$value[$key]=remove_xss($val);
			}
		}
		else
		{
			$value=remove_xss($value);
		}
		$reval=$this->where(array('id'=>$id))->setField('value',serialize($value));
		//清除缓存	
		$this->clearSettingCache($id);		
		return ($reval!==false);
	}
	/*
	* 清除缓存
	*/
	function clearSettingCache($id)
	{
		 $this->delCache('getSetting',array($id));
	}
}