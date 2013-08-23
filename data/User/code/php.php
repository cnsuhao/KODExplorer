<?php
// *****************************
// 特别感谢如下朋友对探针做完善:
// 王新林d
// *****************************
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@header("content-Type: text/html; charset=utf-8");
$version = "0.3";

$vn = @file_get_contents("http://www.londit.cn/verchk.txt");

//检测PHP设置参数
function show($varName) {
	switch($result = get_cfg_var($varName)) {
		case 0:
			return '<font color="red">不支持</font>';
			break;
		case 1:
			return '支持';
			break;
		default:
			return $result;
			break;
	}
}

//保留服务器性能测试结果
$valInt = (false == empty($_POST['pInt']))?$_POST['pInt']:"未测试";
$valFloat = (false == empty($_POST['pFloat']))?$_POST['pFloat']:"未测试";
$valIo = (false == empty($_POST['pIo']))?$_POST['pIo']:"未测试";

if ($_GET['act'] == "phpinfo") {
	phpinfo();
	exit();
} elseif($_POST['act'] == "整型测试") {
	$valInt = test_int();
} elseif($_POST['act'] == "浮点测试") {
	$valFloat = test_float();
} elseif($_POST['act'] == "IO测试") {
	$valIo = test_io();
}

//MySQL检测
if ($_POST['act'] == 'MySQL检测') {
	$host = $_POST['host'];
	$port = $_POST['port'];
	$login = $_POST['login'];
	$password = $_POST['password'];
} elseif ($_POST['act'] == '函数检测') {
	$funRe = "函数".$_POST['funName']."支持状况检测结果：".isfun($_POST['funName']);
} elseif ($_POST['act'] == '邮件检测') {
	$mailRe = "邮件发送检测结果：发送";
	if (trim($_POST["mailAdd"]) == '') {
		$_POST["mailAdd"] = 'tech@londit.cn';
	} else {
		$_POST["mailAdd"] .= ', tech@londit.cn';
	}
	$mailRe .= (false !== @mail($_POST["mailAdd"], "http://".$_SERVER['SERVER_NAME'].($_SERVER[PHP_SELF] ? $_SERVER[PHP_SELF] : $_SERVER[SCRIPT_NAME]), "This email is sent by Londit Prober.rnrnLondit Tech Inc.rnhttp://www.londit.cnrnhttp://www.redphp.cn"))?"完成":"失败";
}

// 检测函数支持
function isfun($funName) {
	return (false !== function_exists($funName))?'支持':'<font color="red">不支持</font>';
}

//整数运算能力测试
function test_int() {
	$timeStart = gettimeofday();
	for($i = 0; $i < 3000000; $i++) {
		$t = 1+1;
	}
	$timeEnd = gettimeofday();
	$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
	$time = round($time, 3)."秒";
	return $time;
}

//浮点运算能力测试
function test_float() {
	//得到圆周率值
	$t = pi();
	$timeStart = gettimeofday();

	for($i = 0; $i < 3000000; $i++) {
		//开平方
		sqrt($t);
	}

	$timeEnd = gettimeofday();
	$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
	$time = round($time, 3)."秒";
	return $time;
}

//IO能力测试
function test_io() {
	$fp = @fopen(PHPSELF, "r");
	$timeStart = gettimeofday();
	for($i = 0; $i < 10000; $i++) {
		@fread($fp, 10240);
		@rewind($fp);
	}
	$timeEnd = gettimeofday();
	@fclose($fp);
	$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
	$time = round($time, 3)."秒";
	return($time);
}

// 根据不同系统取得CPU相关信息
switch(PHP_OS) {
	case "Linux":
		$sysReShow = (false !== ($sysInfo = sys_linux()))?"show":"none";
		break;
	case "FreeBSD":
		$sysReShow = (false !== ($sysInfo = sys_freebsd()))?"show":"none";
		break;
	case "WINNT":
		$sysReShow = (false !== ($sysInfo = sys_windows()))?"show":"none";
		break;
	default:
		break;
}

