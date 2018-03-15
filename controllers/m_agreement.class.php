<?php
#===============================================================
# 	FileName: m_agreement.php
# 		Desc: 用户服务信息页
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.17
# LastChange: index.php?mo=m_agreement&me=index
#===============================================================

class M_agreement extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		// $this->assign('menu_flag','customer');// 导航标识
		$this->assign('menu_title','服务条款');// 栏目标题
		$this->assign('callback_url',WEBPATH_DIR_INC.HTML_DIR.'/mobile/index.html');// 返回地址
	}

	/**
	 * 游戏客服电话列表
	 */
	public function index()
	{
		
		$this->display('mobile/agreement.html');
	}
}
?>