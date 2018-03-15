<?php
/**
 * Copyright (C) 广东星辉天拓互动娱乐有限公司-游戏发行中心技术部
 * @project : 微信官网平台
 * @explain : 服务器管理类
 * @filename : server.class.php
 * @author : cooper
 * @codetime : 
 * @modifier :
 * @modifytime:
 */
class Server extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /**
     * @explain 服务器列表
     *
     */
    public function serverlist()
    {
        if($this->isadmin!=1 && !$this->checkright("serverlist") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $act = get_param('act');
        $sql        =   "select * from ".get_table("game_server")." order by gs_gid desc";
        $query = $this->db->Query($sql);
        $garr = get_game($this->db);
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['gs_endtime'] = date('Y-m-d H:i:s',$rows['gs_endtime']);
            $rows['gs_starttime'] = date('Y-m-d H:i:s',$rows['gs_starttime']);
            $rows['gs_stopoperation'] = $rows['gs_stopoperation'] == 1 ? '运营中' : '停运' ;
            $rows['gs_sid'] =  $rows['gs_sid']."[{$rows['gs_transport']}]";
            $rows['gs_gid'] = $garr[$rows['gs_gid']];
            $rows['gs_transport'] = $rows['gs_transport'] == 1 ? '安卓' : ($rows["gs_transport"] == 2 ? "ios" : "ios越狱");
            $rows['action'] = "<a href='index.php?module=server&method=edit&id=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin){
                $rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=server&method=del&id=".$rows['sysid']."'>[删除]</a>";
            }
            $right_arr[]=array(
                $rows['sysid'],
                $rows['gs_sname'],
                $rows['gs_sid'],
                $rows['gs_starttime'],
                $rows['gs_gid'],
                $rows['gs_transport'],
                $rows['gs_endtime'],
                $rows['gs_stopoperation'],
                $rows['action'],
            );
        }
        $this->assign("server","serverlist");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign('meg','服务器列表页面！');
        $this->display('server.html');
    }

    /**
     * @explain 服务器添加
     *
     */
    public function serveradd()
    {
        if ($this->isadmin != 1 && !$this->checkright("serveradd"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                        = get_param("act");
        $data['gs_gid']             = get_param("gid");
        $data['gs_sid']             = get_param("sid");
        $data['gs_sname']           = get_param("sname");
        $data['gs_starttime']       = get_param("starttime")?strtotime(get_param("starttime")):'';
        $data['gs_stopoperation']   = get_param("stopoperation")?2:1;
        $data['gs_transport']       = get_param("transport");
        $data['gs_endtime']         = get_param("endtime")?strtotime(get_param("endtime")):'';
        $data['gs_uptime']          = THIS_DATETIME;

        if ($act == 'serveradd')
        {
            //判断是否为空
            if (empty($data['gs_gid']) || empty($data['gs_sid']) || empty($data['gs_sname']) || empty($data['gs_starttime']) || empty($data['gs_stopoperation'])) {
                showinfo("必须数据不能为空!", "index.php?module=server&method=serveradd", 2);
            }

            $sql            = "select 1 from ".get_table("game_server")." where gs_gid='".$data["gs_gid"]."' and gs_transport='".$data["gs_transport"]."' and gs_sid='".$data["gs_sid"]."'";
            $is_exist       = $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
               
                $this->admin_log("添加新服务器" . $data['gi_gname'] . "失败，原因：新服务器已存在");
                showinfo("新服务器已存在!", "", 3);

            }else{
                $res = add_record($this->db, "game_server", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log("添加服务器" . $data['gi_gname'] . "成功");
                    showinfo("添加成功!", "index.php?module=server&method=serverlist", 4);
                } else {
                    $this->admin_log("添加服务器" . $data['gi_gname'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=server&method=serveradd", 2);
                }
            }
        }
        $this->assign("server", "serveradd");
        $this->assign("gamestr",$this->get_select($data['gs_gid']));
        $this->assign("data", $data);
        $this->display("server.html");
    }

    /**
     * @explain 编辑服务器
     *
    */
    public function edit()
    {
        if ($this->isadmin != 1 && !$this->checkright("serveradd"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                        = get_param("act");
        $data['sysid']              = get_param("id");
        $data['gs_gid']             = get_param("gid");
        $data['gs_sid']             = get_param("sid");
        $data['gs_sname']           = get_param("sname");
        $data['gs_starttime']       = get_param("starttime")?strtotime(get_param("starttime")):'';
        $data['gs_stopoperation']   = get_param("stopoperation")?2:1;
        $data['gs_transport']       = get_param("transport");
        $data['gs_endtime']         = get_param("endtime")?strtotime(get_param("endtime")):'';
        $data['gs_uptime']          = THIS_DATETIME;

        if ($act == 'edit')
        {
            //判断是否为空
            if (empty($data['gs_gid']) || empty($data['gs_sid']) || empty($data['gs_sname']) || empty($data['gs_starttime']) || empty($data['gs_stopoperation'])) {
                showinfo("必须数据不能为空!", "index.php?module=server&method=serveradd", 2);
            }

            $sql = "select 1 from ". get_table("game_server") . " where gs_gid='{$data["gs_gid"]}' and gs_sid='".$data["gs_sid"]."' and sysid!={$data['sysid']}";
            $is_exist       = $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
                $this->admin_log("修改新服务器" . $data['gi_gname'] . "失败，原因：新服务器已存在");
                showinfo("新服务器已存在!", "", 3);

            }else{
                $result = update_record($this->db,'game_server',$data,array('sysid'=>$data['sysid']),'',1);
                if ($result) {
                    $this->admin_log("修改服务器" . $data['gi_gname'] . "成功");
                    showinfo("修改成功!", "index.php?module=server&method=serverlist", 4);
                } else {
                    $this->admin_log("修改服务器" . $data['gi_gname'] . "失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=server&method=serveradd", 2);
                }
            }
        }else{
            //查询出当前修改数据
            $data = $this->db->getOne($this->db->query("select * from " . get_table("game_server") . " where sysid=".$data['sysid']));
            $data['gs_endtime']     = date('Y-m-d H:i:s',$data['gs_endtime']);
            $data['gs_starttime']   = date('Y-m-d H:i:s',$data['gs_starttime']);
        }
        $this->assign("server", "edit");
        $this->assign("gamestr",$this->get_select($data['gs_gid']));
        $this->assign("data", $data);
        $this->display("server.html");
    }

    /**
     * @explain 删除游戏
     *
     */
    public function del()
    {
        if ($this->isadmin != 1 && !$this->checkright("serveradd")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }
        $where['sysid'] = get_param("id", "int");
        $sql  = "select gs_sid from ".get_table("game_server")." where sysid=".$where["sysid"];
        $pics = $this->db->getOne($this->db->query($sql));
        $ret  = delete_record($this->db, "game_server", $where);
        if ($ret) {
            $this->admin_log("删除服务器 " . $pics["gi_gname"] . " 成功");
            showinfo("删除成功!", "index.php?module=server&method=serverlist", 4);
        } else {
            $this->admin_log("删除服务器" . $pics["gi_gname"] . " 失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
        }
    }
}
?>
