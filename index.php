<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 定义应用目录
define('APP_PATH','./Modules/');
//define('BIND_MODULE','Mobile');
define('APP_DEBUG',true);
define('COMMON_PATH', './');//公共函数目录
define('RUNTIME_PATH','./Runtime/');//缓存目录
if(!file_exists("./Runtime/install.lock")){
	define('INSTALLED',false);
	if(stripos($_SERVER['PATH_INFO'],'/Install')!==0){
		header("Location:/Install");
		exit;
	}
}
else
{
	define('BUILD_LITE_FILE',false);//生成lite文件
	define('INSTALLED',true);
}	
require './ThinkPHP/ThinkPHP.php';// 引入ThinkPHP入口文件
