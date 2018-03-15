<?php
#=============================================================================
#     FileName: update_pay_remain.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 更新游戏充值remain留存数据
#       Author: Jericho
#     HomePage: http://www.game_count.com/
#      Version: 0.0.2
#   LastChange: 2017-08-04
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
$newarr = $news = array();

//强制更新删除旧数据
if ($forceupdate == 2) {
    // 删除当天表中的数据
    $sql_del = "delete from ".get_table('pay_remain')." where cpr_paydate > $date1 and cpr_paydate < $date2";
    $DB->Query($sql_del);
    $res['rows'] = $DB->AffectedRows();
    if ($res['rows'] < 0) {
        $msg .= '<br/>删除'.$date.'记录出错'.__LINE__;
    }
}


//游戏新增人数
$sql  = "select dg_regdate,dg_gid,dg_uaid,dg_uwid,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news from ".get_table("gamelogin_log")." where dg_logdate>{$date1} and dg_logdate<{$date2}  group by dg_gid,dg_uaid,dg_uwid,dg_regdate";
$query = $DB->query($sql);
while($rows = $DB->FetchArray($query)){
    if(empty($rows["news"])){
        continue;
    }
    $key = $rows["dg_regdate"]."_".$rows["dg_gid"]."_".$rows["dg_uaid"]."_".$rows["dg_uwid"];
    $newarr[$key]["news"] = $rows["news"];
}
$DB->FreeResult($query);

//游戏付费留存率
$pst_sql  =  "SELECT dp_uaid,dp_uwid,dp_gid,dp_regtime,dp_regdate,dp_paytime,dp_paydate,count(distinct dp_uid) AS nums,datediff(dp_paydate,dp_regdate) AS diff,sum(dp_money) as moneys FROM ".get_table("paylog_log")." WHERE 1 and dp_paydate>".$date1." and dp_paydate<".$date2." and datediff(dp_paydate,dp_regdate)<32 and datediff(dp_paydate,dp_regdate)>-1 GROUP BY diff,dp_regdate,dp_uaid,dp_uwid,dp_gid";
$pt_re   =  $DB->Query($pst_sql);
while($rows = $DB->FetchArray($pt_re)){
    $key = $rows["dp_regdate"]."_".$rows["dp_gid"]."_".$rows["dp_uaid"]."_".$rows["dp_uwid"];
    $newarr[$key][$rows["diff"]]["nums"]       = $rows["nums"];
    $newarr[$key][$rows["diff"]]["moneys"]     = $rows["moneys"];
    $newarr[$key][$rows["diff"]]["dp_paytime"] = $rows["dp_paytime"];
    $newarr[$key][$rows["diff"]]["dp_paydate"] = $rows["dp_paydate"];
    $newarr[$key][$rows["diff"]]["dp_regtime"] = $rows["dp_regtime"];
}

if(!empty($newarr) && count($newarr)>0){
    foreach($newarr as $k=>$v){
        $tkey = explode("_",$k);
        $row["dp_gid"]     = $tkey[1];
        $row["dp_uaid"]    = $tkey[2];
        $row["dp_uwid"]    = $tkey[3];
        $row["dp_regdate"] = $tkey[0];

        $row["news"]  = $v["news"];
        unset($v["news"]);

        if(empty($v)){
            $wherearr = array(
                "cpr_gameid"    =>  $row["dp_gid"],
                "cpr_uaid"      =>  $row["dp_uaid"],
                "cpr_uwid"      =>  $row["dp_uwid"],
                "cpr_regdate"   =>  $row["dp_regdate"],
                "cpr_diff"      =>  0,
                "cpr_regtime"   =>  0,
            );
            $pt_arr  = array(
            "cpr_gameid"    =>  $row["dp_gid"],
            "cpr_uaid"      =>  $row["dp_uaid"],
            "cpr_uwid"      =>  $row["dp_uwid"],
            "cpr_remain"    =>  0,
            "cpr_paytime"   =>  0,
            "cpr_paydate"   =>  0,
            "cpr_regtime"   =>  0,
            "cpr_regdate"   =>  $row["dp_regdate"],
            "cpr_diff"      =>  0,
            "cpr_money"     =>  0,
            "cpr_news"      =>  $row["news"],
            "cpr_uptime"    =>  THIS_DATETIME,
            );
            //判断是否存在记录
            if(exist_check($DB,'count_pay_remain', $wherearr,2)){
               //更新数据
               $info = update_record($DB,'count_pay_remain',$pt_arr,$wherearr,"",2);
               if(!$info){
                    $msg .="<br>"."付费留存数据更新出错".__LINE__;
                    break;
                }
                $unum++;
            }else{
                $pt_info = add_record($DB,"count_pay_remain",$pt_arr,'',2);
                if($pt_info['rows']<1){
                    $msg .= "<br>"."付费留存数据更新失败，失败原因 ：插入数据库出错".__LINE__;
                    break;
                }
                $inum++;
            }
        }else{
           foreach($v as $key=>$val){
            if($key<0 || $key>31){
                continue;
            }
            $row["diff"]        = $key;
            $row["nums"]        = $val["nums"]?$val["nums"]:0;
            $row["moneys"]      = $val["moneys"]?$val["moneys"]:0;
            $row["dp_paytime"]  = $val["dp_paytime"];
            $row["dp_paydate"]  = $val["dp_paydate"];
            $row["dp_regtime"]  = $val["dp_regtime"];

        $wherearr = array(
            "cpr_gameid"    =>  $row["dp_gid"],
            "cpr_uaid"      =>  $row["dp_uaid"],
            "cpr_uwid"      =>  $row["dp_uwid"],
            "cpr_regdate"   =>  $row["dp_regdate"],
            "cpr_diff"      =>  $row["diff"],
        );
        $pt_arr  = array(
            "cpr_gameid"    =>  $row["dp_gid"],
            "cpr_uaid"      =>  $row["dp_uaid"],
            "cpr_uwid"      =>  $row["dp_uwid"],
            "cpr_remain"    =>  $row["nums"],
            "cpr_paytime"   =>  $row["dp_paytime"],
            "cpr_paydate"   =>  $row["dp_paydate"],
            "cpr_regtime"   =>  $row["dp_regtime"],
            "cpr_regdate"   =>  $row["dp_regdate"],
            "cpr_diff"      =>  $row["diff"],
            "cpr_money"     =>  $row["moneys"],
            "cpr_news"      =>  $row["news"],
            "cpr_uptime"    =>  THIS_DATETIME,
        );

        //判断是否存在记录
        if(exist_check($DB,'count_pay_remain', $wherearr,2)){
           //更新数据
           $info = update_record($DB,'count_pay_remain',$pt_arr,$wherearr,"",2);
           if(!$info){
                $msg .="<br>"."付费留存数据更新出错".__LINE__;
                break;
            }
            $unum++;
        }else{
            $pt_info = add_record($DB,"count_pay_remain",$pt_arr,'',2);
 		    if($pt_info['rows']<1){
                $msg .= "<br>"."付费留存数据更新失败，失败原因 ：插入数据库出错".__LINE__;
                break;
            }
            $inum++;
        }
    }
  }
}
    $msg .= $date1."--".$date2." 付费留存数据更新完成!<br>";
    //记录计划任务日志
    if ($autoflag == 1) {
        //写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_pay_remain.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
     }
}else{
    $msg .="付费留存数据更新失败，失败原因 ：没有数据可更新".$date."<br>";
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
