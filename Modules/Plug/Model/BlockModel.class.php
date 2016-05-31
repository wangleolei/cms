<?php
namespace Plug\Model;
class BlockModel extends \Admin\Classes\CommonModel {
	 protected $tableName='plug_block';
        /**
         * 根据广告块的id返回广告块的名称
         * @param $posid  int 广告块的id
         * @return string 广告块名称
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
         * 根据推荐分组，获取分组下的所有广告块数组
         * @param $group_name string 广告块的名称
         * @return array 分组的所有广告块组成的数组
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
         * 获取所有广告块的数组
        */
        public function getAll(){
            if($val=$this->getCache())return $val;
            $value=$this->where(array('status'=>1))->order('listorder DESC')->select();
            $this->setCache($value,0,'广告块缓存');
            return $value;
        }	
		/*
		* 清除缓存
		*/				 
		public function clearCache()
		{
			$this->delCache('getAll');
		}
		/*
		* 获取某个内容区块的内容
		*/
		public function getSmallBlock($id)
		{
			$content=M('plug_block_content')->where(array('id'=>$id))->getField('content');
			return unserialize($content);
		}
}