<?php
/*
* 行为相关配置
*/
return array(
	'app_init'     =>array('Admin\Behavior\SystemConfigBehavior','Content\Behavior\RouteBehavior'),
	'app_begin'    =>array('Admin\Behavior\RecordBeginBehavior'),
	//'view_filter'  =>array('Behavior\TokenBuildBehaviors'),
	'app_end'      =>array('Admin\Behavior\RecordEndBehavior'),
);
?>