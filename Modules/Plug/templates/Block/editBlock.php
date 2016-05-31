<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_formvalidator();load_artdialog();?>
<script type="text/javascript" src="<?php echo C('JS_PATH');?>admin_form.js"></script>
<style type="text/css">
body {
	background: #e2e9ea;
}
.nav-bar {
	width: 100%;
	height: 40px;
	background: #3a6ea5;
	position: fixed;
	z-index: 999;
	top: 0px;
}
.nav-bar .con {
	width:80%;
	min-width: 978px;
	line-height: 40px;
	margin: 0px auto;
	color: #FFF;
	overflow: hidden;
}
.nav-bar .pos {
	width: 300px;
	float: left;
}
.nav-bar .nav {
	width: 600px;
	float: right;
	height: 40px;
	color: #FFF;
	line-height: 40px;
	text-align: right;
}
.nav-bar .nav a {
	font-size: 14px;
	font-family: "微软雅黑";
	display: inline-block;
	height: 30px;
	padding: 0px 15px;
	line-height: 30px;
	vertical-align: bottom;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
	color: #fff;
}
.nav-bar .nav a.cur {
	background: #fff;
	color: #3a6ea5;
}
.btn-addimg {
	line-height: 20px;
	background: #3a6ea5;
	padding: 5px 15px;
	color: #FFF;
	text-decoration: none !important;
	border: #069 1px solid;
	border-radius: 3px;
}
.img-set {
	overflow: hidden;
	margin-left: -20px;
}
.img-set li {
	float: left;
	width: 222px;
	margin-left: 16px;
	margin-top: 10px;
	position: relative;
}
.img-set li .img-con {
	width: 220px;
	height: 220px;
	border: 1px #eee solid;
	line-height: 210px;
	vertical-align: middle;
	display: table-cell;
	text-align: center;
}
.img-set li img {
	max-width: 200px;
	max-height: 200px;
	padding: 10px;
}
.img-set li .img-title {
	margin-top: 5px;
	width: 215px;
	text-align: center;
}
.img-close {
	width: 18px;
	height: 18px;
	position: absolute;
	top: 5px;
	z-index: 99;
	right: 5px;
	color: #fff;
	cursor: pointer;
	line-height: 18px;
	text-align: center;
	border-radius: 50%;
	font-weight: bolder;
	background: #F00;
	font-size: 16px;
	background: url(/Public/images/msg_img/msg_bg.png) no-repeat;
}
.img-position{
	width: 20px;
	height: 20px;
	position: absolute;
	z-index: 99;
	left: 5px;
	top: 5px;
	}
