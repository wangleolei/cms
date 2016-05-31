<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($seo_title?$seo_title:$name); ?> - ThinkerCMS</title>
<meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
<meta name="description" content="<?php echo ($seo_description); ?>" />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<link rel="stylesheet" type="text/css" href="/Public/modules/home/css/style_2_common.css?Jvw" />
<link rel="stylesheet" type="text/css" href="/Public/modules/home/css/style_2_portal_list.css?Jvw" />
<script src="/Public/modules/home/js/common.js?Jvw" type="text/javascript"></script>
<script src="/Public/modules/home/js/jquery-1.7.2.js" type="text/javascript"></script>
<script src="/Public/modules/home/js/pace.js" type="text/javascript"></script>
<link href="/Public/modules/home/css/style.css" rel="stylesheet" type="text/css" /></head>
<body>
<div id="hd" style="background:#FFF; height:80px;">
  <div class="clear"></div>
  <div id="week_nav">
    <div class="wk_navwp">
      <div class="">
        <div class="wk_logo">
          <h2><a href="<?php echo C('site_host');?>/" title="ThinkerCMS"><img src="/Public/modules/home/images/logo.png" alt="ThinkerCMS" border="0" /></a></h2>
        </div>
        <div class="wk_inav">
          <ul class="nav">
            <li <?php if($index): ?>class="a"<?php endif; ?> id="mn_portal" onmouseover="showMenu({'ctrlid':this.id,'ctrlclass':'hover','duration':2})"><a href="<?php echo C('site_host');?>/" hidefocus="true" title="首页"  >首页<span>home</span></a></li>
            <?php $cats=$d_category->getChildren(0); ?>
            <?php if(is_array($cats)): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($catid == $vo['id'] or $parentid == $vo['id']): ?>class="a"<?php endif; ?> id="mn_P<?php echo ($vo['id']); ?>" onmouseover="showMenu({'ctrlid':this.id,'ctrlclass':'hover','duration':2})"><a href="<?php echo ($vo['url']?$vo['url']:UN('lists',array('catid'=>$vo['id']))); ?>" hidefocus="true" ><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
        </div>
<!--        <div class="wk_idl">
          <div id="header_user">
            <ul id="header_nav">
              <li> <span class="wk_lh60"><a class="login_block wk_dl" href="member.php?mod=logging&amp;action=login&amp;referer=" onclick="showWindow('login', this.href);return false;" title="登录" id="wk_dl" onMouseOver="showMenu({'ctrlid':this.id,'ctrlclass':'a'})">登录</a></span> <span class="wk_lh60"><a class="login_block wk_zc btn-register" href="member.php?mod=register" title="注册">注册</a></span> </li>
            </ul>
          </div>
        </div>-->
        <div class="wk_t_tel"> <span><?php echo ($siteinfo["phone"]); ?></span> </div>
      </div>
    </div>
  </div>
</div>
<ul class="sub_menu" id="wk_dl_menu" style="display: none;">
  <li><a href="connect.php?mod=login&amp;op=init&amp;referer=index.php&amp;statfrom=login" title="QQ登录"><span class="i_qq"></span>QQ登录</a></li>
  <li><a href="plugin.php?id=wechat:login" title="微信登录"><span class="i_wx"></span>微信登录</a></li>
  <li><a href="plugin.php?id=ljxlwb&amp;opp=in" title="微博登录"><span class="i_wb"></span>微博登录</a></li>
</ul>
<div class="sub_nav">
	<div class="p_pop h_pop" id="mn_userapp_menu" style="display: none"></div>
