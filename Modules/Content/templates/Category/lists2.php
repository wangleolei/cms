<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<form name="myform" action="<?php echo U('listorder')?>" method="post">
	<div class="pad-lr-10">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th width="80">排序</th>
						<th width="100">id</th>
						<th  align="left">栏目名称</th>
						<th width="50" align="center">启用</th>
                        <th width="50" align="center">显示</th>
						<th align="center">附加属性</th>                        
						<th width="180">管理操作</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $categorys;?>
				</tbody>
			</table>
			<div class="btn">
				<input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="排序" />
			</div>
		</div>
	</div>
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
</body>
</html>