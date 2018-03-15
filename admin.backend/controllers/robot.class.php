<?php

#=============================================================================
#     FileName: robot.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 管理员日志类
#       Author: cooper
#        Email: 459576285@qq.com
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-0-21
#      History:
#=============================================================================

class Robot extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /** 管理员日志表 * */
    function admin_log_list() {
        if ($this->isadmin != 1 && !$this->checkright("admin_log")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $this->assign("mlog", "mloglist"); 
        $this->assign('meg','您已进入管理员日志列表！');
        $this->display("admin_log.html");
    }

    /*列表分页请求*/
    function listest(){
        if ($this->isadmin != 1 && !$this->checkright("admin_log")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        //设置字段
        $rols_arr = array('sysid','al_userid','al_logname','al_content','al_inserttime','al_ip');
        //获得参数
        $where          = " where 1";
        $sEcho          = get_param('sEcho');// DataTables 用来生成的信息
        $star           = get_param('iDisplayStart');//显示的起始索引
        $lenth          = get_param('iDisplayLength');//显示的行数
        $cols           = get_param('iSortCol_0');//被排序的列
        $asc            = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"

        $where .=  get_param('sSearch_0')?" and {$rols_arr[0]} = '".get_param('sSearch_0')."'":'';//搜索第1个字段
        $where .=  get_param('sSearch_1')?" and {$rols_arr[1]} = '".get_param('sSearch_1')."'":'';//搜索第2个字段
        $where .=  get_param('sSearch_4')?" and {$rols_arr[4]} < '".(strtotime(get_param('sSearch_4'))+86400)."' and {$rols_arr[4]} > '".(strtotime(get_param('sSearch_4'))-1)."'":'';//搜索第5个字段
        $where .=  get_param('sSearch_2')?" and {$rols_arr[2]} = '".get_param('sSearch_2')."'":'';//搜索第3个字段
        $where .=  get_param('sSearch_3')?" and {$rols_arr[3]} = '".get_param('sSearch_3')."'":'';//搜索第4个字段
        
        //得到所有条数
        $sql   = "select count(*) as total from  dcenter_base.`gsys_admin_log`".$where;
        $query = $this->db->Query($sql);
        $total = $this->db->getOne($query);

        //开始查询
        $sql = "SELECT sysid,al_userid,al_logname,al_content,al_inserttime,al_ip FROM dcenter_base.`gsys_admin_log` ".$where. " limit {$star},{$lenth}";
        $query = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $rows['al_inserttime'] = $rows['al_inserttime']?date('Y-m-d H:i:s',$rows['al_inserttime']):'无数据';

            $right_arr['aaData'][]=array(
                $rows['sysid'],
                $rows['al_userid'],
                $rows['al_logname'],
                $rows['al_content'],
                $rows['al_inserttime'],
                $rows['al_ip']
            );
        }
        if(empty($right_arr)){
            $right_arr['aaData'] = array();
        }
        $right_arr['sEcho'] = $sEcho;
        $right_arr['iTotalDisplayRecords'] = $total['total'];
        $right_arr['iTotalRecords'] = $total['total'];
        echo get_json_encode($right_arr);
    }
}
?>

