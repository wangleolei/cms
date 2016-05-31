<?php
namespace Content\Controller;
class IndexController extends \Admin\Classes\AdminController{
	function index()
	{
		$this->redirect('Admin/Index/firstPage');
	}	
}