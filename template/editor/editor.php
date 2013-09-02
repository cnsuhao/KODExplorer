<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>在线编辑器</title>
	<link rel="Shortcut Icon" href="<?php echo STATIC_PATH;?>images/favicon.ico">	
	<script src="<?php echo STATIC_PATH;?>js/jquery-1.8.0.min.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/common.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/artDialog/jquery.artDialog.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/contextMenu/jquery.ui.position.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/contextMenu/jquery.contextMenu.js"></script>
	
	<script src="<?php echo STATIC_PATH;?>js/ztree/js/jquery.ztree.all-3.5.min.js"></script>
	<link   href="<?php echo STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>

	<?php if(STATIC_LESS == 'css'){ ?>
	<link href="<?php echo STATIC_PATH;?>style/skin/<?php echo $value['config']['theme'];?>/app_editor.css" rel="stylesheet" id='link_css_list'/>
	<?php }else{//less_compare_online ?>
	<link rel="stylesheet/less" type="text/css" href="<?php echo STATIC_PATH;?>style/skin/<?php echo $value['config']['theme'];?>/app_editor.css"/>
	<script src="<?php echo STATIC_PATH;?>js/less-1.4.2.min.js"></script>	
	<?php } ?>
</head>

<script>
var web_host	= '<?php echo HOST;?>';// localhost 访问根目录
var app_path	= '<?php echo urlencode(APPHOST);?>';	///www/explorer/
var rootPath	= '<?php echo WEB_ROOT;?>';
var editPath	= app_path+'./';//ajax请求文件路径
var static_path = "<?php echo STATIC_PATH;?>";
</script>

<body onselectstart="return false" style="overflow:hidden;">
	<?php include(TEMPLATE.'common/navbar/index.html');?>
	<div class="frame-main">
		<div class='frame-left'>
			<div class="tools-left">
				<a class="home" href="#"  title='根目录'><i class="icon-home"></i></a>
				<a class="view" href="#"  title='管理目录'><i class="icon-laptop"></i></a>
				<a class="folder" href="#" title='新建文件夹'><i class="icon-folder-close-alt"></i></a>
				<a class="file" href="#" title='新建txt'><i class="icon-file-alt"></i></a>		
				<a class="refresh" href="#" title='刷新'><i class="icon-refresh"></i></a>		 
			</div>
			<ul id="folderList" class="ztree">
				<div class="loading " style="text-align:center;padding:20px;"><img src="./static/images/loading_content.gif"/></div>
			</ul>
		</div><!-- / frame-left end-->
		<div class='frame-resize'></div>
		<div class='frame-right'>
			<div class="frame-right-main"  style="height:99%;padding:0;margin:0;">
				<div class="resizeMask"></div>
				<div class="messageBox"><div class="content"></div></div>
				<div class="menuTreeRoot"></div>
				<div class="menuTreeFolder"></div>
				<div class="menuTreeFile"></div>				
				<div class ='frame'>
					 <iframe name="OpenopenEditor" src="?editor/edit" style="width:100%;height:100%;border:0;" frameborder=0></iframe>
				</div>	
			</div>
		</div><!-- / frame-right end-->
	</div><!-- / frame-main end-->
<script src="<?php echo STATIC_PATH;?>js/app/common/common.js"></script>
<script src="<?php echo STATIC_PATH;?>js/app/edit/editor.js"></script>
</body>
</html>