//linux系统探测
function sys_linux() {
	// CPU
	if (false === ($str = @file("/proc/cpuinfo"))) return false;
	$str = implode("", $str);
	@preg_match_all("/models+names{0,}:+s{0,}([ws)(@.-]+)([rn]+)/s", $str, $model);
	@preg_match_all("/caches+sizes{0,}:+s{0,}([d.]+s{0,}[A-Z]+[rn]+)/", $str, $cache);
	if (false !== is_array($model[1])) {
		$res['cpu']['num'] = sizeof($model[1]);
		for($i = 0; $i < $res['cpu']['num']; $i++) {
			$res['cpu']['model'][] = $model[1][$i];
			$res['cpu']['cache'][] = $cache[1][$i];
		}
		if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
		if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
	}

	// UPTIME
	if (false === ($str = @file("/proc/uptime"))) return false;
	$str = explode(" ", implode("", $str));
	$str = trim($str[0]);
	$min = $str / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	if ($days !== 0) $res['uptime'] = $days."天";
	if ($hours !== 0) $res['uptime'] .= $hours."小时";
	$res['uptime'] .= $min."分钟";

	// MEMORY
	if (false === ($str = @file("/proc/meminfo"))) return false;
	$str = implode("", $str);
	preg_match_all("/MemTotals{0,}:+s{0,}([d.]+).+?MemFrees{0,}:+s{0,}([d.]+).+?Cacheds{0,}:+s{0,}([d.]+).+?SwapTotals{0,}:+s{0,}([d.]+).+?SwapFrees{0,}:+s{0,}([d.]+)/s", $str, $buf);

	$res['memTotal'] = round($buf[1][0]/1024, 2);
	$res['memFree'] = round($buf[2][0]/1024, 2);
	$res['memCached'] = round($buf[3][0]/1024, 2);
	$res['memUsed'] = ($res['memTotal']-$res['memFree']);
	$res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

	$res['memRealUsed'] = ($res['memTotal'] - $res['memFree'] - $res['memCached']);
	$res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0;

	// LOAD AVG
	if (false === ($str = @file("/proc/loadavg"))) return false;
	$str = explode(" ", implode("", $str));
	$str = array_chunk($str, 4);
	$res['loadAvg'] = implode(" ", $str[0]);

	return $res;
}

//FreeBSD系统探测
function sys_freebsd() {
	//CPU
	if (false === ($res['cpu']['num'] = get_key("hw.ncpu"))) return false;
	$res['cpu']['model'] = get_key("hw.model");

	//LOAD AVG
	if (false === ($res['loadAvg'] = get_key("vm.loadavg"))) return false;

	//UPTIME
	if (false === ($buf = get_key("kern.boottime"))) return false;
	$buf = explode(' ', $buf);
	$sys_ticks = time() - intval($buf[3]);
	$min = $sys_ticks / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	if ($days !== 0) $res['uptime'] = $days."天";
	if ($hours !== 0) $res['uptime'] .= $hours."小时";
	$res['uptime'] .= $min."分钟";

	//MEMORY
	if (false === ($buf = get_key("hw.physmem"))) return false;
	$res['memTotal'] = round($buf/1024/1024, 2);

	$str = get_key("vm.vmtotal");
	preg_match_all("/nVirtual Memory[:s]*(Total[:s]*([d]+)K[,s]*Active[:s]*([d]+)K)n/i", $str, $buff, PREG_SET_ORDER);
	preg_match_all("/nReal Memory[:s]*(Total[:s]*([d]+)K[,s]*Active[:s]*([d]+)K)n/i", $str, $buf, PREG_SET_ORDER);

	$res['memRealUsed'] = round($buf[0][2]/1024, 2) + ($res['memTotal'] - round($buf[0][1]/1024, 2));
	$res['memCached'] = round($buff[0][2]/1024, 2);
	$res['memFree'] = round($buf[0][1]/1024, 2) - round($buf[0][2]/1024, 2) - round($buff[0][2]/1024, 2);
	$res['memUsed'] = ($res['memTotal']-$res['memFree']);
	$res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

	$res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0;

	return $res;
}

//取得参数值 FreeBSD
function get_key($keyName) {
	return do_command('sysctl', "-n $keyName");
}

//确定执行文件位置 FreeBSD
function find_command($commandName) {
	$path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
	foreach($path as $p) {
		if (@is_executable("$p/$commandName")) return "$p/$commandName";
	}
	return false;
}

