<?php
/*
* 加载系统配置
*/
namespace Admin\Behavior;
use Think\Behavior;
class SystemConfigBehavior extends Behavior {
     public function run(&$params){
		 if(INSTALLED)
		 {
			 $GLOBALS['setting_model']=D('Admin/Setting');
			 $system_config=$GLOBALS['setting_model']->getSetting(1);
			 C($system_config);
		 }
     }
}
?>