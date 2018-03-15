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

class New_play extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    
    public function newplay(){
        if ($this->isadmin != 1 && !$this->checkright("new_play")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where     = " where 1";
        $pageinfo  = array();
        $export    = get_param("export")?get_param("export"):0;
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            //echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            showinfo("结束日期不能小于开始日期!","",3);
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
        if ($export != 1) {
            //分页
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $url = $_SERVER['PHP_SELF'];
            $sql .=" LIMIT $start, $pagesize";
            $url    .=  "?module=new_play&method=newplay&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
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
            $str .= "<tr>";
            $rows["ga_date"] = date("Y-m-d",strtotime($rows["ga_date"]));
            $rows["ga_gameid"] = $garr[$rows["ga_gameid"]]."[".$rows["ga_gameid"]."]";
            $rows["ga_aid"] = $aarr[$rows["ga_aid"]];
            $rows["ga_wid"] = $aarr[$rows["ga_wid"]];
            $playchange = $rows["ga_activation"]?round($rows["ga_device"]/$rows["ga_activation"]*100,2).'%':'';//玩家转换率
            $str .= "<td>".$rows["ga_date"]."</td><td>".$rows["ga_aid"]."</td><td>".$rows["ga_wid"]."</td><td>".$rows["ga_gameid"]."</td><td>".$rows["ga_opens"]."</td><td>".$rows["ga_activation"]."</td><td>".$rows["ga_device"]."</td><td>".$rows["ga_news"]."</td><td>".$playchange."</td>";
            $str .= "</tr>";
        }
        //点击导出
        if($export==1){
            // var_dump($totalrecord);
            if (count($totalrecord)>0) {
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
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="9"><strong>新增玩家</strong></td></tr><tr><td>统计日期</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>打开设备</td><td>激活设备</td><td>新增设备</td><td>新增注册</td><td>玩家转换率</td></tr>'.$str .'</table>');
            exit;
        }

        $sql_sum    = "SELECT sum(ga_activation) s_act, sum(ga_device) s_dev, sum(ga_news) s_new,avg(ga_activation) a_act, avg(ga_device) a_dev, avg(ga_news) a_new FROM " .get_table('game_active'). " $where LIMIT 1";
        $query  = $this->db->Query($sql_sum);
        while ($rows = $this->db->FetchArray($query)) {
            $s_act = !empty($rows["s_act"])?round($rows["s_act"]):0;
            $s_dev = !empty($rows["s_dev"])?round($rows["s_dev"]):0;
            $s_new = !empty($rows["s_new"])?round($rows["s_new"]):0;
            $a_act = !empty($rows["a_act"])?round($rows["a_act"]):0;
            $a_dev = !empty($rows["a_dev"])?round($rows["a_dev"]):0;
            $a_new = !empty($rows["a_new"])?round($rows["a_new"]):0;
        }

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign('uptime', $uptime);//更新时间
        $this->assign("str",$str);
        $this->assign("s_act",$s_act);
        $this->assign("s_dev",$s_dev);
        $this->assign("s_new",$s_new);
        $this->assign("a_act",$a_act);
        $this->assign("a_dev",$a_dev);
        $this->assign("a_new",$a_new);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入用户统计列表中心！<br>--在对应的列输入搜索信息');
        $this->display("new_play.html");
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