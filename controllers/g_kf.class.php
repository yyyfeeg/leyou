<?php
#===============================================================
# 	FileName: g_kf.php
# 		Desc: 游戏内悬浮窗客服电话列表
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.17
# LastChange: index.php?mo=g_kf&me=index
#===============================================================

class G_kf extends Controller
{
	private $key = '';// 加密key

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->key = 'teamtop#$3737!tang@ttgfun.com';
		$this->assign('menu_title','帮助中心');// 栏目标题
	}

	/**
	 * 游戏客服电话列表
	 */
	public function index()
	{
		// 接收参数
		$param_v = get_param('v');// 接收V参数
		$param_str = simple_xor(base64_decode($param_v),'ttgfun.com@3737^tang!');
		parse_str($param_str);

		// 验证加密串sign
		$my_sign = md5($uid.$uname.$gid.$uaid.$uwid.$this->key);
		if ($sign != $my_sign || empty($uid) || empty($uname) || empty($gid) || empty($uaid) || empty($uwid)) {
			exit('非法请求');
		}

		// 帮助用户登录
		$_SESSION['_uid'] = $uid;
        $_SESSION['_uname'] = $uname;

		// 游戏客服电话列表
		$sql = "select gi_gname,gi_kfphone from ".get_table('game_info')." where sysid = $gid and gi_status=1 and gi_show=1 and gi_gtype in (2,3)";
		$result = $this->_db->getOne($this->_db->Query($sql));
		$data[] = array(
				'gname' => $result['gi_gname'],
				'phone' => $result['gi_kfphone']
				);
		
		$this->assign('data',$data);
		$this->display('game/customer.html');
	}
}
?>