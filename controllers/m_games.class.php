<?php
#=============================================================
# 	FileName: m_games.class.php
# 		Desc: 游戏列表文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.04.18
# LastChange: 
#    TestUrl: index.php?mo=m_games&me=html_games&pptid=1
#=============================================================

class M_games extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','games');// 导航标识
		$this->assign('menu_title','游戏中心');// 栏目标题
		$this->assign('callback_url',WEBPATH_DIR_INC.HTML_DIR.'/mobile/index.html');// 返回地址
	}

	/**
	 * 游戏列表展示
	 * @return [type] [description]
	 */
	public function html_games()
	{
		// 接收参数
		$pptid = get_param('pptid');// 幻灯片类型ID

		// 幻灯片
		$sql = "select fp_title,fp_url,fp_jurl from ".get_table('frontend_photos')." where fp_typeid = $pptid and fp_status=2 order by fp_order asc,sysid desc limit 3";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$index_ppt[] = array(
				'title' => $rows['fp_title'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'jurl'  => $rows['fp_jurl']
				);
		}

		// 游戏
		$sql = "select gi_gname,gi_icon,gi_description,gi_url,gi_azdlurl,gi_iosdlurl from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_gtype in (1,2,3) order by gi_order asc,sysid desc";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$data['lists'][] = array(
				'gname' => $rows['gi_gname'],
				'icon'  => WEBPATH_DIR_INC.str_replace('..','',$rows['gi_icon']),
				'desc'  => stripslashes($rows['gi_description']),
				'gwurl' => ($rows['gi_url'] == '#')? "javascript:alert('敬请期待！');":$rows['gi_url'],
				'azurl' => ($rows['gi_azdlurl'] == '#')? "javascript:alert('敬请期待！');":$rows['gi_azdlurl'],
				'iosurl'=> ($rows['gi_iosdlurl'] == '#')? "javascript:alert('敬请期待！');":$rows['gi_iosdlurl']
				);
		}
		// 生成json数据文件
		$json_file = WEBPATH_DIR.'/html/json/games_list.json';
		$json_data = json_encode($data);
		if (!file_exists($json_file)) {
			$this->_file->touch($json_file);
		}
		$this->_file->write($json_file,$json_data);
		$ajax = WEBPATH_DIR_INC.'/html/json/games_list.json';
		
		$this->assign('ajax',$ajax);// 数据文件地址
		$this->assign('index_ppt',$index_ppt);
		$this->assign('index_games',$data['lists']);
		$this->display('../m/games_m.html');
	}

	/**
	 * 游戏列表展示
	 */
	/*public function html_games()
	{
		// 接收参数
		$page     = get_param('page','int');// 页数
		$pnum     = get_param('pnum','int');// 页数导航数量
		$article  = get_param('article','int');// 单页条数
		$pptid    = get_param('pptid');// 幻灯片类型ID

		// 幻灯片
		$sql = "select fp_title,fp_url,fp_jurl from ".get_table('frontend_photos')." where fp_typeid = $pptid and fp_status=2 order by fp_order asc,sysid desc limit 3";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$index_ppt[] = array(
				'title' => $rows['fp_title'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'jurl'  => $rows['fp_jurl']
				);
		}

		// 游戏
		$sql = "select gi_gname,gi_icon,gi_description,gi_url,gi_azdlurl,gi_iosdlurl from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_gtype in (2,3) order by gi_order asc,sysid desc";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$data[] = array(
				'gname' => $rows['gi_gname'],
				'icon'  => str_replace('..','',$rows['gi_icon']),
				'desc'  => $rows['gi_description'],
				'gwurl' => $rows['gi_url'],
				'azurl' => $rows['gi_azdlurl'],
				'iosurl'=> $rows['gi_iosdlurl']
				);
		}

		// 切分单页数据
		$news_list = @array_chunk($data, $article);
		$pageNum = ceil(count($data)/$article);

		// 分页导航
		for ($i=0; $i < $pageNum; $i++) { 
			if ($i == 0) {
				$page_menu[] =  WEBPATH_DIR_INC.HTML_DIR.'/mobile/games/index.html';
			} else {
				$page_menu[] = WEBPATH_DIR_INC.HTML_DIR.'/mobile/games/list_'.$i.'.html';
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

		$this->assign('page_flag',$page_flag);
		$this->assign('page',$page);
		$this->assign('page_menu',$page_menu);
		$this->assign('index_page',$index_page);
		$this->assign('last_page',$last_page);
		$this->assign('last_page_flag',$pageNum-1);
		$this->assign('data',$news_list[$page]);
		$this->assign('index_ppt',$index_ppt);
		$this->display('mobile/games.html');
	}*/
}
?>