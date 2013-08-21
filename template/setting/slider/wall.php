<div class='h1'><i class="font-icon icon-picture"></i>设置壁纸</div>
<div class="section">
	<div class='wall box'>
	<?php 
		$tpl="<div class='{this} list'><div class='ico' wall='{0}'><img src='./static/images/wall_page/thumb/{0}.jpg'/></div><div class='info'>{1}</div></div>";
		echo getTplList('/','=',$this->config['wallall'],$tpl,$this->config['wall']);
	?>
	<div style="clear:both;"></div>
	</div>
</div>

<div class="savebox">
<a onclick="tools();" href="javascript:void(0);" class="save button">保存</a>
</div>