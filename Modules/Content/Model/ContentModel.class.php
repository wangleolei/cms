<?php
/**
 * 内容模型数据库操作类
 */
namespace Content\Model;
class ContentModel{
	public $table_name = '';
	public $category = '';
	public function set_model($modelid) {
		$this->modelid = $modelid;
		$tablename=M('content_model')->where(array('modelid'=>$this->modelid))->getField('tablename');
		$this->tablename='content_'.$tablename;
		$this->model_tablename = 'content_'.$tablename;
	}
	/**
	 * 添加内容
	 * 
	 * @param $datas
	 * @param $isimport 是否为外部接口导入
	 */
	public function add_content($data,$isimport = 0) {
		if($isimport) $data = new_addslashes($data);
		//$this->search_db = pc_base::load_model('search_model');
		$modelid = $this->modelid;
		require_once MODULE_PATH.'Classes/content_input.class.php';
		$content_input = new \content_input($this->modelid);
		
		$inputinfo = $content_input->get($data,$isimport);
		
		$systeminfo = $inputinfo['system'];
		$modelinfo = $inputinfo['model'];

		if($data['inputtime'] && !is_numeric($data['inputtime'])) {
			$systeminfo['inputtime'] = strtotime($data['inputtime']);
		} elseif(!$data['inputtime']) {
			$systeminfo['inputtime'] = time();
		} else {
			$systeminfo['inputtime'] = $data['inputtime'];
		}

		$setting = string2array($this->fields['inputtime']['setting']);
		extract($setting);
		if($fieldtype=='date') {
			$systeminfo['inputtime'] = date('Y-m-d');
		}elseif($fieldtype=='datetime'){
 			$systeminfo['inputtime'] = date('Y-m-d H:i:s');
		}

		if($data['updatetime'] && !is_numeric($data['updatetime'])) {
			$systeminfo['updatetime'] = strtotime($data['updatetime']);
		} elseif(!$data['updatetime']) {
			$systeminfo['updatetime'] =  time();
		} else {
			$systeminfo['updatetime'] = $data['updatetime'];
		}
		$systeminfo['username'] = $data['username'] ? $data['username'] : session('userinfo.username');
		$systeminfo['sysadd'] = defined('IN_ADMIN') ? 1 : 0;
		
		//自动提取摘要
		if(isset($_POST['add_introduce']) && $systeminfo['description'] == '' && isset($modelinfo['content'])) {
			$content = stripslashes($modelinfo['content']);
			$introcude_length = intval($_POST['introcude_length']);
			$systeminfo['description'] = str_cut(str_replace(array("'","\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;','&nbsp;'), '', strip_tags($content)),$introcude_length);
			$inputinfo['system']['description'] = $systeminfo['description'] = addslashes($systeminfo['description']);
		}
		$systeminfo['description'] = str_replace(array('/','\\','#','.',"'"),' ',$systeminfo['description']);
		$systeminfo['keywords'] = str_replace(array('/','\\','#','.',"'"),' ',$systeminfo['keywords']);
		
		//主表
		$tablename = $this->table_name = $this->model_tablename;
		$id = $modelinfo['id'] =M($tablename)->data($systeminfo)->add();
		
		//附属表
		$this->table_name = $this->table_name.'_data';
		M($this->table_name)->data($modelinfo)->add();
		return $id;
	}
	/**
	 * 修改内容
	 * 
	 * @param $datas
	 */
	public function edit_content($data,$id) {
		$model_tablename = $this->model_tablename;
		
		require_once MODULE_PATH.'Classes/content_input.class.php';
		$content_input = new \content_input($this->modelid);
		$inputinfo = $content_input->get($data);
		$systeminfo = $inputinfo['system'];
		$modelinfo = $inputinfo['model'];
		
		if($data['updatetime'] && !is_numeric($data['updatetime'])) {
			$systeminfo['updatetime'] = strtotime($data['updatetime']);
		} elseif(!$data['updatetime']) {
			$systeminfo['updatetime'] =  time();
		} else {
			$systeminfo['updatetime'] = $data['updatetime'];
		}
		//自动提取摘要
		if(isset($_POST['add_introduce']) && $systeminfo['description'] == '' && isset($modelinfo['content'])) {
			$content = stripslashes($modelinfo['content']);
			$introcude_length = intval($_POST['introcude_length']);
			$systeminfo['description'] = str_cut(str_replace(array("\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;','&nbsp;'), '', strip_tags($content)),$introcude_length);
			$inputinfo['system']['description'] = $systeminfo['description'] = addslashes($systeminfo['description']);
		}
		//自动提取缩略图
		if(isset($_POST['auto_thumb']) && $systeminfo['thumb'] == '' && isset($modelinfo['content'])) {
			$content = $content ? $content : stripslashes($modelinfo['content']);
			$auto_thumb_no = intval($_POST['auto_thumb_no'])-1;
			if(preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches)) {
				$systeminfo['thumb'] = $matches[3][$auto_thumb_no];
			}
		}
		$systeminfo['description'] = str_replace(array('/','\\','#','.',"'"),' ',$systeminfo['description']);
		$systeminfo['keywords'] = str_replace(array('/','\\','#','.',"'"),' ',$systeminfo['keywords']);
		//主表
		M($this->tablename)->where(array('id'=>$id))->data($systeminfo)->save();
		//附属表
		M($this->tablename.'_data')->where(array('id'=>$id))->data($modelinfo)->save();
		return true;
	}
	/**
	 * 删除内容
	 * @param $id 内容id
	 * @param $file 文件路径
	 * @param $catid 栏目id
	 */
	public function delete_content($id,$file,$catid = 0) {
		//删除主表数据
		$this->delete(array('id'=>$id));
		//删除从表数据
		$this->table_name = $this->table_name.'_data';
		$this->delete(array('id'=>$id));
		//重置默认表
		$this->table_name = $this->db_tablepre.$this->model_tablename;
	}
	/**
	 * 获取内容列表
	 * @param $replace 模型id/以数组方式传递的参数集合
	 * @param $catid 从指定栏目获取内容
	 * @param $posid 推荐位
	 * @param $num  数量/每页数量
	 * @param $isimage  是否有缩略图
	 * @param $ispage  是否分页
	 * @param $order  排序方式
	 * @param $addfields  附加字段
	 * @param $pangenum 每页数量
	 */	
	public function getContentList($replace,$catid,$posid,$num,$isimage,$ispage,$order,$addfields,$condition)
	{
		//初始化传递过来的参数
		is_array($replace)?extract($replace):($modelid=$replace);
		$num=($num)?$num:10;
		$order=($order)?$order:'updatetime DESC';
		$ispage=($ispage)?true:false;
		$condition=($condition)?$condition:array();
			
		$tablename=M('content_model')->where('modelid='.$modelid)->getField('tablename');
		$tb1='content_'.$tablename;
		$tb2='content_'.$tablename.'_data';
		$m_tb=M($tb1. ' as a');
		$condition['a.status']=99;
		if($modelid==1 && !$condition['a.disdel'])$condition['a.disdel']=0;
		//限制栏目
		if($catid)
		{
			$map=array();
			$m_cat=M('content_category');
			$path=$m_cat->where(array('id'=>$catid))->getField('path');
			$map['b.path']=array('like',$path.$catid.'-%');
			$map['a.catid']=$catid;
			$map['_logic'] = 'or';
			$condition['_complex'] = $map;
		}
		//限制推荐位
		if($posid)
		{
			if(strpos($posid,'n')!==false)
			{
				$posid=substr($posid,1);
				$condition['posids'] = array('notlike','%,'.$posid.',%');	
			}
			else
			{
				$condition['posids'] = array('like','%,'.$posid.',%');
			}
		}
		if($isimage)
		{
			$condition['thumb'] = array('neq','');
		}
		if($ispage)
		{
			$count=$m_tb->where($condition)->join('LEFT JOIN __CONTENT_CATEGORY__ as b on a.catid=b.id')->field('a.*,b.name as catname')->count();
			$Page       = new \Think\Page($count,$num);
			$show       = $Page->show();
			$list = $m_tb->where($condition)->join('LEFT JOIN __CONTENT_CATEGORY__ as b on a.catid=b.id')->limit($Page->firstRow.','.$Page->listRows)->order($order);
			if($addfields)
			{
				$m_tb->field('a.*,b.name as catname,'.$addfields)->join("left join fc_{$tb2} as c on c.id=a.id");	
			}
			else
			{
				$m_tb->field('a.*,b.name as catname');
			}
			$list = $m_tb->select();			
			$value=array();
			//分页信息
			$value['page']=$show;
			$value['list']=$list;
			//上一页、下一页
			$p=$_GET['p']?$_GET['p']:1;
			$value['next']=($p*$num<$count)?($p+1):'';
			$value['prev']=($p==1)?'':$p-1;
		}
		else
		{
			$m_tb->where($condition)->join('LEFT JOIN __CONTENT_CATEGORY__ as b on a.catid=b.id')->order($order)->limit($num);
			if($addfields)
			{
				$m_tb->field('a.*,b.name as catname,'.$addfields)->join("left join fc_{$tb2} as c on c.id=a.id");	
			}
			else
			{
				$m_tb->field('a.*,b.name as catname');
			}	
			$value=$m_tb->select();	
		}
		return $value;
	}
	/**
	 * 获取内容详细信息
	 * @param $modelid 模型id
	 * @param $id 内容id
	 * @param $np 上一篇 下一篇
	 */		
	public function getDetail($modelid,$id,$catid,$np)
	{
		$tablename=M('content_model')->where('modelid='.$modelid)->getField('tablename');
		$tb1='content_'.$tablename;
		$m_tb1=M($tb1);
		$condition=array();
		$condition['status']=99;
		$condition['id']=$id;
		$info=$m_tb1->where($condition)->find();
		if(!$info)return false;	
		//获取附表信息
		$tb2='content_'.$tablename.'_data';
		$m_tb2=M($tb2);
		$condition2=array();
		$condition2['status']=99;
		$condition2['id']=$id;
		$info2=$m_tb2->where($condition2)->find();
		if($np)
		{
			//上一篇
			$where=array();
			$where['status']=99;
			$where['catid']=$catid;
			$where['id']=array('lt',$id);
			$info['prve']=$m_tb1->where($where)->field('title,id')->find();
			//下一篇
			$where['id']=array('gt',$id);
			$info['next']=$m_tb1->where($where)->field('title,id')->find();				
		}	
		//获取栏目信息
		$info['catname']=D('Content/Category')->getName($info['catid']);
		return array_merge($info,$info2);
	}
}
?>