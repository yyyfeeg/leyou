<?php
#============================================
# 	FileName: spree_cardid.class.php
# 		Desc: 礼包码管理功能类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.04.19
# LastChange: 
#============================================

class Spree_cardid extends Controller
{
	private $upType = '';	//上传文件类型
	private $path = '';		//上传文件路径
	private $fileObj = '';	//文件操作对象

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		if (!$this->checklogin()) {
			showinfo("", "index.php", 1);
		}
		$this->fileObj = new data_file();
		$this->upType = array('txt');
		$this->path = './lyuploads/card/'.date("Ymd")."/";
	}

	/**
	 * 礼包码列表
	 */
	public function cardid_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('cardid_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$flag   = get_param('flag');
		$gid    = get_param('gid');
		$sid    = get_param('sid');
		$cardid = get_param('cardid');
		$ctype  = get_param('ctype');
		$ktype  = get_param('ktype');
		$draw   = get_param('draw');

		// 查询游戏下的区服
		$sql = "select sysid,gs_sname from ".get_table('game_server')." where gs_gid = ".$gid;
		$result = $this->db->getAll($this->db->Query($sql));
		if ($flag == 'get_sid') {
			$str = '<option value="0">全区服</option>';
			if (count($result) > 0) {
				foreach ($result as $value) {
					$str .= "<option value='".$value['sysid']."'>".$value['gs_sname']."</option>";
				}
			}
			echo $str;
			exit;
		}

		if ($flag == 'get_data') {

			$gamesArr = get_game($this->db);
			$serverArr = get_servers($this->db,"","",2);

			// 查询数据
			$where = '1';
			$where .= $gid ? " and gci_gid = $gid":"";
			$where .= $sid ? " and gci_sid = $sid":"";
			$where .= $ctype ? " and gci_ctypeid = $ctype":"";
			$where .= $ktype ? " and gci_keytypeid = $ktype":"";
			$where .= $cardid ? " and gci_cardnum = '".$cardid."'":"";
			$where .= $draw ? " and gci_draw = $draw":"";

			$sql = "select * from ".get_table('cardid_info')." where $where";
			$query = $this->db->Query($sql);
			while ($rows = $this->db->FetchArray($query)) {
				$action = "<a href='index.php?module=spree_cardid&method=del_cardid&id=".$rows['sysid']."'>[删除]</a>";
				$data[] = array(
					$rows['sysid'],
					$gamesArr[$rows['gci_gid']],
					$rows['gci_sid']?$serverArr[$rows['gci_sid']]:'全区服',
					$rows['gci_cardnum'],
					$rows['gci_uid']?$rows['gci_uid']:'-',
					$rows['gci_uname']?$rows['gci_uname']:'-',
					$rows['gci_drawtime']?date('Y-m-d H:i:s',$rows['gci_drawtime']):'-',
					($rows['gci_draw']==1)?'已领取':'未领取',
					$action
					);
			}
			if (empty($data)) {
				echo json_encode(array('code'=>1001,'msg'=>'没有相匹配的数据'));
			} else {
				echo json_encode($data);
			}
			exit;
		}

		$this->assign('type','list');
		$this->assign('gameArr',get_game($this->db));
		$this->assign('ctypeArr',get_ctype($this->db,1));
		$this->assign('ktypeArr',get_ctype($this->db,2));
		$this->display('spree_cardid.html');
	}

	/**
	 * 添加礼包码页面
	 */
	public function add_cardid()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_cardid')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act  = get_param('act');
		$flag = get_param('flag');
		$furl = get_param('furl');
		$data['gci_gid'] = get_param('gid');
		$data['gci_sid'] = get_param('sid');
		$data['gci_ctypeid'] = get_param('ctypeid');
		$data['gci_keytypeid'] = get_param('keytypeid');
		$data['gci_importtime'] = THIS_DATETIME;

		// 查询游戏下的区服
		$sql = "select sysid,gs_sname from ".get_table('game_server')." where gs_gid = ".$data['gci_gid'];
		$result = $this->db->getAll($this->db->Query($sql));
		if ($flag == 'get_sid') {
			$str = '<option value="0">全区服</option>';
			if (count($result) > 0) {
				foreach ($result as $value) {
					$str .= "<option value='".$value['sysid']."'>".$value['gs_sname']."</option>";
				}
			}
			echo $str;
			exit;
		}

		// 添加数据
		if ($act == 'add' && $flag != 'up') {
			// 检查是否选择游戏
			if (empty($data['gci_gid'])) {
				showinfo("请选择游戏!", "index.php?module=spree_cardid&method=add_cardid",3);
			}

			// 检查是否选择礼包类型
			if (empty($data['gci_ctypeid'])) {
				showinfo("请选择礼包类型!", "index.php?module=spree_cardid&method=add_cardid",3);
			}

			// 检查是否上传文件
			if (empty($furl) || !file_exists($furl)) {
				showinfo("请上传礼包码文件!", "index.php?module=spree_cardid&method=add_cardid",3);
			}

			// 读取数据
			$contents = file_get_contents($furl);
			$contents = preg_replace('/\n|\r\n/','&^&',$contents);
			$cardidArr = explode('&^&',$contents);
			$cardidArr = array_filter($cardidArr);
			if (count($cardidArr) > 0) {
				foreach ($cardidArr as $value) {
					$card_data = array(
						'gci_gid' => $data['gci_gid'],
						'gci_sid' => $data['gci_sid'],
						'gci_ctypeid' => $data['gci_ctypeid'],
						'gci_cardnum' => $value,
						'gci_keytypeid' => $data['gci_keytypeid'],
						'gci_importtime' => $data['gci_importtime']
						);
					$result = add_record($this->db,'cardid_info',$card_data);
				}
				$this->admin_log("成功导入一批礼包码，游戏ID：".$data['gci_gid']."；服务器ID：".$data['gci_sid']."；礼包类型ID：".$data['gci_ctypeid']);
                showinfo("添加成功!", "index.php?module=spree_cardid&method=cardid_list", 4);
			} else {
				$msg = '文件内容不能为空！';
			}
		}

		// 上传文件
		if ($flag == 'up') {
			// 重复上传，删除之前上传的文件
            if (!empty($furl)) @unlink(WEBPATH_DIR.$furl);

            $file 	  = $_FILES['file'];	// 上传的文件
            $tempFile = $file['tmp_name'];	// 临时文件名
			$fileName = $file["name"];		// 文件原全名(含后缀)
			$fileType = explode('.', $fileName);
			$fileName = iconv("UTF-8","GB2312",$fileType[0]);	// 原文件名(不含后缀)
			$fileExte = $fileType[1];							// 文件后缀
			$fileSize = ceil(filesize($tempFile)/1024);			// 文件大小(kb)

			// 检测上传文件类型是否合法
			if (!in_array($fileExte, $this->upType)) {
				$msg = '请将文件转成txt格式！';
			} else if ($fileSize > 1024*5) {
				$msg = '上传文件超过5M';
			} else {
				$newFilename = time().'.'.$fileExte;// 新的文件名称
				$path = $this->path.$newFilename;// 上传路径
				 // 复制临时文件到站点目录下
				$res = $this->fileObj->cp($tempFile, $path, true);
				if ($res) {
					$furl = $path;
					$msg = '恭喜您，上传成功！';
				} else {
					$msg = '上传失败！';
				}
			}
		}

		$this->assign('type','add');
		$this->assign('data',$data);
		$this->assign('furl',$furl);
		$this->assign('msg',$msg);
		$this->assign('gameArr',get_game($this->db));
		$this->assign('severArr',$result);
		$this->assign('ctypeArr',get_ctype($this->db,1));
		$this->assign('ktypeArr',get_ctype($this->db,2));
		$this->display('spree_cardid.html');
	}

	/**
	 * 生成礼包码
	 */
	public function create_cardid()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('create_cardid')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$this->assign('type','add');
		$this->display('spree_cardid.html');
	}

	/**
	 * 删除礼包码
	 * @return [type] [description]
	 */
	public function del_cardid()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_cardid')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$where['sysid'] = get_param('id');
		$result = delete_record($this->db,'cardid_info',$where);
		if ($result) {
			// 删除成功
			$this->admin_log("删除礼包码ID： ". $where['sysid'] ." 成功");
            showinfo("删除礼包码ID：". $where['sysid']." 成功!", "index.php?module=spree_cardid&method=cardid_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除礼包码ID：". $where['sysid'] ." 失败，原因:数据库删除失败");
            showinfo("删除礼包码ID：". $where['sysid']." 失败,请重试!", "", 3);
		}
	}
}
?>