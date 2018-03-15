<?php
/**
 * Copyright (C) 广东星辉天拓互动娱乐有限公司-游戏发行中心技术部
 * @project : 微信官网平台
 * @explain : 广告管理类
 * @filename : dp_pay_info.class.php
 * @author : cooper
 * @codetime : 
 * @modifier :
 * @modifytime:
 */
class Dp_pay extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->assign('active',"active");
    }
    /**
     * @explain 游戏渠道充值数据
     *
     */
    public function pay_info()
    {
        if($this->isadmin!=1 && !$this->checkright("pay_info") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $starttime2 = get_param("starttime2");
        $endtime2   = get_param("endtime2");
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
 
        get_games_conn();
        /*注册、点击、安装数据star*/
        $where          = " where 1";
        $starttime2?$where  .= " and dp_paydate>".date('Ymd',strtotime(get_param("starttime2"))-1):'';
        $endtime2?$where    .= " and dp_paydate<".date('Ymd',strtotime(get_param("endtime2"))+86400):'';
        $gid?$where         .= " and dp_gid=".get_param("gid"):'';
        $aid?$where         .= " and dp_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and dp_uwid=".get_param("adsons"):'';
        
        //分页
        $pagesize = 5;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT dp_paydate,dp_gid,dp_uaid,dp_uwid,count(distinct dp_uid) nums,sum(dp_money) money FROM " .get_table('paylog_log'). " $where group by dp_paydate,dp_gid,dp_uaid,dp_uwid ";
        $totalrecord = $this->db->NumRows($this->db->Query($sql));
        $sql .=" order by dp_paydate desc,dp_gid,dp_uaid LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);

        $str    =  "";
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["dp_paydate"] = date("Y-m-d",strtotime($rows["dp_paydate"]));
            $rows["dp_gid"] = $garr[$rows["dp_gid"]]."[".$rows["dp_gid"]."]";
            $str .= "<td>".$rows["dp_paydate"]."</td><td>".$rows["dp_gid"]."</td><td>".$rows["dp_uaid"]."</td><td>".$rows["dp_uwid"]."</td><td>".$rows["nums"]."</td><td>".$rows["money"]."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=dp_pay&method=pay_info&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&adsons=$adsons";
        $multi = multi($totalrecord, $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord,
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord/$pagesize),
            'multi' => $multi
        );

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign('ad_reg_list',$ad_reg_list);
        $this->assign("gamestr",$this->get_select());
        $this->assign('meg','新增注册付费数据列表中心<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("dp_pay_info.html");
    }
    
}
?>
