<?php
/**************
	@更新每天时间段游戏创角统计     -- 暂时无需更新此功能至计划任务
	@CopyRight teamtop 
	@file:update_game_dayreg.php
	@author cooper
	@2016-02-26
***************/
include_once(dirname(__FILE__).'/../config.inc.php');
get_games_conn();
if($_SERVER["argv"]){
        $cs     = $_SERVER["argv"];
        $params = explode("_",$cs[1]);
}

//更新日期
$date1 = !empty($params[0])?$params[0]:get_param("date1");
$date2 = !empty($params[1])?$params[1]:get_param("date2");
$forceupdate = !empty($params[2])?$params[2]:intval(get_param("forceupdate")); //1为普通更新 2为强制更新（把旧数据重新统计一次）,本功能默认为强制更新


if(empty($date1) || empty($date2)){
    $date1 = date("Ymd",strtotime("-2 day"));
    $date2 = date("Ymd");
    $date  = date("Ymd");
    $autoflag = 1; //自动更新
} else {
    $date1 = date('Ymd', strtotime($date1)-1);
    $date2 = date('Ymd', strtotime($date2)+86400);
}

$msg = "";
$unum         = 0;//更新条数
$inum         = 0;//插入条数
//如果选择强制更新，则删除已有数据
if ($forceupdate == 2) {
    $sql = "delete from " . get_table("game_crole_time") . " where grt_date>" .$date1. " and grt_date<" .$date2;
    $DB->query($sql);
    $result['rows'] = $DB->AffectedRows();
    if ($result['rows'] < 0) {
        $msg .= "<br/>删除".$date."记录出错" . __LINE__;
    }
}

//获取每个时段游戏创角数
$sql_num = "select dr_date,dr_uid,count(*) as nums,dr_uaid,dr_uwid,dr_gid,FROM_UNIXTIME(dr_time,'%H') as crole_time from ".get_table("create_role_log")." where dr_date>$date1 and dr_date<$date2 group by dr_date,crole_time,dr_uaid,dr_uwid,dr_gid";
$hourarr = array();
$rs_num = $DB->query($sql_num);
if ($rs_num) {
	while ($row_num = $DB->FetchArray($rs_num)) {
		//组合时段注册人数数组
		$keys = $row_num["dr_date"].'_'. $row_num['dr_uaid'] . '_' . $row_num['dr_uwid'] . '_' .  $row_num['dr_gid'];
        $hourarr[$keys][$row_num['crole_time']] = $row_num['nums'];
        unset($keys);
	}
		
} else {
    $msg .="<br>更新出错" . __LINE__;
}
$DB->FreeResult($rs_num);

if (!empty($hourarr)) {
    foreach ($hourarr as $key => $row) {
        $ad_key = explode('_', $key);
        $wherearr = array(
            'gct_date' => intval($ad_key[0]),
            'gct_gameid' => intval($ad_key[3]), 
            'gct_aid' => intval($ad_key[1]),
            'gct_wid' => intval($ad_key[2]),
        );
        $all_reg = 0;
        for ($i = 0; $i < 24; $i++) {
            $a = ($i<10)?"0$i":$i;
			$row[$a] = intval($row[$a]);
            $arr['gct_hours_'.$a] = $row[$a];
            $all_reg += $row[$a];
        }
        $arr['gct_crole_num'] = $all_reg;
        $arr['gct_uptime'] = THIS_DATETIME;
        if (exist_check($DB, 'game_crole_time', $wherearr,'count',false)==true) {//存在记录
            //更新数据
            $info = update_record($DB, 'game_crole_time', $arr, $wherearr,'',false);
            if (!$info['succe']) {
                $msg .="<br>更新出错 " . __LINE__ . " $date";
                break;
            }
            $unum++;
        } else {
            $arr['gct_date'] = intval($ad_key[0]);
            $arr['gct_gameid'] = intval($ad_key[3]); 
            $arr['gct_aid'] = intval($ad_key[1]);
            $arr['gct_wid'] = intval($ad_key[2]);
            $info = add_record($DB, 'game_crole_time', $arr);
            if ($info['rows'] < 1) {
                $msg .= "<br/>嘿嘿，插入数据库出错 " . __LINE__ . " $date";
                break;
            }
            $inum++;
        }
	   $arr = array();
       unset($ad_key);
    }
    if($autoflag==1){
        //写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_game_dayrole.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
    }
    $msg .="<br>更新每天时间段游戏注册统计成功" . $date;
} else {
    $msg .="<br>当天无数据可更新:" . $date;
}
unset($hourarr);


//关闭MYSQL链接
if ($DB) {
    $DB->Close();
}

$myaction = get_param("ac"); //操作方式
$jsonp = $_GET['callback'];

if ($myaction == "1") {//如果是用JSON的方式，则
    $return_arr["msg"] = $msg;
    $json = json_encode($return_arr);
    ob_clean();
    echo $jsonp . '(' . $json . ')';
    exit();
} else {
    echo($msg);
    exit();
}

?>