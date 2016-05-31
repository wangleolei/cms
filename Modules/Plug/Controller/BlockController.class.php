<?php
/*
* 广告块
*/
namespace Plug\Controller;
class BlockController extends \Admin\Classes\AdminController{
        private $d_pos;
    	function __construct(){
		parent::__construct();
            $this->d_pos=D('Block');   
	}
        function lists(){
            $condition=array();
            if(!empty($_GET['group']))
            {
              $condition['group']=$_GET['group'];  
            }
            $count      = $this->d_pos->where($condition)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $this->d_pos->where($condition)->order('listorder DESC')->limit($Page->firstRow.','.$Page->listRows)->select();      
            include($this->admin_tpl('Block/lists'));
        }
        //添加
        function add(){
            if(IS_POST){
               $info=$_POST['info'];
               $bool=$this->d_pos->filter('new_html_special_chars')->data($info)->add();
			   $this->_delCache();
               if($bool)
               {
                   $this->success('添加广告块成功！');
               }
               else
               {
                  $this->error('添加广告块失败！');
               }
            }
            else{
                include($this->admin_tpl('Block/add'));
            }
        }
        //修改
        function edit(){
            $id=intval($_GET['id']);
            if(IS_POST){
               $info=$_POST['info'];
               $bool=$this->d_pos->where(array('id'=>$id))->filter('new_html_special_chars')->data($info)->save();
			   $this->_delCache();
               if($bool!==false)
               {
                  $this->success('修改广告块成功！');
               }
               else
               {
                  $this->error('修改广告块失败！');
               }               
            }
			else
            {
                $info=$this->d_pos->where(array('id'=>$id))->find();
                include($this->admin_tpl('Block/edit'));
            }
        }
		/*
		* 编辑广告块
		*/
		public function editBlock()
		{
			$blockid=intval($_GET['id']);//广告块id
			$num=M('plug_block')->where(array('id'=>$blockid))->count();
			if(!$num)exit;			
			if(IS_POST)
			{
				$m_b_c=M('plug_block_content');
				I('post.block','strip_tags');
				foreach($_POST['block'] as $k=>$v)
				{
					$data=array();
					$data['title']=$v['title'];
					unset($img);
					foreach($v['img']['src'] as $k1=>$v1)
					{
						$img[$k1]['src']=$v['img']['src'][$k1];
						$img[$k1]['title']=$v['img']['title'][$k1];
						$img[$k1]['url']=$v['img']['url'][$k1];
					}
					$data['content']=serialize($img);
					$m_b_c->where(array('id'=>$k))->data($data)->save();
				}
				$this->success('保存广告块成功！');
			}
			else
			{
				$m_b_c=M('plug_block_content');//广告块内容
				$s_blocks=$m_b_c->where(array('blockid'=>$blockid))->select();
				include($this->admin_tpl('Block/editBlock'));
			}
		}
		/*
		* 添加区块
		*/
		public function addBlock()
		{
			$blockid=intval($_GET['id']);
			if(empty($blockid))exit;
			$id=M('plug_block_content')->data(array('publishtime'=>time(),'blockid'=>$blockid))->add();
			if(!$id)
			{
				$this->ajaxReturn(array('code'=>'error'));
			}
			else
			{
				ob_start();
				include($this->admin_tpl('Block/addBlock'));
				$content=ob_get_contents();
				ob_clean();
				ob_end_clean();
				$this->ajaxReturn(array('code'=>'success','content'=>$content));				
			}
		}
		/*
		* 删除区块
		*/
		public function delBlock()
		{
			$id=intval($_POST['id']);
			if(empty($id))exit;
			$reid=M('plug_block_content')->where(array('id'=>$id))->delete();
			if($reid)
			{
				echo 'success';
			}
		}		
        //删除
        function delete()
        {
            $bool=$this->d_pos->where(array('id'=>$_GET['id']))->delete();
			$this->_delCache();
            if($bool){
                $this->success('删除广告块成功！');
            }
            else{
               $this->success('删除广告块失败！'); 
            }
        }
        //排序
        function listorder()
        {
            foreach($_POST['listorders'] as $k=>$v)
            {
                $this->d_pos->where(array('id'=>$k))->setField('listorder',$v);
            }
			$this->_delCache();
            $this->success('更新排序成功！');      
        }
		//当修改了广告块后，删除广告块的缓存
		private function _delCache()
		{
			$this->d_pos->clearCache();
		}
}