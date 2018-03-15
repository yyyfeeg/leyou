<?php
#============================================
# 	FileName: attest_tel.class.php
# 		Desc: 手机认证日志功能
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Attest_tel extends Controller
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

		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('attest_tel')) {
			showinfo("你没有权限执行该操作。","",2);
		}
	}

	/**
	 * 手机认证记录列表
	 * @return [type] [description]
	 */
	public function tel_list()
	{
		// 接收参数
		$start_time = get_param('start_time');
		$end_time   = get_param('end_time');
		$gameid     = get_param('gid');
		$event      = get_param('event');
		$status     = get_param('status');
		$verify     = get_param('verify');
		$flag       = get_param('flag');

		if (strtotime($start_time) > strtotime($end_time)) {
			list($start_time,$end_time) = array($end_time,$start_time);
		}

		if ($flag == 'get_data') {

			$where = 1;
			if (!empty($start_time)) {
				$where .= " and at_time >= ".strtotime($start_time);
			}
			if (!empty($end_time)) {
				$where .= " and at_time <= ".strtotime($end_time);
			}
			if ($gameid != '') {
				$where .= " and at_gid = $gameid";
			}
			if (!empty($event)) {
				$where .= " and at_event = $event";
			}
			if ($status != '') {
				$where .= " and at_status = $status";
			}
			if (!empty($verify)) {
				$where .= " and at_verify = $verify";
			}

			// 用户列表
			$user_list = get_users();
			$statusArr = array('发送中','成功','失败','已发送至用户手机','发送至用户手机失败');
			$verifyArr = array('已领取','已验证','未验证','超时');

			// 查询数据
			$sql   = "select * from ".get_table('attest_tel')." where $where";
			$query = $this->db->Query($sql);
			while ($rows = $this->db->FetchArray($query)) {
				$rows['at_time']   = date('Y-m-d H:i:s', $rows['at_time']);
				$rows['at_uid']    = ($rows['at_uid'] == 0) ? '系统':$user_list[$rows['at_uid']];
				$rows['at_bulk']   = ($rows['at_bulk'] == 1) ? '单发':'群发';
				$rows['at_status'] = $statusArr[$rows['at_status']];
				$rows['at_verify'] = $verifyArr[$rows['at_verify']];
				$rows['at_type']   = ($rows['at_type'] == 0) ? '验证码':'';

				$data[] = array(
					$rows['sysid'],
					$rows['at_tel'],
					$rows['at_contents'],
					$rows['at_type'],
					$rows['at_time'],
					$rows['at_status'],
					$rows['at_bulk'],
					$rows['at_uid'],
					$rows['at_ip'],
					$rows['at_verify']
					);
			}
			if (empty($data)) {
				echo json_encode(array('code'=>1001,'msg'=>'没有相匹配的数据'));
			} else {
				echo json_encode($data);
			}
			exit;
		}

		$this->assign('gameArr',get_game($this->db));
		$this->assign('type','list');
		$this->assign('meg','手机认证日志列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->display('attest_tel.html');
	}
}
?>