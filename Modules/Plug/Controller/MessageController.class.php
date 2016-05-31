<?php
/*
* 留言板插件
*/
namespace Plug\Controller;
class MessageController extends \Admin\Classes\AdminController{
        private $m_mes;
		private $stauts=array(
			'0'=>'未回复',
			'9'=>'已经回复'
		);
		function __construct(){
		parent::__construct();
		$this->m_mes=D('PlugMessage');
		}
		function lists(){
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
				case 1:$condition['url']=array('like',"%{$keyword}%");break;
				case 2:$condition['name']=array('like',"%{$keyword}%");break;
			}
		}			
			$count = $this->m_mes->where($condition)->count();
			$Page  = new \Think\Page($count,15);
			$show       = $Page->show();
			$list = $this->m_mes->where($condition)->order('time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();			
			include($this->admin_tpl('Message/lists'));
		}
		function delete(){
			 $id=intval($_GET['id']);
			 $this->m_mes->where('id='.$id)->delete();
			 $this->success('删除留言成功！');
			}
		function view(){
			$id=intval($_GET['id']);
			if(IS_POST)
			{
				$data=array();
				$data['reply']=$_POST['reply'];
				if(empty($data['reply']))
				{
					$data['status']=0;
				}
				else
				{
					$data['status']=9;//已经回复的状态码为9	
				}
				$reval=$this->m_mes->where('id='.$id)->filter('new_html_special_chars')->data($data)->save();
				if($reval!==false)
				{
					$this->success('回答留言成功！');
				}
			}
			else
			{
				$info=$this->m_mes->where('id='.$id)->find();
				include($this->admin_tpl('Message/view'));				
			}
			}
		function deleteSelect(){
			$ids=$_POST['ids'];
			foreach($ids as $id)
			{
				 $this->m_mes->where('id='.$id)->delete();
			}
			 $this->success('删除选择留言成功！');
		}
}