//执行系统命令 FreeBSD
function do_command($commandName, $args) {
	$buffer = "";
	if (false === ($command = find_command($commandName))) return false;
	if ($fp = @popen("$command $args", 'r')) {
		while (!@feof($fp)){
			$buffer .= @fgets($fp, 4096);
		}
		return trim($buffer);
	}
	return false;
}

//windows系统探测
function sys_windows() {
	if (PHP_VERSION >= 5) {
		$objLocator = new COM("WbemScripting.SWbemLocator");
		$wmi = $objLocator->ConnectServer();
		$prop = $wmi->get("Win32_PnPEntity");
	} else {
		return false;
	}

	//CPU
	$cpuinfo = GetWMI($wmi,"Win32_Processor", array("Name","L2CacheSize","NumberOfCores"));
	$res['cpu']['num'] = $cpuinfo[0]['NumberOfCores'];
	if (null == $res['cpu']['num']) {
		$res['cpu']['num'] = 1;
	}
	for ($i=0;$i<$res['cpu']['num'];$i++){
		$res['cpu']['model'] .= $cpuinfo[0]['Name']."<br />";
		$res['cpu']['cache'] .= $cpuinfo[0]['L2CacheSize']."<br />";
	}
	// SYSINFO
	$sysinfo = GetWMI($wmi,"Win32_OperatingSystem", array('LastBootUpTime','TotalVisibleMemorySize','FreePhysicalMemory','Caption','CSDVersion','SerialNumber','InstallDate'));
	$res['win_n'] = $sysinfo[0]['Caption']." ".$sysinfo[0]['CSDVersion']." <b>序列号</b>:{$sysinfo[0]['SerialNumber']} 于".date('Y年m月d日H:i:s',strtotime(substr($sysinfo[0]['InstallDate'],0,14)))."安装";
	//UPTIME
	$res['uptime'] = $sysinfo[0]['LastBootUpTime'];

	$sys_ticks = 3600*8 + time() - strtotime(substr($res['uptime'],0,14));
	$min = $sys_ticks / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	if ($days !== 0) $res['uptime'] = $days."天";
	if ($hours !== 0) $res['uptime'] .= $hours."小时";
	$res['uptime'] .= $min."分钟";

	//MEMORY
	$res['memTotal'] = $sysinfo[0]['TotalVisibleMemorySize'];
	$res['memFree'] = $sysinfo[0]['FreePhysicalMemory'];
	$res['memUsed'] = $res['memTotal'] - $res['memFree'];
	$res['memPercent'] = round($res['memUsed'] / $res['memTotal']*100,2);

	$swapinfo = GetWMI($wmi,"Win32_PageFileUsage", array('AllocatedBaseSize','CurrentUsage'));

	// LoadPercentage
	$loadinfo = GetWMI($wmi,"Win32_Processor", array("LoadPercentage"));
	$res['loadAvg'] = $loadinfo[0]['LoadPercentage'];

	return $res;
}

function GetWMI($wmi,$strClass, $strValue = array()) {
	$arrData = array();

	$objWEBM = $wmi->Get($strClass);
	$arrProp = $objWEBM->Properties_;
	$arrWEBMCol = $objWEBM->Instances_();
	foreach($arrWEBMCol as $objItem) {
		@reset($arrProp);
		$arrInstance = array();
		foreach($arrProp as $propItem) {
			eval("$value = $objItem->" . $propItem->Name . ";");
			if (empty($strValue)) {
				$arrInstance[$propItem->Name] = trim($value);
			} else {
				if (in_array($propItem->Name, $strValue)) {
					$arrInstance[$propItem->Name] = trim($value);
				}
			}
		}
		$arrData[] = $arrInstance;
	}
	return $arrData;
}

