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

class Time_new_play extends Controller {
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
    public function newplay(){
        if ($this->isadmin != 1 && !$this->checkright("time_new_play")) {
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
        $updatephp  = "update_game_hours";     //更新文件名称
        $this->assign("updatephp",$updatephp);

        $where              .= " where 1 ";
        $starttime2?$where  .= " and cgh_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and cgh_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and cgh_gameid=".get_param("gid"):'';
        $aid?$where         .= " and cgh_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and cgh_uwid=".get_param("adsons"):'';
        $type?$where        .= " and cgh_count_type=".get_param("type"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and cgh_uaid in (".$hehe["sysid"].")";
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
        $newarr = $pageinfo = $sumarr =  array();
        $arrt  = array('1' => '打开设备','2' => '激活设备','3' => '新增设备','4' => '新增注册','5' => '活跃用户','6' => '有效活跃','7' => '活跃付费玩家','8' => '活跃老玩家');
        //活跃统计表
        $sql = "SELECT * FROM " .get_table('game_hours_info'). " $where order by cgh_date Desc ";
        $sql_count = "SELECT count(*) c FROM " .get_table('game_hours_info'). " $where ";
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
            $url    .=  "?module=time_new_play&method=newplay&action=submit&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons&type=$type";
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
            unset($rows["cgh_uptime"]);
            $str .= "<tr>";
            $rows["cgh_date"] = date("Y-m-d",strtotime($rows["cgh_date"]));
            $rows["cgh_gameid"] = $garr[$rows["cgh_gameid"]]."[".$rows["cgh_gameid"]."]";
            $rows["cgh_uaid"] = $aarr[$rows["cgh_uaid"]];
            $rows["cgh_uwid"] = $aarr[$rows["cgh_uwid"]];
            $str .= "<td>".$rows["cgh_date"]."</td><td>".$rows["cgh_uaid"]."</td><td>".$rows["cgh_uwid"]."</td><td>".$rows["cgh_gameid"]."</td>";
            $k = '00';
            for ($i=0; $i < 24; $i++) { 
                if ($i < 10) {
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                $str .= "<td>".$rows["cgh_hour".$k]."</td>";
                $sumarr["cgh_hour".$k] += $rows["cgh_hour".$k];
            }
            $str .= "</tr>";
        }
        if ($totalrecord>0) {
            $str .= "<tr><td style='text-align:center;'>合计</td><td></td><td></td><td></td>";
            for ($i=0; $i < 24; $i++) {
                if ($i < 10) {
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                $str .= "<td>".$sumarr["cgh_hour".$k]."</td>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."每日分时".$arrt[$type]."数据".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="28"><strong>每日分时'.$arrt[$type].'数据</strong></td></tr><tr><td>日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>0时</td><td>1时</td><td>2时</td><td>3时</td><td>4时</td><td>5时</td><td>6时</td><td>7时</td><td>8时</td><td>9时</td><td>10时</td><td>11时</td><td>12时</td><td>13时</td><td>14时</td><td>15时</td><td>16时</td><td>17时</td><td>18时</td><td>19时</td><td>20时</td><td>21时</td><td>22时</td><td>23时</td></tr>'.$str .'</table>');
            exit;
        }
        //禁止首次加载数据
        if ($action != "submit") {
            $str    = '';
            $str_s  = '';
        }
        //处理图表展示
        if ($totalrecord>0) {
            $view  = $category = array();
            $sql2 = "SELECT * FROM " .get_table('game_hours_info'). " $where order by cgh_date asc ";
            $query  =  $this->db->query($sql2);
            for ($i=0; $i < 24; $i++) {
                $category[]      = $i.'时';
            }
            while($rows = $this->db->FetchArray($query)){
                $key      = date("Y-m-d",strtotime($rows["cgh_date"]));
                for ($i=0; $i < 24; $i++) {
                    if ($i < 10) {
                        $k = '0'.$i;
                    }else{
                        $k = $i;
                    }
                    $view[$key][$i] += $rows["cgh_hour".$k];
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
        $this->assign("type",$type);
        $this->assign("arrt",$arrt);
        $this->assign("active", "active");
        $this->assign('meg','您已进入用户每日分时统计中心！<br>--在对应的列输入搜索信息');
        $this->display("time_new_play.html");
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