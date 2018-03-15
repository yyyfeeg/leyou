<?php
#=============================================================================================
# 	FileName: news_html.class.php
# 		Desc: 新闻类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.26
# LastChange: 
#    TestUrl: index.php?mo=news_html&me=html_news&page=0&id=1&pnum=3&article=2
#=============================================================================================

class News_html extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','news');//导航选中标识
		$this->assign('menu_mobile_games',$this->get_games(2,10));//导航手游
		$this->assign('menu_user_games',$this->get_user_game(10));//用户最近登录游戏
		$this->assign('login_flag',$this->check_login());//是否登录标识
	}

	/**
	 * 动态列表
	 * @return [type] [description]
	 */
	public function html_news()
	{
		$page     = get_param('page','int')?get_param('page','int'):0;// 页数
		$pnum     = get_param('pnum','int')?get_param('pnum','int'):3;// 页数导航数量
		$article  = get_param('article','int')?get_param('article','int'):6;// 单页条数
		$rubricid = get_param('id','int');// 栏目ID

		$dir = get_rubric_dir($this->_db,$rubricid);// 栏目文件保存目录
		$template = get_rubric_keyvalue($this->_db,3);// 生成模板
		$sql = "select sysid,fe_title,fe_virtue,fe_jurl,fe_sphoto,fe_bphoto,fe_desc,fe_rubricid from ".get_table('frontend_essay')." where fe_status=2 and fe_rubricid=$rubricid and fe_showtype in (2,3) order by fe_order asc,sysid desc";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			// 幻灯片
			if (in_array('4',explode(',', $rows['fe_virtue'])) && count($ppt_array) < 4) {
				$ppt_array[] = array(
					'title'  => $rows['fe_title'],
					'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
					'jurl'   => $rows['fe_jurl'],
					'sphoto' => str_replace('..','',$rows['fe_sphoto']),
					'bphoto' => str_replace('..','',$rows['fe_bphoto']),
					'desc'   => $rows['fe_desc'],
					'url'    => WEBPATH_DIR_INC.HTML_DIR.$dir.'/'.$rows['sysid'].'.html'
					);
			}
			// 右侧头条
			if (in_array('1',explode(',', $rows['fe_virtue'])) && count($headlines) < 2) {
				$headlines[] = array(
					'title'  => $rows['fe_title'],
					'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
					'jurl'   => $rows['fe_jurl'],
					'sphoto' => str_replace('..','',$rows['fe_sphoto']),
					'bphoto' => str_replace('..','',$rows['fe_bphoto']),
					'desc'   => $rows['fe_desc'],
					'url'    => WEBPATH_DIR_INC.HTML_DIR.$dir.'/'.$rows['sysid'].'.html'
					);
				continue;
			}

			// 列表数据
			$data[] = array(
				'title'  => $rows['fe_title'],
				'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
				'jurl'   => $rows['fe_jurl'],
				'sphoto' => str_replace('..','',$rows['fe_sphoto']),
				'bphoto' => str_replace('..','',$rows['fe_bphoto']),
				'desc'   => $rows['fe_desc'],
				'url'    => WEBPATH_DIR_INC.HTML_DIR.$dir.'/'.$rows['sysid'].'.html'
				);
		}
		// 切分单页数据
		$news_list = @array_chunk($data, $article);
		$pageNum = ceil(count($data)/$article);

		// 分页导航
		for ($i=0; $i < $pageNum; $i++) { 
			if ($i == 0) {
				$page_menu[] =  WEBPATH_DIR_INC.HTML_DIR.$dir.'/index.html';
			} else {
				$page_menu[] = WEBPATH_DIR_INC.HTML_DIR.$dir."/list_".$i.".html";
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
		
		$this->assign('ppt_array',$ppt_array);
		$this->assign('headlines',$headlines);
		$this->assign('page_flag',$page_flag);
		$this->assign('page',$page);
		$this->assign('page_menu',$page_menu);
		$this->assign('index_page',$index_page);
		$this->assign('last_page',$last_page);
		$this->assign('last_page_flag',$pageNum-1);
		$this->assign('data',$news_list[$page]);
		$this->display($template[$rubricid]);
	}
}
?>