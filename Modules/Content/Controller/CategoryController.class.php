<?php
/*
* 文章栏目管理
*/
namespace Content\Controller;
class CategoryController extends \Admin\Classes\AdminController{
	private $d_cat,$tmp_arr;
	private $category_type=array(
				'seo'=>'SEO'
				);
	private $v_cats=array();
	function __construct(){
		parent::__construct();
		$this->d_cat=D('Category');
		}
	/*
	* 栏目列表
	*/	
	function lists(){
		$num=$this->d_cat->count();
		if($num>100)
		{
			$condition=array();
			if(!empty($_GET['parentid']))
			{
				$condition['parentid']=$_GET['parentid'];
			}
			else
			{
				$condition['parentid']=0;	
			}	
			$count      = $this->d_cat->where($condition)->count();
			$Page       = new \Think\Page($count,25);
			$show       = $Page->show();
			$list = $this->d_cat->where($condition)->order('listorder DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
			include($this->admin_tpl('Category/lists'));			
		}
		else
		{
			$tree=new \Org\Util\tree;
			$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
			$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
			
			$result =  $this->d_cat->order('listorder DESC,id DESC')->select();	
			$array = array();
			foreach($result as $r) {
				$r['cname'] = $r['name'];
				$r['str_manage'] = '<a href="javascript:void(0)"  onClick="addchild(\''.$r['id'].'\',\'添加子栏目\')">添加子栏目</a> | <a href="javascript:void(0)" onClick="edit('.$r['id'].',\'修改当前栏目\')">修改</a> | <a href="'.U('Content/Category/delete/id/'.$r['id']).'" onClick="return myconfirm(\'确认删除该栏目及其子栏目？\');">删除</a> ';
				$r['status']=$r['status']?'√':'×';
				$r['display']=$r['display']?'√':'×';
				$str_l='';
				foreach($this->category_type as $ko=>$vo)
				{
					if(preg_match("/{$ko},/",$r['type']))
					{
						$str_l.="<a href='javascript:{$vo}({$r['id']})'>{$vo}</a> ";
					}
				}
				$r['str_l']=$str_l;				
				$array[] = $r;			
			}
			$str  = "<tr>
						<td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
						<td align='center'>\$id</td>
						<td >\$spacer\$cname</td>
						<td align='center';><span class='red'>\$status</span></td>
						<td align='center';><span class='red'>\$display</span></td>
						<td align='center'>\$str_l</td>
						<td align='center'>\$str_manage</td>
					</tr>";
			$tree->init($array);
			$categorys = $tree->get_tree(0, $str);
			//显示副菜单			
			include($this->admin_tpl('Category/lists2'));
		}
	}
	/*
	* 添加顶级栏目
	*/	
	function add(){
		if(IS_POST)
		{
			$pid=intval($_POST['info']['parentid']);
			$info=$_POST['info'];
			$info['type']=implode(',',$info['type']).',';
			if($pid)
			{
				$pinfo=$this->d_cat->where('id='.$pid)->field('path,parentid')->find();
				$info['path']=$pinfo['path'].$pid.'-';
			}
			else
			{
				$info['path']='-0-';
			}
			$val2=$this->d_cat->where('id='.$pid)->setField('child',1);
			$val=$this->d_cat->data($info)->filter('new_html_special_chars')->add();
			if($val)
			{
				$this->success('添加栏目成功！');
			}
		}
		else
		{
			$result = $this->d_cat->order('listorder DESC,id DESC')->field('name,parentid,id')->select();
			$tree=new \Org\Util\tree;
			$array = array();
			foreach($result as $r) {
				$r['cname'] = $r['name'];
				$r['selected'] = $r['id'] == $_GET['id'] ? 'selected' : '';
				$array[] = $r;
			}
			$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
			$tree->init($array);
			$select_categorys = $tree->get_tree(0, $str);
						
			$models=D('Sitemodel')->getModels();
			include($this->admin_tpl('Category/add'));
		}
	}
	/*
	* 修改栏目
	*/
	function edit(){
		$id=intval($_GET['id']);
		if(IS_POST)
		{
			$info=$_POST['info'];
			
			$old_pid=$this->d_cat->where(array('id'=>$id))->getField('parentid');//获取栏目原来的上级栏目id
			
			$num=$this->d_cat->where(array('parentid'=>$parentid))->count();
			if($num<=1&&$old_pid!=$info['parentid'])
			{
				$this->d_cat->where(array('id'=>$old_pid))->setField('child',0);
			}                   
			$info['type']=implode(',',$info['type']).',';
			if($info['parentid'])
			{
				$p_info=$this->d_cat->where(array('id'=>$info['parentid']))->find();//获取父栏目信息
				$info['path']=$p_info['path'].$p_info['id'].'-';				
			}
			else
			{
				$info['path']='-0-';
			}
			$info['modelids']=','.implode(',',$info['modelids']).',';
			$val=$this->d_cat->where(array('id'=>$id))->data($info)->filter('new_html_special_chars')->save();
            $this->d_cat->where(array('id'=>$info['parentid']))->setField('child',1);
			if($val!==false)
			{
				$this->success('修改当前栏目成功！');
			}	
			else
			{
				$this->error('修改当前栏目失败！');
			}		
		}
		else
		{
			$info=$this->d_cat->where(array("id"=>$_GET['id']))->find();
			$tree=new \Org\Util\tree;
			$result = $this->d_cat->order('listorder DESC,id DESC')->where(array('path'=>array('notlike','%-'.$info['path'].'-'.$info['id'].'-%'),'id'=>array('neq',$info['id'])))->field('name,parentid,id')->select();
			$array = array();
			foreach($result as $r) {
				$r['cname'] = $r['name'];
				$r['selected'] = $r['id'] == $info['parentid'] ? 'selected' : '';
				$array[] = $r;
			}
			$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
			$tree->init($array);
			$select_categorys = $tree->get_tree(0, $str);
			//模型列表
			$models=D('Sitemodel')->getModels();
			include($this->admin_tpl('Category/edit'));
		}
	}
	/*
	* 删除一个栏目并且递归删除其子栏目
	*/
	function delete(){
			$this->d_cat->startTrans();
			//获取要删除的栏目及其子栏目
			$cats_str=$this->d_cat->catid_str($_GET['id']);
			//删除栏目
			$bool1=$this->d_cat->where(array('id'=>array('in',$cats_str)))->delete();
			//删除栏目seo属性
			$bool2=M('content_category_seo')->where(array('catid'=>array('in',$cats_str)))->delete();
			if($bool1!==false&&$bool2!==false)
			{
				$this->d_cat->commit();
				$this->success('删除栏目成功！');
			}
			else
			{
				$this->d_cat->rollback();
			}
		}
	/*
	* 栏目排序
	*/
	function listorder(){
		foreach($_POST['listorders'] as $k=>$v)
		{
			$this->d_cat->where(array('id'=>$k))->setField('listorder',$v);
		}
		$this->success('更新排序成功！');
	}
	/*
	* 修复PATH
	*/
	function repair(){
		$cats=$this->d_cat->select();
		foreach($cats as $k=>$v)
		{
			unset($cats[$k]);
			if($v['parentid']!=0)
			{
				$this->tmp_arr=array();
				$this->_creatPath($v['parentid']);
				krsort($this->tmp_arr);
				$path=implode('-',$this->tmp_arr);
				$path='-'.$path.'-';
				$this->d_cat->where(array('id'=>$v['id']))->data(array('path'=>$path))->save();
			}
			else
			{
				$path='-0-';
				$this->d_cat->where(array('id'=>$v['id']))->data(array('path'=>$path))->save();
			}
		    $num=$this->d_cat->where(array('path'=>$path.'-'.$v['id']))->count();
			if($num>=1)
			{
				$this->d_cat->where(array('id'=>$v['id']))->setField('child',1);
			}
			else
			{
				$this->d_cat->where(array('id'=>$v['id']))->setField('child',0);
			}			
		}
        $this->success('修复栏目结构成功！');
	}
	function _creatPath($pid){
		$this->tmp_arr[]=$pid;
		$parid=$this->d_cat->where(array('id'=>$pid))->getField('parentid');
		if($parid!=0)
		{
			$this->_creatPath($parid);
		}
		else
		{
			$this->tmp_arr[]=0;
		}
	}
	
	/*
	* 栏目SEO属性
	*/
	function seo(){
		$m_seo=M('content_category_seo');	
		if(IS_POST)
		{
			$_POST['info']['catid']=$_GET['id'];
			if($m_seo->where(array('catid'=>$_GET['id']))->count())
			{
				$val=$m_seo->where(array('catid'=>$_GET['id']))->filter('new_html_special_chars')->data($_POST['info'])->save();
			}
			else
			{
				$val=$m_seo->filter('new_html_special_chars')->data($_POST['info'])->add();
			}
			if($val!==false)
			{
				$this->success('编辑栏目SEO成功！');
			}
		}
		else
		{
			$info=$m_seo->where(array('catid'=>$_GET['id']))->find();
			require($this->admin_tpl('Category/seo'));	
		}
	}
}