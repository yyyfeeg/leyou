<?php
#================================================================
# 	FileName: update_game_hours.php
# 		Desc: 每天活跃用户数据更新，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.07.20
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
 	$sql_del = "delete from ".get_table('game_hours_info')." where ga_date > $date1 and ga_date < $date2";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}

// 查询当天某个时间段打开应用数
$sql   = "select dg_gid,dg_uaid,dg_uwid,dg_opendate,FROM_UNIXTIME(dg_opentime,'%H') as fu, count(distinct dg_dnumber) as opens from ".get_table('gameopen_log')." where dg_opendate > {$date1} and dg_opendate < {$date2} group by dg_gid,dg_uaid,dg_uwid,dg_opendate,fu";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_opendate'];
	$data[$key.'_1'][$rows["fu"]] = $rows['opens'];	//打开设备
}

// 查询当天某个时间段激活设备数
$sql   = "select dg_gid,dg_uaid,dg_uwid,dg_regdate,FROM_UNIXTIME(dg_regtime,'%H') as fu, count(distinct dg_dnumber) as acts from ".get_table('gameopen_devices')." where dg_regdate > {$date1} and dg_regdate < {$date2} group by dg_gid,dg_uaid,dg_uwid,dg_regdate,fu";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_regdate'];
	$data[$key.'_2'][$rows["fu"]] = $rows['acts'];	//激活设备
}

// 查询当天某个时间段新增设备数
$sql =  "select dg_uaid,dg_uwid,dg_gid,dg_regdate,FROM_UNIXTIME(dg_regtime,'%H') as fu,count(distinct dg_dnumber) device from ".get_table("gamelogin_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_uaid,dg_uwid,dg_gid,dg_regdate,fu";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_regdate'];
	$data[$key.'_3'][$rows["fu"]] = $rows["device"];	//新增设备
}

//活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,dg_logdate,FROM_UNIXTIME(dg_logtime,'%H') as fu,count(distinct if(dg_regdate=dg_logdate,dg_uid,null)) regnums,count(distinct if(dg_ispay=1,1,null)) paylogs,count(distinct dg_uid) dau,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} group by dg_gid,dg_uaid,dg_uwid,dg_logdate,fu";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_logdate'];
	$data[$key.'_5'][$rows["fu"]]  	= $rows["dau"];		//活跃用户
	$data[$key.'_4'][$rows["fu"]] 	= $rows["news"];	//新增人数
	$data[$key.'_7'][$rows["fu"]]	= $rows["paylogs"];	//付费登录人数
	$data[$key.'_8'][$rows["fu"]]	= $rows["dau"] - $rows["news"];	//活跃老玩家
}

// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'cgh_gameid' => $x_key[0],
			'cgh_date' 	=> $x_key[3],
			'cgh_uaid' 	=> $x_key[1],
			'cgh_uwid' 	=> $x_key[2],
			'cgh_count_type' => $x_key[4]? $x_key[4]:0
		);
		$dataArr = array(
			'cgh_gameid' => $x_key[0],
			'cgh_date' 	=> $x_key[3],
			'cgh_uaid' 	=> $x_key[1],
			'cgh_uwid' 	=> $x_key[2],
			'cgh_count_type' => $x_key[4]? $x_key[4]:0,
			'cgh_uptime' => time()
		);

		// 更新的数据
		foreach ($value as $f=>$val) {
			$dataArr['cgh_hour'.$f] = $val;
		}
		// 判断是否存在该记录
		if (exist_check($DB, "game_hours_info", $whereArr)) {
		// 更新记录
			$res = update_record($DB, 'game_hours_info', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每天活跃用户统计数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'game_hours_info', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每天活跃用户统计数据插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	}
	$msg = $date." 每天活跃用户数据更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_game_active.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每天活跃用户数据-该天没有数据可更新,更新日期:".$date."<br>";
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