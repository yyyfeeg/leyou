<?php
#============================================
# 	FileName: controller.class.php
# 		Desc: 控制器类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.26
# LastChange: 
#=============================================

class Controller extends MySmarty
{
	protected $_db    = null;//数据库对象
	protected $_file  = null;//文件操作对象
	protected $_sms   = null;//短信操作对象
	protected $_mail = null;//邮件操作对象
	protected $_uid   = '';//用户ID
	protected $_uname = '';//用户名称
	protected $_truename = '';//用户真实名称
	
	/**
	 * 构造函数，初始化变量
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_db   = $GLOBALS['base'];
		$this->_file = $GLOBALS['file'];
		$this->_sms  = $GLOBALS['sms'];
		$this->_mail = $GLOBALS['mail'];
		$this->__init();

		// 传递前端基础信息
		$this->assign('web_url', WEBPATH_DIR_INC);//整站URL
		$this->assign('title',WEBNAME);//整站标题
		$this->assign('keywords',KEYWORDS);//SEO关键字
		$this->assign('description',DESC);//网站描述
		$this->assign('powerby',POWERBY);//网站版权
		$this->assign('beian',BEIAN);//进网号
	}

	/**
	 * 初始化用户信息
	 * @return [type] [description]
	 */
	private function __init()
	{
		if (count($_SESSION) > 0) {
			foreach ($_SESSION as $key => $value) {
				if ($key == 'code') {
					continue;
				}
				$this->$key = $value;
			}
		}
	}

	/**
	 *检查是否登录
	 * @return [type] false：未登录，true：已登录
	 */
	public function check_login()
	{
		if (empty($this->_uid)) return false;
		return true;
	}

	/**
	 * 获取导航栏展示的游戏信息
	 * @param  [type]  $type 游戏类型 1.页游 2.手游 3.双端
	 * @param  integer $num  展示条数
	 * @return [type]        [description]
	 */
	public function get_games($type,$num=0)
	{
		if (!empty($num)) $limit = " limit $num";
		$sql = "select gi_gname,gi_virtue,gi_url from ".get_table('game_info')." where gi_status=1 and gi_gtype=$type and gi_show=1 and gi_virtue like '%3%' order by sysid desc $limit";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$data[] = array(
				'game_name'    => $rows['gi_gname'],
				'virtue_array' => explode(',',$rows['gi_virtue']),
				'gw_url'       => $rows['gi_url']
				);
		}
		return $data;
	}

	/**
	 * 获取导航用户最近玩过的游戏
	 * @param  [type] $num  显示条数
	 * @return [type]       [description]
	 */
	public function get_user_game($num=0)
	{
		// 获取游戏名称
		$game_info = get_game_info($this->_db);
		cutover_db_count();//切换到count数据库
		
		if (!empty($num)) $limit = " limit $num";

		$sql = "select dg_gid from ".get_table('gamelogin_log')." where dg_uid = ".$this->_uid." order by sysid desc $limit";
		$query = $GLOBALS['count']->Query($sql);
		while ($rows = $GLOBALS['count']->FetchArray($query)) {
			$games[] = array(
				'game_name'    => $game_info[$rows['dg_gid']]['gname'],
				'virtue_array' => explode(',',$game_info[$rows['dg_gid']]['virtue']),
				'gw_url'       => $game_info[$rows['dg_gid']]['url']
				);
		}

		cutover_db_base();//切换到base数据库
		$this->_db = $GLOBALS['base'];

		return $games;
	}
}
?>