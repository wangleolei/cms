<?php if (!defined('THINK_PATH')) exit; include ($this->admin_tpl('/Admin/head'));load_formvalidator();load_ueditor();load_calender();load_artdialog();?>
<style type="text/css">
html {
	_overflow-y: scroll
}
</style>
<link href="/Public/js/swfupload/swfupload.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="/Public/js/swfupload/swfupload.js"></script>
<script language="JavaScript" type="text/javascript" src="/Public/js/swfupload/fileprogress.js"></script>
<script language="JavaScript" type="text/javascript" src="/Public/js/swfupload/handlers.js"></script>
<script type="text/javascript">
var swfu = '';
var params={"SWFUPLOADSESSID" : "<?php echo $swfuploadsessid?>",'swf_auth_key':'<?php echo $swf_auth_key;?>','user':'<?php echo session('userinfo.username');?>'};
		$(document).ready(function(){
		swfu = new SWFUpload({
			flash_url:"<?php echo C('JS_PATH');?>swfupload/swfupload.swf?"+Math.random(),
			upload_url:"<?php echo U('Plug/Input/swfUpload');?>",
			file_post_name : "Filedata",
			post_params: params,
			file_size_limit:"<?php echo C('UPLOAD_IMG_MAX_SIZE');?>",
			file_types:"*.gif;*.jpg;*.jpeg;*.png;*.bmp",
			file_types_description:"All Files",
			file_upload_limit:"10",
			custom_settings : {progressTarget : "fsUploadProgress",cancelButtonId : "btnCancel"},
	 		debug: false,
			button_image_url: "",
			button_width: 75,
			button_height: 28,
			button_placeholder_id: "buttonPlaceHolder",
			button_text_style: "",
			button_text_top_padding: 3,
			button_text_left_padding: 12,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,

			file_dialog_start_handler : fileDialogStart,
			file_queued_handler : fileQueued,
			file_queue_error_handler:fileQueueError,
			file_dialog_complete_handler:fileDialogComplete,
			upload_progress_handler:uploadProgress,
			upload_error_handler:uploadError,
			upload_success_handler:uploadSuccess,
			upload_complete_handler:uploadComplete
			});
		})</script>
