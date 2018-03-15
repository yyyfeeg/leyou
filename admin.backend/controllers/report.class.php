<?php
/**
 * @project  : 统计后台
 * @explain  : 平台报表
 * @filename : report.class.php
 * @author   : Jericho
 * @codetime : 2017-07-27
 * @modifier :
 * @modifytime:
 */
class Report extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign("isadmin",$this->isadmin);
        get_games_conn();
    }
    /**
     * @explain 报表(日)
     *
     */
    public function reportday()
    {
        if($this->isadmin!=1 && !$this->checkright("reportday") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        
        $str        =  "";
        $newarr     =  $arr = $sumarr = array();
        $export     = get_param("export")?get_param("export"):0;
        $date1      =  get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $date2      =  get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $sdate      =  date('Ymd',strtotime($date1)-1);
        $edate      =  date('Ymd',strtotime($date2)+86400);
        $updatephp  = "update_all_dayinfo";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        //测试数据
        // $sdate  =  20160831;
        // $edate  =  20161001;

        //查询基本数据：
        $sql    =  "select * from ".get_table("all_dayinfo")." where ad_date>$sdate and ad_date<$edate";
        $query  =  $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $key = $rows["ad_date"];
            $newarr[$key]["ad_activation"]  = $rows["ad_activation"];
            $newarr[$key]["ad_opens"]       = $rows["ad_opens"];
            $newarr[$key]["ad_dau"]         = $rows["ad_dau"];
            $newarr[$key]["ad_device"]      = $rows["ad_device"];
            $newarr[$key]["ad_news"]        = $rows["ad_news"];
            $newarr[$key]["ad_pay"]         = $rows["ad_pay"];
            $newarr[$key]["ad_money"]       = $rows["ad_money"];
        }


        //用户留存数据：
        $sql = "select car_diff,car_remain,car_regdate from ".get_table("all_remain")." where car_regdate>$sdate and car_regdate<$edate and car_diff<8 group by car_regdate,car_diff order by car_regdate,car_diff";
        $query = $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $key  = $rows["car_regdate"];
            //当天注册用户数
            if($rows["car_diff"]==0){
                $arr["info_0"] = $rows["car_remain"];
            }
            if($rows["car_diff"]==1 ||$rows["car_diff"]==3 || $rows["car_diff"]==7){
                $per = $arr["info_0"]?round($rows["car_remain"]/$arr["info_0"],4)*100:0;
                $arr["info_".$rows["car_diff"]] = $rows["car_remain"]."[".$per."%]";
            }
            $newarr[$key]["lc"] = $arr;
        }

        //设备留存数据：
        $sql = "select car_diff,car_remain,car_regdate from ".get_table("all_dev_remain")." where car_regdate>$sdate and car_regdate<$edate and car_diff<8 group by car_regdate,car_diff order by car_regdate,car_diff";
        $query = $this->db->query($sql);
        while($rows = $this->db->Fetcharray($query)){
            $key  = $rows["car_regdate"];
            //当天注册用户数
            if($rows["car_diff"]==0){
                $arr["info_0"] = $rows["car_remain"];
            }
            if($rows["car_diff"]==1 ||$rows["car_diff"]==3 || $rows["car_diff"]==7){
                $per = $arr["info_0"]?round($rows["car_remain"]/$arr["info_0"],4)*100:0;
                $arr["info_".$rows["car_diff"]] = $rows["car_remain"]."[".$per."%]";
            }
            $newarr[$key]["sb"] = $arr;
        }
        ksort($newarr);

        //展示数据：

        foreach($newarr as $k=>$v){
            $k                  = date("Y-m-d",strtotime($k));
            $str               .= "<tr>";
            $v["ad_device"]     = $v["ad_device"]?$v["ad_device"]:0;
            $v["ad_activation"] = $v["ad_activation"]?$v["ad_activation"]:0;
            $v["ad_news"]       = $v["ad_news"]?$v["ad_news"]:0;
            $v["ad_money"]      = $v["ad_money"]?$v["ad_money"]:0;
            $v["ad_pay"]        = $v["ad_pay"]?$v["ad_pay"]:0;
            $v["ad_dau"]        = $v["ad_dau"]?$v["ad_dau"]:0;
            $v["zhl"]           = $v["ad_device"]?round($v["ad_device"]/$v["ad_activation"],4)*100:0;;
            $v["ffl"]           = $v["ad_pay"]?round($v["ad_pay"]/$v["ad_dau"],4)*100:0;
            //求sum值
            $sumarr["sum_ad_device"]        += $v["ad_device"];
            $sumarr["sum_ad_activation"]    += $v["ad_activation"];
            $sumarr["sum_ad_news"]          += $v["ad_news"];
            $sumarr["sum_ad_money"]         += $v["ad_money"];
            $sumarr["sum_ad_pay"]           += $v["ad_pay"];
            $sumarr["sum_ad_dau"]           += $v["ad_dau"];

            $str .= "<td>".$k."</td><td>".$v["ad_activation"]."</td><td>".$v["ad_device"]."</td><td>".$v["ad_news"]."</td><td>".$v["zhl"]."%</td><td>".$v["ad_money"]."</td><td>".$v["ad_pay"]."</td><td>".$v["ffl"]."%</td><td>".$v["ad_dau"]."</td>";

            //设备留存
            $k0 = $k1 = $k3 = $k7 = 0;
            if(!empty($v["sb"])){
                foreach($v["sb"] as $k=>$v){
                    switch ($k) {
                        case 'info_0':
                            $k0 = $v;
                            $sumarr["sum_sb_0"] +=$v;
                            break;
                        case 'info_1':
                            $k1 = $v;
                            $sumarr["sum_sb_1"] +=$v;
                            break;
                        case 'info_3':
                            $k3 = $v;
                            $sumarr["sum_sb_3"] +=$v;
                            break;
                        case 'info_7':
                            $k7 = $v;
                            $sumarr["sum_sb_7"] +=$v;
                            break;
                    }
                }
            }else{
                $sumarr["sum_sb_1"] +=0;
                $sumarr["sum_sb_3"] +=0;
                $sumarr["sum_sb_7"] +=0;
            }
            $str .= "<td>".$k1."</td><td>".$k3."</td><td>".$k7."</td>";
            $sumarr["sum_sbp_1"] = $sumarr["sum_sb_0"]?round($sumarr["sum_sb_1"]/$sumarr["sum_sb_0"]*100,2):0;
            $sumarr["sum_sb_1"] = $sumarr["sum_sb_1"]."[".$sumarr["sum_sbp_1"]."%]";
            $sumarr["sum_sbp_3"] = $sumarr["sum_sb_0"]?round($sumarr["sum_sb_3"]/$sumarr["sum_sb_0"]*100,2):0;
            $sumarr["sum_sb_3"] = $sumarr["sum_sb_3"]."[".$sumarr["sum_sbp_3"]."%]";
            $sumarr["sum_sbp_7"] = $sumarr["sum_sb_0"]?round($sumarr["sum_sb_7"]/$sumarr["sum_sb_0"]*100,2):0;
            $sumarr["sum_sb_7"] = $sumarr["sum_sb_7"]."[".$sumarr["sum_sbp_7"]."%]";
            //注册留存
            $ks0 = $ks1 = $ks3 = $ks7 = 0;
            if(!empty($v["lc"])){
                foreach($v["lc"] as $k=>$v){
                    switch ($k) {
                        case 'info_0':
                            $ks1 = $v;
                            $sumarr["sum_lc_0"] +=$v;
                            break;
                        case 'info_1':
                            $ks1 = $v;
                            $sumarr["sum_lc_1"] +=$v;
                            break;
                        case 'info_3':
                            $ks3 = $v;
                            $sumarr["sum_lc_3"] +=$v;
                            break;
                        case 'info_7':
                            $ks7 = $v;
                            $sumarr["sum_lc_7"] +=$v;
                            break;
                    }
                }
            }else{
                $sumarr["sum_lc_1"] +=0;
                $sumarr["sum_lc_3"] +=0;
                $sumarr["sum_lc_7"] +=0;
            }
            $sumarr["sum_lcp_1"] = $sumarr["sum_lc_0"]?round($sumarr["sum_lc_1"]/$sumarr["sum_lc_0"]*100,2):0;
            $sumarr["sum_lc_1"] = $sumarr["sum_lc_1"]."[".$sumarr["sum_lcp_1"]."%]";
            $sumarr["sum_lcp_3"] = $sumarr["sum_lc_0"]?round($sumarr["sum_lc_3"]/$sumarr["sum_lc_0"]*100,2):0;
            $sumarr["sum_lc_3"] = $sumarr["sum_lc_3"]."[".$sumarr["sum_lcp_3"]."%]";
            $sumarr["sum_lcp_7"] = $sumarr["sum_lc_0"]?round($sumarr["sum_lc_7"]/$sumarr["sum_lc_0"]*100,2):0;
            $sumarr["sum_lc_7"] = $sumarr["sum_lc_7"]."[".$sumarr["sum_lcp_7"]."%]";
            $str .= "<td>".$ks1."</td><td>".$ks3."</td><td>".$ks7."</td>";
            $str .="</tr>";
        }
        $counts = count($newarr);
        if ($counts>0) {
            $sumarr["sum_zhl"]=$sumarr["sum_ad_device"]?round($sumarr["sum_ad_device"]/$sumarr["sum_ad_activation"]*100,2):0;
            $sumarr["sum_ffl"]  = $sumarr["sum_ad_pay"]?round($sumarr["sum_ad_pay"]/$sumarr["sum_ad_dau"]*100,2):0;

            $str .= "<tr><td style='text-align:center;'>合计</td><td>".$sumarr["sum_ad_activation"]."</td><td>".$sumarr["sum_ad_device"]."</td><td>".$sumarr["sum_ad_news"]."</td><td>".$sumarr["sum_zhl"]."%</td><td>".$sumarr["sum_ad_money"]."</td><td>".$sumarr["sum_ad_pay"]."</td><td>".$sumarr["sum_ffl"]."%</td><td>".$sumarr["sum_ad_dau"]."</td><td>".$sumarr["sum_sb_1"]."</td><td>".$sumarr["sum_sb_3"]."</td><td>".$sumarr["sum_sb_7"]."</td><td>".$sumarr["sum_lc_1"]."</td><td>".$sumarr["sum_lc_3"]."</td><td>".$sumarr["sum_lc_7"]."</td></tr>";
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
            header("Content-Disposition:attachment;filename=".$date1."至".$date2."平台日报数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="20"><strong>平台日报数据</strong></td></tr><tr><td>日期</td><td>激活设备</td><td>新增设备</td><td>新增玩家</td><td>玩家转化率</td><td>收入金额</td><td>充值人数</td><td>付费率</td><td>活跃玩家</td><td>设备次日留存</td><td>设备3日留存</td><td>设备7日留存</td><td>新增玩家次日留存</td><td>新增玩家3日留存</td><td>新增玩家7日留存</td></tr>'.$str .'</table>');
            exit;
        }
        $this->assign("str",$str);
        $this->assign("starttime2",$date1);
        $this->assign("endtime2",$date2);
        $this->assign("report","reportday");
        $this->assign("gamestr",$this->get_select());
        $this->assign("data",get_json_encode($right_arr));
        $this->assign("meg","平台数据日报");
        $this->display('report.html');
    }

    public function reportweek(){
        if($this->isadmin!=1 && !$this->checkright("reportweek") ){
            showinfo("你没有权限执行该操作。","",2);
        }

        $str    =  "";
        $newarr = $sumarr =  $arr = $today = $yestoday = $yyestoday  = array();
        $export = get_param("export")?get_param("export"):0;
        $date1  =  get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $updatephp  = "update_all_wactive";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        //计算对应周期
        $d      = date("d",strtotime($date1));
        $m      = date("m",strtotime($date1));
        $w      = date("w",strtotime($date1));
        $y      = date("y",strtotime($date1));
        $date   = date("Ymd",mktime(0, 0 , 0,$m,$d-$w+1,$y));
        $sdate  = date("Ymd",mktime(0, 0 , 0,$m,$d-$w-28,$y));    //开始时间，前端推4周
        $edate  = date("Ymd",mktime(0, 0 , 0,$m,$d-$w+8,$y));     //结束时间

        $sql    = "select * from ".get_table("all_wactive")."where aw_sdate>".$sdate." and aw_edate<".$edate." order by aw_sdate desc";
        $query  = $this->db->query($sql);
        $nums   = 0;
        while($rows = $this->db->Fetcharray($query)){
            unset($rows["sysid"]);
            unset($rows["aw_uptime"]);
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
            if($j>($counts-2)){
                continue;
            }
            //求合计
            $sumarr["sum_aw_activation"] += $newarr[$i]["aw_activation"];
            $sumarr["sum_aw_wdevice"]    += $newarr[$i]["aw_wdevice"];
            $sumarr["sum_aw_wnews"]      += $newarr[$i]["aw_wnews"];
            $sumarr["sum_aw_wau"]        += $newarr[$i]["aw_wau"];
            $sumarr["sum_aw_wmoney"]     += $newarr[$i]["aw_wmoney"];
            $sumarr["sum_aw_wpay"]       += $newarr[$i]["aw_wpay"];
            $sumarr["sum_aw_wof"]        += $newarr[$i]["aw_wof"];
            $sumarr["sum_aw_wrf"]        += $newarr[$i]["aw_wrf"];
             //付费率
            $newarr[$i]["ffl"]    = $newarr[$i]["aw_wau"]?round($newarr[$i]["aw_wpay"]/$newarr[$i]["aw_wau"],4)*100:0;

            //ARPU
            $newarr[$i]["arpu"]   = $newarr[$i]["aw_wau"]?round($newarr[$i]["aw_wmoney"]/$newarr[$i]["aw_wau"],2):0;

            //ARPPU
            $newarr[$i]["arppu"]  = $newarr[$i]["aw_wpay"]?round($newarr[$i]["aw_wmoney"]/$newarr[$i]["aw_wpay"],2):0; 

            //周次留
            $newarr[$i]["remain"] = $newarr[$i]["aw_remain"]?ceil($newarr[$i]["aw_remain"]/7):0;
            $sumarr["sum_remain"]        += $newarr[$i]["remain"];
            //周流失率
            $newarr[$i]["loss"]   = $newarr[$i]["aw_wof"]?@round($newarr[$i]["aw_wof"]/$newarr[$i+1]["aw_wau"],2)*100:0;

            //展示环比数据
            if($j==1){
                //激活设备
                $reg_dev = $today["aw_activation"]-$yestoday["aw_activation"];
                $reg_dev = $reg_dev>0?"<font color='red'>+".$reg_dev."</font>":"<font color='green'>".$reg_dev."</font>";

                //新增设备
                $new_dev = $today["aw_wdevice"]-$yestoday["aw_wdevice"];
                $new_dev = $new_dev>0?"<font color='red'>+".$new_dev."</font>":"<font color='green'>".$new_dev."</font>"; 

                //新增玩家
                $new_play = $today["aw_wnews"]-$yestoday["aw_wnews"];
                $new_play = $new_play>0?"<font color='red'>+".$new_play."</font>":"<font color='green'>".$new_play."</font>"; 

                //活跃玩家
                $new_wau = $today["aw_wau"]-$yestoday["aw_wau"];
                $new_wau = $new_wau>0?"<font color='red'>+".$new_wau."</font>":"<font color='green'>".$new_wau."</font>"; 

                //总收入
                $new_mon = $today["aw_wmoney"]-$yestoday["aw_wmoney"];
                $new_mon = $new_mon>0?"<font color='red'>+".$new_mon."</font>":"<font color='green'>".$new_mon."</font>"; 

                //付费玩家
                $new_pay = $today["aw_wpay"]-$yestoday["aw_wpay"];
                $new_pay = $new_pay>0?"<font color='red'>+".$new_pay."</font>":"<font color='green'>".$new_pay."</font>"; 

                //付费率
                $to_ffl  = $today["aw_wau"]?round($today["aw_wpay"]/$today["aw_wau"],4)*100:0;
                $ye_ffl  = $yestoday["aw_wau"]?round($yestoday["aw_wpay"]/$yestoday["aw_wau"],4)*100:0;
                $new_ffl = $to_ffl-$ye_ffl;
                $new_ffl = $new_ffl>0?"<font color='red'>+".$new_ffl."</font>":"<font color='green'>".$new_ffl."</font>"; 

                //ARPU
                $to_arp  = $today["aw_wau"]?round($today["aw_wmoney"]/$today["aw_wau"],2):0;
                $ye_arp  = $yestoday["aw_wau"]?round($yestoday["aw_wmoney"]/$yestoday["aw_wau"],2):0;
                $new_arp = $to_arp-$ye_arp;
                $new_arp = $new_arp>0?"<font color='red'>+".$new_arp."</font>":"<font color='green'>".$new_arp."</font>";

                //ARPPU
                $to_arr  = $today["aw_wpay"]?round($today["aw_wmoney"]/$today["aw_wpay"],2):0;
                $ye_arr  = $yestoday["aw_wpay"]?round($yestoday["aw_wmoney"]/$yestoday["aw_wpay"],2):0;
                $new_arr = $to_arr-$ye_arr;
                $new_arr = $new_arr>0?"<font color='red'>+".$new_arr."</font>":"<font color='green'>".$new_arr."</font>";

                //周次留
                $to_rem  = $today["aw_remain"]?ceil($today["aw_remain"]/7):0;
                $ye_rem  = $yestoday["aw_remain"]?ceil($yestoday["aw_remain"]/7):0;
                $new_rem = $to_rem-$ye_rem;
                $new_rem = $new_rem>0?"<font color='red'>+".$new_rem."</font>":"<font color='green'>".$new_rem."</font>";

                //流失玩家数
                $new_wof = $today["aw_wof"]-$yestoday["aw_wof"];
                $new_wof = $new_wof>0?"<font color='red'>+".$new_wof."</font>":"<font color='green'>".$new_wof."</font>"; 

                //周回流玩家数
                $new_wrf = $today["aw_wrf"]-$yestoday["aw_wrf"];
                $new_wrf = $new_wrf>0?"<font color='red'>+".$new_wrf."</font>":"<font color='green'>".$new_wrf."</font>"; 

                //周流失
                $to_wss  = $today["aw_wof"]?round($today["aw_wof"]/$yestoday["aw_wau"],2)*100:0;
                $ye_wss  = $yestoday["aw_wof"]?@round($yestoday["aw_wof"]/$yyestoday["aw_wau"],2)*100:0;
                $new_wss = $to_wss-$ye_wss;
                $new_wss = $new_wss>0?"<font color='red'>+".$new_wss."</font>":"<font color='green'>".$new_wss."</font>";


                $str .= "<tr>";
                $str .= "<td style='text-align: center'>环比上周</td><td>".$reg_dev."</td><td>".$new_dev."</td><td>".$new_play."</td><td>".$new_wau."</td><td>".$new_mon."</td><td>".$new_pay."</td><td>".$new_ffl."%</td><td>".$new_arp."</td><td>".$new_arr."</td><td>".$new_rem."</td><td>".$new_wof."</td><td>".$new_wrf."</td><td>".$new_wss."%</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td>".date("Y-m-d",strtotime($newarr[$i]["aw_sdate"]))."~".date("Y-m-d",strtotime($newarr[$i]["aw_edate"]))."</td><td>".$newarr[$i]["aw_activation"]."</td><td>".$newarr[$i]["aw_wdevice"]."</td><td>".$newarr[$i]["aw_wnews"]."</td><td>".$newarr[$i]["aw_wau"]."</td><td>".$newarr[$i]["aw_wmoney"]."</td><td>".$newarr[$i]["aw_wpay"]."</td><td>".$newarr[$i]["ffl"]."%</td><td>".$newarr[$i]["arpu"]."</td><td>".$newarr[$i]["arppu"]."</td><td>".$newarr[$i]["remain"]."</td><td>".$newarr[$i]["aw_wof"]."</td><td>".$newarr[$i]["aw_wrf"]."</td><td>".$newarr[$i]["loss"]."%</td>";
            $str .= "</tr>";
            $j   +=1;
        }
        if ($counts>0) {
            //付费率
            $sumarr["sum_ffl"]    = $sumarr["sum_aw_wau"]?round($sumarr["sum_aw_wpay"]/$sumarr["sum_aw_wau"],4)*100:0;
            //ARPU
            $sumarr["sum_arpu"]   = $sumarr["sum_aw_wau"]?round($sumarr["sum_aw_wmoney"]/$sumarr["sum_aw_wau"],2):0;
            //ARPPU
            $sumarr["sum_arppu"]  = $sumarr["sum_aw_wpay"]?round($sumarr["sum_aw_wmoney"]/$sumarr["sum_aw_wpay"],2):0;

            $str .= "<tr><td style='text-align:center;'>合计</td><td>".$sumarr["sum_aw_activation"]."</td><td>".$sumarr["sum_aw_wdevice"]."</td><td>".$sumarr["sum_aw_wnews"]."</td><td>".$sumarr["sum_aw_wau"]."</td><td>".$sumarr["sum_aw_wmoney"]."</td><td>".$sumarr["sum_aw_wpay"]."</td><td>".$sumarr["sum_ffl"]."%</td><td>".$sumarr["sum_arpu"]."</td><td>".$sumarr["sum_arppu"]."</td><td>".$sumarr["sum_remain"]."</td><td>".$sumarr["sum_aw_wof"]."</td><td>".$sumarr["sum_aw_wrf"]."</td><td>/</td></tr>";
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
            header("Content-Disposition:attachment;filename=".$date1."至前4周平台周报数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="20"><strong>平台周报数据</strong></td></tr><tr><td>日期</td><td>周激活设备数</td><td>周新增设备数</td><td>周新增玩家数</td><td>周活跃玩家数</td><td>周总收入金额</td><td>周付费玩家数</td><td>周付费率</td><td>周ARPU</td><td>周ARPPU</td><td>周新增玩家次日留存</td><td>周流失玩家数</td><td>周回流玩家数</td><td>周流失率</td></tr>'.$str .'</table>');
            exit;
        }
        $this->assign("str",$str);
        $this->assign("starttime2",$date1);
        $this->assign("report","reportweek");
        $this->assign("meg","平台数据周报");
        $this->display('report.html');
    }

    public function reportmonth(){
        if($this->isadmin!=1 && !$this->checkright("reportmonth") ){
            showinfo("你没有权限执行该操作。","",2);
        }

        $str    =  "";
        $export = get_param("export")?get_param("export"):0;
        $newarr =  $arr = $today = $yestoday = $yyestoday  = array();
        $date1  =  get_param("starttime2")?str_replace("-","",get_param("starttime2")):date("Ym");
        $year   =  substr($date1,0,4);
        $month  =  substr($date1,4,2);
        $sdate  =  date("Ym",mktime(0,0,0,$month-4,1,$year));
        $edate  =  date("Ym",mktime(0,0,0,$month+1,1,$year));
        $updatephp  = "update_all_mactive";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $sql    = "select * from ".get_table("all_mactive")."where am_month>".$sdate." and am_month<".$edate." order by am_month desc";
        $query  = $this->db->query($sql);
        $nums   = 0;
        while($rows = $this->db->Fetcharray($query)){
            unset($rows["sysid"]);
            unset($rows["am_uptime"]);
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
            $sumarr["sum_am_activation"] += $newarr[$i]["am_activation"];
            $sumarr["sum_am_mdevice"]    += $newarr[$i]["am_mdevice"];
            $sumarr["sum_am_mnews"]      += $newarr[$i]["am_mnews"];
            $sumarr["sum_am_mau"]        += $newarr[$i]["am_mau"];
            $sumarr["sum_am_mmoney"]     += $newarr[$i]["am_mmoney"];
            $sumarr["sum_am_mpay"]       += $newarr[$i]["am_mpay"];
            $sumarr["sum_am_mof"]        += $newarr[$i]["am_mof"];
            $sumarr["sum_am_mrf"]        += $newarr[$i]["am_mrf"];
             //付费率
            $newarr[$i]["ffl"]    = $newarr[$i]["am_mau"]?round($newarr[$i]["am_mpay"]/$newarr[$i]["am_mau"],4)*100:0;

            //ARPU
            $newarr[$i]["arpu"]   = $newarr[$i]["am_mau"]?round($newarr[$i]["am_mmoney"]/$newarr[$i]["am_mau"],2):0;

            //ARPPU
            $newarr[$i]["arppu"]  = $newarr[$i]["am_mpay"]?round($newarr[$i]["am_mmoney"]/$newarr[$i]["am_mpay"],2):0; 

            //月次留
            $newarr[$i]["remain"] = $newarr[$i]["am_remain"]?ceil($newarr[$i]["am_remain"]/30):0;
            $sumarr["sum_remain"]        += $newarr[$i]["remain"];
            //月流失率
            $newarr[$i]["loss"]   = $newarr[$i]["am_mof"]?@round($newarr[$i]["am_mof"]/$newarr[$i+1]["am_mau"],2)*100:0;

            //展示环比数据
            if($j==1){
                //激活设备
                $reg_dev = $today["am_activation"]-$yestoday["am_activation"];
                $reg_dev = $reg_dev>0?"<font color='red'>+".$reg_dev."</font>":"<font color='green'>".$reg_dev."</font>";

                //新增设备
                $new_dev = $today["am_mdevice"]-$yestoday["am_mdevice"];
                $new_dev = $new_dev>0?"<font color='red'>+".$new_dev."</font>":"<font color='green'>".$new_dev."</font>"; 

                //新增玩家
                $new_play = $today["am_mnews"]-$yestoday["am_mnews"];
                $new_play = $new_play>0?"<font color='red'>+".$new_play."</font>":"<font color='green'>".$new_play."</font>"; 

                //活跃玩家
                $new_wau = $today["am_mau"]-$yestoday["am_mau"];
                $new_wau = $new_wau>0?"<font color='red'>+".$new_wau."</font>":"<font color='green'>".$new_wau."</font>"; 

                //总收入
                $new_mon = $today["am_mmoney"]-$yestoday["am_mmoney"];
                $new_mon = $new_mon>0?"<font color='red'>+".$new_mon."</font>":"<font color='green'>".$new_mon."</font>"; 

                //付费玩家
                $new_pay = $today["am_mpay"]-$yestoday["am_mpay"];
                $new_pay = $new_pay>0?"<font color='red'>+".$new_pay."</font>":"<font color='green'>".$new_pay."</font>"; 

                //付费率
                $to_ffl  = $today["am_mau"]?round($today["am_mpay"]/$today["am_mau"],4)*100:0;
                $ye_ffl  = $yestoday["am_mau"]?round($yestoday["am_mpay"]/$yestoday["am_mau"],4)*100:0;
                $new_ffl = $to_ffl-$ye_ffl;
                $new_ffl = $new_ffl>0?"<font color='red'>+".$new_ffl."</font>":"<font color='green'>".$new_ffl."</font>"; 

                //ARPU
                $to_arp  = $today["am_mau"]?round($today["am_mmoney"]/$today["am_mau"],2):0;
                $ye_arp  = $yestoday["am_mau"]?round($yestoday["am_mmoney"]/$yestoday["am_mau"],2):0;
                $new_arp = $to_arp-$ye_arp;
                $new_arp = $new_arp>0?"<font color='red'>+".$new_arp."</font>":"<font color='green'>".$new_arp."</font>";

                //ARRPU
                $to_arr  = $today["am_mpay"]?round($today["am_mmoney"]/$today["am_mpay"],2):0;
                $ye_arr  = $yestoday["am_mpay"]?round($yestoday["am_mmoney"]/$yestoday["am_mpay"],2):0;
                $new_arr = $to_arr-$ye_arr;
                $new_arr = $new_arr>0?"<font color='red'>+".$new_arr."</font>":"<font color='green'>".$new_arr."</font>";

                //月次留
                $to_rem  = $today["am_remain"]?ceil($today["am_remain"]/30):0;
                $ye_rem  = $yestoday["am_remain"]?ceil($yestoday["am_remain"]/30):0;
                $new_rem = $to_rem-$ye_rem;
                $new_rem = $new_rem>0?"<font color='red'>+".$new_rem."</font>":"<font color='green'>".$new_rem."</font>";

                //流失玩家数
                $new_wof = $today["am_mof"]-$yestoday["am_mof"];
                $new_wof = $new_wof>0?"<font color='red'>+".$new_wof."</font>":"<font color='green'>".$new_wof."</font>"; 

                //月回流玩家数
                $new_wrf = $today["am_mrf"]-$yestoday["am_mrf"];
                $new_wrf = $new_wrf>0?"<font color='red'>+".$new_wrf."</font>":"<font color='green'>".$new_wrf."</font>"; 

                //月流失
                $to_wss  = $today["am_mof"]?round($today["am_mof"]/$yestoday["am_mau"],2)*100:0;
                $ye_wss  = $yestoday["am_mof"]?@round($yestoday["am_mof"]/$yyestoday["am_mau"],2)*100:0;
                $new_wss = $to_wss-$ye_wss;
                $new_wss = $new_wss>0?"<font color='red'>+".$new_wss."</font>":"<font color='green'>".$new_wss."</font>";


                $str .= "<tr>";
                $str .= "<td style='text-align: center'>环比上月</td><td>".$reg_dev."</td><td>".$new_dev."</td><td>".$new_play."</td><td>".$new_wau."</td><td>".$new_mon."</td><td>".$new_pay."</td><td>".$new_ffl."%</td><td>".$new_arp."</td><td>".$new_arr."</td><td>".$new_rem."</td><td>".$new_wof."</td><td>".$new_wrf."</td><td>".$new_wss."%</td>";
                $str .= "</tr>";
            }

            $str .= "<tr>";
            $str .= "<td>".date("Y-m",strtotime($newarr[$i]["am_month"]."01"))."</td><td>".$newarr[$i]["am_activation"]."</td><td>".$newarr[$i]["am_mdevice"]."</td><td>".$newarr[$i]["am_mnews"]."</td><td>".$newarr[$i]["am_mau"]."</td><td>".$newarr[$i]["am_mmoney"]."</td><td>".$newarr[$i]["am_mpay"]."</td><td>".$newarr[$i]["ffl"]."%</td><td>".$newarr[$i]["arpu"]."</td><td>".$newarr[$i]["arppu"]."</td><td>".$newarr[$i]["remain"]."</td><td>".$newarr[$i]["am_mof"]."</td><td>".$newarr[$i]["am_mrf"]."</td><td>".$newarr[$i]["loss"]."%</td>";
            $str .= "</tr>";
            $j   +=1;
        }
        if ($counts>0) {
            //付费率
            $sumarr["sum_ffl"]    = $sumarr["sum_am_mau"]?round($sumarr["sum_am_mpay"]/$sumarr["sum_am_mau"],4)*100:0;
            //ARPU
            $sumarr["sum_arpu"]   = $sumarr["sum_am_mau"]?round($sumarr["sum_am_mmoney"]/$sumarr["sum_am_mau"],2):0;
            //ARPPU
            $sumarr["sum_arppu"]  = $sumarr["sum_am_mpay"]?round($sumarr["sum_am_mmoney"]/$sumarr["sum_am_mpay"],2):0;
            $str .= "<tr><td style='text-align:center;'>合计</td><td>".$sumarr["sum_am_activation"]."</td><td>".$sumarr["sum_am_mdevice"]."</td><td>".$sumarr["sum_am_mnews"]."</td><td>".$sumarr["sum_am_mau"]."</td><td>".$sumarr["sum_am_mmoney"]."</td><td>".$sumarr["sum_am_mpay"]."</td><td>".$sumarr["sum_ffl"]."%</td><td>".$sumarr["sum_arpu"]."</td><td>".$sumarr["sum_arppu"]."</td><td>".$sumarr["sum_remain"]."</td><td>".$sumarr["sum_am_mof"]."</td><td>".$sumarr["sum_am_mrf"]."</td><td>/</td></tr>";
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
            header("Content-Disposition:attachment;filename=".$date1."至前3月平台月报数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="20"><strong>平台月报数据</strong></td></tr><tr><td>日期</td><td>月激活设备数</td><td>月新增设备数</td><td>月新增玩家数</td><td>月活跃玩家数</td><td>月总收入金额</td><td>月付费玩家数</td><td>月付费率</td><td>月ARPU</td><td>月ARPPU</td><td>月新增玩家次日留存</td><td>月流失玩家数</td><td>月回流玩家数</td><td>月周流失率</td></tr>'.$str .'</table>');
            exit;
        }
        $this->assign("str",$str);
        $this->assign("starttime2",date("Y-m",strtotime($date1."01")));
        $this->assign("report","reportmonth");
        $this->assign("meg","平台数据月报");
        $this->display('report.html');
    }
}
?>
