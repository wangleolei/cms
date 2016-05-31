<?php
namespace Content\Model;
class CategoryModel extends \Admin\Classes\CommonModel {
	 private $v_cats=array();
	 protected $tableName='content_category';
	/**
	 * 获取栏目下所有子栏目
	 * @param $catid 栏目id
	 * @return array
	 */
	function cats($catid,$condition=array())
	{
		if($catid==0)
		{
			$cats=$this->where($condition)->order('listorder DESC')->select();
		}
		else
		{
			$path=$this->where(array('id'=>$catid))->getField('path');
			$condition['path']=array('like',$path.$catid.'-%');
			$cats=$this->where($condition)->order('listorder DESC')->select();
		}
		return $cats;
	}	
	/**
	 * 获取一级栏目下所有子栏目,同cats($catid);
	 * @param $catid 栏目id
	 * @return array
	 */
	function getChildren($catid=0,$condition=array())
	{
		$cond=array('display'=>1,'status'=>1,'parentid'=>$catid);
		$condition=array_merge($cond,$condition);
		return $this->where($condition)->order('listorder DESC')->field('id,name,url')->select();
	}
	function catid_name_arr($catid)
	{
		$cat=$this->where(array('id'=>$catid))->field('path,keyid')->find();	
		$keyid=intval($cat['keyid']);
		$vals=$this->cats($keyid);
		$values=array();
		foreach($vals as $val)
		{
			if(strpos($val['path'],$cat['path'].'-'.$catid)!==false)$values[$val['id']]=$val['name'];
		}
		return $values;		
	}
	/**
	 * 根据栏目id获取栏目名称
	 * @param $keyid 所属顶级栏目id
	 * @param $catid 类别id
	 * @return array
	 */        
      public function getName($catid){
			$cats=$this->cats(0);
			foreach($cats as $val)
			{
				if($val['id']==$catid)
				{
				   $catname=$val['name'];
                   break;
				}
			}
			return $catname;
        }
	/*
	* 返回一个栏目所有的子栏目的,用','号隔开
	*/
	public function catid_str($catid)
	{
		$valus=$this->cats($catid);
		$str='';
		foreach($valus as $k=>$v)
		{
			$str.=$v['id'].',';		
		}
		$str.=$catid;
		return $str;		
	}
	/*
	* 返回一个栏目所有的子栏目的数组
	*/
	public function catid_arr($catid)
	{
		$valus=$this->cats($catid);
		$arr=array();
		foreach($valus as $k=>$v)
		{
			$arr[]=$v['id'];
		}
		$arr[]=$catid;
		return $arr;		
	}	
	/*
	* 创建类别选项卡,当选项比较多的时候会占用较多CPU资源，因此不建议使用
	* $id栏目id
	* $selected_id 被选中的id
	*/
	function category2select($id,$selected_id,$condition)
	{
		//if($val=$this->getCache()) return $val;
		//ini_set("max_execution_time",120);
		//ignore_user_abort(true);
		$result=$this->cats($id,$condition);
		$tree=new \Org\Util\tree;
		$array = array();
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['selected'] = $r['id'] == $selected_id ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
		$tree->init($array);
		$select_categorys = $tree->get_tree($id, $str);
		//$this->setCache($select_categorys,0,"栏目id为 $id 的所有子栏目树状选项卡");
		return $select_categorys;
		
	}	
	//获取seo信息
	function getSeo($catid)
	{
		return M('content_category_seo')->where(array('catid'=>$catid))->find();
	}
}