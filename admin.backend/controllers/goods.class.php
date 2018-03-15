<?php
#============================================
# 	FileName: goods.class.php
# 		Desc: 礼包中心发放物品管理模块
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.03
# LastChange: 
#============================================

class Goods extends Controller
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
	 * 添加物品发放地址
	 */
	public function give_goods()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('give_goods')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act = get_param('act');
		$data['ggg_gid'] = get_param('gid');
		$data['ggg_key'] = get_param('key');
		$data['ggg_str'] = get_param('str');
		$data['ggg_encrypt'] = get_param('encrypt');
		$data['ggg_giveurl'] = get_param('giveurl');
		$data['ggg_getsid']  = get_param('getsid');
		$data['ggg_open']    = get_param('open')? get_param('open'):1;
		$data['ggg_adduid']  = $this->my_admin_id;
		$data['ggg_addtime'] = THIS_DATETIME;

		if ($act == 'add') {
			// 检查参数完整性
			if (empty($data['ggg_gid'])) {
				showinfo("游戏名称不能为空!", "index.php?module=goods&method=give_goods",3);
			}

			if (empty($data['ggg_key'])) {
				showinfo("加密key不能为空!", "index.php?module=goods&method=give_goods",3);
			}

			if (empty($data['ggg_str'])) {
				showinfo("加密串不能为空!", "index.php?module=goods&method=give_goods",3);
			}

			if (empty($data['ggg_encrypt'])) {
				showinfo("请选择加密方式!", "index.php?module=goods&method=give_goods",3);
			}

			if (empty($data['ggg_giveurl'])) {
				showinfo("发放地址不能为空!", "index.php?module=goods&method=give_goods",3);
			}

			if (empty($data['ggg_getsid'])) {
				showinfo("游戏服地址不能为空!", "index.php?module=goods&method=give_goods",3);
			}

			// 添加记录
			$result = add_record($this->db,'giveurl_goods',$data);
			if ($result['rows'] > 0) {
                $this->admin_log("游戏ID：".$data['ggg_gid']."添加物品发放地址信息成功");
                showinfo("添加成功!", "index.php?module=goods&method=give_goods_list", 4);
            } else {
                $this->admin_log("游戏ID：".$data['ggg_gid']."添加物品发放地址信息失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=goods&method=give_goods", 3);
            }
		}

		$this->assign('type','add');
		$this->assign('gameArr',get_game($this->db));
		$this->assign('data',$data);
		$this->display('goods.html');
	}

	/**
	 * 物品发放地址列表
	 */
	public function give_goods_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('give_goods_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$gamesArr = get_game($this->db);
		$unameArr = get_users();

		// 查询数据
		$sql = "select sysid,ggg_gid,ggg_key,ggg_str,ggg_encrypt,ggg_giveurl,ggg_getsid,ggg_open,ggg_adduid,ggg_addtime from ".get_table('giveurl_goods');
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {
			// 操作
			$action = "<a href='index.php?module=goods&method=give_goods_edit&id=".$rows['sysid']."'>[修改]</a>";
			if ($this->isadmin) {
				$action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=goods&method=give_goods_del&id=".$rows['sysid']."'>[删除]</a>";
			}
			$data[] = array(
				$rows['sysid'],
				$gamesArr[$rows['ggg_gid']],
				$rows['ggg_key'],
				$rows['ggg_str'],
				($rows['ggg_encrypt'] == 1)? 'MD5':'SHA1',
				$rows['ggg_giveurl'],
				$rows['ggg_getsid'],
				($rows['ggg_open'] == 1)? '是':'否',
				$unameArr[$rows['ggg_adduid']],
				date('Y-m-d H:i:s',$rows['ggg_addtime']),
				$action
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->display('goods.html');
	}

	/**
	 * 修改物品发放地址
	 */
	public function give_goods_edit()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('give_goods_edit')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act = get_param('act');
		$data['sysid']   = get_param('id');
		$data['ggg_gid'] = get_param('gid');
		$data['ggg_key'] = get_param('key');
		$data['ggg_str'] = get_param('str');
		$data['ggg_encrypt'] = get_param('encrypt');
		$data['ggg_giveurl'] = get_param('giveurl');
		$data['ggg_getsid']  = get_param('getsid');
		$data['ggg_open']    = get_param('open')? get_param('open'):1;
		$data['ggg_upuid']   = $this->my_admin_id;
		$data['ggg_uptime']  = THIS_DATETIME;

		if ($act == 'edit') {
			// 检查参数完整性
			if (empty($data['ggg_gid'])) {
				showinfo("游戏名称不能为空!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'],3);
			}

			if (empty($data['ggg_key'])) {
				showinfo("加密key不能为空!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'],3);
			}

			if (empty($data['ggg_str'])) {
				showinfo("加密串不能为空!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'],3);
			}

			if (empty($data['ggg_encrypt'])) {
				showinfo("请选择加密方式!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'],3);
			}

			if (empty($data['ggg_giveurl'])) {
				showinfo("发放地址不能为空!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'],3);
			}

			if (empty($data['ggg_getsid'])) {
				showinfo("游戏服地址不能为空!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'],3);
			}

			// 更新数据
			$result = update_record($this->db,'giveurl_goods',$data,array('sysid'=>$data['sysid']),'',1);
			if ($result) {
                $this->admin_log("修改物品发放地址 ID：" . $data['sysid'] . " 成功");
                showinfo("修改成功!", "index.php?module=goods&method=give_goods_list", 4);
            } else {
                $this->admin_log("修改物品发放地址 ID：" . $data['sysid'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=goods&method=give_goods_edit&id=".$data['sysid'], 3);
            }
		} else {
			$sql = "select sysid,ggg_gid,ggg_key,ggg_str,ggg_encrypt,ggg_giveurl,ggg_getsid,ggg_open from ".get_table('giveurl_goods')." where sysid = ".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}

		$this->assign('data',$data);
		$this->assign('gameArr',get_game($this->db));
		$this->assign('type','edit');
		$this->display('goods.html');
	}

	/**
	 * 删除物品发放地址
	 */
	public function give_goods_del()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('give_goods_del')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$where['sysid'] = get_param('id');

		$result = delete_record($this->db,'giveurl_goods',$where);
		if ($result) {
			$this->admin_log("删除物品发放地址 ID：". $where['sysid'] ." 成功");
            showinfo("删除物品发放地址 ID：".$where['sysid']." 成功!", "index.php?module=goods&method=give_goods_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除物品发放地址 ID：". $where['sysid'] ." 失败，原因:数据库删除失败");
            showinfo("删除物品发放地址 ID：".$where['sysid']." 失败,请重试!", "", 3);
		}
	}

	/**
	 * 物品领取日志
	 */
	public function draw_goods()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('draw_goods')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$gameArr = get_game($this->db);

		// 接收参数
		$flag = get_param('flag');
		$gid  = get_param('gid');
		$uname = get_param('uname');
		$role  = get_param('role');
		$spree = get_param('spree');

		// 查询物品礼包数据
		$sql = "select sysid,gfs_name from ".get_table('frontend_spree')." where gfs_give = 1";
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {
			$spreeArr[$rows['sysid']] = $rows['gfs_name'];
		}
		
		// 查询数据
		if ($flag == 'get_data') {
			$where = '1';
			$where .= $gid ? " and gdg_gid = $gid" : "";
			$where .= $uname ? " and gdg_uname = '".$uname."'" : "";
			$where .= $role ? " and gdg_rolename = '".$role."'" : "";
			$where .= $spree ? " and gdg_spreeid = $spree" : "";
			$sql = "select * from ".get_table('drawlog_goods')." where $where";
			$query = $this->db->Query($sql);
			while ($rows = $this->db->FetchArray($query)) {
				$data[] = array(
					$rows['sysid'],
					$gameArr[$rows['gdg_gid']],
					$rows['gdg_sid'],
					$rows['gdg_uid'],
					$rows['gdg_uname'],
					$rows['gdg_roleid'],
					$rows['gdg_rolename'],
					$rows['gdg_spreeid'],
					$rows['gdg_spree'],
					$rows['gdg_goodsid'],
					$rows['gdg_goods'],
					$rows['gdg_goodsnum'],
					date('Y-m-d H:i:s',$rows['gdg_drawtime'])
					);
			}
			if (empty($data)) {
				echo json_encode(array('code'=>1001,'msg'=>'没有相匹配的数据'));
			} else {
				echo json_encode($data);
			}
			exit;
		}

		$this->assign('gameArr',$gameArr);
		$this->assign('spreeArr',$spreeArr);
		$this->assign('type','logs_list');
		$this->display('goods.html');
	}
}
?>