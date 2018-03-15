<?php

#=============================================================================
#     FileName: run_alldata.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 运营总数据(直接从日志中查询)
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2016-03-17
#      History:
#=============================================================================

class Run_alldata extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }
    /*运营总数据列表*/
    public function alldata(){
        if ($this->isadmin != 1 && !$this->checkright("run_alldata")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //查询出日所有数据
        $this->assign("gamestr",$this->get_select());
        $this->assign("run_alldata", "run_alldata");
        $this->assign('meg','您已进入总收入列表中心！<br>搜索：1、点击复选框，可选择是否开启时间选择；2、时间跨度建议不要太大！');
        $this->display("run_alldata.html");
    }

    public function cdata(){//圈圈数据
        if ($this->isadmin != 1 && !$this->checkright("run_alldata")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $dtype   = get_param("dtype")?get_param("dtype"):'';    //周期类型
        $game    = get_param("game")?get_param("game"):'';      //游戏id
        $time    = get_param("stime")?get_param("stime"):date('Ymd',time());    //开始时间
        switch ($dtype) {
            case 'w':
                //周时间跨度
                $this_time     = date('Ymd',strtotime("$time - 1 week"));//这周
                $last_time     = date('Ymd',strtotime("$time - 2 week"));//上周
                $same_time1    = date('Ymd',strtotime("$time - 1 month"));//上月同周star
                $same_time2    = date('Ymd',strtotime("$this_time - 1 month"));//上月同周end
                break;
            case 'm':
                //月时间跨度
                $this_time     = date('Ymd',strtotime("$time - 1 month"));//这月
                $last_time     = date('Ymd',strtotime("$time - 2 month"));//上月
                $same_time1    = date('Ymd',strtotime("$time - 1 year"));//上一年同月star
                $same_time2    = date('Ymd',strtotime("$this_time - 1 year"));//上一年同月end
                break;
            default:
                //天时间跨度
                $this_time      = $time;//这天
                $last_time      = date('Ymd',strtotime("$time - 1 day"));//上一天
                $same_time1     = date('Ymd',strtotime("$time - 1 month"));//上月同一天star
                $same_time2     = date('Ymd',strtotime("$time - 1 month"));//上月同一天end
                break;
        }
        get_games_conn();
        //查询新注册用户，游戏总流水，活跃用户，arpu值，付费率
        $where1 = $dtype=='d' ? "grt_date=$time" : "grt_date<=$time and grt_date>".$this_time;//条件一
        $where2 = $dtype=='d' ? "grt_date=$last_time" : "grt_date<=".$this_time." and grt_date>".$last_time;//条件二
        $where3 = $dtype=='d' ? "grt_date=$same_time1" : "grt_date>".$same_time1." and grt_date<=".$same_time2;//条件三

        $sql = "select sum(if(".$where1.",grt_reg_num,0)) as d1,sum(if(".$where2.",grt_reg_num,0)) as d2,sum(if(".$where3.",grt_reg_num,0)) as d3 from ".get_table('game_reg_time')." where (".$where1.") or (".$where2.") or (".$where3.")  and grt_gameid=$game";//注册
        $query  = $this->db->Query($sql);
        $arr1 = $this->db->getOne($query);

        $where1 = $dtype=='d' ? "dp_paydate=$time" : "dp_paydate>$time and dp_paydate<=".$this_time;//条件一
        $where2 = $dtype=='d' ? "dp_paydate=$last_time" : "dp_paydate>".$this_time." and dp_paydate<=".$last_time;//条件二
        $where3 = $dtype=='d' ? "dp_paydate=$same_time1" : "dp_paydate>".$same_time1." and dp_paydate<=".$same_time2;//条件三

        $sql = "select sum(if(".$where1.",dp_money,0)) as d1,sum(if(".$where2.",dp_money,0)) as d2,sum(if(".$where3.",dp_money,0)) as d3,if(".$where1.",count(distinct dp_uid),0) as du1,if(".$where1.",count(distinct dp_uid),0) as du2,if(".$where1.",count(distinct dp_uid),0) as du3 from ".get_table('paylog_log')." where (".$where1.") or (".$where2.") or (".$where3.") and dp_gid=$game";//总流水、付费去重人数
        $query  = $this->db->Query($sql);
        $arr2 = $this->db->getOne($query);

        $where1 = $dtype=='d' ? "dg_logdate=$time" : "dg_logdate>$time and dg_logdate<=".$this_time;//条件一
        $where2 = $dtype=='d' ? "dg_logdate=$last_time" : "dg_logdate>".$this_time." and dg_logdate<=".$last_time;//条件二
        $where3 = $dtype=='d' ? "dg_logdate=$same_time1" : "dg_logdate>".$same_time1." and dg_logdate<=".$same_time2;//条件三

        $sql = "select if(".$where1.",count(distinct dg_uid),0) as d1,if(".$where2.",count(distinct dg_uid),0) as d2,if(".$where3.",count(distinct dg_uid),0) as d3 from ".get_table('gamelogin_log')." where (".$where1.") or (".$where2.") or (".$where3.") and dg_gid=$game";//活跃用户
        $query  = $this->db->Query($sql);
        $arr3 = $this->db->getOne($query);

        for($i=1;$i<4;$i++){
            $arr4['d'.$i] = $arr2['du'.$i]?($arr2['d'.$i]/$arr2['du'.$i])*100:0;
            $arr5['d'.$i] = $arr1['d'.$i]?($arr2['du'.$i]/$arr1['d'.$i])*100:0;
        }
        echo json_encode(array('str'=>'1000','data'=>array($arr1,$arr2,$arr3,$arr4,$arr5)));
    }

    /*运营总数据存列表*/
    public function listinfo(){
        if ($this->isadmin != 1 && !$this->checkright("run_alldata")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $game     = get_param("game")?get_param("game"):'';      //游戏id
        $stime    = get_param("stime")?get_param("stime"):date('Ymd',time());    //开始时间
        $etime    = get_param("etime")?get_param("etime"):date('Ymd',time());    //开始时间

        //开始时间结束时间限制
        if(empty($stime) || empty($etime) || empty($game)){
            echo json_encode(array('str'=>'1001','meg'=>'开始时间、结束时间、游戏不能为空！'));
            die;
        }else{
            $stime = date("Ymd",strtotime($stime)-1);
            $etime = date("Ymd",strtotime($etime)+86400);
        }

        if(strtotime($etime) - strtotime($stime)<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        get_games_conn();
        //查询新注册用户，游戏总流水，活跃用户，arpu值，付费率
        $sql    = "select grt_reg_num as reg_nums,grt_date from ".get_table('game_reg_time')." where grt_gameid=$game and grt_date<$etime and grt_date>$stime group by grt_date order by grt_date asc";//注册
        $query  = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            $key                        = $rows['grt_date'];
            $keys[$key]                 = 1;
            $arr1[$key]['reg_nums']     = $rows['reg_nums'];
            $arr1['all_reg']            += $rows['reg_nums'];
        }

        //查询总流水和充值人数
        $sql    = "select sum(dp_money) as all_pay,count(distinct dp_uid) as all_nums from ".get_table('paylog_log')." where dp_gid=$game and dp_paydate<$etime and dp_paydate>$stime";//总流水和充值人数
        $query  = $this->db->Query($sql);
        $arr2_res = $this->db->getOne($query);
        $arr2['all_pay'] = $arr2_res['all_pay'];
        $arr2['all_nums']= $arr2_res['all_nums'];
        //$sql = "select sum(dp_money) as pay_nums,count(distinct dp_uid) as nums,dp_paydate from dcenter_count.`count_paylog_log_v` where dp_gid=1 and dp_paydate<20140106 and dp_paydate>20140100 group by dp_paydate order by dp_paydate asc";//测试数据
        $sql    = "select sum(dp_money) as pay_nums,count(distinct dp_uid) as nums,dp_paydate from ".get_table('paylog_log')." where dp_gid=$game and dp_paydate<$etime and dp_paydate>$stime group by dp_paydate order by dp_paydate asc";//总流水和充值人数
        $query  = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            $key                        = $rows['dp_paydate'];
            $keys[$key]                 = 1;
            $arr2[$key]['pay_nums']     = $rows['pay_nums'];
            $arr2[$key]['nums']         = $rows['nums'];
        }
        //活跃用户
        //$sql = "select count(distinct dg_uid) as all_login from dcenter_count.`count_gamelogin_log_v` where dg_gid=1 and dg_logdate<20140310 and dg_logdate>20140209";
        $sql    = "select count(distinct dg_uid) as all_login from ".get_table('gamelogin_log')." where dg_gid=$game and dg_logdate<$etime and dg_logdate>$stime";//总流水和充值人数
        $query  = $this->db->Query($sql);
        $arr3_res = $this->db->getOne($query);
        $arr3['all_login'] = $arr3_res['all_login'];
        //$sql = "select count(distinct dg_uid) as login_nums,dg_logdate from dcenter_count.`count_gamelogin_log_v` where dg_gid=1 and dg_logdate<20140310 and dg_logdate>20140209 group by dg_logdate order by dg_logdate asc";
        $sql    = "select count(distinct dg_uid) as login_nums,dg_logdate from ".get_table('gamelogin_log')." where dg_gid=$game and dg_logdate<$etime and dg_logdate>$stime group by dg_logdate order by dg_logdate asc";//总流水和充值人数
        $query  = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            $key            = $rows['dg_logdate'];
            $keys[$key]     = 1;
            $arr3[$key]['login_nums']   = $rows['login_nums'];
        }
        if(!empty($keys)){
            //总数据
            $right_arr[0] = array(
                ($stime+1)."-".($etime-1),//时间
                $arr1['all_reg']?$arr1['all_reg']:0,
                $arr2['all_pay']?$arr2['all_pay']:0,
                $arr3['all_login']?$arr3['all_login']:0,
                $arr2['all_nums']?($arr2['all_pay']/$arr2['all_nums'])*100:0,
                $arr1['all_reg']?($arr2['all_nums']/$arr1['all_reg'])*100:0,
            );
            foreach ($keys as $k => $v) {
                $right_arr[]=array(
                    $k,//时间
                    $arr1[$k]['reg_nums']?$arr1[$k]['reg_nums']:0,
                    $arr2[$k]['pay_nums']?$arr2[$k]['pay_nums']:0,
                    $arr3[$k]['login_nums']?$arr3[$k]['login_nums']:0,
                    $arr2[$k]['nums']?($arr2[$k]['pay_nums']/$arr2[$k]['nums'])*100:0,
                    $arr1[$k]['reg_nums']?($arr2[$k]['nums']/$arr1[$k]['reg_nums'])*100:0,
                );
            }
        }
        //查询留存end
        if(empty($right_arr)){
            echo json_encode(array('str'=>'1001','meg'=>'没有数据！'));
        }else{
            echo json_encode($right_arr);
        }
        die;
    }
}
?>

