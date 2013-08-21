<?php

date_default_timezone_set('PRC');

//获取年龄
function get_age($date)
{
	if (!$date) {
		$date = 19950101;
	} 
	$timestamp = strtotime($date);
	$year = date('Y', $timestamp);
	$year_now = date('Y', time());
	return intval($year_now - $year);
} 

/**
 * 截止时间格式化函数
 * 输入一个截止时间戳，返回剩余的天、小时或者分数（如：2天、18小时29分、55分）
 * 
 * @param time $timestamp 截止时间戳
 * @return string 
 * @author mosico 
 */
function get_limit_time($timestamp)
{ 
	// 截止时间戳和当前时间戳的差值
	$sub_timestamp = $timestamp - time(); 
	// 如果截止时间戳小于当前的时间戳则返回fale
	if ($sub_timestamp < 0) {
		return false;
	}	
	if ($sub_timestamp >= 86400) {//大于一天 显示格式：2天
		return intval(floor($sub_timestamp / 86400)) . '天';
	}	
	elseif ($sub_timestamp >= 3600) {//小于一天 显示格式：18小时29分
		return intval(floor($sub_timestamp / 3600)) . '小时';
	}
	elseif ($sub_timestamp >= 60) {//小于一个小时	显示格式：55分
		return intval(floor($sub_timestamp / 60)) . '分钟';
	}	
	else {// 小于一分钟	显示格式：不到一分钟
		return '1分钟';
	} 
} 

function get_time($timestamp, $type = '')
{
	if (!$timestamp) {
		return '';
	} 

	$now_time = time();
	$limit = $now_time - $timestamp;
	if ($limit <= 60) {
		return '刚刚';
	} elseif ($limit <= 60 * 60) {
		return floor($limit / 60) . '分钟前';
	} elseif ($limit <= 24 * 60 * 60) {
		return floor($limit / (60 * 60)) . '小时前';
	} elseif ($limit <= 2 * 24 * 60 * 60) {
		return '昨天';
	} elseif ($limit <= 3 * 24 * 60 * 60) {
		return '前天';
	} elseif ($limit <= 4 * 24 * 60 * 60) {
		return '3天前';
	} elseif ($limit <= 5 * 24 * 60 * 60) {
		return '4天前';
	} elseif ($limit <= 6 * 24 * 60 * 60) {
		return '5天前';
	} elseif ($limit <= 7 * 24 * 60 * 60) {
		return '6天前';
	} else {
		return strftime('%m-%d', $timestamp);
	} 
} 

/**
 * 计算日期差
 * 
 * @param  $date1 
 * @param  $date2 
 * @return int 
 */
function get_date_range($date1, $date2)
{
	$current = date_create($date1) -> format('Ymd');
	$datetime2 = date_create($date2) -> format('Ymd');
	$count = 0;

	$ts_1 = strtotime($current);
	$ts_2 = strtotime($datetime2);

	if ($ts_1 > $ts_2) {
		return - get_date_range($date2, $date1);
	} 

	$count = intval(floor(($ts_1 - $ts_2) / 86400));

	return - $count;
} 

/**
 * 通用时间格式化函数（目标是会返回类似“昨天 09:00 AM”这样的格式）
 * 
 * @param  $timestamp 
 * @param  $type 
 * @return string 
 */
function get_time($timestamp, $type = '')
{
	if (!$timestamp) {
		return '';
	} 
	$now_time = time();
	$date = date('Ymd', $timestamp);
	$now_date = date('Ymd', $now_time);
	$limit = time() - $timestamp;

	if ($limit < 60 && ($date != $now_date)) {
		return '昨天';
	} elseif ($limit < 60 && ($date == $now_date)) {
		return '刚刚';
	} 
	if ($limit < 3600 && ($date != $now_date)) {
		return '昨天';
	} elseif ($limit < 3600 && ($date == $now_date)) {
		return floor($limit / 60) . '分钟前';
	} 
	if ($limit < 86400 && ($date != $now_date)) {
		return '昨天';
	} elseif ($limit < 86400 && ($date == $now_date)) {
		return floor($limit / 3600) . '小时前';
	} 
	$limit = strtotime(strftime('%Y%m%d', time())) - $timestamp;
	if ($limit < 259200) {
		if ($type === 'detail') {
			return date('H:i', $timestamp);
		} 
		$days = intval(floor($limit / 86400));
		switch ($days) {
			case 0:
			case 1: return '昨天';
			case 2: return '前天';
		} 
	} 
	if ($type === 'detail') {
		return date('m-d H:i', $timestamp);
	} 
	if ($limit < 604800) {
		$days = intval(floor($limit / 86400));

		return $days . '天前';
	} 
	return strftime('%m-%d', $timestamp);
} 

