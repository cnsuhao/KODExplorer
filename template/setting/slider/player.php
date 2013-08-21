<div class='h1'><i class="font-icon icon-music"></i>音乐播放器设置</div>
<div class="section">
	<div class='music box'>
	<?php 
		$tpl="<div class='{this} list'><div class='ico'><img src='./static/images/thumb/music/{0}.jpg'/></div><div class='info'>{0}</div></div>";
		echo getTplList('/','=',$this->config['musicthemeall'],$tpl,$this->config['musictheme']);
	?>
	<div style="clear:both;"></div>
	</div>
</div>
<div class='h1'>视频播放器设置</div>
<div class="section">
	<div class='movie box'>
		<?php 
			$tpl="<div class='{this} list'><div class='ico'><img src='./static/images/thumb/movie/{0}.jpg'/></div><div class='info'>{0}</div></div>";
			echo getTplList('/','=',$this->config['moviethemeall'],$tpl,$this->config['movietheme']);
		?>
		<div style="clear:both;"></div>
	</div>	
</div>
<div class="savebox">
<a onclick="tools();" href="javascript:void(0);" class="save button">保存</a>
</div>