<?php
/*
* 这是一个所有其它模块控制器都必须继承的控制器
*/
namespace Admin\Classes;
use Think\Controller;
class CommonController extends Controller {
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
}