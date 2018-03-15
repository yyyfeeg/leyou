<?php
#============================================
# 	FileName: logout_tips.class.php
# 		Desc: SDK退出登录提示信息管理类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Logout_tips extends Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->checklogin()) {
			showinfo("", "index.php", 1);
		}
		$GLOBALS['IMG_UP']['image_upload_size'] = 3000000; //更改图片大小为3M
        $this->upimg = new upload_image();
        $this->path  = '../lyuploads/lt/'.date("Ymd")."/";
	}

	/**
	 * 退出登录提示信息列表
	 * @return [type] [description]
	 */
	public function tips_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('tips_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$usersArr = get_admin_name($this->db);
		$gamesArr = get_game($this->db);
		$uaidsArr = get_transport($this->db);
		$statusArr = array('','未发布','已发布','已下架');

		// 查询数据
		$sql = "select sysid,lt_gid,lt_uaid,lt_picture,lt_url,lt_contents,lt_printtime,lt_status,lt_addtime,lt_adduid from ".get_table('logout_tips');
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {

			$rows['lt_adduid']    = $usersArr[$rows['lt_adduid']];
			$rows['lt_uaid']      = empty($rows['lt_uaid']) ? '所有渠道':$uaidsArr[$rows['lt_uaid']];
			$rows['lt_printtime'] = date('Y-m-d',strtotime($rows['lt_printtime']));
			$rows['lt_addtime']   = date('Y-m-d H:i:s',$rows['lt_addtime']);
			$rows['lt_status']    = $statusArr[$rows['lt_status']];
			// 操作
			$action = "<a href='index.php?module=logout_tips&method=edit_tips&id=".$rows['sysid']."&gameid=".$rows['lt_gid']."'>[修改]</a>";
			$rows['lt_gid'] = $gamesArr[$rows['lt_gid']];

			if ($this->isadmin) {
				$action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=logout_tips&method=del_tips&id=".$rows['sysid']."&img=".$rows['lt_picture']."&name=".$rows['lt_gid']."'>[删除]</a>";
			}
			$rows['lt_picture'] = "<img src='".$rows['lt_picture']."' width='100%' height='100px'>";

			$data[] = array(
				$rows['sysid'],
				$rows['lt_gid'],
				$rows['lt_uaid'],
				$rows['lt_picture'],
				$rows['lt_url'],
				$rows['lt_contents'],
				$rows['lt_printtime'],
				$rows['lt_addtime'],
				$rows['lt_adduid'],
				$rows['lt_status'],
				$action
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('meg','退出登录提示信息列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->assign('type','list');
		$this->display('logout_tips.html');
	}

	/**
	 * 添加退出登录提示信息
	 */
	public function add_tips()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_tips')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 获取参数
		$act  = get_param('act');
		$fun  = get_param('fun');
		$flag = get_param('flag');
		$data['lt_gid']       = get_param('gameid');
		$data['lt_uaid']      = get_param('uaid');
		$data['lt_picture']   = get_param('picture');
		$data['lt_url']       = get_param('url');
		$data['lt_contents']  = get_param('contents');
		$data['lt_printtime'] = get_param('printtime') ? get_param('printtime'):date('Y-m-d',time());
		$data['lt_timing']    = get_param('timing');
		$data['lt_timing']    = empty($data['lt_timing']) ? 1:2;
		$data['lt_status']    = get_param('status','int') ? get_param('status','int'):2;
		$data['lt_adduid']    = $this->my_admin_id;
		$data['lt_addtime']   = THIS_DATETIME;
		

		// 获取游戏下的渠道
		$where = " and gc_gid = ".$data['lt_gid'];
		$sql = "select sysid,gc_cname from ".get_table('game_channels')."where 1 $where";
		$uaid_result = $this->db->getAll($this->db->Query($sql));
		if ( $fun == 'get_uaid' ) {
			if (count($uaid_result) > 0){
				$str = '<select name="uaid" style="width:100%;"><option value="0">所有渠道</option>';
				foreach ($uaid_result as $value) {
					$str .= "<option value='".$value['sysid']."'>".$value['gc_cname']."</option>";
				}
				$str .= '</select>';
			}
			echo $str;
			exit;
		}

		// 添加记录
		if ($act == 'add' && $flag != 'up') {
			// 检查参数
			if (empty($data['lt_gid'])) {
				showinfo("请选择游戏!", "index.php?module=logout_tips&method=add_tips",3);
			}
			if (empty($data['lt_picture'])) {
				showinfo("请上传图片!", "index.php?module=logout_tips&method=add_tips",3);
			}
			if (empty($data['lt_url'])) {
				showinfo("跳转地址不能为空!", "index.php?module=logout_tips&method=add_tips",3);
			}

			$data['lt_printtime'] = date('Ymd',strtotime($data['lt_printtime']));

			// 添加记录
			$result = add_record($this->db,'logout_tips',$data);
			if ($result['rows'] > 0) {

                $this->admin_log("添加退出登录提示信息成功");
                showinfo("添加成功!", "index.php?module=logout_tips&method=tips_list", 4);
            } else {

            	// 删除当前上传的图片
            	 if(!empty($data['lt_picture']) && $this->fileobj->isExists($data['lt_picture'])){
                    $this->fileobj->rm($data['lt_picture']);
                }
                $this->admin_log("添加退出登录提示信息失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=logout_tips&method=tips_list", 3);
            }
		}

		// 上传文件
		if ($flag == 'up') {

			// 重复上传，删除之前上传的文件
            if (!empty($data['lt_picture'])) @unlink(WEBPATH_DIR.$data['lt_picture']);

            $file = $_FILES['file'];
            $res = $this->fileobj->isDir($this->path);
            if (!$res) {
                $this->fileobj->mkDir($this->path);
            }
            $file['path'] = $this->path;
            $info = $this->upimg->upload($file);
            if ($this->upimg->errinfo) {
                $error = $this->upimg->errinfo;
            }
            if ($info) {
                $upload_url = $info; //生成上传图片路径
                $success = '恭喜您，上传成功！';
            }
		}
		$img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;

        // 格式处理
        $data['lt_printtime'] = date('Y-m-d',strtotime($data['lt_printtime']));
        $uaid_result = empty($uaid_result) ? '':$uaid_result;

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("success", $success);
        $this->assign("error", $error);
        $this->assign("url", $info);
		$this->assign('gameArr',get_game($this->db));
		$this->assign('uaidArr',$uaid_result);
		$this->assign('data',$data);
		$this->assign('type','add');
		$this->display('logout_tips.html');
	}

	/**
	 * 修改退出登录提示信息
	 * @return [type] [description]
	 */
	public function edit_tips()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('edit_tips')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act  = get_param('act');
		$fun  = get_param('fun');
		$flag = get_param('flag');
		$data['sysid']        = get_param('id');
		$data['lt_gid']       = get_param('gameid');
		$data['lt_uaid']      = get_param('uaid');
		$data['lt_picture']   = get_param('picture');
		$data['lt_url']       = get_param('url');
		$data['lt_contents']  = get_param('contents');
		$data['lt_printtime'] = get_param('printtime') ? get_param('printtime'):date('Y-m-d',time());
		$data['lt_timing']    = get_param('timing');
		$data['lt_timing']    = empty($data['lt_timing']) ? 1:2;
		$data['lt_status']    = get_param('status','int') ? get_param('status','int'):2;
		$data['lt_upuid']     = $this->my_admin_id;
		$data['lt_uptime']    = THIS_DATETIME;

		// 获取游戏下的渠道
		$where = " and gc_gid = ".$data['lt_gid'];
		$sql = "select sysid,gc_cname from ".get_table('game_channels')."where 1 $where";
		$uaid_result = $this->db->getAll($this->db->Query($sql));
		if ( $fun == 'get_uaid' ) {
			if (count($uaid_result) > 0){
				$str = '<select name="uaid" style="width:100%;"><option value="0">所有渠道</option>';
				foreach ($uaid_result as $value) {
					$str .= "<option value='".$value['sysid']."'>".$value['gc_cname']."</option>";
				}
				$str .= '</select>';
			}
			echo $str;
			exit;
		}

		// 查询图片
		$sql = "select lt_picture from ".get_table('logout_tips')." where sysid = ".$data['sysid'];
		$photo = $this->db->getOne($this->db->Query($sql));

		if ($act == 'edit' && $flag != 'up') {
			// 检查参数
			if (empty($data['lt_gid'])) {
				showinfo("请选择游戏!", "index.php?module=logout_tips&method=edit_tips",3);
			}
			if (empty($data['lt_picture'])) {
				showinfo("请上传图片!", "index.php?module=logout_tips&method=edit_tips",3);
			}
			if (empty($data['lt_url'])) {
				showinfo("跳转地址不能为空!", "index.php?module=logout_tips&method=edit_tips",3);
			}

			$data['lt_printtime'] = date('Ymd',strtotime($data['lt_printtime']));

			// 添加记录
			$result = update_record($this->db,'logout_tips',$data,array('sysid'=>$data['sysid']),'',1);
			if ($result) {

				// 如果图片有修改，删除数据库中的图片
				if(!empty($data['lt_picture']) && $data['lt_picture'] != $photo['lt_picture'] && $this->fileobj->isExists($photo['lt_picture'])){
                    $this->fileobj->rm($photo['lt_picture']);
                }
                $this->admin_log("修改游戏 ".$data['lt_gid']." 退出登录提示信息成功");
                showinfo("修改成功!", "index.php?module=logout_tips&method=tips_list", 4);
            } else {
            	
            	// 如果图片有修改，删除当前上传的图片
            	if(!empty($data['lt_picture']) && $this->fileobj->isExists($data['lt_picture']) && $data['lt_picture'] != $photo['lt_picture']){
                    $this->fileobj->rm($data['lt_picture']);
                }
                $this->admin_log("修改游戏 " . $data['lt_gid'] . " 退出登录提示信息失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=logout_tips&method=tips_list&id=".$data['sysid'], 3);
            }
		}

		if ($flag == 'up') {
			// 检查是否和数据库中的一样，否则删除
            if (!empty($data['lt_picture'])  && $data['lt_picture'] != $photo['lt_picture']) {
            	@unlink(WEBPATH_DIR.$data['lt_picture']);
            }

            $file = $_FILES['file'];
            $res = $this->fileobj->isDir($this->path);
            if (!$res) {
                $this->fileobj->mkDir($this->path);
            }
            $file['path'] = $this->path;
            $info = $this->upimg->upload($file);
            if ($this->upimg->errinfo) {
                $error = $this->upimg->errinfo;
            }
            if ($info) {
                $upload_url = $info; //生成上传图片路径
                $success = '恭喜您，上传成功！';
            }
		} else {
			// 查询原有数据
			$sql = "select sysid,lt_gid,lt_uaid,lt_picture,lt_url,lt_contents,lt_printtime,lt_timing,lt_status from ".get_table('logout_tips')." where sysid = ".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}
		$img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;

        // 格式处理
        $data['lt_printtime'] = date('Y-m-d',strtotime($data['lt_printtime']));
        $uaid_result = empty($uaid_result) ? '':$uaid_result;

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("success", $success);
        $this->assign("error", $error);
        $this->assign("url", $info);
		$this->assign('gameArr',get_game($this->db));
		$this->assign('uaidArr',$uaid_result);
		$this->assign('data',$data);
		$this->assign('type','edit');
		$this->display('logout_tips.html');
	}

	/**
	 * 删除退出登录提示信息
	 * @return [type] [description]
	 */
	public function del_tips()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_tips')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$img = get_param('img');
		$name = get_param('name');
		$where['sysid'] = get_param('id');

		$result = delete_record($this->db,'logout_tips',$where);
		if ($result) {
			// 删除成功
			if(!empty($img) && $this->fileobj->isExists($img)){
                $this->fileobj->rm($img);
            }
			$this->admin_log("删除 ". $name ." 退出登录提示信息成功");
            showinfo("删除 ".$name." 退出登录提示成功!", "index.php?module=logout_tips&method=tips_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除 ". $name ." 退出登录提示信息失败，原因:数据库删除失败");
            showinfo("删除 ".$name." 退出登录提示失败,请重试!", "", 3);
		}
	}
}
?>