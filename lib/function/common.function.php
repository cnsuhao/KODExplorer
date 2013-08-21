<?php

/**
 * 检查常量是否定义过,参数必须为包含常量名的字符串
 * @param string $name 
 * @return string value
 */
function D($name)
{
	return defined($name) ? constant($name) : null ;
} 

/**
 * 加载类，从class目录；controller；model目录中寻找class
 */
function _autoload($className)
{
	if (file_exists(CLASS_DIR . strtolower($className) . '.class.php')) {
		require_once(CLASS_DIR . strtolower($className) . '.class.php');
	} else if (file_exists(CONTROLLER_DIR . strtolower($className) . '.class.php')) {
		require_once(CONTROLLER_DIR . strtolower($className) . '.class.php');
	} else if (file_exists(MODEl_DIR . strtolower($className) . '.class.php')) {
		require_once(MODEl_DIR . strtolower($className) . '.class.php');
	} else {
		// error code;
	} 
}
/**
 * 生产model对象
 */
function init_model($model_name)
{
	if (!class_exists($model_name.'Model')) {
		$model_file = MODEL_DIR . strtolower($model_name) . 'Model.class.php';
		require_once ($model_file);
		
		if(!is_file($model_file)){
			return false;
		}
	}
	$reflectionObj = new ReflectionClass($model_name.'Model');
	$args = func_get_args();
	array_shift($args);
	return $reflectionObj -> newInstanceArgs($args);
}
/**
 * 生产controller对象
 */
function init_controller($controller_name)
{
	if (!class_exists($controller_name)) {
		$model_file = CONTROLLER_DIR . strtolower($controller_name) . '.class.php';
		if(!is_file($model_file)){
			return false;
		}
		require_once ($model_file);
	}
	$reflectionObj = new ReflectionClass($controller_name);
	$args = func_get_args();
	array_shift($args);
	return $reflectionObj -> newInstanceArgs($args);
}

/**
 * 加载类
 */
function load_class($class){
	$filename = CLASS_DIR.$class.'.class.php';
	if (file_exists($filename)) {
		require($filename);
	}else{
		pr($filename.' is not exist',1);
	}
}
/**
 * 加载函数库
 */
function load_function($function){
	$filename = FUNCTION_DIR.$function.'.function.php';
	if (file_exists($filename)) {
		require($filename);
	}else{
		pr($filename.' is not exist',1);
	}
}


/**
 * 文本字符串转换
 */
function mystr($str)
{
	$from = array("\r\n", " ");
	$to = array("<br/>", "&nbsp");
	return str_replace($from, $to, $str);
} 

// 清除多余空格和回车字符
function strip($str)
{
	return preg_replace('!\s+!', '', $str);
} 

/**
 * 过滤HTML
 */
function clear_html($HTML, $br = true)
{
	$HTML = htmlspecialchars(trim($HTML));
	$HTML = str_replace("\t", ' ', $HTML);
	if ($br) {
		return nl2br($HTML);
	} else {
		return str_replace("\n", '', $HTML);
	} 
} 

/**
 * 将obj深度转化成array
 * 
 * @param  $obj 要转换的数据 可能是数组 也可能是个对象 还可能是一般数据类型
 * @return array || 一般数据类型
 */
function obj2array($obj)
{
	if (is_array($obj)) {
		foreach($obj as &$value) {
			$value = obj2array($value);
		} 
		return $obj;
	} elseif (is_object($obj)) {
		$obj = get_object_vars($obj);
		return obj2array($obj);
	} else {
		return $obj;
	} 
} 

/**
 * 计算时间差
 * 
 * @param char $pretime 
 * @return char 
 */
function spend_time(&$pretime)
{
	$now = microtime(1);
	$spend = round($now - $pretime, 5);
	$pretime = $now;
	return $spend;
} 

/**
 * 返回当前浮点式的时间,单位秒;主要用在调试程序程序时间时用
 * 
 * @return float 
 */
function microtime_float()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}
/**
 * 计算N次方根
 * @param  $num 
 * @param  $root 
 */
