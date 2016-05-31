<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();load_calender();?>
<script type="text/javascript">
var obj=window.top.document;
$(obj).find("#display_center_id").show();
</script>
<div class="pad-lr-10">
<form name="searchform" action="<?php echo U('lists',array('modelid'=>$_GET['modelid']));?>" method="get">
	<table width="100%" cellspacing="0" class="search-form">
    	<input type="hidden" name="p" value="1">
		<tbody>
			<tr>
				<td><div class="explain-col"> 发表时间：
                		<input type="hidden" name="catid" value="<?php echo $_GET['catid'];?>">
                        <input type="hidden" name="modelid" value="<?php echo $_GET['modelid'];?>">
						<input type="text" name="begin_time" id="begin_time" onClick="WdatePicker()" value="<?php echo $_GET['begin_time'];?>" size="15" class="Wdate" readonly>
						-&nbsp;
						<input type="text" name="end_time" id="end_time" onClick="WdatePicker()" value="<?php echo empty($_GET['end_time'])?date('Y-m-d'):$_GET['end_time'];?>" size="15" class="Wdate" readonly>
						</select>
						<input name="keyword" type="text" value="<?php echo $_GET['keyword'];?>" class="input-text search-text">
						<select name="type" class="search-select">
							<option value="1" <?php if($_GET['type']==1)echo 'selected';?>>标题</option>
							<option value="2" <?php if($_GET['type']==2)echo 'selected';?>>文章ID</option>
							<option value="3" <?php if($_GET['type']==3)echo 'selected';?>>作者</option>
						</select>
						<input type="submit" name="search" class="button button-tkp button-tiny" value="搜 索">
					</div></td>
			</tr>
		</tbody>
	</table>
</form>
<form name="myform" action="<?php echo U('deleteAll')?>" method="post">
<div class="table-list">
	<table width="100%" cellspacing="0">
		<thead>
			<tr>
			<th  align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>
				<th width="30" align="left">id</th>
                <th width="60">排序</th>
				<th  align="left">标题</th>
				<th width="150">栏目</th>
                <th width="150">推荐位</th>
				<th width="105">发布时间</th>
				<th width="100">用户</th>
				<th width="100" align="center">管理操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($list as $v){?>
			<tr>
				<td align="left"><input type="checkbox" value="<?php echo $v['id'];?>" name="ids[]"></td>
				<td><?php echo $v['id'];?></td>
                <td align='center'><input name='listorders[<?php echo $v['id'];?>]' type='text' size='3' value='<?php echo $v['listorder'];?>' class='input-text-c'></td>
				<td><?php if(!empty($v['thumb'])){?><img onClick="show_img('<?php echo $v['thumb'];?>')" src="<?php echo C('IMG_PATH')?>icon/small_img.gif" title="缩略图" class="title-img-ico"> <?php }?> <a href="<?php echo UN('Home/Index/detail',array('catid'=>$_GET['catid'],'id'=>$v['id']));?>" target="_blank"><?php echo $v['title'];?></a></td>
				<td align="center"><?php 
				 	echo D('category')->getName($v['catid']); ?>
				 </td>
                <td align="center"><?php
					$posids=array_filter(explode(',',$v['posids']));
					foreach($posids as $posid)
					{
						echo D('Content/position')->getName($posid);
						if(end($posids)!= $posid)
						{
							echo ',';
						}
					}
                ?></td>
				<td align="center"><?php echo date('Y-m-d H:i',$v['inputtime']);?></td>
				<td align="center"><?php echo $v['username'];?></td>
				<td align="center"><a href="<?php echo U('edit',array('id'=>$v['id'],'modelid'=>$_GET['modelid']));?>" target="_blank">修改</a> | <?php if($v['disdel']){?><font color="#ccc">删除</font><?php }else{?><a href="<?php echo U('Content/Content/delete?catid='.$v['catid'].'&id='.$v['id'].'&modelid='.$_GET['modelid']);?>" onClick="return myconfirm('你确定删除这篇文章吗？');">删除</a><?php }?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
					<div class="btn">
                <input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="排序" formaction="<?php echo U('listorder',array('modelid'=>$_GET['modelid'],'catid'=>$_GET['catid']));?>"/>
			</div>
<div id="pages"> <?php echo $show;?> </div>
</div>
</form>
<script type="text/javascript">
<!--
function show_img(src){
	top.art.dialog({
	id:'show_img',	
	padding: 0,
	title: '缩略图片预览',
	lock: true,	
    content: '<img src="/Public/images/msg_img/loading_d.gif"/>'
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
</body></html>