<div class="pad-10">
<div class="col-tab">
	<ul class="tabBut cu-li">
		<li id="tab_swf_4" class="on" onclick="SwapTab('swf','on','',4,4);">我的图库</li>
		<li id="tab_swf_1"  onclick="SwapTab('swf','on','',4,1);">上传图片</li>
		<!--<li id="tab_swf_2" onclick="SwapTab('swf','on','',4,2);">网络文件</li>-->
		<!-- <li id="tab_swf_3" onclick="SwapTab('swf','on','',4,3);">目录浏览</li>-->
		
	</ul>
	<div id="div_swf_1" class="content pad-10 hidden">
		<div>
			<div class="addnew" id="addnew"> <span id="buttonPlaceHolder"></span> </div>
			<input type="button" id="btupload" value="开始上传" onClick="upload();" />
			<div id="nameTip" class="onShow">最多同时上传<font color="red"> 10</font> 张图片,单文件最大 <font color="red"><?php echo C('maxSize');?> KB</font></div>
			<div class="bk3"></div>
			<div class="lh24">支持 <font style="font-family: Arial, Helvetica, sans-serif">gif、jpg、jpeg、png、bmp</font> 格式,上传时按住shift键可同时选择多张图片。</div>
		</div>
          <form id="thumb_specs" name="thumb_specs">
           <fieldset class="" id="swfupload">
           <legend>选择缩略图规格</legend>
           	  <input type="checkbox" name="thumb_specs[]" value="135" checked onclick="return false"/> 135*135</label>
              <input type="checkbox" name="thumb_specs[]" value="400" checked onclick="return false"/> 400*400</label>
           	  <?php foreach($thumb_specs as $v){?>	
              <label>
                <input type="checkbox" name="thumb_specs[]" value="<?php echo $v;?>"/>
                <?php echo $v;?>*<?php echo $v;?></label>
              <?php }?>  
            </fieldset>
            </form>        
		<fieldset class="blue pad-10" id="swfupload">
			<legend>列表</legend>
			<ul class="attachment-list"  id="fsUploadProgress">
			</ul>
		</fieldset>
	</div>
	<!--<div id="div_swf_2" class="contentList pad-10 hidden">
		<div class="bk10"></div>
		请输入网络地址
		<div class="bk3"></div>
		<input id="infilename" type="text" name="info[filename]" class="input-text" value=""  style="width:350px;"  onblur="addonlinefile(this)">
		<div class="bk10"></div>
	</div>-->
	<!--      <div id="div_swf_3" class="contentList pad-10 hidden">
      </div>-->
	
	<div id="div_swf_4" class="contentList pad-10 ">
		<div>
			<form action="" method="post">
				<span style="padding-right:5px;">图片关键词搜索</span>
				<input name="imgk" type="text" value="<?php echo $_POST['imgk'];?>">
				<input class="button button-tkp button-tiny" type="submit" value="搜索" />
			</form>
		</div>
		<fieldset class="blue pad-10" id="swfupload">
			<legend>列表</legend>
			<ul class="attachment-list imgs-list">
				<?php foreach($imglist as $key=>$vo){?>
				<li>
					<div id="attachment_<?php echo $vo['id'];?>" class="img-wrap"><a href="javascript:;" onclick="javascript:att_cancel(this,<?php echo $vo['id'];?>,'upload')">
						<div class="icon"></div>
						<img src="<?php echo thumb_url($vo['url'],135,135);?>" width="80" style="max-height:80px;" imgid="63" path="<?php echo $vo['url'];?>" title="<?php echo $vo['title'];?>"></a>
						<div class="tit-img">
							<input class="img-title" value="<?php echo $vo['title'];?>" onblur="retitle(this)" data-id="<?php echo $vo['id'];?>">
						</div>
					</div>
				</li>
				<?php }?>
			</ul>
		</fieldset>
		<div class="imglist-page" id="pages" style="text-align:right;"> <?php echo $show;?> </div>
	</div>
	<div id="att-status" class="hidden"></div>
	<div id="att-status-del" class="hidden"></div>
	<div id="att-name" class="hidden"></div>
	<!-- swf --> 
</div>
<div style=" text-align:left;">
	<button id="getimg" style="display:none;" onclick="get_info()">使用选中图片</button>
</div>
<script type="text/javascript">
//修改图片的名称
function retitle(obj)
{
	var title=$(obj).val();
	var id =$(obj).attr('data-id');
	$.post("<?php echo U('Public/swfuploadRetitle');?>",{'id':id,'title':title},function (data){
		if(data!='')
		{
			window.top.art.dialog.alert(data);
		}
	})
}
function upload()
{
	var values=[];	
	var data=$("#thumb_specs").serializeArray();	
    $.each(data, function(i, field){
		values[i]=field.value;
    });
	params.thumb_specs=values.join();
	swfu.setPostParams(params);
	swfu.startUpload();
}
</script>
</body>
<script type="text/javascript">
/*
if ($.browser.mozilla) {
	window.onload=function(){
	  if (location.href.indexOf("&rand=")<0) {
			location.href=location.href+"&rand="+Math.random();
		}
	}
}
*/
function imgWrap(obj){
	$(obj).hasClass('on') ? $(obj).removeClass("on") : $(obj).addClass("on");
}

function SwapTab(name,cls_show,cls_hide,cnt,cur) {
    for(i=1;i<=cnt;i++){
		if(i==cur){
			 $('#div_'+name+'_'+i).show();
			 $('#tab_'+name+'_'+i).addClass(cls_show);
			 $('#tab_'+name+'_'+i).removeClass(cls_hide);
		}else{
			 $('#div_'+name+'_'+i).hide();
			 $('#tab_'+name+'_'+i).removeClass(cls_show);
			 $('#tab_'+name+'_'+i).addClass(cls_hide);
		}
	}
}

function addonlinefile(obj) {
	var strs = $(obj).val() ? '|'+ $(obj).val() :'';
	$('#att-status').html(strs);
}

function set_iframe(id,src){
	$("#"+id).attr("src",src); 
}
</script>
</html>