function croot($num, $root = 3)
{
	$root = intval($root);
	if (!$root) {
		return $num;
	} 
	return exp(log($num) / $root);
} 

/**
 * 去掉魔术引用,格式化字符串
 */
if (! function_exists('stripslashes_deep')) {
	function stripslashes_deep($value)
	{
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	} 
} 
/**
 * 格式化字符串;
 */
if (! function_exists('addslashes_deep')) {
	function addslashes_deep($value)
	{
		return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
	} 
} 

/**
 * 递归的给数组执行 htmlspecialchars()
 */
if (!function_exists('htmlspecialchars_deep')) {
	function htmlspecialchars_deep ($arr)
	{
		if (is_array($arr)) {
			foreach ($arr as &$val) {
				$val = htmlspecialchars_deep($val) ;
			} 
		} else {
			$arr = htmlspecialchars($arr) ;
		} 
		return $arr ;
	} 
} 
if (!function_exists("htmlspecialchars_decode")) {
	function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT)
	{
		return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
	} 
}

function add_magic_quotes($array)
{
	foreach ((array) $array as $k => $v) {
		if (is_array($v)) {
			$array[$k] = add_magic_quotes($v);
		} else {
			$array[$k] = addslashes($v);
		} 
	} 
	return $array;
} 
// 字符串加转义
function add_slashes($string)
{
	if (!$GLOBALS['magic_quotes_gpc']) {
		if (is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = add_slashes($val);
			} 
		} else {
			$string = addslashes($string);
		} 
	} 
	return $string;
} 

/**
 * hex to binary
 */
if (!function_exists('hex2bin')) {
	function hex2bin($hexdata)
	{
		return pack('H*', $hexdata);
	}
}

/**
 * 二维数组按照指定的键值进行排序，
 * 
 * @param  $keys 根据键值
 * @param  $type 升序降序
 * @return array $array = array(
 * array('name'=>'手机','brand'=>'诺基亚','price'=>1050),
 * array('name'=>'笔记本电脑','brand'=>'lenovo','price'=>4300),
 * array('name'=>'手表','brand'=>'卡西欧','price'=>960)
 * );$out = array_sort($array,'price');
 */
function array_sort($arr, $keys, $type = 'asc')
{
	$keysvalue = $new_array = array();
	foreach ($arr as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	} 
	if ($type == 'asc') {
		asort($keysvalue);
	} else {
		arsort($keysvalue);
	} 
	reset($keysvalue);
	foreach ($keysvalue as $k => $v) {
		$new_array[$k] = $arr[$k];
	} 
	return $new_array;
} 
/**
 * 遍历数组，对每个元素调用 $callback，假如返回值不为假值，则直接返回该返回值；
 * 假如每次 $callback 都返回假值，最终返回 false
 * 
 * @param  $array 
 * @param  $callback 
 * @return mixed 
 */
function array_try($array, $callback)
{
	if (!$array || !$callback) {
		return false;
	} 
	$args = func_get_args();
	array_shift($args);
	array_shift($args);
	if (!$args) {
		$args = array();
	} 
	foreach($array as $v) {
		$params = $args;
		array_unshift($params, $v);
		$x = call_user_func_array($callback, $params);
		if ($x) {
			return $x;
		} 
	} 
	return false;
} 
// 求多个数组的并集
function array_union()
{
	$argsCount = func_num_args();
	if ($argsCount < 2) {
		return false;
	} else if (2 === $argsCount) {
		list($arr1, $arr2) = func_get_args();

		while ((list($k, $v) = each($arr2))) {
			if (!in_array($v, $arr1)) $arr1[] = $v;
		} 
		return $arr1;
	} else { // 三个以上的数组合并
		$arg_list = func_get_args();
		$all = call_user_func_array('array_union', $arg_list);
		return array_union($arg_list[0], $all);
	} 
}
// 取出数组中第n项
function array_get($arr,$index){
   foreach($arr as $k=>$v){
       $index--;
       if($index<0) return array($k,$v);
   }
}


