<?php

#=============================================================================
#     FileName: member.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 用户管理日志类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.2
#   LastChange: 2016-02-16
#      History: 0.0.1
#=============================================================================

class Ad_reg extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }

     /*第三方注册日志列表*/
    public function gamelogin_list(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("gamelogin_list")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        $garr       = get_game($this->db);
        $aarr       = ad_get($this->db);
        $sarr       = get_servers($this->db);
        $where      = " where 1";
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $uid        = get_param("uid");
        $uname      = get_param("uname");
        $dnumber    = get_param("dnumber");

        //条件判定
        $starttime2?$where  .= " and dg_logdate>".date('Ymd',strtotime($starttime2)-1)." and dg_logdate<".date("Ymd",strtotime($starttime2)+86400):'';
        $gid?$where         .= " and dg_gid=".get_param("gid"):'';
        $aid?$where         .= " and dg_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dg_uwid=".get_param("adsons"):'';
        $uname?$where       .= " and dg_name like '%".get_param("uname")."%'":'';
        $uid?$where         .= " and dg_uid like '%".get_param("uid")."%'":'';
        $dnumber?$where     .= " and dg_dnumber like '%".get_param("dnumber")."%'":'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and dg_uaid in (".$hehe["sysid"].")";
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
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT count(*) c FROM " .get_table('gamelogin_log'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from ".get_table('gamelogin_log')." $where order by dg_logtime desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);
        // exit($sql);
        $str    =  "";
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["dg_time"]    = date("Y-m-d H:i:s",$rows["dg_logtime"]);
            $rows["dg_gid"]     = $garr[$rows["dg_gid"]]."[".$rows["dg_gid"]."]";
            $rows["dg_uaid"]    = $aarr[$rows["dg_uaid"]]."[".$rows["dg_uaid"]."]";
            $rows["dg_uwid"]    = $aarr[$rows["dg_uwid"]]."[".$rows["dg_uwid"]."]";
            $str .= "<td>".$rows["dg_time"]."</td><td>".$rows["dg_uaid"]."</td><td>".$rows["dg_uwid"]."</td><td>".$rows["dg_gid"]."</td><td>".$rows["dg_name"]."</td><td>".$rows["dg_uid"]."</td><td>".$rows["dg_rolename"]."</td><td>".$rows["dg_dnumber"]."</td><td>".$rows["dg_ip"]."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=ad_reg&method=gamelogin_list&starttime2=$starttime2&gid=$gid&aid=$aid&adsons=$adsons&uid=$uid&uname=$uname&dnumber=$dnumber&tid=$tid";
        $multi = multi($totalrecord["c"], $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord["c"],
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord["c"]/$pagesize),
            'multi' => $multi
        );

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("uname",$uname);
        $this->assign("uid",$uid);
        $this->assign("list","gamelogin");
        $this->assign("tid",$tid);
        $this->assign("dnumber",$dnumber);
        $this->assign('meg','您已进入游戏登录列表！');
        $this->display("gamelogin_list.html");
    }

    /*第三方注册日志列表*/
    public function reglist(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("ad_reg")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $garr       = get_game($this->db);
        $aarr       = ad_get($this->db);
        $sarr       = get_servers($this->db);
        $where      = " where 1";
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $uid        = get_param("uid");
        $uname      = get_param("uname");
        $dnumber    = get_param("dnumber");

        //条件判定
        $starttime2?$where  .= " and dl_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and dl_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and dl_gid=".get_param("gid"):'';
        $aid?$where         .= " and dl_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dl_uwid=".get_param("adsons"):'';
        $uname?$where       .= " and dl_name like '%".get_param("uname")."%'":'';
        $uid?$where         .= " and dl_uid like '%".get_param("uid")."%'":'';
        $dnumber?$where     .= " and dl_dnumber like '%".get_param("dnumber")."%'":'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and dl_uaid in (".$hehe["sysid"].")";
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
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT count(*) c FROM " .get_table('gamereg_log'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from ".get_table('gamereg_log')." $where order by dl_time desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);

        $str    =  "";
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["dl_time"]    = date("Y-m-d H:i:s",$rows["dl_time"]);
            $rows["dl_gid"]     = $garr[$rows["dl_gid"]]."[".$rows["dl_gid"]."]";
            $rows["dl_uaid"]    = $aarr[$rows["dl_uaid"]]."[".$rows["dl_uaid"]."]";
            $rows["dl_uwid"]    = $aarr[$rows["dl_uwid"]]."[".$rows["dl_uwid"]."]";
            $str .= "<td>".$rows["dl_time"]."</td><td>".$rows["dl_uaid"]."</td><td>".$rows["dl_uwid"]."</td><td>".$rows["dl_gid"]."</td><td>".$rows["dl_name"]."</td><td>".$rows["dl_uid"]."</td><td>".$rows["dl_dnumber"]."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=ad_reg&method=reglist&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons&uid=$uid&uname=$uname&dnumber=$dnumber&tid=$tid";
        $multi = multi($totalrecord["c"], $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord["c"],
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord["c"]/$pagesize),
            'multi' => $multi
        );

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("uname",$uname);
        $this->assign("uid",$uid);
        $this->assign("list","list");
        $this->assign("tid",$tid);
        $this->assign("dnumber",$dnumber);
        $this->assign('meg','您已进入第三方渠道注册列表！<br>--在对应的列输入搜索信息');
        $this->display("ad_reg.html");
    }

    /** 游戏注册日志 * */
    public function gamelist() {
        if ($this->isadmin != 1 && !$this->checkright("game_reg")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $this->assign("list", "list");
        $this->assign('meg','您已进入游戏注册日志中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("game_reg.html");
    }

    /*游戏注册数据*/
    public function game_list(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("game_reg")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        //设置字段
        $rols_arr = array('sysid','dg_uid','dg_name','dg_uaid','dg_uwid','dg_firstlogtime','dg_regtime','dg_gid','dg_mac','dg_idfa');

        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $warr   = ad_get($this->db,2);
        //获得参数
        $where          = " where 1";
        $sEcho          = get_param('sEcho');// DataTables 用来生成的信息
        $star           = get_param('iDisplayStart')?get_param('iDisplayStart'):0;//显示的起始索引
        $lenth          = get_param('iDisplayLength')?get_param('iDisplayLength'):10;//显示的行数
        $cols           = get_param('iSortCol_0');//被排序的列
        $asc            = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"
        
        //循环查询条件
        for($i=0;$i<9;$i++){
            $where .=  get_param('sSearch_'.$i)?" and {$rols_arr[$i]} = '".get_param('sSearch_'.$i)."'":'';
        }

        //开始查询
        $sql = "SELECT * FROM dcenter_count.`count_gamelogin_log` ".$where. " group by dg_name,dg_gid limit {$star},{$lenth}";
        //var_dump( $sql);die;
        $query = $this->db->Query($sql);

        while ($rows = $this->db->FetchArray($query)) {
            $right_arr['aaData'][]=array(
                $rows['sysid'],
                $rows['dg_uid'],
                $rows['dg_name'],
                $aarr[$rows['dg_uaid']],
                $warr[$rows['dg_uwid']]."[".$rows['dg_uwid']."]",
                $rows['dg_firstlogtime'],
                $rows['dg_regdate'],
                $garr[$rows['dg_gid']]."[".$rows['dg_gid']."]",
                $rows['dl_date'],
                $rows['dl_mac']
            );
        }
        if(empty($right_arr)){
            $right_arr['aaData'] = array();
        }

        //得到所有条数
        //$total = count($right_arr);
        $sql   = "select count(*) as total from  dcenter_count.`count_gamelogin_log`".$where;
        $query = $this->db->Query($sql);
        $total = $this->db->getOne($query);

        $right_arr['sEcho'] = $sEcho;
        $right_arr['iTotalDisplayRecords'] = $total['total'];
        $right_arr['iTotalRecords'] = $total['total'];
        echo json_encode($right_arr);
    }


    /** 成功充值数据 **/
    public function paylist(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("pay_list")) {
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
        $uname      = get_param("uname");
        $orderid    = get_param("orderid");

        //条件判定
        $starttime2?$where  .= " and dp_paydate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and dp_paydate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dp_uwid=".get_param("adsons"):'';
        $uname?$where       .= " and dp_rname like '%".get_param("uname")."%'":'';
        $uid?$where         .= " and dp_uid like '%".get_param("uid")."%'":'';
        $orderid?$where     .= " and dp_orderid like '%".get_param("orderid")."%'":'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and dp_uaid in (".$hehe["sysid"].")";
        }

        if(!empty($money) && $money!=0){
            if(!empty($mtype)){
                $fh = $mtype;
            }else{
                $fh = "=";
            }
            $where .= " and dp_money".$fh.$money;
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

        $sql_count    = "SELECT count(*) c FROM " .get_table('paylog_log'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));
        $totalrecord = $totalrecord["c"];

        $sql =" select * from ".get_table('paylog_log')." $where order by dp_paytime desc ";

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
            $url    .=  "?module=ad_reg&method=paylist&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons&mtype=$mtype&money=$money&uid=$uid&uname=$uname&orderid=$orderid";
            $multi = multi($totalrecord, $pagesize, $page, $url,2);
            $pageinfo = array(
                'page' => $page,
                'totalrecord' => $totalrecord,
                'pagesize' => $pagesize,
                'totalpage' => ceil($totalrecord/$pagesize),
                'multi' => $multi
            );
        }

        $payway = array('1'=>'微信','2'=>'支付宝','3'=>'QQ钱包');
        $sql2    = "select ol_orderid,ol_gameorder,ol_gid,ol_payway from ".get_table("orderform_log");
        $query2  = $this->db->query($sql2);
        while($rows = $this->db->FetchArray($query2)){
            //洪荒记订单号为商户传入信息
            if ($rows["ol_gid"] == '10002') {
                $payinfo[substr($rows["ol_gameorder"],strpos($rows["ol_gameorder"],'|')+1)]  = $rows["ol_payway"];
            }else{
                $payinfo[$rows["ol_orderid"]]  = $rows["ol_payway"];
            }
        }

        $sql3    = "select sysid,ui_name from ".get_table("user_info");
        $query3  = $this->db->query($sql3);
        while($rows = $this->db->FetchArray($query3)){
            $userinfo[$rows["sysid"]]  = $rows["ui_name"];
        } 

        $str    =  "";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["dp_paytime"] = date("Y-m-d H:i:s",$rows["dp_paytime"]);
            $rows["dp_gid"]     = $garr[$rows["dp_gid"]]."[".$rows["dp_gid"]."]";
            $rows["dp_uaid"]    = $aarr[$rows["dp_uaid"]]."[".$rows["dp_uaid"]."]";
            $rows["dp_uwid"]    = $aarr[$rows["dp_uwid"]]."[".$rows["dp_uwid"]."]";
            $rows["dp_payway"]  = $payway[$payinfo[$rows["dp_orderid"]]];
            $rows["dp_uname"]   = $userinfo[$rows["dp_uid"]];

            $str .= "<td>".$rows["dp_paytime"]."</td>"."<td>".$rows["dp_uaid"]."</td>"."<td>".$rows["dp_uwid"]."</td>"."<td>".$rows["dp_gid"]."</td>"."<td>".$rows["dp_sid"]."</td>"."<td>".$rows["dp_rname"]."</td>"."<td>".$rows["dp_uname"]."</td>"."<td>".$rows["dp_payway"]."</td>"."<td style='vnd.ms-excel.numberformat:@'>".$rows["dp_orderid"]."</td>"."<td>".$rows["dp_money"]."</td>"."<td>".$rows["dp_nums"]."</td>";
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
            header("Content-Disposition:attachment;filename=".$starttime2."至".$endtime2."成功充值订单".".xls");
            echo('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table width="100%" border="1"> <tr><td colspan="28"><strong>成功充值订单</strong></td></tr><tr><td>充值时间</td><td>广告渠道</td><td>子渠道</td><td>游戏</td><td>游戏服ID</td><td>角色名</td><td>账号ID</td><td>支付方式</td><td>平台订单号</td><td>充值金额</td><td>游戏币数量</td></tr>'.$str.'</table>');
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
        $this->assign("uname",$uname);
        $this->assign("uid",$uid);
        $this->assign("money",$money);
        $this->assign("orderid",$orderid);
        $this->assign("list","list");
        $this->assign('meg','您已进入成功充值订单中心！<br>--在对应的列输入搜索信息');
        $this->display("pay_list.html");
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

