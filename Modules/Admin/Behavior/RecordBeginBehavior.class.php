<?php
/*
* 
*/
namespace Admin\Behavior;
use Think\Behavior;
class RecordBeginBehavior extends Behavior {
     public function run(&$params){
		 if(C('admin_log') && INSTALLED)
		 {
			G('RecordAdminBehaviorBegin');
		 }
     }
}
?>