<!--  <ul class="p_pop h_pop" id="mn_portal_menu" style="display: none">
    <li><a href="" hidefocus="true" target="_blank" >购买模板</a></li>
  </ul>-->
  <ul class="p_pop h_pop" id="mn_P1_menu" style="display: none">
  	<?php $arts=$d_content->getContentList(array('modelid'=>1,'catid'=>1)); ?>
    <?php if(is_array($arts)): $i = 0; $__LIST__ = $arts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo UN('detail',array('catid'=>1,'id'=>$vo['id']));?>" hidefocus="true" ><?php echo ($vo['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
 <?php if(is_array($cats)): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i; $cats=$d_category->getChildren($vo1['id']); ?>
 <?php if($cats): ?><ul class="p_pop h_pop" id="mn_P<?php echo ($vo1["id"]); ?>_menu" style="display: none">	
    <?php if(is_array($cats)): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo UN('lists',array('catid'=>$vo['id']));?>" hidefocus="true" ><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
  <!--<ul class="p_pop h_pop" id="mn_forum_2_menu" style="display: none">
    <li><a href="forum.php?mod=forumdisplay&fid=38" hidefocus="true" >社区活动</a></li>
    <li><a href="forum.php?mod=forumdisplay&fid=41" hidefocus="true" >瀑布流</a></li>
  </ul>-->
</div>
<script src="/Public/modules/home/js/week_nav.js" type="text/javascript"></script>
  <style>
.ct2 { border:0; padding-bottom:30px;} 
.wk_c_right_name { height:30px;}
.wk_c_right_name_l, .wk_c_right_name_r { padding-top:0;}
.wk_pro_main {width:265px;}
.wk_pro_img img{width:245px;}
.wk_pro_main_name{ width:225px; }
</style>
<div class="wk_head_banner">
  <div class="wk_portalhead_bn15">
    <div class="wk_portalhead_bg">
      <h1><a><?php echo ($name); ?></a></h1>
      <div class="clear"></div>
      <p>创新突破稳定品质，落实管理提高效率</p>
      <div class="clear"></div>
      <ul>
        <li><a href="<?php echo UN('lists',array('catid'=>$parentid));?>" title="<?php echo ($name); ?>">全部</a></li>
        <?php $cats=$d_category->getChildren($parentid); ?>
        <?php if(is_array($cats)): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo UN('lists',array('catid'=>$vo['id']));?>" title="<?php echo ($vo["name"]); ?>" <?php if($vo['id'] == $catid): ?>class="a"<?php endif; ?>><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="wk_ymbg">
  <div id="wp" class="wp"> 
    <script src="/Public/modules/home/js/jquery.hover.js" type="text/javascript"></script> 
    <script type="text/javascript">
