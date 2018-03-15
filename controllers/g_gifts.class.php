<?php
#================================================================
# 	FileName: g_gifts.class.php
# 		Desc: 游戏内悬浮窗礼包中心类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.04.18
# LastChange: 
#    TestUrl: index.php?mo=g_gifts&me=index
#================================================================

class G_gifts extends Controller
{
	private $key = '';// 干扰加密字串
	private $pageKey = '';// 验证请求的key
	private $spreeid_Arr = '';// 领取过的礼包ID

	public function __construct()
	{
		parent::__construct();
		$this->key = '3737CRM_tangzhifeng_@#&^teamtopgame';
		$this->pageKey = 'teamtop#$3737!tang@ttgfun.com';
		$this->assign('menu_title','礼包中心');// 栏目标题

		// 用户已登录，查询已领取过的礼包
		if ($this->check_login()) {
			$this->spreeid_Arr = get_draw_spreeid($this->_db,$_SESSION['_uid']);
		} else {
			$this->spreeid_Arr = array();
		}

	}

	/**
	 * 展示相关礼包列表
	 */
	public function gifts_list()
	{
		// 接收参数
		$v_param = get_param('v');
		$token = get_param('token');

		// 异步获取数据
		if (!empty($v_param)) {
			// 解析参数
			$param_str = simple_xor(base64_decode($v_param),'ttgfun$^3737@Tang');
			parse_str($param_str);
			$my_sign = md5($this->key.$time);

			// 判断签名是否正确
			if ($sign != $my_sign) {
				$res = array('code'=>'-1','msg'=>'非法请求');
				exit(json_encode($res));
			}

			// 查询数据
			if ($ajax == 1) {

				$gameArr = get_game_info($this->_db);

				$where = '';
				if (!empty($gid)) {
					$where .= " and gfs_gid = $gid";
				}
				if (!empty($id)) {
					$where .= " and sysid != $id";
				}

				$sql = "select sysid,gfs_name,gfs_gid,gfs_desc,gfs_integral,gfs_vip,gfs_give,gfs_goods,gfs_goodsid,gfs_goodsnum from ".get_table('frontend_spree')." where gfs_open=1 $where order by sysid desc";
				$query = $this->_db->Query($sql);
				while ($rows = $this->_db->FetchArray($query)) {

					// 是否还有领取机会
					$draw_flag = true;
					if (!empty($this->spreeid_Arr) && in_array($rows['sysid'],$this->spreeid_Arr)) {
						if ($rows['gfs_integral'] == 0) {
							$draw_flag = false;
						}
					}

					// 领取礼包地址参数加密
					if ($rows['gfs_give'] == 1) {
						$gurl_sign = md5($this->key.THIS_DATETIME.$rows['sysid'].$rows['gfs_integral'].$rows['gfs_vip'].$rows['gfs_give'].$rows['gfs_goodsid'].$rows['gfs_goodsnum']);
						$gurl_str = "sign=$gurl_sign&time=".THIS_DATETIME."&id=".$rows['sysid']."&inte=".$rows['gfs_integral']."&vip=".$rows['gfs_vip']."&give=".$rows['gfs_give']."&goodsid=".$rows['gfs_goodsid']."&goodsnum=".$rows['gfs_goodsnum'];
					} else {
						$gurl_sign = md5($this->key.THIS_DATETIME.$rows['sysid'].$rows['gfs_integral'].$rows['gfs_vip'].$rows['gfs_give']);
						$gurl_str = "sign=$gurl_sign&time=".THIS_DATETIME."&id=".$rows['sysid']."&inte=".$rows['gfs_integral']."&vip=".$rows['gfs_vip']."&give=".$rows['gfs_give'];
					}
					$gurl_v = base64_encode(simple_xor($gurl_str,'teamtop@ttgfun.com^$Tang'));
					
					// 前端数据
					$data['lists'][] = array(
						'gift' => $rows['gfs_name'],
						'icon' => WEBPATH_DIR_INC.str_replace('..', '', $gameArr[$rows['gfs_gid']]['icon']),
						'desc' => stripslashes($rows['gfs_desc']),
						'inte' => $rows['gfs_integral'],
						'vip'  => $rows['gfs_vip'],
						'curl' => WEBPATH_DIR_INC.'/index.php?mo=g_gifts&me=index&id='.$rows['sysid'].'&v='.$token,
						'gurl' => WEBPATH_DIR_INC.'/api/gifts.api.php?v='.$gurl_v,
						'id'   => $rows['gfs_give'],
						'gid'  => $rows['gfs_gid'],
						'draw' => $draw_flag
						);
				}
				$res = array('code'=>'1','msg'=>'success','data'=>$data);
				exit(json_encode($res));

			} else {
				$res = array('code'=>'-1','msg'=>'非法请求');
				exit(json_encode($res));
			}
		}
	}

