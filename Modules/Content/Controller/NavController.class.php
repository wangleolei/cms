<?php
namespace Content\Controller;
class NavController extends \Admin\Classes\AdminController{
	function __construct(){
		parent::__construct();
		}
	function index()
	{
		$condition=array();
		//搜索导航链接名称
		$name=trim($_GET['name']);
		if($name)
		{
			$condition['name']=array('like',"%{$name}%");
		}			
		$m_goods_brand = M('content_nav');
		$count      = $m_goods_brand->where($condition)->count();
		$Page       = new \Think\Page($count,15);
		$show       = $Page->show();
		$list = $m_goods_brand->where($condition)->order('listorder DESC')->limit($Page->firstRow.','.$Page->listRows)->select();	
		include($this->admin_tpl('Nav/lists'));
	}
	function add()
	{
		if(IS_POST)
		{
			$d_nav=D('Nav');
			$data=array();
			$data['name']=$_POST['info']['name'];
			$data['category_id']=$_POST['info']['category_id'];
			$data['url']=remove_xss($_POST['info']['url']);
			$id=$d_nav->data($data)->filter('new_html_special_chars')->add();
			if($id)
			{
				$d_nav->clearCache();
				$this->success('添加导航链接成功！');
			}	
			else
			{
				$this->error('添加导航链接失败！');
			}			
		}
		else
		{
			include($this->admin_tpl('Nav/add'));
		}
	}
	function edit()
	{	
		if(IS_POST)
		{
			$d_nav=D('Nav');
			$data=array();
			$data['name']=$_POST['info']['name'];
			$data['url']=remove_xss($_POST['info']['url']);
			$result=$d_nav->where(array('id'=>$_GET['id']))->data($data)->filter('new_html_special_chars')->save();
			if($result!==false)
			{
				$d_nav->clearCache();
				$this->success('修改导航链接成功！');
			}	
			else
			{
				$this->error('修改导航链接失败！',U('index'));
			}			
		}
		else
		{
			$info=M('content_nav')->where(array('id'=>$_GET['id']))->find();
			include($this->admin_tpl('Nav/edit'));
		}
	}
	function delete()
	{
		$d_nav=D('Nav');
		$result=$d_nav->where(array('id'=>$_GET['id']))->delete();
		if($result)
		{
			$d_nav->clearCache();
			$this->success('删除导航链接成功！');
		}
		else
		{
			$this->error('删除导航链接失败！');
		}
	}
	/*
	* 设置推荐导航链接
	*/
	function status()
	{
		$val=intval($_POST['val']);
		$id=intval($_GET['id']);
		$status=($val!=1)?1:0;
		$d_nav=D('Nav');
		if($d_nav->where(array('id'=>$id))->setField('status',$status))
		{
			$d_nav->clearCache();
			echo 'success';
		}
	}	
	//排序
	function listorder()
	{
		$d_nav=D('Nav');
		foreach($_POST['listorders'] as $k=>$v)
		{
			$d_nav->where(array('id'=>$k))->setField('listorder',$v);
		}
		$d_nav->clearCache();
		$this->success('更新排序成功！');      
	}
	//		
}