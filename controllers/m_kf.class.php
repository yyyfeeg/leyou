<?php
#===============================================================
# 	FileName: m_kf.php
# 		Desc: 客服电话列表
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.17
# LastChange: index.php?mo=m_kf&me=html_kf
#===============================================================

class M_kf extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign('menu_flag','customer');// 导航标识
		$this->assign('menu_title','客服电话');// 栏目标题
		$this->assign('callback_url',WEBPATH_DIR_INC.HTML_DIR.'/mobile/index.html');// 返回地址
	}

	/**
	 * 游戏客服电话列表
	 */
	public function html_kf()
	{
		// 游戏客服电话列表
		$sql = "select gi_gname,gi_kfphone from ".get_table('game_info')." where gi_status=1 and gi_show=1 and gi_kfphone !='' and gi_gtype in (2,3) order by gi_order asc,sysid desc";
		$query = $this->_db->Query($sql);
		while ($rows = $this->_db->FetchArray($query)) {
			$data['lists'][] = array(
				'gname' => $rows['gi_gname'],
				'phone' => $rows['gi_kfphone']
				);
		}

		// 生成json数据文件
		$json_file = WEBPATH_DIR.'/html/json/kf_list.json';
		$json_data = json_encode($data);
		if (!file_exists($json_file)) {
			$this->_file->touch($json_file);
		}
		$this->_file->write($json_file,$json_data);
		$ajax = WEBPATH_DIR_INC.'/html/json/kf_list.json';
		
		$this->assign('ajax',$ajax);// 数据文件地址
		$this->display('../html/mobile/index.html');
	}
}
?>