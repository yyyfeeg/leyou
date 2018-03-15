<?php
#============================================
# 	FileName: grow.class.php
# 		Desc: vip成长值查询
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.07.25
# LastChange: 
#============================================

class Gw_count extends Controller
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
	 * VIP成长值查询
	 */
	public function vip_grow()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('vip_grow')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$starttime 	= get_param('starttime');
		$endtime 	= get_param('endtime');
		$uid 		= get_param('uid');
		$uname		= get_param('uname');
		$source 	= get_param('source');
		$flag 		= get_param('flag');

		if (strtotime($starttime) > strtotime($endtime)) {
			list($starttime,$endtime) = array($endtime,$starttime);
		}

		if ($flag == 'get_data') {
			$where = '1';
			if (!empty($starttime)) {
				$where .= " and gl_time >= ".strtotime($starttime);
			}
			if (!empty($endtime)) {
				$where .= " and gl_time <= ".strtotime($endtime);
			}
			if (!empty($uid)) {
				$where .= " and gl_uid = $uid";
			}
			if (!empty($uname)) {
				$where .= " and gl_uname = '".$uname."'";
			}
			if (!empty($source)) {
				$where .= " and gl_source = $source";
			}

			$sql = "select * from `dcenter_base`.gsys_growing_log where $where";
			$query = $this->db->Query($sql);
			while ($rows = $this->db->FetchArray($query)) {
				$data[] = array(
					date('Y-m-d H:i:s',$rows['gl_time']),
					$rows['gl_uid'],
					$rows['gl_uname'],
					'+'.$rows['gl_value'],
					$rows['gl_tvalue'],
					($rows['gl_source'] == 1) ? '充值':'活动'
					);
			}
			if (empty($data)) {
				echo json_encode(array('code'=>1001,'msg'=>'没有相匹配的数据'));
			} else {
				echo json_encode($data);
			}
			exit;
		}

		$this->assign('type','list');
		$this->assign('flag','vip_grow');
		$this->display('gw_count.html');
	}

	/**
	 * 账号积分查询
	 */
	public function user_integral()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('user_integral')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$type = array('1'=>'充值','2'=>'活动','3'=>'任务','4'=>'礼包','5'=>'签到');

		// 接收参数
		$starttime 	= get_param('starttime');
		$endtime 	= get_param('endtime');
		$uid 		= get_param('uid');
		$uname		= get_param('uname');
		$source 	= get_param('source');
		$flag 		= get_param('flag');

		if (strtotime($starttime) > strtotime($endtime)) {
			list($starttime,$endtime) = array($endtime,$starttime);
		}

		if ($flag == 'get_data') {
			$where = '1';
			if (!empty($starttime)) {
				$where .= " and il_time >= ".strtotime($starttime);
			}
			if (!empty($endtime)) {
				$where .= " and il_time <= ".strtotime($endtime);
			}
			if (!empty($uid)) {
				$where .= " and il_uid = $uid";
			}
			if (!empty($uname)) {
				$where .= " and il_uname = '".$uname."'";
			}
			if (!empty($source)) {
				$where .= " and il_source = $source";
			}

			$sql = "select * from `dcenter_base`.gsys_integral_log where $where";
			$query = $this->db->Query($sql);
			while ($rows = $this->db->FetchArray($query)) {
				$data[] = array(
					date('Y-m-d H:i:s',$rows['il_time']),
					$rows['il_uid'],
					$rows['il_uname'],
					($rows['il_itype'] == 1)? '+'.$rows['il_integral'] : '-'.$rows['il_integral'],
					$rows['il_tintegral'],
					$rows['il_content'],
					$type[$rows['il_source']],
				);
			}
			if (empty($data)) {
				echo json_encode(array('code'=>1001,'msg'=>'没有相匹配的数据'));
			} else {
				echo json_encode($data);
			}
			exit;
		}

		$this->assign('type','list');
		$this->assign('flag','user_integral');
		$this->display('gw_count.html');
	}
}
?>