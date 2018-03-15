<?php

#=============================================================================
#     FileName: count_newpay.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 每日新增用户数据类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-09-28
#      History:
#=============================================================================

class Count_newpay extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /*每日注册列表*/
    public function newpay(){
        if ($this->isadmin != 1 && !$this->checkright("count_newpay")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $this->assign("gamestr",$this->get_select());
        $this->assign("newpay", "newpay");
        $this->assign('meg','您已进入每日新增数据列表中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("count_newpay.html");
    }
    
    /*注册留存列表*/
    public function newpayinfo(){
        if ($this->isadmin != 1 && !$this->checkright("count_newpay")) {
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
        get_param("starttime2")?$where  .= " and gn_date>".date('Ymd',strtotime(get_param("starttime2"))-1):'';
        get_param("endtime2")?$where    .= " and gn_date<".date('Ymd',strtotime(get_param("endtime2"))+86400):'';
        get_param("gid")?$where         .= " and gn_gameid=".get_param("gid"):'';
        get_param("aid")?$where         .= " and gn_aid=".get_param("aid"):'';
        get_param("adsons")?$where      .= " and gn_wid=".get_param("adsons"):'';
        get_games_conn();
        $sql    = "SELECT * FROM " .get_table('game_newpay'). " $where group by gn_gameid,gn_aid,gn_wid,gn_date";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $rows['gn_uptime'] = date('Ymd',$rows['gn_uptime']);
            //php数据处理
            $right_arr[]=array(
                $aarr[$rows['gn_aid']]?($aarr[$rows['gn_aid']]):$rows['gn_aid'],
                $aarr[$rows['gn_wid']]?($aarr[$rows['gn_wid']]):$rows['gn_wid'],
                $garr[$rows['gn_gameid']]?($garr[$rows['gn_gameid']]):$rows['gn_gameid'],
                $rows['gn_uptime'],
                $rows['gn_date'],
                $rows['gn_fpaynum'],
                $rows['gn_fpaymoney'],
                $rows['gn_forders'],
                $rows['gn_paynum'],
                $rows['gn_orders'],
                $rows['gn_paymoney'],
                $rows['gn_logins'],
                $rows['gn_flogins']
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