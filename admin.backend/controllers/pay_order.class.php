<?php
#=============================================================================
#     FileName: ad_reg_pay.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 新增注册付费数据
#       Author: cooper
#        Email: 459576285@qq.com
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2016-02-25
#      History:
#=============================================================================

class Pay_order extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }
    /** 平台充值日志 **/
    public function orderlist(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("pay_order")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);
        $export = get_param("export")?get_param("export"):0;

        $where      = " where 1";
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $mtype      = get_param("mtype");
        $money      = get_param("money");
        $uid        = get_param("uid");
        $result     = get_param("result");
        $ptype      = get_param("ptype");
        $orderid    = get_param("orderid");

        //条件判定
        $starttime2?$where  .= " and ol_paydate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and ol_paydate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and ol_gid=".get_param("gid"):'';
        $aid?$where         .= " and ol_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and ol_uwid=".get_param("adsons"):'';
        $result?$where      .= " and ol_payresult=".get_param("result"):'';
        $ptype?$where       .= " and ol_payway=".get_param("ptype"):'';
        $uid?$where         .= " and ol_rname like '%".get_param("uid")."%'":'';
        $orderid?$where     .= " and ol_orderid like '%".get_param("orderid")."%'":'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and ol_uaid in (".$hehe["sysid"].")";
        }

        if(!empty($money) && $money!=0){
            if(!empty($mtype)){
                $fh = $mtype;
            }else{
                $fh = "=";
            }
            $where .= " and ol_money".$fh.$money;
        }
        
        $this->assign("gamestr",$this->get_select($gid));

        //广告渠道选项
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
        //分页
        $sql_count    = "SELECT count(*) c FROM " .get_table('orderform_log'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));
        $totalrecord = $totalrecord["c"];

        $pgs  =  $ends  = 0;
        $sql =" select * from ".get_table('orderform_log')." $where order by ol_paytime desc ";

        //导出
        if ($export != 1) {
            //分页
            $pagesize = 20;
            $page = empty($_GET['page'])?1:intval($_GET['page']);
            if($page<1) $page=1;
            $start = ($page-1)*$pagesize;
            $ends = ($start+$pagesize);
            $url    = $_SERVER['PHP_SELF'];
            $sql .=" LIMIT $start, $pagesize";
            $url    .=  "?module=pay_order&method=orderlist&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons&mtype=$mtype&money=$money&uid=$uid&result=$result&ptype=$ptype&orderid=$orderid";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }

        $sql2    = "select sysid,ui_name from ".get_table("user_info");
        $query2  = $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query2)){
            $userinfo[$rows["sysid"]]  = $rows["ui_name"];
        } 

        $str    =  "";
        $query  = $this->db->Query($sql);
        $payway = array('1'=>'微信','2'=>'支付宝','3'=>'QQ钱包');
        $paytype = array('1'=>'网页','2'=>'SDK');
        $payresult = array('失败','1'=>'成功','2'=>'失败');
        $giveresult = array('失败','1'=>'成功','2'=>'失败');
        while ($rows = $this->db->FetchArray($query)) {
                $str .= "<tr>";
                $rows["ol_paytime"] = date("Y-m-d H:i:s",$rows["ol_paytime"]);
                $rows["ol_gid"]     = $garr[$rows["ol_gid"]]."[".$rows["ol_gid"]."]";
                $rows["ol_uaid"]    = $aarr[$rows["ol_uaid"]]."[".$rows["ol_uaid"]."]";
                $rows["ol_uwid"]    = $aarr[$rows["ol_uwid"]]."[".$rows["ol_uwid"]."]"; 

                $rows["ol_uname"]   = $userinfo[$rows["ol_uid"]];
                $str .= "<td>".$rows["ol_paytime"]."</td>"."<td>".$rows["ol_uaid"]."</td>"."<td>".$rows["ol_uwid"]."</td>"."<td>".$rows["ol_gid"]."</td>"."<td>".$rows["ol_sid"]."</td>"
                // ."<td>".$rows["ol_uid"]
                ."</td>"."<td>".$rows["ol_uname"]."</td>"."<td>".$rows["ol_rname"]."</td>"."<td>".$payway[$rows["ol_payway"]]."</td>"."<td style='vnd.ms-excel.numberformat:@'>".$rows["ol_ortherid"]."</td>"."<td style='vnd.ms-excel.numberformat:@'>".$rows["ol_orderid"]."</td>"."<td style='vnd.ms-excel.numberformat:@'>".$rows["ol_transorder"]."</td>"."<td>".$rows["ol_money"]."</td><td>".$payresult[$rows["ol_payresult"]]."</td><td>".$giveresult[$rows["ol_giveresult"]]."</td>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."平台充值日志".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="28"><strong>平台充值日志</strong></td></tr><tr><td>充值时间</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>游戏服ID</td><td>账号</td><td>角色名</td><td>支付方式</td><td>第三方订单号</td><td>平台订单号</td><td>渠道订单号</td><td>充值金额</td><td>支付结果</td><td>发放结果</td></tr>'.$str .'</table>');
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
        $this->assign("mtype",$mtype);
        $this->assign("ptype",$ptype);
        $this->assign("result",$result);
        $this->assign("uid",$uid);
        $this->assign("money",$money);
        $this->assign("orderid",$orderid);
        $this->assign("list","list");
        $this->assign('meg','您已进入平台充值日志列表！<br>--在对应的列输入搜索信息');
        $this->display("pay_order.html");
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

