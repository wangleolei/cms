<?php
/*
* 系统
*/
namespace Admin\Controller;
class SystemController extends \Admin\Classes\AdminController{
	function index(){
		$m=M();
		$mysql_vesrsion=$m->query('select VERSION() as version');
		$mysql_vesrsion=$mysql_vesrsion[0]['version'];
		/*服务器负载统计*/
		$m_ab=M('admin_behavior');
		$cycle=$_GET['cycle'];
		if($cycle>=1)
		{
			$data=$m_ab->cache(true,3600)->where(array('time'=>array('gt',strtotime("-{$cycle} month"))))->field('AVG(timelong) as avg,SUM(timelong) as sum,date2,date1')->group('date1')->order('date1 asc')->select();			
		}
		else
		{
			$data=$m_ab->cache(true,60)->where(array('time'=>array('gt',strtotime(date('Y-m-d')))))->field('AVG(timelong) as avg,SUM(timelong) as sum,date2,date1')->group('date2')->order('date2 asc')->select();
		}
		$date=array();
		$avg=array();
		$sum=array();
		foreach($data as $v)
		{
			if($cycle>=1)
			{
				$date[]=substr($v['date1'],5);
			}			
			else
			{
				$date[]=substr($v['date2'],10).'点';
			}
			$avg[]=round($v['avg'],3);
			$sum[]=round($v['sum'],3);
		}
		include($this->admin_tpl('System/index'));
		}
}