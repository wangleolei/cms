<?php
/*
* 
*/
namespace Content\Behavior;
use Think\Behavior;
class RouteBehavior extends Behavior {
     public function run(&$params){
     	if(INSTALLED!==false)
     	{
			 $route=$GLOBALS['setting_model']->getSetting(3);
			 if($route['open']==1)
			 {
				 C('URL_ROUTER_ON',true);
				 C('URL_ROUTE_RULES',array($route['pc_detail']=>array('Home/Index/detail'),$route['pc_lists']=>array('Home/Index/lists'),$route['mb_detail']=>array('Mobile/Index/detail'),$route['mb_lists']=>array('Mobile/Index/lists')));
			 }
		}
     }
}
?>