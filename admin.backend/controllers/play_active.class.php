<?php

#=============================================================================
#     FileName: play_active.class.php
#         Desc: 活跃类
#       Author: Liuf
#        Email: Liuf
#      Version: 0.0.1
#   LastChange: 2017-08-01
#      History:
#=============================================================================

class Play_active extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    /*活跃玩家*/
    public function active(){
        if ($this->isadmin != 1 && !$this->checkright("play_active")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
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
        $updatephp  = "update_game_active";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $where              .= " where 1 ";
        $starttime2?$where  .= " and ga_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and ga_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and ga_gameid=".get_param("gid"):'';
        $aid?$where         .= " and ga_aid=".get_param("aid"):'';
        $adsons?$where      .= " and ga_wid=".get_param("adsons"):'';
        //周、月活跃SQL
        $wwhere              .= " where 1 ";
        $starttime2?$wwhere  .= " and gd_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$wwhere    .= " and gd_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$wwhere         .= " and gd_gameid=".get_param("gid"):'';
        $aid?$wwhere         .= " and gd_aid=".get_param("aid"):'';
        $adsons?$wwhere      .= " and gd_wid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and ga_aid in (".$hehe["sysid"].")";
            $wwhere .= " and gd_aid in (".$hehe["sysid"].")";
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
        //活跃统计表
        $sql = "SELECT ga_date,ga_aid,ga_wid,ga_gameid,ga_dau,ga_news,ga_pay,ga_rel_dau FROM " .get_table('game_active'). " $where order by ga_date desc,ga_gameid ";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $keys = $rows["ga_date"].'_'.$rows["ga_gameid"].'_'.$rows["ga_aid"].'_'.$rows["ga_wid"];

            $rows["ga_date"] = date("Y-m-d",strtotime($rows["ga_date"]));
            $rows["ga_gameid"] = $garr[$rows["ga_gameid"]]."[".$rows["ga_gameid"]."]";
            $rows["ga_aid"] = $aarr[$rows["ga_aid"]];
            $rows["ga_wid"] = $aarr[$rows["ga_wid"]];
            $unpayplay = $rows["ga_dau"] - $rows["ga_pay"];  //非付费玩家
            $oldplay = $rows["ga_dau"] - $rows["ga_news"];  //老玩家玩家

            $newarr[$keys]["ga_news"] = $rows["ga_news"];
            $newarr[$keys]["ga_pay"] = $rows["ga_pay"];
            $newarr[$keys]["ga_dau"] = $rows["ga_dau"];
            $newarr[$keys]["ga_rel_dau"] = $rows["ga_rel_dau"];
            $newarr[$keys]["unpayplay"] = $unpayplay;
            $newarr[$keys]["oldplay"] = $oldplay;
        }
        //周、月活跃玩家
        $sql = "SELECT gd_date,gd_aid,gd_wid,gd_gameid,gd_wau,gd_mau FROM " .get_table('game_dayrisk'). " $wwhere order by gd_date desc, gd_gameid ";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $keys = $rows["gd_date"].'_'.$rows["gd_gameid"].'_'.$rows["gd_aid"].'_'.$rows["gd_wid"];
            $newarr[$keys]["wau"] = $rows["gd_wau"];
            $newarr[$keys]["mau"] = $rows["gd_mau"];
        }
        krsort($newarr);
        $pgs = 0;
        $ends = 0;
        $totalrecord = count($newarr);
        //导出
        if ($export == 1) {
            $start = 0;
            $ends = $totalrecord;
        }
        //查询分页
        else{
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $url = $_SERVER['PHP_SELF'];
            $ends = ($start+$pagesize);
            $url    .=  "?module=play_active&method=active&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }
        foreach ($newarr as $key => $val) {
            //求合计
            $sumarr["sum_ga_news"]    += $val["ga_news"];
            $sumarr["sum_ga_pay"]     += $val["ga_pay"];
            $sumarr["sum_unpayplay"]  += $val["unpayplay"];
            $sumarr["sum_ga_dau"]     += $val["ga_dau"];
            $sumarr["sum_wau"]        += $val["wau"];
            $sumarr["sum_mau"]        += $val["mau"];
            $sumarr["sum_ga_rel_dau"] += $val["ga_rel_dau"];
            $sumarr["sum_oldplay"]    += $val["oldplay"];
            if($pgs>=$start && $pgs<$ends){
                $x_key = explode('_', $key);
                $x_key[0] = date("Y-m-d",strtotime($x_key[0]));
                $x_key[1] = $garr[$x_key[1]]."[".$x_key[1]."]";//ga_gameid
                $x_key[2] = $aarr[$x_key[2]];//ga_aid;
                $x_key[3] = $aarr[$x_key[3]];//ga_wid

                $D_MAU = $val["mau"]?round($val["ga_dau"]/$val["mau"]*100,2).'%':'0%';//
                $str .= "<tr>";
                $str .= "<td>".$x_key[0]."</td><td>".$x_key[2]."</td><td>".$x_key[3]."</td><td>".$x_key[1]."</td><td>".$val["ga_news"]."</td><td>".$val["ga_pay"]."</td><td>".$val["unpayplay"]."</td><td>".$val["ga_dau"]."</td><td>".$val["wau"]."</td><td>".$val["mau"]."</td><td>".$val["ga_rel_dau"]."</td><td>".$val["oldplay"]."</td><td>".$D_MAU."</td>";
                $str .= "</tr>";
            }
            $pgs+=1;
        }
        if ($totalrecord>0) {
            $sumarr["sum_d_mau"]=$sumarr["sum_mau"]?round($sumarr["sum_ga_dau"]/$sumarr["sum_mau"]*100,2):0;

            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td><td>".$sumarr["sum_ga_news"]."</td><td>".$sumarr["sum_ga_pay"]."</td><td>".$sumarr["sum_unpayplay"]."</td><td>".$sumarr["sum_ga_dau"]."</td><td>".$sumarr["sum_wau"]."</td><td>".$sumarr["sum_mau"]."</td><td>".$sumarr["sum_ga_rel_dau"]."</td><td>".$sumarr["sum_oldplay"]."</td><td>".$sumarr["sum_d_mau"]."%</td></tr>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."活跃玩家".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="13"><strong>活跃玩家</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>新增玩家</td><td>付费玩家</td><td>非付费玩家</td><td>DAU</td><td>WAU</td><td>MAU</td><td>有效DAU</td><td>老玩家</td><td>DAU/MAU</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view  = $category = array();
        $sql2   =  " SELECT ga_date, sum(ga_dau) ga_dau, sum(ga_news) ga_news, sum(ga_pay) ga_pay, sum(ga_rel_dau) ga_rel_dau FROM " .get_table('game_active'). " $where group by ga_date order by ga_date asc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $category[]      = date("Y-m-d",strtotime($rows["ga_date"]));
            $unpayplay = $rows["ga_dau"] - $rows["ga_pay"];  //非付费玩家
            $oldplay = $rows["ga_dau"] - $rows["ga_news"];  //老玩家玩家
            $view['新增玩家'][]    = $rows["ga_news"];
            $view['付费玩家'][]  = $rows["ga_pay"];
            $view["非付费玩家"][]   = $unpayplay;
            $view['DAU'][]     = $rows["ga_dau"];
            $view["有效DAU"][]   = $rows["ga_rel_dau"];
            $view['老玩家'][]     = $oldplay;
        }
        // $sql3   =  " SELECT gd_date, sum(gd_wau) gd_wau, sum(gd_mau) gd_mau FROM " .get_table('game_dayrisk'). " $wwhere group by gd_date order by gd_date asc";
        // $query  =  $this->db->query($sql3);
        // while($rows = $this->db->FetchArray($query)){
        //     $category[]      = date("Y-m-d",strtotime($rows["gd_date"]));
        //     $view['WAU'][]     = $rows["gd_wau"];
        //     $view["MAU"][]   = $rows["gd_mau"];
        // }
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
        $this->assign("active", "active");
        $this->assign("title",'活跃玩家');
        $this->assign('meg','您已进入活跃玩家列表中心！<br>--在对应的列输入搜索信息');
        $this->display("play_active.html");
    }
    /*新增玩家*/
    public function newplay(){
        if ($this->isadmin != 1 && !$this->checkright("new_play")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where     = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            //echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            showinfo("结束日期不能小于开始日期!","",3);
            die;
        }
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $export     = get_param("export")?get_param("export"):0;
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $actions    = get_param("actions");
        $ajx        = get_param("ajx");
        $updatephp  = "update_game_active";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $starttime2?$where  .= " and ga_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and ga_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and ga_gameid=".get_param("gid"):'';
        $aid?$where         .= " and ga_aid=".get_param("aid"):'';
        $adsons?$where      .= " and ga_wid=".get_param("adsons"):'';
        
        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and ga_aid in (".$hehe["sysid"].")";
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
        $sql    = "SELECT ga_date, ga_gameid, ga_aid, ga_wid, ga_opens, ga_activation, ga_device, ga_news FROM " .get_table('game_active'). " $where order by ga_date desc ";
        $sql_count    = "SELECT count(*) c FROM " .get_table('game_active'). " $where  ";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));
        $totalrecord = $totalrecord["c"];
        //点击查询
        $array = $sumarr = $pageinfo = array();
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
            $url    .=  "?module=play_active&method=newplay&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
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
        $str    =  "";
        while ($rows = $this->db->FetchArray($query)) {
            //求SUM
            $sumarr["s_act"]   += $rows["ga_activation"];//激活设备SUM
            $sumarr["s_new"]   += $rows["ga_news"];//新增注册SUM
            $sumarr["s_dev"]   += $rows["ga_device"];//新增设备SUM
            $sumarr["s_opens"] += $rows["ga_opens"];//打开设备SUM
            if($pgs>=$start && $pgs<$ends){
                $str .= "<tr>";
                $rows["ga_date"] = date("Y-m-d",strtotime($rows["ga_date"]));
                $rows["ga_gameid"] = $garr[$rows["ga_gameid"]]."[".$rows["ga_gameid"]."]";
                $rows["ga_aid"] = $aarr[$rows["ga_aid"]];
                $rows["ga_wid"] = $aarr[$rows["ga_wid"]];
                $playchange = $rows["ga_activation"]?round($rows["ga_device"]/$rows["ga_activation"]*100,2).'%':'';//玩家转换率
                $str .= "<td>".$rows["ga_date"]."</td><td>".$rows["ga_aid"]."</td><td>".$rows["ga_wid"]."</td><td>".$rows["ga_gameid"]."</td><td>".$rows["ga_opens"]."</td><td>".$rows["ga_activation"]."</td><td>".$rows["ga_device"]."</td><td>".$rows["ga_news"]."</td><td>".$playchange."</td>";
                $str .= "</tr>";
            }
            $pgs += 1;
        }
        //求平均
        if ($totalrecord > 0) {
            $sumarr["a_act"] = $sumarr["s_act"]?ceil($sumarr["s_act"]/$pgs):0;//激活设备AVG
            $sumarr["a_new"] = $sumarr["s_new"]?ceil($sumarr["s_new"]/$pgs):0;//新增注册AVG
            $sumarr["a_dev"] = $sumarr["s_dev"]?ceil($sumarr["s_dev"]/$pgs):0;//新增设备AVG

            $sumarr["sum_zhl"]=$sumarr["s_dev"]?round($sumarr["s_dev"]/$sumarr["s_act"]*100,2):0;

            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td><td>".$sumarr["s_opens"]."</td><td>".$sumarr["s_act"]."</td><td>".$sumarr["s_dev"]."</td><td>".$sumarr["s_new"]."</td><td>".$sumarr["sum_zhl"]."%</td></tr>";
        }
        //点击导出
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."新增玩家数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="9"><strong>新增玩家</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>打开设备</td><td>激活设备</td><td>新增设备</td><td>新增注册</td><td>玩家转换率</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view  = $category = array();
        $sql2   =  " SELECT ga_date, sum(ga_activation) ga_activation, sum(ga_news) ga_news, sum(ga_device) ga_device, sum(ga_opens) ga_opens FROM " .get_table('game_active'). " $where group by ga_date order by ga_date asc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $category[]      = date("Y-m-d",strtotime($rows["ga_date"]));
            $view['打开设备'][]    = $rows["ga_opens"];
            $view['激活设备'][]  = $rows["ga_activation"];
            $view["新增设备"][]   = $rows["ga_device"];
            $view['新增注册'][]     = $rows["ga_news"];
        }
        $this->assign("category",json_encode($category));
        $this->assign("view",json_encode($view));
        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("active",'newplay');
        $this->assign("title",'新增玩家');
        $this->assign("str",$str);
        $this->assign("arrsum",$sumarr);
        $this->assign("array",$array);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("actions",$actions);
        $this->assign('meg','您已进入用户统计列表中心！<br>--在对应的列输入搜索信息');
        $this->display("play_active.html");
    }
    /*留存数据*/
    public function retained(){
        if ($this->isadmin != 1 && !$this->checkright("retained")) {
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
        $updatephp  = "update_remain";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $starttime2?$where  .= " and cgr_regdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and cgr_regdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and cgr_gameid=".get_param("gid"):'';
        $aid?$where         .= " and cgr_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and cgr_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and cgr_uaid in (".$hehe["sysid"].")";
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
        $str = '';
        $newarr = $pageinfo =  array();
        $sql = "SELECT Group_concat(CONCAT_WS('||',cgr_remain,cgr_diff) order by cgr_diff asc) as info,cgr_uaid,cgr_uwid,cgr_gameid,cgr_regdate FROM " .get_table('game_remain'). " $where group by cgr_gameid,cgr_uaid,cgr_uwid,cgr_regdate order by cgr_regdate desc,cgr_gameid ";
        $totalrecord = $this->db->NumRows($this->db->Query($sql));
        $array = $sumarr = $pageinfo = $arr2 = array();
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
            $url    .=  "?module=play_active&method=retained&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }
        $query = $this->db->Query($sql);
        $shows = array(0,1,3,7,14,30);      //显示对应数据展示信息
        while ($rows = $this->db->FetchArray($query)) {
            //php数据处理
            $arr = explode(',',$rows['info']);
            foreach($arr as $k=>$v){
                $rem_diff = explode('||',$v);
                //计算百分比所需基数
                if($rem_diff[1] == 0){
                    $arr2['info0'] = $rem_diff[0];
                }
                if(in_array($rem_diff[1],$shows)){
                    $per = $arr2['info0']?round($rem_diff[0]/$arr2['info0'],4)*100:0;
                    $arr2['info'.$rem_diff[1]] = ($rem_diff[1]==0)?$rem_diff[0]:$rem_diff[0]."[$per%]";
                }
            }
            //求合计
            $sumarr["sum_info0"]  += $arr2["info0"];
            $sumarr["sum_info1"]  += $arr2["info1"];
            $sumarr["sum_info3"]  += $arr2["info3"];
            $sumarr["sum_info7"]  += $arr2["info7"];
            $sumarr["sum_info14"] += $arr2["info14"];
            $sumarr["sum_info30"] += $arr2["info30"];
            if($pgs>=$start && $pgs<$ends){
                $rows["cgr_regdate"] = date('Y-m-d',strtotime($rows["cgr_regdate"]));
                $rows["cgr_uaid"] = $aarr[$rows["cgr_uaid"]];
                $rows["cgr_uwid"] = $aarr[$rows["cgr_uwid"]];
                $rows["cgr_gameid"] = $garr[$rows["cgr_gameid"]]."[".$rows["cgr_gameid"]."]";
                $str .= "<tr>";
                $str .= "<td>".$rows["cgr_regdate"]."</td><td>".$rows["cgr_uaid"]."</td><td>".$rows["cgr_uwid"]."</td><td>".$rows["cgr_gameid"]."</td><td>".$arr2["info0"]."</td><td>".$arr2["info1"]."</td><td>".$arr2["info3"]."</td><td>".$arr2["info7"]."</td><td>".$arr2["info14"]."</td><td>".$arr2["info30"]."</td>";
                $str .= "</tr>";
                unset($arr2);
            }
            $pgs += 1;
        }
        if ($totalrecord>0) {
            $sumarr["sum_per_1"] = $sumarr["sum_info0"]?round($sumarr["sum_info1"]/$sumarr["sum_info0"]*100,2):0;
            $sumarr["sum_per_3"] = $sumarr["sum_info0"]?round($sumarr["sum_info3"]/$sumarr["sum_info0"]*100,2):0;
            $sumarr["sum_per_7"] = $sumarr["sum_info0"]?round($sumarr["sum_info7"]/$sumarr["sum_info0"]*100,2):0;
            $sumarr["sum_per_14"] = $sumarr["sum_info0"]?round($sumarr["sum_info14"]/$sumarr["sum_info0"]*100,2):0;
            $sumarr["sum_per_30"] = $sumarr["sum_info0"]?round($sumarr["sum_info30"]/$sumarr["sum_info0"]*100,2):0;

            $sumarr["sum_info1"]=$sumarr["sum_info1"]."[".$sumarr["sum_per_1"]."%]";
            $sumarr["sum_info3"]=$sumarr["sum_info3"]."[".$sumarr["sum_per_3"]."%]";
            $sumarr["sum_info7"]=$sumarr["sum_info7"]."[".$sumarr["sum_per_7"]."%]";
            $sumarr["sum_info14"]=$sumarr["sum_info14"]."[".$sumarr["sum_per_14"]."%]";
            
            $sumarr["sum_info30"]=$sumarr["sum_info30"]."[".$sumarr["sum_per_30"]."%]";
            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td><td>".$sumarr["sum_info0"]."</td><td>".$sumarr["sum_info1"]."</td><td>".$sumarr["sum_info3"]."</td><td>".$sumarr["sum_info7"]."</td><td>".$sumarr["sum_info14"]."</td><td>".$sumarr["sum_info30"]."</td></tr>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."登录留存数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="10"><strong>登录留存数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>新增玩家</td><td>次日留存</td><td>3日留存</td><td>7日留存</td><td>14日留存</td><td>30日留存</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view  = $category = $arr3 = array();
        $sql2 = "select cgr_diff,sum(cgr_remain) cgr_remain,cgr_regdate from ".get_table("game_remain")." $where group by cgr_regdate,cgr_diff order by cgr_regdate asc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $keys =date('Y-m-d',strtotime($rows["cgr_regdate"]));
            //当天注册用户数
            if($rows["cgr_diff"]==0){
                $arr3[$keys]["info0"] = $rows["cgr_remain"];
            }
            if(in_array($rows["cgr_diff"],$shows)){
                $per = $arr3[$keys]["info0"]?round($rows["cgr_remain"]/$arr3[$keys]["info0"],4)*100:0;
                $arr3[$keys]["info".$rows["cgr_diff"]] = $rows["cgr_remain"];
                $arr3[$keys]["per".$rows["cgr_diff"]] = $per;
            }
        }
        foreach ($arr3 as $key => $value) {
            $category[]      = $key;
            $view['次日留存'][]    = $value["per1"];
            $view['3日留存'][]  = $value["per3"];
            $view["7日留存"][]   = $value["per7"];
            $view['14日留存'][]     = $value["per14"];
            $view['30日留存'][]     = $value["per30"];
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
        $this->assign("title",'留存统计');
        $this->assign("active",'retained');
        $this->assign('meg','您已进入用户留存统计列表中心！<br>--在对应的列输入搜索信息');
        $this->display("play_active.html");
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