<?php

#=============================================================================
#     FileName: re_update.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: jericho
#        Email: jericho
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-07-13
#      History:
#=============================================================================

class Re_updata extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
    }
    
    
    public function updata(){
        if ($this->isadmin != 1 && !$this->checkright("updata")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2");
        $endtime2   = get_param("endtime2");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");

        $starttime2?$where  .= " and dp_paydate>".date('Ymd',strtotime(get_param("starttime2"))-1):'';
        $endtime2?$where    .= " and dp_paydate<".date('Ymd',strtotime(get_param("endtime2"))+86400):'';
        $gid?$where         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dp_uwid=".get_param("adsons"):'';
        
        $this->assign("gamestr",$this->get_select($gid));

        get_games_conn();
        //分页
        $pagesize = 2;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT dp_paydate,dp_gid,dp_uaid,dp_uwid,count(distinct dp_uid) nums,sum(dp_money) money FROM " .get_table('paylog_log'). " $where group by dp_paydate,dp_gid,dp_uaid,dp_uwid ";
        $totalrecord = $this->db->NumRows($this->db->Query($sql));
        $sql .=" order by dp_paydate desc,dp_gid,dp_uaid LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);

        $str    =  "";
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["dp_paydate"] = date("Y-m-d",strtotime($rows["dp_paydate"]));
            $rows["dp_gid"] = $garr[$rows["dp_gid"]]."[".$rows["dp_gid"]."]";
            $str .= "<td>".$rows["dp_paydate"]."</td><td>".$rows["dp_gid"]."</td><td>".$rows["dp_uaid"]."</td><td>".$rows["dp_uwid"]."</td><td>".$rows["nums"]."</td><td>".$rows["money"]."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=re_updata&method=updata&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
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
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入活跃列表中心！');
        $this->display("re_updata.html");
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