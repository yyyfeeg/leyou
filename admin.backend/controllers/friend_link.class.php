<?php
#============================================
#   FileName: friend_link.class.php
#       Desc: 友情链接管理
#     Author: Liu
#      Email: 270461709@qq.com
#       Date: 2018.1.30
# LastChange: 
#============================================
class Friend_link extends Controller
{
    /**
     * 构造函数，初始化父类构造函数及检查是否登录
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /**
     * 添加友情链接
     * @return [type] [description]
     */
    public function add_link()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('add_link')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $act = get_param('act');
        $data['gfl_linkname'] = get_param('lname');
        $data['gfl_linkurl']  = get_param('lurl');
        $data['gfl_linkdesc'] = get_param('desc');
        $data['gfl_open']     = get_param('open')? get_param('open'):1;
        $data['gfl_sort']     = get_param('sort')?get_param('sort'):0;
        $data['gfl_addid']    = $this->my_admin_id;
        $data['gfl_addtime']  = THIS_DATETIME;

        // 添加数据
        if ($act == 'add') {
            // 检查是否填写友链名称
            if (empty($data['gfl_linkname'])) {
                showinfo("请填写友链名称!", "index.php?module=friend_link&method=add_link",3);
            }

            // 检查是否填写友链URL
            if (empty($data['gfl_linkurl'])) {
                showinfo("请填写友链地址!","index.php?module=friend_link&method=add_link",3);
            }

            // 添加记录
            $result = add_record($this->db,'friend_link',$data);
            if ($result['rows'] > 0) {
                $this->admin_log("添加友链 ". $data['gfl_linkname'] ." 成功");
                showinfo("添加成功!", "index.php?module=friend_link&method=list_link", 4);
            } else {
                $this->admin_log("添加友链 ". $data['gfl_linkname'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=friend_link&method=add_link", 3);
            }
        }

        $this->assign('data',$data);
        $this->assign('type','add');
        $this->display('friend_link.html');
    }
    /**
     * 查看友情链接
     * @return [type] [description]
     */
    public function list_link()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('list_link')) {
            showinfo("你没有权限执行该操作。","",2);
        }
        $openArr = array(1=>'开启',2=>'关闭');
        // 查询数据
        $sql = "select * from ".get_table('friend_link');
        $query = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            // 操作
            $action = "<a href='index.php?module=friend_link&method=edit_link&id=".$rows['sysid']."'>[修改]</a>";
            if ($this->isadmin == 1 || $this->checkright('add_link')) {
                $action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=friend_link&method=del_link&id=".$rows['sysid']."&name=".$rows['gfl_linkname']."'>[删除]</a>";
            }
            $data[] = array(
                $rows['sysid'],
                $rows['gfl_linkname'],
                $rows['gfl_linkurl'],
                $openArr[$rows['gfl_open']],
                $rows['gfl_sort'],
                date('Y-m-d',$rows['gfl_addtime']),
                $rows['gfl_addid'],
                $action
                );
        }

        $this->assign('data',get_json_encode($data));
        $this->assign('type','list');
        $this->display('friend_link.html');
    }

    /**
     * 修改友链
     */
    public function edit_link()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('add_link')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $act = get_param('act');
        $data['sysid']        = get_param('id');
        $data['gfl_linkname'] = get_param('lname');
        $data['gfl_linkurl']  = get_param('lurl');
        $data['gfl_linkdesc'] = get_param('desc');
        $data['gfl_open']     = get_param('open')? get_param('open'):1;
        $data['gfl_sort']     = get_param('sort')?get_param('sort'):0;
        $data['gfl_upid']     = $this->my_admin_id;
        $data['gfl_uptime']   = THIS_DATETIME;

        // 修改
        if ($act == 'edit') {
            // 检查是否填写友链名称
            if (empty($data['gfl_linkname'])) {
                showinfo("请填写友链名称!", "index.php?module=friend_link&method=edit_link&id=".$data['sysid'],3);
            }

            // 检查是否填写友链URL
            if (empty($data['gfl_linkurl'])) {
                showinfo("请填写友链地址!","index.php?module=friend_link&method=edit_link&id=".$data['sysid'],3);
            }

            // 更新数据
            $result = update_record($this->db,'friend_link',$data,array('sysid'=>$data['sysid']),'',1);

            if ($result) {
                $this->admin_log("修改友链 " . $data['gfl_linkname'] . " 成功");
                showinfo("修改成功!", "index.php?module=friend_link&method=list_link", 4);
            } else {
                $this->admin_log("修改友链 " . $data['gfl_linkname'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=friend_link&method=edit_link&id=".$data['sysid'], 3);
            }
        }else {
            // 查询原始数据
            $sql = "select * from ".get_table('friend_link')." where sysid = ".$data['sysid'];
            $data = $this->db->getOne($this->db->Query($sql));
        }

        $this->assign('data',$data);
        $this->assign('type','edit');
        $this->display('friend_link.html');
    }

    /**
     * 删除友链
     */
    public function del_link()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('add_link')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $name = get_param('name');
        $where['sysid'] = get_param('id');

        $result = delete_record($this->db,'friend_link',$where);
        if ($result) {
            $this->admin_log("删除友链 ". $name ." 成功");
            showinfo("删除友链 ".$name." 成功!", "index.php?module=friend_link&method=list_link", 4);
        } else {
            // 删除失败
            $this->admin_log("删除友链 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除友链 ".$name." 失败,请重试!", "", 3);
        }
    }
}
?>