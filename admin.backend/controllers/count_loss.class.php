<?php

#=============================================================================
#     FileName: count_loss.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 活跃类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-09-28
#      History:
#=============================================================================

class Count_loss extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    /*新增列表*/
    public function lossinfo(){
        if ($this->isadmin != 1 && !$this->checkright("count_loss")) {
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
        $updatephp  = "update_game_loss";     //更新文件名称
        $this->assign("updatephp",$updatephp);
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
        $starttime2?$where  .= " and gl_logdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and gl_logdate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and gl_gameid=".get_param("gid"):'';
        $aid?$where         .= " and gl_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and gl_uwid=".get_param("adsons"):'';
        $type?$where        .= " and gl_type=".get_param("type"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and gl_uaid in (".$hehe["sysid"].")";
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
        $array = $sumarr = $pageinfo = $arr2 = array();
        $pgs  =  $ends  = $sum  =  $start  = 0;
        $arrt  = array('1' => '新增玩家','2' => '活跃玩家','3' => '付费玩家','4' => '非付费玩家');
        //流失统计表
        $sql = "SELECT Group_concat(CONCAT_WS('||',gl_remain,gl_diff) order by gl_diff asc) as info,gl_uaid,gl_uwid,gl_gameid,gl_logdate FROM " .get_table('game_loss'). " $where group by gl_gameid,gl_uaid,gl_uwid,gl_logdate order by gl_logdate desc,gl_gameid ";
        $totalrecord = $this->db->NumRows($this->db->Query($sql));
        //点击查询按钮
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
            $url    .=  "?module=count_loss&method=lossinfo&action=submit&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons&type=$type";
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
        $shows = array(0,1,3,7,14,30);      //显示对应数据展示信息
        while ($rows = $this->db->FetchArray($query)) {
            unset($rows["sysid"]);
            unset($rows["gl_uptime"]);
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
                $rows["gl_logdate"] = date("Y-m-d",strtotime($rows["gl_logdate"]));
                $rows["gl_gameid"] = $garr[$rows["gl_gameid"]]."[".$rows["gl_gameid"]."]";
                $rows["gl_uaid"] = $aarr[$rows["gl_uaid"]];
                $rows["gl_uwid"] = $aarr[$rows["gl_uwid"]];
                $str .= "<tr>";
                $str .= "<td>".$rows["gl_logdate"]."</td><td>".$rows["gl_uaid"]."</td><td>".$rows["gl_uwid"]."</td><td>".$rows["gl_gameid"]."</td><td>".$arr2["info0"]."</td><td>".$arr2["info1"]."</td><td>".$arr2["info3"]."</td><td>".$arr2["info7"]."</td><td>".$arr2["info14"]."</td><td>".$arr2["info30"]."</td>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2.$arrt[$type]."流失统计数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="28"><strong>'.$arrt[$type].'流失统计数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>'.$arrt[$type].'</td><td>次日流失</td><td>3日流失</td><td>7日流失</td><td>14日流失</td><td>30日流失</td></tr>'.$str .'</table>');
            exit;
        }
        //禁止首次加载数据
        if ($action != "submit") {
            $str    = '';
            $str_s  = '';
        }
        //处理图表展示
        $view  = $category = $arr3 = array();
        $sql2 = "select gl_diff,sum(gl_remain) gl_remain,gl_logdate from ".get_table("game_loss")." $where group by gl_logdate,gl_diff order by gl_logdate asc";
        $query  =  $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query)){
            $keys = date("Y-m-d",strtotime($rows["gl_logdate"]));
            //当天注册用户数
            if($rows["gl_diff"]==0){
                $arr3[$keys]["info0"] = $rows["gl_remain"];
            }
            if(in_array($rows["gl_diff"],$shows)){
                $per = $arr3[$keys]["info0"]?round($rows["gl_remain"]/$arr3[$keys]["info0"],4)*100:0;
                $arr3[$keys]["info".$rows["gl_diff"]] = $rows["gl_remain"];
                $arr3[$keys]["per".$rows["gl_diff"]] = $per;
            }
        }
        foreach ($arr3 as $key => $value) {
            $category[]      = $key;
            $view['次日流失'][]    = $value["per1"];
            $view['3日流失'][]  = $value["per3"];
            $view["7日流失"][]   = $value["per7"];
            $view['14日流失'][]     = $value["per14"];
            $view['30日流失'][]     = $value["per30"];
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
        $this->assign('meg','您已进入流失统计中心！<br>--在对应的列输入搜索信息');
        $this->display("count_loss.html");
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