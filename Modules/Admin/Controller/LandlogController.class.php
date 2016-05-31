<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃ 管理员登录日志』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class LandlogController extends \Admin\Classes\AdminController{
	function lists(){
		$model=M('admin_login');
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
				case 2:$condition['ip']=$keyword;break;
				case 3:$condition['area']=array('like',"%{$keyword}%");break;
			}
		}				
		$count =$model->where($condition)->join('left join __ADMIN__ on __ADMIN__.userid=__ADMIN_LOGIN__.uid')->count();
		$Page  = new \Think\Page($count,20);
		$show  = $Page->show();
		$list=$model->where($condition)->join('left join __ADMIN__ on __ADMIN__.userid=__ADMIN_LOGIN__.uid')->order('time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();	
		include($this->admin_tpl('Landlog/lists'));
	}
	/*
	* 清除3个月前的登陆记录
	*/
	function clear()
	{
		$model=M('admin_login');
		$bool=$model->where(array('time'=>array('lt',strtotime("-3 month"))))->delete();
		if($bool!==false)
		{
			$this->success('清除三个月前的记录成功！');
		}
		else
		{
			$this->success('清除三个月前的记录失败！');
		}
	}
}