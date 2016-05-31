<?php
/*
* 数据上传入口文件
*/
namespace Plug\Controller;
use Think\Controller;
class InputController extends \Admin\Classes\CommonController{
	
	private $rootpath = '/Uploads';	
	/*
	* swfupload图片批量上传
	*/
	public function swfUpload()
	{
        ignore_user_abort(true);//忽略浏览器关闭
		ini_set('max_execution_time', '7200'); 
		//权限验证开始
		$encrypt=M('admin')->cache(true,60)->where(array('username'=>$_POST['user']))->getField('encrypt');
		$swfuploadsessid=$_POST['SWFUPLOADSESSID'];
		$swf_auth_key=password($swfuploadsessid,$encrypt);
		if($swf_auth_key!=$_POST['swf_auth_key'])exit;
		//权限验证结束
		$upload = new \Think\Upload(array('driver'=>C('FILE_UPLOAD_TYPE')));
		$upload->maxSize   =     C('UPLOAD_FILE_MAX_SIZE')*1024;// 设置附件上传大小
		$upload->exts      =     C('UPLOAD_ALLOW_EXT');// 设置附件上传类型
		$upload->rootPath  =     '.'.$this->rootpath; // 设置附件上传根目录
		$upload->autoSub = false;
		$upload->savePath  =     '/files/'.date('Y/md').'/'; // 设置附件上传（子）目录		
		$info=$upload->uploadOne($_FILES['Filedata']);
		
		//生成缩略图
		$img_url=C('upload_host').$this->rootpath.'/files/'.date('Y/md').'/'.$info['savename'];
		//把上传的文件存入文件数据库,如果上传成功把数据返回给前端
		$m=M("PlugUploads");
		$data=array(
			'url'=>$img_url,
			'size'=>$info['size'],
			'uptime'=>time(),
			'owner'=>$_POST['user'],
			'mediatype'=>$info['ext'],
            'thumb'=>$_POST['thumb_specs']
		);		
		$id=$m->data($data)->filter('new_html_special_chars')->add();	
		if($id)
		{
			ob_end_clean();
			header("Connection: close");
			header("HTTP/1.1 200 OK"); 
			ob_start();
			echo($id.'|%|'.$data['url'].'|%|1|%|'.$info['name']);
			$size=ob_get_length();
			header("Content-Length: $size");
			ob_end_flush();
			flush();
			$thumb_specs=array_filter(explode(',',$_POST['thumb_specs']));
			foreach($thumb_specs as $v)
			{
				thumb($img_url,$v,$v);
			}
		}
		else
		{
			exit('图片上传数据库失败!');
		}		
	}
	/*
	*　croproom 图片剪切
	*/
	public function cropzoomUpload()
	{
		if(session("userinfo")==NULL)E('没有登陆！');
		load('@.cropzoom');
		list($width, $height) = getimagesize($_POST["imageSource"]);
		$viewPortW = $_POST["viewPortW"];
		$viewPortH = $_POST["viewPortH"];
		$pWidth = $_POST["imageW"];
		$pHeight =  $_POST["imageH"];
		$ext = end(explode(".",$_POST["imageSource"]));
		$function = returnCorrectFunction($ext);
		$image = $function($_POST["imageSource"]);
		$width = imagesx($image);
		$height = imagesy($image);
		// Resample
		$image_p = imagecreatetruecolor($pWidth, $pHeight);
		setTransparency($image,$image_p,$ext);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $pWidth, $pHeight, $width, $height);
		imagedestroy($image);
		$widthR = imagesx($image_p);
		$hegihtR = imagesy($image_p);
		
		$selectorX = $_POST["selectorX"];
		$selectorY = $_POST["selectorY"];
		
		if($_POST["imageRotate"]){
			$angle = 360 - $_POST["imageRotate"];
			$image_p = imagerotate($image_p,$angle,0);
			
			$pWidth = imagesx($image_p);
			$pHeight = imagesy($image_p);
			
			//print $pWidth."---".$pHeight;
		
			$diffW = abs($pWidth - $widthR) / 2;
			$diffH = abs($pHeight - $hegihtR) / 2;
				
			$_POST["imageX"] = ($pWidth > $widthR ? $_POST["imageX"] - $diffW : $_POST["imageX"] + $diffW);
			$_POST["imageY"] = ($pHeight > $hegihtR ? $_POST["imageY"] - $diffH : $_POST["imageY"] + $diffH);
		
			
		}
		
		
		
		$dst_x = $src_x = $dst_y = $src_y = 0;
		
		if($_POST["imageX"] > 0){
			$dst_x = abs($_POST["imageX"]);
		}else{
			$src_x = abs($_POST["imageX"]);
		}
		if($_POST["imageY"] > 0){
			$dst_y = abs($_POST["imageY"]);
		}else{
			$src_y = abs($_POST["imageY"]);
		}
		
		
		$viewport = imagecreatetruecolor($_POST["viewPortW"],$_POST["viewPortH"]);
		setTransparency($image_p,$viewport,$ext);
		
		imagecopy($viewport, $image_p, $dst_x, $dst_y, $src_x, $src_y, $pWidth, $pHeight);
		imagedestroy($image_p);
		
		
		$selector = imagecreatetruecolor($_POST["selectorW"],$_POST["selectorH"]);
		setTransparency($viewport,$selector,$ext);
		imagecopy($selector, $viewport, 0, 0, $selectorX, $selectorY,$_POST["viewPortW"],$_POST["viewPortH"]);
		
		//获取图片内容
		ob_start();
		parseImage($ext,$selector);
		$img = ob_get_contents();
		ob_end_clean();
		
		if(filter_var($_POST["imageSource"], FILTER_VALIDATE_URL))
		{
			$urlinfo=parse_url($_POST["imageSource"]);
			$path=$urlinfo['path'];
			$pathinfo=pathinfo($path);	
		}
		else
		{
			$path=$_POST["imageSource"];
			$pathinfo=pathinfo($_POST["imageSource"]);
		}
		$file_name=$pathinfo['filename'].'_crop.'.$pathinfo['extension'];//剪切后的图片名称
		$file_path='.'.$pathinfo['dirname'].'/'.$file_name;
		
		file_put_contents($file_path, $img);
		echo C('upload_host').$pathinfo['dirname'].'/'.$file_name;
		imagedestroy($viewport);
	}
}