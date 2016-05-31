<?php
namespace Plug\Controller;
class ApiController extends \Admin\Classes\CommonController{
	function __construct(){
		parent::__construct();
		}
	/*
	* 在商品类别联级菜单中获取下一级商品类别的选择菜单
	*/	
	function ajax_GetNextCatSelect()
	{
		$class=$_GET['class'];
		$catid=intval($_POST['catid']);
		echo D('area')->getOneStageSelect($catid,'',$class);
	}
}