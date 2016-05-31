<?php
namespace Think\Upload\Driver;
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2014-07-11 13:42:25
 * @version $Id$
 */

class alioss{
	
	private $oss;
	
	private $bucket;
    /**
     * 本地上传错误信息
     * @var string
     */
    private $error      =   '';

    /**
     * 构造函数，用于设置上传根路径
     * @param array  $config 配置
     */
    public function __construct($config){
		$this->bucket=C('ALI_OSS_BUCKET');
    }

    /**
     * 检测上传根目录(OSS上传时支持自动创建目录，直接返回)
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath){
        /* 设置根目录 */
        return true;
    }

    /**
     * 检测上传目录(OSS上传时支持自动创建目录，直接返回)
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        return true;
    }

    /**
     * 创建文件夹 (OSS上传时支持自动创建目录，直接返回)
     * @param  string $savepath 目录名称
     * @return boolean          true-创建成功，false-创建失败
     */
    public function mkdir($savepath){
        return true;
    }

    /**
     * 保存指定文件
     * @param  array   $file    保存的文件信息
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     */
    public function save(&$file,$replace=true) {
        $path = 'Uploads'.$file['savepath'] . $file['savename'];		
		if(isset($file['content']))
		{
			$upload_file_options = array(
			'content' => $file['content'],
			/*
			'length' => strlen($file['content']),
			\sdk::OSS_HEADERS => array(
				//'Expires' => '2015-10-01 08:00:00',
			),
			*/
			);			
			$response = $this->create_oss()->upload_file_by_content($this->bucket,$path,$upload_file_options);
		}
		else
		{
			$response= $this->create_oss()->upload_file_by_file($this->bucket,$path,$file['tmp_name']);
		}
        if ($response) {
            return C('upload_host').'/'.$path;
        }else{
            return false;
        }
    }
	/*
	* 检查文件是否存在
	*/
	function is_object_exist($object)
	{
		return ($this->create_oss()->is_object_exist($this->bucket, $object)->status==200);
	}
	/*
	*创建oss对象
	*/
	private function create_oss()
	{
		if(!isset($this->oss))
		{
			import('Vendor.Alioss.sdk');
			return $this->oss=new \sdk();
		}
		else
		{
			return $this->oss;
		}
	}
    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError(){
		return true;
    }
}