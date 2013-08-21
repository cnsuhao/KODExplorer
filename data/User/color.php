<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="webpage">
<meta name="keywords" content="kalcaddle">
<meta name="author" content="kalcaddle.">
<title>test</title>
</head>

<style type="text/css">
	body{background:#f6f6f6;padding-top:20px;font-family:Arial,Sans;}
	#main{width:90%;margin:0 auto;border:6px solid #eee;}
	#contents{background:#fff;border:1px solid #ddd;padding:50px;}	
	
	a{color:#080;
	-webkit-transition: .15s ease-in-out;
    -moz-transition:.2s;text-shadow:1px 1px 3px #bcd;
    text-decoration:none;position:relative;
	}
	a:hover {top:10px;font-size:50px;}
</style>


<body>
<div id="main">
<div id="contents">
<?php

function randomColor() {
    $str = '#';
    for($i = 0 ; $i < 6 ; $i++) {
        $randNum = rand(0 , 15);
        switch ($randNum) {
            case 10: $randNum = 'a'; break;
            case 11: $randNum = 'b'; break;
            case 12: $randNum = 'c'; break;
            case 13: $randNum = 'd'; break;
            case 14: $randNum = 'e'; break;
            case 15: $randNum = 'f'; break;
        }
        $str .= $randNum;
    }
    return $str;
}

function array_reset(&$arr,$num) //数组随机打乱
{	
	for($z=0;$z<$num;$z++) //数组个数次
	{	
		$j=rand(0,$num);
		$k=rand(0,$num);
		echo $arr[$j];
		$temp=$arr[$j];
		$arr[$j]=$arr[$k];
		$arr[$k]=$temp;
	}
}

$num=70;//条数
$print=array($num);
for($i=0;$i<$num;$i++) 
{	
	if($i%10==0) //十分之一概率为25到40之间
	{	
		$print[$i]='<a href= "#" style="font-size:'.rand(30,60).'px;color:'.randomColor().'">color </a>';
	}
	else 
	{
		$print[$i]='<a href= "#" style="font-size:'.rand(13,30).'px;color:'.randomColor().'">color </a>';
	}	
}
array_reset($print,$num-1);
for($m=0;$m<$num;$m++) //输出
{echo $print[$m];}

?>
</div></div>

<div id="footer" style="border-bottom:1px solid #ddd;width:80%;margin:0 auto;margin-top:20px;margin-bottom:10px;"></div>
</body>
</html>

