<?php

#=============================================================================
#     FileName: re_update.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: jericho
#        Email: jericho
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-08-02
#      History:
#=============================================================================
class Pay_date extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);   
    }
    
    /*
    *  付费数据统计
    */
    public function payinfo(){
        if ($this->isadmin != 1 && !$this->checkright("pay_date")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $updatephp  = "update_newpay";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $starttime2?$where  .= " and cgd_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and cgd_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and cgd_gameid=".get_param("gid"):'';
        $aid?$where         .= " and cgd_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and cgd_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and cgd_aid in (".$hehe["sysid"].")";
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
        $str    =  "";
        $sql_count    = "SELECT count(*) c FROM " .get_table('game_daypay'). " $where ";
        $sql    = "SELECT cgd_date,cgd_gameid,cgd_uaid,cgd_uwid,cgd_money,cgd_pay,cgd_nums,cgd_fmoney,cgd_fpay,cgd_fnums,cgd_dmoney,cgd_dpay,cgd_dnums FROM " .get_table('game_daypay'). " $where order by cgd_date desc,cgd_gameid ";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));
        $totalrecord = $totalrecord["c"];
        $newarr = $sumarr = $pageinfo = $arr2 = array();
        $pgs  =  $ends  = $sum  =  $start  = 0;
        if ($export == 1) {
            $start = 0;
            $ends = $totalrecord;
        }else{
            //分页
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $ends = ($start+$pagesize);
            $url = $_SERVER['PHP_SELF'];
            $sql .= " LIMIT $start, $pagesize";
            $url    .=  "?module=pay_date&method=payinfo&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            //求合计
            $sumarr["sum_cgd_money"]  += $rows["cgd_money"];
            $sumarr["sum_cgd_pay"]    += $rows["cgd_pay"];
            $sumarr["sum_cgd_nums"]   += $rows["cgd_nums"];
            $sumarr["sum_cgd_fmoney"] += $rows["cgd_fmoney"];
            $sumarr["sum_cgd_fpay"]   += $rows["cgd_fpay"];
            $sumarr["sum_cgd_fnums"]  += $rows["cgd_fnums"];
            $sumarr["sum_cgd_dmoney"] += $rows["cgd_dmoney"];
            $sumarr["sum_cgd_dpay"]   += $rows["cgd_dpay"];
            $sumarr["sum_cgd_dnums"]  += $rows["cgd_dnums"];
            if($pgs>=$start && $pgs<$ends){
                $str .= "<tr>";
                $rows["cgd_date"] = date("Y-m-d",strtotime($rows["cgd_date"]));
                $rows["cgd_gameid"] = $garr[$rows["cgd_gameid"]]."[".$rows["cgd_gameid"]."]";
                $rows["cgd_uaid"] = $aarr[$rows["cgd_uaid"]];
                $rows["cgd_uwid"] = $aarr[$rows["cgd_uwid"]];
                $str .= "<td>".$rows["cgd_date"]."</td><td>".$rows["cgd_uaid"]."</td><td>".$rows["cgd_uwid"]."</td><td>".$rows["cgd_gameid"]."</td><td>".$rows["cgd_money"]."</td><td>".$rows["cgd_pay"]."</td><td>".$rows["cgd_nums"]."</td><td>".$rows["cgd_fmoney"]."</td><td>".$rows["cgd_fpay"]."</td><td>".$rows["cgd_fnums"]."</td><td>".$rows["cgd_dmoney"]."</td><td>".$rows["cgd_dpay"]."</td><td>".$rows["cgd_dnums"]."</td>";
                $str .= "</tr>";
            }
            $pgs+=1;
        }
        if ($totalrecord>0) {
            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td><td>".$sumarr["sum_cgd_money"]."</td><td>".$sumarr["sum_cgd_pay"]."</td><td>".$sumarr["sum_cgd_nums"]."</td><td>".$sumarr["sum_cgd_fmoney"]."</td><td>".$sumarr["sum_cgd_fpay"]."</td><td>".$sumarr["sum_cgd_fnums"]."</td><td>".$sumarr["sum_cgd_dmoney"]."</td><td>".$sumarr["sum_cgd_dpay"]."</td><td>".$sumarr["sum_cgd_dnums"]."</td></tr>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."付费数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>付费数据</strong></td></tr><tr><td rowspan="2">日期</td><td rowspan="2">广告渠道</td><td rowspan="2">子渠道</td><td rowspan="2">游戏</td><td colspan="3">活跃玩家</td><td colspan="3">首次付费玩家</td><td colspan="3">首日付费玩家</td></tr><tr><td>当日付费金额</td><td>当日付费人数</td><td>当日付费次数</td><td>首次付费金额</td><td>首次付费人数</td><td>首次付费次数</td><td>首日付费金额</td><td>首日付费人数</td><td>首日付费次数</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view  = $category = array();
        $sql2   =  " SELECT cgd_date, sum(cgd_money) cgd_money, sum(cgd_pay) cgd_pay, sum(cgd_nums) cgd_nums, sum(cgd_fmoney) cgd_fmoney, sum(cgd_fpay) cgd_fpay, sum(cgd_fnums) cgd_fnums,sum(cgd_dmoney) cgd_dmoney,sum(cgd_dpay) cgd_dpay, sum(cgd_dnums) cgd_dnums FROM " .get_table('game_daypay'). " $where group by cgd_date order by cgd_date asc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $category[]      = date("Y-m-d",strtotime($rows["cgd_date"]));
            $view['当日付费金额'][]    = $rows["cgd_money"];
            $view['当日付费人数'][]  = $rows["cgd_fpay"];
            $view["当日付费次数"][]   = $rows["cgd_nums"];
            $view['首次付费金额'][]    = $rows["cgd_fmoney"];
            $view['首次付费人数'][]  = $rows["cgd_fpay"];
            $view["首次付费次数"][]   = $rows["cgd_fnums"];
            $view['首日付费金额'][]    = $rows["cgd_dmoney"];
            $view['首日付费人数'][]  = $rows["cgd_fpay"];
            $view["首日付费次数"][]   = $rows["cgd_dnums"];
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
        $this->assign('active',"payinfo");
        $this->assign('title',"付费数据统计");
        $this->assign('meg','您已进入付费数据统计');
        $this->display("pay_date.html");
    }

    /*
    *   付费排行
    */
    public function top_pay(){
        if ($this->isadmin != 1 && !$this->checkright("top_pay")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }  

         //获取详细信息
        $where  = $where2 = $where3 = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $action     = get_param("action");

        //充值where
        $starttime2?$where  .= " and dp_paydate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and dp_paydate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dp_uwid=".get_param("adsons"):'';

        //登录where
        $starttime2?$where2  .= " and dg_logdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where2    .= " and dg_logdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where2         .= " and dg_gid=".get_param("gid"):'';
        $aid?$where2         .= " and dg_uaid=".get_param("aid"):'';
        $adsons?$where2      .= " and dg_uwid=".get_param("adsons"):'';

        //充值
        $gid?$where3         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where3         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where3      .= " and dp_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe    = get_adinfo($this->db,$tid);
            $where  .= " and dp_uaid in (".$hehe["sysid"].")";
            $where2 .= " and dg_uaid in (".$hehe["sysid"].")";
            $where3 .= " and dp_uaid in (".$hehe["sysid"].")";
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

        if(!empty($action) && $action=='go'){
            get_games_conn();
            $sql   = "select sum(dp_money) money,count(*) nums,datediff('".$endtime2."',max(dp_paydate)) paydiff,max(dp_paytime) lastpay,dp_uid,dp_gid,dp_uaid,dp_uwid from ".get_table("paylog_log")." $where group by dp_uid,dp_gid,dp_uaid,dp_uwid order by money desc limit 20";
            // exit($sql);
            $query = $this->db->query($sql);
            $nums  = 1;
            while($rows = $this->db->FetchArray($query)){
                //查询玩家首次充值时间
                $sql = "select min(dp_paydate) paydate from ".get_table("paylog_log")." $where3 and dp_uid='".$rows["dp_uid"]."'";
                // echo $sql;exit;
                $pts = $this->db->getOne($this->db->query($sql));

                //查询玩家信息
                $sql   = "select dg_regdate,max(dg_logtime) lasttime,datediff('".$endtime2."',max(dg_logdate)) logdiff,group_concat(distinct dg_logdate) logs from ".get_table("gamelogin_log")." $where2 and dg_uid='".$rows["dp_uid"]."'";
                $lts   = $this->db->getOne($this->db->query($sql));

                //活跃天数
                if(!empty($lts["logs"])){
                    $exp  = explode(",",$lts["logs"]);
                    $dus  = count($exp);
                }else{
                    $dus  = 0;
                }

                $lts["dg_regdate"]  = !empty($lts["dg_regdate"])?date("Y-m-d",strtotime($lts["dg_regdate"])):"";
                $pts["paydate"]     = !empty($pts["paydate"])?date("Y-m-d",strtotime($pts["paydate"])):"";
                $lts["lasttime"]    = !empty($lts["lasttime"])?date("Y-m-d",$lts["lasttime"]):"";
                $rows["lastpay"]    = !empty($rows["lastpay"])?date("Y-m-d",$rows["lastpay"]):"";


                $str .= "<tr>";
                $str .= "<td>".$nums."</td><td>".$rows["dp_uid"]."</td><td>".$lts["dg_regdate"]."</td><td>".$pts["paydate"]."</td><td>".$rows["money"]."</td><td>".$rows["nums"]."</td><td>".$dus."</td><td>".$lts["lasttime"]."</td><td>".$rows["lastpay"]."</td><td>".$lts["logdiff"]."</td><td>".$rows["paydiff"]."</td>";
                $str .= "</tr>";

                unset($pts);
                unset($lts);
                $nums +=1;
            }
        } 

        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('active',"top_pay");
        $this->assign('title',"付费排行统计");
        $this->assign('meg','您已进入付费排行统计！');
        $this->display("pay_date.html");
    }
    /*
    *   注册当天付费跟踪(单日时段)
    */
    public function today_payinfo(){
        if ($this->isadmin != 1 && !$this->checkright("today_payinfo")) {
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
        $updatephp  = "update_pay_hours";     //更新文件名称
        $this->assign("updatephp",$updatephp);
        $where              .= " where cph_count_type = 4 ";
        $starttime2?$where  .= " and cph_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and cph_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and cph_gameid=".get_param("gid"):'';
        $aid?$where         .= " and cph_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and cph_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and cph_uaid in (".$hehe["sysid"].")";
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
        $str    =  "";
        $newarr = $sumarr = $pageinfo =  array();
        //付费统计表
        $sql = "SELECT * FROM " .get_table('pay_hours_info'). " $where order by cph_date Desc ";
        $sql_count = "SELECT count(*) c FROM " .get_table('pay_hours_info'). " $where ";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));
        $totalrecord = $totalrecord["c"];
        //点击查询按钮
        if ($export != 1) {
            //分页
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $url = $_SERVER['PHP_SELF'];
            $sql .= " LIMIT $start, $pagesize";
            $url    .=  "?module=pay_date&method=today_payinfo&action=submit&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            unset($rows["sysid"]);
            unset($rows["cph_uptime"]);
            $key      = date("Y-m-d",strtotime($rows["cph_date"]));
            for ($i=0; $i < 24; $i++) {
                if ($i < 10) {
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                $newarr[$key][$k] += $rows["cph_hour".$k];
            }
        }
        foreach ($newarr as $key => $value) {
            $str .= "<tr>";
            $str .= "<td>".$key."</td>";
            for ($i=0; $i < 24; $i++) {
                if ($i < 10) {
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                $str .= "<td>".$value[$k]."</td>";
                $sumarr[$k] += $value[$k];
            }
            $str .= "</tr>";
        }
        if ($totalrecord>0) {
            $str .= "<tr><td style='text-align:center;'>合计</td>";
            for ($i=0; $i < 24; $i++) {
                if ($i < 10) {
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                $str .= "<td>".$sumarr[$k]."</td>";
            }
            $str .= "</tr>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."付费跟踪(单日时段)".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>付费跟踪(单日时段)</strong></td></tr><tr><td>日期</td><td>0时</td><td>1时</td><td>2时</td><td>3时</td><td>4时</td><td>5时</td><td>6时</td><td>7时</td><td>8时</td><td>9时</td><td>10时</td><td>11时</td><td>12时</td><td>13时</td><td>14时</td><td>15时</td><td>16时</td><td>17时</td><td>18时</td><td>19时</td><td>20时</td><td>21时</td><td>22时</td><td>23时</td></tr>'.$str .'</table>');
            exit;
        }

        //处理图表展示
        if ($totalrecord>0) {
            $view  = $category = array();
            $sql2 = "SELECT * FROM " .get_table('pay_hours_info'). " $where order by cph_date asc ";
            $query  =  $this->db->query($sql2);
            for ($i=0; $i < 24; $i++) {
                $category[]      = $i.'时';
            }
            while($rows = $this->db->FetchArray($query)){
                $key      = date("Y-m-d",strtotime($rows["cph_date"]));
                for ($i=0; $i < 24; $i++) {
                    if ($i < 10) {
                        $k = '0'.$i;
                    }else{
                        $k = $i;
                    }
                    $view[$key][$i] += $rows["cph_hour".$k];
                }
            }
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
        $this->assign('active',"today_payinfo");
        $this->assign('title',"注册当天付费跟踪(单日时段)");
        $this->assign('meg','您已进入注册当天付费跟踪(单日时段)统计！');
        $this->display("pay_date.html");
    }

     /*
    *   注册当天付费跟踪(注册后付费)
    */
    public function today_paytime(){
        if ($this->isadmin != 1 && !$this->checkright("today_paytime")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }

         //获取详细信息
        $and = "";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $action     = get_param("action");

        $starttime2?$and  .= " and dp_paydate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$and    .= " and dp_paydate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$and         .= " and dp_gid=".get_param("gid"):'';
        $aid?$and         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$and      .= " and dp_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe    = get_adinfo($this->db,$tid);
            $and  .= " and dp_uaid in (".$hehe["sysid"].")";
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
        $newarr = $sumarr = $pageinfo =  array();
        get_games_conn();
        $sql   = "select dp_paydate,count(*) nums,hour(timediff(from_unixtime(dp_paytime,'%Y-%m-%d %H:%i:%s'),from_unixtime(dp_regtime,'%Y-%m-%d %H:%i:%s'))) hours from ".get_table("paylog_log")." where dp_paydate=dp_regdate $and group by dp_paydate,hours order by dp_paydate desc,hours";
        $query = $this->db->query($sql);
        while($rows = $this->db->FetchArray($query)){
            $key = $rows["dp_paydate"];
            $newarr[$key][$rows["hours"]] = $rows["nums"];
        } 
        //释放mysql
        $this->db->FreeResult($query);

        //循环遍历数据
        $str = "";
        $ends = count($newarr);
        if(!empty($newarr)){
            foreach($newarr as $k=>$v){
                $str .= "<tr><td>".date("Y-m-d",strtotime($k))."</td>";
                for ($i=0; $i < 12; $i++) { 
                    $v[$i] = !empty($v[$i])?$v[$i]:0;   //充值0值
                    $str .= "<td>".$v[$i]."</td>";
                    $sumarr[$i] += $v[$i];
                }
                $str .= "</tr>";
            }
            //处理图表展示
            $view  = $category = array();
            for ($i=1; $i < 13; $i++) {
                $category[]      = '注册'.$i.'小时';
            }
            ksort($newarr);
            foreach($newarr as $k=>$v){
                $key = date("Y-m-d",strtotime($k));
                for ($i=0; $i < 12; $i++) {
                    $v[$i] = !empty($v[$i])?$v[$i]:0;   //充值0值
                    $view[$key][$i] = $v[$i];
                }
            }
        }
        if ($ends>0) {
            $str .= "<tr><td style='text-align:center;'>合计</td>";
            for ($i=0; $i < 12; $i++) {
                    $str .= "<td>".$sumarr[$i]."</td>";
            }
            $str .= "</tr>";
        }
        //点击导出按钮
        if($export == 1){
            if ($ends>0) {
                if ($ends>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."付费跟踪(注册后付费)".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>付费跟踪(注册后付费)</strong></td></tr><tr><td>日期</td><td>注册1小时</td><td>注册2小时</td><td>注册3小时</td><td>注册4小时</td><td>注册5小时</td><td>注册6小时</td><td>注册7小时</td><td>注册8小时</td><td>注册9小时</td><td>注册10小时</td><td>注册11小时</td><td>注册12小时</td></tr>'.$str .'</table>');
            exit;
        }
        $this->assign("category",json_encode($category));
        $this->assign("view",json_encode($view));
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('active',"today_paytime");
        $this->assign('title',"注册当天付费跟踪(注册后付费)");
        $this->assign('meg','您已进入注册当天付费跟踪(注册后付费)统计！');
        $this->display("pay_date.html");
    }

    /*
    *  新增付费分析
    */
    public function pay_conversion(){
        if ($this->isadmin != 1 && !$this->checkright("pay_conversion")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = $nwhere = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");

        $starttime2?$where  .= " and dp_regdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and dp_regdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dp_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and dp_uaid in (".$hehe["sysid"].")";
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
        //新增付费分析
        $str    =  "";
        $newarr = $sumarr = $arr = array();
        $sql = "SELECT count(distinct if(dp_paydate=dp_regdate,dp_uid,null)) info0 ,count(distinct if(datediff(dp_paydate,dp_regdate)>-1 and datediff(dp_paydate,dp_regdate)<7,dp_uid,null)) as info7,count(distinct if(datediff(dp_paydate,dp_regdate)>-1 and datediff(dp_paydate,dp_regdate)<30,dp_uid,null)) info30,dp_regdate FROM ".get_table('paylog_log')." $where group by dp_regdate ";

        $query = $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $key  = $rows["dp_regdate"];

            $newarr[$key]["info0"]  = $rows["info0"];
            $newarr[$key]["info7"]  = $rows["info7"];
            $newarr[$key]["info30"] = $rows["info30"];
        }
        //当日新增玩家
        $starttime2?$nwhere  .= " and dg_logdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$nwhere    .= " and dg_logdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$nwhere         .= " and dg_gid=".get_param("gid"):'';
        $aid?$nwhere         .= " and dg_uaid=".get_param("aid"):'';
        $adsons?$nwhere      .= " and dg_uwid=".get_param("adsons"):'';

        $sql = "select count(distinct if(dg_logdate=dg_regdate,dg_uid,null)) AS news,dg_logdate from ".get_table("gamelogin_log")." $nwhere group by dg_logdate";

        $query = $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $key  = $rows["dg_logdate"];

            $newarr[$key]["news"]  = $rows["news"];
        }
        krsort($newarr);// 键名从高到低
        $view  = $category = array();
        foreach($newarr as $k=>$v){
            //求合计
            $sumarr["sum_news"]   += $v["news"];
            $sumarr["sum_info0"]  += $v["info0"];
            $sumarr["sum_info7"]  += $v["info7"];
            $sumarr["sum_info30"] += $v["info30"];

            $info_0 = $v["news"]?$v["info0"]."[".round($v["info0"]/$v["news"]*100,2)."%]":0;
            $info_7 = $v["news"]?$v["info7"]."[".round($v["info7"]/$v["news"]*100,2)."%]":0;
            $info_30 = $v["news"]?$v["info30"]."[".round($v["info30"]/$v["news"]*100,2)."%]":0;

            $str .= "<tr>";
            $str .= "<td>".date("Y-m-d",strtotime($k))."</td><td>".$info_0."</td><td>".$info_7."</td><td>".$info_30."</td>";
            $str .= "</tr>";
        }
        $ends = count($newarr);
        if ($ends>0) {
            $info_0 = $sumarr["sum_news"]?$sumarr["sum_info0"]."[".round($sumarr["sum_info0"]/$sumarr["sum_news"]*100,2)."%]":0;
            $info_7 = $sumarr["sum_news"]?$sumarr["sum_info7"]."[".round($sumarr["sum_info7"]/$sumarr["sum_news"]*100,2)."%]":0;
            $info_30 = $sumarr["sum_news"]?$sumarr["sum_info30"]."[".round($sumarr["sum_info30"]/$sumarr["sum_news"]*100,2)."%]":0;

            $str .= "<tr><td style='text-align:center;'>合计</td><td>".$info_0."</td><td>".$info_7."</td><td>".$info_30."</td></tr>";
        }
        //点击导出按钮
        if($export == 1){
            if ($ends>0) {
                if ($ends>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."新增付费分析".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>新增付费分析</strong></td></tr><tr><td>日期</td><td>首周付费人数（%）</td><td>首日付费人数（%）</td><td>首月付费人数（%）</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        ksort($newarr);//键名从低到高
        foreach($newarr as $k=>$v){
            $category[]      = date("Y-m-d",strtotime($k));
            $info_0 = $v["news"]?round($v["info0"]/$v["news"]*100,2):0;
            $info_7 = $v["news"]?round($v["info7"]/$v["news"]*100,2):0;
            $info_30 = $v["news"]?round($v["info30"]/$v["news"]*100,2):0;

            $view['首日付费率'][]    = $info_0;
            $view['首周付费率'][]  = $info_7;
            $view["首月付费率"][]   = $info_30;
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
        $this->assign('active',"pay_conversion");
        $this->assign('title',"新增付费分析");
        $this->assign('meg','您已进入新增付费分析');
        $this->display("pay_date.html");
    }


    /*
    *  LTV分析
    */
    public function pay_ltv(){
        if ($this->isadmin != 1 && !$this->checkright("pay_ltv")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = $where2 = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $updatephp  = "update_pay_remain";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $starttime2?$where  .= " and cpr_regdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and cpr_regdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and cpr_gameid=".get_param("gid"):'';
        $aid?$where         .= " and cpr_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and cpr_uwid=".get_param("adsons"):'';


        $starttime2?$where2  .= " and cgr_regdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where2    .= " and cgr_regdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where2         .= " and cgr_gameid=".get_param("gid"):'';
        $aid?$where2         .= " and cgr_uaid=".get_param("aid"):'';
        $adsons?$where2      .= " and cgr_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and cpr_uaid in (".$hehe["sysid"].")";
            $where2.= " and cgr_uaid in (".$hehe["sysid"].")";
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

        //LTV
        $str    =  "";
        $newarr =$arr = array();
        $sql = "select cpr_gameid,cpr_uaid,cpr_uwid,cpr_diff,cpr_regdate,sum(cpr_money) money,sum(cpr_news) remain from ".get_table("pay_remain")." $where and cpr_diff<31 group by cpr_regdate,cpr_diff order by cpr_regdate desc,cpr_diff";
        $query = $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $key  = $rows["cpr_regdate"];
            //当天注册用户数
            if($rows["cpr_diff"]==0){
                $js = !empty($rows["remain"])?$rows["remain"]:0;
                $newarr[$key]["remain"] = $rows["remain"];
            }
            $per = $newarr[$key]["remain"]?round($rows["money"]/$newarr[$key]["remain"],2):0;
            $newarr[$key]["info_".$rows["cpr_diff"]] =  "<span style='color:red'>".$per."</span>[".$rows["money"]."]";
            unset($arr);
        }
        $this->db->FreeResult($query);

        //展示数据:
        $ends = count($newarr);
        if(!empty($newarr)){
            foreach($newarr as $k=>$v){
                $str .= "<tr><td>".date("Y-m-d",strtotime($k))."</td><td><b>".$v["remain"]."</b></td>";
                for ($i=0; $i < 32; $i++) { 
                    $v["info_".$i] = !empty($v["info_".$i])?$v["info_".$i]:0;   //充值0值
                    $str .= "<td>".$v["info_".$i]."</td>";
                }
                $str .= "</tr>";
            }
        }
        //点击导出按钮
        if($export == 1){
            if ($ends>0) {
                if ($ends>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."新增玩家LTV".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>新增玩家LTV</strong></td></tr><tr><td>日期</td><td>新增</td><td>当日</td><td>1日</td><td>2日</td><td>3日</td><td>4日</td><td>5日</td><td>6日</td><td>7日</td><td>8日</td><td>9日</td><td>10日</td><td>11日</td><td>12日</td><td>13日</td><td>14日</td><td>15日</td><td>16日</td><td>17日</td><td>18日</td><td>19日</td><td>20日</td><td>21日</td><td>22日</td><td>23日</td><td>24日</td><td>25日</td><td>26日</td><td>27日</td><td>28日</td><td>29日</td><td>30日</td><td>31日</td></tr>'.$str .'</table>');
            exit;
        }
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('active',"pay_ltv");
        $this->assign('title','新增玩家LTV');
        $this->assign('meg','您已进入新增玩家LTV');
        $this->display("pay_date.html");
    }
/*
    *  付费行为
    */
    public function pay_behavior(){
        if ($this->isadmin != 1 && !$this->checkright("pay_behavior")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");

        $starttime2?$where  .= " and dp_paydate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and dp_paydate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dp_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and dp_uaid in (".$hehe["sysid"].")";
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
        $str    =  "";
        $newarr = $pageinfo =  array();
        $sql_count    = "SELECT count(*) c FROM " .get_table('paylog_log'). " $where ";
        $sql    = "SELECT dp_gid,dp_uaid,dp_uwid,dp_lv,count(*) nums,sum(dp_money) moneys FROM " .get_table('paylog_log'). " $where group by dp_gid,dp_uaid,dp_uwid,dp_lv ";
        $totalrecord = $this->db->NumRows($this->db->Query($sql));

        //点击查询按钮
        if ($export != 1) {
            //分页
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $url = $_SERVER['PHP_SELF'];
            $sql .= " LIMIT $start, $pagesize";
            $url    .=  "?module=pay_date&method=pay_behavior&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["dp_gid"] = $garr[$rows["dp_gid"]]."[".$rows["dp_gid"]."]";
            $rows["dp_uaid"] = $aarr[$rows["dp_uaid"]];
            $rows["dp_uwid"] = $aarr[$rows["dp_uwid"]];
            $str .= "<td>".$rows["dp_uaid"]."</td><td>".$rows["dp_uwid"]."</td><td>".$rows["dp_gid"]."</td><td>".$rows["dp_lv"]."</td><td>".$rows["moneys"]."</td><td>".$rows["nums"]."</td>";
            $str .= "</tr>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."付费行为数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>付费行为数据</strong></td></tr><tr><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>付费等级</td><td>付费金额</td><td>付费次数</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view  = $category = array();
        $sql2   =  " SELECT dp_lv, count(distinct sysid) nums, sum(dp_money) moneys FROM " .get_table('paylog_log'). " $where group by dp_lv order by moneys desc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $category[]      = $rows["dp_lv"];
            $view['付费金额'][]    = $rows["moneys"];
            $view['付费次数'][]  = $rows["nums"];
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
        $this->assign('active',"pay_behavior");
        $this->assign('title',"付费行为统计");
        $this->assign('meg','您已进入付费行为统计');
        $this->display("pay_date.html");
    }

    //付费区间
    public function pay_range(){
        if ($this->isadmin != 1 && !$this->checkright("pay_range")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }

        //获取详细信息
        $where  = " where 1";
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $export     = get_param("export")?get_param("export"):0;
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $updatephp  = "update_pay_range";     //更新文件名称
        $this->assign("updatephp",$updatephp);
        $starttime2?$where  .= " and pr_date=".date('Ymd',strtotime($starttime2)):'';
        $gid?$where         .= " and pr_gameid=".get_param("gid"):'';
        $aid?$where         .= " and pr_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and pr_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and pr_uaid in (".$hehe["sysid"].")";
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
        $str    =  "";
        $newarr = $pageinfo =  array();
        $sql_count    = "SELECT count(*) c FROM " .get_table('pay_range'). " $where ";
        $sql    = "SELECT pr_date,pr_range,sum(pr_nums) pr_nums,sum(pr_pays) pr_pays,sum(pr_wpays) pr_wpays,sum(pr_mpays) pr_mpays FROM " .get_table('pay_range'). " $where group by pr_date,pr_range order by pr_range asc ";
        $totalrecord = $this->db->NumRows($this->db->Query($sql));

        $query  = $this->db->Query($sql);
        $arr4 = $this->payrange;
        $view  = $category = array();
        while ($rows = $this->db->FetchArray($query)) {
            if(!empty($arr4)){
                foreach($arr4 as $k=>$v){
                    if ($k == $rows["pr_range"]) {
                        $rows["pr_range"] = '(￥)'.($v["s"]+1).'-'.($v["e"]-1);
                    }
                }
            }
            $category[]      = $rows["pr_range"];
            $view['每日付费玩家'][]    = $rows["pr_pays"];
            $view['每周付费玩家'][]    = $rows["pr_wpays"];
            $view['每月付费玩家'][]    = $rows["pr_mpays"];
            $str .= "<tr>";
            $str .= "<td>".$rows["pr_range"]."</td><td>".$rows["pr_pays"]."</td><td>".$rows["pr_wpays"]."</td><td>".$rows["pr_mpays"]."</td>";
            $str .= "</tr>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."付费区间数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>付费区间数据</strong></td></tr><tr><td>付费金额区间</td><td>付费次数区间</td><td>每日付费玩家</td><td>每周付费玩家</td><td>每月付费玩家</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $this->assign("category",json_encode($category));
        $this->assign("view",json_encode($view));
        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('active',"pay_range");
        $this->assign('title',"付费区间分析");
        $this->assign('meg','您已进入付费区间统计');
        $this->display("pay_date.html");
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