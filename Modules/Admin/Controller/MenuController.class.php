<?php
/*
		╭-╮╭-╮
		┃ ┃┃ ┃后台菜单』
		┃ ゛＂゛ ┃ │ ┌│││┌┘
		┃ ┃ ┃　┃ │ ││││┌┘
		ミ~~ · ~~ミ ─┘─┘─┘─┘
		╰━┳━┳━╯
		╭┫ ┣╮
┺┻┺┻┺┻┻┻┹聯係我图：thinkerphp@thinkerphp.com╯
*/
namespace Admin\Controller;
class MenuController extends \Admin\Classes\AdminController{
    protected $m_menu,$tmp_arr;
    public function _initialize() {
        $this->m_menu=M('admin_menu');
    }
	public function index()
	{
		$this->menuList();
	}
	/*
	*菜单列表
	*/
    public function menuList()
	{
		$tree=new \Org\Util\tree;
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		
        $result =  $this->m_menu->order('listorder DESC,id DESC')->select();	
		$array = array();
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['str_manage'] = '<a href="'.U('Menu/add?parentid='.$r['id']).'">添加子菜单</a> | <a href="'.U('Menu/edit?parentid='.$r['parentid'].'&menuid='.$r['id']).'">修改</a> | <a href="'.U('Menu/delete?menuid='.$r['id']).'" onClick="return myconfirm(\'确认删除该菜单及其子菜单？\');">删除</a> ';
			$array[] = $r;
		}
		$str  = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
					<td align='center'>\$id</td>
					<td >\$spacer\$cname</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		//显示副菜单
		include($this->admin_tpl('Menu/menuList'));
	}
	//删除菜单
	public function delete()
	{
		$menuid=intval($_GET['menuid']);
		$this->m_menu->startTrans();
		$bool=$this->_delete($menuid);
		if($bool!==false)
		{
			$this->m_menu->commit();
			$this->success('删除菜单及其下级菜单成功！',U('menuList'));
		}
		else
		{
			$this->m_menu->rollback();
			$this->error('删除菜单及其下级菜单失败！',U('menuList'));
		}
	}
	//迭代删除子菜单菜单以及权限表
	private function _delete($menuid)
	{
		//删除权限表中的内容
		$authid=$this->m_menu->where(array('id'=>$menuid))->getField('authid');
		$m=M('auth_rule');
		$bool1=$m->where(array('id'=>$authid))->delete();
		//删除菜单表中的内容
		$bool2=$this->m_menu->where(array('id'=>$menuid))
					->delete();	
		if(!$bool1 || !$bool2)return false;		
		$childs=$this->m_menu->where(array('parentid'=>$menuid))->select();
		if($childs)
		{
			foreach($childs as $v)
			{
				$this->_delete($v['id']);
			}
		}
	}
	/*
	*添加菜单
	*/
	public function add()
	{
		if(isset($_POST['dosubmit']))
		{
			$this->m_menu->startTrans();//开启事务
			$info=$_POST['info'];
			if($info['data'])
			{
				$arr=explode('/',$info['data']);
				$length=count($arr);
				$newarr=array();
				for($i=0;$i<$length;$i=$i+2)
				{
					if(strpos($arr[$i],'pow_')!==false)
					{
						$newarr[$arr[$i]]=$arr[$i+1];
					}
				}
				$data=$this->_get_data($newarr);
			}
			
			$arr_auth=array('name'=>$info['m'].'/'.$info['c'].'/'.$info['a'].'/'.$data,'title'=>$info['name'],'type'=>1,'status'=>1);
			$m_auth=M('auth_rule');
			$is_exist=$m_auth->where(array('name'=>$info['m'].'/'.$info['c'].'/'.$info['a'].'/'.$data))->getField('id');
			if($is_exist)
			{
				$this->error('权限位已经存在！');
				exit;
			}
			$authid=$m_auth->filter('new_html_special_chars')->data($arr_auth)->add();
			
			//添加菜单信息
			if($info['parentid']!=0)
			{
				$p_path=$this->m_menu->where(array('id'=>$info['parentid']))->getField('path');
				$info['path']=$p_path.$info['parentid'].'-';
			}
			else
			{
				$info['path']='-0-';
			}
			$info['authid']=$authid;//获取菜单的权限组id
			$bool=$this->m_menu->data($info)->add();								
			if($authid and $bool)
			{
				$this->m_menu->commit();//事务确认
				$this->success('添加菜单成功！');
				exit;
			}
			else
			{
				$this->m_menu->rollback();//事务回滚
				$this->error('添加菜单失败！');
				exit;	
			}
			
		}
		else
		{
		$tree=new \Org\Util\tree;
		$result = $this->m_menu->order('listorder DESC,id DESC')->field('name,parentid,id')->select();
		$array = array();
		
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['selected'] = $r['id'] == $_GET['parentid'] ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
		$tree->init($array);
		$select_categorys = $tree->get_tree(0, $str);
		include($this->admin_tpl('Menu/menuAdd'));
		}
	}
	/*
	*编辑菜单
	*/
	public function edit()
	{
		
		if(isset($_POST['dosubmit']))
		{
			$info=$_POST['info'];
			if($info['parentid']==$_GET['menuid']){$this->error('父菜单不能是菜单本身！');exit;}
			$this->m_menu->startTrans();
			//1.获取权限位id
			$authid=$this->m_menu->where(array("id"=>$_GET['menuid']))->getField('authid');				
			//2.新的权限位名称	
			if($info['data'])
			{
				$arr=explode('/',$info['data']);
				$length=count($arr);
				$newarr=array();
				for($i=0;$i<$length;$i=$i+2)
				{
					if(strpos($arr[$i],'pow_')!==false)
					{
						$newarr[$arr[$i]]=$arr[$i+1];
					}
				}
				$data=$this->_get_data($newarr);
			}							
			$name=$info['m'].'/'.$info['c'].'/'.$info['a'].'/'.$data;		
			$m_auth=M('auth_rule');
			if(!$authid)
			{
				$arr_auth=array('name'=>$name,'title'=>$info['name'],'type'=>1,'status'=>1);
				$trans1=$m_auth->data($arr_auth)->add();
			}
			else
			{
				$arr_auth=array('name'=>$name,'title'=>$info['name'],'type'=>1);
				$trans1=$m_auth->where(array('id'=>$authid))->data($arr_auth)->save();
			}
			//修改菜单表
			if(!$authid)
			{
				$info['authid']=$trans1;
			}
			if($info['parentid']!=0)
			{
				$p_path=$this->m_menu->where(array('id'=>$info['parentid']))->getField('path');
				$info['path']=$p_path.$info['parentid'].'-';
			}
			else
			{
				$info['path']='-0-';
			}
			$trans2=$this->m_menu->where(array("id"=>$_GET['menuid']))->data($info)->save();
			if(($trans1!==false) and ($trans2!==false))
			{
				$this->m_menu->commit();
				$this->success("修改菜单成功！");
			}
			else
			{
				$this->m_menu->rollback();
				$this->error("修改菜单失败！");
			}
			exit;
		}
		else
		{
		$info=$this->m_menu->where(array("id"=>$_GET['menuid']))->find();

		$tree=new \Org\Util\tree;
		$result = $this->m_menu->order('listorder DESC,id DESC')->where(array('path'=>array('notlike','%-'.$info['path'].'-'.$info['id'].'-%'),'id'=>array('neq',$info['id'])))->field('name,parentid,id')->select();
		$array = array();
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['selected'] = $r['id'] == $_GET['parentid'] ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
		$tree->init($array);
		$select_categorys = $tree->get_tree(0, $str);
		
					
		include($this->admin_tpl('Menu/menuEdit'));
		}
	}
	/*
	*菜单排序
	*/	
	public function listorder()
	{
		$listorders=$_POST['listorders'];
		foreach($listorders as $id=>$value)
		{
			$this->m_menu->where(array('id'=>$id))->data(array('listorder'=>$value))->save();
		}
		$this->redirect('Menu/menuList');
	}
	/*
	* 修复菜单path结构
	*/
	function repair()
	{
		$cats=$this->m_menu->select();
		foreach($cats as $k=>$v)
		{
			unset($cats[$k]);
			if($v['parentid']!=0)
			{
				$this->tmp_arr=array();
				$this->_creatPath($v['parentid']);
				krsort($this->tmp_arr);
				$this->m_menu->where(array('id'=>$v['id']))->data(array('path'=>'-'.implode('-',$this->tmp_arr).'-'))->save();
			}
			else
			{
				$this->m_menu->where(array('id'=>$v['id']))->data(array('path'=>'-0-'))->save();
			}
		}
        $this->success('修复菜单结构成功！');
	}
	function _creatPath($pid)
	{
		$this->tmp_arr[]=$pid;
		$parid=$this->m_menu->where(array('id'=>$pid))->getField('parentid');
		if($parid!=0)
		{
			$this->_creatPath($parid);
		}
		else
		{
			$this->tmp_arr[]=0;
		}
	}	
}