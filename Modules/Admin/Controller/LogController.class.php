<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃ 操作日志』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class LogController extends \Admin\Classes\AdminController{
	function index(){
		$model=M('admin_behavior');
		$condition=array();
		
		//时间范围筛选
		if($_GET['end_time'] && $_GET['begin_time'])
		{
			$condition['time']=array(array('lt',strtotime($_GET['end_time'])+3600*24),array('gt',strtotime($_GET['begin_time'])),'and');
		}
		else
		{
			/*发布开始时间段*/
			if($_GET['begin_time'])
			{
				$condition['time']=array('gt',strtotime($_GET['begin_time']));
			}
			/*发布时间结束时间段*/
			if($_GET['end_time'])
			{
				$condition['time']=array('lt',strtotime($_GET['end_time'])+3600*24);
			}				
		}
		//搜索
		$keyword=trim($_GET['keyword']);
		if($keyword)
		{
			switch($_GET['type'])
			{
				case 1:$condition['username']=$keyword;break;
			}
		}				
		$count =$model->where($condition)->join('left join __ADMIN__ on __ADMIN__.userid=__ADMIN_BEHAVIOR__.uid')->count();
		$Page  = new \Think\Page($count,20);
		$show  = $Page->show();
		$list=$model->where($condition)->join('left join __ADMIN__ on __ADMIN__.userid=__ADMIN_BEHAVIOR__.uid')->order('time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();	
		include($this->admin_tpl('Log/index'));
	}
	/*
	* 清除3个月前的操作记录
	*/
	function clear()
	{
		$model=M('admin_login');
		$bool=$model->where(array('time'=>array('lt',strtotime("-3 month"))))->delete();
		if($bool!==false)
		{
			$this->success('清除三个月前的日志成功！');
		}
		else
		{
			$this->success('清除三个月前的日志失败！');
		}
	}
}