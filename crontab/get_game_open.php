<?php
#==============================================================================
# 	FileName: get_game_open.php
# 		Desc: 计划任务执行打开游戏数据(redis)写入mysql操作
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.07.21
# 			  	
#==============================================================================

// 设置执行时间
set_time_limit(0);

// 包含总配置文件

include_once(dirname(__FILE__).'/../config.inc.php');

// 获取广告主站uaid
$uaidArr = get_adsite_uaid($GLOBALS['base']);

// 切换数据库
cutover_db_count();


// 数据表名
$tableName = 'count_gameopen_log';

// 集合中的基数
$nums = $GLOBALS['redis']->sCard('gameopen');

if ($nums) {
	
	// $num = ($nums>2000)? 2000:$nums;
	$num = $nums;
	$rows 	= 0;	//插入行数
	$values = '';	//数据
	$error  = '';	//错误数据
	$status = 'ok';	//插入状态
	$sql_base = "insert into $tableName(dg_opentime,dg_gid,dg_uaid,dg_uwid,dg_mac,dg_dnumber,dg_ip,dg_opendate,dg_vender,dg_dmodel) values";
	for ( $i=0; $i < $num; $i++ ) {
		$data 	 = $GLOBALS['redis']->sPop('gameopen');
		$tmpArr  = explode('дк', $data);
		$odate 	 = date('Ymd', $tmpArr[0]);	//打开日期

		// 过滤错误uaid的上报，并记录本地log
		// if (!in_array($tmpArr[2],$uaidArr)) {
		// 	$error .= '['.date('Y-m-d H:i:s',$tmpArr[0]).'/gid:'.$tmpArr[1].'/uaid:'.$tmpArr[2].'/uwid:'.$tmpArr[3].'] - - '.$data.PHP_EOL;
		// 	continue;
		// }

		// 去重操作
		$unique_sql = "select count(1) c from ".get_table('gameopen_log')." where dg_gid = ".$tmpArr[1]." and dg_uaid = ".$tmpArr[2]." and dg_uwid = ".$tmpArr[3]." and dg_dnumber = '".$tmpArr[5]."'";
		$unique_res = $GLOBALS['count']->getOne($GLOBALS['count']->Query($unique_sql));
		if ($unique_res["c"]) {
			continue;
		} 


		//检测是否广告用户
		$ad_where  = array(
			"ciw_gid" => $tmpArr[1],
			"ciw_ch"  => $tmpArr[3],	
			"ciw_dev" => $tmpArr[5],
		);
		$ad_return = exist_aduser($GLOBALS['count'],$ad_where);
		if(!empty($ad_return)){
			//调用广告回调接口
			$ad_url = WEBPATH_DIR_NW."api/ad_send.php";
			$posdata = array(
				"event_type"=> 0,
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

		
		//记录/更新设备注册信息
        $sql   = "select count(1) c from ".get_table("gameopen_devices")." where dg_gid=".$tmpArr[1]." and dg_uaid=".$tmpArr[2]." and dg_uwid=".$tmpArr[3]." and dg_dnumber='".$tmpArr[5]."'";
        $dinfo = $GLOBALS["count"]->getOne($GLOBALS["count"]->query($sql));
        if(empty($dinfo["c"])){
            $darr = array(
                "dg_regtime"    =>  $tmpArr[0],
                "dg_regdate"    =>  $odate,
                "dg_gid"        =>  $tmpArr[1],
                "dg_uaid"       =>  $tmpArr[2],
                "dg_uwid"       =>  $tmpArr[3],
                "dg_dnumber"    =>  $tmpArr[5],
                "dg_vender"     =>  $tmpArr[7],
                "dg_dmodel"     =>  $tmpArr[8],
            );
            add_record($GLOBALS["count"],"gameopen_devices",$darr);
        }
        unset($dinfo);



		// 拼接入库数据值
		$values .= "('{$tmpArr[0]}','{$tmpArr[1]}','{$tmpArr[2]}','{$tmpArr[3]}','{$tmpArr[4]}','{$tmpArr[5]}','{$tmpArr[6]}','{$odate}','{$tmpArr[7]}','{$tmpArr[8]}'),";

		// 一次插入5000行数据
		if ($rows == 1000) {
			$sql = $sql_base.substr($values, 0, -1);
			$result = $GLOBALS['count']->Query($sql);
			if (!$result) {
				// 插入失败，记录本地log日志
				$sql_error_log = WEBPATH_DIR."lylogs/gameopen_sql_error_".date('Ym',time()).".log";
				if (!file_exists($sql_error_log)) {
					touch($sql_error_log);
				}
				file_put_contents($sql_error_log, $sql, FILE_APPEND | LOCK_EX);
				$status = 'error';
			}
			// 清空之前的信息
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
			$sql_error_log = WEBPATH_DIR."lylogs/gameopen_sql_error_".date('Ym',time()).".log";
			if (!file_exists($sql_error_log)) {
				touch($sql_error_log);
			}
			file_put_contents($sql_error_log, $sql, FILE_APPEND | LOCK_EX);
			$status = 'error';
		}
	}

	// 将错误上报数据记录本地log
	$log_file = WEBPATH_DIR."lylogs/gameopen_errordata_".date('Ym',time()).".log";
	if (!empty($error)) {
		if (!file_exists($log_file)) {
			touch($log_file);
		}
		file_put_contents($log_file, $error, FILE_APPEND | LOCK_EX);
	}

	echo "更新完成，时间：".date("Y-m-d H:i:s")." 更新状态：".$status."\n";
	
} else {

	// 不存在打开游戏数据
	echo "暂无数据可更新，时间：".date("Y-m-d H:i:s")."\n";

}
exit;
?>
