<?php
#====================================================================================
# 	FileName: update_newpay.php
# 		Desc: 每天充值统计数据更新，包括自动更新和手动更新
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.08.31
# LastChange: cooper 2016.3.3
#====================================================================================

// 包含总配置文件
include_once(dirname(__FILE__).'/../config.inc.php');
get_games_conn();

$msg = '';	// 定义消息变量
$key_arr = array();
$data = array();//充值相关

// 命令行中是否有传递参数
if ($_SERVER['argv']) {
	$cs = $_SERVER['argv'];
    $params = explode("_",$cs[1]);
}

// 获取要更新的时间段
$date = !empty($params[0]) ? $params[0] : get_param('date');

// 更新类型：1、普通更新 2、强制更新(把旧数据重新统计一次)；本功能默认为普通更新
$forceupdate = !empty($params[2]) ? $params[2] : get_param('forceupdate','int');

// 时间点位置处理
if (!empty($date1) && !empty($date2)) {
	if ($date1 > $date2) {
		list($date1, $date2) = array($date2, $date1);
	}
}

// 判断自动更新还是手动更新
if (empty($date)) {
	// 自动更新
	$operType = 1; // 自动更新(计划任务标识)
	$date = date("Ymd",strtotime("-1 day"));
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
	$sql_del = "delete from ".get_table('game_daypay')." where gn_date > $date1 and gn_date < $date2";
	$DB->Query($sql_del);
	$res['rows'] = $DB->AffectedRows();
	if ($res['rows'] < 0) {
		$msg .= '<br/>删除'.$date.'记录出错'.__LINE__;
	}
}

// 查询当天充值总人数、充值总次数、充值总金额;首次付费充值人数、充值次数、充值金额；首日充值人数、充值次数、充值金额
$sql = "select dp_paydate,dp_gid,dp_uwid,dp_uaid,count(distinct dp_uid) as paynum,sum(dp_money) as paymoney,count(*) as orders, count(distinct if(dp_isfirst = 1, 1, null)) as fpaynum,count(distinct if(dp_paydate = dp_regdate, dp_uid, null)) as dpaynum,sum(if(dp_paydate = dp_regdate, dp_money, 0)) as dpaymoney,sum(if(dp_paydate = dp_regdate, 1, 0)) as dorders from ".get_table("paylog_log")."  where dp_paydate > $date1 and dp_paydate < $date2 group by dp_paydate,dp_gid,dp_uwid,dp_uaid";

$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dp_paydate'].'_'.$rows['dp_gid'].'_'.$rows['dp_uwid'];
	$key_arr[$key]=$rows['dp_paydate'].'_'.$rows['dp_gid'].'_'.$rows['dp_uaid'].'_'.$rows['dp_uwid'];//日期，游戏，主渠道，渠道
	//$rows['fpaynum'] = ($rows['fpaynum'])>1?$rows['fpaynum']-1:$rows['fpaynum'];
	$data[$key]['paynum']     = $rows['paynum'];	// 总充值人数
	$data[$key]['paymoney']   = $rows['paymoney'];	// 总充值金额
	$data[$key]['orders']     = $rows['orders'];	// 总充值次数
	$data[$key]['fpaynum']    = $rows['fpaynum'];	// 首次充值总人数
	$data[$key]['dpaynum']    = $rows['dpaynum'];	// 首日充值总人数
	$data[$key]['dpaymoney']  = $rows['dpaymoney'];	// 首日充值总金额
	$data[$key]['dorders']    = $rows['dorders'];	// 首日充值总次数
}

// 查询首次充值次数、充值金额
$sql = "select dp_paydate,dp_gid,dp_uwid,dp_uaid,sum(dp_money) fpaymoney,count(*) as forders from ".get_table("paylog_log")."  where dp_paydate > $date1 and dp_paydate < $date2 and dp_uid in (select group_concat(dp_uid) from ".get_table("paylog_log")." where dp_paydate > $date1 and dp_paydate < $date2 and dp_isfirst = 1) group by dp_paydate,dp_gid,dp_uwid,dp_uaid";

