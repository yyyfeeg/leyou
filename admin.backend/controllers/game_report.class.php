<?php

#=============================================================================
#     FileName: game_week.class.php
#         Desc: 活跃类
#       Author: cooper
#        Email: cooper
#      Version: 0.0.1
#   LastChange: 2015-09-28
#      History:
#=============================================================================

class Game_report extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    /*游戏日报列表*/
    public function dreport(){
        if($this->isadmin!=1 && !$this->checkright("game_day") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $where  = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $action     = get_param("action");
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $export     = get_param("export")?get_param("export"):0;
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
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
        //活跃用户统计
        $sql    = "SELECT ga_date, ga_gameid, ga_aid, ga_wid, sum(ga_opens) ga_opens, sum(ga_activation) ga_activation, sum(ga_device) ga_device, sum(ga_news) ga_news,sum(ga_dau) ga_dau,sum(ga_rel_dau) ga_rel_dau,sum(ga_pay) ga_pay,sum(ga_money) ga_money,sum(ga_nums) ga_nums,sum(ga_sum_activation) ga_sum_activation, sum(ga_sum_news) ga_sum_news, sum(ga_sum_dau) ga_sum_dau, sum(ga_sum_pay) ga_sum_pay,sum(ga_sum_money) ga_sum_money FROM " .get_table('game_active'). " $where ";
        $sql .= " group by ga_date order by ga_date desc ";
        $query  = $this->db->Query($sql);

        $allarr = $newarr = $sumarr = $arr = $today = $yestoday = $yyestoday  = array();
        $str    =  "";
        $str_s  =  "";
        while ($rows = $this->db->FetchArray($query)) {
            $keys = $rows["ga_date"];
            $playchange = $rows["ga_activation"]?round($rows["ga_device"]/$rows["ga_activation"]*100,2):'0';//玩家转换率
            $regchange = $rows["ga_dau"]?round($rows["ga_news"]/$rows["ga_dau"]*100,2).'%':'';//注册转换率
            $daypay = $rows["ga_dau"]?round($rows["ga_pay"]/$rows["ga_dau"]*100,2).'%':'';//日付费率
            $daynums = $rows["ga_nums"]?ceil($rows["ga_dau"]/$rows["ga_nums"]):'0';//日平均游戏次数
            $arpu = $rows["ga_dau"]?round($rows["ga_money"]/$rows["ga_dau"],2):0;
            $arppu = $rows["ga_pay"]?round($rows["ga_money"]/$rows["ga_pay"],2):0;
            $all_pay = $rows["ga_sum_dau"]?round($rows["ga_sum_pay"]/$rows["ga_sum_dau"]*100,2).'%':'0%';//整体付费率
            $all_arpu = $rows["ga_sum_dau"]?round($rows["ga_sum_money"]/$rows["ga_sum_dau"],2):0;
            $all_arppu = $rows["ga_sum_pay"]?round($rows["ga_sum_money"]/$rows["ga_sum_pay"],2):0;

            $newarr[$keys]["ga_activation"] = $rows["ga_activation"];//激活设备
            $newarr[$keys]["ga_device"] = $rows["ga_device"];//新增设备
            $newarr[$keys]["ga_news"] = $rows["ga_news"];//新增玩家
            $newarr[$keys]["ga_dau"] = $rows["ga_dau"];//活跃玩家
            $newarr[$keys]["ga_rel_dau"] = $rows["ga_rel_dau"];//有效活跃玩家
            $newarr[$keys]["ga_money"] = $rows["ga_money"];//付费金额
            $newarr[$keys]["ga_pay"] = $rows["ga_pay"];//付费玩家人数
            $newarr[$keys]["ga_nums"] = $rows["ga_nums"];//登录次数
            $newarr[$keys]["daynums"] = $daynums;//日平均游戏次数
            $newarr[$keys]["arpu"] = $arpu;
            $newarr[$keys]["arppu"] = $arppu;
            $newarr[$keys]["daypay"] = $daypay;//日付费率
            $newarr[$keys]["playchange"] = $playchange;
            $newarr[$keys]["regchange"] = $regchange;

            $newarr[$keys]["ga_sum_activation"] = $rows["ga_sum_activation"];//累计激活设备
            $newarr[$keys]["ga_sum_pay"] = $rows["ga_sum_pay"];//累计付费玩家
            $newarr[$keys]["ga_sum_news"] = $rows["ga_sum_news"];//累计新增玩家
            $newarr[$keys]["ga_sum_money"] = $rows["ga_sum_money"];//累计付费金额
            $newarr[$keys]["all_pay"] = $all_pay;
            $newarr[$keys]["all_arpu"] = $all_arpu;
            $newarr[$keys]["all_arppu"] = $all_arppu;
        }

        //用户留存数据：
        $swhere               = " where 1";
        $starttime2?$swhere  .= " and cgr_regdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$swhere    .= " and cgr_regdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$swhere         .= " and cgr_gameid=".get_param("gid"):'';
        $aid?$swhere         .= " and cgr_uaid=".get_param("aid"):'';
        $adsons?$swhere      .= " and cgr_uwid=".get_param("adsons"):'';
        $sql = "select cgr_uaid,cgr_uwid,cgr_gameid,cgr_diff,sum(cgr_remain) cgr_remain,cgr_regdate from ".get_table("game_remain")." $swhere and cgr_diff<8 group by cgr_regdate,cgr_diff order by cgr_regdate desc";
        $query = $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $keys = $rows["cgr_regdate"];
            //当天注册用户数
            if($rows["cgr_diff"]==0){
                $newarr[$keys]["info0"] = $rows["cgr_remain"];
            }

            if($rows["cgr_diff"]==1 ||$rows["cgr_diff"]==3 || $rows["cgr_diff"]==7){
                $per = $newarr[$keys]["info0"]?round($rows["cgr_remain"]/$newarr[$keys]["info0"],4)*100:0;
                $newarr[$keys]["info".$rows["cgr_diff"]] = $rows["cgr_remain"]."[".$per."%]";
                $newarr[$keys]["per".$rows["cgr_diff"]] = $per;
            }
        }
        krsort($newarr);
        $nums   = 0;
        foreach ($newarr as $key => $val) {
            if($nums>2 && (array_sum($val)==0 || empty($val))) continue;
            switch ($nums) {
                case 0:
                    $today = $val;
                    break;
                case 1:
                    $yestoday = $val;
                    break;
                case 2:
                    $yyestoday = $val;
                    break;
            }
            $val["date"] = $key;
            $allarr[] = $val;
            $nums+=1;
        }
        $counts = count($allarr);
        $j      = 0;
        for($i=0;$i<$counts;$i++){
            $allarr[$i]["ga_gameid"] = $garr[$gid]."[".$gid."]";
            //求合计
            $sumarr["sum_ga_activation"] += $allarr[$i]["ga_activation"];
            $sumarr["sum_ga_device"]     += $allarr[$i]["ga_device"];
            $sumarr["sum_ga_news"]       += $allarr[$i]["ga_news"];
            $sumarr["sum_ga_dau"]        += $allarr[$i]["ga_dau"];
            $sumarr["sum_ga_money"]      += $allarr[$i]["ga_money"];
            $sumarr["sum_ga_pay"]        += $allarr[$i]["ga_pay"];
            $sumarr["sum_ga_nums"]       += $allarr[$i]["ga_nums"];
            $sumarr["sum_ga_rel_dau"]    += $allarr[$i]["ga_rel_dau"];
            $sumarr["sum_info1"]         += $allarr[$i]["info1"];
            $sumarr["sum_info3"]         += $allarr[$i]["info3"];
            $sumarr["sum_info7"]         += $allarr[$i]["info7"];
            //展示环比数据
            if($j==1){
                //激活设备
                $reg_dev = $today["ga_activation"]-$yestoday["ga_activation"];
                $reg_dev = $reg_dev>0?"<font color='red'>+".$reg_dev."</font>":"<font color='green'>".$reg_dev."</font>";

                //新增设备
                $new_dev = $today["ga_device"]-$yestoday["ga_device"];
                $new_dev = $new_dev>0?"<font color='red'>+".$new_dev."</font>":"<font color='green'>".$new_dev."</font>";

                //新增玩家
                $new_play = $today["ga_news"]-$yestoday["ga_news"];
                $new_play = $new_play>0?"<font color='red'>+".$new_play."</font>":"<font color='green'>".$new_play."</font>";

                //注册转化率
                $reg_chg = $today["regchange"]-$yestoday["regchange"];
                $reg_chg = $reg_chg>0?"<font color='red'>+".$reg_chg."</font>":"<font color='green'>".$reg_chg."</font>";

                //活跃玩家
                $new_dau = $today["ga_dau"]-$yestoday["ga_dau"];
                $new_dau = $new_dau>0?"<font color='red'>+".$new_dau."</font>":"<font color='green'>".$new_dau."</font>"; 

                //有效活跃玩家
                $new_rel_dau = $today["ga_rel_dau"]-$yestoday["ga_rel_dau"];
                $new_rel_dau = $new_rel_dau>0?"<font color='red'>+".$new_rel_dau."</font>":"<font color='green'>".$new_rel_dau."</font>";

                //总收入
                $new_mon = $today["ga_money"]-$yestoday["ga_money"];
                $new_mon = $new_mon>0?"<font color='red'>+".$new_mon."</font>":"<font color='green'>".$new_mon."</font>";

                //付费玩家
                $new_pay = $today["ga_pay"]-$yestoday["ga_pay"];
                $new_pay = $new_pay>0?"<font color='red'>+".$new_pay."</font>":"<font color='green'>".$new_pay."</font>";

                //付费率
                $new_ffl = $today["daypay"]-$yestoday["daypay"];
                $new_ffl = $new_ffl>0?"<font color='red'>+".$new_ffl."</font>":"<font color='green'>".$new_ffl."</font>";

                //ARPU
                $new_arp = $today["arpu"]-$yestoday["arpu"];
                $new_arp = $new_arp>0?"<font color='red'>+".$new_arp."</font>":"<font color='green'>".$new_arp."</font>";

                //ARRPU
                $new_arr = $today["arppu"]-$yestoday["arppu"];
                $new_arr = $new_arr>0?"<font color='red'>+".$new_arr."</font>":"<font color='green'>".$new_arr."</font>";

                //游戏次数
                $new_nums = $today["ga_nums"]-$yestoday["ga_nums"];
                $new_nums = $new_nums>0?"<font color='red'>+".$new_nums."</font>":"<font color='green'>".$new_nums."</font>";

                //平均日游戏次数
                $avg_nums = $today["daynums"]-$yestoday["daynums"];
                $avg_nums = $avg_nums>0?"<font color='red'>+".$avg_nums."</font>":"<font color='green'>".$avg_nums."</font>";

                //次日留存
                $rem_1 = $today["info1"]-$yestoday["info1"];
                $rema_1 = $today["per1"]-$yestoday["per1"];
                $rema_1 = $rema_1?"[".$rema_1."%]":'';
                $rem_1 = $rem_1>0?"<font color='red'>+".$rem_1.$rema_1."</font>":"<font color='green'>".$rem_1.$rema_1."</font>";

                //3日留存
                $rem_3 = $today["info3"]-$yestoday["info3"];
                $rema_3 = $today["per3"]-$yestoday["per3"];
                $rema_3 = $rema_3?"[".$rema_3."%]":'';
                $rem_3 = $rem_3>0?"<font color='red'>+".$rem_3.$rema_3."</font>":"<font color='green'>".$rem_3.$rema_3."</font>";

                //7日留存
                $rem_7 = $today["info7"]-$yestoday["info7"];
                $rema_7 = $today["per7"]-$yestoday["per7"];
                $rema_7 = $rema_7?"[".$rema_7."%]":'';
                $rem_7 = $rem_7>0?"<font color='red'>+".$rem_7.$rema_7."</font>":"<font color='green'>".$rem_7.$rema_7."</font>";

                //累计激活设备
                $reg_dev_s = $today["ga_sum_activation"]-$yestoday["ga_sum_activation"];
                $reg_dev_s = $reg_dev_s>0?"<font color='red'>+".$reg_dev_s."</font>":"<font color='green'>".$reg_dev_s."</font>";

                //累计新增玩家
                $new_play_s = $today["ga_sum_news"]-$yestoday["ga_sum_news"];
                $new_play_s = $new_play_s>0?"<font color='red'>+".$new_play_s."</font>":"<font color='green'>".$new_play_s."</font>";

                //累计付费玩家
                $new_pay_s = $today["ga_sum_pay"]-$yestoday["ga_sum_pay"];
                $new_pay_s = $new_pay_s>0?"<font color='red'>+".$new_pay_s."</font>":"<font color='green'>".$new_pay_s."</font>";

                //累计付费金额
                $new_mon_s = $today["ga_sum_money"]-$yestoday["ga_sum_money"];
                $new_mon_s = $new_mon_s>0?"<font color='red'>+".$new_mon_s."</font>":"<font color='green'>".$new_mon_s."</font>";

                //总体付费率
                $all_pay = $today["all_pay"]-$yestoday["all_pay"];
                $all_pay = $all_pay>0?"<font color='red'>+".$all_pay."</font>":"<font color='green'>".$all_pay."</font>";

                //累计ARPU
                $all_arpu = $today["all_arpu"]-$yestoday["all_arpu"];
                $all_arpu = $all_arpu>0?"<font color='red'>+".$all_arpu."</font>":"<font color='green'>".$all_arpu."</font>";

                //累计ARPPU
                $all_arppu = $today["all_arppu"]-$yestoday["all_arppu"];
                $all_arppu = $all_arppu>0?"<font color='red'>+".$all_arppu."</font>":"<font color='green'>".$all_arppu."</font>";
                $str_s .= "<tr>";  //累计表格
                $str_s .= "<td>环比昨日</td><td>".$allarr[$i]["ga_gameid"]."</td><td>".$reg_dev_s."</td><td>".$new_play_s."</td><td>".$new_pay_s."</td><td>".$new_mon_s."</td><td>".$all_pay."%</td><td>".$all_arpu."</td><td>".$all_arppu."</td>";  // <td>".$allarr[$i]["ga_aid"]."</td><td>".$allarr[$i]["ga_wid"]."</td> 暂时取消
                $str_s .= "</tr>";

                $str .= "<tr>";
                $str .= "<td >环比昨日</td><td>".$allarr[$i]["ga_gameid"]."</td><td>".$reg_dev."</td><td>".$new_dev."</td><td>".$new_play."</td><td>".$reg_chg."%</td><td>".$new_dau."</td><td>".$new_rel_dau."</td><td>".$new_mon."</td><td>".$new_pay."</td><td>".$new_ffl."%</td><td>".$new_arp."</td><td>".$new_arr."</td><td>".$new_nums."</td><td>".$avg_nums."</td><td>".$rem_1."</td><td>".$rem_3."</td><td>".$rem_7."</td>";  // <td>".$allarr[$i]["ga_aid"]."</td><td>".$allarr[$i]["ga_wid"]."</td> 暂时取消
                    $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td>".date("Y-m-d",strtotime($allarr[$i]["date"]))."</td><td>".$allarr[$i]["ga_gameid"]."</td><td>".$allarr[$i]["ga_activation"]."</td><td>".$allarr[$i]["ga_device"]."</td><td>".$allarr[$i]["ga_news"]."</td><td>".$allarr[$i]["regchange"]."</td><td>".$allarr[$i]["ga_dau"]."</td><td>".$allarr[$i]["ga_rel_dau"]."</td><td>".$allarr[$i]["ga_money"]."</td><td>".$allarr[$i]["ga_pay"]."</td><td>".$allarr[$i]["daypay"]."</td><td>".$allarr[$i]["arpu"]."</td><td>".$allarr[$i]["arppu"]."</td><td>".$allarr[$i]["ga_nums"]."</td><td>".$allarr[$i]["daynums"]."</td><td>".$allarr[$i]["info1"]."</td><td>".$allarr[$i]["info3"]."</td><td>".$allarr[$i]["info7"]."</td>";     // <td>".$allarr[$i]["ga_aid"]."</td><td>".$allarr[$i]["ga_wid"]."</td> 暂时取消渠道选项
            $str .= "</tr>";

            $str_s .= "<tr>";  //累计表格
            $str_s .= "<td>".date("Y-m-d",strtotime($allarr[$i]["date"]))."</td><td>".$allarr[$i]["ga_gameid"]."</td><td>".$allarr[$i]["ga_sum_activation"]."</td><td>".$allarr[$i]["ga_sum_news"]."</td><td>".$allarr[$i]["ga_sum_pay"]."</td><td>".$allarr[$i]["ga_sum_money"]."</td><td>".$allarr[$i]["all_pay"]."</td><td>".$allarr[$i]["all_arpu"]."</td><td>".$allarr[$i]["all_arppu"]."</td>";  // <td>".$allarr[$i]["ga_aid"]."</td><td>".$allarr[$i]["ga_wid"]."</td> 暂时取消
            $str_s .= "</tr>";
            $j   +=1;
        }
        if ($counts>0) {
            //注册转化率
            $sumarr["sum_zhl"]  = $sumarr["sum_ga_dau"]?round($sumarr["sum_ga_news"]/$sumarr["sum_ga_dau"],4)*100:0;
            //付费率
            $sumarr["sum_ffl"]    = $sumarr["sum_ga_dau"]?round($sumarr["sum_ga_pay"]/$sumarr["sum_ga_dau"],4)*100:0;
            //ARPU
            $sumarr["sum_arpu"]   = $sumarr["sum_ga_dau"]?round($sumarr["sum_ga_money"]/$sumarr["sum_ga_dau"],2):0;
            //ARPPU
            $sumarr["sum_arppu"]  = $sumarr["sum_ga_pay"]?round($sumarr["sum_ga_money"]/$sumarr["sum_ga_pay"],2):0;
            //平均日游戏次数
            $sumarr["sum_daynums"] = $sumarr["sum_ga_nums"]?ceil($sumarr["sum_ga_dau"]/$sumarr["sum_ga_nums"]):0;//日平均游戏次数
            $sumarr["sum_info1"] = $sumarr["sum_ga_news"]?$sumarr["sum_info1"]."[".(round($sumarr["sum_info1"]/$sumarr["sum_ga_news"],4)*100)."%]":0;
            $sumarr["sum_info3"] = $sumarr["sum_ga_news"]?$sumarr["sum_info3"]."[".(round($sumarr["sum_info3"]/$sumarr["sum_ga_news"],4)*100)."%]":0;
            $sumarr["sum_info7"] = $sumarr["sum_ga_news"]?$sumarr["sum_info7"]."[".(round($sumarr["sum_info7"]/$sumarr["sum_ga_news"],4)*100)."%]":0;
            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td>".$sumarr["sum_ga_activation"]."</td><td>".$sumarr["sum_ga_device"]."</td><td>".$sumarr["sum_ga_news"]."</td><td>".$sumarr["sum_zhl"]."%</td><td>".$sumarr["sum_ga_dau"]."</td><td>".$sumarr["sum_ga_rel_dau"]."</td><td>".$sumarr["sum_ga_money"]."</td><td>".$sumarr["sum_ga_pay"]."</td><td>".$sumarr["sum_ffl"]."%</td><td>".$sumarr["sum_arpu"]."</td><td>".$sumarr["sum_arppu"]."</td><td>".$sumarr["sum_ga_nums"]."</td><td>".$sumarr["sum_daynums"]."</td><td>".$sumarr["sum_info1"]."</td><td>".$sumarr["sum_info3"]."</td><td>".$sumarr["sum_info7"]."</td></tr>";
        }
        //点击导出按钮
        if($export == 1){
            if ($counts>0) {
                if ($counts>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."游戏日报数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="20"><strong>游戏日报数据</strong></td></tr><tr><td>日期</td><td>游戏</td><td>激活设备</td><td>新增设备</td><td>新增玩家</td><td>注册转化率（%）</td><td>活跃玩家</td><td>有效活跃玩家</td><td>付费金额</td><td>付费玩家数量</td><td>日付费率</td><td>ARPU</td><td>ARPPU</td><td>游戏次数</td><td>平均日游戏次数</td><td>次日留存</td><td>3日留存</td><td>7日留存</td></tr>'.$str .'<tr><td colspan="9"><strong>累计数据</strong></td></tr><tr><td>日期</td><td>游戏</td><td>累计激活设备</td><td>累计新增玩家</td><td>累计付费玩家</td><td>累计收入金额</td><td>总体付费率（%）</td><td>累计ARPU</td><td>累计ARPPU</td></tr>'.$str_s.'</table>');
            exit;
        }
        //禁止首次加载数据
        if ($action == "submit") {
            $this->assign("str",$str);
            $this->assign("str_s",$str_s);
        }

        $this->assign("report","dreport");
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入游戏报表统计列表中心！<br>--在对应的列输入搜索信息');
        $this->display("game_report.html");
    }
    /*游戏周报列表*/
    public function wreport(){
        if ($this->isadmin != 1 && !$this->checkright("game_week")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $action     = get_param("action");
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $updatephp  = "update_game_wactive";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $str    =  "";
        $newarr =  $arr = $today = $yestoday = $yyestoday  = array();
        $date1  =  get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        //计算对应周期
        $d      = date("d",strtotime($date1));
        $m      = date("m",strtotime($date1));
        $w      = date("w",strtotime($date1));
        $y      = date("y",strtotime($date1));
        $sdate  = date("Ymd",mktime(0, 0 , 0,$m,$d-$w-28,$y));    //开始时间，前端推4周
        $edate  = date("Ymd",mktime(0, 0 , 0,$m,$d-$w+8,$y));     //结束时间

        $where              .= " where gw_sdate>".$sdate." and gw_edate<".$edate;
        $gid?$where         .= " and gw_gameid=".get_param("gid"):'';
        $aid?$where         .= " and gw_aid=".get_param("aid"):'';
        $adsons?$where      .= " and gw_wid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and gw_aid in (".$hehe["sysid"].") ";
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
        $sql = "SELECT gw_sdate,gw_edate,gw_aid,gw_wid,gw_gameid,sum(gw_activation) gw_activation,sum(gw_wdevice) gw_wdevice,sum(gw_wau) gw_wau,sum(gw_wnews) gw_wnews,sum(gw_wpay) gw_wpay,sum(gw_wmoney) gw_wmoney,sum(gw_rel_dau) gw_rel_dau,sum(gw_wof) gw_wof,sum(gw_wrf) gw_wrf,sum(gw_remain) gw_remain FROM " .get_table('game_wactive'). " $where ";
            $sql .= " group by gw_sdate order by gw_sdate Desc ";
            $query  = $this->db->Query($sql);
            $nums   = 0;

            while ($rows = $this->db->FetchArray($query)) {
                switch ($nums) {
                    case 0:
                        $today = $rows;
                        break;
                    case 1:
                        $yestoday = $rows;
                        break;
                    case 2:
                        $yyestoday = $rows;
                        break;
                }
                $newarr[] = $rows;
                $nums+=1;
            }
            $counts = count($newarr);
            $j      = 0;
            for($i=0;$i<$counts;$i++){
                //剔除最后数据
                if($j>$counts-2){
                    continue;
                }
                $newarr[$i]["gw_gameid"] = $garr[$newarr[$i]["gw_gameid"]]."[".$newarr[$i]["gw_gameid"]."]";
                $newarr[$i]["gw_aid"] = $aarr[$newarr[$i]["gw_aid"]];
                $newarr[$i]["gw_wid"] = $aarr[$newarr[$i]["gw_wid"]];
                //求合计
                $sumarr["sum_gw_activation"] += $newarr[$i]["gw_activation"];
                $sumarr["sum_gw_wdevice"]    += $newarr[$i]["gw_wdevice"];
                $sumarr["sum_gw_wnews"]      += $newarr[$i]["gw_wnews"];
                $sumarr["sum_gw_wau"]        += $newarr[$i]["gw_wau"];
                $sumarr["sum_gw_rel_dau"]    += $newarr[$i]["gw_rel_dau"];
                $sumarr["sum_gw_wmoney"]     += $newarr[$i]["gw_wmoney"];
                $sumarr["sum_gw_wpay"]       += $newarr[$i]["gw_wpay"];
                $sumarr["sum_gw_wof"]        += $newarr[$i]["gw_wof"];
                $sumarr["sum_gw_wrf"]        += $newarr[$i]["gw_wrf"];
                 //周付费率
                $newarr[$i]["ffl"]    = $newarr[$i]["gw_wau"]?round($newarr[$i]["gw_wpay"]/$newarr[$i]["gw_wau"],4)*100:0;

                //ARPU
                $newarr[$i]["arpu"]   = $newarr[$i]["gw_wau"]?round($newarr[$i]["gw_wmoney"]/$newarr[$i]["gw_wau"],2):0;

                //ARRPU
                $newarr[$i]["arppu"]  = $newarr[$i]["gw_wpay"]?round($newarr[$i]["gw_wmoney"]/$newarr[$i]["gw_wpay"],2):0; 

                //周次留
                $newarr[$i]["remain"] = $newarr[$i]["gw_remain"]?ceil($newarr[$i]["gw_remain"]/7):0;
                $sumarr["sum_remain"]        += $newarr[$i]["remain"];
                //周流失率
                $newarr[$i]["loss"]   = $newarr[$i]["gw_wof"]?@round($newarr[$i]["gw_wof"]/$newarr[$i+1]["gw_wau"],2)*100:0;

                //展示环比数据
                if($j==1){
                    //激活设备
                    $reg_dev = $today["gw_activation"]-$yestoday["gw_activation"];
                    $reg_dev = $reg_dev>0?"<font color='red'>+".$reg_dev."</font>":"<font color='green'>".$reg_dev."</font>";

                    //新增设备
                    $new_dev = $today["gw_wdevice"]-$yestoday["gw_wdevice"];
                    $new_dev = $new_dev>0?"<font color='red'>+".$new_dev."</font>":"<font color='green'>".$new_dev."</font>"; 

                    //新增玩家
                    $new_play = $today["gw_wnews"]-$yestoday["gw_wnews"];
                    $new_play = $new_play>0?"<font color='red'>+".$new_play."</font>":"<font color='green'>".$new_play."</font>"; 

                    //活跃玩家
                    $new_wau = $today["gw_wau"]-$yestoday["gw_wau"];
                    $new_wau = $new_wau>0?"<font color='red'>+".$new_wau."</font>":"<font color='green'>".$new_wau."</font>"; 

                    //有效活跃玩家
                    $rel_wau = $today["gw_rel_dau"]-$yestoday["gw_rel_dau"];
                    $rel_wau = $new_wau>0?"<font color='red'>+".$rel_wau."</font>":"<font color='green'>".$rel_wau."</font>"; 

                    //总收入
                    $new_mon = $today["gw_wmoney"]-$yestoday["gw_wmoney"];
                    $new_mon = $new_mon>0?"<font color='red'>+".$new_mon."</font>":"<font color='green'>".$new_mon."</font>"; 

                    //付费玩家
                    $new_pay = $today["gw_wpay"]-$yestoday["gw_wpay"];
                    $new_pay = $new_pay>0?"<font color='red'>+".$new_pay."</font>":"<font color='green'>".$new_pay."</font>"; 

                    //付费率
                    $to_ffl  = $today["gw_wau"]?round($today["gw_wpay"]/$today["gw_wau"],4)*100:0;
                    $ye_ffl  = $yestoday["gw_wau"]?round($yestoday["gw_wpay"]/$yestoday["gw_wau"],4)*100:0;
                    $new_ffl = $to_ffl-$ye_ffl;
                    $new_ffl = $new_ffl>0?"<font color='red'>+".$new_ffl."</font>":"<font color='green'>".$new_ffl."</font>"; 

                    //ARPU
                    $to_arp  = $today["gw_wau"]?round($today["gw_wmoney"]/$today["gw_wau"],4)*100:0;
                    $ye_arp  = $yestoday["gw_wau"]?round($yestoday["gw_wmoney"]/$yestoday["gw_wau"],4)*100:0;
                    $new_arp = $to_arp-$ye_arp;
                    $new_arp = $new_arp>0?"<font color='red'>+".$new_arp."</font>":"<font color='green'>".$new_arp."</font>";

                    //ARRPU
                    $to_arr  = $today["gw_wpay"]?round($today["gw_wmoney"]/$today["gw_wpay"],4)*100:0;
                    $ye_arr  = $yestoday["gw_wpay"]?round($yestoday["gw_wmoney"]/$yestoday["gw_wpay"],4)*100:0;
                    $new_arr = $to_arr-$ye_arr;
                    $new_arr = $new_arr>0?"<font color='red'>+".$new_arr."</font>":"<font color='green'>".$new_arr."</font>";

                    //周次留
                    $to_rem  = $today["gw_remain"]?ceil($today["gw_remain"]/7):0;
                    $ye_rem  = $yestoday["gw_remain"]?ceil($yestoday["gw_remain"]/7):0;
                    $new_rem = $to_rem-$ye_rem;
                    $new_rem = $new_rem>0?"<font color='red'>+".$new_rem."</font>":"<font color='green'>".$new_rem."</font>";

                    //流失玩家数
                    $new_wof = $today["gw_wof"]-$yestoday["gw_wof"];
                    $new_wof = $new_wof>0?"<font color='red'>+".$new_wof."</font>":"<font color='green'>".$new_wof."</font>"; 

                    //周回流玩家数
                    $new_wrf = $today["gw_wrf"]-$yestoday["gw_wrf"];
                    $new_wrf = $new_wrf>0?"<font color='red'>+".$new_wrf."</font>":"<font color='green'>".$new_wrf."</font>"; 

                    //周流失
                    $to_wss  = $today["gw_wof"]?round($today["gw_wof"]/$yestoday["gw_wau"],2)*100:0;
                    $ye_wss  = $yestoday["gw_wof"]?@round($yestoday["gw_wof"]/$yyestoday["gw_wau"],2)*100:0;
                    $new_wss = $to_wss-$ye_wss;
                    $new_wss = $new_wss>0?"<font color='red'>+".$new_wss."</font>":"<font color='green'>".$new_wss."</font>";

                    $str .= "<tr>";
                    $str .= "<td style='text-align: center'>环比上周</td><td>".$newarr[$i]["gw_gameid"]."</td><td>".$reg_dev."</td><td>".$new_dev."</td><td>".$new_play."</td><td>".$new_wau."</td><td>".$rel_wau."</td><td>".$new_mon."</td><td>".$new_pay."</td><td>".$new_ffl."%</td><td>".$new_arp."</td><td>".$new_arr."</td><td>".$new_rem."</td><td>".$new_wof."</td><td>".$new_wss."</td><td>".$new_wrf."%</td>";
                    $str .= "</tr>";
                }
                $str .= "<tr>";
                $str .= "<td>".date("Y-m-d",strtotime($newarr[$i]["gw_sdate"]))."~".date("Y-m-d",strtotime($newarr[$i]["gw_edate"]))."</td><td>".$newarr[$i]["gw_gameid"]."</td><td>".$newarr[$i]["gw_activation"]."</td><td>".$newarr[$i]["gw_wdevice"]."</td><td>".$newarr[$i]["gw_wnews"]."</td><td>".$newarr[$i]["gw_wau"]."</td><td>".$newarr[$i]["gw_rel_dau"]."</td><td>".$newarr[$i]["gw_wmoney"]."</td><td>".$newarr[$i]["gw_wpay"]."</td><td>".$newarr[$i]["ffl"]."%</td><td>".$newarr[$i]["arpu"]."</td><td>".$newarr[$i]["arppu"]."</td><td>".$newarr[$i]["remain"]."</td><td>".$newarr[$i]["gw_wof"]."</td><td>".$newarr[$i]["loss"]."%</td><td>".$newarr[$i]["gw_wrf"]."</td>";
                $str .= "</tr>";
                $j   +=1;
            }
        if ($counts>0) {
            //付费率
            $sumarr["sum_ffl"]    = $sumarr["sum_gw_wau"]?round($sumarr["sum_gw_wpay"]/$sumarr["sum_gw_wau"],4)*100:0;
            //ARPU
            $sumarr["sum_arpu"]   = $sumarr["sum_gw_wau"]?round($sumarr["sum_gw_wmoney"]/$sumarr["sum_gw_wau"],2):0;
            //ARPPU
            $sumarr["sum_arppu"]  = $sumarr["sum_gw_wpay"]?round($sumarr["sum_gw_wmoney"]/$sumarr["sum_gw_wpay"],2):0;

            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td>".$sumarr["sum_gw_activation"]."</td><td>".$sumarr["sum_gw_wdevice"]."</td><td>".$sumarr["sum_gw_wnews"]."</td><td>".$sumarr["sum_gw_wau"]."</td><td>".$sumarr["sum_gw_rel_dau"]."</td><td>".$sumarr["sum_gw_wmoney"]."</td><td>".$sumarr["sum_gw_wpay"]."</td><td>".$sumarr["sum_ffl"]."%</td><td>".$sumarr["sum_arpu"]."</td><td>".$sumarr["sum_arppu"]."</td><td>".$sumarr["sum_remain"]."</td><td>".$sumarr["sum_gw_wof"]."</td><td>/</td><td>".$sumarr["sum_gw_wrf"]."</td></tr>";
        }
        //点击导出按钮
        if($export == 1){
            if ($counts>0) {
                if ($counts>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$sdate."至".$edate."游戏周报数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="20"><strong>游戏周报数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>周激活设备</td><td>周新增设备</td><td>周新增玩家</td><td>周活跃玩家（WAU）</td><td>周有效活跃玩家</td><td>周付费金额</td><td>周付费玩家数量</td><td>周付费率</td><td>周ARPU</td><td>周ARPPU</td><td>周次日留存率</td><td>周流失玩家数量</td><td>周流失率</td><td>周回流玩家数量</td></tr>'.$str .'</table>');
            exit;
        }
        if ($action == "submit") {
            $this->assign("str",$str);
        }

        $this->assign("starttime2",$date1);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("report", "wreport");
        $this->assign('meg','您已进入游戏周报数据！');
        $this->display("game_report.html");
    }
    /*游戏月报列表*/
    public function mreport(){
        if ($this->isadmin != 1 && !$this->checkright("game_month")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $export     = get_param("export")?get_param("export"):0;
        $action     = get_param("action");
        $aarr       = ad_get($this->db);
        $garr       = get_game($this->db);
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $updatephp  = "update_game_mactive";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $str    =  "";
        $newarr =  $arr = $today = $yestoday = $yyestoday  = array();
        $date1  =  get_param("starttime2")?str_replace("-","",get_param("starttime2")):date("Ym");
        $year   =  substr($date1,0,4);
        $month  =  substr($date1,4,2);
        $sdate  =  date("Ym",mktime(0,0,0,$month-4,1,$year));
        $edate  =  date("Ym",mktime(0,0,0,$month+1,1,$year));

        $where              .= " where gm_month>".$sdate." and gm_month<".$edate;
        $gid?$where         .= " and gm_gameid=".get_param("gid"):'';
        $aid?$where         .= " and gm_aid=".get_param("aid"):'';
        $adsons?$where      .= " and gm_wid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and gm_aid in (".$hehe["sysid"].") ";
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
        $sql = "SELECT gm_month,gm_aid,gm_wid,gm_gameid,sum(gm_activation) gm_activation,sum(gm_mdevice) gm_mdevice,sum(gm_mau) gm_mau,sum(gm_mnews) gm_mnews,sum(gm_mpay) gm_mpay,sum(gm_mmoney) gm_mmoney,sum(gm_rel_dau) gm_rel_dau,sum(gm_mof) gm_mof,sum(gm_mrf) gm_mrf,sum(gm_remain) gm_remain FROM " .get_table('game_mactive'). " $where ";
            $sql .= " group by gm_month order by gm_month Desc ";
            $query  = $this->db->Query($sql);
            $nums   = 0;
            while ($rows = $this->db->FetchArray($query)) {
                switch ($nums) {
                    case 0:     
                        $today = $rows;
                        break;
                    case 1:
                        $yestoday = $rows;
                        break;
                    case 2:
                        $yyestoday = $rows;
                        break;
                }
                $newarr[] = $rows;
                $nums+=1;
            }

            $counts = count($newarr);
            $j      = 0;
            for($i=0;$i<$counts;$i++){
                //剔除最后数据
                if($j>$counts-2){
                    continue;
                }
                //求合计
                $sumarr["sum_gm_activation"] += $newarr[$i]["gm_activation"];
                $sumarr["sum_gm_mdevice"]    += $newarr[$i]["gm_mdevice"];
                $sumarr["sum_gm_mnews"]      += $newarr[$i]["gm_mnews"];
                $sumarr["sum_gm_mau"]        += $newarr[$i]["gm_mau"];
                $sumarr["sum_gm_rel_dau"]    += $newarr[$i]["gm_rel_dau"];
                $sumarr["sum_gm_mmoney"]     += $newarr[$i]["gm_mmoney"];
                $sumarr["sum_gm_mpay"]       += $newarr[$i]["gm_mpay"];
                $sumarr["sum_gm_mof"]        += $newarr[$i]["gm_mof"];
                $sumarr["sum_gm_mrf"]        += $newarr[$i]["gm_mrf"];
                $newarr[$i]["gm_gameid"] = $garr[$newarr[$i]["gm_gameid"]]."[".$newarr[$i]["gm_gameid"]."]";
                $newarr[$i]["gm_aid"] = $aarr[$newarr[$i]["gm_aid"]];
                $newarr[$i]["gm_wid"] = $aarr[$newarr[$i]["gm_wid"]];
                 //月付费率
                $newarr[$i]["ffl"]    = $newarr[$i]["gm_mau"]?round($newarr[$i]["gm_mpay"]/$newarr[$i]["gm_mau"],4)*100:0;
                //ARPU
                $newarr[$i]["arpu"]   = $newarr[$i]["gm_mau"]?round($newarr[$i]["gm_mmoney"]/$newarr[$i]["gm_mau"],2):0;

                //ARRPU
                $newarr[$i]["arppu"]  = $newarr[$i]["gm_mpay"]?round($newarr[$i]["gm_mmoney"]/$newarr[$i]["gm_mpay"],2):0; 

                //月次留
                $newarr[$i]["remain"] = $newarr[$i]["gm_remain"]?ceil($newarr[$i]["gm_remain"]/30):0;
                $sumarr["sum_remain"]        += $newarr[$i]["remain"];
                //月流失率
                $newarr[$i]["loss"]   = $newarr[$i]["gm_mof"]?@round($newarr[$i]["gm_mof"]/$newarr[$i+1]["gm_mau"],2)*100:0;

                //展示环比数据
                if($j==1){
                    //激活设备
                    $reg_dev = $today["gm_activation"]-$yestoday["gm_activation"];
                    $reg_dev = $reg_dev>0?"<font color='red'>+".$reg_dev."</font>":"<font color='green'>".$reg_dev."</font>";

                    //新增设备
                    $new_dev = $today["gm_mdevice"]-$yestoday["gm_mdevice"];
                    $new_dev = $new_dev>0?"<font color='red'>+".$new_dev."</font>":"<font color='green'>".$new_dev."</font>"; 

                    //新增玩家
                    $new_play = $today["gm_mnews"]-$yestoday["gm_mnews"];
                    $new_play = $new_play>0?"<font color='red'>+".$new_play."</font>":"<font color='green'>".$new_play."</font>"; 

                    //活跃玩家
                    $new_wau = $today["gm_mau"]-$yestoday["gm_mau"];
                    $new_wau = $new_wau>0?"<font color='red'>+".$new_wau."</font>":"<font color='green'>".$new_wau."</font>"; 

                    //有效活跃玩家
                    $rel_wau = $today["gm_rel_dau"]-$yestoday["gm_rel_dau"];
                    $rel_wau = $new_wau>0?"<font color='red'>+".$rel_wau."</font>":"<font color='green'>".$rel_wau."</font>"; 

                    //总收入
                    $new_mon = $today["gm_mmoney"]-$yestoday["gm_mmoney"];
                    $new_mon = $new_mon>0?"<font color='red'>+".$new_mon."</font>":"<font color='green'>".$new_mon."</font>"; 

                    //付费玩家
                    $new_pay = $today["gm_mpay"]-$yestoday["gm_mpay"];
                    $new_pay = $new_pay>0?"<font color='red'>+".$new_pay."</font>":"<font color='green'>".$new_pay."</font>"; 

                    //付费率
                    $to_ffl  = $today["gm_mau"]?round($today["gm_mpay"]/$today["gm_mau"],4)*100:0;
                    $ye_ffl  = $yestoday["gm_mau"]?round($yestoday["gm_mpay"]/$yestoday["gm_mau"],4)*100:0;
                    $new_ffl = $to_ffl-$ye_ffl;
                    $new_ffl = $new_ffl>0?"<font color='red'>+".$new_ffl."</font>":"<font color='green'>".$new_ffl."</font>"; 

                    //ARPU
                    $to_arp  = $today["gm_mau"]?round($today["gm_mmoney"]/$today["gm_mau"],4)*100:0;
                    $ye_arp  = $yestoday["gm_mau"]?round($yestoday["gm_mmoney"]/$yestoday["gm_mau"],4)*100:0;
                    $new_arp = $to_arp-$ye_arp;
                    $new_arp = $new_arp>0?"<font color='red'>+".$new_arp."</font>":"<font color='green'>".$new_arp."</font>";

                    //ARRPU
                    $to_arr  = $today["gm_mpay"]?round($today["gm_mmoney"]/$today["gm_mpay"],4)*100:0;
                    $ye_arr  = $yestoday["gm_mpay"]?round($yestoday["gm_mmoney"]/$yestoday["gm_mpay"],4)*100:0;
                    $new_arr = $to_arr-$ye_arr;
                    $new_arr = $new_arr>0?"<font color='red'>+".$new_arr."</font>":"<font color='green'>".$new_arr."</font>";

                    //周次留
                    $to_rem  = $today["gm_remain"]?ceil($today["gm_remain"]/30):0;
                    $ye_rem  = $yestoday["gm_remain"]?ceil($yestoday["gm_remain"]/30):0;
                    $new_rem = $to_rem-$ye_rem;
                    $new_rem = $new_rem>0?"<font color='red'>+".$new_rem."</font>":"<font color='green'>".$new_rem."</font>";

                    //流失玩家数
                    $new_wof = $today["gm_mof"]-$yestoday["gm_mof"];
                    $new_wof = $new_wof>0?"<font color='red'>+".$new_wof."</font>":"<font color='green'>".$new_wof."</font>"; 

                    //周回流玩家数
                    $new_wrf = $today["gm_mrf"]-$yestoday["gm_mrf"];
                    $new_wrf = $new_wrf>0?"<font color='red'>+".$new_wrf."</font>":"<font color='green'>".$new_wrf."</font>"; 

                    //周流失
                    $to_wss  = $today["gm_mof"]?round($today["gm_mof"]/$yestoday["gm_mau"],2)*100:0;
                    $ye_wss  = $yestoday["gm_mof"]?@round($yestoday["gm_mof"]/$yyestoday["gm_mau"],2)*100:0;
                    $new_wss = $to_wss-$ye_wss;
                    $new_wss = $new_wss>0?"<font color='red'>+".$new_wss."</font>":"<font color='green'>".$new_wss."</font>";

                    $str .= "<tr>";
                    $str .= "<td style='text-align: center'>环比上月</td><td>".$newarr[$i]["gm_gameid"]."</td><td>".$reg_dev."</td><td>".$new_dev."</td><td>".$new_play."</td><td>".$new_wau."</td><td>".$rel_wau."</td><td>".$new_mon."</td><td>".$new_pay."</td><td>".$new_ffl."%</td><td>".$new_arp."</td><td>".$new_arr."</td><td>".$new_rem."</td><td>".$new_wof."</td><td>".$new_wss."</td><td>".$new_wrf."%</td>";
                    $str .= "</tr>";
                }
                $str .= "<tr>";
                $str .= "<td>".date("Y-m",strtotime($newarr[$i]["gm_month"]."01"))."</td><td>".$newarr[$i]["gm_gameid"]."</td><td>".$newarr[$i]["gm_activation"]."</td><td>".$newarr[$i]["gm_mdevice"]."</td><td>".$newarr[$i]["gm_mnews"]."</td><td>".$newarr[$i]["gm_mau"]."</td><td>".$newarr[$i]["gm_rel_dau"]."</td><td>".$newarr[$i]["gm_mmoney"]."</td><td>".$newarr[$i]["gm_mpay"]."</td><td>".$newarr[$i]["ffl"]."%</td><td>".$newarr[$i]["arpu"]."</td><td>".$newarr[$i]["arppu"]."</td><td>".$newarr[$i]["remain"]."</td><td>".$newarr[$i]["gm_mof"]."</td><td>".$newarr[$i]["loss"]."%</td><td>".$newarr[$i]["gm_mrf"]."</td>";
                $str .= "</tr>";
                $j   +=1;
            }
        if ($counts>0) {
            //付费率
            $sumarr["sum_ffl"]    = $sumarr["sum_gm_mau"]?round($sumarr["sum_gm_mpay"]/$sumarr["sum_gm_mau"],4)*100:0;
            //ARPU
            $sumarr["sum_arpu"]   = $sumarr["sum_gm_mau"]?round($sumarr["sum_gm_mmoney"]/$sumarr["sum_gm_mau"],2):0;
            //ARPPU
            $sumarr["sum_arppu"]  = $sumarr["sum_gm_mpay"]?round($sumarr["sum_gm_mmoney"]/$sumarr["sum_gm_mpay"],2):0;
            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td>".$sumarr["sum_gm_activation"]."</td><td>".$sumarr["sum_gm_mdevice"]."</td><td>".$sumarr["sum_gm_mnews"]."</td><td>".$sumarr["sum_gm_mau"]."</td><td>".$sumarr["sum_gm_rel_dau"]."</td><td>".$sumarr["sum_gm_mmoney"]."</td><td>".$sumarr["sum_gm_mpay"]."</td><td>".$sumarr["sum_ffl"]."%</td><td>".$sumarr["sum_arpu"]."</td><td>".$sumarr["sum_arppu"]."</td><td>".$sumarr["sum_remain"]."</td><td>".$sumarr["sum_gm_mof"]."</td><td>/</td><td>".$sumarr["sum_gm_mrf"]."</td></tr>";
        }
         //点击导出按钮
        if($export == 1){
            if ($counts>0) {
                if ($counts>20000) {
                    showinfo("数据量太大了，请选择合适的条件再进行导出!","",3);
                    die;
                }
            }
            else{
                showinfo("没有数据!","",3);
                die;
            }
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:attachment;filename=".$sdate."至".$edate."游戏月报数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="20"><strong>游戏月报数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>月激活设备</td><td>月新增设备</td><td>月新增玩家</td><td>月活跃玩家（MAU）</td><td>月有效活跃玩家</td><td>月付费金额</td><td>月付费玩家数量</td><td>月付费率</td><td>月ARPU</td><td>月ARPPU</td><td>月次日留存率</td><td>月流失玩家数量</td><td>月流失率</td><td>月回流玩家数量</td></tr>'.$str .'</table>');
            exit;
        }
        if ($action == "submit") {
            $this->assign("str",$str);
        }
        $this->assign("starttime2",date("Y-m",strtotime($date1."01")));
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("report", "mreport");
        $this->assign('meg','您已进入游戏月报数据！');
        $this->display("game_report.html");
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