	<?
session_start();
if(!isset($_SESSION['key']))
{
	$_SESSION['key']=rand(1,100);
}
$num=$_GET['num'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>php猜数字游戏 </title>
  <meta name="author" content="kalcaddle-824691958" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 </head>
 <body onload="document.getElementById('guesstext').focus();">
 <div style="margin:auto;margin-top:50px;width:350px;height:180px;border:5px solid #ddf;text-align:center;padding:10px;color:#336;font-size:14px;">
 <div style="font-size:20px;margin:20px;color:#f88;"> 
 <?
 if(isset($_SESSION['key'])&&$num!=null)
 {
	 if($_SESSION['key']==$num)
	 {
		 session_destroy();
		 echo "恭喜你，猜中了,<br/>程序已经重新生成数字，可以继续";
	 }
	 if($_SESSION['key']>$num)
	 {echo "你猜的数字小了";}
	 if($_SESSION['key']<$num)
	 {echo "你猜的数字大了";}
 }
 if(isset($_SESSION['key'])&&$num==null)
	{echo "请输入数字";}
 ?>
 </div>
 <form action="guess.php" method="get">
 请输入数字：<input type="text" name="num" id='guesstext'/>
 <input type="submit" value="猜数字"/>
 </div>
 </body>
</html>  	