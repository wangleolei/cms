<?php
/*
* 插件系统
*/
namespace Plug\Controller;
class IndexController extends \Admin\Classes\AdminController{
	function index()
	{
		$this->redirect('Admin/Index/firstPage');
	}	
}