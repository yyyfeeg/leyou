<?php
#================================================================
# 	FileName: update_game_active.php
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
$year   = substr($date,0,4);
$month  = substr($date,4,2);

$date3  = date("Ym",mktime(0,0,0,$month-1,1,$year))."31";	//本月开始时间-1
$date4  = date("Ym",mktime(0,0,0,$month+1,1,$year))."01";	//本月结束时间+1


// 强制更新,删除旧数据
if ($forceupdate == 2) {
 	// 删除当天表中的数据
 	$sql_del = "delete from ".get_table('game_active')." where ga_date > $date1 and ga_date < $date2";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}

// 查询当天打开应用数
$sql   = "select dg_gid,dg_uaid,dg_uwid,dg_opendate,count(distinct dg_dnumber) as opens from ".get_table('gameopen_log')." where dg_opendate > {$date1} and dg_opendate < {$date2} group by dg_gid,dg_uaid,dg_uwid,dg_opendate";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_opendate'];
	$data[$key]['opens'] = $rows['opens'];
}


//活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct if(dg_logdate=dg_regdate and dg_nums>2,dg_uid,null)) AS rel_news,count(distinct if(dg_nums>2,dg_uid,null)) as rel_dau,count(distinct if(dg_ispay=1,dg_uid,null)) as paylogs,count(distinct dg_uid) as dau,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news,sum(dg_nums) nums,dg_logdate from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2} group by dg_gid,dg_uaid,dg_uwid,dg_logdate";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_logdate'];
	$data[$key]['dau']  	= $rows["dau"];		//日活跃
	$data[$key]['news'] 	= $rows["news"];	//新增登录注册人数
	$data[$key]['paylogs']	= $rows["paylogs"];	//付费登录人数
	$data[$key]['rel_news']	= $rows["rel_news"];//有效新增数
	$data[$key]['rel_dau']	= $rows["rel_dau"];	//有效活跃数
	$data[$key]['nums']		= $rows["nums"];	//登录次数
}

//充值用户相关信息
$sql   =  "select dp_paydate,dp_gid,dp_uaid,dp_uwid,count(distinct dp_uid) paynums,sum(dp_money) money from ".get_table("paylog_log")." where dp_paydate>{$date1} and dp_paydate<{$date2} group by dp_gid,dp_uaid,dp_uwid,dp_paydate";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dp_gid'].'_'.$rows['dp_uaid'].'_'.$rows['dp_uwid'].'_'.$rows['dp_paydate'];
	$data[$key]['paynums'] = $rows["paynums"];	//充值人数
	$data[$key]['money']   = $rows["money"];	//充值金额
}

//获取新增设备数
$sql =  "select dg_regdate,dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gamelogin_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_regdate,dg_uaid,dg_uwid,dg_gid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_regdate'];
	$data[$key]['device'] = $rows["device"];	//新增设备
}

//获取激活设备数
$sql =  "select dg_regdate,dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gameopen_devices")." where dg_regdate>{$date1} and dg_regdate<{$date2} group by dg_regdate,dg_uaid,dg_uwid,dg_gid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$rows['dg_regdate'];
	$data[$key]['jh'] = $rows["device"];	//激活设备
}

//获取累计激活设备数
$sql =  "select dg_regdate,dg_uaid,dg_uwid,dg_gid,count(distinct dg_dnumber) device from ".get_table("gameopen_devices")." where dg_regdate>{$date3} and dg_regdate<{$date2} group by dg_uaid,dg_uwid,dg_gid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$date;
	$data[$key]['ljjh'] = $rows["device"];	//累计激活设备
}

//累计活跃用户相关信息
$sql   = "select dg_gid,dg_uaid,dg_uwid,count(distinct dg_uid) as dau,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news,dg_logdate from ".get_table("gamelogin_log")." where dg_logdate>{$date3} and dg_logdate<{$date2} group by dg_gid,dg_uaid,dg_uwid";
$query = $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dg_gid'].'_'.$rows['dg_uaid'].'_'.$rows['dg_uwid'].'_'.$date;
	$data[$key]['ljhy']  	= $rows["dau"];		//累计活跃玩家
	$data[$key]['ljxz'] 	= $rows["news"];	//累计新增玩家
}


//累计付费玩家相关信息
$sql   =  "select dp_paydate,dp_gid,dp_uaid,dp_uwid,count(distinct dp_uid) paynums,sum(dp_money) money from ".get_table("paylog_log")." where dp_paydate>{$date3} and dp_paydate<{$date2} group by dp_gid,dp_uaid,dp_uwid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	$key = $rows['dp_gid'].'_'.$rows['dp_uaid'].'_'.$rows['dp_uwid'].'_'.$date;
	$data[$key]['ljff'] = $rows["paynums"];	//累计付费人数
	$data[$key]['ljje']   = $rows["money"];	//累计充值金额
}

// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'ga_gameid' => $x_key[0],
			'ga_date' 	=> $x_key[3],
			'ga_aid' 	=> $x_key[1],
			'ga_wid' 	=> $x_key[2]
			);
		// 更新的数据
		$dataArr = array(
			'ga_gameid' 		=> $x_key[0],
			'ga_date' 			=> $x_key[3],
			'ga_aid' 			=> $x_key[1],
			'ga_wid' 			=> $x_key[2],
			'ga_opens'  		=> !empty($value['opens'])? $value['opens']:0,
			'ga_dau' 			=> !empty($value['dau'])? $value['dau']:0,
			'ga_news' 			=> !empty($value['news'])? $value['news']:0,
			'ga_pay' 			=> !empty($value['paynums'])? $value['paynums']:0,
			'ga_money' 			=> !empty($value['money'])? $value['money']:0,
			'ga_paylog' 		=> !empty($value['paylogs'])? $value['paylogs']:0,
			'ga_device' 		=> !empty($value['device'])? $value['device']:0,
			'ga_activation' 	=> !empty($value['jh'])? $value['jh']:0,
			'ga_rel_dau' 		=> !empty($value['rel_dau'])? $value['rel_dau']:0,
			'ga_rel_news' 		=> !empty($value['rel_news'])? $value['rel_news']:0,
			'ga_sum_activation' => !empty($value['ljjh'])? $value['ljjh']:0,
			'ga_sum_news' 		=> !empty($value['ljxz'])? $value['ljxz']:0,
			'ga_sum_dau' 		=> !empty($value['ljhy'])? $value['ljhy']:0,
			'ga_sum_pay' 		=> !empty($value['ljff'])? $value['ljff']:0,
			'ga_sum_money' 		=> !empty($value['ljje'])? $value['ljje']:0,
			'ga_nums' 			=> !empty($value['nums'])? $value['nums']:0,
			'ga_uptime' 		=> time(),
			);
		// 判断是否存在该记录
		if (exist_check($DB, "game_active", $whereArr)) {
			// 更新记录
			$res = update_record($DB, 'game_active', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每天活跃用户统计数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'game_active', $dataArr);
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