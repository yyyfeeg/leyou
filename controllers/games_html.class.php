<?php
#===================================================================================================
# 	FileName: games_html.class.php
# 		Desc: 游戏列表类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.26
# LastChange: 
#    TestUrl: index.php?mo=games_html&me=games_list&page=0&pnum=3&article=2
#====================================================================================================

class Games_html extends Controller
{
	/**
	 * 构造函数，并初始化右侧信息
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','games');//导航选中标识
		$this->assign('games_list_left',$this->hot_games());//左侧热门游戏
		$this->assign('menu_mobile_games',$this->get_games(2,10));//导航手游
		$this->assign('menu_user_games',$this->get_user_game(10));//用户最近登录游戏
		$this->assign('login_flag',$this->check_login());//是否登录标识
	}

	/**
	 * 生成游戏列表
	 * @return [type] [description]
	 */
	public function games_list()
	{
		$page    = get_param('page','int')?get_param('page','int'):0;// 页数
		$pnum    = get_param('pnum','int')?get_param('pnum','int'):3;// 页数导航数量
		$article = get_param('article','int')?get_param('article','int'):6;// 单页条数

		// 获取游戏详细信息
		$arr_gtype = array('1' => '网页游戏','2' => '手机游戏','3' => '双端游戏');//游戏类型
		$arr_rating = array('0' => '★','1' => '★','2' => '★★','3' => '★★★','4' => '★★★★','5' => '★★★★★');//评价星级
		$sql = "select gi_gname,gi_virtue,gi_gtype,gi_rating,gi_description,gi_photo,gi_icon,gi_azewm,gi_iosewm,gi_url from ".get_table('game_info')." where gi_status=1 and gi_show=1 order by gi_order asc,sysid desc";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			// 幻灯片
			if (in_array('4',explode(',', $rows['gi_virtue'])) && count($ppt_array) < 4) {
				$ppt_array[] = array(
					'title'  => $rows['gi_gname'],
					'virtue' => in_array('3',explode(',',$rows['gi_virtue'])) ? 'true':'false',
					'jurl'   => $rows['gi_url'],
					'rating' => $arr_rating[$rows['gi_rating']],
					'sphoto' => str_replace('..','',$rows['gi_icon']),
					'bphoto' => str_replace('..','',$rows['gi_photo']),
					'desc'   => $rows['gi_description'],
					'url'    => $rows['gi_url']
				);
			}
			$games_list[] = array(
				'gname'  => $rows['gi_gname'],
				'gtype'  => $arr_gtype[$rows['gi_gtype']],
				'rating' => $arr_rating[$rows['gi_rating']],
				'photo'  => str_replace('..','',$rows['gi_photo']),
				'desc'   => $rows['gi_description'],
				'azewm'  => str_replace('..','',$rows['gi_azewm']),
				'iosewm' => str_replace('..','',$rows['gi_iosewm']),
				'url'    => $rows['gi_url']
			);
		}
		$pageNum = ceil(count($games_list)/$article);// 列表总页数
		$games_list = @array_chunk($games_list, $article);// 切分数据条数

		// 分页导航
		for ($i=0; $i < $pageNum; $i++) { 
			if ($i == 0) {
				$page_menu[] = WEBPATH_DIR_INC.'/html/games/index.html';
			} else {
				$page_menu[] = WEBPATH_DIR_INC."/html/games/list/list_".$i.".html";
			}
		}

		// 提取首页和尾页
		if (count($page_menu) > 1) {
			$page_flag = 'true';
			$index_page = array_shift($page_menu);
			$last_page  = array_pop($page_menu);
		}

		// 分页导航数量控制
		$tab = floor($page/($pnum+1));
		$page_menu = @array_chunk($page_menu, $pnum, true);
		$page_menu = $page_menu[$tab];

		// 分页控制变量
		$this->assign('page_flag',$page_flag);
		$this->assign('page',$page);
		$this->assign('page_menu',$page_menu);
		$this->assign('index_page',$index_page);
		$this->assign('last_page',$last_page);
		$this->assign('last_page_flag',$pageNum-1);
		$this->assign('ppt_array',$ppt_array);
		$this->assign('games_list',$games_list[$page]);
		$this->display('games_center.html');
	}

	/**
	 * 获取左侧热门游戏
	 * @return [type] [description]
	 */
	private function hot_games()
	{
		$sql = "select gi_gname,gi_icon,gi_rating,gi_url from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_virtue like '%3%' order by gi_order asc,sysid desc limit 10";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$games_list_left[] = array(
				'gname'  => $rows['gi_gname'],
				'icon'   => str_replace('..','',$rows['gi_icon']),
				'rating' => $rows['gi_rating'],
				'url'    => $rows['gi_url']
			);
		}
		return $games_list_left;
	}
}
?>