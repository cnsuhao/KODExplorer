<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>在线编辑器</title>
	<link rel="Shortcut Icon" href="<?=STATIC_PATH;?>images/favicon.ico">
<!-- 	// <script src="http://libs.baidu.com/jquery/1.8.0/jquery.min.js"></script> -->
	<script src="<?=STATIC_PATH;?>js/jquery-1.8.0.min.js"></script>	
	<script src="<?=STATIC_PATH;?>js/artDialog/jquery.artDialog.js"></script>
	<script src="<?=STATIC_PATH;?>js/artDialog/iframeTools.js"></script>
	<script src="<?=STATIC_PATH;?>js/contextMenu/jquery.ui.position.js"></script>
	<script src="<?=STATIC_PATH;?>js/contextMenu/jquery.contextMenu.js"></script>
	<script src="<?=STATIC_PATH;?>js/common.js"></script>
	<script src="<?=STATIC_PATH;?>js/frameCall.js"></script> <!-- 跨窗口函数调用 -->
	<script src="<?=STATIC_PATH;?>js/ztree/js/jquery.ztree.all-3.5.min.js"></script>
	<link   href="<?=STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>
	<!-- 
	<link   href="<?=STATIC_PATH;?>style/skin/<?=$value['config']['theme'];?>/app_editor.css" rel="stylesheet" id='link_css_list'/>
	!-->

	<link rel="stylesheet/less" type="text/css" href="<?=STATIC_PATH;?>style/skin/<?=$value['config']['theme'];?>/app_editor.less">
	<script src="<?=STATIC_PATH;?>js/less-1.3.3.min.js"></script>
	
</head>

<script>
var app_path	= '<?=urlencode(APPHOST);?>';	///www/explorer/
var rootPath	= '<?=WEB_ROOT;?>';
var editPath	= app_path+'./';//ajax请求文件路径
</script>

<body onselectstart="return false" style="overflow:hidden;">
	<?php include(TEMPLATE.'common/navbar/index.html');?>
	<div class="frame-main">
		<div class='frame-left'>
			<div class="tools-left">						
		        <a id='newfolder' href="#" class="button"><i class="font-icon icon-folder-close-alt"></i>新建分类</a>		        
				<span class='msg'></span>
			</div>
			<ul id="folderList" class="ztree">
				<div class="loading " style="text-align:center;padding:20px;"><img src="./static/images/loading_content.gif"/></div>
			</ul>
		</div><!-- / frame-left end-->
		<div class='frame-resize'></div>
		<div class='frame-right'>
			<div class="frame-right-main">
				<div class="resizeMask"></div>
				<div class="messageBox menuTree" ><div class="content"></div></div>
				<div class ='frame'>
					 <iframe name="editor" src="?editor/edit" style="width:100%;height:100%;border:0;" ></iframe>
				</div>	
			</div>
		</div><!-- / frame-right end-->
	</div><!-- / frame-main end-->
<script src="<?=STATIC_PATH;?>js/app/edit/editor.js"></script>
</body>
</html>
