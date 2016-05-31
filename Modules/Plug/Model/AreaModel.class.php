<?php
namespace Plug\Model;
class AreaModel extends \Admin\Classes\CommonModel {
	 private $v_cats=array();
	 protected $tableName='plug_area';
	/**
	 * 获取所有下级行政区
	 * @param $catid 顶级行政区id
	 * @return array
	 */
	function cats($catid)
	{
		if($catid==0)
		{
			$cats=$this->select();
		}
		else
		{
			$condition=array('id'=>$catid);
			$path=$this->where($condition)->getField('path');
			$cats=$this->where(array('path'=>array(array('like',$path.'-'.$catid.'-%'),array('eq',$path.'-'.$catid),'or')))->select();
		}
		return $cats;
	}
		
	//行政区的所有子行政区
	function getChildren($catid)
	{
		return $this->cats($catid);
	}
	/**
	 * 根据行政区id获取行政区名称
	 * @param $areaid 行政区id
	 * @return array
	 */        
      public function getName($areaid){
			$cats=$this->cats();
			foreach($cats as $val)
			{
				if($val['id']==$areaid)
				{
				   $catname=$val['name'];
                   break;
				}
			}
			return $catname;
        }
	/*
	* 返回一个行政区所有的子行政区的,用','号隔开
	*/
	public function catid_str($catid)
	{
		$valus=$this->getChildren($catid);
		$str='';
		foreach($valus as $k=>$v)
		{
			$str.=$v['id'].',';		
		}
		$str.=$catid;
		return $str;		
	}
	/*
	* 创建行政区选项卡,当选项比较多的时候会占用较多CPU资源，因此不建议使用
	* $id行政区id
	* $selected_id 被选中的id
	*/
	function category2select($id,$selected_id)
	{
		$result=$this->cats($id);
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
		return $select_categorys;
	}
	/*
	* 获取一个行政区的下一级行政区
	*/
	function getNextCategory($catid)
	{
		return $this->where(array('parentid'=>$catid))->select();
	}
	/*
	* $catid int 上一级行政区id
	* $selected_id int 选中的行政区id
	*/	
	function getOneStageSelect($catid,$selectid,$class)
	{
		$cats=$this->getNextCategory($catid);
		if(empty($cats))return;
		$str.='<select onChange="cat_select(this)" class="'.$class.'">';
		$str.='<option value="0">--请选择--</option>';
		foreach($cats as $vo)
		{	if($vo['id']==$selectid)
			{
				$str.="<option value='{$vo['id']}' selected>{$vo['name']}</option>";
			}
			else
			{
				$str.="<option value='{$vo['id']}'>{$vo['name']}</option>";
			}
		}
		$str.='</select>';
		return $str;		
	}	
	/*
	* 创建行政区联级菜单
	* $catid int 初始化$catid
	*/
	function getCategorySelect($catid,$class)
	{
		$str='<span id="category_select_box">';
		if(empty($catid))
		{
			$catid=0;
			$str.=$this->getOneStageSelect($catid,'',$class);
		}
		else
		{
			$path=$this->where(array('id'=>$catid))->getField('path');
			$arr=explode('-',$path);
			$arr[]=$catid;
			foreach($arr as $k=>$v)
			{
				//if(isset($arr[$k+1]))
				{
					$str.=$this->getOneStageSelect($v,$arr[$k+1],$class);
				}
			}
		}	
		 $str.='</span>';
		 return $str;
	}
	function get_path_name($areaid)
	{
		$areaname=$this->getName($areaid);
		$areapath=$this->where(array('id'=>$areaid))->getField('path');
		$arr=array_slice(explode('-',$areapath),1);
		foreach($arr as $v)
		{
			$path_name.=$this->getName($v).'-';
		}
		return $path_name.$areaname;
	}
}