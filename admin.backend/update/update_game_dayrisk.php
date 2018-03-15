<?php
#================================================================
# 	FileName: update_game_dayrisk.php
# 		Desc: 每天活跃用户累计数据更新，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.01
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

$msg    = "";
$unum   = 0;	//更新条数
$inum   = 0;	//插入条数

$d      = date("d",strtotime($date));
$m 		= date("m",strtotime($date));
$w 		= date("w",strtotime($date));
$y 		= date("y",strtotime($date));

$sdate  = date("Ymd",strtotime($date)-1);
$edate  = date("Ymd",strtotime($date)+86400);

$date1  = date("Ymd",mktime(0, 0 , 0,$m,$d-7,$y));		//周开始时间，往前推7天
$date2  = date("Ymd",mktime(0, 0 , 0,$m,$d+1,$y));		//周结束时间

$date3  = date("Ymd",strtotime($date)-(30*86400));  	//月开始时间，前推30天
$date4  = $date2;      									//月结束时间

// 强制更新,删除旧数据
if ($forceupdate == 2) {
 	// 删除当天表中的数据
 	$sql_del = "delete from ".get_table('game_dayrisk')." where gd_date > $sdate and gd_date < $edate";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}

//7天活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct dg_uid) dau from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2}  group by dg_gid,dg_uaid,dg_uwid";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['wau']  = !empty($rows['dau'])? $rows["dau"]:0;			//前推7天活跃总人数
}

//30天活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct dg_uid) dau from ".get_table("gamelogin_log")." where dg_logdate>{$date3} and dg_logdate<{$date4}  group by dg_gid,dg_uaid,dg_uwid";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'];
	$data[$key]['mau']  = !empty($rows['dau'])? $rows["dau"]:0;			//前推7天活跃总人数
}

// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'gd_gameid' => $x_key[0],
			'gd_date' 	=> $date,
			'gd_aid' 	=> $x_key[1],
			'gd_wid' 	=> $x_key[2],
		);
		// 更新的数据
		$dataArr = array(
			'gd_gameid' => $x_key[0],
			'gd_date' 	=> $date,
			'gd_aid' 	=> $x_key[1],
			'gd_wid' 	=> $x_key[2],
			'gd_wau' 	=> $value["wau"],
			'gd_mau' 	=> $value["mau"],
			'gd_uptime' => time(),
			);
		// 判断是否存在该记录
		if (exist_check($DB, "game_dayrisk", $whereArr)) {
			// 更新记录
			$res = update_record($DB, 'game_dayrisk', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每天活跃用户累计数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'game_dayrisk', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每天活跃用户累计数据插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	}
	$msg = $date." 每天活跃用户累计数据更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_game_dayrisk.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每天活跃用户累计数据-该天没有数据可更新,更新日期:".$date."<br>";
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