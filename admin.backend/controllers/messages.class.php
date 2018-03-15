<?php
/**
 * Copyright (C) 
 * @explain : 发送消息
 * @filename : messages.class.php
 * @author : Jericho
 * @codetime : 20170925
 * @modifier : 
 * @modifytime: 
 */
class Messages extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /**
     * @消息列表
     *
     */
    public function msg_list()
    {
        if($this->isadmin!=1 && !$this->checkright("msg_list") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $garr   = get_game($this->db);
        $act = get_param('act');

        $where      = " where 1";
        $status     = get_param("status");
        $name       = get_param("name");
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m")."-01";
        $endtime2   = get_param("endtime2")?get_param("endtime2"):date("Y-m-d");

        //条件判定
        $starttime2?$where  .= " and ul_date>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and ul_date<".date('Ymd',strtotime($endtime2)+86400):'';
        $status?$where      .= " and ul_state=".$status:'';
        $name?$where        .= " and ul_name='".$name."'":'';

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;
        $url = $_SERVER['PHP_SELF'];
        // 切换到dcenter_base数据库
        $sql_count    = "SELECT count(*) c FROM " .get_table('user_msg_log'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql_count));

        $sql        = "select * from ".get_table("user_msg_log"). " $where order by sysid desc";
        $query      = $this->db->Query($sql);
        $uc_status  = array('','未读','已读');
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['ul_time']   = date('Y-m-d H:i:s',$rows['ul_time']);
            $rows['ul_state']  = $uc_status[$rows['ul_state']];
            $rows['ul_pushid'] = $rows["ul_pushid"]==0?"系统":$rows["ul_pushid"];
            $rows['ul_pushname'] = $rows["ul_pushname"]==0?"系统":$rows["ul_pushname"];
            $rows['action']    = "<a href='index.php?module=messages&method=delmsg&id=".$rows['sysid']."'>[删除]</a>";
             $rows["action"] = '<a href='."'index.php?module=messages&method=delmsg&id=".$rows['sysid']."' onclick='return confirm(".'"确认要删除?"'.")'>[删除]</a>";
            $arrs[] = $rows;
        }
        $url    .=  "?module=messages&method=msg_list&starttime2=$starttime2&name=$name&status=$status";
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
        $this->assign("active","msg_list");
        $this->assign("data",$arrs);
        $this->assign("name",$name);
        $this->assign("status",$status);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign('meg','发送系统消息列表页面！');
        $this->display('messages.html');
    }

    /**
     * @消息添加
     *
     */
    public function msg_add()
    {
        if ($this->isadmin != 1 && !$this->checkright("msg_add"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                    = get_param("act");
        $data['ul_title']       = get_param('title');
        $data['ul_content']     = get_param("content");
        $data['ul_name']        = get_param("name");
        $data['ul_date']        = date('Ymd');
        $data['ul_time']        = THIS_DATETIME;
        $data['ul_pushid']      = 0;
        $data['ul_pushname']    = 0;

        if ($act == 'addmsg')
        {
            //判断是否为空
            if ($data['ul_title'] == '') {
                showinfo("账号不能为空!", "index.php?module=messages&method=msg_add", 3);
            }
            if ($data['ul_content'] == '') {
                showinfo("内容不能为空!", "index.php?module=messages&method=msg_add", 3);
            }
            if ($data['ul_name'] == '') {
                showinfo("账号不能为空!", "index.php?module=messages&method=msg_add", 3);
            }

            $sql  = "select 1 from view_user_info where ui_name='".$data['ul_name']."'";
            $exis = $this->db->getOne($this->db->query($sql));
            if($exis){ 
                $res = add_record($this->db, "user_msg_log", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log($this->my_admin_id."发送消息给" . $data['ul_name'] . "成功");
                    showinfo("添加成功!", "index.php?module=messages&method=msg_list", 4);
                } else {
                    showinfo("添加失败!请重新再试!", "index.php?module=messages&method=msg_list", 3);
                }
            }else{
                showinfo("该账号不存在!", "index.php?module=messages&method=msg_list", 3);
            }
        }
        $this->assign("active", "msg_add");
        $this->display("messages.html");
    }

    /**
     * @删除消息
     *
     */
    public function delmsg()
    {
        if ($this->isadmin != 1 && !$this->checkright("msg_list")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }
        $where['sysid'] = get_param("id", "int");

        $result = delete_record($this->db,'user_msg_log',$where);
        if ($result>0) {
            $this->admin_log("删除成功");
            showinfo("删除成功!", "index.php?module=messages&method=msg_list", 4);
        } else {
            $this->admin_log("删除失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
        }
    }
}
?>
