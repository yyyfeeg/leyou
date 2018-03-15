<?php
/**
 * Copyright (C) 广东星辉天拓互动娱乐有限公司-游戏发行中心技术部
 * @project : 微信官网平台
 * @explain : 团队管理类
 * @filename : game.class.php
 * @author : cooper
 * @codetime : 2015820
 * @modifier : 
 * @modifytime: 
 */
class Team extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /**
     * @explain 游戏列表
     *
     */
    public function listteam()
    {
        if($this->isadmin!=1 && !$this->checkright("listteam") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $act = get_param('act');
        $sql        =   "select sysid,ti_teamname,ti_teamdesc,ti_addtime from ".get_table("team_info")." order by ti_addtime desc";
        $query = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['ti_addtime'] = date('Y-m-d H:i:s',$rows['ti_addtime']);
            $rows['action'] = "<a href='index.php?module=team&method=edit&id=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin){
                $rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=team&method=del&id=".$rows['sysid']."'>[删除]</a>";
            }
            $right_arr[]=array(
                    $rows['sysid'],
                    $rows['ti_teamname'],
                    $rows['ti_teamdesc'],
                    $rows['ti_addtime'],
                    $rows['action'],
                );
        }
        $this->assign("team","listteam");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign('meg','游戏列表页面！');
        $this->display('team.html');
    }

    /**
     * @explain 游戏添加
     *
     */
    public function addteam()
    {
        if ($this->isadmin != 1 && !$this->checkright("addteam"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                    = get_param("act");
        $data['ti_teamname']    = get_param("teamname");
        $data['ti_teamdesc']    = get_param("teamdesc");
        $data['ti_addtime']     = THIS_DATETIME;
        $data['ti_addid']       = $this->my_admin_id;

        if ($act == 'addteam')
        {
            //判断是否为空
            if ($data['ti_teamname'] == '') {
                showinfo("团队名称不能为空!", "index.php?module=team&method=addteam", 2);
            }
            $sql            = "select 1 from ".get_table("team_info")." where ti_teamname='".$data["ti_teamname"]."'";
            $is_exist       = $this->db->getOne($this->db->query($sql));

            if ($is_exist) {
                $this->admin_log("添加新团队" . $data['ti_teamname'] . "失败，原因：团队已存在");
                showinfo("团队或名称已存在!", "", 3);
            }else{
                $res = add_record($this->db, "team_info", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log("添加团队" . $data['gi_gname'] . "成功");
                    showinfo("添加成功!", "index.php?module=team&method=listteam", 4);
                } else {
                    $this->admin_log("添加团队" . $data['gi_gname'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=team&method=addteam", 2);
                }
            }
        }
        $this->assign("team", "addteam");
        $this->display("team.html");
    }

    /**
     * @explain 编辑游戏
     *
     */
    public function edit()
    {
        if ($this->isadmin != 1 && !$this->checkright("addteam"))
            {
                showinfo("你没有权限执行该操作。", "", 2);
            }
            $act                    = get_param("act");
            $data['sysid']          = get_param("id");
            $data['ti_teamname']    = get_param("teamname");
            $data['ti_teamdesc']    = get_param("teamdesc");
            $data['ti_uptime']         = THIS_DATETIME;
            $data['ti_upid']           = $this->my_admin_id;

            if ($act == 'edit')
            {
                //判断是否为空
                if ($data['ti_teamname'] == '') {
                    showinfo("团队名称不能为空!", "index.php?module=team&method=edit", 2);
                }
                $sql = "select 1 from ". get_table("team_info") . " where ti_teamname='".$data["ti_teamname"]."' and sysid!={$data['sysid']}";
                $is_exist       = $this->db->getOne($this->db->query($sql));

                if ($is_exist) {
                    $this->admin_log("修改新团队" . $data['ti_teamname'] . "失败，原因：团队已存在");
                    showinfo("团队或名称已存在!", "", 3);
                }else{
                    $result = update_record($this->db,'team_info',$data,array('sysid'=>$data['sysid']),'',1);

                    if ($result) {
                        $this->admin_log("修改团队" . $data['ti_teamname'] . "成功");
                        showinfo("修改成功!", "index.php?module=team&method=listteam", 4);
                    } else {
                        $this->admin_log("修改团队" . $data['ti_teamname'] . "失败，原因：数据库插入失败");
                        showinfo("修改失败!请重新再试!", "index.php?module=team&method=edit", 2);
                    }
                }
            }else{
                //查询出当前修改数据
                $data = $this->db->getOne($this->db->query("select * from " . get_table("team_info") . " where sysid=".$data['sysid']));
            }
            $this->assign("data", $data);
            $this->assign("team", "edit");
            $this->display("team.html");
    }

    /**
     * @explain 删除游戏
     *
     */
    public function del()
    {
        if ($this->isadmin != 1 && !$this->checkright("addteam")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }
        $where['sysid'] = get_param("id", "int");
        $sql  = "select ti_teamname from ".get_table("team_info")." where sysid=".$where["sysid"];
        $pics = $this->db->getOne($this->db->query($sql));
        $ret  = delete_record($this->db, "team_info", $where);

        if ($ret) {
            $this->admin_log("删除团队 " . $pics["gi_gname"] . " 成功");
            showinfo("删除成功!", "index.php?module=team&method=listteam", 4);
        } else {
            $this->admin_log("删除团队 " . $pics["gi_gname"] . " 失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
        }
    }
}
?>
