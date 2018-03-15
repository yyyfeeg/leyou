<?php

#=============================================================================
#     FileName: count_active.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 活跃类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-09-28
#      History:
#=============================================================================

class Count_active extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /*每日注册列表*/
    public function active(){
        if ($this->isadmin != 1 && !$this->checkright("count_active")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $this->assign("gamestr",$this->get_select());
        $this->assign("active", "active");
        $this->assign('meg','您已进入活跃列表中心！');
        $this->display("count_active.html");
    }
    
    /*活跃列表*/
    public function activeinfo(){
        if ($this->isadmin != 1 && !$this->checkright("count_active")) {
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
        get_param("starttime2")?$where  .= " and ga_date>".date('Ymd',strtotime(get_param("starttime2"))-1):'';
        get_param("endtime2")?$where    .= " and ga_date<".date('Ymd',strtotime(get_param("endtime2"))+86400):'';
        get_param("gid")?$where         .= " and ga_gameid=".get_param("gid"):'';
        get_param("aid")?$where         .= " and ga_aid=".get_param("aid"):'';
        get_param("adsons")?$where      .= " and ga_wid=".get_param("adsons"):'';
        get_games_conn();
        $sql    = "SELECT * FROM " .get_table('game_active'). " $where group by ga_gameid,ga_aid,ga_wid,ga_date";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $rows['ga_uptime'] = date('Ymd',$rows['ga_uptime']);
            //php数据处理
            $right_arr[]=array(
                $aarr[$rows['ga_aid']]?($aarr[$rows['ga_aid']]):$rows['ga_aid'],
                $aarr[$rows['ga_wid']]?($aarr[$rows['ga_wid']]):$rows['ga_wid'],
                $garr[$rows['ga_gameid']]?($garr[$rows['ga_gameid']]):$rows['ga_gameid'],
                $rows['ga_date'],
                $rows['ga_uptime'],
                $rows['ga_opens'],
                $rows['ga_dau'],
                $rows['ga_wau'],
                $rows['ga_wof'],
                $rows['ga_wrf'],
                $rows['ga_mau']
            );
        }
        if(empty($right_arr)){
            echo json_encode(array('str'=>'1001','meg'=>'没有数据！'));
        }else{
            echo json_encode($right_arr);
        }
        die;
    }
    /*获取子渠道分类
    /*$t 渠道
    /*$t 主站
    */
    public function getadson(){
        $aid        =  get_param("aid","int");
        $tid        =  get_param("tid","int");
        if($aid){
            $res = ad_get($this->db,2,''," and gp_aid=$aid");
            echo json_encode($res);
        }else{
            $res = ad_get($this->db,'',''," and ga_transport=$tid");
            echo json_encode($res);
        }
        
    }
}
?>