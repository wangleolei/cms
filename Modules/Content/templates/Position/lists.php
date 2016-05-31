<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<div class="pad-lr-10">
<form name="searchform" action="" method="get">
	<table width="100%" cellspacing="0" class="search-form">
    	<input type="hidden" name="p" value="1">
		<tbody>
			<tr>
				<td><div class="explain-col">
                                        内容模型：
				<select name="modelid" id="modelid">
            	<option value="0">全部</option>
                <?php foreach($models as $vo){?>
                <option value="<?php echo $vo['modelid'];?>" <?php echo ($vo['modelid']==$_GET['modelid'])?'selected':'';?>><?php echo $vo['name'];?></option>
                <?php }?>
            </select>
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
            <th width="100" align="left">id</th>
			
            <th  align="left" width="350">名称</th>
             <th  align="left" width="350">内容模型</th>
			<th width="100" align="left">状态</th>
			<th align="center"></th> 
			<th width="100" align="center">管理操作</th>
            </tr>
        </thead>
	<tbody>
	<?php foreach($list as $v){?>
		<tr>
			<td align='center'><input name='listorders[<?php echo $v['id'];?>]' type='text' size='3' value='<?php echo $v['listorder'];?>' class='input-text-c'></td>

			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['name'];?></td>
            <td><?php 
				foreach($models as $vo)
				{
					if($vo['modelid']==$v['modelid'])
					{
						echo $vo['name'];
						break;
					}
				}
			?></td>
			<td align="left";><span class="red"><?php echo $v['status']?'√':'×';?></span></td>
			<td align="center"></td>
			<td align="center"> <a href="javascript:void(0)" onClick="edit('<?php echo $v['id'];?>','修改当前推荐位')">修改</a> | <a href="<?php echo U('delete?id='.$v['id']);?>" onClick="return myconfirm('你确定删除这个推荐位吗？');">删除</a></td>
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
	window.top.art.dialog.open('<?php echo U('edit');?>?id='+id,{title:name,width:500,height:120
	,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
</script>
</body>
</html>