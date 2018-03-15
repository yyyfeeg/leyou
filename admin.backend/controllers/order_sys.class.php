<?php

#=============================================================================
#     FileName: real_time.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: liuf
#        Email: liuf
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-07-14
#      History:
#=============================================================================

class Order_sys extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
    }
    
    
    public function orderlist(){
        if ($this->isadmin != 1 && !$this->checkright("orderlist")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息

        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2");
        $endtime2   = get_param("endtime2");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        
        $this->assign("gamestr",$this->get_select($gid));

        get_games_conn();
        
        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;
        $url = $_SERVER['PHP_SELF'];

        $str    =  "";
        $newarr =  array();
        //付费表
        $where              .= " where 1 ";
        $gid?$where         .= " and ol_gid=".get_param("gid"):'';
        $aid?$where         .= " and ol_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and ol_uwid=".get_param("adsons"):'';
        $starttime2?$where  .= " and ol_paytime>".date('Ymd',strtotime(get_param("starttime2"))-86400):'';
        $endtime2?$where    .= " and ol_paytime<".date('Ymd',strtotime(get_param("endtime2"))+86400):'';
        $sql    = "SELECT ol_rid,ol_uid,ol_orderid,ol_uaid,ol_gid,ol_sid,ol_money,ol_paytime FROM " .get_table('orderform_log'). " $where ";

        $totalrecord = $this->db->NumRows($this->db->Query($sql));
        $sql    .= " order by ol_paytime Desc LIMIT $start, $pagesize ";

        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $rows["ol_paytime"] = date("Y-m-d",strtotime($rows["ol_paytime"]));
            $rows["ol_gid"] = $garr[$rows["ol_gid"]]."[".$rows["ol_gid"]."]";
            $str .= "<tr>";
            $str .= "<td>".$rows["ol_rid"]."</td><td>".$rows["ol_uid"]."</td><td>".$rows["ol_orderid"]."</td><td>".$rows["ol_uaid"]."</td><td>".$rows["ol_gid"]."</td><td>".$rows["ol_sid"]."</td><td>".$rows["ol_money"]."</td><td>".$rows["ol_paytime"]."</td>";
            $str .= "</tr>";
        }  

        $url    .=  "?module=Order_sys&method=orderlist&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
        $multi = multi($totalrecord, $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord,
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord/$pagesize),
            'multi' => $multi
        );

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入平台汇总中心！');
        $this->display("Order_sys.html");
    }

    /*获取子渠道分类
    /*$t 渠道
    /*$t 主站
    */
    public function getadson(){
        $aid        =  get_param("aid","int");
        $tid        =  get_param("tid","int");
        if($aid){
            $res = ad_get($this->db,'','','',$aid);
            echo json_encode($res);
        }else{
            $res = ad_get($this->db,'a','',$tid);
            echo json_encode($res);
        }
    }
}
?>