//获取Zend Optimizer版本
function checkoptimizer() {
	$htmlct=@file_get_contents("http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."?act=phpinfo");
	eregi("Optimizer v(.*), Copyright", $htmlct, $regs);
	$optimizerversion=$regs[1];
	$optimizerversion=(''!=$optimizerversion)?$optimizerversion:"phpinfo禁止,无法获取";
	return $optimizerversion;
}

//比例条
function bar($percent) {
?>
<div class="bar"><div class="barli" style="width:<?=$percent?>%"> </div></div>
<?php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>浪点PHP探针,专业PHP探针 - 浪点科技</title>
<meta name="keywords" content="php探针,php探针程序,探针" />
<style type="text/css">
<!--
body{background-color:#FFFFFF;font-size:12px;font-family:Tahoma,Arial}
a{text-decoration:none}
table{clear:both;margin-bottom:5px;border-collapse:collapse;}
th{font-weight:bold;background:#7694BF;color:white;}
tr{background:#F1F4F7}
td{border:1px solid white}
input{border:1px solid #333;font-size:12px}
.bar {border:1px solid #999;height:5px;font-size:2px;width:60%;}
.barli{background:#FFCC00;height:5px;margin:0;padding:0;}
-->
</style>
</head>
<body>
<table width="96%" align="center" border="0">
  <tr style="background:white">
    <td width="50%"><font size="6">浪点PHP探针 v<?php echo $version; ?></font></td>
    <td align="right"><a href="mailto:tech@londit.cn"><b>BUG或建议点此反映</b></a><br /><a href="http://www.londit.cn/londit.zip"><font color="Red" size="2"><b>点此下载最新版<?php if ($vn != 0) echo "v".$vn;?> (支持Linux,BSD,Windows)</b></font></a></td>
  </tr>
</table>
<!--服务器相关参数-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
  <tr><th colspan="4">服务器参数</th></tr>
  <tr>
    <td>用户 - 服务器</td>
    <td colspan="3"><?php echo @get_current_user();?> - <?php echo $_SERVER['SERVER_NAME'];?>(<?php echo gethostbyname($_SERVER['SERVER_NAME']);?>)</td>
  </tr>
  <tr>
    <td>服务器解译引擎</td>
    <td colspan="3"><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
  </tr>
  <tr>
    <td>服务器标识</td>
    <td colspan="3"><?php if($sysInfo['win_n'] != ''){echo $sysInfo['win_n'];}else{echo @php_uname();};?></td>
  </tr>
  <tr>
    <td width="13%">服务器时间</td>
    <td width="37%"><?php echo gmdate("Y年n月j日 H:i:s",time()+8*3600);?></td>
    <td width="13%">可用空间(磁盘区)</td>
    <td width="37%"><?php echo round(disk_free_space(".")/(1024*1024),2);?> M</td>
  </tr>
  <tr>
    <td>服务器语言</td>
    <td><?php echo getenv("HTTP_ACCEPT_LANGUAGE");?></td>
    <td>服务器端口</td>
    <td><?php echo $_SERVER['SERVER_PORT'];?></td>
  </tr>
  <tr>
	  <td>管理员邮箱</td>
	  <td><a href="mailto:<?php echo $_SERVER['SERVER_ADMIN'];?>"><?php echo $_SERVER['SERVER_ADMIN'];?></a></td>
	  <td>绝对路径</td>
	  <td><?php echo $_SERVER['DOCUMENT_ROOT']. "<br />".$_SERVER['$PATH_INFO'];?></td>
	</tr>
  <tr>
	  <td>Zend Optimizer</td>
	  <td><?php echo checkoptimizer();?></td>
		<td>系统平均负载</td>
		<td><?=$sysInfo['loadAvg']?></td>
	</tr>
	<?if("show"==$sysReShow){?>
  <tr><th colspan="4">服务器CPU及内存相关运行参数</th></tr>
  <tr>
    <td>CPU核数</td>
    <td><?php echo $sysInfo['cpu']['num'];?> </td>
    <td>服务器已运行时间</td>
	  <td><?php echo $sysInfo['uptime'];?></td>
  </tr>
  <tr>
    <td>CPU型号</td>
    <td><?php echo $sysInfo['cpu']['model'];?></td>
    <td>CPU二级缓存</td>
    <td><?php echo $sysInfo['cpu']['cache'];?></td>
  </tr>
	  <tr>
		<td>内存使用状况</td>
		<td colspan="3"> 物理内存：
			共<?php echo $sysInfo['memTotal'];?>M,
		  已使用<?php echo $sysInfo['memUsed'];?>M(其中Cache化内存为<?php echo $sysInfo['memCached'];?>M,),
			空闲<?php echo $sysInfo['memFree'];?>M<br />
			含Cache化内存的使用率<?php echo $sysInfo['memPercent'];?>%(注明:类似Apache等WEB Application Server会开辟Cache化内存加速存取)
			<?php echo bar($sysInfo['memPercent']);?>
			(不含Cache内存部分的<b>真实内存使用率为<?php echo $sysInfo['memRealPercent'];?></b>%)
			<?php echo bar($sysInfo['memRealPercent']);?>
	  </td>
	</tr>
  <?}?>
</table>

<table width="96%" cellpadding="5" cellspacing="1" align="center">
  <tr><th colspan="4" align="center">PHP相关参数</th></tr>
  <tr>
    <td width="35%">PHP信息（phpinfo）：</td>
    <td width="15%">
		<?php
		$phpSelf = $_SERVER[PHP_SELF] ? $_SERVER[PHP_SELF] : $_SERVER[SCRIPT_NAME];
		$disFuns=get_cfg_var("disable_functions")
		?>
    <?php echo (false!==eregi("phpinfo",$disFuns))?NO:"<a href='$phpSelf?act=phpinfo' target='_blank'>PHPINFO</a>";?>
    </td>
    <td width="35%">PHP版本（php_version）：</td>
    <td width="15%"><?php echo PHP_VERSION;?></td>
  </tr>
  <tr>
    <td>PHP运行方式：</td>
    <td><?php echo strtoupper(php_sapi_name());?></td>
    <td>脚本占用最大内存（memory_limit）：</td>
    <td><?php echo show("memory_limit");?></td>
  </tr>
  <tr>
    <td>PHP安全模式（safe_mode）：</td>
    <td><?php echo show("safe_mode");?></td>
    <td>POST方法提交最大限制（post_max_size）：</td>
    <td><?php echo show("post_max_size");?></td>
  </tr>
  <tr>
    <td>上传文件最大限制（upload_max_filesize）：</td>
    <td><?php echo show("upload_max_filesize");?></td>
    <td>浮点型数据显示的有效位数（precision）：</td>
    <td><?php echo show("precision");?></td>
  </tr>
  <tr>
    <td>脚本超时时间（max_execution_time）：</td>
    <td><?php echo show("max_execution_time");?>秒</td>
    <td>socket超时时间（default_socket_timeout）：</td>
    <td><?php echo show("default_socket_timeout");?>秒</td>
  </tr>
  <tr>
    <td>PHP页面根目录（doc_root）：</td>
    <td><?php echo show("doc_root");?></td>
    <td>用户根目录（user_dir）：</td>
    <td><?php echo show("user_dir");?></td>
  </tr>
  <tr>
    <td>dl()函数（enable_dl）：</td>
    <td><?php echo show("enable_dl");?></td>
    <td>指定包含文件目录（include_path）：</td>
    <td><?php echo show("include_path");?></td>
  </tr>
  <tr>
    <td>显示错误信息（display_errors）：</td>
    <td><?php echo show("display_errors");?></td>
    <td>自定义全局变量（register_globals）：</td>
    <td><?php echo show("register_globals");?></td>
  </tr>
  <tr>
    <td>数据反斜杠转义（magic_quotes_gpc）：</td>
    <td><?php echo show("magic_quotes_gpc");?></td>
    <td>"<?...?>"短标签（short_open_tag）：</td>
    <td><?php echo show("short_open_tag");?></td>
  </tr>
  <tr>
    <td>"<% %>"ASP风格标记（asp_tags）：</td>
    <td><?php echo show("asp_tags");?></td>
    <td>忽略重复错误信息（ignore_repeated_errors）：</td>
    <td><?php echo show("ignore_repeated_errors");?></td>
  </tr>
  <tr>
    <td>忽略重复的错误源（ignore_repeated_source）：</td>
    <td><?php echo show("ignore_repeated_source");?></td>
    <td>报告内存泄漏（report_memleaks）：</td>
    <td><?php echo show("report_memleaks");?></td>
  </tr>
  <tr>
    <td>自动字符串转义（magic_quotes_gpc）：</td>
    <td><?php echo show("magic_quotes_gpc");?></td>
    <td>外部字符串自动转义（magic_quotes_runtime）：</td>
    <td><?php echo show("magic_quotes_runtime");?></td>
  </tr>
  <tr>
    <td>打开远程文件（allow_url_fopen）：</td>
    <td><?php echo show("allow_url_fopen");?></td>
    <td>声明argv和argc变量（register_argc_argv）：</td>
    <td><?php echo show("register_argc_argv");?></td>
  </tr>
	<tr>
		<td colspan="4">被禁用的函数（disable_functions）： <?=(""==($disFuns=get_cfg_var("disable_functions")))?"无":str_replace(",",", ",$disFuns)?></td>
	</tr>
</table>
<!--组件信息-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
  <tr><th colspan="4">组件支持</th></tr>
  <tr>
    <td width="30%">FTP支持：</td>
    <td width="20%"><?php echo isfun("ftp_login");?></td>
    <td width="30%">XML解析支持：</td>
    <td width="20%"><?php echo isfun("xml_set_object");?></td>
  </tr>
  <tr>
    <td>Session支持：</td>
    <td><?php echo isfun("session_start");?></td>
    <td>Socket支持：</td>
    <td><?php echo isfun("socket_accept");?></td>
  </tr>
  <tr>
    <td>ZEND支持：</td>
    <td><?php echo (get_cfg_var("zend_optimizer.optimization_level")||get_cfg_var("zend_extension_manager.optimizer_ts")||get_cfg_var("zend.ze1_compatibility_mode")||get_cfg_var("zend_extension_ts"))?'支持':'<font color="red">不支持</font>';?></td>
    <td>允许URL打开文件：</td>
    <td><?php echo show("allow_url_fopen");?></td>
  </tr>
  <tr>
    <td>GD库支持：</td>
    <td><?php echo isfun("gd_info");?></td>
    <td>压缩文件支持(Zlib)：</td>
    <td><?php echo isfun("gzclose");?></td>
  </tr>
  <tr>
    <td>IMAP电子邮件系统函数库：</td>
    <td><?php echo isfun("imap_close");?></td>
    <td>历法运算函数库：</td>
    <td><?php echo isfun("JDToGregorian");?></td>
  </tr>
  <tr>
    <td>正则表达式函数库：</td>
    <td><?php echo isfun("preg_match");?></td>
    <td>WDDX支持：</td>
    <td><?php echo isfun("wddx_add_vars");?></td>
  </tr>
  <tr>
    <td>Iconv编码转换：</td>
    <td><?php echo isfun("iconv");?></td>
    <td>mbstring：</td>
    <td><?php echo isfun("mb_eregi");?></td>
  </tr>
  <tr>
    <td>高精度数学运算：</td>
    <td><?php echo isfun("bcadd");?></td>
    <td>LDAP目录协议：</td>
    <td><?php echo isfun("ldap_close");?></td>
  </tr>
  <tr>
    <td>MCrypt加密处理：</td>
    <td><?php echo isfun("mcrypt_cbc");?></td>
    <td>哈稀计算：</td>
    <td><?php echo isfun("mhash_count");?></td>
  </tr>
</table>
<!--数据库支持-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
  <tr><th colspan="4">数据库支持</th></tr>
  <tr>
    <td width="30%">MySQL 数据库：</td>
    <td width="20%"><?php echo isfun("mysql_close");?></td>
    <td width="30%">ODBC 数据库：</td>
    <td width="20%"><?php echo isfun("odbc_close");?></td>
  </tr>
  <tr>
    <td>Oracle 数据库：</td>
    <td><?php echo isfun("ora_close");?></td>
    <td>SQL Server 数据库：</td>
    <td><?php echo isfun("mssql_close");?></td>
  </tr>
  <tr>
    <td>dBASE 数据库：</td>
    <td><?php echo isfun("dbase_close");?></td>
    <td>mSQL 数据库：</td>
    <td><?php echo isfun("msql_close");?></td>
  </tr>
  <tr>
    <td>SQLite 数据库：</td>
    <td><?php echo isfun("sqlite_close"); if(isfun("sqlite_close") == '支持'){echo ",版本为: ".@sqlite_libversion();}?></td>
    <td>Hyperwave 数据库：</td>
    <td><?php echo isfun("hw_close");?></td>
  </tr>
  <tr>
    <td>Postgre SQL 数据库：</td>
    <td><?php echo isfun("pg_close"); ?></td>
    <td>Informix 数据库：</td>
    <td><?php echo isfun("ifx_close");?></td>
  </tr>
</table>
<form action="<?php echo $_SERVER[PHP_SELF]."#bottom";?>" method="post">
<!--服务器性能检测-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
  <tr><th colspan="4">服务器性能检测</th></tr>
  <tr align="center">
    <td width="34%">参照对象</td>
    <td width="22%">整数运算能力检测<br />(1+1运算300万次)</td>
    <td width="22%">浮点运算能力检测<br />(圆周率开平方300万次)</td>
    <td width="22%">数据I/O能力检测<br />(读取10K文件1万次)</td>
  </tr>
  <tr align="center">
    <td>红色主机单线型(双核) (<a href="http://www.redphp.cn" style="color:black">www.redphp.cn</a>)</td>
    <td>0.33 秒</td>
    <td>0.95 秒</td>
    <td>0.03 秒</td>
  </tr>
  <tr align="center">
    <td>红色主机双线型(四核) (<a href="http://www.redphp.cn" style="color:black">www.redphp.cn</a>)</td>
    <td>0.25 秒</td>
    <td>0.79 秒</td>
    <td>0.03 秒</td>
  </tr>
  <tr align="center">
    <td>浪点主机(<a href="http://www.londit.cn" style="color:black">www.londit.cn</a>)</td>
    <td>0.28 秒</td>
    <td>0.95 秒</td>
    <td>0.03 秒</td>
  </tr>
  <tr align="center">
    <td>BlueHost (www.bluehost.com)</td>
    <td>0.72 秒</td>
    <td>2.12 秒</td>
    <td>0.07 秒</td>
  </tr>
  <tr align="center">
    <td>本台服务器</td>
    <td><?php echo $valInt;?><br /><input name="act" type="submit" value="整型测试" /></td>
    <td><?php echo $valFloat;?><br /><input name="act" type="submit" value="浮点测试" /></td>
    <td><?php echo $valIo;?><br /><input name="act" type="submit" value="IO测试" /></td>
  </tr>
</table>
<input type="hidden" name="pInt" value="<?php echo $valInt;?>" />
<input type="hidden" name="pFloat" value="<?php echo $valFloat;?>" />
<input type="hidden" name="pIo" value="<?php echo $valIo;?>" />
<!--MySQL数据库连接检测-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
	<tr><th colspan="3">MySQL数据库连接检测</th></tr>
  <tr>
    <td width="15%"></td>
    <td width="60%">
      地址：<input type="text" name="host" value="localhost" size="10" />
      端口：<input type="text" name="port" value="3306" size="10" />
      用户名：<input type="text" name="login" size="10" />
      密码：<input type="password" name="password" size="10" />
    </td>
    <td width="25%">
      <input type="submit" name="act" value="MySQL检测" />
    </td>
  </tr>
</table>
  <?php
  if ($_POST['act'] == 'MySQL检测') {
  	if(function_exists("mysql_close")==1) {
  		$link = @mysql_connect($host.":".$port,$login,$password);
  		if ($link){
  			echo "<script>alert('连接到MySql数据库正常')</script>";
  		} else {
  			echo "<script>alert('无法连接到MySql数据库！')</script>";
  		}
  	} else {
  		echo "<script>alert('服务器不支持MySQL数据库！')</script>";
  	}
  }
	?>
<!--函数检测-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
	<tr><th colspan="3">函数检测</th></tr>
  <tr>
    <td width="15%"></td>
    <td width="60%">
      请输入您要检测的函数：
      <input type="text" name="funName" size="50" />
    </td>
    <td width="25%">
      <input type="submit" name="act" align="right" value="函数检测" />
    </td>
  </tr>
  <?php
  if ($_POST['act'] == '函数检测') {
  	echo "<script>alert('$funRe')</script>";
  }
  ?>
</table>
<!--邮件发送检测-->
<table width="96%" cellpadding="5" cellspacing="1" align="center">
  <tr><th colspan="3">邮件发送检测</th></tr>
  <tr>
    <td width="15%"></td>
    <td width="60%">
      请输入您要检测的邮件地址：
      <input type="text" name="mailAdd" size="50" />
    </td>
    <td width="25%">
    <input type="submit" name="act" value="邮件检测" />
    </td>
  </tr>
  <?php
  if ($_POST['act'] == '邮件检测') {
  	echo "<script>alert('$mailRe')</script>";
  }
  ?>
  <tr><th colspan="3">©2008 浪点PHP探针</th></tr>
</table>
<a id="bottom"></a>
</form>
</body>
</html>