<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="webpage">
<meta name="keywords" content="kalcaddle">
<meta name="author" content="kalcaddle.">
  <head>
<!-- 	// <script src="http://libs.baidu.com/jquery/1.8.0/jquery.min.js"></script> -->
	<script src="<?=STATIC_PATH;?>js/jquery-1.8.0.min.js"></script>	
	<script src="<?=STATIC_PATH;?>js/artDialog/jquery.artDialog.js"></script>
	<script src="<?=STATIC_PATH;?>js/artDialog/iframeTools.js"></script>
	<script src="<?=STATIC_PATH;?>js/common.js"></script>
	<script src="<?=STATIC_PATH;?>js/frameCall.js"></script> <!-- 跨窗口函数调用 -->

    <link href="<?=STATIC_PATH;?>js/codemirror/lib/codemirror.css" rel="stylesheet" >
    <script src="<?=STATIC_PATH;?>js/codemirror/lib/codemirror.js"></script>
    <script src="<?=STATIC_PATH;?>js/codemirror/self/zen_codemirror.min.js"></script><!--zendCoding-->
    <script src="<?=STATIC_PATH;?>js/codemirror/addon/selection/active-line.js"></script>
    <script src="<?=STATIC_PATH;?>js/codemirror/addon/edit/matchbrackets.js"></script>
    <script src="<?=STATIC_PATH;?>js/codemirror/addon/mode/loadmode.js"></script>

	<!-- 搜索 -->
    <script src="<?=STATIC_PATH;?>js/codemirror/addon/search/searchcursor.js"></script>
    <script src="<?=STATIC_PATH;?>js/codemirror/self/my_search.js"></script>
	<link href="<?=STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>
	<link href="<?=STATIC_PATH;?>js/codemirror/theme/all.css" rel="stylesheet" >
	<!-- 
	<link href="./static/style/skin/<?=$this->config['theme'];?>/app_code_edit.css" rel="stylesheet" id='link_css_list'/>
	!-->
    <link href="<?=STATIC_PATH;?>style/skin/<?=$this->config['theme'];?>/app_code_edit.less" rel="stylesheet/less" type="text/css" >
	<script src="<?=STATIC_PATH;?>js/less-1.3.3.min.js"></script>
  </head>
  <script>
	var app_path		= "<?php echo APPHOST;?>";
	var frist_file		= "<?=$_GET['filename'];?>";
	var codetheme 		= "<?php echo $this->config['codetheme'];?>";
	CodeMirror.modeURL	= "<?=STATIC_PATH;?>js/codemirror/mode/%N/%N.js";
  </script>


  <body onselectstart="return false">
	<div class="edit_main">
		<div class="edit_tab">
			<div class="tabs">
				<a  href="javascript:Main.Editor.add()" class="add icon-plus"></a>
				<div style="clear:both"></div>
			</div>
		</div>
		<div class="tools">
			<div class="left">
				<a class="save" href="#" title='保存'><i class="font-icon icon-save"></i></a>
				<a class="saveall" href="#" title='全部保存'><i class="font-icon icon-paste"></i></a>
				<span class="line"></span>
				<a class="pre" href="#" title="撤销"><i class="font-icon icon-undo"></i></a>
				<a class="next" href="#" title="反撤销"><i class="font-icon icon-repeat"></i></a>
				<span class="line"></span>
				<a class="find" href="#" title="查找"><i class="font-icon icon-search"></i></a>
				<a class="gotoline" href="#" title="定位到N行"><i class="font-icon icon-pushpin"></i></a>
				<span class="line"></span>
				<a class="font" href="#" title="字体大小">
					<i class="font-icon icon-font"></i>字体<i class="font-icon icon-caret-down"></i>
				</a>		
				<a class="codetheme" href="#" title="代码风格">
				<i class="font-icon icon-magic"></i>代码风格<i class="font-icon icon-caret-down"></i>
				</a>

				<span class="line"></span>
				<a class="wordbreak" href="#" title="自动换行"><i class="font-icon icon-level-down"></i></a>
				<a class="tabbeautify" href="#" title="tab对齐"><i class="font-icon icon-indent-left"></i></a>
				<span class="line"></span>
				<a class="about" href="#" title="关于"><i class="font-icon icon-info"></i></a>
			</div>
			<div class="right">
				<a class="max" href="#" title="全屏"><i class="font-icon icon-fullscreen"></i></a>
				<a class="close" href="#" title="关闭"><i class="font-icon icon-remove"></i></a>
			</div>
			<div style="clear:both"></div>
		</div><!-- end tools -->
		<ul id="fontsize" class="dropbox">
			<li>12px</li><li>13px</li><li>14px</li><li>16px</li>
			<li>18px</li><li>24px</li><li>28px</li><li>32px</li>		
		</ul>
		<ul id="codetheme" class="dropbox">
		<?php 
			$tpl="<li class='{this} list' theme='{0}'>{0}</li>\n";
			echo getTplList('/','=',$this->config['codethemeall'],$tpl,$this->config['codetheme']);
		?>
		</ul>
		<div class="edit_body">
			<div class="tabs"></div>
		</div>
	</div>
	<script src="<?=STATIC_PATH;?>js/app/edit/edit.js"></script>
</body>
</html>