	/**
	 * 展示礼包内容页面
	 */
	public function index()
	{
		// 接收参数
		$draw_flag = true;
		$sysid = get_param('id','int');
		$param_v = get_param('v');// 接收V参数
		$param_str = simple_xor(base64_decode($param_v),'ttgfun.com@3737^tang!');
		parse_str($param_str);

		// 验证加密串sign
		$my_sign = md5($uid.$uname.$gid.$uaid.$uwid.$this->pageKey);
		if ($sign != $my_sign || empty($uid) || empty($uname) || empty($gid) || empty($uaid) || empty($uwid)) {
			exit('非法请求');
		}

		// 帮助用户登录
		$_SESSION['_uid'] = $uid;
        $_SESSION['_uname'] = $uname;

        // 查询用户领取过的礼包
		$this->spreeid_Arr = get_draw_spreeid($this->_db,$_SESSION['_uid']);

		$where = "gfs_gid = $gid";
		if (empty($sysid)) {
			// 查询排序最大的礼包
			$where .= " and gfs_open=1 order by gfs_order desc";
		} else {
			$where .= " and sysid = $sysid and gfs_open=1";
		}

		// 查询当前礼包内容数据
		$sql = "select sysid,gfs_name,gfs_photo,gfs_content,gfs_integral,gfs_vip,gfs_gid,gfs_give,gfs_goods,gfs_goodsid,gfs_goodsnum from ".get_table('frontend_spree')." where $where";
		$result = $this->_db->getOne($this->_db->Query($sql));
		if ($result) {

			// 是否还有领取机会
			if (!empty($this->spreeid_Arr) && in_array($result['sysid'],$this->spreeid_Arr)) {
				if ($result['gfs_integral'] == 0) {
					$draw_flag = false;
				}
			}

			// 领取礼包地址参数加密
			if ($result['gfs_give'] == 1) {
				$gurl_sign = md5($this->key.THIS_DATETIME.$result['sysid'].$result['gfs_integral'].$result['gfs_vip'].$result['gfs_give'].$result['gfs_goodsid'].$result['gfs_goodsnum']);
				$gurl_str = "sign=$gurl_sign&time=".THIS_DATETIME."&id=".$result['sysid']."&inte=".$result['gfs_integral']."&vip=".$result['gfs_vip']."&give=".$result['gfs_give']."&goodsid=".$result['gfs_goodsid']."&goodsnum=".$result['gfs_goodsnum'];
			} else {
				$gurl_sign = md5($this->key.THIS_DATETIME.$result['sysid'].$result['gfs_integral'].$result['gfs_vip'].$result['gfs_give']);
				$gurl_str = "sign=$gurl_sign&time=".THIS_DATETIME."&id=".$result['sysid']."&inte=".$result['gfs_integral']."&vip=".$result['gfs_vip']."&give=".$result['gfs_give'];
			}
			$gurl_v = base64_encode(simple_xor($gurl_str,'teamtop@ttgfun.com^$Tang'));

			// 相关礼包异步请求地址
			$sign  = md5($this->key.THIS_DATETIME);
			$str   = "sign=$sign&ajax=1&time=".THIS_DATETIME."&gid=".$result['gfs_gid']."&id=".$result['sysid'];
			$v_str = base64_encode(simple_xor($str,'ttgfun$^3737@Tang'));
			$ajax  = WEBPATH_DIR_INC."/index.php?mo=g_gifts&me=gifts_list&v=$v_str&token=$param_v";

			// 礼包内容数据
			$data = array(
				'gift' => $result['gfs_name'],
				'photo'=> WEBPATH_DIR_INC.str_replace('..', '',$result['gfs_photo']),
				'inte' => $result['gfs_integral'],
				'vip'  => $result['gfs_vip'],
				'content' => stripslashes($result['gfs_content']),
				'gurl' => WEBPATH_DIR_INC.'/api/gifts.api.php?v='.$gurl_v,
				'ajax' => $ajax,
				'id'   => $result['gfs_give'],
				'gid'  => $result['gfs_gid'],
				'draw' => $draw_flag
				);
		}

		$this->assign('data',$data);
		$this->display('game/gifts_content.html');
	}
}
?>