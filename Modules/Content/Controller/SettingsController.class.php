<?php
/*
* 网站设置
*/
namespace Content\Controller;
class SettingsController extends \Admin\Classes\AdminController{
	function index(){
		$d=$GLOBALS['setting_model'];
		if(IS_POST)
		{
			$index=array();
			//路由规则
			$d->setSetting(3,$_POST['route']);
			//首页SEO
			$d->setSetting(4,$_POST['index']);
			//基本信息
			$d->setSetting(5,$_POST['baseinfo']);
			
			$this->success('修改站点设置成功!');
		}
		else
		{
			$index=$d->getSetting(4);
			$baseinfo=$d->getSetting(5);
			$route=$d->getSetting(3);
			require($this->admin_tpl('Settings/index'));
		}
	}
}