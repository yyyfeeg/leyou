<?php
#====================================================================================================================
# 	FileName: get_create_role.php
# 		Desc: 计划任务执行创角数据(redis)写入mysql操作
# 	  Author: Jericho
# 		Date: 2017.07.21    
#===================================================================================================================

// 设置执行时间
set_time_limit(0);

// 包含总配置文件
include_once(dirname(__FILE__).'/../config.inc.php');

// 获取广告主站uaid
$uaidArr = get_adsite_uaid($GLOBALS['base']);

// 切换数据库
cutover_db_count();

// 数据表名
$tableName = 'count_create_role_log';

// 集合中的基数
$nums = $GLOBALS['redis']->sCard('create_role');

if ($nums) {

	// $num = ($nums>2000)? 2000:$nums;
	$num = $nums;
	// 当前存在创角数据，进行更新操作
	$rows 	= 0;	//插入行数
	$values = '';	//数据
	$error  = '';   //错误数据
	$status = 'ok';	//插入状态
	$sql_base = "insert into $tableName(dr_time,dr_gid,dr_uaid,dr_uwid,dr_sid,dr_uid,dr_name,dr_mac,dr_dnumber,dr_ip,dr_gname,dr_guid,dr_date,dr_vender,dr_dmodel) values";
	for ($i=0; $i < $num; $i++) {
		$data 	 = $GLOBALS['redis']->sPop('create_role');
		$tmpArr  = explode('дк', $data);

		// 过滤错误uaid的上报，并记录本地log
        // if (!in_array($tmpArr[2],$uaidArr)) {
        //     $error .= '['.date('Y-m-d H:i:s',$tmpArr[0]).'/gid:'.$tmpArr[1].'/uaid:'.$tmpArr[2].'/uwid:'.$tmpArr[3].'] - - '.$data.PHP_EOL;
        //     continue;
        // }

		//检测是否广告用户
		$ad_where  = array(
			"ciw_gid" => $tmpArr[1],
			"ciw_ch"  => $tmpArr[3],	
			"ciw_dev" => $tmpArr[8],
		);
		$ad_return = exist_aduser($GLOBALS['count'],$ad_where);
		if(!empty($ad_return)){
			//调用广告回调接口
			$ad_url = WEBPATH_DIR_NW."api/ad_send.php";
			$posdata = array(
				"event_type"=> 1,
				"conv_time" => $tmpArr[0],
				"callback" 	=> $ad_return["ciw_callback"],
				"os"	   	=> $ad_return["ciw_os"],
				"dev"     	=> $ad_return["ciw_dev"],
				"tp"		=> $ad_return["ciw_tp"],
				"ciwid"		=> $ad_return["sysid"],
				"ch"		=> $ad_return["ciw_ch"],
				"gid"		=> $ad_return["ciw_gid"],
			);
			post_request($ad_url,$posdata,"post");
		}

		$uid     = $tmpArr[5];
		$uname   = $tmpArr[6];
		$cdate 	 = date('Ymd', $tmpArr[0]);	//创角日期
		$values .= "('{$tmpArr[0]}','{$tmpArr[1]}','{$tmpArr[2]}','{$tmpArr[3]}','{$tmpArr[4]}','{$uid}','{$uname}','{$tmpArr[7]}','{$tmpArr[8]}','{$tmpArr[9]}','{$tmpArr[11]}','{$tmpArr[12]}','{$cdate}','{$tmpArr[14]}','{$tmpArr[15]}'),";

		// 一次性插入500条数据
		if ($rows == 1000) {
			$sql = $sql_base.substr($values, 0, -1);
			$result = $GLOBALS['count']->Query($sql);
			if (!$result) {
				// 插入失败，记录本地log日志
				$sql_error_log = WEBPATH_DIR."lylogs/createrole_sql_error_".date('Ym',time()).".log";
				if (!file_exists($sql_error_log)) {
					touch($sql_error_log);
				}
				file_put_contents($sql_error_log, $sql, FILE_APPEND | LOCK_EX);
				$status = 'error';
			}
			// 清空之前的数据
			$values = '';
			$rows 	= 0;
		}
		$rows++;
	}

	// 处理不够500行的数据
	if (!empty($values)) {
		$sql = $sql_base.substr($values, 0, -1);
		$result = $GLOBALS['count']->Query($sql);
		if (!$result) {
			// 插入失败，记录本地log日志
			$sql_error_log = WEBPATH_DIR."lylogs/createrole_sql_error_".date('Ym',time()).".log";
			if (!file_exists($sql_error_log)) {
				touch($sql_error_log);
			}
			file_put_contents($sql_error_log, $sql, FILE_APPEND | LOCK_EX);
			$status = 'error';
		}
	}

	// 将错误上报数据记录本地log
    $log_file = WEBPATH_DIR."lylogs/createrole_errordata_".date('Ym',time()).".log";
    if (!empty($error)) {
        if (!file_exists($log_file)) {
            touch($log_file);
        }
        file_put_contents($log_file, $error, FILE_APPEND | LOCK_EX);
    }

	echo "更新完成，时间：".date("Y-m-d H:i:s")." 更新状态：".$status."\n";

} else {

	// 当前没有创角数据
	echo '暂无数据可更新，时间：'.date("Y-m-d H:i:s")."\n";

}
exit;
?>