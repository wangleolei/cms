<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃系统配置』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class ConfigController extends \Admin\Classes\AdminController{
	function configList()
	{
		$d=$GLOBALS['setting_model'];
		$config=$d->getSetting(1);
		include($this->admin_tpl('Config/configList'));
	}
	function edit()
	{
		$d=$GLOBALS['setting_model'];
		if(IS_POST)
		{
			if($_FILES['watermark_img']['tmp_name'])
			{
				$upload = new \Think\Upload(array('driver'=>C('FILE_UPLOAD_TYPE')));
				$upload->maxSize   =     C('maxSize')*1024 ;// 设置附件上传大小
				$upload->exts      =     C('UPLOAD_ALLOW_EXT');// 设置附件上传类型
				$upload->rootPath  =     C('UPLOAD_PATH'); // 设置附件上传根目录
				$upload->autoSub = false;
				$upload->savePath  =    '/'; // 设置附件上传（子）目录
				$upload->saveName  = 'watermark_img'; 		
				$info=$upload->uploadOne($_FILES['watermark_img']);
				$_POST['system']['WATERMARK_IMG']=C('upload_host').'/Uploads/watermark_img.jpg';	
			}			
			$system=array();
			$val=$d->setSetting(1,$_POST['system']);
			if($val!==false)
			{	
				$this->success('修改配置参数成功！');
			}
			else
			{
				$this->error('修改配置参数失败！');	
			}
		}
		else
		{
			$this->error('你没有提交数据！');
		}		
	}
	function test_mail()
	{
		$mailto=$_POST['mailto'];
		$val=send_mail($mailto, C('system_name').'测试邮件', $subject = C('system_name').'测试邮件', $body = C('system_name').'测试邮件');
		if($val==1)
		{
			echo 'success';
		}
		else
		{
			echo $val;
		}
	}
}
?>