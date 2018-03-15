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

class Pay_rate extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    /*付费率*/
    public function payrate(){
        if ($this->isadmin != 1 && !$this->checkright("pay_rate")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $action     = get_param("action");
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $type       = get_param("type");

        $where              .= " where 1 ";

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $gaid = 'ga_aid';
            if (!empty($type)) {
                if ($type == 2) {
                    $gaid = 'gw_aid';
                }elseif ($type == 3) {
                    $gaid = 'gm_aid';
                }else{
                    $gaid = 'ga_aid';
                }
            }
            $where .= " and ".$gaid." in (".$hehe["sysid"].")";
        }
        $this->assign("gamestr",$this->get_select($gid));

        if(!empty($tid)){
            $adstr = $wdstr = "";
            $tt    = ad_get($this->db,'a',"",$tid);
            if(!empty($tt)){
                foreach($tt as $k=>$v){
                    $check  = ($k==$aid)?"selected=selected":"";
                    $adstr .= "<option value='$k' ".$check.">$v</option>";
                }
            }
            unset($tt);

            $tt    = ad_get($this->db,"","",$tid,$aid);
            if(!empty($tt)){
                foreach($tt as $k=>$v){
                    $check  = ($k==$adsons)?"selected=selected":"";
                    $wdstr .= "<option value='$k' ".$check.">$v</option>";
                }
            }
            $this->assign('tps',1);
            $this->assign("adstr",$adstr);
            $this->assign("wdstr",$wdstr);
        }
        get_games_conn();
        $str    = $sql  =  $sql2  =$sql3 =  $totalrecord  =  "";
        $newarr = $pageinfo =  array();
        $arrt   = array('1' => '日付费率','2' => '周付费率','3' => '月付费率');
        //点击查询按钮  /分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;
        $url = $_SERVER['PHP_SELF'];
        $url    .=  "?module=pay_rate&method=payrate&action=submit&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons&type=$type";
        //日付费率
        if ($type == 1) {
            //日活跃用户统计
            $starttime2?$where  .= " and ga_date>".date('Ymd',strtotime($starttime2)-1):'';
            $endtime2?$where    .= " and ga_date<".date('Ymd',strtotime($endtime2)+86400):'';
            $gid?$where         .= " and ga_gameid=".get_param("gid"):'';
            $aid?$where         .= " and ga_aid=".get_param("aid"):'';
            $adsons?$where      .= " and ga_wid=".get_param("adsons"):'';
            $sql    = "SELECT ga_date, ga_gameid, ga_aid, ga_wid, ga_dau,ga_pay FROM " .get_table('game_active'). " $where order by ga_date desc ";
            $sql2    = "SELECT ga_date, sum(ga_dau) ga_dau,sum(ga_pay) ga_pay FROM " .get_table('game_active'). " $where group by ga_date order by ga_date asc ";
            $sql3    = "SELECT ga_date, sum(ga_dau) ga_dau,sum(ga_pay) ga_pay FROM " .get_table('game_active'). " $where ";
            if ($action == 'submit') {
                $sql .= " LIMIT $start, $pagesize";
            }
            $query  = $this->db->Query($sql);
            $sql_count = "SELECT count(*) c FROM " .get_table('game_active'). " $where ";
            $totalrecord = $this->db->getOne($this->db->Query($sql_count));
            $totalrecord = $totalrecord["c"];
            while ($rows = $this->db->FetchArray($query)) {
                $rows["ga_date"] = date('Y-m-d',strtotime($rows["ga_date"]));
                $rows["ga_aid"] = $aarr[$rows["ga_aid"]];
                $rows["ga_wid"] = $aarr[$rows["ga_wid"]];
                $rows["ga_gameid"] = $garr[$rows["ga_gameid"]]."[".$rows["ga_gameid"]."]";
                $rate = $rows["ga_dau"]?round($rows["ga_pay"]/$rows["ga_dau"]*100,2).'%':'';//日付费率
                $str .= "<tr>";
                $str .= "<td>".$rows["ga_date"]."</td><td>".$rows["ga_aid"]."</td><td>".$rows["ga_wid"]."</td><td>".$rows["ga_gameid"]."</td><td>".$rate."</td>";
                $str .= "</tr>";
            }
        }
        //周付费率
        if ($type == 2) {
            //周活跃用户统计
            //计算对应周期
            $d_s      = date("d",strtotime($starttime2));
            $m_s      = date("m",strtotime($starttime2));
            $w_s      = date("w",strtotime($starttime2));
            $y_s      = date("y",strtotime($starttime2));
            $d_e      = date("d",strtotime($endtime2));
            $m_e      = date("m",strtotime($endtime2));
            $w_e      = date("w",strtotime($endtime2));
            $y_e      = date("y",strtotime($endtime2));
            $sdate  = date("Ymd",mktime(0, 0 , 0,$m_s,$d_s-$w_s-1,$y_s));    //开始时间，前端推周
            $edate  = date("Ymd",mktime(0, 0 , 0,$m_e,$d_e-$w_e+8,$y_e));     //结束时间
            $where              .= " and gw_sdate>".$sdate." and gw_edate<".$edate;
            $gid?$where         .= " and gw_gameid=".get_param("gid"):'';
            $aid?$where         .= " and gw_aid=".get_param("aid"):'';
            $adsons?$where      .= " and gw_wid=".get_param("adsons"):'';
            $sql    = "SELECT gw_sdate,gw_edate, gw_gameid, gw_aid, gw_wid, gw_wau,gw_wpay FROM " .get_table('game_wactive'). " $where order by gw_sdate desc ";
            $sql2    = "SELECT concat(DATE_FORMAT(gw_sdate,'%Y-%m-%d'),'~',DATE_FORMAT(gw_edate,'%Y-%m-%d')) ga_date, sum(gw_wau) ga_dau,sum(gw_wpay) ga_pay FROM " .get_table('game_wactive'). " $where group by ga_date order by ga_date asc ";
            $sql3    = "SELECT concat(DATE_FORMAT(gw_sdate,'%Y-%m-%d'),'~',DATE_FORMAT(gw_edate,'%Y-%m-%d')) ga_date, sum(gw_wau) ga_dau,sum(gw_wpay) ga_pay FROM " .get_table('game_wactive'). " $where ";
            if ($action == 'submit') {
                $sql .= " LIMIT $start, $pagesize";
            }
            $query  = $this->db->Query($sql);
            $sql_count = "SELECT count(*) c FROM " .get_table('game_wactive'). " $where ";
            $totalrecord = $this->db->getOne($this->db->Query($sql_count));
            $totalrecord = $totalrecord["c"];
            while ($rows = $this->db->FetchArray($query)) {
                $rows["gw_sdate"] = date('Y-m-d',strtotime($rows["gw_sdate"]));
                $rows["gw_edate"] = date('Y-m-d',strtotime($rows["gw_edate"]));
                $rows["gw_aid"] = $aarr[$rows["gw_aid"]];
                $rows["gw_wid"] = $aarr[$rows["gw_wid"]];
                $rows["gw_gameid"] = $garr[$rows["gw_gameid"]]."[".$rows["gw_gameid"]."]";
                $rate = $rows["gw_wau"]?round($rows["gw_wpay"]/$rows["gw_wau"]*100,2).'%':'';//周付费率
                $str .= "<tr>";
                $str .= "<td>".$rows["gw_sdate"]."~".$rows["gw_edate"]."</td><td>".$rows["gw_aid"]."</td><td>".$rows["gw_wid"]."</td><td>".$rows["gw_gameid"]."</td><td>".$rate."</td>";
                $str .= "</tr>";
            }
        }
        //月付费率
        if ($type == 3) {
            //月活跃用户统计
            $date1  =  $starttime2?str_replace("-","",$starttime2):date("Ym");
            $date2  =  $endtime2?str_replace("-","",$endtime2):date("Ym");
            $year_s   =  substr($date1,0,4);
            $month_s  =  substr($date1,4,2);
            $year_e   =  substr($date2,0,4);
            $month_e  =  substr($date2,4,2);
            $sdate  =  date("Ym",mktime(0,0,0,$month_s-1,1,$year_s));
            $edate  =  date("Ym",mktime(0,0,0,$month_e+1,1,$year_e));

            $where              .= " and gm_month>".$sdate." and gm_month<".$edate;
            $gid?$where         .= " and gm_gameid=".get_param("gid"):'';
            $aid?$where         .= " and gm_aid=".get_param("aid"):'';
            $adsons?$where      .= " and gm_wid=".get_param("adsons"):'';
            $sql    = "SELECT gm_month, gm_gameid, gm_aid, gm_wid, gm_mau,gm_mpay FROM " .get_table('game_mactive'). " $where order by gm_month desc ";
            $sql2    = "SELECT gm_month as ga_date, sum(gm_mau) ga_dau, sum(gm_mpay) ga_pay FROM " .get_table('game_mactive'). " $where group by ga_date order by ga_date asc ";
            $sql3    = "SELECT gm_month as ga_date, sum(gm_mau) ga_dau, sum(gm_mpay) ga_pay FROM " .get_table('game_mactive'). " $where ";
            if ($action == 'submit') {
                $sql .= " LIMIT $start, $pagesize";
            }
            $query  = $this->db->Query($sql);
            $sql_count = "SELECT count(*) c FROM " .get_table('game_mactive'). " $where ";
            $totalrecord = $this->db->getOne($this->db->Query($sql_count));
            $totalrecord = $totalrecord["c"];
            while ($rows = $this->db->FetchArray($query)) {
                $rows["gm_month"] = date('Y-m',strtotime($rows["gm_month"]));
                $rows["gm_aid"] = $aarr[$rows["gm_aid"]];
                $rows["gm_wid"] = $aarr[$rows["gm_wid"]];
                $rows["gm_gameid"] = $garr[$rows["gm_gameid"]]."[".$rows["gm_gameid"]."]";
                $rate = $rows["gm_mau"]?round($rows["gm_mpay"]/$rows["gm_mdau"]*100,2).'%':'';//月付费率
                $str .= "<tr>";
                $str .= "<td>".$rows["gm_month"]."</td><td>".$rows["gm_aid"]."</td><td>".$rows["gm_wid"]."</td><td>".$rows["gm_gameid"]."</td><td>".$rate."</td>";
                $str .= "</tr>";
            }
        }
        $multi = multi($totalrecord, $pagesize, $page, $url,2);
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord,
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord/$pagesize),
            'multi' => $multi
        );
        if ($totalrecord>0) {
            $query  =  $this->db->query($sql3);
            while($rows = $this->db->FetchArray($query)){
                $rate = $rows["ga_dau"]?round($rows["ga_pay"]/$rows["ga_dau"]*100,2):0;//付费率
                $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td>";
                $str .= "<td>".$rate."%</td>";
                $str .= "</tr>";
            }
        }
        //点击导出按钮
        if($export == 1){
            if ($totalrecord>0) {
                if ($totalrecord>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2.$arrt[$type]."数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="28"><strong>'.$arrt[$type].'数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>'.$arrt[$type].'</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view  = $category = array();
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            if ($type == 2) {
                $category[]      = $rows["ga_date"];
            }elseif ($type == 3) {
                $category[]      = date("Y-m",strtotime($rows["ga_date"]));
            }else{
                $category[]      = date("Y-m-d",strtotime($rows["ga_date"]));
            }
            $rate = $rows["ga_dau"]?round($rows["ga_pay"]/$rows["ga_dau"]*100,2):0;//付费率
            $view[$arrt[$type]][]    = $rate;
        }
        $this->assign("category",json_encode($category));
        $this->assign("view",json_encode($view));
        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("type",$type);
        $this->assign("arrt",$arrt);
        $this->assign("active", "active");
        $this->assign('meg','您已进入付费率列表中心！<br>--在对应的列输入搜索信息');
        $this->display("pay_rate.html");
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