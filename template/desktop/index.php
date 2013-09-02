<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Cloud Explorer——云。在线资源管理</title>
	<link rel="Shortcut Icon" href="<?php echo STATIC_PATH;?>images/favicon.ico">    
	<script src="<?php echo STATIC_PATH;?>js/jquery-1.8.0.min.js"></script>	
    <script src="<?php echo STATIC_PATH;?>js/common.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/artDialog/jquery.artDialog.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/contextMenu/jquery.ui.position.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/contextMenu/jquery.contextMenu.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/cmp4/cmp.js"></script>
	<script src="<?php echo STATIC_PATH;?>js/picasa/picasa.js"></script>	

	<link   href="<?php echo STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>
	<link   href="<?php echo STATIC_PATH;?>js/picasa/style/style.css" rel="stylesheet"/>

	<?php if(STATIC_LESS == 'css'){ ?>
	<link href="<?php echo STATIC_PATH;?>style/skin/<?php echo $value['config']['theme'];?>/app_desktop.css" rel="stylesheet" id='link_css_list'/>
	<?php }else{//less_compare_online ?>
	<link rel="stylesheet/less" type="text/css" href="<?php echo STATIC_PATH;?>style/skin/<?php echo $value['config']['theme'];?>/app_desktop.css"/>
	<script src="<?php echo STATIC_PATH;?>js/less-1.4.2.min.js"></script>	
	<?php } ?>
</head>


<script>
var web_host	= '<?php echo HOST;?>';// localhost 访问根目录
var this_path	= '<?php echo DESKTOP;?>';
var WEB_ROOT	= '<?php echo WEB_ROOT;?>';//D:/wwwroot/ 服务器路径 用于api更新列表情况下保证web_path的正确性.
var web_path	= '<?php echo urlencode(str_replace(WEB_ROOT,'', DESKTOP));?>';
var app_path	= '<?php echo urlencode(APPHOST);?>';	///www/explorer/  程序路径，用于静态资源调用
var json_data	= '';			//用于存储每次获取列表后的json数据值。
var json_sort_field = '<?php echo $value['config']['list_sort_field'];?>'; //列表排序依照的字段  
var json_sort_order = '<?php echo $value['config']['list_sort_order'];?>'; //列表排序升序or降序
var static_path		= '<?php echo STATIC_PATH;?>';
</script>

<style type="text/css" media="screen">
.desktop{
    background:#222 url('<?php echo STATIC_PATH;?>images/wall_page/<?php echo $value['config']['wall'];?>.jpg');
    -moz-background-size: 100% 100%;
    -o-background-size: 100% 100%;
    -webkit-background-size: 100% 100%;
    background-size: 100% 100%;
}
</style>

<body onselectstart="return false" style="overflow: hidden;">
    <?php include(TEMPLATE.'common/navbar/index.html');?>
	<div class='bodymain html5_drag_upload_box desktop'>
        <div class="messageBox"><div class="content"></div></div>
		<div class="fileContiner">
            <div class="loading " style="
            text-align:center;padding:20px;position:absolute;left:50%;top:40%;
            "><img src="./static/images/loading_content.gif"/></div>
        </div>
	</div><!-- html5拖拽上传list -->


   <div id="taskbar">
        <a href="#" class="start"></a>
        <div id="desktop"></div>
    </div>
    <div id="menuwin">
        <div id="startmenu"></div>
        <ul id="programs">
            <li><a href="#">Internet Explorer</a></li>
            <li><a href="#">Microsoft Media Center</a></li>
            <li><div id="leftspliter"></div></li>
            <li><a href="#">Microsoft Word 2010</a></li>
            <li><a href="#">Microsoft Excel 2010</a></li>
            <li><a href="#">Microsoft PowerPoint 2010</a></li>
            <li><a href="#">Microsoft Access 2010</a></li>
            <li><a href="#">Windows Update</a></li>
            <li><div id="leftspliter"></div></li>
            <li><a href="#">All Programs</a></li>
            <li class="search"></li>
        </ul>
        <ul id="links">
            <li class="icon"></li>
            <li><a href="#" onclick="Main.PathOpen.openComputer('?explorer','我的文档');"><span>我的文档</span></a></li>
            <li><a href="#" onclick="Main.PathOpen.openComputer('?explorer','我的图片');"><span>我的图片</span></a></li>
            <li><a href="#" onclick="Main.PathOpen.openComputer('?explorer','我的音乐');"><span>我的音乐</span></a></li>
            <li><a href="#" onclick="Main.PathOpen.openComputer('?explorer','下载');"><span>下载</span></a></li>
            <li><div id="rightspliter"></div></li>
            <li><a href="#" onclick="Main.Common.setting('wall');"><span>设置壁纸</span></a></li>
            <li><a href="#" onclick="Main.Common.setting('fav');"><span>收藏夹管理</span></a></li>
            <li><a href="#" onclick="Main.Common.setting('theme');"><span>设置主题</span></a></li>
            <li><a href="?user/logout" style="margin-top:70px;"><span>退出></span></a></li>            
        </ul>
    </div>
<script src="<?php echo STATIC_PATH;?>js/app/common/taskTap.js"></script>
<script src="<?php echo STATIC_PATH;?>js/app/common/CMPlayer.js"></script>
<script src="<?php echo STATIC_PATH;?>js/app/common/common.js"></script>

<script src="<?php echo STATIC_PATH;?>js/app/desktop/main.js"></script>
<script src="<?php echo STATIC_PATH;?>js/app/desktop/rightMenu.js"></script>
<script src="<?php echo STATIC_PATH;?>js/app/desktop/pathOperate.js"></script>
<script src="<?php echo STATIC_PATH;?>js/app/desktop/fileSelect.js"></script>
</body>
</html>
