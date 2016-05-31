<?php
/*
* 公共的方法合集
* 无权限控制
*/
namespace Plug\Controller;
class PublicController extends \Admin\Classes\AdminController{
	/*
	*图片上传iframe窗口
	*/
 	public function swfupload()
	{	
		$m=M('PlugUploads');	
		$condition=array();
		/*
		if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
		{
			$condition=array('owner'=>session('userinfo.username'));
		}
		*/
		//图片标题搜索
		$imgk=trim($_POST['imgk']);
		if(!empty($imgk))
		{
			$condition['title']=array('like',"%{$imgk}%");
		}
		//浏览图库中的图片
		$count = $m->where($condition)->count();	
		$Page       = new \Think\Page($count,8);
		$Page->rollPage=5;
		$show       = $Page->show();
		$imglist = $m->where($condition)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->field('id,title,url')->select();
		
		$condition=array();
		$condition['username']=session('userinfo.username');
		$encrypt=M('admin')->cache(true,60)->where($condition)->getField('encrypt');
		$swfuploadsessid=uniqid();
		$swf_auth_key=password($swfuploadsessid,$encrypt);
		$thumb_specs=array_unique(array_filter(explode(',',C('THUMB_SPEC'))));
		include($this->admin_tpl('Public/swfupload'));
	}
	/*
	* 修改多图上传的图片文件名
	*/
	public function swfuploadRetitle()
	{
		$m   =M('PlugUploads');
		$id  =intval($_POST['id']);
		$condition=array();
		$condition['id']=$id;
		//$condition['owner']=$owner;
		$bool=$m->where($condition)->data(array('title'=>$_POST['title']))->filter('new_html_special_chars')->save();
	}
	/*
	* cropzoom 图片剪切
	*/
	public function cropzoom()
	{
		if (isset($_GET['picurl']) && !empty($_GET['picurl'])) {
			$picurl = $_GET['picurl'];
			$picinfo = getimagesize($picurl);
			include $this->admin_tpl('Public/cropzoom');
		}
		else
		{
			echo '没有图片地址!';
		}
	}
	/*
	* ueditor 文件上传
	*/
	public function ueditorUpload(){
    	$data = new \Org\Util\Ueditor();
		echo $data->output();
	}				
}