<?php

#=============================================================================
#     FileName: count_dayreg.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 注册留存类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-09-28
#      History:
#=============================================================================

class Count_dayreg extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /*每日注册列表*/
    public function dayreg(){
        if ($this->isadmin != 1 && !$this->checkright("count_dayreg")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $this->assign("gamestr",$this->get_select());
        $this->assign("dayreg", "dayreg");
        $this->assign('meg','您已进入每日注册列表中心！');
        $this->display("count_dayreg.html");
    }
    
    /*注册留存列表*/
    public function dayreginfo(){
        if ($this->isadmin != 1 && !$this->checkright("count_dayreg")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $aarr = ad_get($this->db);
        $garr = get_game($this->db);
        get_param("starttime2")?$where  .= " and grt_date=>".date('Ymd',strtotime(get_param("starttime2"))):'';
        get_param("endtime2")?$where    .= " and grt_date<=".date('Ymd',strtotime(get_param("endtime2"))):'';
        get_param("gid")?$where         .= " and grt_gameid=".get_param("gid"):'';
        get_param("aid")?$where         .= " and grt_aid=".get_param("aid"):'';
        get_param("adsons")?$where      .= " and grt_wid=".get_param("adsons"):'';
        get_games_conn();
        $sql    = "SELECT * FROM " .get_table('game_reg_time'). " $where group by grt_gameid,grt_aid,grt_wid ORDER BY grt_date DESC";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $rows['grt_uptime'] = date('Ymd',$rows['grt_uptime']);
            //php数据处理
            $right_arr[]=array(
                $rows['grt_date'],
                $aarr[$rows['grt_aid']],
                $aarr[$rows['grt_wid']],
                $garr[$rows['grt_gameid']],
                $rows['grt_uptime'],
                $rows['grt_reg_num'],
                $rows['grt_hours_00'],
                $rows['grt_hours_01'],
                $rows['grt_hours_02'],
                $rows['grt_hours_03'],
                $rows['grt_hours_04'],
                $rows['grt_hours_05'],
                $rows['grt_hours_06'],
                $rows['grt_hours_07'],
                $rows['grt_hours_08'],
                $rows['grt_hours_09'],
                $rows['grt_hours_10'],
                $rows['grt_hours_11'],
                $rows['grt_hours_12'],
                $rows['grt_hours_13'],
                $rows['grt_hours_14'],
                $rows['grt_hours_15'],
                $rows['grt_hours_16'],
                $rows['grt_hours_17'],
                $rows['grt_hours_18'],
                $rows['grt_hours_19'],
                $rows['grt_hours_20'],
                $rows['grt_hours_21'],
                $rows['grt_hours_22'],
                $rows['grt_hours_23'],
            );
        }
        if(empty($right_arr)){
            echo json_encode(array('str'=>'1001','meg'=>'没有数据！'));
        }else{
            echo json_encode($right_arr);
        }
        die;
    }
}
?>