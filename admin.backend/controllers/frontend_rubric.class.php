<?php
#============================================
# 	FileName: frontend_rubric.class.php
# 		Desc: 前端栏目管理类
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Frontend_rubric extends Controller
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
	 * 前端栏目列表
	 * @return [type] [description]
	 */
	public function rubric_list()
	{
		// 检查权限
		if ($this->isadmin !=1 && !$this->checkright('rubric_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 查询数据
		$tmp = get_rubric($this->db);
		if (!empty($tmp)) {
			foreach ($tmp as $value) {
				// 操作
				$action = "<a href='index.php?module=frontend_rubric&method=edit_rubric&id=".$value['sysid']."'>[修改]</a>";
				if ($this->isadmin) {
					$action .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='del_rubric(".$value['sysid'].");'>[删除]</a>";
				}
				$data[] = array(
					$value['sysid'],
					$value['fr_name'],
					$value['level'],
					$value['fr_order'],
					$action
					);
			}
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','rubric_list');
		$this->assign('meg','栏目列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->display('frontend_rubric.html');
	}

	/**
	 * 添加前端栏目
	 * @return [type] [description]
	 */
	public function add_rubric()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_rubric')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act = get_param('act');
		$data['fr_fid']        = get_param('fid');
		$data['fr_name']       = get_param('name');
		$data['fr_dir']        = get_param('dir');
		$data['fr_template']   = get_param('template');
		$data['fr_desc']       = get_param('desc');
		$data['fr_order']      = get_param('order');
		$data['fr_contribute'] = get_param('contribute')? get_param('contribute'):1;
		$data['fr_hide']       = get_param('hide')? get_param('hide'):1;

		if ($act == 'add') {
			// 验证参数
			if ($data['fr_fid'] == '') {
				showinfo("上级栏目不能为空!", "index.php?module=frontend_rubric&method=add_rubric",3);
			}
			if (empty($data['fr_name'])) {
				showinfo("栏目名称不能为空!", "index.php?module=frontend_rubric&method=add_rubric",3);
			}
			if (empty($data['fr_dir'])) {
				showinfo("文件保存目录不能为空!", "index.php?module=frontend_rubric&method=add_rubric",3);
			}

			// 检查是否已经存在
			$sql = "select * from ".get_table('frontend_rubric')." where fr_fid = ".$data['fr_fid']." and fr_name = '".$data['fr_name']."'";
			$is_exist = $this->db->getOne($this->db->Query($sql));

			if ($is_exist) {

				// 已经存在
				$this->admin_log("添加新栏目" . $data['fr_name'] . "失败，原因：栏目已存在");
                showinfo("栏目已存在!", "", 3);
			} else {

				// 添加记录
				$result = add_record($this->db, "frontend_rubric", $data);
                if ($result['rows'] > 0) {
                    $this->admin_log("添加新栏目 " . $data['fr_name'] . " 成功");
                    showinfo("添加成功!", "index.php?module=frontend_rubric&method=rubric_list", 4);
                } else {
                    $this->admin_log("添加新栏目 " . $data['fr_name'] . " 失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=frontend_rubric&method=add_rubric", 3);
                }
			}
		}

		$this->assign('rubricArr',get_rubric($this->db));
		$this->assign('data',$data);
		$this->assign('type','add_rubric');
		$this->display('frontend_rubric.html');
	}

	/**
	 * 修改栏目
	 * @return [type] [description]
	 */
	public function edit_rubric()
	{
		// 检查权限
		if ($this->isadmin !=1 && !$this->checkright('edit_rubric')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act   = get_param('act');
		$sysid = get_param('id');
		$data['fr_fid']        = get_param('fid');
		$data['fr_name']       = get_param('name');
		$data['fr_dir']        = get_param('dir');
		$data['fr_template']   = get_param('template');
		$data['fr_desc']       = get_param('desc');
		$data['fr_order']      = get_param('order');
		$data['fr_contribute'] = get_param('contribute')? get_param('contribute'):1;
		$data['fr_hide']       = get_param('hide')? get_param('hide'):1;

		if ($act == 'edit') {
			// 验证参数
			if ($data['fr_fid'] == '') {
				showinfo("上级栏目不能为空!", "index.php?module=frontend_rubric&method=add_rubric",3);
			}
			if (empty($data['fr_name'])) {
				showinfo("栏目名称不能为空!", "index.php?module=frontend_rubric&method=add_rubric",3);
			}
			if (empty($data['fr_dir'])) {
				showinfo("文件保存目录不能为空!", "index.php?module=frontend_rubric&method=add_rubric",3);
			}

			// 检查栏目名称是否已存在
			$sql = "select * from ".get_table('frontend_rubric')." where sysid != $sysid and fr_fid = ".$data['fr_fid']." and fr_name = '".$data['fr_name']."'";
			$is_exist = $this->db->getOne($this->db->Query($sql));

			if ($is_exist) {

				// 已经存在
				$this->admin_log("修改栏目" . $data['fr_name'] . "失败，原因：栏目已存在");
                showinfo("栏目已存在!", "", 3);
			} else {
				
				// 更新数据
				$result = update_record($this->db,'frontend_rubric',$data,array('sysid'=>$sysid),'',1);

				if ($result) {
				 	$this->admin_log("修改栏目 " . $data['fr_name'] . " 成功");
                    showinfo("修改成功!", "index.php?module=frontend_rubric&method=rubric_list", 4);
				} else {
					$this->admin_log("修改栏目 " . $data['fr_name'] . " 失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=frontend_rubric&method=edit_rubric&id=".$sysid, 3);
				}
			}
		} else {
			// 查询当前记录数据
			$sql = "select * from ".get_table('frontend_rubric')." where sysid = $sysid";
			$data = $this->db->getOne($this->db->Query($sql));
		}

		$this->assign('rubricArr',get_rubric($this->db));
		$this->assign('data',$data);
		$this->assign('type','edit_rubric');
		$this->display('frontend_rubric.html');
	}

	/**
	 * 删除栏目
	 * @return [type] [description]
	 */
	public function del_rubric()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_rubric')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$sysid = get_param('id');
		$sql = "select fr_name from ".get_table('frontend_rubric')." where sysid = ".$sysid;
		$result = $this->db->getOne($this->db->Query($sql));
		$name = $result['fr_name'];

		// 检查当前ID的子类
		$data = get_rubric($this->db,$sysid,0,'');
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$idArr[] = $value['sysid'];
				$nameArr[] = str_replace('|——','',substr($value['fr_name'],strpos($value['fr_name'],'|——'))); 
			}
		}

		// 删除记录
		$name  = (count($nameArr) > 0) ? $name.'、'.join('、', $nameArr):$name;
		$sysid = (count($idArr) > 0) ? $sysid.','.join(',', $idArr):$sysid;
		$del_sql = "delete from ".get_table('frontend_rubric')." where sysid in ($sysid)";
		$this->db->Query($del_sql);
		$result = $this->db->AffectedRows();

		if ($result) {

			$this->admin_log("删除栏目 ". $name ." 成功");
			echo json_encode(array('msg'=>'删除栏目 '.$name.' 成功!','data'=>'index.php?module=frontend_rubric&method=rubric_list'));
		} else {

			$this->admin_log("删除栏目 ". $name ." 失败，原因:数据库删除失败");
            echo json_encode(array('msg'=>'删除栏目 '.$name.' 失败,请重试!','data'=>''));
		}
	}
}
?>