jQuery(document).ready(function(){jQuery(function() {jQuery('#photo .list').hoverdir();});});
</script>
    <div class="wp"> 
      <!--[diy=diy1]-->
      <div id="diy1" class="area"></div>
      <!--[/diy]--> 
    </div>
    <div id="ct" class="ct2 wp cl">
      <div class="wk_c_right_name">
        <div class="wk_c_right_name_l">
          <div class="bm_h cl"> <a href="javascript:void(0)" class="y xi2 rss" target="_blank" title="RSS">订阅</a> </div>
        </div>
        <div class="wk_c_right_name_r">
          <ul>
            <li> <img src="/Public/modules/home/images/right_wz.png" alt="" /> </li>
             <li> <span>您现在的位置：</span> <span><a href="<?php echo C('site_host');?>/">首页</a></span> <span>→</span> <span><a href="<?php echo UN('lists?catid='.$parentid);?>"><?php echo ($pname); ?></a></span><span>→</span> <span><?php echo ($name); ?></span> </li>
          </ul>
        </div>
      </div>
      <div class="clear"></div>
      <!--[diy=listcontenttop]-->
      <div id="listcontenttop" class="area"></div>
      <!--[/diy]-->
      <div class="xld">
      	<?php $val=$d_content->getContentList(array('modelid'=>$modelid,'catid'=>$catid,'num'=>8,'ispage'=>1)); ?>
        <?php if(is_array($val["list"])): $i = 0; $__LIST__ = $val["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl class="wk_rele cl">
          <dd class="wk_asl_01 <?php if($i%2 == 0): ?>wk_asl_r<?php endif; ?>"> <em class="wk_tw01"><em class="wk_tw02"><a href="<?php echo UN('detail',array('catid'=>$vo['catid'],'id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>" target="_blank" style="background-image:url(<?php echo thumb_url($vo['thumb'],400,400);?>)"><?php echo ($vo["title"]); ?></a></em></em> </dd>
          <dt><a href="<?php echo UN('detail',array('catid'=>$vo['catid'],'id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>" target="_blank" class="xi2" style=""><?php echo ($vo["title"]); ?></a> </dt>
          <dd><?php echo ($vo["description"]); ?> ...</dd>
          <dd class="xg1"> <span  class="wk_xg1">时间:<?php echo date('Y-m-d H:i',$vo['inputtime']);?></span> </dd>
          <div class="clear"></div>
        </dl><?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
      <div class="clear"></div>
      <div class="pgs cl">
        <div class="pg"><?php echo ($val["page"]); ?></div>
      </div>
      <!--[diy=diycontentbottom]-->
      <div id="diycontentbottom" class="area"></div>
      <!--[/diy]-->
      <div class="clear"></div>
    </div>
  </div>
  <div class="wp mtn"> 
    <!--[diy=diy3]-->
    <div id="diy3" class="area"></div>
    <!--[/diy]--> 
  </div>
</div>
<script type="text/javascript">
_attachEvent(window, 'load', getForbiddenFormula, document);
function getForbiddenFormula() {
var toGetForbiddenFormulaFIds = function () {
ajaxget('plugin.php?id=cloudsearch&formhash=1e0fac23');
};
var a = document.body.getElementsByTagName('a');
for(var i = 0;i < a.length;i++){
if(a[i].getAttribute('sc')) {
a[i].setAttribute('mid', hash(a[i].href));
a[i].onmousedown = function() {toGetForbiddenFormulaFIds();};
}
}
var btn = document.body.getElementsByTagName('button');
for(var i = 0;i < btn.length;i++){
if(btn[i].getAttribute('sc')) {
btn[i].setAttribute('mid', hash(btn[i].id));
btn[i].onmousedown = function() {toGetForbiddenFormulaFIds();};
}
}
}
</script>
<div id="wk_ft">
  <div class="wk_foot">
    <div class="wk_foot_c">
      <div class="wk_foot_topx">
        <div class="wk_fot_01"><span>网站导航</span></div>
        <div class="wk_fot_02"><span>企业简介</span></div>
        <div class="wk_fot_03"><span>联系我们</span></div>
        <div class="wk_fot_04"><span>站内搜索</span></div>
      </div>
      <div class="wk_foot_nav">
        <ul>
          <?php if(is_array($navs)): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
      <div class="wk_liuyan">
        <div class="wk_ft_logo"><img src="/Public/modules/home/images/ft_logo.png" height="50" alt="ThinkerCMS_技术服务演示站点" border="0" /></div>
        <div class="wk_ft_qyjs">ThinkerCMS是ThinkerPHP旗下的一款专门针对小型网站的内容管理系统，其特点在于小巧精致，可快速进行二次开发。</div>
      </div>
      <div class="wk_foot_content">
        <div class="wk_foot_add">地址：<?php echo ($siteinfo["address"]); ?></div>
        <div class="wk_foot_phone">电话：<?php echo ($siteinfo["phone"]); ?></div>
        <div class="wk_foot_fax">传真：<?php echo ($siteinfo["fax"]); ?></div>
        <div class="wk_foot_zip">邮箱：<?php echo ($siteinfo["email"]); ?></div>
      </div>
      <div class="wk_foot_search">
        <div id="scbar" class="cl">
          <form id="scbar_form" method="post" autocomplete="off" onsubmit="searchFocus($('scbar_txt'))" action="search.php?searchsubmit=yes" target="_blank">
            <input type="hidden" name="mod" id="scbar_mod" value="search" />
            <input type="hidden" name="formhash" value="4557416e" />
            <input type="hidden" name="srchtype" value="title" />
            <input type="hidden" name="srhfid" value="0" />
            <input type="hidden" name="srhlocality" value="portal::index" />
            <table cellspacing="0" cellpadding="0">
              <tr>
                <td class="scbar_txt_td"><input type="text" name="srchtxt" id="scbar_txt" value="请输入搜索内容" autocomplete="off" x-webkit-speech speech /></td>
                <td class="scbar_type_td"><a href="javascript:;" id="scbar_type" class="xg1" onclick="showMenu(this.id)" hidefocus="true">搜索</a></td>
                <td class="scbar_btn_td"><button type="submit" name="searchsubmit" id="scbar_btn" sc="1" class="pn pnc" value="true"><strong class="xi2">搜索</strong></button></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      <div class="wk_foot_key">
        <ul class="wk_foot_kbox">
          <li><a href=""></a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="wk_foot_fx">
        <div class="bdsharebuttonbox"> <a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a> <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a> <a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a> <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a> </div>
        <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"2","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
      </div>
      <div class="clear"></div>
      <div class="wk_foot_bkp">
        <div class="wk_copy_name">Copyright &copy; 2016 <a href="http://www.thinkerphp.com" target="_blank">ThinkerPHP.</a> Powered by <a href="http://cms.thinkerphp.com" title="thinkercms!" target="_blank">ThinkerCMS!</a> <em><?php echo C('version');?></em> 
          <a href="http://www.miitbeian.gov.cn/" target="_blank">( <?php echo ($siteinfo["icp"]); ?> )</a>&nbsp;&nbsp;<span id="tcss"></span></div>
      </div>
    </div>
  </div>
  <div id="ft" class="wp cl" style="border:0;"> </div>
</div> <link href="/Public/modules/home/css/service.css" rel="stylesheet" type="text/css" />
<div class="main-im">
  <div id="open_im" class="open-im">&nbsp;</div>
  <div class="im_main" id="im_main">
    <div id="close_im" class="close-im"><a href="javascript:void(0);" title="点击关闭">&nbsp;</a></div>
    <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($siteinfo["qq"]); ?>&amp;site=qq&amp;menu=yes" target="_blank" class="im-qq qq-a" title="在线QQ客服">
    <div class="qq-container"></div>
    <div class="qq-hover-c"><img class="img-qq" src="/Public/modules/home/images/qq.png"></div>
    <span>QQ在线咨询</span> </a>
    <div class="im-tel">
      <dt>售前咨询热线</dt>
      <dt class="tel-num"><?php echo ($siteinfo["phone"]); ?></dt>
      <dt>售后服务热线</dt>
      <dt class="tel-num"><?php echo ($siteinfo["phone1"]); ?></dt>
    </div>
    <div class="im-footer" style="position:relative">
      <div class="weixing-container">
        <div class="weixing-show">
          <div class="weixing-txt">微信扫一扫<br>
            关注ThinkerPHP<br>
            获取更多信息</div>
          <img class="weixing-ma" src="/Public/modules/home/images/weixin.jpg">
          <div class="weixing-sanjiao"></div>
          <div class="weixing-sanjiao-big"></div>
        </div>
      </div>
      <div class="go-top"><a href="javascript:;" title="返回顶部"></a> </div>
      <div style="clear:both"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
FOM(function(){
FOM('#close_im').bind('click',function(){
FOM('#main-im').css("height","0");
FOM('#im_main').hide();
FOM('#open_im').show();
});
FOM('#open_im').bind('click',function(e){
FOM('#main-im').css("height","272");
FOM('#im_main').show();
FOM(this).hide();
});
FOM('.go-top').bind('click',function(){
FOM(window).scrollTop(0);
});
FOM(".weixing-container").bind('mouseenter',function(){
FOM('.weixing-show').show();
})
FOM(".weixing-container").bind('mouseleave',function(){        
FOM('.weixing-show').hide();
});
});
</script> 
<div id="scrolltop"> <span hidefocus="true"><a title="返回顶部" onclick="window.scrollTo('0','0')" class="scrolltopa" ><b>返回顶部</b></a></span> </div>
<script type="text/javascript">_attachEvent(window, 'scroll', function () { showTopLink(); });checkBlind();</script>
</body>
</html>