<?php
#============================================
# 	FileName: spree_type.class.php
# 		Desc: 礼包类型管理功能类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.04.19
# LastChange: 
#============================================

class Spree_type extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		if (!$this->checklogin()) {
			showinfo("", "index.php", 1);
		}
	}

	/**
	 * 添加礼包类型
	 */
	public function add_stype()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_stype')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act = get_param('act');
		$data['gst_name']     = get_param('gst_name');
		$data['gst_desc']     = get_param('gst_desc');
		$data['gst_allownum'] = get_param('gst_allownum') ? get_param('gst_allownum'):0;
		$data['gst_allowday'] = get_param('gst_allowday') ? get_param('gst_allowday'):0;
		$data['gst_open']     = get_param('gst_open') ? get_param('gst_open'):1;
		$data['gst_platform'] = get_param('gst_platform') ? get_param('gst_platform'):2;
		$data['gst_adduid']   = $this->my_admin_id;
		$data['gst_addtime']  = THIS_DATETIME;

		if ($act == 'add') {
			// 检查类型名称
			if (empty($data['gst_name'])) {
				showinfo("类型名称不能为空!", "index.php?module=spree_type&method=add_stype",3);
			}

			// 添加记录
			$result = add_record($this->db,'spree_type',$data);
			if ($result['rows'] > 0) {
                $this->admin_log("添加礼包类型 ". $data['gst_name'] ." 成功");
                showinfo("添加成功!", "index.php?module=spree_type&method=stype_list", 4);
            } else {
                $this->admin_log("添加礼包类型 ". $data['gst_name'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=spree_type&method=add_stype", 3);
            }
		}

		$this->assign('data',$data);
		$this->assign('type','add');
		$this->display('spree_type.html');
	}

	/**
	 * 礼包类型列表
	 */
	public function stype_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('stype_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$openArr = array(1=>'开启',2=>'关闭');
		$platform = array(1=>'是',2=>'否');

		// 查询记录
		$sql = "select sysid,gst_name,gst_desc,gst_allownum,gst_allowday,gst_open,gst_platform,gst_addtime from ".get_table('spree_type');
		$query = $this->db->Query($sql);

		while ($rows = $this->db->FetchArray($query))
		{
			// 操作
			$action = "<a href='index.php?module=spree_type&method=edit_stype&id=".$rows['sysid']."'>[修改]</a>";
			if ($this->isadmin) {
				$action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=spree_type&method=del_stype&id=".$rows['sysid']."&name=".$rows['gst_name']."'>[删除]</a>";
			}
			$data[] = array(
				$rows['sysid'],
				$rows['gst_name'],
				$rows['gst_desc'],
				$rows['gst_allownum'],
				$rows['gst_allowday'],
				$openArr[$rows['gst_open']],
				$platform[$rows['gst_platform']],
				date('Y-m-d H:i:s',$rows['gst_addtime']),
				$action
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->display('spree_type.html');
	}

	/**
	 * 修改礼包类型
	 */
	public function edit_stype()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('edit_stype')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act = get_param('act');
		$data['sysid']        = get_param('id');
		$data['gst_name']     = get_param('gst_name');
		$data['gst_desc']     = get_param('gst_desc');
		$data['gst_allownum'] = get_param('gst_allownum') ? get_param('gst_allownum'):0;
		$data['gst_allowday'] = get_param('gst_allowday') ? get_param('gst_allowday'):0;
		$data['gst_open']     = get_param('gst_open') ? get_param('gst_open'):1;
		$data['gst_platform'] = get_param('gst_platform') ? get_param('gst_platform'):2;
		$data['gst_upuid']    = $this->my_admin_id;
		$data['gst_uptime']   = THIS_DATETIME;

		if ($act == 'edit') {
			// 检查类型名称
			if (empty($data['gst_name'])) {
				showinfo("类型名称不能为空!", "index.php?module=spree_type&method=edit_stype&id=".$data['sysid'],3);
			}

			// 更新数据
			$result = update_record($this->db,'spree_type',$data,array('sysid'=>$data['sysid']),'',1);

			if ($result) {
                $this->admin_log("修改礼包类型 " . $data['gst_name'] . " 成功");
                showinfo("修改成功!", "index.php?module=spree_type&method=stype_list", 4);
            } else {
                $this->admin_log("修改礼包类型 " . $data['gst_name'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=spree_type&method=edit_stype&id=".$data['sysid'], 3);
            }
		} else {
			// 查询原有数据
			$sql = "select sysid,gst_name,gst_desc,gst_allownum,gst_allowday,gst_open,gst_platform,gst_addtime from ".get_table('spree_type')." where sysid=".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}
		
		$this->assign('data',$data);
		$this->assign('type','edit');
		$this->display('spree_type.html');
	}

	/**
	 * 删除礼包类型
	 */
	public function del_stype()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_stype')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$name = get_param('name');
		$where['sysid'] = get_param('id');

		$result = delete_record($this->db,'spree_type',$where);
		if ($result) {
			$this->admin_log("删除礼包类型 ". $name ." 成功");
            showinfo("删除礼包类型 ".$name." 成功!", "index.php?module=spree_type&method=stype_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除礼包类型 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除礼包类型 ".$name." 失败,请重试!", "", 3);
		}

	}
}
?>