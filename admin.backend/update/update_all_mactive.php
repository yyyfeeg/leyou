<?php
#================================================================
# 	FileName: update_all_mactive.php
# 		Desc: 每月平台活跃用户数据更新，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.07.28
# LastChange: 
#================================================================

// 包含总配置文件
ini_set('default_charset', "utf-8");
date_default_timezone_set('Asia/Shanghai'); //定义时区
include_once(dirname(__FILE__).'/../config.inc.php');
get_games_conn();

$msg = '';	// 定义消息变量

// 命令行中是否有传递参数
if ($_SERVER['argv']) {
	$cs = $_SERVER['argv'];
    $params = explode("_",$cs[1]);
}

// 获取要更新的时间段
$dts = !empty($params[0]) ? $params[0] : get_param('date');
// 更新类型：1、普通更新 2、强制更新(把旧数据重新统计一次)；本功能默认为普通更新
$forceupdate = !empty($params[2]) ? $params[2] : get_param('forceupdate','int');

// 判断自动更新还是手动更新
if (empty($dts)) {
	// 自动更新
	$operType = 1; // 计划任务标识
	$date     = date('Ym');
}else{
	$dts  = strlen($dts)==6?$dts."01":$dts;
	$date = date("Ym",strtotime($dts));
}

$msg    = "";
$unum   = 0;	//更新条数
$inum   = 0;	//插入条数
$year   = substr($date,0,4);
$month  = substr($date,4,2);

$date1  = date("Ym",mktime(0,0,0,$month-1,1,$year))."31";  //本月开始时间-1
$date2  = date("Ym",mktime(0,0,0,$month+1,1,$year))."01";  //本月结束时间+1

$date3  = date("Ym",mktime(0,0,0,$month-2,1,$year))."31";  //上月开始时间-1
$date4  = date("Ym",mktime(0,0,0,$month,1,$year))."01";    //上月结束时间+1

$date5  = date("Ym",mktime(0,0,0,$month-3,1,$year))."31";  //上上月开始时间-1
$date6  = date("Ym",mktime(0,0,0,$month-1,1,$year))."01";  //上上月结束时间+1

$sdate  = date("Ym",mktime(0,0,0,$month-1,1,$year));  //开始时间
$edate  = date("Ym",mktime(0,0,0,$month+1,1,$year));  //结束时间

// echo $date1.'->'.$date2."<br>";
// echo $date3.'->'.$date4."<br>";
// echo $date5.'->'.$date6."<br>";
// echo $sdate.'->'.$edate;
// exit;


// 强制更新,删除旧数据
if ($forceupdate == 2) {
 	// 删除当天表中的数据
 	$sql_del = "delete from ".get_table('all_mactive')." where gm_month > $sdate and gm_month < $edate";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}

//活跃用户相关信息
$sql   = "select count(distinct if(dg_ispay=1,dg_uid,null)) paylogs,count(distinct dg_uid) dau,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2}";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$data['dau']  	= !empty($rows['dau'])? $rows["dau"]:0;			//月活跃
	$data['news'] 	= !empty($rows['news'])? $rows["news"]:0;		//新增人数
	$data['paylogs']= !empty($rows['paylogs'])? $rows["paylogs"]:0;	//付费登录人数
}


//有效活跃用户信息
$nums  =  0;
$sql   =  "select dg_uid,group_concat(distinct dg_logdate) logdate,sum(dg_nums) lognums from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} group by dg_uid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$k2  = explode(",",$rows["logdate"]);
	if($rows["lognums"]<24){
		continue;
	}
	if(count($k2)<8){
		continue;
	}
	$nums += 1;
	$data["rel_dau"] = $nums;
}


//充值用户相关信息
$sql   =  "select count(distinct dp_uid) paynums,sum(dp_money) money from ".get_table("paylog_log")." where dp_paydate>{$date1} and dp_paydate<{$date2}";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$data['paynums'] = !empty($rows['paynums'])? $rows["paynums"]:0;	//充值人数
	$data['money']   = !empty($rows['money'])? $rows["money"]:0;		//充值金额
}

