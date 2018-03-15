<?php
#=================================================================================
# 	FileName: m_content.class.php
# 		Desc: 内容页类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.18
# LastChange: 
#    TestUrl: index.php?mo=m_content&me=html_content&rubricid=2&id=6
#=================================================================================

class M_content extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','news');// 导航标识
		$this->assign('menu_title','新闻资讯');// 栏目标题
		$this->assign('callback_url',WEBPATH_DIR_INC.HTML_DIR.'/mobile/news/index.html');// 返回地址
	}

	/**
	 * 内容页展示
	 */
	public function html_content()
	{
		$sysid    = get_param('id','int');// 文章自动ID
		$rubricid = 0;//get_param('rubricid','int');// 栏目分类ID   手机段显示全部文章

		$dir    = get_rubric_dir($this->_db,2);// 栏目文件保存目录 $rubricid 手机端默认在游戏新闻栏目下
		$upid   = get_up_next_pen($this->_db,'up',$rubricid,1,$sysid);
		$nextid = get_up_next_pen($this->_db,'next',$rubricid,1,$sysid);

		if ($upid != 'NO') {
			$sql = "select sysid,fe_title,fe_virtue,fe_jurl from ".get_table('frontend_essay')." where sysid=$upid";
			$query = $this->_db->Query($sql);
			while ($rows = $this->_db->FetchArray($query)) {
				$pen[$rows['sysid']] = array(
					'title'  => $rows['fe_title'],
					'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
					'jurl'   => $rows['fe_jurl'],
					'url'    => WEBPATH_DIR_INC.HTML_DIR.'/mobile'.$dir.'/'.$rows['sysid'].'.html'
					);
			}
		}
		if ($nextid != 'NO') {
			$sql = "select sysid,fe_title,fe_virtue,fe_jurl from ".get_table('frontend_essay')." where sysid=$nextid";
			$query = $this->_db->Query($sql);
			while ($rows = $this->_db->FetchArray($query)) {
				$pen[$rows['sysid']] = array(
					'title'  => $rows['fe_title'],
					'virtue' => in_array('3',explode(',',$rows['fe_virtue'])) ? 'true':'false',
					'jurl'   => $rows['fe_jurl'],
					'url'    => WEBPATH_DIR_INC.HTML_DIR.'/mobile'.$dir.'/'.$rows['sysid'].'.html'
					);
			}
		}

		// 文章内容
		$sql = "select fe_title,fe_contents,fe_printtime,fe_rubricid,fe_author from ".get_table('frontend_essay')." where sysid=$sysid";
		$result = $this->_db->getOne($this->_db->Query($sql));
		if ($result) {
			$content_arr = array(
				'title'     => $result['fe_title'],
				'contents'  => htmlspecialchars_decode($result['fe_contents']),
				'printtime' => date('Y-m-d',strtotime($result['fe_printtime'])),
				'author'    => $result['fe_author'],
				'uppen'     => ($pen[$upid]) ? $pen[$upid]:'NO',
				'nextpen'   => ($pen[$nextid])? $pen[$nextid]:'NO',
				);
		}
		$this->assign('content_arr',$content_arr);
		$this->display('../m/news_con.html');
	}
}
?>