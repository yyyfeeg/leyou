<?php
#=============================================================================
#     FileName: member.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 用户管理日志类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-08-13
#      History:
#               Tang 2016.07.10  修复修改会员密码没同步到redis用户表中的情况
#               Tang 2016.10.12  增加快速搜索会员及修改会员资料的功能
#=============================================================================

class Member extends Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /** 用户日志 * */
    public function mloglist() {

        if ($this->isadmin != 1 && !$this->checkright("mloglist")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $this->assign("mlog", "mloglist");
        $this->assign('meg','您已进入用户日志中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("member.html");
    }

    /*用户修改名称日志列表*/
    public function mlistest(){
        if ($this->isadmin != 1 && !$this->checkright("mloglist")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //设置字段
        $rols_arr = array('sysid','dul_uid','dul_gid','dul_uaid','dul_uwid','dul_name','dul_oname','dul_ip','dul_content','dul_inserttime');
        //获得参数
        $where          = " where 1";
        $sEcho          = get_param('sEcho');// DataTables 用来生成的信息
        $star           = get_param('iDisplayStart');//显示的起始索引
        $lenth          = get_param('iDisplayLength');//显示的行数
        $cols           = get_param('iSortCol_0');//被排序的列
        $asc            = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"

        $where .=  get_param('sSearch_0')?" and {$rols_arr[0]} = '".get_param('sSearch_0')."'":'';//搜索第1个字段
        $where .=  get_param('sSearch_1')?" and {$rols_arr[1]} = '".get_param('sSearch_1')."'":'';//搜索第2个字段
        $where .=  get_param('sSearch_2')?" and {$rols_arr[2]} = '".get_param('sSearch_2')."'":'';//搜索第3个字段
        $where .=  get_param('sSearch_3')?" and {$rols_arr[3]} = '".get_param('sSearch_3')."'":'';//搜索第4个字段
        $where .=  get_param('sSearch_4')?" and {$rols_arr[4]} = '".get_param('sSearch_4')."'":'';//搜索第5个字段
        $where .=  get_param('sSearch_5')?" and {$rols_arr[5]} = '".get_param('sSearch_5')."'":'';//搜索第6个字段
        $where .=  get_param('sSearch_6')?" and {$rols_arr[6]} = '".get_param('sSearch_6')."'":'';//搜索第7个字段
        
        //得到所有条数
        $sql   = "select count(*) as total from  dcenter_count.`count_user_log`".$where;
        $query = $this->db->Query($sql);
        $total = $this->db->getOne($query);

        //开始查询
        $sql = "SELECT * FROM dcenter_count.`count_user_log` ".$where. " limit {$star},{$lenth}";
        $query = $this->db->Query($sql);

        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $warr   = ad_get($this->db,2);

        while ($rows = $this->db->FetchArray($query)) {
            $rows['al_inserttime'] = $rows['al_inserttime']?date('Y-m-d H:i:s',$rows['al_inserttime']):'无数据';
            $right_arr['aaData'][]=array(
                $rows['sysid'],
                $rows['dul_uid'],
                $garr[$rows['dul_gid']]."[".$rows['dul_gid']."]",
                $aarr[$rows['dul_uaid']],
                $warr[$rows['dul_uwid']]."[".$rows['dul_uwid']."]",
                $rows['dul_name'],
                $rows['dul_oname'],
                $rows['dul_ip'],
                $rows['dul_content'],
                $rows['dul_inserttime'],
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

    /*用户列表*/
    public function mlist(){
        if ($this->isadmin != 1 && !$this->checkright("memberlist")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $this->assign("mlist", "mlist");
        $this->assign('meg','您已进入用户列表中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("member.html");
    }
    
    /*用户密码修改*/
    public function mpwedit(){
        $data['act']        = get_param('act');
        $data['ui_pass']    = get_param('ui_pass');
        $data['sysid']      = get_param('sysid');
        $res             =  $this->db->getOne($this->db->query("select ui_name,ui_salt from dcenter_count.`count_user_info` where sysid='".$data['sysid']."'"));
        $data['ui_name'] = $res['ui_name'];
        $salt = $res['ui_salt'];
        if($data['act'] == "mpwedit"){
            if(empty($data['ui_pass'])){
                    showinfo("参数错误，请重新填写!","",3);
            }
            // md5后的密码
            $md5_pwd = empty($salt)? md5($data['ui_pass']):md5(md5($data['ui_pass']).$salt);
            
            //执行mysql相关更新
            $update_sql = "update dcenter_count.`count_user_info` set ui_pass = '".$md5_pwd."' where sysid = ".$data['sysid'];
            $update_res = $this->db->Query($update_sql);
            
            //执行redis
            if($update_res){
                // 同步修改redis中的用户表中的密码
                $redis_res = $GLOBALS['redis']->lSet($data['ui_name']."_info", 0,$md5_pwd);
                if($redis_res){
                    $this->member_log($data['ui_name']."修改密码成功！",$data['sysid'],$data['ui_name']);
                    showinfo("修改密码成功!","index.php?module=member&method=mlist",4);
                }
            }else{
                $this->member_log($data['ui_name']."修改密码失败！",$data['sysid'],$data['ui_name']);
                showinfo("修改密码失败,请重试!","",3);
            }
        }
        //查询相关信息
        $this->assign("mpwedit", "mpwedit");
        $this->assign("data", $data);
        $this->assign('meg','您已进入会员密码修改！');
        $this->display("member.html");
    }

    /*会员详细信息*/
    public function minfo(){
        //获取详细信息
        $sysid = get_param("sysid","int");
        $sql   = "SELECT * FROM dcenter_count.`count_user_info` where sysid=".$sysid;
        $baseinfo = $this->db->getOne($this->db->query($sql));
        $garr = get_game($this->db);
        $tarr = get_transport($this->db);

        $baseinfo['ui_gid'] = $garr[$baseinfo['ui_gid']];
        $baseinfo['ui_uaid'] = $tarr[$baseinfo['ui_uaid']]."[".$baseinfo['ui_uaid']."]";
        $baseinfo['ui_regtime'] = date('Y-m-d H:i:s',$baseinfo['ui_regtime']);
        $baseinfo['ui_lasttime'] = date('Y-m-d H:i:s',$baseinfo['ui_lasttime']);
        $baseinfo['ui_utype'] = ($baseinfo['ui_utype'] == 1)? '普通会员['.$baseinfo['ui_utype'].']':'游客账号['.$baseinfo['ui_utype'].']';

        if ($this->isadmin == 1 || $this->checkright("modify_uinfo")) {
            $baseinfo["action"] = "<a href='index.php?module=member&method=medit&sysid=".$baseinfo['sysid']."'>[修改资料] </a>&nbsp;&nbsp;&nbsp;<a href='index.php?module=member&method=mpwedit&sysid=".$baseinfo['sysid']."'>[修改密码] </a>";
        } else {
            $baseinfo["action"] = "-";
        }

        foreach($baseinfo as $k=>$v){
            if(empty($v) || $v==null){
                $baseinfo[$k] = "无";
            }
        }
        unset($baseinfo['ui_pass']);
        echo json_encode($baseinfo);
    }

    /*分页测试*/
    function listest(){
        if ($this->isadmin != 1 && !$this->checkright("memberlist")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //设置字段
        $rols_arr = array('sysid','ui_name','ui_utype','ui_dnum','ui_gid','ui_uaid','ui_uwid','ui_regtime');
        //获得参数
        $where = " where 1";
        $sEcho          = get_param('sEcho');// DataTables 用来生成的信息
        $star           = get_param('iDisplayStart');//显示的起始索引
        $lenth          = get_param('iDisplayLength');//显示的行数
        $cols           = get_param('iSortCol_0');//被排序的列
        $asc            = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"
        $gid            = get_param('sSearch_4');
        $aid            = get_param('sSearch_5');
        $wid            = get_param('sSearch_6');

        $where.=  get_param('sSearch_0')?" and {$rols_arr[0]} = '".get_param('sSearch_0')."'":'';//搜索第1个字段
        $where.=  get_param('sSearch_2')?" and {$rols_arr[2]} = '".get_param('sSearch_2')."'":'';//搜索第3个字段
        $where.=  $gid?" and {$rols_arr[4]} = '".$gid."'":'';//搜索第5个字段
        $where.=  $aid?" and {$rols_arr[5]} = '".$aid."'":'';//搜索第6个字段
        $where.=  $wid?" and {$rols_arr[6]} = '".$wid."'":'';//搜索第7个字段
        $where.=  get_param('sSearch_7')?" and {$rols_arr[7]} < ".(strtotime(get_param('sSearch_7'))+86400)." and {$rols_arr[7]} > ".(strtotime(get_param('sSearch_7'))-1) :'';//搜索第8个字段
        $where.=  get_param('sSearch_1')?" and {$rols_arr[1]} = '".get_param('sSearch_1')."'":'';//搜索第2个字段
        $where.=  get_param('sSearch_3')?" and {$rols_arr[3]} = '".get_param('sSearch_3')."'":'';//搜索第4个字段
        $where.= $rols_arr[$cols] ? " order by {$rols_arr[$cols]} {$asc}" : ' order by sysid asc';
        //得到所有条数
        $sql   = "select count(*) as total from  dcenter_count.`count_user_info`".$where;
        $query = $this->db->Query($sql);
        $total = $this->db->getOne($query);
        
        //开始查询
        $sql = "SELECT sysid,ui_name,ui_utype,ui_gid,ui_uaid,ui_regtime,ui_uwid,ui_dnum FROM dcenter_count.`count_user_info` ".$where. " limit {$star},{$lenth}";
        $query = $this->db->Query($sql);
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $warr   = ad_get($this->db,2);
        while ($rows = $this->db->FetchArray($query)) {
            $rows['ui_regtime'] = $rows['ui_regtime']?date('Y-m-d H:i:s',$rows['ui_regtime']):'无数据';
            $rows['ui_utype2']   = $rows['ui_utype']==1?'普通会员':'游客账号';
            if ($this->isadmin == 1 || $this->checkright("modify_uinfo")) {
                $rows["action"] = "<a href='index.php?module=member&method=medit&sysid=".$rows['sysid']."'>[修改资料] </a>&nbsp;&nbsp;&nbsp;<a href='index.php?module=member&method=mpwedit&sysid=".$rows['sysid']."'>[修改密码] </a>";
            }else{
                $rows["action"] = "-";
            }
            $right_arr['aaData'][]=array(
                $rows['sysid'],
                $rows['ui_name'],
                $rows['ui_utype2']."[".$rows['ui_utype']."]",
                $rows['ui_dnum'],
                $garr[$rows['ui_gid']]."[".$rows['ui_gid']."]",
                $aarr[$rows['ui_uaid']]."[".$rows['ui_uaid']."]",
                $warr[$rows['ui_uwid']]."[".$rows['ui_uwid']."]",
                $rows['ui_regtime'],
                $rows['action'],
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

    /**
    * 修改用户资料
    * Author: Tang
    * Date: 2016.10.12
    */
    public function medit()
    {
        // 获取参数，用户ID
        $sysid = get_param('sysid','int');
        $uname = get_param('uname');
        $phone = get_param('phone');
        $email = get_param('email');
        $act   = get_param('act');

        if ($act == 'modify_uinfo') {
            if (empty($phone) && empty($email)) {
                $res = array('code'=>1001,'msg'=>'您并没有修改会员资料哦~~');
                exit(json_encode($res));
            } else {
                if (!empty($sysid) && !empty($uname)) {
                    // 修改会员资料
                    $set = '';
                    $set .= (empty($phone))? '':"ui_phone = '".$phone."'";
                    $set .= (empty($email))? '':",ui_email = '".$email."'";

                    $sql = "update dcenter_count.`count_user_info` set $set where sysid = $sysid";
                    $result = $this->db->Query($sql);
                    if ($result) {
                        // 查询redis当前用户信息
                        $redis_uinfo = $GLOBALS['redis']->lRange("{$uname}_info",0,15);
                        $bind_email_exist = $GLOBALS['redis']->exists("bind_".$redis_uinfo[1]."_info");
                        $bind_phone_exist = $GLOBALS['redis']->exists("bind_".$redis_uinfo[2]."_info");

                        if ($bind_email_exist && !empty($email)) {
                            $bind_email_info = $GLOBALS['redis']->lRange("bind_".$redis_uinfo[1]."_info",0,-1);
                            if ($bind_email_info[2] == $sysid && $bind_email_info[0] == $uname) {
                                $GLOBALS['redis']->rename("bind_".$redis_uinfo[1]."_info","bind_".$email."_info");
                            }
                        }

                        if ($bind_phone_exist && !empty($phone)) {
                            $bind_phone_info = $GLOBALS['redis']->lRange("bind_".$redis_uinfo[2]."_info",0,-1);
                            if ($bind_phone_info[2] == $sysid && $bind_phone_info[0] == $uname) {
                                $GLOBALS['redis']->rename("bind_".$redis_uinfo[2]."_info","bind_".$phone."_info");
                            }
                        }

                        if ($redis_uinfo[12] == $sysid) {
                            (empty($phone))? '':$GLOBALS['redis']->lSet("{$uname}_info", 2,$phone);
                            (empty($email))? '':$GLOBALS['redis']->lSet("{$uname}_info", 1,$email);
                        }

                        
                        $this->admin_log("用户于".date('Y-m-d H:i:s')."修改会员($uname)资料成功");
                        $res = array('code'=>1000,'msg'=>'修改成功');
                        exit(json_encode($res));
                    } else {
                        $this->admin_log("用户于".date('Y-m-d H:i:s')."修改会员($uname)资料失败");
                        $res = array('code'=>1002,'msg'=>'修改失败，入库失败');
                        exit(json_encode($res));
                    }
                } else {
                    $this->admin_log("用户于".date('Y-m-d H:i:s')."进行非法操作。");
                    $res = array('code'=>1003,'msg'=>'修改失败，基础数据不正确');
                    exit(json_encode($res));
                }
            }
            
        } else {
            $gameArr = get_game($this->db);//游戏名称数组
            $transportArr = get_transport($this->db);//渠道名称数组

            $sql = "select * from dcenter_count.`count_user_info` where sysid = $sysid";
            $uinfo = $this->db->getOne($this->db->Query($sql));

            $uinfo['ui_gid'] = $gameArr[$uinfo['ui_gid']];
            $uinfo['ui_uaid'] = $transportArr[$uinfo['ui_uaid']]."[".$uinfo['ui_uaid']."]";
            $uinfo['ui_regtime'] = date('Y-m-d H:i:s',$uinfo['ui_regtime']);
            $uinfo['ui_lasttime'] = date('Y-m-d H:i:s',$uinfo['ui_lasttime']);
            $uinfo['ui_utype'] = ($uinfo['ui_utype'] == 1)? '普通会员['.$uinfo['ui_utype'].']':'游客账号['.$uinfo['ui_utype'].']';
            foreach ($uinfo as $k => $v) {
                if(empty($v) || $v == null) $uinfo[$k] = "无";
            }
        }

        $this->assign('uinfo',$uinfo);
        $this->assign("medit","medit");
        $this->display("member.html");
    }

    /**
     * 快速搜索会员
     * Author: Tang
     * Date: 2016.10.12 
     */
    public function search()
    {
        if ($this->isadmin != 1 && !$this->checkright("search_user")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        // 接收参数
        $act = get_param('act');
        $uid = get_param('uid','int');
        $uname = get_param('uname');
        $utype = get_param('utype','int');

        if ($act == 'search') {
            // 判断参数
            if (empty($uid) && empty($uname)) {
                showinfo("请填写会员ID或会员账号", "", 2);
            }

            $where = "1";
            $where .= (empty($uid))? "":" and sysid = $uid";
            $where .= (empty($uname))? "":" and ui_name = '".$uname."'";
            $where .= (empty($utype))? "":" and ui_utype = $utype";
            $sql = "select * from dcenter_count.`count_user_info` where $where";
            $query = $this->db->Query($sql);
            while ($rows = $this->db->FetchArray($query)) {
                $rows['ui_regtime'] = ($rows['ui_regtime'])? date('Y-m-d H:i:s',$rows['ui_regtime']):'无数据';
                $rows['ui_utype2']  = ($rows['ui_utype']==1)? '普通会员':'游客账号';
                if ($this->isadmin == 1 || $this->checkright("modify_uinfo")) {
                    $rows["action"] = "<a href='index.php?module=member&method=medit&sysid=".$rows['sysid']."'>[修改资料] </a>&nbsp;&nbsp;&nbsp;<a href='index.php?module=member&method=mpwedit&sysid=".$rows['sysid']."'>[修改密码] </a>";
                } else {
                    $rows["action"] = "-";
                }
                
                $data[] = array(
                    $rows['sysid'],
                    $rows['ui_name'],
                    $rows['ui_utype2']."[".$rows['ui_utype']."]",
                    $rows['ui_dnum'],
                    $garr[$rows['ui_gid']]."[".$rows['ui_gid']."]",
                    $aarr[$rows['ui_uaid']],
                    $warr[$rows['ui_uwid']]."[".$rows['ui_uwid']."]",
                    $rows['ui_regtime'],
                    $rows['action']
                );
            }
            if (empty($data)) {
                echo json_encode(array('code'=>1001,'msg'=>'没有相匹配的数据'));
            } else {
                echo json_encode($data);
            }
            exit;
        }

        $this->assign('flag','search');
        $this->display("member.html");
    }
}
?>

