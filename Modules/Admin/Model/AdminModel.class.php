<?php
namespace Admin\Model;
class AdminModel extends \Admin\Classes\CommonModel {
	//添加管理员
     function addAdmin($username,$password,$realname,$group,$usertype)
	 {
		if($this->_is_username($username) && $this->_is_password($password) && $this->_is_realname($realname))
		{
			$arr=password($password);
			$id=$this->filter('new_html_special_chars')->add(array('username'=>$username,'password'=>$arr['password'],'encrypt'=>$arr['encrypt'],'realname'=>$realname,'status'=>1));
			$num=M('auth_group')->where(array('usertype'=>$usertype,'id'=>$group))->count();
			if(!$num)
			{
				$this->error='权限不允许!';
				return false;
			}
		    $val=M('auth_group_access')->add(array('uid'=>$id,'group_id'=>$group));
			return ($id&&$val)? $id:false;
		}
		else
		{
			return false;
		}
	 }
	 function _is_username($username)
	 {
		 if(htmlspecialchars($username,ENT_QUOTES,'utf-8')!=$username)
		 {
			 $this->error='用户名不能使用特殊字符!';
			 return false;
		 }
		 elseif(strlen($username)>15 or strlen($username)<2)
		 {
			 $this->error='用户名长度非法!';
			 return false;
		 }
		 else
		 {
			return true;
		 }
		 
	 }
	 function _is_password($password)
	 {
		if(strlen($password)>20 or strlen($password)<6)
		{
			$this->error='密码长度非法!';
			return false;
		}
		return true;
	 }
	 function _is_realname($realname)
	 {
		 if(htmlspecialchars($realname,ENT_QUOTES,'utf-8')!=$realname)
		 {
			 $this->error='真实姓名不能使用特殊字符!';
			 return false;
		 }
		 elseif(strlen($realname)>15 or strlen($realname)<2)
		 {
			 $this->error='真实姓名长度非法!';
			 return false;
		 }
		 else
		 {
			return true;
		 }		 
	 }
	 /*
	 * 检测是否是指定的用户类别
	 */
	 function checkUserType($userid,$usertype)
	 {
		$group_id=M('auth_group_access')->where(array('uid'=>$userid))->getField('group_id');
		$usertype2=M('auth_group')->where(array('id'=>$group_id))->getField('usertype');
		if($usertype==$usertype2)return true;
		$this->error='用户类别检测失败！';
	 }
	 /*
	 * 修改管理员密码
	 */
	 function change_password($password,$userid,$usertype)
	 { 
		 if(!$this->_is_password($password))
		 {
			 return false;
		 }
		 $arr=password($password);
		 $val=$this->where(array('userid'=>$userid))->data(array('password'=>$arr['password'],'encrypt'=>$arr['encrypt']))->save();
		 return $val;
	 }
}