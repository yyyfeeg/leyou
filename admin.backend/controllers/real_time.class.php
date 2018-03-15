<?php

#=============================================================================
#     FileName: real_time.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: liuf
#        Email: liuf
#         Edit: Jericho
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-07-26
#      History: 暂时实时统计当月累计数据，后续就看是否修改
#=============================================================================

class Real_time extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
    }
    
    
    public function real(){
        if ($this->isadmin != 1 && !$this->checkright("real_time")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        get_games_conn();
        
        $str    =  "";
        $newarr =  array();
        $date   =  date("Ymd");
        $sdate  =  date('Ymd',time() - 86400*2);
        $edate  =  date('Ymd',time() + 86400);
        $date3  =  date("Ym",mktime(0,0,0,date("m")-1,1,date("Y")))."31";  //上月开始时间-1
        $date4  =  date("Ym",mktime(0,0,0,date("m")+1,1,date("Y")))."01";    //上月结束时间+1

        //测试数据
        // $sdate  = 20160921;
        // $edate  = 20160924;
        // $date3  = 20160831;
        // $date4  = 20161001;

        //付费数据：
        $where  = " where dp_paydate > ".$sdate." and  dp_paydate < ".$edate;
        $sql    = "SELECT dp_paydate,sum(dp_money) total,count(distinct dp_uid) paysum FROM " .get_table('paylog_log'). " $where group by dp_paydate";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $keys = $rows["dp_paydate"];
            $newarr[$keys]["total"]  = $rows["total"];
            $newarr[$keys]["paysum"] = $rows["paysum"];
        }  

        //新增设备数:
        $where  = " where dg_regdate > ".$sdate." and  dg_regdate < ".$edate;
        $sql    = "SELECT dg_regdate,count(distinct dg_dnumber) devices FROM " .get_table('gamelogin_devices'). " $where group by dg_regdate";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $keys = $rows["dg_regdate"];
            $newarr[$keys]["devices"]  = $rows["devices"]?$rows["devices"]:0;
        }

        //登陆数据：
        $dwhere  = " where dg_logdate > ".$sdate." and  dg_logdate < ".$edate;
        $sql_new = "SELECT dg_logdate,count(distinct dg_uid) loginplay,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) as newplay FROM " .get_table('gamelogin_log'). " $dwhere group by dg_logdate";
        $query  = $this->db->Query($sql_new);
        while ($rows = $this->db->FetchArray($query)) {
            $keys = $rows["dg_logdate"];
            $newarr[$keys]["newplay"]   = $rows["newplay"];
            $newarr[$keys]["loginplay"] = $rows["loginplay"];
            $newarr[$keys]["oldplay"]   = (empty($rows["newplay"]))?0:($rows["loginplay"]-$rows["newplay"]);
        }
        //日期倒序排序
        asort($newarr);


        //展示数据
        foreach($newarr as $key=>$val)
        {
            if($key==$date){
                $k  = "today";
            }else{
                $k  = "yestoday";
            }
            $val["devices"]     = $val["devices"]?$val["devices"]:0;
            $val["paysum"]      = $val["paysum"]?$val["paysum"]:0;
            $val["total"]       = $val["total"]?$val["total"]:0;
            $val["loginplay"]   = $val["loginplay"]?$val["loginplay"]:0;
            $val["newplay"]     = $val["newplay"]?$val["newplay"]:0;
            $val["oldplay"]     = $val["oldplay"]?$val["oldplay"]:0;
            $arrs[$k]           = $val;
        }  

        //获取当月累计数据

        //付费
        $where  = " where dp_paydate > ".$date3." and  dp_paydate < ".$date4;
        $sql    = "SELECT dp_paydate,sum(dp_money) total,count(distinct dp_uid) paysum FROM " .get_table('paylog_log'). " $where";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $arrs["totals"]       = $rows["total"];
            $arrs["total_paysum"] = $rows["paysum"];
        }  

        //新增设备
        $where  = " where dg_regdate > ".$date3." and  dg_regdate < ".$date4;
        $sql    = "SELECT count(distinct dg_dnumber) devices FROM " .get_table('gamelogin_devices'). " $where";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $arrs["total_devices"]  = $rows["devices"]?$rows["devices"]:0;
        }


        //登陆
        $dwhere  = " where dg_logdate > ".$date3." and  dg_logdate < ".$date4;
        $sql_new = "SELECT dg_logdate,count(distinct dg_uid) loginplay,count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) as newplay FROM " .get_table('gamelogin_log'). " $dwhere";
        $query  = $this->db->Query($sql_new);
        while ($rows = $this->db->FetchArray($query)) {
            $arrs["total_newplay"]   = $rows["newplay"];
            $arrs["total_loginplay"] = $rows["loginplay"];
            $arrs["total_oldplay"]   = (empty($rows["newplay"]))?0:($rows["loginplay"]-$rows["newplay"]);
        }

        $this->assign("arrs",$arrs);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入实时统计中心！');
        $this->display("real_time.html");
    }
}
?>