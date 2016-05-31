<?php
/**
 * 
 * @author Nintendov
 */

namespace Vin\FileStorage\Driver;
use Vin\FileStorage;

class Alioss extends FileStorage{
	private $oss,$bucket;
	public function __construct(){
		if(C('FILE_UPLOAD_TYPE')=='Alioss')
		{
			import('Vendor.Alioss.sdk');
			$this->oss=new \sdk();
			$this->bucket=C('ALI_OSS_BUCKET');
		}
		else
		{
			return true;
		}
	}
	/**
	 * 写文件
	 * @param 文件名
	 * @param 文件内容
	 * @param 限制尺寸 默认不限制
	 * @param 是否覆盖 默认是
	 */	
	public function put($rootpath,$filename, $content,$maxSize=-1, $cover = TRUE){
		$rootpath = trim($rootpath,'/');
		$filename=$rootpath.$filename;
		$upload_file_options = array(
			'content' => $content
		);	
		$response = $this->oss->upload_file_by_content($this->bucket,$filename,$upload_file_options);
		if($response)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function listFile($rootpath,$path , $allowFiles='all'){
/*		$new_rootpath = trim($rootpath,'/');
		$options = array(
			'delimiter' => '/',
			'prefix' => 'Uploads/'
		);
		
	    $response = $this->oss->list_object($this->bucket,$options);
		$lists=xml_to_array($response->body);
		//dump($lists);
		//$arr=xml2array($response);
		$list[0] = array(
	                    'url'=> json_encode($response),
	                    'mtime'=> '2234234234'
	                );
		//return $list;	*/		
	}
	private function getFlies($path)
	{
		
	}
	/**
	 * 得到路径
	 */
	public function getPath($rootpath,$path){
		$url=C('UPLOAD_HOST').$rootpath.$path;
		return $url;
	}	
}