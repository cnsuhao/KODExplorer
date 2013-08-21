<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Cloud Explorer——云。在线资源管理</title>
	<link rel="Shortcut Icon" href="<?=STATIC_PATH;?>/images/favicon.ico">

<!-- 	// <script src="http://libs.baidu.com/jquery/1.8.0/jquery.min.js"></script> -->
	<script src="<?=STATIC_PATH;?>js/jquery-1.8.0.min.js"></script>	
	<script src="<?=STATIC_PATH;?>js/artDialog/jquery.artDialog.js"></script>
	<script src="<?=STATIC_PATH;?>js/artDialog/iframeTools.js"></script>
	<script src="<?=STATIC_PATH;?>js/contextMenu/jquery.ui.position.js"></script>
	<script src="<?=STATIC_PATH;?>js/contextMenu/jquery.contextMenu.js"></script>

	<script src="<?=STATIC_PATH;?>js/common.js"></script>
	<script src="<?=STATIC_PATH;?>js/frameCall.js"></script>
	<script src="<?=STATIC_PATH;?>js/ztree/js/jquery.ztree.all-3.5.min.js"></script>

	<link   href="<?=STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>
	<!-- <link  href="<?=STATIC_PATH;?>style/skin/<?=$value['config']['theme'];?>/app_explorer.css" rel="stylesheet" id='link_css_list'/> -->

	<link rel="stylesheet/less" type="text/css" href="<?=STATIC_PATH;?>style/skin/<?=$value['config']['theme'];?>/app_node.less">
	<script src="<?=STATIC_PATH;?>js/less-1.3.3.min.js"></script>
</head>


<script>
var HOME    = '<?=HOME;?>';//$dir为初次进入或者刷新浏览器后的当前目录。
var web_host= '<?=HOST;?>';// localhost 访问根目录
var this_path='<?=urlencode($value["dir"]);?>';//D:/wwwroot/www/explorer/0000/当前绝对路径
var WEB_ROOT='<?=WEB_ROOT;?>';//D:/wwwroot/ 服务器路径 用于api更新列表情况下保证web_path的正确性.
var web_path='<?=urlencode(str_replace(WEB_ROOT,'',$value["dir"]));?>';// 当前url目录,从根目录开始到当前 用于文件打开 

var json_data = '';			//用于存储每次获取列表后的json数据值。
var app_path='<?=urlencode(APPHOST);?>';	///www/explorer/  程序路径，用于静态资源调用
var list_type='<?=$value['config']['list'];?>';		//文件列表显示方式 list/icon
var list_theme='<?=$value['config']['theme'];?>';	//文件列表主题
var json_sort_field = '<?=$value['config']['list_sort_field'];?>'; //列表排序依照的字段  
var json_sort_order = '<?=$value['config']['list_sort_order'];?>';	//列表排序升序or降序
var static_path = "<?=STATIC_PATH;?>";
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


		<div class='frame-middle'>
			<div class="tools-left">						
		        <a id='newfile' href="#" class="button right"><i class="font-icon icon-file-alt"></i>新建笔记</a>
			</div>
			<ul id="folderList" class="ztree">
				<div class="loading " style="text-align:center;padding:20px;"><img src="./static/images/loading_content.gif"/></div>
			</ul>			
		</div>


		<div class='frame-right'>
			<div class="frame-right-main">
				<div class="messageBox"><div class="content"></div></div>				
				<div class="tools">
					<div id='yarnball'></div>
					<div class="tools-right">
						<a id='set_icon' href="#" class="button left" title="显示图标模式"><i class="font-icon icon-th"></i></a>
						<a id='set_list' href="#" class="button middle" title="显示列表模式"><i class="font-icon icon-list"></i></a>
						<a id="set_theme" href="#" class="button right" title="更换主题"><i class="font-icon icon-dashboard"></i></a>
					</div>
					<div style="clear:both"></div>
				</div><!-- end tools -->

				<ul id="theme_list" class="theme_list">
					<?php 
						$tpl="<li class='list {this}' theme='{0}'>{1}</li>\n";
						echo getTplList('/','=',$value['config']['themeall'],$tpl,$value['config']['theme'],'this');
					?>
				</ul>

				<div id='list_type_list'></div><!-- list type 列表排序方式 -->
				<div class='bodymain'>
					<div class="fileContiner"></div>
				</div><!-- html5拖拽上传list -->
			</div>
		</div><!-- / frame-right end-->
	</div><!-- / frame-main end-->
<script src="<?=STATIC_PATH;?>js/app/note/main.js"></script>
<script src="<?=STATIC_PATH;?>js/app/note/rightMenu.js"></script>
<script src="<?=STATIC_PATH;?>js/app/note/pathOperate.js"></script>
<script src="<?=STATIC_PATH;?>js/app/note/fileSelect.js"></script>
</body>
</html>
