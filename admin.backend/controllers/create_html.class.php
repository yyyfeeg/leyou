<?php
#======================================================================================
# 	FileName: create_html.class.php
# 		Desc: 生成静态文件类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.26
# LastChange:
#    TestUrl: index.php?module=create_html&method=create_content&typeid=2&rurl=http://
#======================================================================================

class Create_html extends Controller
{
	private $web_path = '';// 前端整站路径
	private $html_dir = '';// 生成静态文件保存路径
	private $index_url = '';// 生成前端首页url

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		// 检查是否登录
		if (!$this->checklogin()) {
			showinfo("", "index.php", 1);
		}

		// 查询前端整站信息表
		$sql = "select fi_basehost,fi_arcdir,fi_indexurl from ".get_table('frontend_info');
		$result = $this->db->getOne($this->db->Query($sql));
		$this->web_path  = $result['fi_basehost'];
		$this->html_dir  = $result['fi_arcdir'];
		$this->index_url = $result['fi_indexurl'];

	}

	/**
	 * 生成首页
	 * @return [type] [description]
	 */
	public function create_index()
	{
		$index_file_dir = WEBPATH_DIR.'../index.html';
		$index_file_url = $this->web_path.'/'.$this->index_url;
		$index = file_get_contents($index_file_url);
		$this->fileobj->touch($index_file_dir);
		$result = $this->fileobj->write($index_file_dir, $index);
		if ($result) {
			$res = array('code'=>'1','msg'=>'生成首页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成首页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成新闻列表
	 * @return [type] [description]
	 */
	public function create_list()
	{
		$sysid   = get_param('id','int');// 栏目自动ID
		$pnum    = get_param('pnum');// 页数导航数量
		$article = get_param('article','int');// 单页条数

		$rubric_dir  = get_rubric_dir($this->db,$sysid);// 栏目文件保存目录

		$sql = "select count(*) as num from ".get_table('frontend_essay')." where fe_status=2 and fe_rubricid=$sysid and fe_showtype in (2,3)";
		$result = $this->db->getOne($this->db->Query($sql));

		$pageNum = ceil($result['num']/$article);
		
		for ($i=0; $i < $pageNum; $i++) {

			if ($i == 0) {
				$file_dir = WEBPATH_DIR."..".$this->html_dir.$rubric_dir."/index.html";
			} else {
				$file_dir = WEBPATH_DIR."..".$this->html_dir.$rubric_dir."/list_".$i.".html";
			}
			$file_url = $this->web_path."/index.php?mo=news_html&me=html_news&page=$i&pnum=$pnum&article=$article&id=$sysid";
			$content = file_get_contents($file_url);
			$this->fileobj->touch($file_dir);
			$result = $this->fileobj->write($file_dir, $content);
		}

		if ($result) {
			$res = array('code'=>'1','msg'=>'生成列表页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成列表页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成游戏列表
	 * @return [type] [description]
	 */
	public function create_games_center()
	{
		$pnum = get_param('pnum');// 页数导航数量
		$article = get_param('article','int');// 单页条数

		$sql = "select count(*) as num from ".get_table('game_info')." where gi_status=1 and gi_show=1";
		$result = $this->db->getOne($this->db->Query($sql));

		$pageNum = ceil($result['num']/$article);

		for ($i=0; $i < $pageNum; $i++) {

			if ($i == 0) {
				$file_dir = WEBPATH_DIR."../html/games/index.html";
			} else {
				$file_dir = WEBPATH_DIR."../html/games/list/list_".$i.".html";
			}
			
			$file_url = $this->web_path."/index.php?mo=games_html&me=games_list&page=$i&pnum=$pnum&article=$article";
			$content = file_get_contents($file_url);
			$this->fileobj->touch($file_dir);
			$result = $this->fileobj->write($file_dir, $content);
		}

		if ($result) {
			$res = array('code'=>'1','msg'=>'生成列表页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成列表页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成内容页
	 * @return [type] [description]
	 */
	public function create_content()
	{
		$typeid = get_param('typeid','int');// 热点游戏类型ID

		$sql = "select sysid,fe_rubricid from ".get_table('frontend_essay')." where fe_status=2 and fe_showtype in (2,3)";
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {

			$rubric_dir = get_rubric_dir($this->db,$rows['fe_rubricid']);// 栏目文件保存目录

			$file_dir = WEBPATH_DIR."..".$this->html_dir.$rubric_dir."/".$rows['sysid'].".html";
			$file_url = $this->web_path."/index.php?mo=content_html&me=html_content&id=".$rows['sysid']."&typeid=$typeid&rubricid=".$rows['fe_rubricid'];
			$content = file_get_contents($file_url);
			$this->fileobj->touch($file_dir);
			$result = $this->fileobj->write($file_dir, $content);

			// 更新文章表已生成
			update_record($this->db,'frontend_essay',array('fe_html'=>2),array('sysid'=>$rows['sysid']),'',1);
		}
		if ($result) {
			$res = array('code'=>'1','msg'=>'生成内容页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成内容页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成移动端首页
	 */
	public function create_m_index()
	{
		$pptid = get_param('pptid','int');// 首页幻灯片分类ID

		$index_file_dir = WEBPATH_DIR.'../html/mobile/index.html';
		$index_file_url = $this->web_path.'/index.php?mo=m_index&pptid='.$pptid;
		$index = file_get_contents($index_file_url);
		$this->fileobj->touch($index_file_dir);
		$result = $this->fileobj->write($index_file_dir, $index);
		if ($result) {
			$res = array('code'=>'1','msg'=>'生成移动端首页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成移动端首页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成移动新闻列表页
	 */
	public function create_m_news()
	{
		$rubricid = get_param('rubricid','int');// 新闻栏目ID

		$index_file_dir = WEBPATH_DIR.'../html/mobile/news/index.html';
		$index_file_url = $this->web_path.'/index.php?mo=m_news&me=html_news&rubricid='.$rubricid;
		$index = file_get_contents($index_file_url);
		$this->fileobj->touch($index_file_dir);
		$result = $this->fileobj->write($index_file_dir, $index);
		if ($result) {
			$res = array('code'=>'1','msg'=>'生成移动新闻列表页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成移动新闻列表页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成移动端游戏列表页
	 */
	public function create_m_games()
	{
		$pptid = get_param('pptid','int');// 游戏幻灯片分类ID

		$index_file_dir = WEBPATH_DIR.'../html/mobile/games/index.html';
		$index_file_url = $this->web_path.'/index.php?mo=m_games&me=html_games&pptid='.$pptid;
		$index = file_get_contents($index_file_url);
		$this->fileobj->touch($index_file_dir);
		$result = $this->fileobj->write($index_file_dir, $index);
		if ($result) {
			$res = array('code'=>'1','msg'=>'生成移动端游戏列表页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成移动端游戏列表页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成移动端客服列表页
	 */
	public function create_m_kf()
	{
		$index_file_dir = WEBPATH_DIR.'../html/mobile/kf/index.html';
		$index_file_url = $this->web_path.'/index.php?mo=m_kf&me=html_kf';
		$index = file_get_contents($index_file_url);
		$this->fileobj->touch($index_file_dir);
		$result = $this->fileobj->write($index_file_dir, $index);
		if ($result) {
			$res = array('code'=>'1','msg'=>'生成移动端客服列表页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成移动端客服列表页失败');
		}
		exit(json_encode($res));
	}

	/**
	 * 生成移动端内容页
	 */
	public function create_m_content()
	{
		$rubricid = get_param('rubricid','int');// 栏目分类ID

		// if (empty($rubricid)) {
		// 	$where = '';
		// } else {
		// 	$where = '';//" and fe_rubricid = ".$rubricid; 移动端显示全部新闻文章
		// }
		// 查询所有文章sysid
		$sql = "select sysid,fe_rubricid from ".get_table('frontend_essay')." where fe_status=2 and fe_showtype in (1,3) ";
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {

			$rubric_dir = get_rubric_dir($this->db,2);// 栏目文件保存目录，移动端保存在游戏新闻栏目下2  $rows['fe_rubricid']

			$index_file_dir = WEBPATH_DIR.'..'.$this->html_dir.'/mobile'.$rubric_dir.'/'.$rows['sysid'].'.html';
			$index_file_url = $this->web_path.'/index.php?mo=m_content&me=html_content&rubricid='.$rows['fe_rubricid'].'&id='.$rows['sysid'];
			$index = file_get_contents($index_file_url);
			$this->fileobj->touch($index_file_dir);
			$result = $this->fileobj->write($index_file_dir, $index);
		}

		// 生成服务条款页
		$page_file_php = $this->web_path.'/index.php?mo=m_agreement&me=index';
		$page_file_dir = WEBPATH_DIR.'../html/mobile/agreement/index.html';
		$page_contents = file_get_contents($page_file_php);
		$this->fileobj->touch($page_file_dir);
		$this->fileobj->write($page_file_dir,$page_contents);

		if ($result) {
			$res = array('code'=>'1','msg'=>'生成移动端内容页成功');
		} else {
			$res = array('code'=>'-1','msg'=>'生成移动端内容页失败');
		}
		exit(json_encode($res));
	}
}
?>