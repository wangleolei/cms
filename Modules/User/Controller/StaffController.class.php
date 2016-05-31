<?php
namespace User\Controller;
class StaffController extends \Admin\Classes\AdminController{
	/*头像*/
	public function public_head(){
		$pic1=base64_decode($_POST['pic1']);   
		$pic2=base64_decode($_POST['pic2']);  
		$pic3=base64_decode($_POST['pic3']); 
		$md5_str=md5(session('userinfo.username').'admin'); 		
		$file_name1=$md5_str.'_big.png';
		$file_name2=$md5_str.'_middle.png';
		$file_name3=$md5_str.'_small.png';
		if(IS_POST)
		{
			if(C('FILE_UPLOAD_TYPE')=='Alioss')
			{
				$oss=new \Think\Upload\Driver\Alioss;
				$file1=array(
				'savepath'=>'/crop/head/',
				'savename'=>$file_name1,
				'content'=>$pic1
				);
				$file2=array(
				'savepath'=>'/crop/head/',
				'savename'=>$file_name2,
				'content'=>$pic2
				);
				$file3=array(
				'savepath'=>'/crop/head/',
				'savename'=>$file_name3,
				'content'=>$pic3
				);								
				$url=$oss->save($file1);
				$url=$oss->save($file2);
				$url=$oss->save($file3);	
				M('manage_staff')->where(array('adminid'=>session('userinfo.userid')))->setField('ishead',1);
			}
			else
			{
				$file_path=C('UPLOAD_PATH').'/crop/head/';
				dir_create($file_path);
				file_put_contents($file_path.$file_name1, $pic1);
				file_put_contents($file_path.$file_name2, $pic2);
				file_put_contents($file_path.$file_name3, $pic3);
				M('admin')->where(array('adminid'=>session('userinfo.userid')))->setField('ishead',1);
			}
			$rs['status'] = 1;
			echo json_encode($rs);								
		}
		else
		{
			require($this->admin_tpl('Staff/head'));
		}
	}
}