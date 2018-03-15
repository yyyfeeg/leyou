<?php
/**
 * Copyright (C) 
 * @project : 微信官网平台
 * @explain : 账号封禁类
 * @filename : user_ban.class.php
 * @author : cooper
 * @codetime : 2017921
 * @modifier : 
 * @modifytime: 
 */
class User_ban extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /**
     * @explain 封禁列表
     *
     */
    public function listban()
    {
        if($this->isadmin!=1 && !$this->checkright("listban") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $garr   = get_game($this->db);
        $act = get_param('act');

        $where      = " where 1";
        $gid        = get_param("gid");
        $tid        = get_param("tid");
        $status     = get_param("status");
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-d");

        //条件判定
        $starttime2?$where  .= " and uc_closedate>".date('Ymd',strtotime($starttime2)-1):'';
        $gid?$where         .= " and uc_gid=".get_param("gid"):'';
        $tid?$where         .= " and uc_timelimit=".get_param("tid"):'';
        $status?$where      .= " and uc_type=".get_param("status"):'';

        $this->assign("gamestr",$this->get_select($gid));

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;
        $url = $_SERVER['PHP_SELF'];
        // 切换到dcenter_base数据库
        $sql_count    = "SELECT count(*) c FROM " .get_table('user_closure'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));

        $sql        =   "select * from ".get_table("user_closure"). " $where order by sysid desc";
        $query = $this->db->Query($sql);
        $uc_type = array('','封禁账号','封禁IP','封禁设备号');
        $uc_status = array('','已封禁','已解封');
        $uc_lim = array('30'=>'30分钟','180'=>'3小时','720'=>'12小时','1440'=>'1天','4320'=>'3天','10080'=>'7天','43200'=>'30天','0'=>'永久');
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows["uc_gid"]       = $rows["uc_gid"]?$garr[$rows["uc_gid"]]."[".$rows["uc_gid"]."]":"所有游戏";
            $rows['uc_closetime'] = date('Y-m-d H:i:s',$rows['uc_closetime']);
            $rows['uc_type']      = $uc_type[$rows['uc_type']];
            $rows['uc_status']    = $uc_status[$rows['uc_status']];
            $rows['uc_timelimit'] = $uc_lim[$rows['uc_timelimit']];
            $rows['uc_addname']   = $rows['uc_addname']?$rows['uc_addname']:$rows['uc_adduid'];
            $rows['action'] = "<a href='index.php?module=user_ban&method=editban&id=".$rows['sysid']."'>[修改]</a>";
            // if($this->isadmin){
                $rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=user_ban&method=delban&id=".$rows['sysid']."'>[解封]</a>";
            // }
            $arrs[] = $rows;
        }
        $url    .=  "?module=user_ban&method=listban&starttime2=$starttime2&gid=$gid&tid=$tid&mtype=$mtype";
        $multi = multi($totalrecord["c"], $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord["c"],
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord["c"]/$pagesize),
            'multi' => $multi
        );
        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("gamestr",$this->get_select($gid));
        $this->assign("active","listban");
        $this->assign("data",$arrs);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("status",$status);
        $this->assign("starttime2",$starttime2);
        $this->assign('meg','账号封禁列表页面！');
        $this->display('user_ban.html');
    }

    /**
     * @explain 封禁添加
     *
     */
    public function addban()
    {
        if ($this->isadmin != 1 && !$this->checkright("addban"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                    = get_param("act");
        $data['uc_type']        = get_param("screen");
        $data['uc_gid']         = get_param("gid");
        $data['uc_timelimit']   = get_param("tid");
        $data['uc_uids']        = get_param("uids");
        $data['uc_reason']      = get_param("reason");
        $data['uc_closedate']   = date('Ymd');
        $data['uc_closetime']   = THIS_DATETIME;
        $data['uc_addtime']     = THIS_DATETIME;
        $data['uc_status']      = 1;
        $data['uc_adduid']      = $this->my_admin_id;
        $data['uc_addname']     = $this->myad_realname;

        if ($act == 'addban')
        {
            //判断是否为空
            if ($data['uc_uids'] == '') {
                showinfo("封禁账号不能为空!", "index.php?module=user_ban&method=addban", 3);
            }
            if ($data['uc_timelimit'] == '') {
                showinfo("封禁时限不能为空!", "index.php?module=user_ban&method=addban", 3);
            }
            $sql            = "select 1 from ".get_table("user_closure")." where uc_uids='".$data["uc_uids"]."' and uc_timelimit='".$data["uc_timelimit"]."'";
            $is_exist       = $this->db->getOne($this->db->query($sql));

            if ($is_exist) {
                $this->admin_log("添加新封禁" . $data['uc_uids'] . "失败，原因：封禁账号或封禁时限重复");
                showinfo("封禁账号或封禁时限重复!", "", 3);
            }else{
                $res = add_record($this->db, "user_closure", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log("添加封禁" . $data['uc_uids'] . "成功");
                    showinfo("添加成功!", "index.php?module=user_ban&method=listban", 4);
                } else {
                    $this->admin_log("添加封禁" . $data['uc_uids'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=user_ban&method=addban", 3);
                }
            }
        }
        $this->assign("gamestr",$this->get_select($gid));
        $this->assign("active", "addban");
        $this->display("user_ban.html");
    }

    /**
     * @explain 编辑封禁
     *
     */
    public function editban()
    {
        if ($this->isadmin != 1 && !$this->checkright("addban"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
            $act                    = get_param("act");
            $data['sysid']          = get_param("id");
            $data['uc_type']        = get_param("screen");
            $data['uc_gid']         = get_param("gid");
            $data['uc_timelimit']   = get_param("tid");
            $data['uc_uids']        = get_param("uids");
            $data['uc_reason']      = get_param("reason");
            // $data['uc_closedate']   = date('Ymd');
            // $data['uc_closetime']   = THIS_DATETIME;
            $data['uc_edittime']    = THIS_DATETIME;
            $data['uc_edituid']     = $this->my_admin_id;
            $data['uc_editname']    = $this->myad_realname;

            if ($act == 'editban')
            {
                //判断是否为空
                if ($data['uc_uids'] == '') {
                showinfo("封禁账号不能为空!", "index.php?module=user_ban&method=editban", 3);
                }
                if ($data['uc_timelimit'] == '') {
                    showinfo("封禁时限不能为空!", "index.php?module=user_ban&method=editban", 3);
                }
                $result = update_record($this->db,'user_closure',$data,array('sysid'=>$data['sysid']),'',1);

                if ($result) {
                    $this->admin_log("修改封禁成功");
                    showinfo("修改成功!", "index.php?module=user_ban&method=listban", 4);
                } else {
                    $this->admin_log("修改封禁失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=user_ban&method=editban", 3);
                }
            }else{
                //查询出当前修改数据
                $data = $this->db->getOne($this->db->query("select * from " . get_table("user_closure") . " where sysid=".$data['sysid']));
            }
            $this->assign("gamestr",$this->get_select($data['uc_gid']));
            $this->assign("tid", $data['uc_timelimit']);
            $this->assign("status", $data['uc_type']);
            $this->assign("data", $data);
            $this->assign('sysid',$data['sysid']);
            $this->assign("active", "editban");
            $this->display("user_ban.html");
    }

    /**
     * @explain 解除封禁
     *
     */
    public function delban()
    {
        if ($this->isadmin != 1 && !$this->checkright("addban")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }
        $where['sysid'] = get_param("id", "int");

        $data['uc_status']     = 2;
        $data['uc_mode']       = 2;
        $data['uc_opendate']   = date('Ymd');
        $data['uc_opentime']   = THIS_DATETIME;
        $data['uc_edittime']   = THIS_DATETIME;
        $data['uc_edituid']    = $this->my_admin_id;

        $result = update_record($this->db,'user_closure',$data,array('sysid'=>$where['sysid']),'',1);

        if ($result) {
            $this->admin_log("解除封禁成功");
            showinfo("解除封禁成功!", "index.php?module=user_ban&method=listban", 4);
        } else {
            $this->admin_log("解除封禁失败，原因:数据库删除失败");
            showinfo("解除封禁失败,请重试!", "", 3);
        }
    }
}
?>
