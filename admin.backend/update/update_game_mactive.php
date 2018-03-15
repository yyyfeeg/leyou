<?php
#================================================================
# 	FileName: update_game_mactive.php
# 		Desc: 每月活跃用户数据更新，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.07.21
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

$date1  = date("Ym",mktime(0,0,0,$month-1,1,$year))."31";	//本月开始时间-1
$date2  = date("Ym",mktime(0,0,0,$month+1,1,$year))."01";	//本月结束时间+1

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
 	$sql_del = "delete from ".get_table('game_mactive')." where gm_month > $sdate and gm_month < $edate";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}


// 查询当天打开应用数
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct dg_dnumber) as opens from ".get_table('gameopen_log')." where dg_opendate > {$date1} and dg_opendate < {$date2} group by dg_gid,dg_uaid,dg_uwid";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['opens'] = !empty($rows['opens'])? $rows['opens']:0;
}

//活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct if(dg_ispay=1,dg_uid,null)) paylogs,count(distinct dg_uid) dau,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} group by dg_gid,dg_uaid,dg_uwid";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['dau']  	= !empty($rows['dau'])? $rows["dau"]:0;		//月活跃
	$data[$key]['news'] 	= !empty($rows['news'])? $rows["news"]:0;	//新增人数
	$data[$key]['paylogs']	= !empty($rows['paylogs'])? $rows["paylogs"]:0;	//付费登录人数
}


//有效活跃用户信息
$nums  =  0;
$sql   =  "select dg_gid,dg_uaid,dg_uwid,dg_uid,group_concat(distinct dg_logdate) logdate,sum(dg_nums) lognums from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} group by dg_gid,dg_uaid,dg_uwid,dg_uid";
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
	$key   = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]["rel_dau"] = $nums;
}


//充值用户相关信息
$sql   =  "select dp_gid,dp_uaid,dp_uwid,count(distinct dp_uid) paynums,sum(dp_money) money from ".get_table("paylog_log")." where dp_paydate>{$date1} and dp_paydate<{$date2} group by dp_gid,dp_uaid,dp_uwid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dp_gid'].'_'.$rows['dp_uaid'].'_'.$rows['dp_uwid'];
	$data[$key]['paynums'] = !empty($rows['paynums'])? $rows["paynums"]:0;	//充值人数
	$data[$key]['money']   = !empty($rows['money'])? $rows["money"]:0;	//充值金额
}

//获取新增设备数
$sql =  "select dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gamelogin_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_uaid,dg_uwid,dg_gid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['device'] = !empty($rows["device"])?$rows["device"]:0;	//新增设备
}

//获取激活设备数
$sql =  "select dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gameopen_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_uaid,dg_uwid,dg_gid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['jh'] = !empty($rows["device"])?$rows["device"]:0;	//激活设备
}

//月回流
$sql 	= "select dg_gid,dg_uaid,dg_uwid,count(distinct dg_uid) nums from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} and dg_lastlogdate>".$date5." and dg_lastlogdate<".$date6." group by dg_gid,dg_uaid,dg_uwid";
$query 	= $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['wrf'] = !empty($rows["nums"])?$rows["nums"]:0;	
}


//月流失
$sql    = "select dg_gid,dg_uaid,dg_uwid,max(dg_logdate) maxdate from ".get_table("gamelogin_log")." where dg_logdate>{$date3} and dg_logdate<".($edate."01")." group by dg_gid,dg_uaid,dg_uwid,dg_uid";
$query 	= $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	if($rows["maxdate"]<$date4){
		$data[$key]['wof'] += 1;	
	}
}

//月新增玩家次日留存数
$sql    = "select cgr_gameid,cgr_uaid,cgr_uwid,cgr_gameid,sum(cgr_remain) sums from ".get_table("game_remain")." where cgr_regdate>{$date1} and cgr_regdate<{$date2} and cgr_diff=1 group by cgr_gameid,cgr_uaid,cgr_uwid";
$query  = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['cgr_gameid'].'_'.$rows['cgr_uaid'].'_'.$rows['cgr_uwid'];
	$data[$key]['remain'] = !empty($rows["sums"])?$rows["sums"]:0;
}

// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'gm_gameid' => $x_key[0],
			'gm_month' 	=> $date,
			'gm_aid' 	=> $x_key[1],
			'gm_wid' 	=> $x_key[2],
			);
		// 更新的数据
		$dataArr = array(
			'gm_gameid' 	=> $x_key[0],
			'gm_month' 		=> $date,
			'gm_aid' 		=> $x_key[1],
			'gm_wid' 		=> $x_key[2],
			'gm_mof' 		=> !empty($value['wof'])? $value['wof']:0,
			'gm_mrf' 		=> !empty($value['wrf'])? $value['wrf']:0,
			'gm_mopens' 	=> !empty($value['opens'])? $value['opens']:0,
			'gm_mau' 		=> !empty($value['dau'])? $value['dau']:0,
			'gm_mnews' 		=> !empty($value['news'])? $value['news']:0,
			'gm_mpay' 		=> !empty($value['paynums'])? $value['paynums']:0,
			'gm_mmoney' 	=> !empty($value['money'])? $value['money']:0,
			'gm_mpaylog'	=> !empty($value['paylogs'])? $value['paylogs']:0,
			'gm_mdevice'	=> !empty($value['device'])? $value['device']:0,
			'gm_activation'	=> !empty($value['jh'])? $value['jh']:0,
			'gm_rel_dau'	=> !empty($value['rel_dau'])? $value['rel_dau']:0,
			'gm_uptime' 	=> time(),
			);
		// 判断是否存在该记录
		if (exist_check($DB, "game_mactive", $whereArr)) {
			// 更新记录
			$res = update_record($DB, 'game_mactive', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每月活跃用户统计数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'game_mactive', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每月活跃用户统计数据插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	}
	$msg = $date." 每月活跃用户数据更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_game_mactive.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每月活跃用户数据-该天没有数据可更新,更新日期:".$date."<br>";
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