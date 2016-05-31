<?php
namespace Content\Controller;
class PositionController extends \Admin\Classes\AdminController{
        private $d_pos;
    	function __construct(){
		parent::__construct();
            $this->d_pos=D('Position');   
	}
        function lists(){
            $condition=array();
            if(!empty($_GET['modelid']))
            {
              $condition['modelid']=$_GET['modelid'];  
            }
            $count      = $this->d_pos->where($condition)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $this->d_pos->where($condition)->order('listorder DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
			$d_models=D('Content/Sitemodel');
			$models=$d_models->getModels();	     
            include($this->admin_tpl('Position/lists'));
        }
        //添加
        function add()
        {
            if(IS_POST){
               $info=$_POST['info'];
               $bool=$this->d_pos->filter('new_html_special_chars')->data($info)->add();
			   $this->_delCache();
               if($bool)
               {
                   $this->success('添加推荐位成功！');
               }
               else
               {
                  $this->error('添加推荐位失败！');
               }
            }
            else{
				$d_models=D('Content/Sitemodel');
				$models=$d_models->getModels();
                include($this->admin_tpl('Position/add'));
            }
        }
        //修改
        function edit()
        {
            $id=intval($_GET['id']);
            if(IS_POST){
               $info=$_POST['info'];
               $bool=$this->d_pos->where(array('id'=>$id))->data($info)->save();
			   $this->_delCache();
               if($bool!==false)
               {
                  $this->success('修改推荐位成功！');
               }
               else
               {
                  $this->error('修改推荐位失败！');
               }               
            }
			else
            {
                $info=$this->d_pos->where(array('id'=>$id))->find();
				$d_models=D('Content/Sitemodel');
				$models=$d_models->getModels();				
                include($this->admin_tpl('Position/edit'));
            }
        }
        //删除
        function delete()
        {
            $bool=$this->d_pos->where(array('id'=>$_GET['id']))->delete();
			$this->_delCache();
            if($bool){
                $this->success('删除推荐位成功！');
            }
            else{
               $this->error('删除推荐位失败！'); 
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
		//当修改了推荐位后，删除推荐位的缓存
		private function _delCache()
		{
			$this->d_pos->clearCache();
		}
}