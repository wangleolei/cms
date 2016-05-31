<?php
namespace Content\Controller;
class ContentController extends \Admin\Classes\AdminController{
	private $db;
	public  $categorys;
	public function __construct() {
		parent::__construct();
	}
	public function index() {
		$m_model=M('content_model');
		$condition=array();
		$condition['disabled']=0;
		if(session('userinfo.userid')==1 || session('userinfo.groupid')==1)
		{
			$models=$m_model->where($condition)->select();	
		}
		else
		{
			$models=$m_model->where($condition)->select();						
		}
		require($this->admin_tpl());
		}
	public function public_search()
	{
		$keywpord=trim($_GET['keyword']);
		if($keywpord)
		{
			$condition=array('status'=>1,'name'=>array('like','%'.$keywpord.'%'));
			if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
			{
				$views=M('content_category_priv')->cache(true,7200)->where(array('grpid'=>session('userinfo.groupid')))->getField('view');
				$condition['id']=array('in',$views);
			}
			$categorys=M('content_category')->where($condition)->field('name,id,modelids')->select();
			require($this->admin_tpl());			
		}
	}		
	public function lists(){
		if(!$_GET['modelid'])
		{
			$this->error('请选择栏目的模型！');
			exit;
		}
		$m_model=M('content_model');
		$model_info=$m_model->where(array('modelid'=>$_GET['modelid'],'disabled'=>0))->find();
		$tablename='content_'.$model_info['tablename'];
		$m_tb=M($tablename);
		$d_category=D('Content/category');
			$condition=array();
			if($this->check_constraint(1))
			{
				$condition['author']=session('userinfo.username');
			}
			if($_GET['pow_del']==1)//回收站
			{
				$condition['delete']=1;
			}
			elseif($_GET['pow_del']==2)//审核列表
			{
				$condition['delete']=0;
				$condition['status']=89;
			}
			else
			{
				$condition['delete']=0;
				$condition['status']=99;
			}
			//时间范围筛选
			if($_GET['end_time'] && $_GET['begin_time'])
			{
				$condition['inputtime']=array(array('lt',strtotime($_GET['end_time'])+3600*24),array('gt',strtotime($_GET['begin_time'])),'and');
			}
			else
			{
				/*发布开始时间段*/
				if($_GET['begin_time'])
				{
					$condition['inputtime']=array('gt',strtotime($_GET['begin_time']));
				}
				/*发布时间结束时间段*/
				if($_GET['end_time'])
				{
					$condition['inputtime']=array('lt',strtotime($_GET['end_time'])+3600*24);
				}				
			}
			//筛选栏目
			$m_auth_group=M('content_category_priv');
			$views=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('view');			
			$catid=intval($_GET['catid']);
			if($catid&&$catid!='all')
			{
				//可见权限
				if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
				{
					$views_arr=array_filter(explode(',',$views));
					$allow_array=array_intersect($views_arr,$d_category->catid_arr($catid));
					$condition['catid']=array('in',implode(',',$allow_array));
				}	
				else
				{
					$condition['catid']=array('in',$d_category->catid_str($catid));
				}			
			}
			else
			{
				if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
				{			
					$condition['catid']=array('in',explode(',',$views));					
				}				
			}			
			//搜索
			$keyword=trim($_GET['keyword']);
			if($keyword)
			{
				switch($_GET['type'])
				{
					case 1:$condition['title']=array('like',"%{$keyword}%");break;
					case 2:$condition['id']=intval($keyword);break;
					case 3:$condition['username']=$keyword;break;
				}
			}
			//只能管理自己发布的内容
			if($this->check_constraint(1))
			{
				$condition['username']=session('userinfo.username');
			}			
			$count      = $m_tb->where($condition)->count();// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $m_tb->where($condition)->order('ID DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
			require($this->admin_tpl('Content/list'));
	}	
	public function add() {
		if(IS_POST) {
			define('INDEX_HTML',true);
			$catid = $_POST['info']['catid'] = intval($_POST['info']['catid']);
			if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
			{
				$m_auth_group=M('content_category_priv');
				$adds=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('add');
				if(strpos(','.$adds.',',','.$catid.',')===false)
				{
					$this->error('栏目权限检查失败！');
					exit;
				}				
			}
			if(trim($_POST['info']['title'])=='')
			{
				$this->error('标题不为空！');
				exit;
			}
			$modelid = intval($_GET['modelid']); 
			$this->db=D('Content');
			$this->db->set_model($modelid);
			
			if($_POST['status']!=99) {
				//如果用户是超级管理员，那么则根据自己的设置来发布
				$_POST['info']['status'] = session('userinfo.userid')==1 ? intval($_POST['status']) : 1;
			} else {
				$_POST['info']['status'] = 99;
			}
			$this->db->add_content($_POST['info']);
			if(!empty($_POST['dosubmit_continue']))
			{
				$this->success('添加内容成功！');
				exit;
			}
			else
			{
				$this->success('添加内容成功！',C('CLOSE_WINDOW'));
				exit;
			}		
			} else {
				$modelid = $_GET['modelid'];
				//取模型ID，依模型ID来生成对应的表单
				require MODULE_PATH.'Classes/content_form.class.php';
				$content_form = new \content_form($modelid,$catid,$this->categorys);
				$forminfos = $content_form->get();
				$formValidator = $content_form->formValidator;
				$setting = string2array($category['setting']);
				require $this->admin_tpl('Content/add');
				header("Cache-control: private");
		}
	}
	public function edit() {
			//只能管理自己发布的内容
			$id = intval($_GET['id']);
			$modelid = intval($_GET['modelid']);
			$tablename =M('content_model')->where(array('modelid'=>$modelid))->getField('tablename');
			$tablename='content_'.$tablename;		
			if($this->check_constraint(1))
			{
				$username = M($tablename)->where(array('id'=>$id))->getField('username');
				if($username!=session('userinfo.username'))
				{
					$this->error('权限检测失败！');
					exit;					
				}		
			}
			//设置cookie 在附件添加处调用
			if(isset($_POST['dosubmit']) || isset($_POST['dosubmit_continue'])) {
				define('INDEX_HTML',true);
				$id = $_GET['info']['id'] = intval($_GET['id']);
				if(trim($_POST['info']['title'])=='')
				{
					$this->error('标题不能为空！');
					exit;
				}
				//栏目操作权限
				if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
				{
					$m_auth_group=M('content_category_priv');
					$edits=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('edit');
					if(strpos(','.$edits.',',','.$_POST['info']['catid'].',')===false)
					{
						$this->error('你没有权限编辑该文章！');
						exit;
					}
				}
								
				$modelid = intval($_GET['modelid']);
				$this->db=D('Content');
				$this->db->set_model($modelid);
				$this->db->edit_content($_POST['info'],$id);
				if(!empty($_POST['dosubmit_continue']))
				{
					$this->success('修改内容成功！');
					exit;
				}
				else
				{
					$this->success('修改内容成功！',C('CLOSE_WINDOW'));
					exit;
				}
			} else {				
				$r = M($tablename)->where(array('id'=>$id))->find();
				//栏目操作权限
				if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
				{
					$m_auth_group=M('content_category_priv');
					$edits=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('edit');
					if(strpos(','.$edits.',',','.$r['catid'].',')===false)
					{
						$this->error('你没有权限编辑该文章！');
						exit;
					}					
				}								
				$tablename2 = $tablename.'_data';
				$r2 = M($tablename2)->where(array('id'=>$id))->find();
				if(!$r2)
				{
					$this->error('附表数据丢失！');
					exit;
				}
				$data = array_merge($r,$r2);
				$data = array_map('htmlspecialchars_decode',$data);
				require MODULE_PATH.'Classes/content_form.class.php';
				$content_form = new \content_form($modelid);
				$forminfos = $content_form->get($data);
				$formValidator = $content_form->formValidator;
				include $this->admin_tpl();
				}
	}
	public function delete() {
			//栏目操作权限
			if(session('userinfo.userid')!=1 && session('userinfo.groupid')!=1)
			{
			    $m_auth_group=M('content_category_priv');
			    $deletes=$m_auth_group->where(array('grpid'=>session('userinfo.groupid')))->getField('delete');
				if(strpos(','.$deletes.',',','.$_GET['catid'].',')===false)
				{
					$this->error('你没有权限删除该文章！');
					exit;
				}	
			}				
			//从数据库获取内容
			$id = intval($_GET['id']);
			
			$modelid = intval($_GET['modelid']);
			$table_name=M('content_model')->where(array('modelid'=>$modelid))->getField('tablename');
			$table_name1 = 'content_'.$table_name;
			$m_tb1=M($table_name1);
			$r = $m_tb1->where(array('id'=>$id))->delete();	
			$table_name2 ='content_'.$table_name.'_data';
			$m_tb2=M($table_name2);
			
			$r2 = $m_tb2->where(array('id'=>$id))->delete();
			if($r&&$r2)
			{
				$this->success('删除内容成功！');
			}		
		}
	/**
	 * 排序
	 */
	public 	function listorder()
	{
		$modelid=intval($_GET['modelid']);
		$table_name=M('content_model')->where(array('modelid'=>$modelid))->getField('tablename');
		$table_name = 'content_'.$table_name;	
		$m_tb=M($table_name);	
		foreach($_POST['listorders'] as $k=>$v)
		{
			$m_tb->where(array('id'=>$k))->setField('listorder',$v);
		}
		$this->success('更新排序成功！',U('lists',array('catid'=>$_GET['catid'],'modelid'=>$_GET['modelid'])));      
	}
	/**
	 * 检查标题是否存在
	 */
	public function public_check_title() {
		if($_GET['data']=='' || (!$_GET['catid'])) return '';
		$catid = intval($_GET['catid']);
		$modelid = $this->categorys[$catid]['modelid'];
		$this->db->set_model($modelid);
		$title = $_GET['data'];
		if(CHARSET=='gbk') $title = iconv('utf-8','gbk',$title);
		$r = $this->db->get_one(array('title'=>$title));
		if($r) {
			exit('1');
		} else {
			exit('0');
		}
	}
	public function public_category()
	{
		//筛选栏目
		if((session('userinfo.userid')!=1) && (session('userinfo.groupid')!=1))
		{
			$m_admin_group=M('content_category_priv');	
			$views=$m_admin_group->where(array('grpid'=>session('userinfo.groupid')))->getField('view');
			$cats=D('category')->cats(0,array('id'=>array('in','0,'.$views),'url'=>'','status'=>1));			
		}
		else
		{
			$cats=D('category')->cats(0,array('url'=>'','status'=>1));			
		}
		$tree=new \Org\Util\tree;
		foreach($cats as $r)
		{
			if($r['child']==1) {
				if($r['type']==1) {
					$r['vs_show'] = "<a href='' target='right'>内容页</a>";
				} else {
					$r['vs_show'] ='';
				}
				$r['icon_type'] = 'file';
				$r['add_icon'] = '';
				$r['type'] = 'add';
			} else {
				$r['modelid']=trim($r['modelids'],',');
				$r['icon_type'] = $r['vs_show'] = '';
				$r['type'] = 'init';
				$r['add_icon'] = "<a target='right' href='javascript:void(0)'><img src='/Public/images/add_content.gif' alt='添加'></a> ";
				//onclick=javascript:openwinx('/Content/Content/add/catid/{$r['id']}/modelid/{$r['modelid']}.html')
			}
			$r['modelid']=trim($r['modelids'],',');
			$categorys[$r['id']] = $r;			
		}
		if(!empty($categorys)) {
			$tree->init($categorys);
				switch($from) {
					case 'block':
						$strs = "<span class='\$icon_type'>\$add_icon<a href='' target='right'>\$catname</a> \$vs_show</span>";
						$strs2 = "<img src='/Public/images/folder.gif'> <a href='' target='right'>\$catname</a>";
					break;
					default:
						$strs = "<span class='\$icon_type'>\$add_icon<a href='/Content/Content/lists/catid/\$id/modelid/\$modelid.html' target='right' onclick='open_list(this)'>\$name</a></span>";
						$strs2 = "<span class='folder'>\$name</span>";
						break;
				}
			$categorys = $tree->get_treeview(0,'category_tree',$strs,$strs2,$ajax_show);
		} else {
			$categorys = '请添加栏目';
		}	
		include $this->admin_tpl();
	}	
}