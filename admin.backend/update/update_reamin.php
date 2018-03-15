<?php
#=============================================================================
#     FileName: update_remain.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 更新remain留存数据
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.game_count.com/
#      Version: 0.0.2
#   LastChange: 2015-08-27
#      History: 0.0.1
#=============================================================================
set_time_limit(0);
include_once(dirname(__FILE__).'/../config.inc.php');
get_games_conn();

// 命令行中是否有传递参数
if ($_SERVER['argv']) {
	$cs     = $_SERVER['argv'];
    $params = explode("_",$cs[1]);
}
// 获取要更新的时间段
$date = !empty($params[0]) ? $params[0] : get_param('date');

$forceupdate = !empty($params[2])?$params[2]:intval(get_param("forceupdate")); //1为普通更新 2为强制更新（把旧数据重新统计一次）,本功能默认为强制更新

if (empty($date)) {
    // 自动更新
    $autoflag = 1; // 计划任务标识
    $date     = date("Ymd",strtotime("-1 day"));
} else {
    // 手动更新
    $date = $date;
}

$date1  = date("Ymd",strtotime($date)-1);
$date2  = date("Ymd",strtotime($date)+86400);

$msg    = "";
$unum   = 0;//更新条数
$inum   = 0;//插入条数

//强制更新删除旧数据
if ($forceupdate == 2) {
    // 删除当天表中的数据
    $sql_del = "delete from ".get_table('game_remain')." where cgr_logdate > $date1 and cgr_logdate < $date2";
    $DB->Query($sql_del);
    $res['rows'] = $DB->AffectedRows();
    if ($res['rows'] < 0) {
        $msg .= '<br/>删除'.$date.'记录出错'.__LINE__;
    }
}

//平台留存率
$pst_sql  =  "SELECT dg_uaid,dg_uwid,dg_gid,dg_regtime,dg_logtime,dg_logdate,dg_regdate,count(distinct dg_uid) AS nums,datediff(dg_logdate,dg_regdate) AS diff FROM ".get_table("gamelogin_log")." WHERE 1 and dg_logdate>".$date1." and dg_logdate<".$date2." and datediff(dg_logdate,dg_regdate)<31 and datediff(dg_logdate,dg_regdate)>-1 GROUP BY diff,dg_regdate,dg_uaid,dg_uwid,dg_gid";
$pt_re   =  $DB->Query($pst_sql);
if($pt_re){
    $pt_nums = $DB->NumRows($pt_re);
    if($pt_nums>0){
        while($row = $DB->FetchArray($pt_re)){
            //判断数据表是否存在这条数据
            if($row["diff"]<0 || $row["diff"]>30){
                continue;
            }
            $wherearr = array(
                "cgr_gameid"    =>  $row["dg_gid"],
                "cgr_uaid"      =>  $row["dg_uaid"],
                "cgr_uwid"      =>  $row["dg_uwid"],
                "cgr_regdate"   =>  $row["dg_regdate"],
                "cgr_diff"      =>  $row["diff"]
            );
            $pt_arr  = array(
                "cgr_gameid"    =>  $row["dg_gid"],
                "cgr_uaid"      =>  $row["dg_uaid"],
                "cgr_uwid"      =>  $row["dg_uwid"],
                "cgr_remain"    =>  $row["nums"],
                "cgr_logtime"   =>  $row["dg_logtime"],
                "cgr_logdate"   =>  $row["dg_logdate"],
                "cgr_regtime"   =>  $row["dg_regtime"],
                "cgr_regdate"   =>  $row["dg_regdate"],
                "cgr_diff"      =>  $row["diff"],
                "cgr_uptime"    =>  THIS_DATETIME
            );

            //判断是否存在记录
            if(exist_check($DB,'count_game_remain', $wherearr,2)){
               //更新数据
               $info = update_record($DB,'count_game_remain',$pt_arr,$wherearr,"",2);
               if(!$info){
                    $msg .="<br>"."注册留存数据更新出错".__LINE__;
                    break;
                }
                $unum++;
            }else{
                $pt_info = add_record($DB,"count_game_remain",$pt_arr,'',2);
    		    if($pt_info['rows']<1){
                        $msg .= "<br>"."留存数据更新失败，失败原因 ：插入数据库出错".__LINE__;
                        break;
                    }
                $inum++;
            }
            $msg .= $row["dg_logdate"]." 平台留存数据更新完成!<br>";
        }
        //记录计划任务日志
        if ($autoflag == 1) {
            //写入日志
            $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
            if(!is_dir($path_log)){
                mkdir($path_log);
            }
            $file_log = $path_log."/update_reamin.txt";
            file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
         }
    }
}else{
    $msg .="留存数据更新失败，失败原因 ：没有数据可更新".$date."<br>";
}
$DB->FreeResult($pt_re);
//关闭MYSQL链接
if($DB){
$DB->Close();
}
$myaction = get_param("ac");//操作方式
$jsonp = $_GET['callback'];

if($myaction=="1"){//如果是用JSON的方式，则
	$return_arr["msg"] = $msg;
	$json = json_encode($return_arr);
	ob_clean();
	echo $jsonp.'('.$json.')';
	exit();
}else{
	echo($msg);
	exit();
}

?>
