<?php
/*
* 这个配置文件是用来设置thinkphp自带的配置项
*/
$db=include "./Conf/db.php";
$configs = array(
	//'配置项'=>'配置值'
	"LOAD_EXT_FILE"=>"load_front_plug,form,dir",//扩展函数
	'URL_CASE_INSENSITIVE' =>true, //false区分大小写,true不区分大小写
	'URL_ROUTER_ON'   => true,
	'URL_MODEL' =>2,
	//提示信息模板
	'ERROR_PAGE'=>__ROOT__.'/Public/404/error.html',
    'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'Tpl/think_exception.tpl',// 异常页面的模板文
	//缓存配置
	'DATA_CACHE_TYPE'       =>  'File',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator|Redis
	'DATA_CACHE_TIME'       =>  3600,      // 数据缓存有效期 0表示永久缓存
	'CLOSE_WINDOW'      =>'/Public/tpl/close.htm', //窗口关闭URL跳转地址 例子：$this->success('添加成功！',CLOSE_WINDOW);
	'DEFAULT_MODULE'=>'Home',//默认模块
	/*
	* 文件上传		
	*/
	'UPLOAD_PATH'      =>'./Uploads', //相对于入口文件上传的路径
	'UPLOAD_URL'       => '/Uploads', //文件的URL地址
	'UPLOAD_ALLOW_EXT' =>array('jpg', 'gif', 'jpeg', 'png', 'bmp', 'mp4','mp3','zip','rar'), //允许上传的文件类型
	'maxSize'=>'2048',
	/*添加模板替换规则*/
	'TMPL_PARSE_STRING'  =>array(
        '__PUBLIC__' => '/Public',
		'__JQUERY__'  => '/Public/js/jquery.min.js'
	),
	'DB_BACKUP'=> RUNTIME_PATH.'dbbackup/',
	'login_theme'=>'login_new',//登录界面主题
	'version'=>'2.0'
);
return  array_merge($configs,$db);