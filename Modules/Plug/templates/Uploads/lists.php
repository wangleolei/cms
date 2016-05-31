<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_calender();?>
<style type="text/css">
.goods-thumb-img{ padding:5px; border:#CCC 1px solid; background:#FFF; cursor:pointer; max-height:135px; max-width:135px;}
</style>
<div class="pad-lr-10">
<form name="searchform" action="" method="get" id="searchform">
	<table width="100%" cellspacing="0" class="search-form">
    	<input type="hidden" name="p" value="1">
		<tbody>
			<tr>
				<td><div class="explain-col"> 发表时间：
						<input type="text" name="begin_time" id="begin_time" onClick="WdatePicker()" value="<?php echo $_GET['begin_time'];?>" size="15" class="Wdate" readonly>
						-&nbsp;
						<input type="text" name="end_time" id="end_time" onClick="WdatePicker()" value="<?php echo empty($_GET['end_time'])?date('Y-m-d'):$_GET['end_time'];?>" size="15" class="Wdate" readonly>
						<input name="keyword" type="text" value="<?php echo $_GET['keyword'];?>" class="input-text search-text">
						<select name="type" class="search-select">
							<option value="1" <?php if($_GET['type']==1)echo 'selected';?>>标题</option>
                            <option value="2" <?php if($_GET['type']==2)echo 'selected';?>>用户</option>
						</select>
						<input type="submit" name="search" class="button button-tkp button-tiny" value="搜 索">
					</div></td>
			</tr>
		</tbody>
	</table>
</form>
<form name="myform" id="myform" method="post">
<div class="table-list">
	<table width="100%" cellspacing="0">
		<thead>
			<tr>
			<th  align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>
				<th  align="center" width="150">预览</th>
				<th  align="left">图片规格</th>
				<th width="50">类型</th>
				<th width="70" align="left">文件大小</th>
				<th width="70">上传时间</th>
				<th width="100">用户</th>
				<th width="100" align="center">管理操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($list as $v){?>
			<tr>
				<td align="left"><input type="checkbox" value="<?php echo $v['id'];?>" name="ids[]" /></td>
				<td align="center">
                <img src="<?php echo thumb_url($v['url'],135,135);?>" onClick="show_img('<?php echo $v['url'];?>')"  data-original="" title="<?php echo $v['title'];?>" class="goods-thumb-img">
                </td>
				<td align="left"><?php echo '<a href="'.$v['url'].'" target="_blank">'.$v['url'];?></a><br/>
                <?php 
					$thumb_arr=array_filter(explode(',',$v['thumb']));
					foreach($thumb_arr as $tm)
					{
						$url=thumb_url($v['url'],$tm,$tm);
						echo '<a href="'.$url.'" target="_blank">'.$url.'</a><br/>';
					}
				?>
                </td>
				<td align="center"><?php echo $v['mediatype'];?></td>
				<td align="left"><?php echo (int)($v['size']/1024);?>KB</td>
				<td align="center"><?php echo date('Y-m-d H:i',$v['uptime']);?></td>
				<td align="center"><?php echo $v['owner'];?></td>
				<td align="center"><a href="javascript:void(0)" onClick="edit('<?php echo $v['id'];?>','<?php echo $v['title'];?>')" target="_blank">重命名</a> | <a href="<?php echo U('Plug/Uploads/delete/id/'.$v['id']);?>" onClick="return myconfirm('你确定删除该文件吗？');">删除</a></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
					<div class="btn">
				<input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="删除" onclick="return confirm('你确定删除选中图片？');" formaction="<?php echo U('deleteAll')?>">
                <a type="submit" class="button button-tkp button-tiny" onclick="creatTrumb();">创建缩略图</a>
			</div>
<div id="pages"> <?php echo $show;?> </div>
</div>
</form>
<script type="text/javascript">
<!--
function edit(id,title) {
	top.art.dialog.prompt('请修改图片名称', function (val) {
		$.post('<?php echo U('edit');?>?id='+id,{'title':val},function(data){
			if(data=='success')
			{
			 	window.location.reload();	
			}
			else
			{
				top.art.dialog.alert('修改名称失败！');
			}
		})
}, title);
}
function creatTrumb() {
	window.top.art.dialog.prompt('请输入缩略图规格，多个用‘,’隔开', function (val) {
		$.post('<?php echo U('creatTrumb')?>?type='+val,$("#myform").serialize(),function(data){
			if(data=='success')
			{
				window.top.art.dialog.alert('缩略图生成中！');
			}
			else
			{
				window.top.art.dialog.alert(data);
			}
		})
}, '');
}
function show_img(src){
	var xxx=111;
	top.art.dialog({
	id:'show_img',	
	padding: 0,
	title: '缩略图片预览',
	lock: true,	
    content: '<img src="/Public/images/msg_img/loading_d.gif"/>',
});	
	var img = new Image();
	img.src = src;
	img.onload = function() {
		top.art.dialog({id:'show_img'}).close();
		top.art.dialog({
		padding: 0,
		title: '缩略图片预览',
		lock: true,	
		content: '<img src="'+src+'" style="max-width:800px;"/>'
	});
	}
}
//-->
</script>
</body>
</html>