/**
 * 打包返回AJAX请求的数据
 * @params {int} 返回状态码， 通常0表示正常
 * @params {array} 返回的数据集合
 */
function ajax_return($result, $data)
{
	@header('Content-Type: application/json');
	if (is_array($data)) {
		$data = json_encode($data, true);
	} 
	echo json_encode(array('result' => $result, 'data' => $data), true);
} 


/**
 * 简单模版转换，用于根据配置获取列表：
 * 参数：cute1:第一次切割的字符串，cute2第二次切割的字符串,
 * arraylist为待处理的字符串，$this 为标记当前项，$this_str为当项标记的替换。
 * $tpl为处理后填充到静态模版({0}代表切割后左值,{1}代表切割后右值,{this}代表当前项填充值)
 * 例子：
 * $arr="default=淡蓝(默认)=5|mac=mac海洋=6|mac1=mac1海洋=7";
 * $tpl="<li class='list {this}' theme='{0}'>{1}_{2}</li>\n";
 * echo getTplList('|','=',$arr,$tpl,'mac'),'<br/>';
 */
function getTplList($cute1, $cute2, $arraylist, $tpl,$this,$this_str='')
{
	$list = explode($cute1, $arraylist);
	if ($this_str == '') $this_str ="this";
	$html = '';
	foreach ($list as $value) {
		$info = explode($cute2, $value);
		$arr_replace = array();	
		foreach ($info as $key => $value) {
			$arr_replace[$key]='{'.$key .'}';
		}
		if ($info[0] == $this) {
			$temp = str_replace($arr_replace, $info, $tpl);
			$temp = str_replace('{this}', $this_str, $temp);
		} else {
			$temp = str_replace($arr_replace, $info, $tpl);
			$temp = str_replace('{this}', '', $temp);
		}
		$html .= $temp;
	} 
	return $html;
} 
/**
 * 去掉HTML代码中的HTML标签，返回纯文本
 * @param string $document 待处理的字符串
 * @return string 
 */
function html2txt($document)
{
	$search = array ("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
		"'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
		"'([\r\n])[\s]+'", // 去掉空白字符
		"'&(quot|#34);'i", // 替换 HTML 实体
		"'&(amp|#38);'i",
		"'&(lt|#60);'i",
		"'&(gt|#62);'i",
		"'&(nbsp|#160);'i",
		"'&(iexcl|#161);'i",
		"'&(cent|#162);'i",
		"'&(pound|#163);'i",
		"'&(copy|#169);'i",
		"'&#(\d+);'e"); // 作为 PHP 代码运行
	$replace = array ("",
		"",
		"",
		"\"",
		"&",
		"<",
		">",
		" ",
		chr(161),
		chr(162),
		chr(163),
		chr(169),
		"chr(\\1)");
	$text = preg_replace ($search, $replace, $document);
	return $text;
} 

// 获取内容第一条
function match($content, $preg)
{
	$preg = "/" . $preg . "/isU";
	preg_match($preg, $content, $result);
	return $result[1];
} 
// 获取内容,获取一个页面若干信息.结果在 1,2,3……中
function match_all($content, $preg)
{
	$preg = "/" . $preg . "/isU";
	preg_match_all($preg, $content, $result);
	return $result;
} 

/**
 * 字符串截取，支持中文和其他编码
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string 
 */
function m_substr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
	if (function_exists("mb_substr"))
		return mb_substr($str, $start, $length, $charset);
	elseif (function_exists('iconv_substr')) {
		return iconv_substr($str, $start, $length, $charset);
	} 
	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("", array_slice($match[0], $start, $length));
	if ($suffix) {
		return $slice . "…";
	} 
	return $slice;
} 

/**
 * 获取指定长度的 utf8 字符串
 * 
 * @param string $string 
 * @param int $length 
 * @param string $dot 
 * @return string 
 */
