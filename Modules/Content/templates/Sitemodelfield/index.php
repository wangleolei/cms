<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();?>
<div class="pad-lr-10">
<form name="myform" action="<?php echo U('listorder',array('modelid'=>$r['modelid']));?>" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0" >
        <thead>
            <tr>
			 <th width="70">排序</th>
            <th width="90">字段名称</th>
			<th width="100">字段别名</th>
			<th width="100">字段类型</th>
			<th width="50">系统</th> 
            <th width="50">必填</th>
            <th width="50">搜索</th>
            <th width="50">排序</th>
            <th></th>
			<th width="120" align="center">操作</th>
            </tr>
        </thead>
    <tbody class="td-line">
	<?php
	foreach($datas as $r) {
		$tablename = L($r['tablename']);
	?>
    <tr>
		<td align='center' width='70'><input name='listorders[<?php echo $r['fieldid']?>]' type='text' size='3' value='<?php echo $r['listorder']?>' class='input-text-c'></td>
		<td width='90'><?php echo $r['field']?></td>
		<td width="100"><?php echo $r['name']?></td>
		<td width="100" align='center'><?php echo $r['formtype']?></td>
		<td width="50" align='center'><span class="red"><?php echo $r['issystem']?'√':'×';?></td>
		<td width="50" align='center'><span class="red"><?php echo $r['minlength']?'√':'×';?></td>
		<td width="50" align='center'><span class="red"><?php echo $r['issearch']?'√':'×';?></span></td>
		<td width="50" align='center'><span class="red"><?php echo $r['isorder']?'√':'×';?></span></td>
        <td></td>
		<td align='center'> <a href="<?php echo U('edit',array('modelid'=>$r['modelid'],'fieldid'=>$r['fieldid']));?>">编辑</a> | 
		
		<?php if(!in_array($r['field'],$forbid_fields)) { ?>
		<a href="<?php echo U('disabled',array('modelid'=>$r['modelid'],'fieldid'=>$r['fieldid'],'disabled'=>$r['disabled']));?>"><?php echo $r['disabled'] ? '启用' : '禁用';?></a>
		<?php } else { ?><font color="#BEBEBE"> 禁用 </font><?php } ?>|<?php if(!in_array($r['field'],$forbid_delete)) {?> 
		<a href="javascript:confirmurl('<?php echo U('delete',array('modelid'=>$r['modelid'],'fieldid'=>$r['fieldid']));?>','你确定删除该字段？')">删除</a><?php } else {?><font color="#BEBEBE"> 删除 </font><?php }?> </td>
	</tr>
	<?php } ?>
    </tbody>
    </table>
   <div class="btn"><input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="排序" /></div></div>
</form>
</div>
</body>
</html>
