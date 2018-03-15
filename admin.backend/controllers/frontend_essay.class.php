<?php
#============================================
# 	FileName: frontend_essay.class.php
# 		Desc: 文章管理功能类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Frontend_essay extends Controller
{
	/**
	 * 构造函数，初始化父类构造函数及检查是否登录
	 */
	public function __construct()
	{
		parent::__construct();
		if (!$this->checklogin()) {
			showinfo("", "index.php", 1);
		}
		$GLOBALS['IMG_UP']['image_upload_size'] = 3000000; //更改图片大小为3M
        $this->upimg = new upload_image();
        $this->path  = '../lyuploads/f_essay/'.date("Y")."/".date("m")."/";
	}

	/**
	 * 文章列表
	 * @return [type] [description]
	 */
	public function essay_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('essay_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$htmlArr     = array('','未生成','已生成');
		$showtypeArr = array('','手机端','PC端','双端');
		$statusArr   = array('','未发布','已发布','已下架');
		$rubricArr   = get_rubric_keyvalue($this->db);
		$unameArr    = get_users();

		// 查询记录
		$sql   = "select * from ".get_table('frontend_essay');
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {

			$rows['fe_html']      = $htmlArr[$rows['fe_html']];
			$rows['fe_showtype']  = $showtypeArr[$rows['fe_showtype']];
			$rows['fe_status']    = $statusArr[$rows['fe_status']];
			$rows['fe_printtime'] = date('Y-m-d',strtotime($rows['fe_printtime']));
			$rows['fe_addtime']   = date('Y-m-d H:i:s',$rows['fe_addtime']);
			$rows['fe_rubricid']  = $rubricArr[$rows['fe_rubricid']];
			$rows['fe_adduid']    = $unameArr[$rows['fe_adduid']];

			// 操作
			$rows['action'] = "<a href='index.php?module=frontend_essay&method=edit_essay&id=".$rows['sysid']."'>[修改]</a>";
			if ($this->isadmin) {
				$rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=frontend_essay&method=del_essay&id=".$rows['sysid']."&name=".$rows['fe_title']."&img1=".$rows['fe_sphoto']."&img2=".$rows['fe_bphoto']."'>[删除]</a>";
			}
			
			$data[] = array(
					$rows['sysid'],
					$rows['fe_title'],
					$rows['fe_printtime'],
					$rows['fe_addtime'],
					$rows['fe_rubricid'],
					$rows['fe_clicknum'],
					$rows['fe_html'],
					$rows['fe_showtype'],
					$rows['fe_adduid'],
					$rows['fe_status'],
					$rows['action']
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->assign('meg','文章列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->display('frontend_essay.html');
	}

	/**
	 * 添加文章
	 * @return [type] [description]
	 */
	public function add_essay()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_essay')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 获取参数
		$act  = get_param('act');
		$flag = get_param('flag');
		$type = get_param('type');
		$data['fe_title']     = get_param('title');
		$data['fe_author']    = get_param('author');
		$data['fe_virtue']    = get_array('virtue',',',true);
		$data['fe_jurl']      = get_param('jurl');
		$data['fe_rubricid']  = get_param('rubricid');
		$data['fe_gameid']    = get_param('gameid');
		$data['fe_sphoto']    = get_param('sphoto');
		$data['fe_bphoto']    = get_param('bphoto');
		$data['fe_keywords']  = get_param('keywords');
		$data['fe_desc']      = get_param('desc');
		$data['fe_contents']  = htmlspecialchars($_POST['contents']);
		$data['fe_template']  = get_param('template');
		$data['fe_order']     = get_param('order');
		$data['fe_printtime'] = get_param('printtime') ? get_param('printtime'):date('Y-m-d',time());
		$data['fe_timing']    = get_param('timing') ? get_param('timing'):1;
		$data['fe_showtype']  = get_param('showtype') ? get_param('showtype'):3;
		$data['fe_status']    = get_param('status') ? get_param('status'):2;

		$data['fe_adduid'] = $this->my_admin_id;
		$data['fe_addtime'] = THIS_DATETIME;

		// 添加文章
		if ($act == 'add' && $flag != 'up') {
			// 验证参数完整性
			if (empty($data['fe_title'])) {
				showinfo("文章标题不能为空!", "index.php?module=frontend_essay&method=add_essay",3);
			}
			if (empty($data['fe_author'])) {
				showinfo("作者不能为空!", "index.php?module=frontend_essay&method=add_essay",3);
			}
			if (empty($data['fe_rubricid'])) {
				showinfo("请选择所属栏目!", "index.php?module=frontend_essay&method=add_essay",3);
			}
			if ($data['fe_gameid'] == '') {
				showinfo("请选择所属游戏!", "index.php?module=frontend_essay&method=add_essay",3);
			}

			$data['fe_printtime'] = date('Ymd',strtotime($data['fe_printtime']));

			// 添加记录
			$result = add_record($this->db,'frontend_essay',$data);
			if ($result['rows'] > 0) {

				$this->admin_log("添加新文章 ". $data['fp_title'] ." 成功");
                showinfo("添加文章成功!", "index.php?module=frontend_essay&method=essay_list", 4);
			} else {

				// 删除当前上传的图片
            	if(!empty($data['fe_sphoto']) && $this->fileobj->isExists($data['fe_sphoto'])){
                    $this->fileobj->rm($data['fe_sphoto']);
                }
                if(!empty($data['fe_bphoto']) && $this->fileobj->isExists($data['fe_bphoto'])){
                    $this->fileobj->rm($data['fe_bphoto']);
                }
                $this->admin_log("添加新图片 ". $data['fp_title'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=frontend_photos&method=add_photos", 3);
			}
		}

		// 上传图片
		$typeArr = array('','fe_sphoto','fe_bphoto');
		if ($flag == 'up' && !empty($type)) {

			// 重复上传，删除之前上传的文件
            if (!empty($data[$typeArr[$type]])) @unlink(WEBPATH_DIR.$data[$typeArr[$type]]);

			$file = $_FILES[$typeArr[$type]];
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
        $imgArr = array($typeArr[$type]=>$info,'error'.$type=>$error,'success'.$type=>$success);

        // 返回数据处理
        $data['fe_contents'] = htmlspecialchars_decode($data['fe_contents']);
        $virtueArr = (!empty($data['fe_virtue'])) ? explode(',', $data['fe_virtue']):'';

        $this->assign('img',$img);
        $this->assign('size',$size);
        $this->assign('imgArr',$imgArr);
        $this->assign('virtueArr',$virtueArr);
        $this->assign('rubricArr',get_rubric($this->db));
        $this->assign('gameArr',get_game($this->db));
		$this->assign('data',$data);
		$this->assign('type','add');
		$this->display('frontend_essay.html');
	}

	/**
	 * 修改文章
	 * @return [type] [description]
	 */
	public function edit_essay()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('edit_essay')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 获取参数
		$act  = get_param('act');
		$flag = get_param('flag');
		$type = get_param('type');
		$data['sysid']        = get_param('id');
		$data['fe_title']     = get_param('title');
		$data['fe_author']    = get_param('author');
		$data['fe_virtue']    = get_array('virtue',',',true);
		$data['fe_jurl']      = get_param('jurl');
		$data['fe_rubricid']  = get_param('rubricid');
		$data['fe_gameid']    = get_param('gameid');
		$data['fe_sphoto']    = get_param('sphoto');
		$data['fe_bphoto']    = get_param('bphoto');
		$data['fe_keywords']  = get_param('keywords');
		$data['fe_desc']      = get_param('desc');
		$data['fe_contents']  = htmlspecialchars($_POST['contents']);
		$data['fe_template']  = get_param('template');
		$data['fe_order']     = get_param('order');
		$data['fe_printtime'] = get_param('printtime') ? get_param('printtime'):date('Y-m-d',time());
		$data['fe_timing']    = get_param('timing') ? get_param('timing'):1;
		$data['fe_showtype']  = get_param('showtype') ? get_param('showtype'):3;
		$data['fe_status']    = get_param('status') ? get_param('status'):2;

		$data['fe_upuid']  = $this->my_admin_id;
		$data['fe_uptime'] = THIS_DATETIME;

		// 查询图片
		$photos_sql = "select fe_sphoto,fe_bphoto from ".get_table('frontend_essay')." where sysid = ".$data['sysid'];
		$photo = $this->db->getOne($this->db->Query($photos_sql));

		// 修改文章
		if ($act == 'edit' && $flag != 'up') {
			// 验证参数完整性
			if (empty($data['fe_title'])) {
				showinfo("文章标题不能为空!", "index.php?module=frontend_essay&method=add_essay",3);
			}
			if (empty($data['fe_author'])) {
				showinfo("作者不能为空!", "index.php?module=frontend_essay&method=add_essay",3);
			}
			if (empty($data['fe_rubricid'])) {
				showinfo("请选择所属栏目!", "index.php?module=frontend_essay&method=add_essay",3);
			}
			if ($data['fe_gameid'] == '') {
				showinfo("请选择所属游戏!", "index.php?module=frontend_essay&method=add_essay",3);
			}

			$data['fe_printtime'] = date('Ymd',strtotime($data['fe_printtime']));

			// 更新数据
			$result = update_record($this->db,'frontend_essay',$data,array('sysid'=>$data['sysid']),'',1);

			if ($result) {

				// 如果图片有修改，删除数据库中的图片
				if(!empty($data['fe_sphoto']) && $data['fe_sphoto'] != $photo['fe_sphoto'] && $this->fileobj->isExists($photo['fe_sphoto'])){
                    $this->fileobj->rm($photo['fe_sphoto']);
                }
                if(!empty($data['fe_bphoto']) && $data['fe_bphoto'] != $photo['fe_bphoto'] && $this->fileobj->isExists($photo['fe_bphoto'])){
                    $this->fileobj->rm($photo['fe_bphoto']);
                }
                $this->admin_log("修改文章 " . $data['fp_name'] . " 成功");
                showinfo("修改成功!", "index.php?module=frontend_essay&method=essay_list", 4);

            } else {
            	
            	// 如果图片有修改，删除当前上传的图片
            	if(!empty($data['fp_url']) && $this->fileobj->isExists($data['fp_url']) && $data['fp_url'] != $photo['fp_url']){
                    $this->fileobj->rm($data['fp_url']);
                }
                $this->admin_log("修改文章 " . $data['fp_name'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=frontend_essay&method=edit_essay&id=".$data['sysid'], 3);
            }
		}

		// 上传图片
		$typeArr = array('','fe_sphoto','fe_bphoto');
		if ($flag == 'up' && !empty($type)) {

			// 检查是否和数据库中的一样，否则删除
            if (!empty($data[$typeArr[$type]])  && $data[$typeArr[$type]] != $photo[$typeArr[$type]]) {
            	@unlink(WEBPATH_DIR.$data[$typeArr[$type]]);
            }

			$file = $_FILES[$typeArr[$type]];
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
			$sql = "select * from ".get_table('frontend_essay')." where sysid = ".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}
		$img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;
        $imgArr = array($typeArr[$type]=>$info,'error'.$type=>$error,'success'.$type=>$success);

         // 返回数据处理
        $data['fe_contents'] = htmlspecialchars_decode($data['fe_contents']);
        $data['fe_printtime'] = date('Y-m-d',strtotime($data['fe_printtime']));
        $virtueArr = (!empty($data['fe_virtue'])) ? explode(',', $data['fe_virtue']):'';

        $this->assign('img',$img);
        $this->assign('size',$size);
        $this->assign('imgArr',$imgArr);
        $this->assign('virtueArr',$virtueArr);
        $this->assign('rubricArr',get_rubric($this->db));
        $this->assign('gameArr',get_game($this->db));
		$this->assign('data',$data);
		$this->assign('type','edit');
		$this->display('frontend_essay.html');
	}

	/**
	 * 删除文章
	 * @return [type] [description]
	 */
	public function del_essay()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_essay')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$where['sysid'] = get_param('id');
		$name  = get_param('name');
		$img1  = get_param('img1');
		$img2  = get_param('img2');

		$result = delete_record($this->db,'frontend_essay',$where);
		if ($result) {
			// 删除成功
			if(!empty($img1) && $this->fileobj->isExists($img1)){
                $this->fileobj->rm($img1);
            }
            if(!empty($img2) && $this->fileobj->isExists($img2)){
                $this->fileobj->rm($img2);
            }
			$this->admin_log("删除文章 ". $name ." 成功");
            showinfo("删除 ".$name." 成功!", "index.php?module=frontend_essay&method=essay_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除文章 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除 ".$name." 失败,请重试!", "", 3);
		}

	}
}
?>