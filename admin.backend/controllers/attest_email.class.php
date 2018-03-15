<?php
#============================================
# 	FileName: attest_email.class.php
# 		Desc: 邮箱认证日志功能
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Attest_email extends Controller
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
	 * 邮箱认证记录列表
	 * @return [type] [description]
	 */
	public function email_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('email_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 用户列表
		$user_list = get_users();
		$statusArr = array('发送中','成功','失败');
		$verifyArr = array('已领取','已验证','未验证','超时');

		// 查询数据
		$sql   = "select * from ".get_table('attest_email');
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {
			$rows['ae_time']   = date('Y-m-d H:i:s', $rows['ae_time']);
			$rows['ae_uid']    = ($rows['ae_uid'] == 0) ? '系统':$user_list[$rows['ae_uid']];
			$rows['ae_bulk']   = ($rows['ae_bulk'] == 1) ? '单发':'群发';
			$rows['ae_status'] = $statusArr[$rows['ae_status']];
			$rows['ae_verify'] = $verifyArr[$rows['ae_verify']];
			$rows['ae_type']   = ($rows['ae_type'] == 0) ? '验证邮箱':'';

			$data[] = array(
				$rows['sysid'],
				$rows['ae_email'],
				$rows['ae_contents'],
				$rows['ae_type'],
				$rows['ae_time'],
				$rows['ae_status'],
				$rows['ae_bulk'],
				$rows['ae_uid'],
				$rows['ae_ip'],
				$rows['ae_verify']
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->assign('meg','邮箱认证日志列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->display('attest_email.html');
	}
}
?>