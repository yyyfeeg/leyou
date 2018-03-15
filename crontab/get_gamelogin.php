<?php
#================================================================================
#   FileName: get_gamelogin.php
#       Desc: 计划任务执行游戏登录数据(redis)写入mysql操作
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

//设置测试时间
$stime = time();

// 查询gamelogin条数
$nums = $GLOBALS["redis"]->sCard("gamelogin");

if ($nums) {
    // $num = ($nums>5000)? 5000:$nums;
    $num   = $nums;
    $date  = date("Ymd");
    $state = "ok";
    $error = ''; //错误数据
    for ( $i=0; $i < $num; $i++ ) {
        $login_nums = 1;
        $res        = $GLOBALS["redis"]->sPop("gamelogin");
        $arr        = explode("дк",$res);
        $uid        = $arr[5];
        $uname      = $arr[6];
        $dts        = date("Ymd",$arr[0]);
        $keys       = $arr[1]."дк".$arr[5]."дк".$dts."дк".$arr[4]."дк".$arr[12]."дк".$arr[2]."дк".$arr[3];
        $wheres     = " and dg_gid=".$arr[1]." and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_sid=".$arr[4]." and dg_uid='".$arr[5]."'  and dg_roleid=".$arr[12];
        // 过滤错误uaid的上报，并记录本地log
        // if (!in_array($arr[2],$uaidArr)) {
        //     $error .= '['.date('Y-m-d H:i:s',$arr[11]).'/gid:'.$arr[1].'/uaid:'.$arr[2].'/uwid:'.$arr[3].'] - - '.$res.PHP_EOL;
        //     continue;
        // }

        //查询账号注册时间
        if($arr[11]==1){
            $account_ts = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select ui_regtime from ".get_table("user_info")." where sysid=".$uid));
        }else{
            $account_ts = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select min(dg_regtime) as ui_regtime from ".get_table($table_name)." where dg_gid=".$arr[1]." and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_uid='".$arr[5]."'"));
        }

        //记录/更新设备注册信息
        $sql   = "select count(1) c from ".get_table("gamelogin_devices")." where dg_gid=".$arr[1]." and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_dnumber='".$arr[8]."'";
        $dinfo = $GLOBALS["count"]->getOne($GLOBALS["count"]->query($sql));
        if(empty($dinfo["c"])){
            $darr = array(
                "dg_regtime"    =>  $arr[0],
                "dg_regdate"    =>  $dts,
                "dg_gid"        =>  $arr[1],
                "dg_uaid"       =>  $arr[2],
                "dg_uwid"       =>  $arr[3],
                "dg_dnumber"    =>  $arr[8],
                'dg_vender'     =>  $arr[14],
                'dg_dmodel'     =>  $arr[15],
            );
            add_record($GLOBALS["count"],"gamelogin_devices",$darr);
        }
        unset($dinfo);


        // 查询用户是否有登录记录
        $exists_sql = "select count(1) as num from ".get_table($table_name)." where dg_logdate=".$dts." $wheres";
        $exists_res = $GLOBALS['count']->getOne($GLOBALS['count']->Query($exists_sql));

        //获取当前登录次数
        // $login_nums = $GLOBALS["redis"]->hget("{$dts}-login_nums",$keys);
        empty($login_nums)?$login_nums:1;    

        if (empty($exists_res["num"])) {
            //更新上一次登录数据:    
            $last_sql  = "select dg_gid,dg_sid,dg_logtime,dg_firstlogtime,dg_firstgame,dg_firstser from ".get_table($table_name)." where dg_logdate<".$dts."  and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_uid='".$arr[5]."' order by sysid desc limit 1";
            $last_res  = $GLOBALS['count']->getOne($GLOBALS['count']->Query($last_sql));

            //查询是否充值玩家：
            $ispay_res  = array();
            $ispay_sql  = "select dg_ispay from ".get_table($table_name)." where dg_logdate<".$dts." and dg_gid=".$arr[1]." and dg_uaid=".$arr[2]." and dg_uwid=".$arr[3]." and dg_uid='".$arr[5]."' order by dg_logtime desc limit 1";
            $ispay_res  = $GLOBALS['count']->getOne($GLOBALS['count']->Query($ispay_sql));

            $regtime = $account_ts["ui_regtime"]?$account_ts["ui_regtime"]:$arr[0];

            // 当前用户当天第一次注册登录，直接录入到mysql的登录日志表
            $data = array(
                'dg_regtime'        =>  $regtime,
                'dg_regdate'        =>  date("Ymd",$regtime),
                'dg_uaid'           =>  $arr[2],
                'dg_uwid'           =>  $arr[3],
                'dg_gid'            =>  $arr[1],
                'dg_sid'            =>  $arr[4],
                'dg_uid'            =>  $uid,
                'dg_name'           =>  $uname,
                'dg_logtime'        =>  $arr[0],
                'dg_logdate'        =>  $dts,
                'dg_lastserver'     =>  $arr[4],
                'dg_mac'            =>  $arr[7],
                'dg_dnumber'        =>  $arr[8],
                'dg_ip'             =>  $arr[9],
                'dg_type'           =>  $arr[11],
                'dg_lastgame'       =>  $arr[1],
                'dg_roleid'         =>  $arr[12],
                'dg_rolename'       =>  $arr[13],
                'dg_nums'           =>  $login_nums,
                'dg_firstgame'      =>  $arr[1],
                'dg_firstser'       =>  $arr[4],
                'dg_day_lasttime'   =>  $stime,
                'dg_ispay'          =>  $ispay_res["dg_ispay"],
                'dg_vender'         =>  $arr[14],
                'dg_dmodel'         =>  $arr[15],
            );

            if(!empty($last_res)){
                $data['dg_lastlogdate'] =  date("Ymd",$last_res["dg_logtime"]);
                $data["dg_lasttime"]    =  $last_res["dg_logtime"];
                $data["dg_lastgame"]    =  $last_res["dg_gid"];
                $data["dg_lastserver"]  =  $last_res["dg_sid"];
                $data["dg_firstgame"]   =  $last_res["dg_firstgame"];
                $data["dg_firstser"]    =  $last_res["dg_firstser"];
                $data['dg_firstlogtime']=  $last_res["dg_firstlogtime"];
                $first                  =  0;
            }else{
                $data['dg_lastlogdate'] =  $dts;
                $data['dg_firstlogtime']=  $arr[0];
                $data['dg_lasttime']    =  $arr[0];
                $data["dg_lastgame"]    =  $arr[1];
                $data["dg_lastserver"]  =  $arr[4];
                $first                  =  1;
            }
            add_record($GLOBALS["count"],$table_name,$data);

            if($first){
                // 第一次注册登录时，记录注册日志
                $reg_data = array(
                    "dl_time"           =>  ($arr[11]==1)?$account_ts["ui_regtime"]:$arr[0],
                    "dl_date"           =>  ($arr[11]==1)?date("Ymd",$account_ts["ui_regtime"]):$dts,
                    "dl_uaid"           =>  $arr[2],
                    "dl_uwid"           =>  $arr[3],
                    "dl_gid"            =>  $arr[1],
                    "dl_sid"            =>  $arr[4],
                    "dl_uid"            =>  $uid,
                    "dl_name"           =>  $uname,
                    "dl_mac"            =>  $arr[7],
                    "dl_dnumber"        =>  $arr[8],
                    "dl_ip"             =>  $arr[9],
                    "dl_rid"            =>  $arr[12],
                    "dl_rname"          =>  $arr[13],
                    "dl_firstlogtime"   =>  $arr[0],
                    "dl_firstlogdate"   =>  $dts,
                    'dl_vender'         =>  $arr[14],
                    'dl_dmodel'         =>  $arr[15],

                );
                add_record($GLOBALS["count"],'gamereg_log',$reg_data);
            }
        
        } else {
            //判断今天是否登录过相同服，仅记录当天登录不同服信息
            $unique_res = $GLOBALS["redis"]->sismember("{$dts}-login_tmp",$keys);

            // 查询上次登录日志的数据
            $sql      = "select * from ".get_table($table_name)." where dg_logdate=".$dts." $wheres order by dg_logtime desc limit 1";
            $query    = $GLOBALS["count"]->query($sql);
            $return   = $GLOBALS["count"]->getOne($query);

            if (!empty($unique_res)) {
                // 今天有登录过相同服的，则更新登录次数
                $GLOBALS["count"]->query("update ".get_table($table_name)."  set dg_nums=".$login_nums." where sysid=".$return["sysid"]);
            } 
            continue;
        }
        unset($data);
    }

    // 将错误上报数据记录本地log
    $log_file = WEBPATH_DIR."lylogs/gamelogin_errordata_".date('Ym',time()).".log";
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