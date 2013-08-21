<?php
	date_default_timezone_set('PRC');
	setcookie("date", date("Y/m/d H:i:s"), time() + 3600 * 24 * 5);
	header("Content-type: text/html; charset=utf-8");

	echo end(explode('#','ost/www/explorer/?setting#adfasdf'));
	echo "<body>上次访问时间为" . $_COOKIE['date'];

	$dir = "./";
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		if ($file != "." && $file != ".." && $file!="index.php") {
			echo '<li><a href="' . $file . '">' . $file . '</a></li>';
		} 
	}
	closedir($dh);
?>
<style>
body{padding-left:100px;font-family:"微软雅黑";font-weight:200;}
li{list-style-type:circle;font-size:18px;line-height:22px;}
</style>
