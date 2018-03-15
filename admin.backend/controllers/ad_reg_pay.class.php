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

class Ad_reg_pay extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }
    /*注册留存列表*/
    public function reg_pay(){
        if ($this->isadmin != 1 && !$this->checkright("new_reg_pay")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $ajx = get_param("ajx");
        $tid    = get_param("tid")?get_param("tid"):''; //渠道类型
        $aid   = get_param("aid")?get_param("aid"):''; //主渠道
        $wid   = get_param("wid")?get_param("wid"):''; //子渠道
        $stime = get_param("starttime2")?get_param("starttime2"):''; //开始时间
        $etime = get_param("endtime2")?get_param("endtime2"):'';     //结束时间
        $data = '';
 
        get_games_conn();
        /*注册、点击、安装数据star*/
        $where          = " where 1";
        $stime?$where   .= " and dp_paydate>".date('Ymd',$stime):'';
        $etime?$where   .= " and dp_paydate<".date('Ymd',$etime):'';
        $aid?$where     .= " and dp_uaid=".$aid:'';
        $wid?$where     .= " and dp_uwid=".$wid:'';

        //大渠道分类
        $sql    = "SELECT dp_paydate,dp_gid,dp_uaid,dp_uwid,dp_money,count(distinct dp_uid) nums,sum(dp_money) total FROM " .get_table('paylog_log'). " $where group by dp_paydate,dp_gid,dp_uaid,dp_uwid";

        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $data .= "<tr id='odd'><td>".$rows['dp_paydate']."</td><td>".$rows['dp_gid']."</td><td>".$rows['dp_uaid']."</td><td>".$rows['dp_uwid']."</td><td>".$rows['dp_money']."</td><td>".$rows['nums']."</td><td>".$rows['total']."</td></tr>";
        }
        if(!empty($ajx) && $ajx==1){
            exit($data);
        }

        $condition .= " order by gr_gettime desc";
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;
        
        //信息列表
        $url = $_SERVER['PHP_SELF'];
                
        $totalrecord = $GLOBALS["conn"]->getOne($GLOBALS["conn"]->Query("select count(*) nums from ".get_table("game_reg","union")." where 1 $condition"));
        $totalrecord = $totalrecord['nums'];
                
        $sql = "select * from ".get_table("game_reg","union")." where 1 $condition LIMIT $start, $pagesize";
        $url .= "?action=$action&gameid=$gameid&serverid=$serverid&date1=$date1&date2=$date2&adweb=$adweb&aid=$aid&wid=$wid&mac=$mac&userid=$userid&username=$username";                
        $result = $GLOBALS["conn"]->query($sql);
        while($value = $GLOBALS["conn"] -> FetchArray($result)) {
            $value['dp_paydate'] = $value['dp_paydate'];
            $value['dp_gid'] = $value['dp_gid'];
            $value['dp_uaid'] = $value['dp_uaid'];
            $value['dp_uwid'] = $value['dp_uwid']; 
            $value['nums'] = $value['nums'];   
            $value['total'] = $value['total'];          
                        
            $ad_reg_list[] = $value;
        }
        $multi = multi($totalrecord, $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord,
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord/$pagesize),
            'multi' => $multi
        ); 

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign('ad_reg_list',$ad_reg_list);
        $this->assign("gamestr",$this->get_select());
        $this->assign("reg_pay", "reg_pay");
        $this->assign('meg','新增注册付费数据列表中心<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("ad_reg_pay.html");
    }
}
?>

