<?php
#================================================================================
#   FileName: get_pay_log.php
#       Desc: 计划任务执行游戏充值数据(redis)写入mysql操作
#     Author: jericho
#       Date: 2017.07.23
#                               
#================================================================================

// 设置执行时间
set_time_limit(0);

// 包含总配置文件
include_once(dirname(__FILE__).'/../config.inc.php');

// 获取广告主站uaid
$uaidArr = get_adsite_uaid($GLOBALS['base']);

// 切换数据库
cutover_db_count();

// 数据表名
$table_name  = "count_paylog_log";
$role_table  = "count_create_role_log";
$login_table = "count_gamelogin_log";

$nums = $GLOBALS["redis"]->sCard("pay_log");

if ($nums) {

    // $num = ($nums>2000)? 2000:$nums;
    $num = $nums;
    $j = 0;
    $str = '';
    $state = "ok";
    $error = '';  //错误数据
    $sql_insert = "insert into $table_name(dp_paytime,dp_paydate,dp_orderid,dp_uaid,dp_uwid,dp_gid,dp_sid,dp_uid,dp_name,dp_money,dp_nums,dp_regtime,dp_regdate,dp_mac,dp_dnumber,dp_rid,dp_rname,dp_roletime,dp_roledate,dp_ip,dp_isfirst,dp_lv,dp_vender,dp_dmodel) values";
    for ( $i=0; $i < $num; $i++ ){

        $res    = $GLOBALS["redis"]->sPop("pay_log");
        $arr    = explode("дк",$res);

        // 过滤错误uaid的上报，并记录本地log
        // if (!in_array($arr[2],$uaidArr)) {
        //     $error .= '['.date('Y-m-d H:i:s',$arr[15]).'/gid:'.$arr[1].'/uaid:'.$arr[2].'/uwid:'.$arr[3].'] - - '.$res.PHP_EOL;
        //     continue;
        // }

        $uid    = $arr[5];
        $uname  = $arr[6];
        $pdate  = date('Ymd',$arr[0]);//充值日期

        //更改游戏信息(修真[2]--洪荒记[10002]为服务端上报，充值参数还是原来的修真[2])
        if ($arr[1] == "2") {
            $sql = "select sysid,ol_gid,ol_uaid,ol_uwid,ol_sid,ol_orderid,ol_ortherid,ol_transorder from ".get_table("orderform_log")." where ol_payresult=1 and ol_giveresult=1 and ol_money=".$arr[8]." and ol_gid=10002 and substring(ol_gameorder,instr(ol_gameorder,'|')+1)='".$arr[7]."' and ol_uid='".$uid."' and ol_rid='".$arr[12]."'";
            $haha = $GLOBALS["count"]->getOne($GLOBALS["count"]->query($sql));
            if(!empty($haha)){
                $arr[1] = $haha["ol_gid"];
                $arr[2] = $haha["ol_uaid"];
                $arr[3] = $haha["ol_uwid"];
                $arr[4] = $haha["ol_sid"];
            }
            unset($haha);
            unset($sql);
        }


        //更改渠道信息(部分游戏为服务端上报，分包会出现渠道参数为9999问题)
        if($arr[2]=="9999" || $arr[3]=="9999"){
            $sql = "select sysid,ol_uaid,ol_uwid,ol_orderid,ol_ortherid,ol_transorder from ".get_table("orderform_log")." where ol_payresult=1 and ol_paydate=".$pdate." and ol_gid=".$arr[1]." and ol_sid=".$arr[4]." and ol_uid=".$uid." and ol_rid=".$arr[12]." and (ol_orderid='".$arr[7]."' or ol_ortherid='".$arr[7]."' or ol_transorder='".$arr[7]."')";
            $haha = $GLOBALS["count"]->getOne($GLOBALS["count"]->query($sql));
            if(!empty($haha)){
                $arr[2] = $haha["ol_uaid"];
                $arr[3] = $haha["ol_uwid"];
            }
            unset($haha);
            unset($sql);
        }

        //检测是否广告用户
        $ad_where  = array(
            "ciw_gid" => $tmpArr[1],
            "ciw_ch"  => $tmpArr[3],    
            "ciw_dev" => $tmpArr[11],
        );
        $ad_return = exist_aduser($GLOBALS['count'],$ad_where);
        if(!empty($ad_return)){
            //调用广告回调接口
            $ad_url = WEBPATH_DIR_NW."api/ad_send.php";
            $posdata = array(
                "event_type"=> 2,
                "conv_time" => $tmpArr[0],
                "callback"  => $ad_return["ciw_callback"],
                "os"        => $ad_return["ciw_os"],
                "dev"       => $ad_return["ciw_dev"],
                "tp"        => $ad_return["ciw_tp"],
                "ciwid"     => $ad_return["sysid"],
                "ch"        => $ad_return["ciw_ch"],
                "gid"       => $ad_return["ciw_gid"],
            );
            post_request($ad_url,$posdata,"post");
        }



        //取出用户注册时间:
        if ($arr[17]==1) {
            $wheres = " and sysid = ".$arr[5];
            $uinfo  = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select ui_regtime from ".get_table("user_info")." where 1 $wheres"));
            $ttime  = $uinfo["ui_regtime"];
            $rtime  = empty($ttime)?0:$ttime;
            $rdate  = empty($ttime)?0:date("Ymd",$ttime);
        } else {
            // 查询第三方渠道用户第一次注册记录
            $wheres = " and dg_gid=".$arr[1]." and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_uid='".$arr[5]."'";
            $sql    = "select dg_regtime from $login_table where 1 $wheres order by sysid asc limit 1";
            $result = $GLOBALS['count']->getOne($GLOBALS['count']->Query($sql));
            $ttime  = empty($result['dg_regtime'])? 0:$result['dg_regtime'];
            $rtime  = empty($ttime)?0:$ttime;
            $rdate  = empty($ttime)?0:date("Ymd",$ttime);
        }
        unset($wheres);

        //查询当前是否游戏渠道首次付费 -- 未判定游戏服
        $wheres = " and dp_gid=".$arr[1]." and dp_uaid=".$arr[2]." and dp_uwid=".$arr[3]." and dp_uid='".$arr[5]."'";
        $sql    = "select 1 from $table_name where 1 $wheres";
        $result = $GLOBALS["count"]->getOne($GLOBALS["count"]->query($sql));
        if(!empty($result)){
            $isfirst = 0;
        }else{
            $isfirst = 1;
        }

        //更新是否付费用户--登录表：
        $loginfo  = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,dg_ispay from ".$login_table." where dg_gid=".$arr[1]." and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_logdate=".$pdate." and dg_uid='".$arr[5]."' order by sysid desc limit 1"));
        if(!empty($loginfo) && $loginfo["dg_ispay"]==0){
            $GLOBALS["count"]->query("update ".$login_table." set dg_ispay=1 where sysid=".$loginfo["sysid"]);
        }
        unset($loginfo);


        // 查询当前充值角色的创角时间
        $wheres   = " and dr_gid=".$arr[1]." and dr_sid=".$arr[4]." and dr_uaid=".$arr[2]." and dr_uwid=".$arr[3]." and dr_guid=".$arr[12]." and dr_uid='".$arr[5]."'";
        $sql      = "select dr_time from $role_table where 1 $wheres order by sysid desc limit 1";
        $result   = $GLOBALS['count']->getOne($GLOBALS['count']->Query($sql));
        $roletime = empty($result['dr_time'])?'':$result['dr_time'];
        $roledate = empty($result['dr_time'])?'':date('Ymd',$result['dr_time']);
        $username = $uname;

        $str.= "('{$arr[0]}','{$pdate}','{$arr[7]}','{$arr[2]}','{$arr[3]}','{$arr[1]}','{$arr[4]}','{$uid}','{$username}','{$arr[8]}','{$arr[9]}','{$rtime}','{$rdate}','{$arr[10]}','{$arr[11]}','{$arr[12]}','{$arr[13]}','{$roletime}','{$roledate}','{$arr[14]}','{$isfirst}','{$arr[17]}','{$arr[18]}','{$arr[19]}'),";

        if ( $j == 1000 ) {
           $tmp_arr = $sql_insert.substr($str,0,strlen($str)-1);
           $rs      = $GLOBALS["count"]->Query($tmp_arr);
           if ( !$rs ) {
                // 插入失败，记录本地log日志
                $sql_error_log = WEBPATH_DIR."lylogs/paylog_sql_error_".date('Ym',time()).".log";
                if (!file_exists($sql_error_log)) {
                    touch($sql_error_log);
                }
                file_put_contents($sql_error_log, $sql, FILE_APPEND | LOCK_EX);
               $state = "error";
           }
           $str = "";
           $j   = 0;
        }
        $j++;
        unset($uinfo);
    }
    
    if ( !empty($str) ) {
        $tmp_arr = $sql_insert.substr($str,0,strlen($str)-1);
        $rs      = $GLOBALS["count"]->Query($tmp_arr);
        if ( !$rs ) {
            // 插入失败，记录本地log日志
            $sql_error_log = WEBPATH_DIR."lylogs/paylog_sql_error_".date('Ym',time()).".log";
            if (!file_exists($sql_error_log)) {
                touch($sql_error_log);
            }
            file_put_contents($sql_error_log, $sql, FILE_APPEND | LOCK_EX);
            $state = "error";
        }
    }

    // 将错误上报数据记录本地log
    $log_file = WEBPATH_DIR."lylogs/paylog_errordata_".date('Ym',time()).".log";
    if (!empty($error)) {
        if (!file_exists($log_file)) {
            touch($log_file);
        }
        file_put_contents($log_file, $error, FILE_APPEND | LOCK_EX);
    }

    echo "更新完成，时间：".date("Y-m-d H:i:s")." 更新状态：".$state."\n";
}else{
    echo "暂无数据可更新，时间：".date("Y-m-d H:i:s")."\n";
}
exit;
?>