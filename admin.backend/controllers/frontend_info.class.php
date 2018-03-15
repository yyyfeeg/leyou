<?php
#============================================
# 	FileName: frontend_info.class.php
# 		Desc: 前端整站信息功能
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Frontend_info extends Controller
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
	 * 添加整站信息
	 * @return [type] [description]
	 */
	public function add_info()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_info')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act = get_param('act');
		$sysid = get_param('id');
		$data['fi_basehost']   = get_param('basehost');
		$data['fi_webname']    = get_param('webname');
		$data['fi_arcdir']     = get_param('arcdir');
		$data['fi_upload_dir'] = get_param('upload_dir');
		$data['fi_powerby']    = get_param('powerby');
		$data['fi_keywords']   = get_param('keywords');
		$data['fi_desc']       = get_param('desc');
		$data['fi_beian']      = get_param('beian');
		$data['fi_indexurl']   = get_param('indexurl');

		if ($act == 'add'){
			// 检查参数
			if (empty($data['fi_basehost'])) {
				showinfo("站点根网址不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}
			if (empty($data['fi_webname'])) {
				showinfo("网站名称不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}
			if (empty($data['fi_arcdir'])) {
				showinfo("文档HTML默认保存路径不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}
			if (empty($data['fi_upload_dir'])) {
				showinfo("图片/上传文件默认路径不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}
			if (empty($data['fi_keywords'])) {
				showinfo("站点默认关键字不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}
			if (empty($data['fi_desc'])) {
				showinfo("站点描述不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}
			if (empty($data['fi_indexurl'])) {
				showinfo("生成首页地址不能为空!", "index.php?module=frontend_info&method=add_info",2);
			}

			if (empty($sysid)) {

				// 当前没有任何记录
				$result = add_record($this->db, 'frontend_info', $data);
				if ($result['rows'] > 0) {
					$this->admin_log("添加前端整站信息成功");
                    showinfo("保存成功!", "index.php?module=frontend_info&method=add_info", 4);
				} else {
					$this->admin_log("添加前端整站信息失败！原因：入库失败!");
                    showinfo("保存失败!", "index.php?module=frontend_info&method=add_info", 4);
				}
			} else {
				
				// 更新数据
				$result = update_record($this->db,'frontend_info',$data,array('sysid'=>$sysid),'',1);
				if ($result) {
					$this->admin_log("更新前端整站信息成功！");
                    showinfo("保存成功!", "index.php?module=frontend_info&method=add_info", 4);
				} else {
					$this->admin_log("更新前端整站信息失败！原因：数据库插入失败");
                    showinfo("保存失败!", "index.php?module=frontend_info&method=add_info", 4);
				}
			}

		} else {
			// 查询原来数据
			$sql = "select * from ".get_table('frontend_info');
			$data = $this->db->getOne($this->db->Query($sql));
		}
		
		$this->assign('data',$data);
		$this->display('frontend_info.html');
	}
}
?>