$query = $DB->Query($sql);
while ($rows = $DB->FetchArray($query)) {
	$key = $rows['dp_paydate'].'_'.$rows['dp_gid'].'_'.$rows['dp_uwid'];
	$key_arr[$key]=$rows['dp_paydate'].'_'.$rows['dp_gid'].'_'.$rows['dp_uaid'].'_'.$rows['dp_uwid'];//日期，游戏，主渠道，渠道
	$data[$key]['fpaymoney']  = $rows['fpaymoney'];	// 首次充值总金额
	$data[$key]['forders']    = $rows['forders'];	// 首次充值总次数
}

if(count($key_arr)>0){
	//处理消息入库
	foreach ($key_arr as $k =>$v) {
		// 判断数据表是否存在记录条件
		$k_res = explode('_', $v);
		$whereArr = array(
			'cgd_date'   	=> $k_res[0],		// 日期
			'cgd_gameid' 	=> $k_res[1],	 	// 游戏ID
			'cgd_uwid'    	=> $k_res[3]	 	// 子渠道ID
		);
		// 更新的数据
		$dataArr = array(
			'cgd_date'   	=> $k_res[0],		// 日期
			'cgd_gameid' 	=> $k_res[1],	 	// 游戏ID
			'cgd_uaid' 		=> $k_res[2],		// 渠道ID
			'cgd_uwid'    	=> $k_res[3],	 	// 子渠道ID
			'cgd_fpay'   	=> $data[$k]['fpaynum'] ? $data[$k]['fpaynum'] : 0,			// 首次付费用户充值人数
			'cgd_fmoney' 	=> $data[$k]['fpaymoney'] ? $data[$k]['fpaymoney'] : 0,		// 首次付费用户充值金额
			'cgd_fnums'   	=> $data[$k]['forders'] ? $data[$k]['forders'] : 0,			// 首次付费用户充值次数
			'cgd_pay'    	=> $data[$k]['paynum'] ? $data[$k]['paynum'] : 0,			// 当天充值总人数
			'cgd_nums'    	=> $data[$k]['orders'] ? $data[$k]['orders'] : 0,			// 当天充值总次数
			'cgd_money'  	=> $data[$k]['paymoney'] ? $data[$k]['paymoney'] : 0,		// 当天充值总金额
			'cgd_dpay'    	=> $data[$k]['dpaynum'] ? $data[$k]['dpaynum'] : 0,			// 首日充值总人数
			'cgd_dnums'   	=> $data[$k]['dorders'] ? $data[$k]['dorders'] : 0,			// 首日充值总次数
			'cgd_dmoney'   	=> $data[$k]['dpaymoney'] ? $data[$k]['dpaymoney'] : 0,		// 首日充值总金额
			'cgd_uptime'    => time(),													// 最后更新时间
		);
		// 判断是否存在该记录
			if (exist_check($DB, "game_daypay", $whereArr)) {
				// 更新记录
				$res = update_record($DB, 'game_daypay', $dataArr, $whereArr);
				if (!$res) {
					// 更新失败
					$msg .="<br>".$rows['dg_logdate']."每天充值统计数据更新出错" . __LINE__;
					break;
				}
				$unum++;
			} else {
				// 添加一条记录
				$res = add_record($DB, 'game_daypay', $dataArr);
				if ($res['rows'] < 1) {
					$msg .= "<br>".$rows['dg_logdate']."每天充值统计数据插入数据库出错" . __LINE__;
					break;
				}
				$inum++;
			}
			$msg .= $rows['dg_logdate']." 每天充值数据更新完成!"."<br>";
			// 记录计划任务日志
			if ($operType == 1) {
			  	//写入日志
	            $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
	            if(!is_dir($path_log)){
	                mkdir($path_log);
	            }
	            $file_log = $path_log."/update_newpay.txt";
	            file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
			}
	}
	
}else{
	// 没有当天记录
	$msg .="每天充值数据-该天没有数据可更新,更新日期:".$date1."<br>";
}

// 释放结果内存
$DB->FreeResult($query);

// 关闭数据连接
if ($DB) {
	$DB->Close();
}

// 返回信息处理
$myaction = get_param("ac"); //操作方式
$jsonp 	  = $_GET ['callback'];

//销毁变量
unset($key_arr,$data1,$data2,$data3,$data4,$data5,$data6);
if ($myaction == 1) {
	// 返回json数据
	$json = json_encode(array('msg'=>$msg));
	ob_clean();// 清除ob缓存
	exit($jsonp.'('.$json.')');
} else {
	exit($msg);
}

?>