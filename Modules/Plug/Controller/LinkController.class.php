<?php
namespace Plug\Controller;
class LinkController extends \Admin\Classes\AdminController{
	function __construct(){
		parent::__construct();
		}
	function index()
	{
		$condition=array();
		//搜索友情链接名称
		$name=trim($_GET['name']);
		if($name)
		{
			$condition['name']=array('like',"%{$name}%");
		}			
		$m_goods_brand = M('plug_link');
		$count      = $m_goods_brand->where($condition)->count();
		$Page       = new \Think\Page($count,15);
		$show       = $Page->show();
		$list = $m_goods_brand->where($condition)->order('listorder DESC')->limit($Page->firstRow.','.$Page->listRows)->select();	
		include($this->admin_tpl('Link/lists'));
	}
	function add()
	{
		if(IS_POST)
		{
			$data=array();
			$data['name']=$_POST['info']['name'];
			$data['url']=$_POST['info']['url'];
			if($_FILES['image']['tmp_name'])
			{
				$upload = new \Think\Upload(array('driver'=>C('FILE_UPLOAD_TYPE')));
				$upload->maxSize   =     C('UPLOAD_FILE_MAX_SIZE')*1024;// 设置附件上传大小
				$upload->exts      =     C('UPLOAD_ALLOW_EXT');// 设置附件上传类型
				$upload->rootPath  =     '.'.$this->rootpath; // 设置附件上传根目录
				$upload->autoSub = false;
				$upload->savePath  =     '/files/'.date('Y/md').'/'; // 设置附件上传（子）目录		
				$info=$upload->uploadOne($_FILES['image']);
				$data['image']=C('upload_host').$this->rootpath.'/files/'.date('Y/md').'/'.$info['savename'];
				if(!$info)
				{
					$this->error('上传友情链接图标失败！');exit;
				}				
			}
			$id=M('plug_link')->data($data)->filter('new_html_special_chars')->add();
			if($id)
			{
				$this->success('添加友情链接成功！');
			}	
			else
			{
				$this->error('添加友情链接失败！');
			}			
		}
		else
		{
			include($this->admin_tpl('Link/add'));
		}
	}
	function edit()
	{
		if(IS_POST)
		{
			$data=array();
			$data['name']=$_POST['info']['name'];
			$data['url']=$_POST['info']['url'];
			//判断是否有图片上传	
			if($_FILES['image']['tmp_name'])
			{
				$upload = new \Think\Upload(array('driver'=>C('FILE_UPLOAD_TYPE')));
				$upload->maxSize   =     C('UPLOAD_FILE_MAX_SIZE')*1024;// 设置附件上传大小
				$upload->exts      =     C('UPLOAD_ALLOW_EXT');// 设置附件上传类型
				$upload->rootPath  =     '.'.$this->rootpath; // 设置附件上传根目录
				$upload->autoSub = false;
				$upload->savePath  =     '/files/'.date('Y/md').'/'; // 设置附件上传（子）目录		
				$info=$upload->uploadOne($_FILES['image']);
				$data['image']=C('upload_host').$this->rootpath.'/files/'.date('Y/md').'/'.$info['savename'];
				if(!$info)
				{
					$this->error('上传友情链接图标失败！');exit;
				}	
			}
			$result=M('plug_link')->where(array('id'=>$_GET['id']))->data($data)->filter('new_html_special_chars')->save();
			if($result!==false)
			{
				$this->success('修改友情链接成功！');
			}	
			else
			{
				$this->error('修改友情链接失败！');
			}			
		}
		else
		{
			$info=M('plug_link')->where(array('id'=>$_GET['id']))->find();
			include($this->admin_tpl('Link/edit'));
		}
	}
	function delete()
	{
		$result=M('plug_link')->where(array('id'=>$_GET['id']))->delete();
		if($result)
		{
			$this->success('删除友情链接成功！');
		}
		else
		{
			$this->error('删除友情链接失败！');
		}
	}
	/*
	* 是否显示友情链接
	*/
	function recommend()
	{
		$val=intval($_POST['val']);
		$id=intval($_GET['id']);
		$display=($val!=1)?1:0;
		if(M('plug_link')->where(array('id'=>$id))->setField('display',$display))
		{
			echo 'success';
		}
	}	
	//排序
	function listorder()
	{
		$m_brand=M('plug_link');
		foreach($_POST['listorders'] as $k=>$v)
		{
			$m_brand->where(array('id'=>$k))->setField('listorder',$v);
		}
		$this->success('更新排序成功！');      
	}		
}