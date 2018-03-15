<?php
#=========================================================================================
# 	FileName: index_html.class.php
# 		Desc: 生成首页控制器文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.21
# LastChange: 
#    TestUrl: index.php?mo=index_html&me=html_index&hppt_tid=&gppt_tid=&rid=&hgame_tid=
#=========================================================================================

class Index_html extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','index');//导航选中标识
		$this->assign('menu_mobile_games',$this->get_games(2,10));//导航手游
		$this->assign('login_flag',$this->check_login());//是否登录标识
	}

	/**
	 * 显示首页内容
	 * @return [type] [description]
	 */
	public function html_index()
	{
		$hppt_typeid    = get_param('hppt_tid','int')?get_param('hppt_tid','int'):1;// 首页头部幻灯片图片类型ID
		$gppt_typeid    = get_param('gppt_tid','int')?get_param('gppt_tid','int'):2;// 首页游戏动态-幻灯片图片类型ID
		$gnews_rubricid = get_param('rid','int')?get_param('rid','int'):2;// 首页游戏新闻栏目ID
		$hgame_typeid   = get_param('hgame_tid','int')?get_param('hgame_tid','int'):3;// 首页热点游戏图片类型ID

		$dir = get_rubric_dir($this->_db,$gnews_rubricid);// 栏目文件保存目录

		// 获取首页头部幻灯片
		$sql = "select fp_title,fp_url,fp_jurl from ".get_table('frontend_photos')." where fp_typeid=$hppt_typeid and fp_status=2 order by fp_order asc,sysid desc limit 4";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$index_ppt[] = array(
				'title' => $rows['fp_title'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'jurl'  => $rows['fp_jurl']
				);
		}

		// 游戏动态-幻灯片
		$sql = "select fp_title,fp_desc,fp_url,fp_jurl from ".get_table('frontend_photos')." where fp_typeid=$gppt_typeid and fp_status=2 order by fp_order asc,sysid desc limit 4";
		$query = $this->_db->Query($sql);
		$gid = 1;
		while ($rows = $this->_db->FetchArray($query)) {
			$games_ppt[] = array(
				'gid'   => $gid,
				'title' => $rows['fp_title'],
				'desc'  => $rows['fp_desc'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'jurl'  => $rows['fp_jurl']
				);
			$gid ++;
		}

		// 游戏新闻
		$sql = "select sysid,fe_title,fe_virtue,fe_jurl,fe_printtime from ".get_table('frontend_essay')." where fe_rubricid=$gnews_rubricid and fe_status=2 and fe_showtype in (2,3) and (fe_virtue like '%1%' or fe_virtue like '%2%') order by fe_order asc,sysid desc limit 6";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			// 头条
			if (in_array('1',explode(',',$rows['fe_virtue'])) && empty($headerArr)) {
				$headerArr[] = array(
					'sysid'  => $rows['sysid'],
					'title'  => $rows['fe_title'],
					'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
					'jurl'   => $rows['fe_jurl'],
					'time'   => strtotime($rows['fe_printtime']),
					'url'    => WEBPATH_DIR_INC.HTML_DIR.$dir.'/'.$rows['sysid'].'.html'
					);
				continue;
			}
			// 推荐
			$game_dynamic[] = array(
				'sysid'  => $rows['sysid'],
				'title'  => $rows['fe_title'],
				'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
				'jurl'   => $rows['fe_jurl'],
				'time'   => strtotime($rows['fe_printtime']),
				'url'    => WEBPATH_DIR_INC.HTML_DIR.$dir.'/'.$rows['sysid'].'.html'
				);
		}
		
		if (empty($headerArr)) $headerArr[] = @array_shift($game_dynamic);// 如果没有头条，默认取第一条为头条

		// 热点游戏
		$sql = "select (@i := @i +1) AS rowid,fp_title,fp_url,fp_jurl,fp_desc,fp_sphoto from ".get_table('frontend_photos')." ,(SELECT @i :=0) AS it where fp_typeid=$hgame_typeid and fp_status=2 order by fp_order asc,sysid desc limit 3";
		$query  = $this->_db->Query($sql);
		$hot_id = $hot_ar = 0;
		while ($rows = $this->_db->FetchArray($query)) {
			$key = "hot_".$hot_ar;
			$hot_games[$key][] = array(
				'rowid' => $rows['rowid'],
				'title' => $rows['fp_title'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'qrurl' => str_replace('..','',$rows['fp_sphoto']),
				'jurl'  => $rows['fp_jurl'],
				'desc'  => $rows['fp_desc']
			);
			$hot_id++;
			if ($hot_id == 6) {
				$hot_ar ++;
				$hot_id = 0;
			}
		}

		// 全部游戏
		$sql = "select gi_gname,gi_icon,gi_url,gi_azdlurl from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_gtype in (1,2,3) and gi_virtue like '%3%' order by gi_order asc,sysid desc limit 12";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$mobile_game[] = array(
				'gname' => $rows['gi_gname'],
				'icon'  => str_replace('..','',$rows['gi_icon']),
				'gwurl' => $rows['gi_url'],
				'azurl' => $rows['gi_azdlurl']
				);
		}
		
		// 网页游戏
		/*$sql = "select gi_gname,gi_icon,gi_url,gi_azdlurl from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_gtype in (1,3) and gi_virtue like '%3%' order by gi_order asc,sysid desc limit 6";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$web_game[] = array(
				'gname' => $rows['gi_gname'],
				'icon'  => str_replace('..','',$rows['gi_icon']),
				'gwurl' => $rows['gi_url'],
				'azurl' => $rows['gi_azdlurl']
				);
		}*/

		// 手机游戏
		/*$sql = "select gi_gname,gi_icon,gi_url,gi_azdlurl from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_gtype in (2,3) and gi_virtue like '%3%' order by gi_order asc,sysid desc limit 12";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$mobile_game[] = array(
				'gname' => $rows['gi_gname'],
				'icon'  => str_replace('..','',$rows['gi_icon']),
				'gwurl' => $rows['gi_url'],
				'azurl' => $rows['gi_azdlurl']
				);
		}*/

		$this->assign('index_ppt',$index_ppt);
		$this->assign('games_ppt',$games_ppt);
		$this->assign('game_dynamic',$game_dynamic);
		$this->assign('headerArr',$headerArr);
		$this->assign('hot_games',$hot_games);
		$this->assign('web_game',$web_game);
		$this->assign('mobile_game',$mobile_game);
		$this->display('index.html');
	}
}
?>