<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  menu="menubody">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!-- 	// <script src="http://libs.baidu.com/jquery/1.8.0/jquery.min.js"></script> -->
	<script src="<?=STATIC_PATH;?>js/jquery-1.8.0.min.js"></script>	
	<script src="<?=STATIC_PATH;?>js/artDialog/jquery.artDialog.js"></script>
	<script src="<?=STATIC_PATH;?>js/frameCall.js"></script> <!-- 跨窗口函数调用 -->
	<link   href="<?=STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>

	<?php if(USE_NO_LESS){?>
	<link  href="<?=STATIC_PATH;?>style/skin/<?=$value['config']['theme'];?>/app_setting.css" rel="stylesheet" id='link_css_list'/>
	<?php }else{?>	
	<link rel="stylesheet/less" type="text/css" href="<?=STATIC_PATH;?>style/skin/<?=$value['config']['theme'];?>/app_setting.less">
	<script src="<?=STATIC_PATH;?>js/less-1.3.3.min.js"></script>
	<?php }?>

</head>

<script type="text/javascript">
	var setting=location.hash.split("#", 2)[1];//首次进入定位。
	var favAddName="<?=$_GET['name'];?>";//添加收藏夹
	var favAddPath="<?=$_GET['path'];?>";
	var static_path = "<?=STATIC_PATH;?>";
</script>

<body>
	<?php include(TEMPLATE.'common/navbar/index.html');?>
	<div id="body">
		<div class="menu_left">	
			<h1>选项</h1>
			<ul class='setting'>
				<li id="user"><i class="font-icon icon-user"></i>用户设置</li>
				<li id="theme"><i class="font-icon icon-dashboard"></i>主题切换</li>	
				<li id="wall"><i class="font-icon icon-picture"></i>更换壁纸</li>
				<li id="fav"><i class="font-icon icon-star"></i>收藏夹管理</li>
				<li id="editer"><i class="font-icon icon-edit"></i>编辑器设置</li>
				<li id="player"><i class="font-icon icon-music"></i>播放器</li>	
				<li id="help"><i class="font-icon icon-question"></i>帮助</li>
				<li id="update"><i class="font-icon icon-upload-alt"></i>更新</li>
				<li id="about"><i class="font-icon icon-info-sign"></i>关于</li>
			</ul>
		</div>		
		<div class='main'></div>
	</div>
</body>
<script src="<?=STATIC_PATH;?>js/app/setting.js"></script>
</html>