function get_utf8_str($string, $length, $dot = '...')
{
	if (strlen($string) <= $length) return $string;

	$strcut = '';
	$n = $tn = $noc = 0;

	while ($n < strlen($string)) {
		$t = ord($string[$n]);
		if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$tn = 1;
			$n++;
			$noc++;
		} elseif (194 <= $t && $t <= 223) {
			$tn = 2;
			$n += 2;
			$noc += 2;
		} elseif (224 <= $t && $t <= 239) {
			$tn = 3;
			$n += 3;
			$noc += 2;
		} elseif (240 <= $t && $t <= 247) {
			$tn = 4;
			$n += 4;
			$noc += 2;
		} elseif (248 <= $t && $t <= 251) {
			$tn = 5;
			$n += 5;
			$noc += 2;
		} elseif ($t == 252 || $t == 253) {
			$tn = 6;
			$n += 6;
			$noc += 2;
		} else {
			$n++;
		} 
		if ($noc >= $length) break;
	} 
	if ($noc > $length) {
		$n -= $tn;
	} 
	if ($n < strlen($string)) {
		$strcut = substr($string, 0, $n);
		return $strcut . $dot;
	} else {
		return $string ;
	} 
} 

/**
 * 字符串截取，支持中文和其他编码
 * 
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string 
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
	if (function_exists("mb_substr")) {
		$i_str_len = mb_strlen($str);
		$s_sub_str = mb_substr($str, $start, $length, $charset);
		if ($length >= $i_str_len) {
			return $s_sub_str;
		} 
		return $s_sub_str . '...';
	} elseif (function_exists('iconv_substr')) {
		return iconv_substr($str, $start, $length, $charset);
	} 
	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("", array_slice($match[0], $start, $length));
	if ($suffix) return $slice . "…";
	return $slice;
} 
// 高亮关键字
function height_color($Content, $KeyWords, $Num = '')
{
	$HighLightKeyWords = '<b style="font-weight:800;color:f44;">' . $KeyWords . '</b>';
	$Content = str_replace($KeyWords, $HighLightKeyWords, $Content);
	if (!empty($Num)) {
		$Content = msubstr($Content, $Num);
	} 
	return $Content;
} 

function web2wap(&$content)
{
	$search = array ("/<img[^>]+src=\"([^\">]+)\"[^>]+>/siU",
		"/<a[^>]+href=\"([^\">]+)\"[^>]*>(.*)<\/a>/siU",
		"'<br[^>]*>'si",
		"'<p>'si",
		"'</p>'si",
		"'<script[^>]*?>.*?</script>'si", // 去掉 javascript
		"'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
		"'([\r\n])[\s]+'", // 去掉空白字符
		); // 作为 PHP 代码运行
	$replace = array ("#img#\\1#/img#",
		"#link#\\1#\\2#/link#",
		"[br]",
		"",
		"[br]",
		"",
		"",
		"",
		);
	$text = preg_replace ($search, $replace, $content);
	$text = str_replace("[br]", "<br/>", $text);
	$img_start = "<img src=\"" . $publish_url . "automini.php?src=";
	$img_end = "&amp;pixel=100*80&amp;cache=1&amp;cacheTime=1000&amp;miniType=png\" />";
	$text = preg_replace ("/#img#(.*)#\/img#/isUe", "'$img_start'.urlencode('\\1').'$img_end'", $text);
	$text = preg_replace ("/#link#(.*)#(.*)#\/link#/isU", "<a href=\"\\1\">\\2</a>", $text);
	while (preg_match("/<br\/><br\/>/siU", $text)) {
		$text = str_replace('<br/><br/>', '<br/>', $text);
	} 
	return $text;
} 

/**
 * 获取变量的名字
 * eg hello="123" 获取ss字符串
 */
function get_var_name(&$aVar)
{
	foreach($GLOBALS as $key => $var) {
		if ($aVar == $GLOBALS[$key] && $key != "argc") {
			return $key;
		} 
	} 
} 
// -----------------变量调试-------------------
/**
 * 格式化输出变量，或者对象
 * 
 * @param mixed $var 
 * @param boolean $exit 
 */
