<?php
/*
*使用JQUERY-EASTUI
*参数：theme =black|bootstrap|default|gray|metro
*中文网站:http://www.jeasyui.net/
*/
function load_easyui($theme='default')
{
	echo '<link rel="stylesheet" type="text/css" href="'.C('JS_PATH').'jquery-easyui/themes/'.$theme.'/easyui.css"><link rel="stylesheet" type="text/css" href="'.C('JS_PATH').'jquery-easyui/themes/icon.css"><script type="text/javascript" src="'.C('JS_PATH').'jquery-easyui/jquery.easyui.min.js"></script>';
}
/*
*加载数据验证js插件formvalidator
*手册：http://www.thinkerphp.com/Public/js/formvalidator/formvalidator4.1.3/demo/index.html
*/
function load_formvalidator()
{
	echo '<script language="javascript" type="text/javascript" src="'.C('JS_PATH').'formvalidator/formvalidator.js" charset="UTF-8"></script><script language="javascript" type="text/javascript" src="'.C('JS_PATH').'formvalidator/formvalidatorregex.js" charset="UTF-8"></script>';	
}
/*
*使用Ueditor
*官网：http://ueditor.baidu.com/website/index.html
*/
function load_ueditor()
{
	echo'<script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'ueditor/ueditor.config.js"></script><script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'ueditor/ueditor.all.min.js"> </script><script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'ueditor/lang/zh-cn/zh-cn.js"></script>';
}
/*
*使用calender
*官网：http://www.my97.net/dp/demo/resource/main.asp
*/
function load_calender()
{
	echo '<script type="text/javascript" charset="utf-8" src="'.C('JS_PATH').'calendar/WdatePicker.js"></script>';
}
/*
* 使用artdialog弹出窗口
* 手册：http://www.thinkerphp.com/Public/js/artDialog4/
*/
function load_artdialog($theme)
{
	$style=cookie('style');
	if(empty($theme))
	{
		if($style=='styles2')
		{
			$theme='black';
		}
		elseif($style=='styles3')
		{
			$theme='green';
		}
		elseif($style=='styles4')
		{
			$theme='chrome';
		}
		else
		{
			$theme='idialog';
		}		
	}	
	echo '<script src="'.C('JS_PATH').'artDialog4/jquery.artDialog.js?skin='.$theme.'"></script>
		  <script src="'.C('JS_PATH').'artDialog4/plugins/iframeTools.js"></script>';
}
/*
* 使用Validform弹出窗口
* 官网:http://validform.rjboy.cn/document.html
*/
function load_Validform()
{
		echo '<link href="'.C('JS_PATH').'Validform/theme/default.css" rel="stylesheet">
		<script src="'.C('JS_PATH').'Validform/Validform_v5.3.2_min.js"></script>';
}
/*
* boutton按钮样式库
*/
function load_buttons()
{
	echo '<link href="'.C('CSS_PATH').'buttons.css" rel="stylesheet">';
}
/*
*	创建输Ueditor入框
*/
function ueditor($id,$type='all')
{
	if(empty($name))$name=$id;
	
	if($type=='all')
	{
		echo "<script type=\"text/javascript\">
    $(function(){
        var ue = UE.getEditor('{$id}',{
            serverUrl :'".U('Plug/Public/ueditorUpload')."',toolbars:[[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'help', 'drafts'
        ]]
        });
    })
    </script>";
	}
        elseif($type=='simple')
        {
 		echo "<script type=\"text/javascript\">
    $(function(){
        var ue = UE.getEditor('{$id}',{
            serverUrl :'".U('Plug/Public/ueditorUpload')."',toolbars:[[\"fullscreen\",\"undo\",\"redo\",\"insertunorderedlist\",\"insertorderedlist\",\"unlink\",\"link\",\"|\",\"bold\",\"italic\",\"underline\",\"fontborder\",\"strikethrough\",\"forecolor\",\"backcolor\",\"superscript\",\"subscript\",\"justifyleft\",\"justifycenter\",\"justifyright\",\"justifyjustify\",\"inserttable\",\"deletetable\",\"mergeright\",\"mergedown\",\"splittorows\",\"splittocols\",\"splittocells\",\"mergecells\",\"insertcol\",\"insertrow\",\"deletecol\",\"deleterow\",\"insertparagraphbeforetable\",\"charts\"]]
        });
    })
    </script>";           
        }
}
?>