<?php
/*
* 
*/
namespace Admin\Behavior;
use Think\Behavior;
class RecordEndBehavior extends Behavior {
	private $ignore=array('Admin/Index/current_pos');
     public function run(&$params){
     	 $uid=session('userinfo.userid');
		 if(C('admin_log') && $uid && INSTALLED)
		 {
			if(in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$this->ignore))return;//忽略不需要记录的操作 
			G('RecordAdminBehaviorEnd');
			$long=G('RecordAdminBehaviorBegin','RecordAdminBehaviorEnd',6);
			$memory=G('RecordAdminBehaviorBegin','RecordAdminBehaviorEnd','m');
			$model=M('admin_behavior');
			$data=array(
				'uid'=>session('userinfo.userid'),
				'timelong'=>$long,
				'memory'=>str_replace(',','',$memory),
				'time'=>time(),
				'date1'=>date('Y-m-d'),
				'date2'=>date('Y-m-d H'),
				'm'=>MODULE_NAME,
				'c'=>CONTROLLER_NAME,
				'a'=>ACTION_NAME,
				'ip'=>get_client_ip(),
				'data'=>$this->_get_data()
			);
			$model->data($data)->add();
		 }		 
     }	 
	 private function _get_data()
	{
		$arr=$_GET;
		$data='';
		foreach($arr as $k=>$v)
		{
				$data.='/'.$k.'/'.$v; 
		}
		if(empty($data))
		{
			return '';
		}
		else
		{
			return substr($data,1);
		}
	}
}
?>