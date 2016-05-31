<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃后台用户公用』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class IndexController extends \Admin\Classes\AdminController{
	/*
	* 用户登陆
	*/
    public function login(){

		//判断是否已经登陆，如果已经登陆则跳转到后台首页
		if(session('userinfo.userid')!=NULL&&session('userinfo.username')!=NULL)
		{
			$this->redirect('index');	
		}
		//是否提交登陆信息，如何提交进行验证，否则显示登陆页面
        if(IS_POST)
        {
			//验证验证码是否正确
                        if(empty($_POST['code']))
                        {
                            $this->error("验证码不能为空！");
							exit;	 
                        }
			$verify = new \Think\Verify();
                        if(!$verify->check($_POST['code']))
			{
				$this->error("验证码有误！");
				exit;	
			}
                        //验证用户名是否正确
                       if(empty($_POST['username']))
                        {
                            $this->error("用户名不能为空！");
							exit;	 
                        }
                        //验证密码
                       if(empty($_POST['password']))
                        {
                            $this->error("密码不能为空！");
							exit;	 
                        }
                     
            $m_admin=M('admin as a');
			$arr_where1=array(
				'a.username'=>$_POST['username'],
				'a.status'=>1
			);
			$val1=$m_admin->where($arr_where1)
						  ->join('LEFT JOIN __AUTH_GROUP_ACCESS__ ON __AUTH_GROUP_ACCESS__.uid=a.userid')
				  		  ->join(' LEFT JOIN __AUTH_GROUP__ ON __AUTH_GROUP__.id=__AUTH_GROUP_ACCESS__.group_id ')
						  ->field('encrypt,password,userid,group_id,title,constraint,usertype')
						  ->find();
						  
			if(empty($val1))
			{
				$this->error("用户名或密码错误！");
			}
            $user_password=password($_POST['password'], $val1['encrypt']);
            if($user_password==$val1['password'])
			{
			//获取上次登陆的ip相关信息	
			$taobaoIp=new \Org\Net\taobaoIp;
			$ip_info=$taobaoIp->getLocation(get_client_ip());
			$area=$ip_info['isp'].' '.$ip_info['country'].'-'.$ip_info['area'].'-'.$ip_info['region'].'-'.$ip_info['city']; 
			//更新登陆的ip和登陆时间
			$m_admin_longin=M('admin_login');
			$m_admin_longin->data(array('uid'=>$val1['userid'],'time'=>time(),'ip'=>get_client_ip(),'area'=>$area))->add();
			//保存登陆信息
			session('userinfo',array('username'=>$_POST['username'],'userid'=>$val1['userid'],'groupid'=>$val1['group_id'],'usertitle'=>$val1['title'],'constraint'=>$val1['constraint'],'usertype'=>$val1['usertype']));
			$this->success('登陆成功！','index');  
			}
			else
			{
				$this->error("用户名或密码错误！");	
			}
        }
        else
        {
			include($this->admin_tpl('Index/'.C('login_theme')));		
        }
    }
	/*
	*注销登陆
	*/
    public function logout()
    {
       //清空登陆信息
	   $this->_logout();
	   $this->success('退出登陆成功',U('login'));
    }
	/*
	* 后台主界面
	*/
	public function index()
	{
		//获取顶部菜单的数组
		$m_menu=M('admin_menu');
			//获取一级菜单
			$top_menu=parent::admin_menu(0);
			$array_1=array();
			if(session('userinfo.userid')!=1 or session('userinfo.groupid')!=1)
			{
				foreach($top_menu as $info)
				{
					$name=$info['m'].'/'.$info['c'].'/'.$info['a'];
					if($this->auth->check($name,session('userinfo.userid'))||(MODULE_NAME=="Admin"&&CONTROLLER_NAME=="Index"&&in_array(ACTION_NAME,array('login','index','menuLeft','firstPage'))))
					{
						$array_1[]=$info;
					}
				}
			}
			else
			{
				$array_1=$top_menu;
			}
		$top_menu=$array_1;
		//获取panel
		$m_panel=M('admin_panel');
		$panel_list=$m_panel->where(array('userid'=>session('userinfo.userid')))
				->join('LEFT JOIN __ADMIN_MENU__ ON __ADMIN_MENU__.id =__ADMIN_PANEL__.menuid')
				->limit(10)
				->select();			
		include($this->admin_tpl('Index/index'));	
	}
	/*
	*登陆进去显示的页面
	*/
	public function firstPage(){
		/*获取上一次的登陆信息*/
		$m_admin_login=M('admin_login');
		$last_login_info=$m_admin_login->where('uid='.session('userinfo.userid'))->order('id desc')->limit('1,1')->find();
		$ishead=M('admin')->where(array('adminid'=>session('userinfo.userid')))->getField('ishead');
		include($this->admin_tpl('Index/firstPage'));	
	}
	/*
	*后台左侧菜单
	*/
	public function menuLeft(){
		$menuid = intval($_GET['menuid']);
		//如果$menuid为空则默认menuid=10
		if(empty($menuid))
		{
			$top_menu=parent::admin_menu(0);
			$first=array_shift($top_menu);
			$menuid=$first['id'];
		}		
		$datas=parent::admin_menu($menuid); 
		include($this->admin_tpl('Index/leftMenu'));	
	}
	/*
	*增加快捷标签
	*/
	public function ajax_add_panel()
	{
		$m_menu=M('admin_menu');
		$menuid=intval($_POST['menuid']);
		$info=$m_menu->where(array('id'=>$menuid))->find();
		if(!empty($info)&&count(explode('-',$info['path']))>=3)
		{
			$m_panel=M('admin_panel');
			$panel=$m_panel->where(array('menuid'=>$menuid,'userid'=>session('userinfo.userid')))->count();
			$all_num=$m_panel->where(array('userid'=>session('userinfo.userid')))->count();
			if($panel==0 && $all_num<=10)
			{
				$m_panel->data(array('menuid'=>$menuid,'userid'=>session('userinfo.userid')))->add();
				echo '<span id="panel_'.$info['id'].'"><a onclick="paneladdclass(this);" target="right" href="'.U($info['m'].'/'.$info['c'].'/'.$info['a'].'/'.$info['data']).'">'.$info['name'].'</a>  <a class="panel-delete" href="javascript:delete_panel('.$info['id'].');"></a></span>';
			}
		}
	}
	public function ajax_delete_panel()
	{
		$m_panel=M('admin_panel');
		$menuid=intval($_POST['menuid']);
		$panel=$m_panel->where(array('menuid'=>$menuid,'userid'=>session('userinfo.userid')))->delete();
	}
	/*
	*锁屏
	*/	
	public function ajax_lock_screen()
	{
		session("lock",'locked');
	}
	/*
	*锁屏登陆
	*/	
	public function login_screenlock()
	{
		$password = trim($_GET['lock_password']);
		$m_admin=M('admin');
		$arr=$m_admin->where(array('userid'=>session('userinfo.userid')))->field("password,encrypt")->find();
		
				if(8-session('lock_login_num')<=0)
				{
					echo 3;
					exit;
				}
                if(password($password, $arr['encrypt'])==$arr['password'])
                {
				   session('lock_login_num',NULL);
				   session('lock',NULL);
                   echo '1';
                }
				else
				{					
					session('lock_login_num',session('lock_login_num')+1);
					$num=8-session('lock_login_num');
					if($num>=0)
					{
						echo $num.'|0';
					}	
				}
	}
	/*
	* 修改管理员的登录信息
	*/
	public function relogininfo()
	{
		if(IS_POST)
		{
			$d=M('admin');
			
			//第一步验证输入的当前密码是否正确
			$info=$d->where(array('userid'=>session('userinfo.userid')))->field('password,encrypt')->find();
			if(password($_POST['passwordold'],$info['encrypt'])!=$info['password'])
			{
				$this->error('你输入的当前密码错误!');
				exit;
			}
			
			$data=$_POST['info'];			
			//获取加密后的密码字符串
			if(!empty($_POST['password']))
			{
				$pw=password($_POST['password']);
				$data['password']=$pw['password'];
				$data['encrypt']=$pw['encrypt'];
			}
			//修改用户信息
			$bool=$d->where(array('userid'=>session('userinfo.userid')))->filter('new_html_special_chars')->data($data)->save();
			if($bool!==false)
			{
				$this->success('修改登录信息成功！');
			}
			else
			{
				$this->success('修改登录信息失败！');
			}				
		}
		else
		{
			$m2=M('admin');
			$info=$m2->where(array('userid'=>session('userinfo.userid')))->find();	
			include($this->admin_tpl('Index/relogininfo'));
		}	
	}
	/*
	*当前位置
	*/
	public function current_pos()
	{
		$menuid=intval($_GET['menuid']);
		$this->_current_pos($menuid,$str='');
	}
	/*
	*计算当前位置
	*/
	private function _current_pos($menuid,$str)
	{
		$m_menu=M('admin_menu');
		$arr=$m_menu->where(array('id'=>$menuid))->field('name,parentid')->find();
		if($arr['parentid']!=0)
		{
			$str=$arr['name'].'>'.$str;
			$this->_current_pos($arr['parentid'],$str);
		}
		else{
			 $str=$arr['name'].'>'.$str;
			 echo  $str;
			}		
	}
	/*
	*后台地图
	*/
	public function map()
	{
		$tree=new \Org\Util\tree;
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		
        $result =  M('admin_menu as a')->order('listorder DESC,id DESC')->select();
		$array = array();
		foreach($result as $r) {	
			if(session('userinfo.userid')!=1&&session('userinfo.groupid')!=1)
			{
				if(!$this->check_prev($r))continue;	
			}
			$r['cname'] = $r['name'];
			$array[] = $r;
		}
		$str  = "<tr>
					<td align='center'>\$id</td>
					<td >\$spacer\$cname</td>
					
				</tr>";
		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		//显示副菜单		
		include($this->admin_tpl('Index/menuMap'));
	}
	function recache()
	{
		$dir='./Runtime/Temp';
		$dh=opendir($dir);
		  while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
			  $fullpath=$dir."/".$file;
			  if(!is_dir($fullpath)) {
				  unlink($fullpath);
			  } else {
				  deldir($fullpath);
			  }
			}
		  }
		$dir='./Runtime/Cache';
		$dh=opendir($dir);
		  while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
			  $fullpath=$dir."/".$file;
			  if(!is_dir($fullpath)) {
				  unlink($fullpath);
			  } else {
				  deldir($fullpath);
			  }
			}
		  }		  	  
		  $this->success('清除缓存成功！');
	}
}