//获取新增设备数
$sql =  "select count(distinct dg_dnumber) device from ".get_table("gamelogin_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2}";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$data['device'] = !empty($rows["device"])? $rows["device"]:0;	//新增设备
}

//获取激活设备数
$sql =  "select count(distinct dg_dnumber) device from ".get_table("gameopen_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2}";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$data['jh'] = !empty($rows["device"])?$rows["device"]:0;	//激活设备
}

//月回流
$sql 	= "select count(distinct dg_uid) nums from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} and dg_lastlogdate>".$date5." and dg_lastlogdate<".$date6;
$query 	= $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$data['wrf'] = !empty($rows["nums"])?$rows["nums"]:0;	//月回流
}


//月流失
$sql    = "select max(dg_logdate) maxdate from ".get_table("gamelogin_log")." where dg_logdate>{$date3} and dg_logdate<".($edate."01")." group by dg_uid";
$query 	= $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	if($rows["maxdate"]<$date4){
		$data['wof'] += 1;	
	}
}

//月新增玩家次日留存数
$sql    = "select sum(car_remain) sums from ".get_table("all_remain")." where car_regdate>{$date1} and car_regdate<{$date2} and car_diff=1";
$query  = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$data['remain'] = !empty($rows["sums"])?$rows["sums"]:0;
}

// 数据入库处理
if (!empty($data)) {		//  && array_sum($data)>0
		// 判断当前记录是否已存在
		$whereArr = array(
			'am_month' 	=> $date,
			);
		// 更新的数据
		$dataArr = array(
			'am_month' 		=> $date,
			'am_mof' 		=> !empty($data['wof'])? $data['wof']:0,
			'am_mrf' 		=> !empty($data['wrf'])? $data['wrf']:0,
			'am_mau' 		=> !empty($data['dau'])? $data['dau']:0,
			'am_mnews' 		=> !empty($data['news'])? $data['news']:0,
			'am_mpay' 		=> !empty($data['paynums'])? $data['paynums']:0,
			'am_mmoney' 	=> !empty($data['money'])? $data['money']:0,
			'am_mpaylog'	=> !empty($data['paylogs'])? $data['paylogs']:0,
			'am_mdevice'	=> !empty($data['device'])? $data['device']:0,
			'am_activation'	=> !empty($data['jh'])? $data['jh']:0,
			'am_rel_dau'	=> !empty($data['rel_dau'])? $data['rel_dau']:0,
			'am_remain'		=> !empty($data['remain'])? $data['remain']:0,
			'am_uptime' 	=> time(),
			);
		// 判断是否存在该记录
		if (exist_check($DB, "all_mactive", $whereArr)) {
			// 更新记录
			$res = update_record($DB, 'all_mactive', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每月平台数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'all_mactive', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每月平台数据插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	$msg = $date." 每月平台数据更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_all_mactive.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每月平台数据-该天没有数据可更新,更新日期:".$date."<br>";
}

$DB->FreeResult($query);	// 释放结果内存
if ($DB) { $DB->Close(); }	// 关闭数据连接
// 返回信息处理
$action = get_param("ac"); //操作方式
$jsonp 	= $_GET ['callback'];

if ($action == 1) { // 返回json数据
	$json = json_encode(array('msg'=>$msg));
	ob_clean();
	exit($jsonp.'('.$json.')');
} else {
	exit($msg);
}

/**
 * 日期范围比较函数
 * @param  [type] $arr   需要比较的日期数组
 * @param  [type] $date1 日期1
 * @param  [type] $date2 日期2
 * @return [type]        Boolean：true表示在其范围内，false不在
 */
function compare($arr,$date1,$date2)
{
	if (!empty($date1) && !empty($date2)) {
		if ($date1 > $date2) {
			list($date1,$date2) = array($date2,$date1);
		}
	}
	if (!empty($arr) && is_array($arr)) {
		foreach ($arr as $value) {
			if ($value > $date1 && $value <= $date2) {
				return true;
			}
		}
	}
	return false;
}

?>