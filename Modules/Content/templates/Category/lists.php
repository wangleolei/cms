<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<form name="myform" action="<?php echo U('listorder')?>" method="post">
	<div class="pad-lr-10">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th width="80">排序</th>
						<th width="100" align="left">id</th>
						<th  align="left" width="350">名称</th>
						<th width="100" align="left">状态</th>
                        <th width="100" align="center">可见</th>
						<th align="center">附加属性</th>
						<th width="210" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($list as $v){?>
					<tr>
						<td align='center'><input name='listorders[<?php echo $v['id'];?>]' type='text' size='3' value='<?php echo $v['listorder'];?>' class='input-text-c'></td>
						<td><?php echo $v['id'];?></td>
						<td><?php echo $v['name'];?></td>
						<td align="left";><span class="red"><?php echo $v['status']?'√':'×';?></span></td>
                        <td align="left";><span class="red"><?php echo $v['display']?'√':'×';?></span></td>
						<td align="center"><?php
			foreach($this->category_type as $ko=>$vo)
			{
				if(preg_match("/{$ko},/",$v['type']))
				{
					echo "<a href='javascript:{$vo}({$v['id']})'>{$vo}</a> ";
				}
			}
			?></td>
						<td align="center"><?php if($v['child']==1){?>
							<a href="<?php echo U('lists',array('parentid'=>$v['id']))?>" onClick="flash_ifram('<?php echo U('lists',array('parentid'=>$v['id']))?>')">管理子栏目</a> |
							<?php }?>
							<a href="javascript:void(0)"  onClick="addchild('<?php echo $v['id'];?>','添加子栏目')">添加子栏目</a> | <a href="javascript:void(0)" onClick="edit('<?php echo $v['id'];?>','修改当前栏目')">修改</a> | <a href="<?php echo U('Content/Category/delete/id/'.$v['id']);?>" onClick="return myconfirm('你确定删除这个栏目吗？');">删除</a></td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			<div class="btn">
				<input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="排序" />
			</div>
		</div>
		<div id="pages"> <?php echo $show;?> </div>
	</div>
</form>
<script type="text/javascript">
function edit(id, name) {
	window.top.art.dialog.open('<?php echo U('edit');?>?id='+id,{title:name,width:700,height:500
	,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
function addchild(id, name) {
	window.top.art.dialog.open('<?php echo U('add');?>?id='+id,{title:name,width:700,height:500,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
function SEO(id)
{
	window.top.art.dialog.open('<?php echo U('seo');?>?id='+id,{title:'SEO 设置',width:550,height:210
	,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
function flash_ifram(url)
{
	var obj=window.top.document;
	$(obj).find("#rightMain").attr('src',url);
}
</script>
</body></html>