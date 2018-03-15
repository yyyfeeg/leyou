<?php
#=================================================================================
# 	FileName: content_html.class.php
# 		Desc: 内容页类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.26
# LastChange: 
#    TestUrl: index.php?mo=content_html&me=html_content&id=1&typeid=3&rubricid=2
#=================================================================================

class Content_html extends Controller
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
	 * 生成内容页
	 * @return [type] [description]
	 */
	public function html_content()
	{
		$sysid      = get_param('id','int');// 文章自动ID
		$typeid     = get_param('typeid','int');// 热点游戏类型ID
		$rubricid   = get_param('rubricid','int');// 内容类目ID
		$rubric_arr = get_rubric_keyvalue($this->_db);// 获取栏目数组

		$dir = get_rubric_dir($this->_db,$rubricid);// 栏目文件保存目录

		// 获取公共信息
		
		// 热点游戏
		$sql = "select fp_title,fp_url,fp_jurl,fp_desc from ".get_table('frontend_photos')." where fp_typeid=$typeid and fp_status=2 order by fp_order asc,sysid desc limit 4";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$hot_games[] = array(
				'title' => $rows['fp_title'],
				'purl'  => str_replace('..','',$rows['fp_url']),
				'jurl'  => $rows['fp_jurl'],
				'desc'  => $rows['fp_desc']
			);
		}

		// 热门动态
		$sql = "select sysid,fe_title,fe_virtue,fe_jurl,fe_printtime from ".get_table('frontend_essay')." where fe_status=2 and fe_rubricid=$rubricid and fe_showtype in (2,3) and (fe_virtue like '%1%' or fe_virtue like '%2%') order by fe_order asc,sysid desc limit 9";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$dynamic[] = array(
				'sysid'  => $rows['sysid'],
				'title'  => $rows['fe_title'],
				'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
				'jurl'   => $rows['fe_jurl'],
				'time'   => strtotime($rows['fe_printtime']),
				'url'    => WEBPATH_DIR_INC.HTML_DIR.$dir.'/'.$rows['sysid'].'.html'
			);
		}

		// 文章内容
		$sql = "select fe_title,fe_contents,fe_printtime,fe_rubricid,fe_author from ".get_table('frontend_essay')." where sysid=$sysid";
		$result = $this->_db->getOne($this->_db->Query($sql));

		if ($result) {
			$content_arr = array(
				'title'     => $result['fe_title'],
				'contents'  => htmlspecialchars_decode(Filter_word($result['fe_contents'])),
				'printtime' => date('Y-m-d',strtotime($result['fe_printtime'])),
				'rubricid'  => $rubric_arr[$result['fe_rubricid']],
				'author'    => $result['fe_author'],
				'rubricurl' => WEBPATH_DIR_INC.HTML_DIR.$dir.'/index.html'
				);
		}
		
		$this->assign('hot_games',$hot_games);
		$this->assign('dynamic',$dynamic);
		$this->assign('content_arr',$content_arr);
		$this->assign('rubricUrl',$rubricUrl);
		$this->display('content.html');
	}
}
?>