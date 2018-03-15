<?php

#=============================================================================
#     FileName: member.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 用户管理日志类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.3737.com/
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
    }

    /** 第三方注册日志 * */
    public function reglist() {
        if ($this->isadmin != 1 && !$this->checkright("ad_reg")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $this->assign("list", "list");
        $this->assign('meg','您已进入第三方注册日志中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("ad_reg.html");
    }

    /*第三方注册日志列表*/
    public function listest(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("ad_reg")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        //设置字段
        $rols_arr = array('sysid','dl_uid','dl_name','dl_uaid','dl_uwid','dl_gid','dl_date','dl_mac','dl_ip');

        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $warr   = ad_get($this->db,2);
        //获得参数
        $where          = " where 1";
        $sEcho          = get_param('sEcho');// DataTables 用来生成的信息
        $star           = get_param('iDisplayStart');//显示的起始索引
        $lenth          = get_param('iDisplayLength');//显示的行数
        $cols           = get_param('iSortCol_0');//被排序的列
        $asc            = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"
        
        //循环查询条件
        for($i=0;$i<8;$i++){
            $where .=  get_param('sSearch_'.$i)?" and {$rols_arr[$i]} = '".get_param('sSearch_'.$i)."'":'';
        }

        //得到所有条数
        $sql   = "select count(*) as total from  dcenter_count.`count_gamereg_log`".$where;
        $query = $this->db->Query($sql);
        $total = $this->db->getOne($query);

        //开始查询
        $sql = "SELECT * FROM dcenter_count.`count_gamereg_log` ".$where. " limit {$star},{$lenth}";
        $query = $this->db->Query($sql);

        while ($rows = $this->db->FetchArray($query)) {
            $right_arr['aaData'][]=array(
                $rows['sysid'],
                $rows['dl_uid'],
                $rows['dl_name'],
                $aarr[$rows['dl_uaid']],
                $warr[$rows['dl_uwid']]."[".$rows['dl_uwid']."]",
                $garr[$rows['dl_gid']]."[".$rows['dl_gid']."]",
                $rows['dl_date'],
                $rows['dl_mac'],
                $rows['dl_ip']
            );
        }
        if(empty($right_arr)){
            $right_arr['aaData'] = array();
        }
        $right_arr['sEcho'] = $sEcho;
        $right_arr['iTotalDisplayRecords'] = $total['total'];
        $right_arr['iTotalRecords'] = $total['total'];
        echo json_encode($right_arr);
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
    public function paylist() {
        if ($this->isadmin != 1 && !$this->checkright("pay_list")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $this->assign("list", "list");
        $this->assign('meg','您已进入成功充值订单中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("pay_list.html");
    }

    /** 成功充值数据 **/
    public function pay_list(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("pay_list")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        //设置字段
        //订单号，第三方订单号，账户，用户ID，游戏，服务器，渠道，付费时间，注册时间
        //$rols_arr = array('sysid','dp_orderid','dp_name','dp_uid','dp_gid','dp_sid','dp_uaid','dp_uwid','dp_paydate','dp_regdate');
        $rols_arr = array('sysid','dp_orderid','dp_name','dp_regdate','dp_money','dp_gid','dp_sid','dp_uaid','dp_uwid','dp_paydate');

        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);

        //获得参数
        $where          = " where 1";
        $sEcho          = get_param('sEcho');// DataTables 用来生成的信息
        $star           = get_param('iDisplayStart')?get_param('iDisplayStart'):0;//显示的起始索引
        $lenth          = get_param('iDisplayLength')?get_param('iDisplayLength'):10;//显示的行数
        $cols           = get_param('iSortCol_0');//被排序的列
        $asc            = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"
        //循环查询条件
        for($i=0;$i<9;$i++){
            if($i==2){
                $where .=  get_param('sSearch_'.$i)?" and {$rols_arr[$i]} like '%".get_param('sSearch_'.$i)."%'":'';
            }else{
                $where .=  get_param('sSearch_'.$i)?" and {$rols_arr[$i]} = '".get_param('sSearch_'.$i)."'":'';
            }
        }

        //开始查询
        $sql = "SELECT * FROM dcenter_count.`count_paylog_log` ".$where. " limit {$star},{$lenth}";
        //var_dump( $sql);die;
        $query = $this->db->Query($sql);

        while ($rows = $this->db->FetchArray($query)){
            $right_arr['aaData'][]=array(
                $rows['sysid'],                                 //订单id
                $rows['dp_orderid'],                            //第三方订单号
                $rows['dp_uid'],                                //充值帐号
                $rows['dp_regdate'],                            //注册时间
                $rows['dp_money'],                              //充值金额
                $garr[$rows['dp_gid']]."[".$rows['dp_gid']."]", //游戏名称
                $sarr[$rows['dp_gid']][$rows['dp_sid']]."[".$rows['dp_sid']."]",
                $aarr[$rows['dp_uaid']],                        //广告站
                $aarr[$rows['dp_uwid']],                        //广告
                $rows['dp_paydate']                             //付费时间
            );
        }
        if(empty($right_arr)){
            $right_arr['aaData'] = array();
        }

        //得到所有条数
        $sql   = "select count(*) as total from  dcenter_count.`count_paylog_log` ".$where;
        $query = $this->db->Query($sql);
        $total = $this->db->getOne($query);

        $right_arr['sEcho'] = $sEcho;
        $right_arr['iTotalDisplayRecords'] = $total['total'];
        $right_arr['iTotalRecords'] = $total['total'];
        echo json_encode($right_arr);
    }
}
?>

