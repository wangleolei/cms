<fieldset style="margin-top:10px;" class="small-block" id="block_<?php echo $id;?>">
	<div class="del-block-btn" onClick="del_small_block(<?php echo $id;?>,this)"></div>
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>
			<tr style="height:40px;">
				<th width="80"><a class="btn-addimg"  href='javascript:void(0);' onclick="blockid=<?php echo $id;?>;flashupload(crert_str);return false;">添加内容</a></th>
				<td><?php echo $id;?>号区块名称：
					<input type="text" value=""  class="input-text" size="40" name="block[<?php echo $id;?>][title]"/></td>
			</tr>
			<tr>
				<td colspan="2">
					<ul class="img-set">
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>