function pr($var, $exit = false)
{
	ob_start();
	$style = '<style>
	pre#debug{margin:10px;font-size:14px;color:#222;font-family:Consolas ;line-height:1.2em;background:#f6f6f6;border-left:5px solid #444;padding:5px;width:95%;word-break:break-all;}
	pre#debug b{font-weight:400;}
	#debug #debug_str{color:#E75B22;}
	#debug #debug_keywords{font-weight:800;color:00f;}
	#debug #debug_tag1{color:#22f;}
	#debug #debug_tag2{color:#f33;font-weight:800;}
	#debug #debug_var{color:#33f;}
	#debug #debug_var_str{color:#f00;}
	#debug #debug_set{color:#0C9CAE;}</style>';
	if (is_array($var)) {
		print_r($var);
	} else if (is_object($var)) {
		echo get_class($var) . " Object";
	} else if (is_resource($var)) {
		echo (string)$var;
	} else {
		echo var_dump($var);
	} 
	$out = ob_get_clean(); //缓冲输出给$out 变量
	
	$out = preg_replace('/"(.*)"/', '<b id="debug_var_str">"' . '\\1' . '"</b>', $out); //高亮字符串变量
	$out = preg_replace('/=\>(.*)/', '=>' . '<b id="debug_str">' . '\\1' . '</b>', $out); //高亮=>后面的值
	$out = preg_replace('/\[(.*)\]/', '<b id="debug_tag1">[</b><b id="debug_var">' . '\\1' . '</b><b id="debug_tag1">]</b>', $out); //高亮变量
	$from = array('    ', '(', ')', '=>');
	$to = array('  ', '<b id="debug_tag2">(</i>', '<b id="debug_tag2">)</b>', '<b id="debug_set">=></b>');
	$out = str_replace($from, $to, $out);

	$keywords = array('Array', 'int', 'string', 'class', 'object', 'null'); //关键字高亮
	$keywords_to = $keywords;
	foreach($keywords as $key => $val) {
		$keywords_to[$key] = '<b id="debug_keywords">' . $val . '</b>';
	} 
	$out = str_replace($keywords, $keywords_to, $out);
	$out = str_replace("\n\n", "\n", $out);
	echo $style . '<pre id="debug"><b id="debug_keywords">' . get_var_name($var) . '</b> = ' . $out . '</pre>';
	if ($exit) exit; //为真则退出
} 

/**
 * 调试输出变量，对象的值。
参数任意个(任意类型的变量)
 * 
 * @return echo 
 */
function debug_out()
{
	$avg_num = func_num_args();
	$avg_list = func_get_args();
	ob_start();
	for($i = 0; $i < $avg_num; $i++) {
		pr($avg_list[$i]);
	} 
	$out = ob_get_clean();
	echo $out;
	exit;
} 

/**
 * 取$from~$to范围内的随机数
 * 
 * @param  $from 下限
 * @param  $to 上限
 * @return unknown_type 
 */
function rand_from_to($from, $to)
{
	$size = $from - $to; //数值区间
	$max = 30000; //最大
	if ($size < $max) {
		return $from + mt_rand(0, $size);
	} else {
		if ($size % $max) {
			return $from + random_from_to(0, $size / $max) * $max + mt_rand(0, $size % $max);
		} else {
			return $from + random_from_to(0, $size / $max) * $max + mt_rand(0, $max);
		} 
	} 
} 

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * 
 * @param string $len 长度
 * @param string $type 字串类型：0 字母 1 数字 2 大写字母 3 小写字母  4 中文  
 * 其他为数字字母混合(去掉了 容易混淆的字符oOLl和数字01，)
 * @param string $addChars 额外字符
 * @return string 
 */
function rand_string($len = 4, $type = '', $addChars = '')
{
	$str = '';
	switch ($type) {
		case 0://大小写中英文
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1://数字
			$chars = str_repeat('0123456789', 3);
			break;
		case 2://大写字母
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3://小写字母
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		default: 
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
			break;
	} 
	if ($len > 10) { // 位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
	} 
	if ($type != 4) {
		$chars = str_shuffle($chars);
		$str = substr($chars, 0, $len);
	} else {
		// 中文随机字
		for($i = 0; $i < $len; $i ++) {
			$str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
		} 
	} 
	return $str;
} 