.img-position .up{ display:block; background: url(/Public/images/icon/listorder.png) no-repeat rgba(204,204,204,1); width:20px; height:9px; border-top-left-radius:30px;border-top-right-radius:30px;}
.img-position .down{display:block; background:url(/Public/images/icon/listorder.png) 0 -10px no-repeat rgba(204,204,204,1); width:20px; height:9px; margin-top:2px;border-bottom-left-radius:30px;border-bottom-right-radius:30px;}
.img-position a:hover{ background-color:rgba(153,153,153,1);}
.small-block {
	position: relative;
}
.small-block .del-block-btn {
	position: absolute;
	background: #CCC;
	height: 18px;
	width: 18px;
	z-index: 99;
	background: url(/Public/images/msg_img/msg_bg.png) no-repeat;
	top: 10px;
	right: 10px;
	cursor: pointer;
}
</style>
<form name="myform" id="myform" action="" method="post" enctype="multipart/form-data">
	<div class="nav-bar">
		<div class="con">
			<div class="pos"><i class="fa fa-home fa-lg"></i> 当前位置：内容  > 广告块 > 编辑广告块 </div>
		</div>
	</div>
	<div class="addContent" style="padding-top:45px;">
		<div class="col-auto">
			<div class="col-1">
				<div class="pad-6" id="small-blocks">
					<?php foreach($s_blocks as $vo){?>
					<fieldset style="margin-top:10px;" class="small-block" id="block_<?php echo $vo['id'];?>">
                    <?php if($this->check_url_prev('delBlock')){?>
						<div class="del-block-btn" onClick="del_small_block(<?php echo $vo['id'];?>,this)"></div>
                     <?php }?>   
						<table width="100%" cellspacing="0" class="table_form">
							<tbody>
								<tr style="height:40px;">
									<th width="115"><a class="button  button-tkp button-tiny"  href='javascript:void(0);' onclick="blockid=<?php echo $vo['id'];?>;flashupload(crert_str);return false;"><i class="fa fa-plus fa-lg"></i> <em>添加内容</em></a></th>
									<td><b><?php echo $vo['id'];?></b> 号区块名称：
										<input type="text" value="<?php echo $vo['title'];?>"  class="input-text" size="40" name="block[<?php echo $vo['id'];?>][title]"/></td>
								</tr>
								<tr>
									<td colspan="2"><ul class="img-set">
											<?php $imgs=unserialize($vo['content']); foreach($imgs as $img){?>
											<li>
												<div class="img-con"><img src="<?php echo $img['src'];?>" /></div>
												<input type="hidden" name="block[<?php echo $vo['id'];?>][img][src][]" value="<?php echo $img['src'];?>" />
												<input title="text" name="block[<?php echo $vo['id'];?>][img][title][]" placeholder="请输入图片标题" class="img-title" value="<?php echo $img['title'];?>"/>
												<input title="text" name="block[<?php echo $vo['id'];?>][img][url][]" placeholder="请输入链接地址" class="img-title" value="<?php echo $img['url'];?>"/>
												<div class="img-close" onClick="remove_img_box(this)"></div>
                                               	<div class="img-position">
                                                	<a href="javascript:void(0)" onclick="listorderUp(this)" class="up"></a>
                                                    <a href="javascript:void(0)" onclick="listorderDown(this)" class="down"></a>
                                                </div>
											</li>
											<?php }?>
										</ul></td>
								</tr>
							</tbody>
						</table>
					</fieldset>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="fixed-bottom" id="block-footer">
		<div class="fixed-but text-c">
			<div class="button button-tkp button-tiny">
				<input value="保存" type="submit" name="dosubmit" class="cu" style="width:100px;">
			</div>
			<div class="button button-tkp button-tiny">
				<input value="关闭(X)" type="button" name="close" onclick="javascript:window.opener=null;window.open('','_self');window.close();" class="cu" style="width:100px;">
			</div>
            <?php if($this->check_url_prev('addBlock')){?>
			<div class="button button-tkp button-tiny">
				<input value="添加区块(X)" type="button" name="close" onclick="add_small_block()" class="cu" style="width:100px;">
			</div>	
            <?php }?>		
		</div>
	</div>
</form>
<script type="text/javascript">
function listorderUp(obj)
{
	var newobj=$(obj).parent().parent();
	var listorder=$(newobj).index();
	if(listorder>0)
	{
		var html=$(newobj).parent().find('li').eq(listorder).prop("outerHTML");
		var newlistorder=listorder-1;
		$(newobj).parent().find('li').eq(newlistorder).before(html);	
		$(newobj).remove();
	}
}
function listorderDown(obj)
{
	var newobj=$(obj).parent().parent();
	var listorder=$(newobj).index();
	var size=$(newobj).parent().find('li').length;
	if(listorder<size-1)
	{
		var html=$(newobj).parent().find('li').eq(listorder).prop("outerHTML");
		var newlistorder=listorder+1;
		$(newobj).parent().find('li').eq(newlistorder).after(html);	
		$(newobj).remove();
	}	
}
var blockid;
function crert_str(arr)
{
	var str='';
	for(var i=0;i<arr.length;i++){
		str+='<li><div class="img-con"><img src="'+arr[i]['src']+'" /></div><input type="hidden" name="block['+blockid+'][img][src][]" value="'+arr[i]['src']+'"><input title="text" name="block['+blockid+'][img][title][]" class="img-title input-text" value="'+arr[i]['name']+'" placeholder="请输入图片标题"/><input title="text" name="block['+blockid+'][img][url][]" class="img-title input-text" placeholder="请输入图片地址" /><div class="img-close" onClick="remove_img_box(this)"></div><div class="img-position"><a href="javascript:void(0)" onclick="listorderUp(this)" class="up"></a><a href="javascript:void(0)" onclick="listorderDown(this)" class="down"></a></div></li>';	
	}
	$("#block_"+blockid+" .img-set").append(str);
}
function remove_img_box(obj)
{
	$(obj).parent().remove();
}
function close_window(){
	window.close();
	}
function add_small_block()
{
	$.post('<?php echo U('addBlock',array('id'=>$_GET['id']));?>',{},function(data){
		if(data.code=='success')
		{
			$("#small-blocks").append(data.content);
			window.location='<?php echo __SELF__?>'+'#block-footer';
			window.top.art.dialog.alert('成功添加一个区块！');
		}
		else
		{
			window.top.art.dialog.alert('添加区块失败！');	
		}		
	},"json");
}
function del_small_block(id,obj)
{
	if(confirm("你确认删除该区块？"))
	{
		$.post('<?php echo U('delBlock');?>',{'id':id},function(data){
			
			if(data=='success')
			{
				$(obj).parent().remove();
				window.top.art.dialog.alert('删除区块成功！');
			}
			else
			{
				window.top.art.dialog.alert('删除区块失败！');	
			}
		});
	}
}	
</script> 
<!-- 右侧导航 -->

<div class="rightNav"> <a href="#"><em>^</em>回到顶部</a> 
<?php foreach($s_blocks as $vo){?>
<a href="#block_<?php echo $vo['id'];?>"><em><?php echo $vo['id'];?></em><?php echo $vo['title'];?></a>
<?php }?>
</div>
<script type="text/javascript">
	//右侧导航
	var btb=$(".rightNav");
	var tempS;
	$(".rightNav").hover(function(){
			var thisObj = $(this);
			tempS = setTimeout(function(){
			thisObj.find("a").each(function(i){
				var tA=$(this);
				setTimeout(function(){ tA.animate({right:"0"},200);},50*i);
			});
		},200);

	},function(){
		if(tempS){ clearTimeout(tempS); }
		$(this).find("a").each(function(i){
			var tA=$(this);
			setTimeout(function(){ tA.animate({right:"-110"},200,function(){
			});},50*i);
		});

	});
	var isIE6 = !!window.ActiveXObject&&!window.XMLHttpRequest;

	//滚动加载
	var scrollLoad =function(){
		$("#content iframe[_src]").each(function(){
				var t = $(this);
				if( t.offset().top<= $(document).scrollTop() + $(window).height()  )
				{
					t.attr( "src",t.attr("_src") ).removeAttr("_src");
				}
		});//each E
	}

	scrollLoad();
	$(window).scroll(function(){ 
		if(isIE6){ btb.css("top", $(document).scrollTop()+30) }
		scrollLoad();
	});
</script>
</body></html>