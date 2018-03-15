<?php
#================================================================
# 	FileName: update_pay_hours.php
# 		Desc: 每天用户付费跟踪数据更新，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.20
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
 	$sql_del = "delete from ".get_table('pay_hours_info')." where ga_date > $date1 and ga_date < $date2";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}

// 查询当天注册的各个时间段付费人数
$sql   = "SELECT count(distinct dp_uid) nums,from_unixtime(dp_paytime,'%H') hours,dp_paydate,dp_gid,dp_uaid,dp_uwid  FROM ".get_table('paylog_log')." WHERE dp_paydate=dp_regdate and dp_paydate > {$date1} and dp_paydate < {$date2} group by dp_gid,dp_uaid,dp_uwid,dp_paydate,hours order by dp_paydate,hours";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dp_gid'].'_'.$rows['dp_uaid'].'_'.$rows['dp_uwid'].'_'.$rows['dp_paydate'];
	$data[$key.'_4'][$rows["hours"]] = $rows['nums'];	//新增注册付费
}
//exit($sql);
// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'cph_gameid' => $x_key[0],
			'cph_date' 	=> $x_key[3],
			'cph_uaid' 	=> $x_key[1],
			'cph_uwid' 	=> $x_key[2],
			'cph_count_type' => $x_key[4]? $x_key[4]:0
		);
		$dataArr = array(
			'cph_gameid' => $x_key[0],
			'cph_date' 	=> $x_key[3],
			'cph_uaid' 	=> $x_key[1],
			'cph_uwid' 	=> $x_key[2],
			'cph_count_type' => $x_key[4]? $x_key[4]:0,
			'cph_uptime' => time()
		);

		// 更新的数据
		foreach ($value as $f=>$val) {
			$dataArr['cph_hour'.$f] = $val;
		}
		// 判断是否存在该记录
		if (exist_check($DB, "pay_hours_info", $whereArr)) {
		// 更新记录
			$res = update_record($DB, 'pay_hours_info', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每天用户付费跟踪统计更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'pay_hours_info', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每天用户付费跟踪统计插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	}
	$msg = $date." 每天用户付费跟踪统计更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_pay_hours_info.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每天用户付费跟踪-该天没有数据可更新,更新日期:".$date."<br>";
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