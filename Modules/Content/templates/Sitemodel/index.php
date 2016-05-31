<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<div class="pad-lr-10">
<div class="table-list">
    <table width="100%" cellspacing="0" >
        <thead>
            <tr>
			 <th width="100">模型id</th>
            <th width="150">模型名称</th>
			<th width="150">数据表名</th>
            <th align="left">描述</th>
            <th width="100">状态</th>
			<th width="250">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php
	foreach($datas as $r) {
		$tablename = $r['name'];
	?>
    <tr>
		<td align='center'><?php echo $r['modelid']?></td>
		<td align='center'><?php echo $tablename?></td>
		<td align='center'><?php echo $r['tablename']?></td>
		<td align='left'>&nbsp;<?php echo $r['description']?></td>
		<td align='center'><span class="red"><?php echo $r['disabled']?'×':'√';?></span></td>
		<td align='center'><a href="<?php echo U('Sitemodelfield/index',array('modelid'=>$r['modelid']));?>">字段管理</a> | <a href="javascript:edit('<?php echo $r['modelid']?>','<?php echo addslashes($tablename);?>')">修改</a> | <a href="<?php echo U('disabled',array('modelid'=>$r['modelid']));?>"><?php echo $r['disabled'] ? '启用' : '禁用';?></a> | <a href="javascript:;" onclick="model_delete(this,'<?php echo $r['modelid']?>','你确定删除该模型，这样数据将无法恢复？')">删除</a> | <a href="<?php echo U('export',array('modelid'=>$r['modelid']));?>">导出</a></td>
	</tr>
	<?php } ?>
    </tbody>
    </table>
   <div id="pages"><?php echo $pages;?>
  </div>
</div>
<script type="text/javascript"> 
<!--
function edit(id, name) {
	window.top.art.dialog.open('<?php echo U('edit');?>?modelid='+id,{title:'修改模型《'+name+'》',width:580,height:170,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
function model_delete(obj,id,name){
	window.top.art.dialog({content:name, fixed:true, style:'confirm', id:'model_delete'}, 
	function(){
	$.get('<?php echo U('delete');?>?modelid='+id,function(data){
				if(data) {
					$(obj).parent().parent().fadeOut("slow");
				}
			}) 	
		 }, 
	function(){});
};
//-->
</script>
</body>
</html>
