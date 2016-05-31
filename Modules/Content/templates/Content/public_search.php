<?php foreach($categorys as $vo){$modelid=trim($vo['modelids'],',');if(is_numeric($modelid)){?>
<tr>
  <th scope="row"><?php echo $vo['name'];?></th>
  <td width="80"><a href="<?php echo U('lists',array('modelid'=>$modelid,'catid'=>$vo['id']));?>">内容列表</a></td>
  <td width="80"><a href="<?php echo U('add',array('modelid'=>$modelid,'catid'=>$vo['id']));?>" target="_blank">添加内容</a></td>
</tr>
<?php }}?> 