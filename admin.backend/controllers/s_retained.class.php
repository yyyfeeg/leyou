<?php

#=============================================================================
#     FileName: s_retained.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: jericho
#        Email: jericho
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-07-13
#      History:
#=============================================================================

class S_retained extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    
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
        if ($export != 1) {
            //分页
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $url = $_SERVER['PHP_SELF'];
            $sql .=" LIMIT $start, $pagesize";
            $url    .=  "?module=s_retained&method=retained&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
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
            $arr2 = array('cgr_regtime'=>'空','info0'=>0,'info1'=>0,'info3'=>0,'info7'=>0,'info14'=>0,'info30'=>0);
            //php数据处理
            $arr = explode(',',$rows['info']);
            foreach($arr as $k=>$v){
                $rem_diff = explode('||',$v);
                //计算百分比所需基数
                if($rem_diff[1] == 0){
                    $js = $rem_diff[0];
                }
                if(in_array($rem_diff[1],$shows)){
                    $per = $js?round($rem_diff[0]/$js,4)*100:0;
                    $arr2['info'.$rem_diff[1]] = ($rem_diff[1]==0)?$rem_diff[0]:$rem_diff[0]."[$per%]";
                }
            }
            $rows["cgr_regdate"] = date('Y-m-d',strtotime($rows["cgr_regdate"]));
            $rows["cgr_uaid"] = $aarr[$rows["cgr_uaid"]].$rows["cgr_uaid"];
            $rows["cgr_uwid"] = $aarr[$rows["cgr_uwid"]].$rows["cgr_uwid"];
            $rows["cgr_gameid"] = $garr[$rows["cgr_gameid"]]."[".$rows["cgr_gameid"]."]";
            $str .= "<tr>";
            $str .= "<td>".$rows["cgr_regdate"]."</td><td>".$rows["cgr_uaid"]."</td><td>".$rows["cgr_uwid"]."</td><td>".$rows["cgr_gameid"]."</td><td>".$arr2["info0"]."</td><td>".$arr2["info1"]."</td><td>".$arr2["info3"]."</td><td>".$arr2["info7"]."</td><td>".$arr2["info14"]."</td><td>".$arr2["info30"]."</td>";
            $str .= "</tr>";
            unset($arr2);
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

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('meg','您已进入用户留存统计列表中心！<br>--在对应的列输入搜索信息');
        $this->display("retained.html");
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