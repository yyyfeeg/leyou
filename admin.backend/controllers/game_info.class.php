<?php

#=============================================================================
#     FileName: game_info.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: jericho
#        Email: jericho
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-07-13
#      History:
#=============================================================================

class Game_info extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    
    public function gameinfo(){
        if ($this->isadmin != 1 && !$this->checkright("game_info")) {
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
        $export     = get_param("export")?get_param("export"):0;
        $action     = get_param("action");
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
        $sql    = "SELECT ga_date, ga_gameid, ga_aid, ga_wid, ga_activation, ga_device, ga_news, ga_dau, ga_pay, ga_money FROM " .get_table('game_active'). " $where order by ga_date desc,ga_gameid,ga_aid ";
        $sql_count    = "SELECT count(*) c FROM " .get_table('game_active'). " $where  ";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));
        $totalrecord = $totalrecord["c"];
        $pgs  =  $ends  = 0;
        //导出
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
            $url    = $_SERVER['PHP_SELF'];
            $url    .=  "?module=game_info&method=gameinfo&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
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
        $str    = "";
        $sumarr = array();
        while ($rows = $this->db->FetchArray($query)) {
            //求合计
            $sumarr["sum_ga_activation"] += $rows["ga_activation"];
            $sumarr["sum_ga_device"]     += $rows["ga_device"];
            $sumarr["sum_ga_news"]       += $rows["ga_news"];
            $sumarr["sum_ga_dau"]        += $rows["ga_dau"];
            $sumarr["sum_ga_pay"]        += $rows["ga_pay"];
            $sumarr["sum_ga_money"]      += $rows["ga_money"];
            if($pgs>=$start && $pgs<$ends){
                $str .= "<tr>";
                $rows["ga_date"] = date("Y-m-d",strtotime($rows["ga_date"]));
                $rows["ga_gameid"] = $garr[$rows["ga_gameid"]]."[".$rows["ga_gameid"]."]";
                $rows["ga_aid"] = $aarr[$rows["ga_aid"]];
                $rows["ga_wid"] = $aarr[$rows["ga_wid"]];
                $playchange = $rows["ga_activation"]?round($rows["ga_device"]/$rows["ga_activation"]*100,2).'%':'0%';//玩家转换率
                $oldplay = $rows["ga_dau"] - $rows["ga_news"];//老玩家
                $arpu = $rows["ga_dau"]?round($rows["ga_money"]/$rows["ga_dau"],2):0;
                $arppu = $rows["ga_pay"]?round($rows["ga_money"]/$rows["ga_pay"],2):0;
                $pay = $rows["ga_dau"]?round($rows["ga_pay"]/$rows["ga_dau"]*100,2).'%':'0%';//付费率
                $str .= "<td>".$rows["ga_date"]."</td><td>".$rows["ga_aid"]."</td><td>".$rows["ga_wid"]."</td><td>".$rows["ga_gameid"]."</td><td>".$rows["ga_activation"]."</td><td>".$rows["ga_news"]."</td><td>".$playchange."</td><td>".$rows["ga_dau"]."</td><td>".$oldplay."</td><td>".$rows["ga_pay"]."</td><td>".$rows["ga_money"]."</td><td>".$arpu."</td><td>".$arppu."</td><td>".$pay."</td>";
                $str .= "</tr>";
            }
            $pgs+=1;
        }
        if ($totalrecord>0) {
            $sumarr["sum_zhl"]=$sumarr["sum_ga_device"]?round($sumarr["sum_ga_device"]/$sumarr["sum_ga_activation"]*100,2):0;
            $sumarr["sum_ffl"]  = $sumarr["sum_ga_pay"]?round($sumarr["sum_ga_pay"]/$sumarr["sum_ga_dau"]*100,2):0;
            $sumarr["sum_arpu"]  = $sumarr["sum_ga_dau"]?round($sumarr["sum_ga_money"]/$sumarr["sum_ga_dau"]*100,2):0;
            $sumarr["sum_arppu"]  = $sumarr["sum_ga_pay"]?round($sumarr["sum_ga_money"]/$sumarr["sum_ga_pay"]*100,2):0;
            $sumarr["sum_old"] = $sumarr["sum_ga_dau"] - $sumarr["sum_ga_news"];
            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td><td>".$sumarr["sum_ga_activation"]."</td><td>".$sumarr["sum_ga_news"]."</td><td>".$sumarr["sum_zhl"]."%</td><td>".$sumarr["sum_ga_dau"]."</td><td>".$sumarr["sum_old"]."</td><td>".$sumarr["sum_ga_pay"]."</td><td>".$sumarr["sum_ga_money"]."</td><td>".$sumarr["sum_arpu"]."</td><td>".$sumarr["sum_arppu"]."</td><td>".$sumarr["sum_ffl"]."%</td></tr>";
        }
        $this->db->FreeResult($query);
        unset($rows);
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."游戏明细数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="28"><strong>游戏明细数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>激活设备</td><td>新增玩家</td><td>玩家转换率</td><td>登录玩家</td><td>老玩家</td><td>付费玩家</td><td>收入金额</td><td>ARPU</td><td>ARPPU</td><td>付费率</td></tr>'.$str .'</table>');
            exit;
        }
        //处理图表展示
        $view   = $category = array();
        $sql2   =  " SELECT ga_date, sum(ga_activation) ga_activation, sum(ga_news) ga_news, sum(ga_dau) ga_dau, sum(ga_pay) ga_pay, sum(ga_money) ga_money FROM " .get_table('game_active'). " $where group by ga_date order by ga_date asc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $category[]      = date("Y-m-d",strtotime($rows["ga_date"]));
            $view['激活设备'][]    = $rows["ga_activation"];
            $view['新增玩家'][]     = $rows["ga_news"];
            $oldplay            = $rows["ga_dau"] - $rows["ga_news"];//老玩家
            $view['登录玩家'][]  = $rows["ga_dau"];
            $view["老玩家"][]   = $oldplay;
            $view['付费玩家'][]   = $rows["ga_pay"];
            $view['收入金额'][] = $rows["ga_money"];
        }
        $this->assign("category",json_encode($category));
        $this->assign("view",json_encode($view));
        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign('uptime', $uptime);//更新时间
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入游戏明细列表中心！<br>--在对应的列输入搜索信息');
        $this->display("game_info.html");
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