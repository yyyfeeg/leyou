<?php
#============================================
# 	FileName: index.class.php
# 		Desc: 首页控制器文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.01.04
# LastChange: 
#============================================

class Index extends Controller
{
	private $index_file_url;//首页URL地址
	private $index_file_dir;//首页文件地址

	/**
	 * 构造函数，首次访问生成index.html静态文件
	 */
	public function __construct()
	{
		parent::__construct();
		$this->index_file_dir = WEBPATH_DIR.'index.html';
		$this->index_file_url = WEBPATH_DIR_INC.'/'.CREATE_INDEX_URL;
		if (!file_exists($this->index_file_dir)) {
			$index = file_get_contents($this->index_file_url);
			$this->_file->touch($this->index_file_dir);
			$this->_file->write($this->index_file_dir, $index);
		}
	}

	/**
	 * 显示首页内容
	 * @return [type] [description]
	 */
	public function show_index()
	{
		// 跳转到首页内容展示控制器
		header("Location:".$this->index_file_dir);
		exit;
	}
}
?>