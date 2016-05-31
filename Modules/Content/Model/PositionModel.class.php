<?php
namespace Content\Model;
class PositionModel extends \Admin\Classes\CommonModel {
	 protected $tableName='content_position';
        /**
         * 根据推荐位的id返回推荐位的名称
         * @param $posid  int 推荐位的id
         * @return string 推荐位名称
         */
        public function getName($posid){
            $pos_arr=$this->getAll();
            foreach($pos_arr as $v)
            {
                if($v['id']==$posid)
                {
                    $name= $v['name'];
                    break;
                }
            }
            return $name; 
        }
        /**
         * 根据推荐分组，获取分组下的所有推荐位数组
         * @param $group_name string 推荐位的名称
         * @return array 分组的所有推荐位组成的数组
         */        
        public function getGroup($group_name)
        {
           $pos_arr=$this->getAll();
           $arr=array();
           $i=0;
            foreach($pos_arr as $v)
            {
                if($v['group']==$group_name)
                {
                    $arr[$i]['id']=$v['id'];
                    $arr[$i]['name']=$v['name'];
                }
                $i++;
            }
            return $arr;
        }
        /*
         * 获取所有推荐位的数组
        */
        public function getAll(){
            if($val=$this->getCache())return $val;
            $value=$this->where(array('status'=>1))->order('listorder DESC')->select();
            $this->setCache($value,0,'推荐位缓存');
            return $value;
        }	
		/*
		* 清除缓存
		*/				 
		public function clearCache()
		{
			$this->delCache('getAll');
		}
}