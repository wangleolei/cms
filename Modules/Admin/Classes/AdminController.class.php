<?php
/*
* 所有后台相关操作的控制器都必须继承于该控制器
*/
namespace Admin\Classes;
class AdminController extends CommonController {
	protected $auth,$is_submenu,$menu_type;
    public function __construct() {
		parent::__construct();
		//1.如果后台绑定了域名则检查是否是指定域名
		if(C('admin_url') && $_SERVER['HTTP_HOST']!=C('admin_url'))
		{
			send_http_status(404);
			exit;
		}		
		//2.验证登陆，在没有登陆的情况下跳转到登陆窗口
		if(!$this->check_login()&&!(in_array(U(),array(U('Admin/Index/login')))))
		{
			$this->redirect('Admin/Index/login');
			exit;	
		}		
        //3.如果锁定的情况下访问非登陆锁定窗口的页面就直接注销登陆
		if(session('lock')=="locked"&&ACTION_NAME!='login_screenlock')
		{
			$this->_logout();
			$this->error('屏幕锁定，请重新登陆！',U('Admin/Index/login'));
			exit;
		}		
		$this->auth=new \Think\Auth();
		//4.检测权限状态
        if(!$this->check_prev())
		{
			$this->tip('你没有权限执行该操作！');
			exit;
		}	
		$this->_is_Submenu();
    }
    /*
     * 验证用户是否登陆
     */
   final protected function check_login()
    {
	  return session("userinfo")?true:false;
    }
    /*
     * 验证用户权限
	 * 如果设置参数$ndoe则验证$node的权限,否则验证用户当前访问的节点权限
	 * 所有表单不得使用GET传值，否则会导致权限检查错误
     */    
   final protected function check_prev($node=array())
   {
	   	//如果是系统管理员则不需要验证
	   if(session('userinfo.userid')==1 or session('userinfo.groupid')==1)return true;   
	   if(!empty($node))
	   {
		 $m_name=$node['m'];
		 $c_name=$node['c'];
		 $a_name=$node['a'];
		 $data=($node['data'])?$this->_get_data($node['data']):'';  
	   }
	   else
	   {
		  $m_name=MODULE_NAME;
		  $c_name=CONTROLLER_NAME;
		  $a_name=ACTION_NAME;
		  $data=$this->_get_data();
		}
		//公共的控制器不需要进行权限控制	
		if(strtolower($c_name)=='public')return true;
		//公共的方法不需要进行权限控制
		if(strstr($a_name,'public_'))return true;	
		//Index控制器中的所有方法不需要进行权限验证
		if($m_name=="Admin"&&$c_name=="Index")return true;
		//ajax方法都不进行权限验证
		if(preg_match('|ajax|',$a_name)) return true;		
		//验证权限表中的权限
		if($this->auth->check($m_name.'/'.$c_name.'/'.$a_name.'/'.$data,session('userinfo.userid')))return true;
	    //没通过权限验证
	    return false;
	}
	/**
	 * 按父ID查找菜单子项
	 * @param integer $parentid   父菜单ID  
	 * @param integer $with_self  是否包括他自己
	 */
	final public function admin_menu($parentid, $with_self = 0) {
		$m_menu=M('admin_menu');
		$condition=array("parentid"=>$parentid,'display'=>1);
		//把系统管理员排除在用户菜单之外
		if($parentid==0 && (session('userinfo.userid')==1 || session('userinfo.groupid')==1))
		{
			$condition['id']=array('neq',194);
		}
		$array  = $m_menu->where($condition)->order('listorder DESC,id DESC')->select();
		if(session('userinfo.userid')==1||session('userinfo.groupid')==1)
		{
			return $array;	
		}
		else
		{
			$array2=array();
			foreach($array as $info)
			{
				if($this->check_prev(array('m'=>$info['m'],'c'=>$info['c'],'a'=>$info['a'],'data'=>$info['data'])))
				{
					$array2[]=$info;
				}
			}
			return $array2;
		}  
	}
	//后台模板地址
	final protected function admin_tpl($name)
	{
		if(preg_match('|^/|',$name))
		{
			$arr=array_filter(explode('/',$name));
			$str=APP_PATH.$arr[1].'/templates/';
			array_shift($arr);
			return  $str=$str.implode('/',$arr).'.php';
		}
		elseif(!empty($name))
		{
			return MODULE_PATH.'templates'.DIRECTORY_SEPARATOR.$name.'.php';
		}
		else
		{
			$info=debug_backtrace();
			$info=$info[1];
			return MODULE_PATH.'templates'.DIRECTORY_SEPARATOR.CONTROLLER_NAME.DIRECTORY_SEPARATOR.$info['function'].'.php';
		}
	}
	/*
	*生成副窗口的菜单
	*/
  	final protected function SubMenu()
	{
		$m_menu=M("admin_menu");
		$data=$this->_get_data();
		$cond1=array('m'=>MODULE_NAME,'c'=>CONTROLLER_NAME,'a'=>ACTION_NAME);
		//if($data)$cond1['data']=$data;
		if($this->menu_type==1)$cond1['data']=$data;
		$val=$m_menu->where($cond1)->find();

		$stage=substr_count($val['path'],'-')-1;
		if($stage==3)
		{
			$arr=$m_menu->where(array('parentid'=>$val['id'],'project1'=>1))->order('listorder DESC,id DESC')->select();
		}
		elseif($stage==4)
		{
			$arr=$m_menu->where(array('parentid'=>$val['parentid'],'project1'=>1))->order('listorder DESC,id DESC')->select();
			$val=$m_menu->where(array('id'=>$val['parentid'],'project1'=>1))->find();	
		}
		$arr=empty($arr)?array():$arr;
		array_unshift($arr,$val);
		$str='';
		foreach($arr as $v)
		{
			if($v['project1']==0)continue;//不显示菜单按钮
			if(!$this->check_prev($v))continue;//如果没有权限则无法看见菜单
			//替换get变量
			preg_match_all('|get_([a-zA-Z0-9_]*)|',$v['data'],$tmparr);
			foreach($tmparr[1] as $k)
			{
				$v['data']=str_replace('get_'.$k,$_GET[$k],$v['data']);
			}			
			$span='<span>|</span>';	
			$target=($v['target']==1)?'target="_blank"':'';
			if($v['project2']==1 && $v['project3']==1)
			{
				$str="<a class=\"button  button-tkp button-tiny\" href=\"javascript:window.top.art.dialog.open('".U($v['m'].'/'.$v['c'].'/'.$v['a'].'/'.$v['data'])."',{id:'$v[a]', title:'$v[name]', width:$v[project5], height:$v[project4], lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:function(){window.top.art.dialog({id:'$v[a]'}).close()}});void(0);\"><i class=\"fa fa-plus fa-lg\"></i> <em>$v[name]</em></a><span></span>".$str;
				continue;
			}
			elseif($v['project2']==1 && $v['project3']==0)
			{
				$class_str='';
				if($v['a']==ACTION_NAME&&$v['c']==CONTROLLER_NAME&&$v['m']==MODULE_NAME&&$v['data']==$data)
				{
					$class_str= 'class="button button-tkp button-tiny"';
				}				
				$str.="<a href=\"javascript:window.top.art.dialog.open('".U($v['m'].'/'.$v['c'].'/'.$v['a'].'/'.$v['data'])."',{id:'$v[a]', title:'$v[name]', width:$v[project5], height:$v[project4], lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:function(){window.top.art.dialog({id:'$v[a]'}).close()}});void(0);\"><em>$v[name]</em></a>".$span;
				continue;
			}
			elseif($v['project2']==0 && $v['project3']==1)
			{
				$str="<a class=\"button button-tkp button-tiny\" href=\"".U($v['m'].'/'.$v['c'].'/'.$v['a'].'/'.$v['data'])."\" {$target}><i class=\"fa fa-plus fa-lg\"></i> <em>$v[name]</em></a><span></span>".$str;
				continue;
			}
			else
			{
				$class_str='';
				if($v['a']==ACTION_NAME&&$v['c']==CONTROLLER_NAME&&$v['m']==MODULE_NAME&&($v['data']==$data || $this->menu_type==2))
				{
					$class_str= 'class="button button-tkp button-tiny"';
				}
				$str.="<a href='".U($v['m'].'/'.$v['c'].'/'.$v['a'].'/'.$v['data'])."' ".$class_str." {$target}><em>$v[name]</em></a>".$span;	
				continue;
			}
		}
		$str='<div class="subnav"><div class="content-menu ib-a blue line-x">'.$str.'</div></div>';
		return preg_replace('/<span>\|<\/span><\/div><\/div>/','</div></div>',$str);//去除最后一个分隔符
	}
	/*
	* 是否需要副导航
	*/	
	final protected function _is_Submenu()
	{
		$m_menu=M('admin_menu');
		$data=$this->_get_data();
		$condition=array('m'=>MODULE_NAME,'c'=>CONTROLLER_NAME,'a'=>ACTION_NAME,'data'=>$data,'project6'=>1);
		$val=$m_menu->where($condition)->count();
		if($val==1)
		{
			$this->is_submenu=true;
			$this->menu_type=1;
		}
		else
		{
			$condition=array('m'=>MODULE_NAME,'c'=>CONTROLLER_NAME,'a'=>ACTION_NAME,'project6'=>1);
			$val=$m_menu->where($condition)->count();	
			$this->is_submenu=($val==1)?true:false;
			$this->menu_type=2;
		}
	}
	/*
	* 创建菜单
	*/
	final protected function creatSubmenu()
	{
		return ($this->is_submenu)?$this->subMenu():'';
	} 
	/*
	* 注销登陆
	*/
	final public function _logout()
	{
		session(NULL);
		session_unset();
		session_destroy();
	}
	final protected function _get_data($arr)
	{
		$arr=empty($arr)?$_GET:$arr;
		$data='';
		foreach($arr as $k=>$v)
		{
			if(preg_match('/^pow_/',$k))
			{
				$data.='/'.$k.'/'.$v; 
			}
		}
		return (empty($data))?'':substr($data,1);
	}
	/*检测是否拥有该约束
	 * @param integer $id 约束id
	*/
	final protected function check_constraint($id)
	{
		//系统管理员不进行权限约束
		if(session('userinfo.userid')==1||session('userinfo.groupid')==1)return false;
		$constraint=session('userinfo.constraint');
		$arr=explode(',',$constraint);
		return (in_array($id,$arr))?true:false;
	}
	/*
	* 检测url权限
	*/
	final protected function check_url_prev($url='',$vars='')
	{
    // 解析URL
    $info   =  parse_url($url);
    $url    =  !empty($info['path'])?$info['path']:ACTION_NAME;
    if(isset($info['fragment'])) { // 解析锚点
        $anchor =   $info['fragment'];
        if(false !== strpos($anchor,'?')) { // 解析参数
            list($anchor,$info['query']) = explode('?',$anchor,2);
        }        
        if(false !== strpos($anchor,'@')) { // 解析域名
            list($anchor,$host)    =   explode('@',$anchor, 2);
        }
    }elseif(false !== strpos($url,'@')) { // 解析域名
        list($url,$host)    =   explode('@',$info['path'], 2);
    }
    // 解析子域名
    if(isset($host)) {
        $domain = $host.(strpos($host,'.')?'':strstr($_SERVER['HTTP_HOST'],'.'));
    }elseif($domain===true){
        $domain = $_SERVER['HTTP_HOST'];
        if(C('APP_SUB_DOMAIN_DEPLOY') ) { // 开启子域名部署
            $domain = $domain=='localhost'?'localhost':'www'.strstr($_SERVER['HTTP_HOST'],'.');
            // '子域名'=>array('模块[/控制器]');
            foreach (C('APP_SUB_DOMAIN_RULES') as $key => $rule) {
                $rule   =   is_array($rule)?$rule[0]:$rule;
                if(false === strpos($key,'*') && 0=== strpos($url,$rule)) {
                    $domain = $key.strstr($domain,'.'); // 生成对应子域名
                    $url    =  substr_replace($url,'',0,strlen($rule));
                    break;
                }
            }
        }
    }

    // 解析参数
    if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
        parse_str($vars,$vars);
    }elseif(!is_array($vars)){
        $vars = array();
    }
    if(isset($info['query'])) { // 解析地址里面参数 合并到vars
        parse_str($info['query'],$params);
        $vars = array_merge($params,$vars);
    }
    // URL组装
    $depr       =   C('URL_PATHINFO_DEPR');
    $urlCase    =   C('URL_CASE_INSENSITIVE');
    if($url) {
        if(0=== strpos($url,'/')) {// 定义路由
            $route      =   true;
            $url        =   substr($url,1);
            if('/' != $depr) {
                $url    =   str_replace('/',$depr,$url);
            }
        }else{
            if('/' != $depr) { // 安全替换
                $url    =   str_replace('/',$depr,$url);
            }
            // 解析模块、控制器和操作
            $url        =   trim($url,$depr);
            $path       =   explode($depr,$url);
            $var        =   array();
            $varModule      =   C('VAR_MODULE');
            $varController  =   C('VAR_CONTROLLER');
            $varAction      =   C('VAR_ACTION');
            $var[$varAction]       =   !empty($path)?array_pop($path):ACTION_NAME;
            $var[$varController]   =   !empty($path)?array_pop($path):CONTROLLER_NAME;
            if($maps = C('URL_ACTION_MAP')) {
                if(isset($maps[strtolower($var[$varController])])) {
                    $maps    =   $maps[strtolower($var[$varController])];
                    if($action = array_search(strtolower($var[$varAction]),$maps)){
                        $var[$varAction] = $action;
                    }
                }
            }
            if($maps = C('URL_CONTROLLER_MAP')) {
                if($controller = array_search(strtolower($var[$varController]),$maps)){
                    $var[$varController] = $controller;
                }
            }
            if($urlCase) {
                $var[$varController]   =   parse_name($var[$varController]);
            }
            $module =   '';
            
            if(!empty($path)) {
                $var[$varModule]    =   implode($depr,$path);
            }else{
                if(C('MULTI_MODULE')) {
                    if(MODULE_NAME != C('DEFAULT_MODULE') || !C('MODULE_ALLOW_LIST')){
                        $var[$varModule]=   MODULE_NAME;
                    }
                }
            }
            if($maps = C('URL_MODULE_MAP')) {
                if($_module = array_search(strtolower($var[$varModule]),$maps)){
                    $var[$varModule] = $_module;
                }
            }
            if(isset($var[$varModule])){
                $module =   $var[$varModule];
                unset($var[$varModule]);
            }
            
        }
    }
	return $this->check_prev(array('m'=>$module,'c'=>$var['c'],'a'=>$var['a'],'data'=>$this->_get_data($vars)));	
	}
	function error($error,$jumpUrl,$waitSecond=3,$type=1)
	{
		require ('Public/tpl/error.htm');
	}
	function success($message,$jumpUrl,$waitSecond=3,$close=1)
	{
		require ('Public/tpl/success.htm');
	}
	function tip($info,$waitSecond=3)
	{
		require ('Public/tpl/tip.htm');
	}	
}