<?php
#==================================================
# 	FileName: create_url.class.php
# 		Desc: 请求生成静态文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.26
# LastChange: 
#    TestUrl: 
#==================================================

class Create_url extends Controller
{
	private $json_file = '';// 生成URL文件路径

	public function __construct()
	{
		parent::__construct();

		// 检查是否有权限
		if ($this->isadmin != 1 && !$this->checkright('create_url')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$this->json_file = WEBPATH_DIR.'/create_url.json';
	}

	/**
	 * 展示要生成的信息
	 */
	public function show_create_info()
	{
		// 接收参数
		$flag  = get_param('flag');
		$data['url_1'] = get_param('url_1');
		$data['url_2'] = get_param('url_2');
		$data['url_3'] = get_param('url_3');
		$data['url_4'] = get_param('url_4');
		$data['url_5'] = get_param('url_5');
		$data['url_6'] = get_param('url_6');
		$data['url_7'] = get_param('url_7');
		$data['url_8'] = get_param('url_8');
		$data['url_9'] = get_param('url_9');
		$data['url_10'] = get_param('url_10');

		if ($flag == 'write' && $this->isadmin == 1) {
			$json_data = json_encode($data);
			if (!file_exists($this->json_file)) {
				$this->fileobj->touch($this->json_file);
			}
			$this->fileobj->write($this->json_file,$json_data);
		}
		$json_data = file_get_contents($this->json_file);
		$dataArr = json_decode($json_data,true);

		$this->assign('data',$dataArr);
		$this->display('create_html.html');
	}

}
?>