// -----------------加密解密-------------------
/**
 * MD5加密密码
 */
function encrypt_md5($password)
{
	$encryptkeys = array('0' => '!',
		'1' => '*',
		'3' => '%',
		'4' => '(',
		'5' => '#',
		'6' => ')',
		'7' => '+',
		'8' => '_',
		'9' => '&',
		'a' => '{',
		'c' => '}',
		'd' => 'D',
		'e' => '`',
		'g' => '"',
		'i' => '-',
		'k' => '=',
		'm' => ':',
		'n' => '<',
		'o' => ';',
		'p' => '>',
		'q' => ',',
		's' => '~',
		't' => ' ',
		'u' => 'U',
		'v' => 'V',
		'w' => ']',
		'y' => '?',
		'z' => '['
		);
	$encryptkeysFlip = array_flip($encryptkeys);
	for($i = 0; $i < strlen($password); $i ++) {
		if ($encryptkeys[$password{$i}]) {
			$password{$i} = $encryptkeys[$password{$i}];
		} elseif ($encryptkeysFlip[$password{$i}]) {
			$password{$i} = $encryptkeysFlip[$password{$i}];
		} 
	} 
	return md5($password);
} 

/**
 * php DES解密函数
 * 
 * @param string $key 密钥
 * @param string $encrypted 加密字符串
 * @return string 
 */
function des_decode($key, $encrypted)
{
	$encrypted = base64_decode($encrypted);
	$td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, ''); //使用MCRYPT_DES算法,cbc模式
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	$ks = mcrypt_enc_get_key_size($td);

	mcrypt_generic_init($td, $key, $key); //初始处理
	$decrypted = mdecrypt_generic($td, $encrypted); //解密
	
	mcrypt_generic_deinit($td); //结束
	mcrypt_module_close($td);
	return pkcs5_unpad($decrypted);
} 
/**
 * php DES加密函数
 * 
 * @param string $key 密钥
 * @param string $text 字符串
 * @return string 
 */
function des_encode($key, $text)
{
	$y = pkcs5_pad($text);
	$td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, ''); //使用MCRYPT_DES算法,cbc模式
	$ks = mcrypt_enc_get_key_size($td);

	mcrypt_generic_init($td, $key, $key); //初始处理
	$encrypted = mcrypt_generic($td, $y); //解密
	mcrypt_generic_deinit($td); //结束
	mcrypt_module_close($td);
	return base64_encode($encrypted);
} 
function pkcs5_unpad($text)
{
	$pad = ord($text{strlen($text)-1});
	if ($pad > strlen($text)) return $text;
	if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
	return substr($text, 0, -1 * $pad);
} 
function pkcs5_pad($text, $block = 8)
{
	$pad = $block - (strlen($text) % $block);
	return $text . str_repeat(chr($pad), $pad);
} 


// -----------------编码转换-------------------
/**
 * 通过BOM判断  0xFF,0xFE。
原理很简单，因为gb2312/gbk是中文两字节，这两个字节是有取值范围的，
而utf-8中汉字是三字节，同样每个字节也有取值范围。
而英文不 管在何种编码情况下，都是小于128，只占用一个字节（全角除外）。
如果是文件形式的编码检查，还可以直接check utf-8的BOM信息。
这个函数是用来对字符串进行检查和转码的。
 */
