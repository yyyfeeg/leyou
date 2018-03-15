<?php
#================================================================
# 	FileName: update_all_dayinfo.php
# 		Desc: 每天平台数据汇总更新，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.07.26
# LastChange: 
#================================================================

// 包含总配置文件
include_once(dirname(__FILE__).'/../config.inc.php');
get_games_conn();

$msg = '';	// 定义消息变量

// 命令行中是否有传递参数
if ($_SERVER['argv']) {
	$cs = $_SERVER['argv'];
    $params = explode("_",$cs[1]);
}

// 获取要更新的时间段
$date = !empty($params[0]) ? $params[0] : get_param('date');

// 更新类型：1、普通更新 2、强制更新(把旧数据重新统计一次)；本功能默认为普通更新
$forceupdate = !empty($params[2]) ? $params[2] : get_param('forceupdate','int');


// 判断自动更新还是手动更新
if (empty($date)) {
	// 自动更新
	$operType = 1; // 计划任务标识
	$date 	  = date("Ymd",strtotime("-1 day"));
} else {
	// 手动更新
	$date = $date;
}
$date1  = date("Ymd",strtotime($date)-1);
$date2  = date("Ymd",strtotime($date)+86400);

$msg    = "";
$unum   = 0;//更新条数
$inum   = 0;//插入条数


// 强制更新,删除旧数据
if ($forceupdate == 2) {
 	// 删除当天表中的数据
 	$sql_del = "delete from ".get_table('all_dayinfo')." where ga_date > $date1 and ga_date < $date2";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}

// 查询当天打开应用数
$sql   = "select dg_gid,dg_uaid,dg_uwid,dg_opendate,count(distinct dg_dnumber) as opens from ".get_table('gameopen_log')." where dg_opendate > {$date1} and dg_opendate < {$date2}";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dg_opendate'];
	if(!empty($rows['opens'])){
		$data[$key]['opens'] = $rows['opens'];
	}
}


//活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct dg_uid) as dau,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news,dg_logdate from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} group by dg_logdate";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_logdate'];
	$data[$key]['dau']  	= $rows["dau"];		//日活跃
	$data[$key]['news'] 	= $rows["news"];	//新增登录注册人数
}

//充值用户相关信息
$sql   =  "select dp_paydate,dp_gid,dp_uaid,dp_uwid,count(distinct dp_uid) paynums,sum(dp_money) money from ".get_table("paylog_log")." where dp_paydate>{$date1} and dp_paydate<{$date2} group by dp_paydate";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dp_paydate'];
	$data[$key]['paynums'] = $rows["paynums"];	//充值人数
	$data[$key]['money']   = $rows["money"];	//充值金额
}

//获取新增设备数
$sql =  "select dg_regdate,dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gamelogin_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_regdate";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_regdate'];
	$data[$key]['device'] = $rows["device"];	//新增设备
}

//获取激活设备数
$sql =  "select dg_regdate,dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gameopen_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_regdate";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_regdate'];
	$data[$key]['jh'] = $rows["device"];	//激活设备
}

// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'ad_date' 	=> $x_key[0],
			);
		// 更新的数据
		$dataArr = array(
			'ad_date' 		=> $x_key[0],
			'ad_opens'  	=> !empty($value['opens'])? $value['opens']:0,
			'ad_dau' 		=> !empty($value['dau'])? $value['dau']:0,
			'ad_news' 		=> !empty($value['news'])? $value['news']:0,
			'ad_pay' 		=> !empty($value['paynums'])? $value['paynums']:0,
			'ad_money' 		=> !empty($value['money'])? $value['money']:0,
			'ad_device' 	=> !empty($value['device'])? $value['device']:0,
			'ad_activation' => !empty($value['jh'])? $value['jh']:0,
			'ad_uptime' 	=> time(),
			);
		// 判断是否存在该记录
		if (exist_check($DB, "all_dayinfo", $whereArr)) {
			// 更新记录
			$res = update_record($DB, 'all_dayinfo', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每天平台数据汇总统计数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'all_dayinfo', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每天平台数据汇总统计数据插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	}
	$msg = $date." 每天平台数据汇总数据更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_all_dayinfo.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每天平台数据汇总数据-该天没有数据可更新,更新日期:".$date."<br>";
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


?>