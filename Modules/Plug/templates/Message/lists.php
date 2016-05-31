<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));echo $this->creatSubmenu();;load_calender();?>
<div class="pad-lr-10">
<form name="searchform" action="" method="get">
  <table width="100%" cellspacing="0" class="search-form">
    	<input type="hidden" name="p" value="1">
    <tbody>
      <tr>
        <td><div class="explain-col"> 留言时间：
            <input type="text" name="begin_time" id="begin_time" onClick="WdatePicker()" value="<?php echo $_GET['begin_time'];?>" size="15" class="Wdate" readonly>
            -&nbsp;
            <input type="text" name="end_time" id="end_time" onClick="WdatePicker()" value="<?php echo empty($_GET['end_time'])?date('Y-m-d'):$_GET['end_time'];?>" size="15" class="Wdate" readonly>
            <input name="keyword" type="text" value="<?php echo $_GET['keyword'];?>" class="input-text search-text">
            <select name="type" class="search-select">
              <option value="1" <?php if($_GET['type']==1)echo 'selected';?>>来源</option>
              <option value="2" <?php if($_GET['type']==2)echo 'selected';?>>留言人姓名</option>
            </select>
            <input type="submit" name="search" class="button button-tkp button-tiny" value="提 交">
          </div></td>
      </tr>
    </tbody>
  </table>
</form>
<form name="myform" action="<?php echo U('deleteSelect')?>" method="post">
  <div class="table-list">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <th  align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>
          <th width="50" align="left">id</th>
          <th  align="left">主题</th>
          <th width="150" align="left">留言来源</th>
          <th width="80" align="left">姓名</th>
          <th width="150">留言时间</th>
          <th width="100">联系电话</th>
          <th width="50">状态</th>
          <th width="100" align="center">管理操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($list as $v){?>
        <tr>
          <td align="left"><input type="checkbox" value="<?php echo $v['id'];?>" name="ids[]"></td>
          <td><?php echo $v['id'];?></td>
          <td><?php echo $v['title'];?></td>
          <td align="left"><?php echo $v['url'];?></td>
          <td align="left"><?php echo $v['name']; ?></td>
          <td align="center"><?php echo date('Y-m-d H:i',$v['time']);?></td>
          <td align="center"><?php echo $v['phone'];?></td>
          <td align="center"><?php echo $this->stauts[$v['status']];?></td>
          <td align="center"><a href="javascript:void(0)" onClick="view('<?php echo $v['id'];?>','<?php echo $v['title'];?>')" target="_blank">回答</a> | <a href="<?php echo U('Plug/Message/delete/id/'.$v['id']);?>" onClick="return myconfirm('你确定删除该留言？');">删除</a></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
    <div class="btn">
      <input type="submit" class="button button-tkp button-tiny" name="dosubmit" value="删除" />
    </div>
    <div id="pages"> <?php echo $show;?> </div>
  </div>
</form>
<script type="text/javascript">
<!--
function view(id, name) {
	window.top.art.dialog.open('<?php echo U('view');?>?id='+id,{title:name+'--留言内容',width:600,height:200
	,lock:true,ok:function(){var iframe = this.iframe.contentWindow;var form = iframe.document.getElementById('dosubmit');form.click();return false;},cancel:true});
}
//-->
</script>
</body>
</html>
