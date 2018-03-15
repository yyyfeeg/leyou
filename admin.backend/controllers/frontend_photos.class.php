<?php
#============================================
# 	FileName: frontend_photos.class.php
# 		Desc: 图片管理功能类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.16
# LastChange: 
#============================================

class Frontend_photos extends Controller
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
        $this->path  = '../lyuploads/f_photos/'.date("Y")."/".date("m")."/";
	}

	/**
	 * 图片列表
	 * @return [type] [description]
	 */
	public function photos_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('photos_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 状态
		$statusArr = array('','未发布','已发布','已下架');

		// 查询数据
		$type_name_arr = get_phototype($this->db);
		$sql = "select sysid,fp_typeid,fp_title,fp_url,fp_sphoto,fp_jurl,fp_order,fp_status,fp_QRcode_url from ".get_table('frontend_photos');
		$query = $this->db->Query($sql);

		while ($rows = $this->db->FetchArray($query))
		{
			$rows['fp_status'] = $statusArr[$rows['fp_status']];
			$rows['img']  = "<img src='".$rows['fp_url']."' width='100%' height='100px'/>";
			$rows['simg']  = ($rows['fp_sphoto'])? "<img src='".$rows['fp_sphoto']."' width='100%' height='100px'/>":'无';
			
			// 操作
			$action = "<a href='index.php?module=frontend_photos&method=edit_photos&id=".$rows['sysid']."'>[修改]</a>";
			if ($this->isadmin) {
				$action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=frontend_photos&method=del_photos&id=".$rows['sysid']."&name=".$rows['fp_title']."&img=".$rows['fp_url']."&img2=".$rows['fp_sphoto']."'>[删除]</a>";
			}

			$data[] = array(
				$rows['sysid'],
				$rows['simg'],
				$rows['img'],
				$type_name_arr[$rows['fp_typeid']],
				$rows['fp_title'],
				$rows['fp_jurl'],
				$rows['fp_order'],
				$rows['fp_status'],
				$action
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->assign('meg','图片列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
		$this->display('frontend_photos.html');
	}

	/**
	 * 添加图片
	 * @return [type] [description]
	 */
	public function add_photos()
	{	
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_photos')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act = get_param('act');
		$flag = get_param('flag');
		$type = get_param('type');
		$data['fp_typeid'] = get_param('typeid');
		$data['fp_title']  = get_param('title');
		$data['fp_sphoto'] = get_param('sphoto');
		$data['fp_url']    = get_param('url');
		$data['fp_jurl']   = get_param('jurl');
		$data['fp_desc']   = get_param('desc');
		$data['fp_order']  = get_param('order');
		$data['fp_status'] = get_param('status') ? get_param('status'):2;

		$data['fp_adduid'] = $this->my_admin_id;
		$data['fp_addtime'] = THIS_DATETIME;

		if ($act == 'add' && $flag != 'up') {
			// 检查参数
			if (empty($data['fp_typeid'])) {
				showinfo("图片分类不能为空!", "index.php?module=frontend_photos&method=add_photos",3);
			}
			if (empty($data['fp_title'])) {
				showinfo("图片标题不能为空!", "index.php?module=frontend_photos&method=add_photos",3);
			}
			if (empty($data['fp_url'])) {
				showinfo("请上传图片!", "index.php?module=frontend_photos&method=add_photos",3);
			}
			if (empty($data['fp_jurl'])) {
				showinfo("跳转地址不能为空!", "index.php?module=frontend_photos&method=add_photos",3);
			}

			// 添加记录
			$result = add_record($this->db,'frontend_photos',$data);
			if ($result['rows'] > 0) {

                $this->admin_log("添加新图片 ". $data['fp_title'] ." 成功");
                showinfo("添加成功!", "index.php?module=frontend_photos&method=photos_list", 4);
            } else {

            	// 删除当前上传的图片
            	 if(!empty($data['fp_url']) && $this->fileobj->isExists($data['fp_url'])){
                    $this->fileobj->rm($data['fp_url']);
                }
                $this->admin_log("添加新图片 ". $data['fp_title'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=frontend_photos&method=add_photos", 3);
            }
		}

		// 上传文件
		if ($flag == 'up' && !empty($type)) {

			// 重复上传，删除之前上传的文件
            if (!empty($data['fp_url'])&&$type == 'bphoto') @unlink(WEBPATH_DIR.$data['fp_url']);
            if (!empty($data['fp_sphoto'])&&$type == 'sphoto') @unlink(WEBPATH_DIR.$data['fp_sphoto']);

            $url = $type.'_url';
            $err = 'error_'.$type;
            $suc = 'success_'.$type;

            $file = $_FILES[$type];
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
        $imgArr = array($url => $info, $err => $error, $suc => $success);

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("imgArr", $imgArr);
        $this->assign("data", $data);
        $this->assign("typeArr", get_phototype($this->db));
		$this->assign('type','add');
		$this->display('frontend_photos.html');
	}

	/**
	 * 修改图片
	 * @return [type] [description]
	 */
	public function edit_photos()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('edit_photos')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$act   = get_param('act');
		$flag  = get_param('flag');
		$type  = get_param('type');
		$data['sysid']     = get_param('id');
		$data['fp_typeid'] = get_param('typeid');
		$data['fp_title']  = get_param('title');
		$data['fp_sphoto'] = get_param('sphoto');
		$data['fp_url']    = get_param('url');
		$data['fp_jurl']   = get_param('jurl');
		$data['fp_desc']   = get_param('desc');
		$data['fp_order']  = get_param('order');
		$data['fp_status'] = get_param('status');

		$data['fp_upuid'] = $this->my_admin_id;
		$data['fp_uptime'] = THIS_DATETIME;

		// 查询数据库中图片信息
		$sql = "select fp_url,fp_sphoto,fp_QRcode_url from ".get_table('frontend_photos')." where sysid = ".$data['sysid'];
		$photo = $this->db->getOne($this->db->Query($sql));

		if ($act == 'edit' && $flag != 'up') {
			// 检查参数
			if (empty($data['fp_typeid'])) {
				showinfo("图片分类不能为空!", "index.php?module=frontend_photos&method=add_photos",3);
			}
			if (empty($data['fp_title'])) {
				showinfo("图片标题不能为空!", "index.php?module=frontend_photos&method=add_photos",3);
			}
			if (empty($data['fp_url'])) {
				showinfo("请上传图片!", "index.php?module=frontend_photos&method=add_photos",3);
			}
			if (empty($data['fp_jurl'])) {
				showinfo("跳转地址不能为空!", "index.php?module=frontend_photos&method=add_photos",3);
			}

			// 更新数据
			$result = update_record($this->db,'frontend_photos',$data,array('sysid'=>$data['sysid']),'',1);
			
			if ($result) {

				// 如果图片有修改，删除数据库中的图片
				if(!empty($data['fp_url']) && $data['fp_url'] != $photo['fp_url'] && $this->fileobj->isExists($photo['fp_url'])){
                    $this->fileobj->rm($photo['fp_url']);
                }
                if(!empty($data['fp_sphoto']) && $data['fp_sphoto'] != $photo['fp_sphoto'] && $this->fileobj->isExists($photo['fp_sphoto'])){
                    $this->fileobj->rm($photo['fp_sphoto']);
                }
                $this->admin_log("修改图片 " . $data['fp_name'] . " 成功");
                showinfo("修改成功!", "index.php?module=frontend_photos&method=photos_list", 4);
            } else {
            	
            	// 如果图片有修改，删除当前上传的图片
            	if(!empty($data['fp_url']) && $this->fileobj->isExists($data['fp_url']) && $data['fp_url'] != $photo['fp_url']){
                    $this->fileobj->rm($data['fp_url']);
                }
                if(!empty($data['fp_sphoto']) && $this->fileobj->isExists($data['fp_sphoto']) && $data['fp_sphoto'] != $photo['fp_sphoto']){
                    $this->fileobj->rm($data['fp_sphoto']);
                }
                $this->admin_log("修改图片 " . $data['fp_name'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=frontend_photos&method=edit_photos&id=".$data['sysid'], 3);
            }

		} 
		// 上传文件
		if ($flag == 'up') {
			// 检查是否和数据库中的一样，否则删除
            if (!empty($data['fp_url'])  && $data['fp_url'] != $photo['fp_url'] && $type == 'bphoto') {
            	@unlink(WEBPATH_DIR.$data['fp_url']);
            }
            if (!empty($data['fp_sphoto'])  && $data['fp_sphoto'] != $photo['fp_sphoto'] && $type == 'sphoto') {
            	@unlink(WEBPATH_DIR.$data['fp_sphoto']);
            }

            $url = $type.'_url';
            $err = 'error_'.$type;
            $suc = 'success_'.$type;
            
            $file = $_FILES[$type];
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
			$sql = "select * from ".get_table('frontend_photos')." where sysid = ".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}
		$img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;
        $imgArr = array($url => $info, $err => $error, $suc => $success);

		$this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("imgArr", $imgArr);
        $this->assign("data", $data);
        $this->assign("typeArr", get_phototype($this->db));
		$this->assign('type','edit');
		$this->display('frontend_photos.html');
	}

	/**
	 * 删除图片
	 * @return [type] [description]
	 */
	public function del_photos()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_photos')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$name = get_param('name');
		$img  = get_param('img');
		$img2 = get_param('img2');
		$qrimg = get_param('qrimg');
		$where['sysid'] = get_param('id');

		$result = delete_record($this->db,'frontend_photos',$where);
		if ($result) {
			// 删除成功
			if(!empty($img) && $this->fileobj->isExists($img)){
                $this->fileobj->rm($img);
            }
            if(!empty($img2) && $this->fileobj->isExists($img2)){
                $this->fileobj->rm($img2);
            }
            if(!empty($qrimg) && $this->fileobj->isExists($qrimg)){
                $this->fileobj->rm($qrimg);
            }
			$this->admin_log("删除图片 ". $name ." 成功");
            showinfo("删除 ".$name." 成功!", "index.php?module=frontend_photos&method=photos_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除图片 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除 ".$name." 失败,请重试!", "", 3);
		}

	}
}
?>