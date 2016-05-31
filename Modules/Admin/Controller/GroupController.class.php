<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃用户权限分组』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class GroupController extends \Admin\Classes\AdminController{
	private $m1,$cat_db;
	private $menu_arr,$categorys;
	private $usertype;//用户类别
	public function __construct() {
		parent::__construct();
		$this->m1=M("auth_group");
		$this->usertype=C('usertype');
 	}
	public function groupList()
	{
		$count =$this->m1->count();
		$Page  = new \Think\Page($count,20);
		$show  = $Page->show();
		$condition=array();
		if($_GET['usertype'])
		{
			$condition['usertype']=$_GET['usertype'];
		}
		$arr=$this->m1->where($condition)->limit($Page->firstRow.','.$Page->listRows)->order('listorder desc')->select();
		include $this->admin_tpl('Group/groupList');
	}
	public function groupEdit()
	{
		$id=intval($_GET['id']);
		$m=M("auth_group");
		if(!IS_POST)
		{
			$info=$m->where(array('id'=>$id))->find();
			include($this->admin_tpl('Group/groupEdit'));
		}
		else
		{
			$bool=$m->where(array('id'=>$id))->data($_POST['info'])->save();
			if($bool!==false)
			{
				$this->success('修改用户组成功！',U('groupList'));
			}
			else
			{
				$this->error('修改用户组失败！');
			}
		}
	}
	public function groupAdd()
	{
		if(IS_POST)
		{
			$this->m1->data($_POST['info'])->filter('new_html_special_chars')->field('title,status,usertype')->add();
			$this->success('添加权限分租成功');
		}
		else
		{
			include($this->admin_tpl('Group/groupAdd'));
		}
	}
	public function groupDelete()
	{
		$id=intval($_GET['id']);
		if($id==1)
		{
			$this->error("超级管理员的权限组不能被删除！");
			exit;
		}
		if($this->m1->where(array('id'=>$id))->delete())
		{
			$this->success('删除权限组成功！');
		}
		else
		{
			$this->error('删除权限组失败！');
		}
	}
	public function groupPrev()
	{
		if(!IS_POST)
		{
			$menu_db=M('admin_menu');
			$menu_arr=$menu_db->order('listorder DESC,id DESC')->field('id,name,parentid,authid')->select();
			$this->menu_arr=$menu_arr;
			$m_auth_group=M('auth_group');
			
			//获取当前用户组的当前权限
			$rules_str=$m_auth_group->where(array('id'=>$_GET['id']))->getField('rules');
			$rules=explode(',',$rules_str);
			include($this->admin_tpl('Group/groupPrev'));
		}
		else
		{
			$data=array(
			'rules'=>implode(',',$_POST['ids']),
			);
			$m_auth_group=M('auth_group');
			$bool=$m_auth_group->where(array('id'=>$_POST['groupid']))->data($data)->save();
			if($bool!==false)
			{
				$this->success('修改用户权限成功！',U('groupList'));
			}
			else
			{
				$this->error('修改用户权限失败！');
			}
		}
	}
	/*
	* 权限约束
	*/
	public function constraint()
	{
		$id=intval($_GET['id']);
		if(empty($id) || !$id)exit;
		if(IS_POST)
		{
			$str=implode(',',$_POST['ids']);
			$val=$this->m1->where(array('id'=>$id))->filter('new_html_special_chars')->data(array('constraint'=>$str))->save();
			if($val!==false)
			{
				$this->success('修改用户组权限约束成功！');
			}
			else
			{
				$this->error('修改用户组权限约束失败！');
			}
		}
		else
		{
		$m1=M("admin_constraint");
		$lists=$m1->where(array('status'=>1))->select();	
		$constraints=$this->m1->where(array('id'=>$id))->getField('constraint');
		$constraints=explode(',',$constraints);
		include($this->admin_tpl('Group/constraint'));
		}
	}
	private function gettree($parentid,$n=0,$rules)
	{
		$str='<ul>';
		foreach($this->menu_arr as $k=>$v)
		{		
			if($v['parentid']==$parentid)
			{
				if($n<2)
				{
					$str.='<li data-options="state:\'closed\'">';
					$str.='<span><label><input '; 
					if(in_array($v['authid'],$rules))$str.='checked';
					$str.=" data-parentid='{$v['parentid']}' data-id='{$v['id']}'";
					$str.=' type="checkbox" name="ids[]" value="'.$v['authid'].'"/> '.$v['name'].'</label></span>';
					$str.=$this->gettree($v['id'],$n+1,$rules);
					$str.='</li>';
				}
				else
				{
					$str.='<li>';
					$str.='<label><input ';
					$str.=" data-parentid='{$v['parentid']}' data-id='{$v['id']}'";
					if(in_array($v['authid'],$rules))$str.='checked';
					$str.=' type="checkbox" name="ids[]" value="'.$v['authid'].'"/> '.$v['name'].'</span>';
					$str.='</li>';
				}
			}
		}
		$str.='</ul>';
		return $str;
	}	
	//内容栏目的权限设置
	function categoryPrev()
	{
		if(!IS_POST)
		{
			$this->cat_db=M('content_category');
			$menu_arr=$this->cat_db->order('listorder DESC,id DESC')->field('id,name,parentid,path')->select();
			$this->categorys=$menu_arr;
			//获取当前用户组的当前权限
			$m_auth_group=M('content_category_priv');
			$rules=$m_auth_group->where(array('grpid'=>$_GET['id']))->find();
			$rules['view']=','.$rules['view'].',';
			$rules['add']=','.$rules['add'].',';
			$rules['edit']=','.$rules['edit'].',';
			$rules['delete']=','.$rules['delete'].',';
			$rules['examine']=','.$rules['examine'].',';
			include($this->admin_tpl('Group/categoryPrev'));
		}
		else
		{
			$m_category_priv=M('content_category_priv');
			$m_category_priv->where(array('grpid'=>$_POST['groupid']))->delete();
			$data=array();
			$data['grpid']=$_POST['groupid'];
			$data['view']=implode(',',$_POST['view']);
			$data['add']=implode(',',$_POST['add']);
			$data['edit']=implode(',',$_POST['edit']);
			$data['delete']=implode(',',$_POST['delete']);
			$data['examine']=implode(',',$_POST['examine']);
			$bool=$m_category_priv->data($data)->add();
			if($bool!==false)
			{
				$this->success('修改栏目权限成功！',U('groupList'));
			}
			else
			{
				$this->error('修改栏目权限失败！');
			}
		}		
	}
	private function gettree2($parentid,$rules)
	{
		$str='<ul>';
		foreach($this->categorys as $k=>$v)
		{		
			if($v['parentid']==$parentid)
			{
				$str.='<li >';
				$view=(strpos($rules['view'],','.$v['id'].',')!==false)?'checked':'';
				$add=(strpos($rules['add'],','.$v['id'].',')!==false)?'checked':'';
				$edit=(strpos($rules['edit'],','.$v['id'].',')!==false)?'checked':'';
				$delete=(strpos($rules['delete'],','.$v['id'].',')!==false)?'checked':'';
				$examine=(strpos($rules['examine'],','.$v['id'].',')!==false)?'checked':'';
				$str.='<span> '.$v['name'].'<div class="tree-prev">
								<label><input data-parentid="'.$v['parentid'].'" data-id="'.$v['id'].'" type="checkbox" name="view[]" value="'.$v['id'].'" '.$view.'/> 查看</label> 
								<label><input data-parentid="'.$v['parentid'].'" data-id="'.$v['id'].'" type="checkbox" name="add[]" value="'.$v['id'].'" '.$add.'/> 发布</label> 
								<label><input data-parentid="'.$v['parentid'].'" data-id="'.$v['id'].'" type="checkbox" name="edit[]" value="'.$v['id'].'" '.$edit.'/> 修改</label> 
								<label><input data-parentid="'.$v['parentid'].'" data-id="'.$v['id'].'" type="checkbox" name="delete[]" value="'.$v['id'].'" '.$delete.'/> 删除</label></div>
								</span>';
				$is_find=$this->cat_db->where(array('path'=>$v['path'].$v['id'].'-'))->field('id')->find();
				if($is_find)
				{
					$str.=$this->gettree2($v['id'],$rules);
				}
				$str.='</li>';
			}
		}
		$str.='</ul>';
		return $str;
	}	
	//排序
	function listorder()
	{
		foreach($_POST['listorders'] as $k=>$v)
		{
			$this->m1->where(array('id'=>$k))->setField('listorder',$v);
		}
		$this->success('更新排序成功！');      
	}		
}