function week($timestamp)
{
	$date_info = getdate($timestamp);
	$timestamp = strtotime($date_info[year] . '-' . $date_info[mon] . '-' . $date_info[mday]);
	$sunday = $timestamp - $date_info['wday'] * 24 * 3600;

	for($i = 0; $i <= 6; $i++) {
		$time = $sunday + $i * 24 * 3600;
		$week[$i] = getdate($time);
	} 

	return $week;
} 

function month($timestamp)
{
	$date = date('Y-m', $timestamp);
	$month_start = strtotime($date . '-1') ;
	$date_info = getdate($month_start);

	for($i = 1; $i <= 31; $i++) {
		if (checkdate($date_info[mon], $i , $date_info[year])) {
			$time = strtotime($date_info[year] . '-' . $date_info[mon] . '-' . $i);
			$month[$i] = getdate($time);
		} 
	} 

	return $month;
} 

function in_today($time)
{
	$current_date = date('Y-m-d', time());
	$date = date('Y-m-d', $time);

	if ($current_date == $date) {
		return true;
	} else {
		return false;
	} 
} 

function in_week($time)
{
	$current_dateInfo = getdate(time());
	$timestamp = strtotime($current_dateInfo[year] . '-' . $current_dateInfo[mon] . '-' . $current_dateInfo[mday]);
	$current_sunday = $timestamp - $current_dateInfo['wday'] * 24 * 3600;
	$date_info = getdate($time);
	$timestamp = strtotime($date_info[year] . '-' . $date_info[mon] . '-' . $date_info[mday]);
	$sunday = $timestamp - $date_info['wday'] * 24 * 3600;

	if ($current_sunday == $sunday) {
		return true;
	} else {
		return false;
	} 
} 

function in_month($time)
{
	$current_month = date('Y-m', time());
	$month = date('Y-m', $time);

	if ($current_month == $month) {
		return true;
	} else {
		return false;
	} 
} 

function in_year($time)
{
	$cyear = date('Y', time());
	$lyear = date('Y', $time);
	return $cyear == $lyear;
} 

/**
 * 获得日历数据
 * 
 * @param  $timestamp 
 * @return array 
 */
function get_calender($timestamp = null)
{
	if ($timestamp === null) {
		$timestamp = time();
	} 

	$date_info = getdate($timestamp);

	$year = $date_info['year'];
	$month = $date_info['mon'];
	$day = $date_info['mday'];

	$total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

	$days = array();
	for($i = 1; $i <= $total_days_in_month; ++$i) {
		$the_day_start = mktime(0, 0, 0, $month, $i, $year); // 本日起始时间戳（含）
		$the_day_end = mktime(23, 59, 59, $month, $i, $year) + 1; // 本日结束时间戳（不含）
		$day_in_week = array_get(getdate($the_day_start), 'wday');

		$days[] = array('year' => $year,
			'month' => $month,
			'day' => $i,
			'date' => str_pad($year, 4, '0', STR_PAD_LEFT) . str_pad($month, 2, '0', STR_PAD_LEFT) . str_pad($i, 2, '0', STR_PAD_LEFT),
			'span' => array($the_day_start, $the_day_end),
			'day_in_week' => $day_in_week,
			'is_weekend' => ($day_in_week == 0 || $day_in_week == 6),
			'is_today' => ($i == $day),
			);
	} 

	$start_day = $days[0];
	$end_day = $days[$total_days_in_month-1]; 
	// 补全
	if ($start_day['day_in_week']) {
		$offset = $start_day['day_in_week'];
		for($j = 0; $j < $offset; ++$j) {
			array_unshift($days, array());
		} 
	} 
	if ($end_day['day_in_week'] != 6) {
		$offset = 6 - $end_day['day_in_week'];
		for($j = 0; $j < $offset; ++$j) {
			array_push($days, array());
		} 
	} 

	return array('start' => $start_day,
		'end' => $end_day,
		'days' => $days,
		);
} 

?>