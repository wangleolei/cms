<?php
/*
* 上传的文件管理
*/
namespace Plug\Controller;
class UploadsController extends \Admin\Classes\AdminController{
	private $uploads,$oss;
	function __construct(){
		parent::__construct();
		$this->uploads=M('plug_uploads');
		}	
	function lists()
	{
		$condition=array();
		//时间范围筛选
		if($_GET['end_time'] && $_GET['begin_time'])
		{
			$condition['uptime']=array(array('lt',strtotime($_GET['end_time'])+3600*24),array('gt',strtotime($_GET['begin_time'])),'and');
		}
		else
		{
			/*发布开始时间段*/
			if($_GET['begin_time'])
			{
				$condition['uptime']=array('gt',strtotime($_GET['begin_time']));
			}
			/*发布时间结束时间段*/
			if($_GET['end_time'])
			{
				$condition['uptime']=array('lt',strtotime($_GET['end_time'])+3600*24);
			}				
		}
		//搜索
		$keyword=trim($_GET['keyword']);
		if($keyword)
		{
			switch($_GET['type'])
			{
				case 1:$condition['title']=array('like',"%{$keyword}%");break;
				case 2:$condition['owner']=$keyword;break;
			}
		}				
		$count = $this->uploads->where($condition)->count();
		$Page  = new \Think\Page($count,15);
		$show       = $Page->show();
		$list = $this->uploads->where($condition)->order('uptime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		include($this->admin_tpl('Uploads/lists'));
	}
	function add()
	{
		if(IS_POST)
		{
			$info=$_POST['info'];
			if(!(empty($info['url'])))
			{
				$fileext = strtolower(fileext($info['url']));
				
				if(!in_array($fileext,C('UPLOAD_ALLOW_EXT')))
				{
					$this->error('上传文件类型非法！');
					exit;
				}
				$new_file=uniqid().'.'.$fileext;
				
				$info['uptime']=time();
				$info['mediatype']=$fileext;
				$pic=file_get_contents($info['url']);
				$info['size']=strlen($pic);
				if(C('FILE_UPLOAD_TYPE')=='Alioss')
				{
					$file=array(
					'savepath'=>'/files/'.date('Y/md/'),
					'savename'=>$new_file,
					'content'=>$pic
					);
					$oss=new \Think\Upload\Driver\Alioss;
					$info['url']=$oss->save($file);	
				}
				else
				{
					dir_create(C('UPLOAD_PATH').'/files/'.date('Y/md/'));
					file_put_contents(C('UPLOAD_PATH').'/files/'.date('Y/md/').$new_file, $pic);
					$info['url']=C('upload_host').C('UPLOAD_URL').'/files/'.date('Y/md/').$new_file;
				}	
				$info['thumb']=implode(',',$_POST['thumb_specs']);		
				$this->uploads->data($info)->filter('new_html_special_chars')->field('title,url,uptime,thumb,mediatype,size')->add();
				//生成缩略图		
				ob_end_clean();
				header("Connection: close");
				header("HTTP/1.1 200 OK"); 
				ob_start();
				$this->success('上传文件成功!');
				$size=ob_get_length();
				header("Content-Length: $size");
				ob_end_flush();
				flush();
				$thumb_specs=$_POST['thumb_specs'];
				foreach($thumb_specs as $v)
				{
					thumb($info['url'],$v,$v);
				}				
			}
			$this->success('上传文件完成!');
		}
		else
		{
			$encrypt=M('admin')->cache(true,60)->where(array('username'=>session('userinfo.username')))->getField('encrypt');
			$swfuploadsessid=uniqid();
			$swf_auth_key=password($swfuploadsessid,$encrypt);
			$thumb_specs=array_unique(array_filter(explode(',',C('THUMB_SPEC'))));			
			include($this->admin_tpl('Uploads/add'));
		}
	}
	/*
	* 删除个文件
	*/
	function delete()
	{	
		$id=intval($_GET['id']);
		$file=$this->uploads->where(array('id'=>$id))->field('url,thumb')->find();
		//删除原图
		$this->_delete_file($file['url']);
		//删除缩略图
		$thumbs=explode(',',$file['thumb']);
		foreach ($thumbs as $v) {
			$this->_delete_file(thumb_url($file['url'],$v,$v));
		}
		$bool=$this->uploads->where(array('id'=>$id))->delete();
		if($bool)
		{
			$this->success('删除文件成功！');
		}
		else
		{
			$this->success('删除文件失败！');	
		}
	}
	private function _delete_file($url)
	{
		if(C('FILE_UPLOAD_TYPE')=='Alioss')
		{
			if(!$this->oss)
			{
				import('Vendor.Alioss.sdk');
				$this->oss=new \sdk();
			}
			$urlinfo=parse_url($url);
			$this->oss->delete_object(C('ALI_OSS_BUCKET'),substr($urlinfo['path'],1));
		}
		elseif(C('FILE_UPLOAD_TYPE')=='Local')
		{
			$urlinfo=parse_url($url);
			unlink('.'.$urlinfo['path']);
		}
	}
	/*
	* 删除选中的文件
	*/
	function deleteAll()
	{
		$ids=$_POST['ids'];
		if(!$ids)
		{
			$this->error('你没有选中的有图片！');
			exit;
		}	
		$idstr=implode(',',$ids);
		$files=$this->uploads->where(array('id'=>array('in',$idstr)))->field('url,thumb')->select();
		foreach($files as $file)
		{
			//删除原图
			$this->_delete_file($file['url']);
			//删除缩略图
			$thumbs=explode(',',$file['thumb']);
			foreach ($thumbs as $v) {
				$this->_delete_file(thumb_url($file['url'],$v,$v));
			}
		}
		$bool=$this->uploads->where(array('id'=>array('in',$idstr)))->delete();
		if($bool)
		{
			$this->success('删除选中文件成功！');
		}
		else
		{
			$this->success('删除选中文件失败！');
		}	
	}	
	/*
	* 修改一个文件名称
	*/
	function edit()
	{
		$id=intval($_GET['id']);
		$bool=$this->uploads->where(array('id'=>$id))->filter('new_html_special_chars')->setField('title',$_POST['title']);
		if($bool!==false)die('success');
	}
	/*
	* 生成缩略图
	*/
	function creatTrumb()
	{
		ignore_user_abort(true);//忽略浏览器关闭
		ini_set('max_execution_time', '7200');
		 
		$ids=$_POST['ids'];
		if(!$ids)die('你没有选中的有图片！');
		header("Connection: close");
		header("HTTP/1.1 200 OK"); 
		ob_start();
		echo 'success';
		$size=ob_get_length();
		header("Content-Length: $size");
		ob_end_flush();
		flush();
		//生成缩略图
		$idstr=implode(',',$ids);
		$types=array_unique(array_filter(explode(',',$_GET['type'])));
		$images=$this->uploads->where(array('id'=>array('in',$idstr)))->field('id,url,thumb')->select();
		foreach($images as $img)
		{
			$thumb=array_filter(explode(',',$img['thumb']));
			$data=array();
			foreach($types as $v)
			{
				if(!in_array($v,$thumb) && is_numeric($v))
				{
					thumb($img['url'],$v,$v);
					$thumb[]=$v;
				}
			}
			sort($thumb);
			$this->uploads->where(array('id'=>$img['id']))->setField('thumb',implode(',',$thumb));
		}
	}
}