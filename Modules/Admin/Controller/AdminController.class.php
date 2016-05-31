<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃后台管理员』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class AdminController extends \Admin\Classes\AdminController{			
	public function adminList()
	{
		$m=M('admin');
		$count      = $m->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page ->setConfig('prev','上一页');
		$Page ->setConfig('next','下一页');
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$condition=array();
		if(!empty($_GET['groupid']))
		{
			$condition['group_id']=$_GET['groupid'];
		}
		$list = $m->join(' LEFT JOIN __AUTH_GROUP_ACCESS__ ON __AUTH_GROUP_ACCESS__.uid=__ADMIN__.userid ')
				  ->join(' LEFT JOIN __AUTH_GROUP__ ON __AUTH_GROUP__.id=__AUTH_GROUP_ACCESS__.group_id ')
				  ->where($condition)
				  ->order('userid ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
				  
		$usertype=C('usertype');		  
		include($this->admin_tpl('Admin/adminList'));
	}
	public function adminAdd()
	{
		if(IS_POST)
		{
			if(session('userinfo.userid')!=1 && intval($_POST['group']['groupid']==1)) exit;//只有创始人账户能够添加系统管理员组用户			
			
			$d=D('admin');
			$data=$_POST['info'];
			
			//获取加密后的密码字符串
			$pw=password($data['password']);
			$data['password']=$pw['password'];
			$data['encrypt']=$pw['encrypt'];
			
			$d->startTrans();
			
			$uid=$d->filter('new_html_special_chars')->data($data)->add();//添加用户	
			//设置用户的权限组
			$m=M('auth_group_access');
						
			$bool=$m->filter('new_html_special_chars')->data(array('uid'=>$uid,'group_id'=>intval($_POST['group']['groupid'])))->add();			
			if($uid && $bool)
			{
				$d->commit();
				$this->success('添加用户成功！',U('adminList'));
			}
			else
			{
				$d->rollback();
				$this->success('添加用户失败！');
			}

		}
		else
		{
			$m=M("auth_group");
			$condition=array('status'=>1);
			if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
			{	
				$condition['id']=array('neq',1);			
			}
			$auth_group=$m->where($condition)->field('id,title')->select();
			include($this->admin_tpl('Admin/adminAdd'));
		}
	}
	public function adminEdit()
	{
		$id=intval($_GET['id']);
		if(IS_POST)
		{
			$d=D('admin');
			$data=$_POST['info'];
			
			//获取加密后的密码字符串
			if(!empty($_POST['password']))
			{
				$pw=password($_POST['password']);
				$data['password']=$pw['password'];
				$data['encrypt']=$pw['encrypt'];
			}
			//开启事务处理
			$d->startTrans();
			//修改用户信息
			$bool1=$d->where(array('userid'=>$id))->filter('new_html_special_chars')->data($data)->save();
			//设置用户的权限组
			$m=M('auth_group_access');
			$bool2=$m->where(array('uid'=>$id,'userid'=>array('neq',1)))->data(array('group_id'=>intval($_POST['group']['groupid'])))->save();
			if(($bool1!==false) && ($bool2!==false))
			{
				$d->commit();
				$this->success('修改用户信息成功！',U('adminList'));
			}
			else
			{
				$d->rollback();
				$this->success('修改用户信息失败！');
			}		
		}
		else
		{
			/*获取用户组列表*/
			$m=M("auth_group");
			$condition=array('status'=>1);
			if(session('userinfo.userid')!=1)
			{	
				$condition['id']=array('neq',1);			
			}			
			$auth_group=$m->where($condition)->field('id,title')->select();
			/*获取当前用户信息*/
			$m2=M('admin');
			$info=$m2->join(' LEFT JOIN __AUTH_GROUP_ACCESS__ ON __AUTH_GROUP_ACCESS__.uid=__ADMIN__.userid ')->where(array('userid'=>$id))->find();		
			include($this->admin_tpl('Admin/adminEdit'));
		}
	}
	public function adminDelete()
	{
		$id=intval($_GET['id']);
		if($id==1)
		{
			$this->error('ID为1的账户为创始人账户，无法删除！');
		}
		else
		{
			$m1=M('admin');
			$m1->startTrans();
			$bool1=$m1->where(array('userid'=>$id))->delete();
			
			$m2=M('auth_group_access');
			$bool2=$m2->where(array('uid'=>$id))->delete();
			if($bool1)
			{
				$m1->commit();
				$this->success('删除用户成功！',U('adminList'));
			}
			else
			{
				 $m1->rollback();
				 $this->error('删除用户失败！',U('adminList'));	
			}
		}
	}	
	public function ajax_check_username()
	{
		$username=$_GET['username'];
		if(!$this->_is_exist_username($username))
		{
			echo 1;
		}
	}
	public function ajax_check_email()
	{
		$email=$_GET['email'];
		$userid=empty($_GET['id'])?session('userinfo.userid'):intval($_GET['id']);
		if(!$this->_is_exist_email($email,$userid))
		{
			echo 1;
		}		
	}
	//验证用户名是否已经存在
	private function _is_exist_username($username)
	{
		$m=M('admin');
		if($m->where(array('username'=>$username))->getField('username'))
		{
			return true;	
		}
		else
		{
			return false;
		}	
	}
	//验证email是否已经存在
	private function _is_exist_email($email,$userid)
	{
		$m=M('admin');
		$condition=array();
		$condition['email']=$email;
		if($userid)$condition['userid']=array('neq',$userid);
		$num=$m->where($condition)->count();
		if($num==0)
		{
			return false;
		}
		else
		{
			return true;
		}			
	}	
}