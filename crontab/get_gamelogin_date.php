<?php
#================================================================================
#   FileName: get_gamelogin_date.php
#       Desc: 计划任务执行游戏登录数据(redis)写入mysql操作 -- 每日更新统计登录次数、最后登录时间
#       info: --暂时合并更新，后续根据具体效率看是否划分-- (重要)
#     Author: jericho
#       Date: 2017.07.23                       
#================================================================================

// 设置执行时间
set_time_limit(0);

// 包含中配置文件
include_once(dirname(__FILE__).'/../config.inc.php');

// 获取广告主站uaid
$uaidArr = get_adsite_uaid($GLOBALS['base']);

// 切换数据库
cutover_db_count();

// 数据表名
$table_name = "gamelogin_log";

$date       = date('Ymd',time());     // 当天日期


// 查询并更新登录次数表
$rtname1    = "{$date}-login_nums"; //登录次数
$rtname2    = "{$date}-login_time"; //登录时间
$data1      = $GLOBALS["redis"]->hkeys($rtname1);

if(!empty($data1)){
   foreach($data1 as $v){
        //获取数据并更新
        $sets["dg_nums"]         = $GLOBALS["redis"]->hget($rtname1,$v);
        $sets["dg_day_lasttime"] = $GLOBALS["redis"]->hget($rtname2,$v);
        $infos                   = explode("дк",$v);
        $wheres                  = array(
            "dg_gid"        =>  $infos[0],
            "dg_uid"        =>  $infos[1],
            "dg_logdate"    =>  $infos[2],
            "dg_sid"        =>  $infos[3],
            "dg_roleid"     =>  $infos[4],
            "dg_uaid"       =>  $infos[5],
            "dg_uwid"       =>  $infos[6],
        );
        update_record($GLOBALS["count"],$table_name,$sets,$wheres);
       }
    echo "登录次数&最后登录时间更新完成，时间：".date("Y-m-d H:i:s")."\n";
}else{
    echo "暂无登录次数&最后登录时间可更新，时间：".date("Y-m-d H:i:s")."\n";
}
unset($sets);
unset($infos);
?>