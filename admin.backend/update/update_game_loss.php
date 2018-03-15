<?php
#=============================================================================
#     FileName: update_game_loss.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 更新游戏玩家流失数据
#       Author:
#     HomePage: http://www.game_count.com/
#      Version: 0.0.1
#   LastChange: 2017-08-10
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

// 判断自动更新还是手动更新
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
$date3  = date("Ymd",strtotime($date1)-(31*86400));    //往前推30天


$msg    = "";
$unum   = 0;//更新条数
$inum   = 0;//插入条数
$newarr = $news = array();

//强制更新删除旧数据
if ($forceupdate == 2) {
    // 删除当天表中的数据
    $sql_del = "delete from ".get_table('game_loss')." where gl_logdate > $date1 and gl_logdate < $date2";
    $DB->Query($sql_del);
    $res['rows'] = $DB->AffectedRows();
    if ($res['rows'] < 0) {
        $msg .= '<br/>删除'.$date.'记录出错'.__LINE__;
    }
}

$sql  =  "SELECT min(dg_regdate) dg_regdate,min(dg_logdate) dg_logdate, MAX(dg_logdate) maxdate, dg_ispay, dg_gid, dg_uaid, dg_uwid, dg_uid FROM ".get_table("gamelogin_log")." WHERE dg_logdate>".$date3." and dg_logdate<".$date2." GROUP BY dg_uid";
//exit($sql);
$query = $DB->query($sql);
while($rows = $DB->FetchArray($query)){
    $diff = change_format($date,$rows["maxdate"],'d');
    $key = $rows["maxdate"]."_".$rows["dg_gid"]."_".$rows["dg_uaid"]."_".$rows["dg_uwid"];
    if ($diff > -1 && $diff < 31) {
        //活跃玩家
        $newarr[$key.'_2']["diff"] = $diff;
        $newarr[$key.'_2']["nums"] += 1;
        //新增玩家
        if ($rows["dg_regdate"] == $rows["dg_logdate"]) {
            $newarr[$key.'_1']["diff"] = $diff;
            $newarr[$key.'_1']["nums"] += 1;
        }
        //付费玩家
        if ($rows["dg_ispay"] == 1) {
            $newarr[$key.'_3']["diff"] = $diff;
            $newarr[$key.'_3']["nums"] += 1;
        }
        //非付费玩家
        if ($rows["dg_ispay"] == 0) {
            $newarr[$key.'_4']["diff"] = $diff;
            $newarr[$key.'_4']["nums"] += 1;
        }
    }else{
        continue;
    }
}
$DB->FreeResult($query);
if(!empty($newarr) && count($newarr)>0){
    foreach($newarr as $k=>$v){
        $tkey = explode("_",$k);
        if(!empty($v)){
            $whereArr = array(
                "gl_gameid"    =>  $tkey[1],
                "gl_uaid"      =>  $tkey[2],
                "gl_uwid"      =>  $tkey[3],
                "gl_logdate"   =>  $tkey[0],
                "gl_diff"      =>  $v["diff"],
                "gl_type"      =>  $tkey[4]
            );
            $dataArr  = array(
            "gl_gameid"    =>  $tkey[1],
            "gl_uaid"      =>  $tkey[2],
            "gl_uwid"      =>  $tkey[3],
            "gl_remain"    =>  $v["nums"],
            "gl_logdate"   =>  $tkey[0],
            "gl_diff"      =>  $v["diff"],
            "gl_type"      =>  $tkey[4],
            "gl_uptime"   =>  THIS_DATETIME
            );
            // 判断是否存在该记录
            if (exist_check($DB, "game_loss", $whereArr)) {
                // 更新记录
                $res = update_record($DB, 'game_loss', $dataArr, $whereArr);
                if (!$res) {
                    // 更新失败
                    $msg .="<br>" . "玩家流失数据更新出错" . __LINE__;
                    break;
                }
                $unum++;
            } else {
                // 添加一条记录
                $res = add_record($DB, 'game_loss', $dataArr);
                if ($res['rows'] < 1) {
                    $msg .= "<br>" . "玩家流失数据插入数据库出错" . __LINE__;
                    break;
                }
                $inum++;
            }
        }
    }
    $msg .= $date." 玩家流失数据更新完成!<br>";
    //记录计划任务日志
    if ($autoflag == 1) {
        //写入日志
        $path_log   = WEBPATH_DIR."lyuploads/upday_redis_log/";
        if(!is_dir($path_log)){
            mkdir($path_log);
        }
        $file_log = $path_log."/update_game_loss.txt";
        file_put_contents($file_log,'更新时间:'.$date."更新条数：".$unum.",插入条数：".$inum.chr(10), FILE_APPEND);
     }
}else{
        $msg .="玩家流失数据更新失败，失败原因 ：没有数据可更新".$date."<br>";
}
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
