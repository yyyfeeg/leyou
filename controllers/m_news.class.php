<?php
#=============================================================================================
# 	FileName: m_news.class.php
# 		Desc: 新闻类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.04.18
# LastChange: 
#    TestUrl: index.php?mo=m_news&me=html_news&rubricid=2
#=============================================================================================

class M_news extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','news');// 导航标识
		$this->assign('menu_title','新闻资讯');// 栏目标题
		$this->assign('callback_url',WEBPATH_DIR_INC.HTML_DIR.'/mobile/index.html');// 返回地址
	}

	/**
	 * 新闻列表展示
	 */
	public function html_news()
	{
		$rubricid = get_param('rubricid','int');// 栏目ID

		$dir = get_rubric_dir($this->_db,$rubricid);// 栏目文件保存目录

		$sql = "select sysid,fe_title,fe_virtue,fe_jurl,fe_sphoto,fe_desc from ".get_table('frontend_essay')." where fe_status=2 and fe_showtype in (1,3) order by fe_order asc,sysid desc";  //and fe_rubricid=$rubricid
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$jump = in_array('3',explode(',',$rows['fe_virtue'])) ? true:false;
			if ($jump) {
				$url = $rows['fe_jurl'];
				$target = 'target="_blank"';
			} else {
				$url = WEBPATH_DIR_INC.HTML_DIR.'/mobile'.$dir.'/'.$rows['sysid'].'.html';
				$target = '';
			}
			$data['lists'][] = array(
				'title'  => $rows['fe_title'],
				'sphoto' => WEBPATH_DIR_INC.str_replace('..','',$rows['fe_sphoto']),
				'desc'   => stripslashes($rows['fe_desc']),
				'url'    => $url,
				'target' => $target
				);
		}
		// 生成json数据文件
		$json_file = WEBPATH_DIR.'/html/json/news_list.json';
		$json_data = json_encode($data);
		if (!file_exists($json_file)) {
			$this->_file->touch($json_file);
		}
		$this->_file->write($json_file,$json_data);
		$ajax = WEBPATH_DIR_INC.'/html/json/news_list.json';
		
		$this->assign('ajax',$ajax);// 数据文件地址
		$this->assign('data_news',$data['lists']);
		$this->display('../m/news_m.html');
	}

	/**
	 * 新闻列表展示
	 */
	/*public function html_news()
	{
		$page     = get_param('page','int');// 页数
		$pnum     = get_param('pnum','int');// 页数导航数量
		$article  = get_param('article','int');// 单页条数
		$rubricid = get_param('id','int');// 栏目ID

		$dir = get_rubric_dir($this->_db,$rubricid);// 栏目文件保存目录
		$template = get_rubric_keyvalue($this->_db,3);// 生成模板

		$sql = "select sysid,fe_title,fe_virtue,fe_jurl,fe_sphoto,fe_desc from ".get_table('frontend_essay')." where fe_status=2 and fe_rubricid=$rubricid and fe_showtype in (1,3) order by fe_order asc,sysid desc";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$data[] = array(
				'title'  => $rows['fe_title'],
				'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
				'jurl'   => $rows['fe_jurl'],
				'sphoto' => str_replace('..','',$rows['fe_sphoto']),
				'desc'   => $rows['fe_desc'],
				'url'    => WEBPATH_DIR_INC.HTML_DIR.'/mobile'.$dir.'/'.$rows['sysid'].'.html'
				);
		}

		// 切分单页数据
		$news_list = @array_chunk($data, $article);
		$pageNum = ceil(count($data)/$article);

		// 分页导航
		for ($i=0; $i < $pageNum; $i++) { 
			if ($i == 0) {
				$page_menu[] =  WEBPATH_DIR_INC.HTML_DIR.'/mobile'.$dir.'/index.html';
			} else {
				$page_menu[] = WEBPATH_DIR_INC.HTML_DIR.'/mobile'.$dir."/list_".$i.".html";
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
		$this->display('mobile/'.$template[$rubricid]);
	}*/
}
?>