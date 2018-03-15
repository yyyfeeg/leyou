<?php
#================================================================
# 	FileName: update_pay_range.php
# 		Desc: 每天游戏充值区间，包括自动更新和手动更新
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.22
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

//测试日期
// $date = 20160920; 

$date1  = date("Ymd",strtotime($date)-1);
$date2  = date("Ymd",strtotime($date)+86400);

$msg    = $str = "";
$unum   = 0;//更新条数
$inum   = 0;//插入条数

// 强制更新,删除旧数据
if ($forceupdate == 2) {
 	// 删除当天表中的数据
 	$sql_del = "delete from ".get_table('pay_range')." where pr_date > $date1 and pr_date < $date2";
 	$DB->Query($sql_del);
 	$res['rows'] = $DB->AffectedRows();
 	if ($res['rows'] < 0) {
 		$msg .= '<br/>删除'.$date.'记录出错'. __LINE__;
 	}
}


if(!empty($arr_config["payrange"])){
	$cts = count($arr_config["payrange"]);
	$j   = 1;
	$str = ",";
	foreach($arr_config["payrange"] as $k=>$v){
		if($j==$cts){
			$str .= "count(distinct if(dp_money>".$v["s"].",dp_uid,null)) as pay_".$k.",";
			$str .= "count(if(dp_money>".$v["s"].",dp_uid,null)) as nums_".$k.",";
			$str .= "sum(if(dp_money>".$v["s"].",dp_money,0)) as money_".$k;
		}else{
			$str .= "count(distinct if(dp_money>".$v["s"]." and dp_money<".$v["e"].",dp_uid,null)) as pay_".$k.",";
			$str .= "count(if(dp_money>".$v["s"]." and dp_money<".$v["e"].",dp_uid,null)) as nums_".$k.",";
			$str .= "sum(if(dp_money>".$v["s"]." and dp_money<".$v["e"].",dp_money,0)) as money_".$k.",";
		}
		$j   += 1;
	}
}


//查询日期
$d      = date("d",strtotime($date));
$m 		= date("m",strtotime($date));
$w 		= date("w",strtotime($date));
$y 		= date("y",strtotime($date));

$sdate  = date("Ymd",mktime(0, 0 , 0,$m,$d-$w+1,$y));	//开始时间
$edate  = date("Ymd",mktime(0, 0 , 0,$m,$d-$w+7,$y));	//结束时间

$date3  = date("Ymd",strtotime($sdate)-86400);			//本周开始时间-1
$date4  = date("Ymd",strtotime($edate)+86400);			//本周结束时间+1

$date5  = date("Ym",mktime(0,0,0,$m-1,1,$y))."31";  //本月开始时间-1
$date6  = date("Ym",mktime(0,0,0,$m+1,1,$y))."01";  //本月结束时间+1

// echo $date1.'-->'.$date2."<br>";
// echo $date3.'-->'.$date4;
// exit;


// 查询当天充值相关
$sql   = "select dp_gid,dp_uaid,dp_uwid $str from ".get_table('paylog_log')." where dp_paydate > {$date1} and dp_paydate < {$date2} group by dp_gid,dp_uaid,dp_uwid";
$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	for($i=1;$i<=$cts;$i++){
		$key  = $rows["dp_gid"]."_".$rows["dp_uaid"]."_".$rows["dp_uwid"]."_".$i;
		$data[$key]["pays"]   = $rows["pay_".$i];
		$data[$key]["money"]  = $rows["money_".$i];
		$data[$key]["nums"]   = $rows["nums_".$i];
 	}
}
$DB->FreeResult($query);



//查询当前自然周充值相关信息
$sql   = "select dp_gid,dp_uaid,dp_uwid $str from ".get_table('paylog_log')." where dp_paydate > {$date3} and dp_paydate < {$date4} group by dp_gid,dp_uaid,dp_uwid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	for($i=1;$i<=$cts;$i++){
		$key  = $rows["dp_gid"]."_".$rows["dp_uaid"]."_".$rows["dp_uwid"]."_".$i;
		$data[$key]["wpays"]   = $rows["pay_".$i];
		$data[$key]["wmoney"]  = $rows["money_".$i];
		$data[$key]["wnums"]   = $rows["nums_".$i];
 	}
}
$DB->FreeResult($query);


//查询当前自然月充值相关信息
$sql   = "select dp_gid,dp_uaid,dp_uwid $str from ".get_table('paylog_log')." where dp_paydate > {$date5} and dp_paydate < {$date6} group by dp_gid,dp_uaid,dp_uwid";
$query =  $DB->Query($sql);
while($rows = $DB->FetchArray($query)){
	for($i=1;$i<=$cts;$i++){
		$key  = $rows["dp_gid"]."_".$rows["dp_uaid"]."_".$rows["dp_uwid"]."_".$i;
		$data[$key]["mpays"]   = $rows["pay_".$i];
		$data[$key]["mmoney"]  = $rows["money_".$i];
		$data[$key]["mnums"]   = $rows["nums_".$i];
 	}
}
$DB->FreeResult($query);


// 数据入库处理
if (!empty($data)) {
	foreach ($data as $k=>$value) {
		//过滤0值数据
		if(array_sum($value)==0){continue;}

		// 判断当前记录是否已存在
		$x_key = explode('_', $k);
		$whereArr = array(
			'pr_date' 		=> $date,
			'pr_gameid'		=> $x_key[0],
			'pr_uaid'		=> $x_key[1],
			'pr_uwid'		=> $x_key[2],
			'pr_range'		=> $x_key[3],
			);
		// 更新的数据
		$dataArr = array(
			'pr_date' 		=> $date,
			'pr_gameid'		=> $x_key[0],
			'pr_uaid'		=> $x_key[1],
			'pr_uwid'		=> $x_key[2],
			'pr_range'		=> $x_key[3],
			'pr_pays'		=> $value["pays"],
			'pr_wpays'		=> $value["wpays"],
			'pr_mpays'		=> $value["mpays"],
			'pr_money'		=> $value["money"],
			'pr_wmoney'		=> $value["wmoney"],
			'pr_mmoney'		=> $value["mmoney"],
			'pr_nums'		=> $value["nums"],
			'pr_wnums'		=> $value["wnums"],
			'pr_mnums'		=> $value["mnums"],
			'pr_uptime' 	=> time(),
			);
		// 判断是否存在该记录
		if (exist_check($DB, "pay_range", $whereArr)) {
			// 更新记录
			$res = update_record($DB, 'pay_range', $dataArr, $whereArr);
			if (!$res) {
				// 更新失败
				$msg .="<br>" . "每天游戏充值区间统计数据更新出错" . __LINE__;
				break;
			}
			$unum++;
		} else {
			// 添加一条记录
			$res = add_record($DB, 'pay_range', $dataArr);
			if ($res['rows'] < 1) {
				$msg .= "<br>" . "每天游戏充值区间数据插入数据库出错" . __LINE__;
				break;
			}
			$inum++;
		}
	}
	$msg = $date." 每天游戏充值区间数据更新完成!<br/>";

	// 记录计划任务日志
	if ($operType == 1) {
		//写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_pay_range.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
	}
} else {
	$msg .="每天游戏充值区间-该天没有数据可更新,更新日期:".$date."<br>";
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