function str_to_utf8($string, $outEncoding = 'UTF-8')
{
	$encoding = "UTF-8";
	for($i = 0;$i < strlen($string);$i++) {
		if (ord($string{$i}) < 128)
			continue;
		if ((ord($string{$i})&224) == 224) {
			$char = $string{++$i}; //第一个字节判断通过      
			if ((ord($char)&128) == 128) {
				// 第二个字节判断通过
				$char = $string{++$i};
				if ((ord($char)&128) == 128) {
					$encoding = "UTF-8";
					break;
				} 
			} 
		} 
		if ((ord($string{$i})&192) == 192) {
			// 第一个字节判断通过
			$char = $string{++$i};
			if ((ord($char)&128) == 128) {
				// 第二个字节判断通过
				$encoding = "GB2312";
				break;
			} 
		} 
	} 
	if (strtoupper($encoding) == strtoupper($outEncoding))
		return $string;
	else
		return iconv($encoding, $outEncoding, $string);
} 

/**
 * GBK到Unicode编码(character reference)转换函数
 * 
 * @param string $str 待转换的字符串
 * @return string 
 * @access public 
 */
function gb_to_unicode($gb)
{
	if (!trim($gb))
		return $gb;
	$filename = INCLUDE_PATH . "lib/encoder/gb2312.txt";
	$tmp = file($filename);
	$codetable = array();
	while (list($key, $value) = each($tmp))
	$codetable[hexdec(substr($value, 0, 6))] = substr($value, 9, 4);
	$utf = "";
	while ($gb) {
		if (ord(substr($gb, 0, 1)) > 127) {
			$that = substr($gb, 0, 2);
			$gb = substr($gb, 2, strlen($gb));
			$utf .= "&#x" . $codetable[hexdec(bin2hex($that)) - 0x8080] . ";";
		} else {
			// $gb=substr($gb,1,strlen($gb));
			// $utf.=substr($gb,0,1);
			$utf .= substr($gb, 0, 1);
			$gb = substr($gb, 1, strlen($gb));
		} 
	} 
	return $utf;
} 

// 获取操作系统信息
function getOS ()
{
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$os = false;
	if (eregi('win', $agent) && strpos($agent, '95')) {
		$os = 'Windows 95';
	} else if (eregi('win 9x', $agent) && strpos($agent, '4.90')) {
		$os = 'Windows ME';
	} else if (eregi('win', $agent) && ereg('98', $agent)) {
		$os = 'Windows 98';
	} else if (eregi('win', $agent) && eregi('nt 5.1', $agent)) {
		$os = 'Windows XP';
	} else if (eregi('win', $agent) && eregi('nt 5', $agent)) {
		$os = 'Windows 2000';
	} else if (eregi('win', $agent) && eregi('nt', $agent)) {
		$os = 'Windows NT';
	} else if (eregi('win', $agent) && ereg('32', $agent)) {
		$os = 'Windows 32';
	} else if (eregi('linux', $agent)) {
		$os = 'Linux';
	} else if (eregi('unix', $agent)) {
		$os = 'Unix';
	} else if (eregi('sun', $agent) && eregi('os', $agent)) {
		$os = 'SunOS';
	} else if (eregi('ibm', $agent) && eregi('os', $agent)) {
		$os = 'IBM OS/2';
	} else if (eregi('Mac', $agent) && eregi('PC', $agent)) {
		$os = 'Macintosh';
	} else if (eregi('PowerPC', $agent)) {
		$os = 'PowerPC';
	} else if (eregi('AIX', $agent)) {
		$os = 'AIX';
	} else if (eregi('HPUX', $agent)) {
		$os = 'HPUX';
	} else if (eregi('NetBSD', $agent)) {
		$os = 'NetBSD';
	} else if (eregi('BSD', $agent)) {
		$os = 'BSD';
	} else if (ereg('OSF1', $agent)) {
		$os = 'OSF1';
	} else if (ereg('IRIX', $agent)) {
		$os = 'IRIX';
	} else if (eregi('FreeBSD', $agent)) {
		$os = 'FreeBSD';
	} else if (eregi('teleport', $agent)) {
		$os = 'teleport';
	} else if (eregi('flashget', $agent)) {
		$os = 'flashget';
	} else if (eregi('webzip', $agent)) {
		$os = 'webzip';
	} else if (eregi('offline', $agent)) {
		$os = 'offline';
	} else {
		$os = 'Unknown';
	} 
	return $os;
} 
