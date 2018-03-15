<?php
#============================================
# 	FileName: frontend_phototype.class.php
# 		Desc: 图片分类管理类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Frontend_phototype extends Controller
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
	 * 图片分类列表
	 * @return [type] [description]
	 */
	public function phototype_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('phototype_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 查询数据
		$sql = "select sysid,fp_name,fp_order,fp_addtime from ".get_table('frontend_phototype');
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query))
		{
			$rows['fp_addtime'] = date('Y-m-d H:i:s',$rows['fp_addtime']);

			// 操作
			$action = "<a href='index.php?module=frontend_phototype&method=edit_phototype&id=".$rows['sysid']."'>[修改]</a>";
			if ($this->isadmin) {
				$action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=frontend_phototype&method=del_phototype&id=".$rows['sysid']."&name=".$rows['fp_name']."'>[删除]</a>";
			}

			$data[] = array(
				$rows['sysid'],
				$rows['fp_name'],
				$rows['fp_addtime'],
				$rows['fp_order'],
				$action
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->assign('meg','图片分类列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->display('frontend_phototype.html');
	}

	/**
	 * 添加图片分类
	 * @return [type] [description]
	 */
	public function add_phototype()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_phototype')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act = get_param('act');
		$data['fp_name']    = get_param('name');
		$data['fp_order']   = get_param('order');
		$data['fp_adduid']  = $this->my_admin_id;
		$data['fp_addtime'] = THIS_DATETIME;

		if ($act == 'add') {
			// 检查参数
			if (empty($data['fp_name'])) {
				showinfo("分类名称不能为空!", "index.php?module=frontend_phototype&method=add_phototype",3);
			}

			// 查询是否已存在
			$sql = "select * from ".get_table('frontend_phototype')." where fp_name = '".$data['fp_name']."'";
			$is_exists = $this->db->getOne($this->db->Query($sql));

			if ($is_exists) {

				// 已存在
				$this->admin_log("添加新图片分类 ". $data['fp_name'] ." 失败，原因：图片分类已存在");
                showinfo("分类已存在!", "", 3);
			} else {

				// 添加记录
				$result = add_record($this->db,'frontend_phototype',$data);
				if ($result['rows'] > 0) {
                    $this->admin_log("添加新图片分类 ". $data['fp_name'] ." 成功");
                    showinfo("添加成功!", "index.php?module=frontend_phototype&method=phototype_list", 4);
                } else {
                    $this->admin_log("添加新图片分类 ". $data['fp_name'] ." 失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=frontend_phototype&method=add_phototype", 3);
                }
			}
		}

		$this->assign('data',$data);
		$this->assign('type','add');
		$this->display('frontend_phototype.html');
	}

	/**
	 * 修改图片分类
	 * @return [type] [description]
	 */
	public function edit_phototype()
	{
		// 检查权限
		if ($this->isadmin !=1 && !$this->checkright('edit_phototype')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act = get_param('act');
		$data['sysid']     = get_param('id');
		$data['fp_name']   = get_param('name');
		$data['fp_order']  = get_param('order');
		$data['fp_upuid']  = $this->my_admin_id;
		$data['fp_uptime'] = THIS_DATETIME;

		if ($act == 'edit') {
			// 检查参数
			if (empty($data['fp_name'])) {
				showinfo("分类名称不能为空!", "index.php?module=frontend_phototype&method=edit_phototype&id=".$data['sysid'],3);
			}

			// 检查是否已存在
			$sql = "select * from ".get_table('frontend_phototype')." where sysid != ".$data['sysid']." and fp_name = '".$data['fp_name']."'";
			$is_exists = $this->db->getOne($this->db->Query($sql));

			if ($is_exists) {

				// 已存在
				$this->admin_log("修改图片分类 " . $data['fp_name'] . " 失败，原因：图片分类已存在");
                showinfo("分类已存在!", "", 3);
			} else {
				
				// 更新记录
				$result = update_record($this->db,'frontend_phototype',$data,array('sysid'=>$data['sysid']),'',1);

				if ($result) {
                    $this->admin_log("修改图片分类 " . $data['fp_name'] . " 成功");
                    showinfo("修改成功!", "index.php?module=frontend_phototype&method=phototype_list", 4);
                } else {
                    $this->admin_log("修改图片分类 " . $data['fp_name'] . " 失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=frontend_phototype&method=edit_phototype&id=".$data['sysid'], 3);
                }
			}
		} else {
			// 查询当前数据
			$sql = "select * from ".get_table('frontend_phototype')." where sysid = ".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}

		$this->assign('data',$data);
		$this->assign('type','edit');
		$this->display('frontend_phototype.html');
	}

	/**
	 * 删除图片分类
	 * @return [type] [description]
	 */
	public function del_phototype()
	{
		// 检查权限
		if ($this->isadmin !=1 && !$this->checkright('del_phototype')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 删除记录
		$name = get_param('name');
		$where['sysid'] = get_param('id','int');
		$result = delete_record($this->db,'frontend_phototype',$where);

		if ($result) {
			// 删除成功
			$this->admin_log("删除图片分类 ". $name ." 成功");
            showinfo("删除成功!", "index.php?module=frontend_phototype&method=phototype_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除图片分类 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
		}
	}
}
?>