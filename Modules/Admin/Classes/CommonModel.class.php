<?php
namespace Admin\Classes;
use Think\Model;
class CommonModel extends Model {
	public function __construct()
	{	
		parent::__construct();
	}
	/**
	 * 获取类的方法生成缓存
	 */	
	protected final function getCache(){
		$info=debug_backtrace();
		$info=$info[1];
		$md5str=md5($info['class'].$info['function'].serialize($info['args']));
		return S($md5str);
		}
	/**
	 * 类的方法生创建缓存
	 * @param $value 缓存值
	 * @param $time 缓存时间
	 * @param $time 缓存名称
	 */		
	protected final function setCache($value,$time=0,$title){
		$info=debug_backtrace();
		$info=$info[1];
		unset($info['args']['delcache']);
		$args=serialize($info['args']);
		$md5str=md5($info['class'].$info['function'].$args);
		$m=M('admin_cache');
		$num=$m->where(array('name'=>$md5str))->count();
		$data=array(
			'name'=>$md5str,
			'title'=>$title,
			'namespace'=>$info['class'],
			'function'=>$info['function'],
			'args'=>$args,
			'creattime'=>time(),
			'long'=>$time,
			'clear'=>0,
		);
		if($num==0)
		{
            $m->filter('new_html_special_chars')->data($data)->add();
		}
		else
		{
           $m->where(array('name'=>$md5str))->filter('new_html_special_chars')->data($data)->save();
		}
		S($md5str,$value,$time);
		}
	/**
	 * 删除缓存
	 * @param $function 方法
	 * @param $args 参数
 	 * @param $namespace 命名空间	 
	 */		
	protected final function delCache($function,$args,$namespace){
		//如何命名空间的位置为空则自动获取调用delCache的位置的命名空间
		if(empty($namespace))
		{
			$info=debug_backtrace();
			$info=$info[1];
			$namespace=$info['class'];
		}
		$args=empty($args)?array():$args;
		$args=is_array($args)?serialize($args):$args;
		$md5str=md5($namespace.$function.$args);
		S($md5str,NULL);
	}
        //获取url中参数的值 
        function geturlval($url,$name) 
        { 
            $arr = parse_url($url); 
            $arr_query = $this->convertUrlQuery($arr['query']); 
                   
            return $arr_query[$name]; 
           
        } 
        function convertUrlQuery($query) 
        { 
            $queryParts = explode('&', $query); 
           
            $params = array(); 
            foreach ($queryParts as $param) 
            { 
                $item = explode('=', $param); 
                $params[$item[0]] = $item[1]; 
            } 
           
            return $params; 
        } 
        /**
         * 抓取远程图片
         *
         * @param string $url 远程图片路径
         * @param string $filename 本地存储文件名
         */ 
        function grabImage($url, $savepath) { 
            if($url =="") { 
                return false; //如果 $url 为空则返回 false; 
            } 
            $ext_name = strrchr($url, '.'); //获取图片的扩展名 
            if($ext_name != '.gif' && $ext_name != '.jpg' && $ext_name != '.bmp' && $ext_name != '.png') { 
                return false; //格式不在允许的范围 
            } 
            //获取原图片名 
            $filename = $savepath.'\\'.end(explode('/',$url)); 
            //开始捕获 
            ob_start(); 
            readfile($url); 
            $img_data = ob_get_contents(); 
            ob_end_clean(); 
            $size = strlen($img_data); 
            $local_file = fopen($filename , 'a'); 
            echo $filename; 
            if(fwrite($local_file, $img_data)== FALSE){ 
                echo '图片下载失败'; 
            } 
            fclose($local_file); 
            return $filename; 
        } 
	/**
	 * 检查表是否存在
	 * @param $table 表名
	 * @return boolean
	 */
	 function table_exists($table) {
		$tables = $this->list_tables();
		return in_array($this->tablePrefix.$table, $tables) ? 1 : 0;
	}
	
	 function list_tables() {
		$tables = array();
		$data=$this->query("SHOW TABLES");
		foreach($data as $v)
		{
			$tables[]=reset($v);
		}
		return $tables;
	}
	/**
	 * 获取表字段
	 * @param $table 		数据表
	 * @return array
	 */
	 function get_fields($table) {
		$fields = array();
		$data=$this->query("SHOW COLUMNS FROM $table");
		foreach($data as $v)
		{
			$fields[]=reset($v);
		}
		return $fields;
	} 								
}