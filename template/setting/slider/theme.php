<div class='h1'><i class="font-icon icon-dashboard"></i>主题设置</div>
<div class="section">
	<div class='theme box'>

	<?php 
		$tpl="<div class='{this} list'><div class='ico' theme='{0}'><img src='./static/images/thumb/theme/{0}.png'/></div><div class='info'>{1}</div></div>";
		echo getTplList('/','=',$this->config['themeall'],$tpl,$this->config['theme']);
	?>
	<div style="clear:both;"></div>
	</div>
</div>

<div class="savebox">
<a onclick="tools();" href="javascript:void(0);" class="save button">保存</a>
</div>