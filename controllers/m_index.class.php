<?php
#============================================
# 	FileName: m_index.php
# 		Desc: 移动端首页控制器文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.17
# LastChange: index.php?mo=m_index&pptid=4
#============================================

class M_index extends Controller
{
	private $key = '';// 干扰加密字串

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','index');// 导航标识
		$this->assign('menu_title','首页');// 栏目标题

		$this->key = '3737CRM_tangzhifeng_@#&^teamtopgame';
	}

	/**
	 * 首页数据展示处理
	 * @return [type] [description]
	 */
	public function show_index()
	{
		$pptid = get_param('pptid');// 首页幻灯片图片类型ID

		// 首页幻灯片
		$sql = "select fp_title,fp_url,fp_jurl from ".get_table('frontend_photos')." where fp_typeid = $pptid and fp_status=2 order by fp_order asc,sysid desc limit 3";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$index_ppt[] = array(
				'title' => $rows['fp_title'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'jurl'  => $rows['fp_jurl']
				);
		}
		// 游戏推荐
		$sql = "select sysid,gi_gname,gi_icon,gi_description,gi_url,gi_azdlurl,gi_iosdlurl from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_gtype in (1,2,3) and gi_virtue like '%3%' order by gi_order asc,sysid desc limit 4";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$index_game[] = array(
				'gname' => $rows['gi_gname'],
				'icon'  => str_replace('..','',$rows['gi_icon']),
				'desc'  => $rows['gi_description'],
				'gwurl' => $rows['gi_url'],
				'ginfo' => WEBPATH_DIR_INC.'/html/mobile/ginfo/'.$rows['sysid'].'.html',
				'azurl' => ($rows['gi_azdlurl'] == '#')? "javascript:alert('敬请期待！');":$rows['gi_azdlurl'],
				'iosurl'=> ($rows['gi_iosdlurl'] == '#')? "javascript:alert('敬请期待！');":$rows['gi_iosdlurl'],
				);
		}

		$this->assign('index_game',$index_game);
		$this->assign('index_ppt',$index_ppt);
		$this->display('../m/index_m.html');
	}

	/**
	 * 首页展示热门礼包
	 */
	public function show_hot_gifts()
	{
		// 用户已登录，查询已领取过的礼包
		if ($this->check_login()) {
			$spreeid_Arr = get_draw_spreeid($this->_db,$_SESSION['_uid']);
		} else {
			$spreeid_Arr = array();
		}

		// 热门礼包
		// $gameArr = get_game_info($this->_db);
		$sql = "select sysid,gfs_name,gfs_gid,gfs_desc,gfs_integral,gfs_vip,gfs_give,gfs_photo,gfs_goodsid,gfs_goodsnum from ".get_table('frontend_spree')." where gfs_open=1 and gfs_hot=1 order by sysid desc limit 4";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			
			// 是否还有领取机会
			if (!empty($spreeid_Arr) && in_array($rows['sysid'],$spreeid_Arr)) {
				if ($rows['gfs_integral'] == 0) {
					continue;
				}
			}

			// 领取礼包地址参数加密
			if ($rows['gfs_give'] == 1) { // 游戏内物品
				$gurl_sign = md5($this->key.THIS_DATETIME.$rows['sysid'].$rows['gfs_integral'].$rows['gfs_vip'].$rows['gfs_give'].$rows['gfs_goodsid'].$rows['gfs_goodsnum']);
				$gurl_str = "sign=$gurl_sign&time=".THIS_DATETIME."&id=".$rows['sysid']."&inte=".$rows['gfs_integral']."&vip=".$rows['gfs_vip']."&give=".$rows['gfs_give']."&goodsid=".$rows['gfs_goodsid']."&goodsnum=".$rows['gfs_goodsnum'];
			} else { // CDKEY
				$gurl_sign = md5($this->key.THIS_DATETIME.$rows['sysid'].$rows['gfs_integral'].$rows['gfs_vip'].$rows['gfs_give']);
				$gurl_str = "sign=$gurl_sign&time=".THIS_DATETIME."&id=".$rows['sysid']."&inte=".$rows['gfs_integral']."&vip=".$rows['gfs_vip']."&give=".$rows['gfs_give'];
			}
			$gurl_v = base64_encode(simple_xor($gurl_str,'teamtop@ttgfun.com^$Tang'));

			$index_lb[] = array(
				'desc'   => $rows['gfs_desc'],
				'photo'  => WEBPATH_DIR_INC.str_replace('..','',$rows['gfs_photo']),
				'name'   => $rows['gfs_name'],
				'lq_url' => WEBPATH_DIR_INC.'/api/gifts.api.php?v='.$gurl_v,
				'c_url'  => WEBPATH_DIR_INC.'/index.php?mo=m_gifts&me=gifts_content&id='.$rows['sysid'],
				'id'     => $rows['gfs_give'],
				'gid'    => $rows['gfs_gid']
				);
		}
		$res = array('code'=>'1000','msg'=>'success','data'=>$index_lb);
		exit(json_encode($res));
	}

}
?>