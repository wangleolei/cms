<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<div class="pad-lr-10">
<form name="searchform" action="" method="get">
	<table width="100%" cellspacing="0" class="search-form">
    	<input type="hidden" name="p" value="1">
		<tbody>
			<tr>
				<td><div class="explain-col">			

                         友情链接名称：
						<input name="name" type="text" value="<?php echo $_GET['name'];?>" class="input-text search-text">
						<input type="submit" name="search" class="button button-tkp button-tiny" value="搜 索">
					</div></td>
			</tr>
		</tbody>
	</table>
	
</form>
<form name="myform" action="<?php echo U('listorder')?>" method="post">    
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="80">排序</th>
            <th width="40" align="left">id</th>
			<th width="250" align="left">友情链接图标</th>
            <th width="150" align="left">友情链接名称</th>		
			<th align="left">url地址</th> 
			<th width="100" align="center">是否显示</th>
			<th width="100" align="center">管理操作</th>
            </tr>
        </thead>
	<tbody>
	<?php foreach($list as $v){?>
		<tr>
			<td align='center'><input name='listorders[<?php echo $v['id'];?>]' type='text' size='3' value='<?php echo $v['listorder'];?>' class='input-text-c'></td>
			
			<td><?php echo $v['id'];?></td>
			<td><?php if($v['image']){?><img src="<?php echo $v['image'];?>" style="max-height:50px;"/><?php }?></td>
			<td><?php echo $v['name'];?></td>
           
			<td align="left"><?php echo $v['url'];?></td>
			<td align="center";><img class='ico-gou' data-gou='<?php echo $v['display'];?>' src='/Public/images/icon/gou_<?php echo $v['display'];?>.png' onClick='change_display(this,<?php echo $v['id'];?>)' /></td>
			<td align="center"> <a href="javascript:void(0)" onClick="edit('<?php echo $v['id'];?>','修改当前友情链接')">修改</a> | <a href="<?php echo U('Plug/Link/delete/id/'.$v['id']);?>" onClick="return myconfirm('你确定删除这个友情链接吗？');">删除</a></td>
		</tr>
	<?php }?>
	</tbody>
	</table>
				<div class="btn">
				<input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="排序" />
			</div>
	<div id="pages">
	<?php echo $show;?>	
	</div>			
</div>
</form>
<script type="text/javascript">
function edit(id, name) {
	window.top.art.dialog.open('<?php echo U('edit');?>?id='+id,{title:name,width:550,height:150
	,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
/*
* 是否显示友情链接
*/
function change_display(obj,id)
{
	var val=$(obj).attr('data-gou');
	if(val==1)
	{
		$.post("<?php echo U('recommend');?>?id="+id,{'val':val},function(data){
			if(data=='success')
			{
				$(obj).attr('src','/Public/images/icon/gou_0.png');
				$(obj).attr('data-gou',0);
			}
		})
	}
	else if(val==0)
	{
		$.post("<?php echo U('recommend');?>?id="+id,{'val':val},function(data){
			if(data=='success')
			{
				$(obj).attr('src','/Public/images/icon/gou_1.png');
				$(obj).attr('data-gou',1);
			}
		})		
	}
}
</script>
</body>
</html>