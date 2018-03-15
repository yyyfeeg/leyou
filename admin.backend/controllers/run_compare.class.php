<?php

#=============================================================================
#     FileName: run_compare.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 运营对比数据(直接从日志中查询)
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2016-03-18
#      History:
#=============================================================================

class Run_compare extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }
    /*运营总数据列表*/
    public function alldata(){
        if ($this->isadmin != 1 && !$this->checkright("run_compare")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //查询出日所有数据
        $this->assign("gamestr",$this->get_select());
        $this->assign("games",get_game($this->db));
        $this->assign("run_compare", "run_compare");
        $this->assign('meg','您已进入总收入列表中心！<br>搜索：1、点击复选框，可选择是否开启时间选择；2、时间跨度建议不要太大！');
        $this->display("run_compare.html");
    }

    public function listdata(){//圈圈数据
        if ($this->isadmin != 1 && !$this->checkright("run_alldata")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $stime    = get_param("stime")?get_param("stime"):'';    //开始时间
        $etime    = get_param("etime")?get_param("etime"):'';    //开始时间

        if(empty($stime) || empty($etime)){
            echo json_encode(array('str'=>'1001','meg'=>'开始时间、结束时间、游戏不能为空！'));
            die;
        }else{
            $times = (strtotime($etime)-strtotime($stime)+86400)/86400;
            $stime1 = date("Ymd",strtotime($stime));
            $stime = date("Ymd",strtotime($stime)-1);
            $etime = date("Ymd",strtotime($etime)+86400);
        }
        get_games_conn();
        //活跃用户
        $sql    = "select count(distinct dg_uid) as all_login,dg_gid from ".get_table('gamelogin_log')." where dg_logdate<$etime and dg_logdate>$stime group by dg_gid";//总流水和充值人数
        $query  = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            $key                       = $rows['dg_gid'];
            $keys[$key]                = 1;
            $arr1[$key]['all_login']   = $rows['all_login'];
        }
        //注册用户
        $sql    = "select sum(grt_reg_num) as reg_nums,grt_gameid from ".get_table('game_reg_time')." where grt_date<$etime and grt_date>$stime group by grt_gameid";//注册
        $query  = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            $key                        = $rows['grt_gameid'];
            $keys[$key]                 = 1;
            $arr2[$key]['reg_nums']     = $rows['reg_nums'];
        }
        $where           = " where 1 and go_type=1";
        $stime?$where   .= " and go_regdate=".$stime1:'';
        $where          .= " and go_diff<".$times;
        //计算ltv值
        $sql = "SELECT sum(go_money) as all_pay,go_gameid as gameid FROM " .get_table('gamerrpay_outflow'). " $where group by go_gameid";
        $query = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $key            = $rows['go_gameid'];
            $keys[$key]     = 1;
            $arr3[$key]['all_pay']  = $rows['all_pay'];

        }
        //广告消耗
        $sql    = "SELECT sum(gn_use) as gm_uses,gn_gameid FROM " .get_table('game_newpay'). " where gn_date>$stime and gn_date<$etime group by gn_gameid";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $key            = $rows['gn_gameid'];
            $keys[$key]     = 1;
            $arr4[$key]['gm_uses']  = $rows['gm_uses'];
        }
        if(!empty($keys)){
            foreach ($keys as $k => $v) {
                $right_arr[]=array(
                    $k,//游戏名称
                    $arr4[$k]['gm_uses']?$arr3[$k]['all_pay']/$arr4[$k]['gm_uses']:0,//回款率
                    $arr1[$k]['all_login']?$arr1[$k]['all_login']:0,//活跃用户
                    $arr2[$k]['reg_nums']?($arr3[$k]['all_pay']/$arr2[$k]['reg_nums'])*100:0,//ltv
                    $arr2[$k]['reg_nums']?$arr2[$k]['reg_nums']:0,//总注册数
                    $arr3[$k]['all_pay']?$arr3[$k]['all_pay']:0,//总收入
                    $arr2[$k]['reg_nums']?$arr3[$k]['all_pay']/$arr2[$k]['reg_nums']:0,//总收入占比
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

    /*运营总数据存列表*/
    public function listinfo(){
        if ($this->isadmin != 1 && !$this->checkright("run_alldata")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $type     = get_param("type")?get_param("type"):'';      //数据类型
        $game     = get_param("game")?get_param("game"):'';      //游戏id
        $stime    = get_param("stime")?get_param("stime"):'';    //开始时间
        $etime    = get_param("etime")?get_param("etime"):'';    //开始时间
        $gids     = explode(',', $game);
        //开始时间结束时间限制
        if(empty($stime) || empty($etime) || empty($game) || empty($type)){
            echo json_encode(array('str'=>'1001','meg'=>'选项不能为空！'));
            die;
        }else{
            $times = (strtotime($etime)-strtotime($stime)+86400)/86400;
            $stime1= date("Ymd",strtotime($stime));
            $stime = date("Ymd",strtotime($stime)-1);
            $etime = date("Ymd",strtotime($etime)+86400);
        }
        if(strtotime($etime) - strtotime($stime)<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        get_games_conn();

        //通过type,创建数组
        for($m=0;$m<$times;$m++){
            $m1 = date('Ymd',strtotime($stime1) + ($m)*86400);
            for($i=1;$i<5;$i++){
                //计算日期
                $arrs[$type][$m1][$m1]   = $m1;
                $arrs[$type][$m1][$i]   = 0;
            }
        }
        if($type!='1' && $type!='2'){
            //游戏充值数据
            $where           = " where 1 and go_type=1";
            $stime?$where   .= " and go_regdate=".$stime1:'';
            $where          .= " and go_diff<".$times;
            $where          .= " and go_gameid in (".$game.")";
            $sql = "SELECT sum(go_money) as all_pay,go_uids,go_paydate,go_gameid FROM " .get_table('gamerrpay_outflow'). " $where group by go_diff,go_gameid order by go_diff,go_gameid asc";
            //$sql = "SELECT sum(go_money) as all_pay,go_uids,go_paydate,go_gameid FROM dcenter_count.`count_gamerrpay_outflow` where 1 and go_type=1 and go_regdate=20140101 and go_diff<4 and go_gameid in (1,2) group by go_diff,go_gameid order by go_diff,go_gameid asc";//测试数据
            $query = $this->db->Query($sql);
            while ($rows = $this->db->FetchArray($query)) {
                $all_pay[$rows['go_gameid']]                            += $rows['all_pay'];
                if($type==7){
                    //arpu
                    $arrs[$type][$rows['go_paydate']][$rows['go_gameid']]= $rows['go_uids']?$all_pay[$rows['go_gameid']]/$rows['go_uids']:0;
                }else{
                    //总充值
                    $arrs[$type][$rows['go_paydate']][$rows['go_gameid']]= $all_pay[$rows['go_gameid']];
                }
            }
        }
        //获取类型
        if($type==1 || $type==4){
            //某游戏新增注册
            $sql    = "select sum(grt_reg_num) as reg_nums,grt_date,grt_gameid from ".get_table('game_reg_time')." where grt_date<$etime and grt_date>$stime and grt_gameid in (".$game.") group by grt_date,grt_gameid order by grt_date,grt_gameid asc";//注册
            //$sql = "select sum(grt_reg_num) as reg_nums,grt_date,grt_gameid from dcenter_count.`count_game_reg_time` where grt_date<20131215 and grt_date>20131203 and grt_gameid in (1,2) group by grt_date,grt_gameid order by grt_gameid,grt_date asc";
            $query  = $this->db->Query($sql);
            while($rows = $this->db->FetchArray($query)){
                if($type==1){
                    //新增注册
                    $arrs[$type][$rows['grt_date']][$rows['grt_gameid']]= $rows['reg_nums'];
                }else{
                //LTV
                    $arrs[$type][$rows['grt_date']][$rows['grt_gameid']]    = $rows['reg_nums']?$arrs[$type][$rows['grt_date']][$rows['grt_gameid']]/$rows['reg_nums']:0; 
                }
            }
        }
        if($type==2 || $type==6){
            //某游戏活跃数据
            $sql    = "select count(distinct dg_uid) as all_login,dg_logdate,dg_gid from ".get_table('gamelogin_log')." where dg_logdate<$etime and dg_logdate>$stime and dg_gid in (".$game.") group by dg_logdate,dg_gid order by dg_logdate asc";//活跃用户
            //$sql = "select count(distinct dg_uid) as all_login,dg_logdate,dg_gid from dcenter_count.`count_gamelogin_log_v` where dg_logdate<20140211 and dg_logdate>20140201 and dg_gid in (1,2,3) group by dg_logdate,dg_gid order by dg_logdate asc";
            $query  = $this->db->Query($sql);
            while($rows = $this->db->FetchArray($query)){
                if($type==2){
                    $arrs[$type][$rows['dg_logdate']][$rows['dg_gid']] = $rows['all_login'];
                }else{
                    //付费渗透
                    $arrs[$type][$rows['dg_logdate']][$rows['dg_gid']] = $rows['all_login']?$arrs[$type][$rows['dg_logdate']][$rows['dg_gid']]/$rows['all_login']:0;
                }
            }
        }
        if($type==5){
            //广告付费数据
            $sql    = "SELECT sum(gn_use) as gm_uses,gn_gameid,gn_date FROM " .get_table('game_newpay'). " where gn_date>$stime and gn_date<$etime group by gn_gameid,gn_date";
            $query  = $this->db->Query($sql);
            while ($rows = $this->db->FetchArray($query)) {
                //回款率
                $arrs[$type][$rows['gn_date']][$rows['gn_gameid']] = $rows['gm_uses']?$arrs[$type][$rows['gn_date']][$rows['gn_gameid']]/$rows['gm_uses']:0;
            }
        }
        //返回数据
        $right_arr = delkeys($arrs[$type]);
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

