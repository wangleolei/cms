<?php
namespace Mobile\Controller;
use Think\Controller;
class IndexController extends Controller {
	private $cacheTime;
	private $d_content,$d_category;
	public function __construct()
	{
		parent::__construct();
	}
	//首页
    public function index(){
		$site_index=S('site_mobile_index');
		if($site_index)die($site_index);
		$this->_init();
		//网站首页SEO信息
		$seo=$GLOBALS['setting_model']->getSetting(4);
		$this->assign($seo);
		//广告焦点图
		$d_block=D('Plug/Block');
		$adds=$d_block->getSmallBlock(1);
		$this->assign('adds',$adds);
		//标注首页
		$this->assign('index',true);		
		$site_index=$this->fetch();
		S('site_mobile_index',$site_index,$this->cacheTime);
		echo $site_index;
    }
	//内容列表页及栏目页
	public function lists()
	{
		$catid=intval($_GET['catid']);
		$html_info=S('site_mobile_lists_'.$catid);
		if($html_info)die($html_info);
		$this->_init();
		$catinfo=$this->d_category->where(array('id'=>$catid))->field('name,parentid,modelids,template3')->find();
		$this->assign('name',$catinfo['name']);
		$this->assign('parentid',$catinfo['parentid']);
		$this->assign('catid',$catid);
		$this->assign('modelid',trim($catinfo['modelids'],','));
		$this->assign('pname',$this->d_category->getName($catinfo['parentid']));
		//获取seo信息
		$seo=$this->d_category->getSeo($catid);
		$this->assign($seo);	
		$html_info=$this->fetch('Lists/'.$catinfo['template3']);
		S('site_mobile_lists_'.$catid,$html_info,$this->cacheTime);
		echo $html_info;
	}
	//内容详细页
	public function detail()
	{
		$catid=intval($_GET['catid']);
		$id=intval($_GET['id']);//文章id
		$html_info=S('site_mobile_lists_'.$catid.'_'.$id);
		if($html_info)die($html_info);
		$this->_init();		
		//栏目信息
		$catinfo=$this->d_category->where(array('id'=>$catid))->field('name,parentid,modelids,template4')->find();
		$this->assign('name',$catinfo['name']);
		$this->assign('parentid',$catinfo['parentid']);
		$this->assign('catid',$catid);
		$this->assign('pname',$this->d_category->getName($catinfo['parentid']));
		//文章信息
		$modelid=trim($catinfo['modelids'],',');
		$info=$this->d_content->getDetail($modelid,$id,$catid,true);
		$this->assign('info',$info);
		$this->assign('modelid',$modelid);
		$html_info=$this->fetch('Detail/'.$catinfo['template4']);
		S('site_mobile_lists_'.$catid.'_'.$id,$html_info,$this->cacheTime);
		echo $html_info;
	}
	//初始化网站
	private function _init()
	{
		$site_info=$GLOBALS['setting_model']->getSetting(5);
		$this->cacheTime=$site_info['cachetime'];
		$this->assign('siteinfo',$site_info);
		if(!$this->d_content)
		{
			$this->d_content=D('Content/content');
			$this->assign('d_content',$this->d_content);			
		}
		if(!$this->d_category)
		{
			$this->d_category=D('Content/category');
			$this->assign('d_category',$this->d_category);			
		}
		$navs=M('content_nav')->where('status=1')->select();	
		$this->assign('navs',$navs);
		$this->cacheTime=intval(C('cacheTime'));	
	}
}