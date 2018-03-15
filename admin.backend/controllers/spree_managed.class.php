<?php
#============================================
# 	FileName: spree_managed.class.php
# 		Desc: 前端礼包管理功能类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.04
# LastChange: 
#============================================

class Spree_managed extends Controller
{

	/**
	 * 构造函数
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
        $this->cpath  = '../lyuploads/f_cardnum/'.date("Y")."/".date("m")."/";
	}

	/**
	 * 礼包列表
	 */
	public function spree_list()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('spree_list')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		$gameArr = get_game($this->db);
		$ctypeArr = get_ctype($this->db);
		$openArr = array(1=>'开启',2=>'关闭');
		$giveArr = array(1=>'物品',2=>'礼包码');

		// 查询数据
		$sql = "select * from ".get_table('frontend_spree');
		$query = $this->db->Query($sql);
		while ($rows = $this->db->FetchArray($query)) {
			// 操作
			$action = "<a href='index.php?module=spree_managed&method=edit_spree&id=".$rows['sysid']."'>[修改]</a>";
			if ($this->isadmin) {
				$action .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=spree_managed&method=del_spree&id=".$rows['sysid']."&name=".$rows['gfs_name']."&img=".$rows['gfs_photo']."&img2=".$rows['gfs_icon']."'>[删除]</a>";
			}
			$data[] = array(
				$rows['sysid'],
				$rows['gfs_name'],
				$rows['gfs_desc']? $rows['gfs_desc']:'-',
				htmlspecialchars_decode($rows['gfs_content']),
				$rows['gfs_integral'],
				$rows['gfs_vip'],
				$ctypeArr[$rows['gfs_ctypeid']],
				$gameArr[$rows['gfs_gid']],
				$giveArr[$rows['gfs_give']],
				$rows['gfs_goods']? $rows['gfs_goods']:'-',
				$rows['gfs_goodsid']? $rows['gfs_goodsid']:'-',
				$rows['gfs_goodsnum']? $rows['gfs_goodsnum']:'-',
				$rows['gfs_drawnum'],
				$rows['gfs_allownum'],
				$rows['gfs_allowday'],
				date('Y-m-d H:i:s',$rows['gfs_addtime']),
				$openArr[$rows['gfs_open']],
				$action
				);
		}

		$this->assign('data',get_json_encode($data));
		$this->assign('type','list');
		$this->display('spree_managed.html');
	}

	/**
	 * 添加礼包
	 */
	public function add_spree()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('add_spree')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act = get_param('act');
		$flag = get_param('flag');
		$type = get_param('type');
		$data['gfs_name']     = get_param('sname');
		$data['gfs_desc']     = get_param('desc');
		$data['gfs_content']  = htmlspecialchars(get_param('content'));
		$data['gfs_integral'] = get_param('integral')? get_param('integral'):0;
		$data['gfs_vip']      = get_param('vip')? get_param('vip'):0;
		$data['gfs_ctypeid']  = get_param('ctypeid');
		$data['gfs_keytypeid']= get_param('ktypeid');
		$data['gfs_gid']      = get_param('gid');
		$data['gfs_icon']     = get_param('icon');
		$data['gfs_photo']    = get_param('url');
		$data['gfs_open']     = get_param('open')? get_param('open'):1;
		$data['gfs_hot']      = get_param('hot')? get_param('hot'):2;
		$data['gfs_give']     = get_param('goods')? get_param('goods'):2;
		$data['gfs_goods']    = get_param('goodsname');
		$data['gfs_goodsid']  = get_param('goodsid');
		$data['gfs_goodsnum'] = get_param('goodsnum');
		$data['gfs_allownum'] = get_param('allownum')? get_param('allownum'):0;
		$data['gfs_allowday'] = get_param('allowday')? get_param('allowday'):0;
		$data['gfs_adduid']   = $this->my_admin_id;
		$data['gfs_addtime']  = THIS_DATETIME;

		// 添加数据
		if ($act == 'add' && $flag != 'up') {
			// 检查是否填写礼包名称
			if (empty($data['gfs_name'])) {
				showinfo("请填写礼包名称!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 检查是否填写礼包内容
			if (empty($data['gfs_content'])) {
				showinfo("请填写礼包内容!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 检查是否选择礼包类型
			if (empty($data['gfs_ctypeid'])) {
				showinfo("请选择礼包类型!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 检查是否选择游戏
			if (empty($data['gfs_gid'])) {
				showinfo("请选择游戏!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 检查是否上传礼包icon
			if (empty($data['gfs_icon'])) {
				showinfo("请上传礼包icon!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 检查是否上传礼包截图
			if (empty($data['gfs_photo'])) {
				showinfo("请上传礼包截图!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 物品
			if ($data['gfs_give'] == 1) {
				// 检查是否填写物品名称
				if (empty($data['gfs_goods'])) {
					showinfo("请填写物品名称!", "index.php?module=spree_managed&method=add_spree",3);
				}

				// 检查是否填写物品ID
				if (empty($data['gfs_goodsid'])) {
					showinfo("请填写物品ID!", "index.php?module=spree_managed&method=add_spree",3);
				}

				// 检查是否填写物品数量
				if (empty($data['gfs_goodsnum'])) {
					showinfo("请填写物品数量!", "index.php?module=spree_managed&method=add_spree",3);
				}
			} else {
				// 检查是否选择游戏
				if (empty($data['gfs_keytypeid'])) {
					showinfo("请选择礼包卡类型!", "index.php?module=spree_managed&method=add_spree",3);
				}
			}

			// 添加记录
			$result = add_record($this->db,'frontend_spree',$data);
			if ($result['rows'] > 0) {
                $this->admin_log("添加礼包 ". $data['gfs_name'] ." 成功");
                showinfo("添加成功!", "index.php?module=spree_managed&method=spree_list", 4);
            } else {
                $this->admin_log("添加礼包 ". $data['gfs_name'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=spree_managed&method=add_spree", 3);
            }
		}

		// 上传文件
		if ($flag == 'up'&&!empty($type)) {

			// 重复上传，删除之前上传的文件
            if (!empty($data['gfs_photo'])&&$type=='photo') @unlink(WEBPATH_DIR.$data['gfs_photo']);
            if (!empty($data['gfs_icon'])&&$type=='icon') @unlink(WEBPATH_DIR.$data['gfs_icon']);

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
		$this->assign('data',$data);
		$this->assign('gameArr',get_game($this->db));
		$this->assign('ctypeArr',get_ctype($this->db,1));
		$this->assign('ktypeArr',get_ctype($this->db,2));
		$this->assign('type','add');
		$this->display('spree_managed.html');
	}

    /**
     * 导入礼包码
     */
    public function import_spree()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('import_spree')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $act = get_param('act');
        $flag = get_param('flag');
        $type = get_param('type');
        $ctypeid  = $data['gci_ctypeid']  = get_param('ctypeid')? get_param('ctypeid'):0;
        $ktypeid  = $data['gci_keytypeid'] = get_param('ktypeid')? get_param('ktypeid'):1;
        $gameid   = $data['gci_gid']      = get_param('gid');
        $serverid = $data['gci_sid']      = 999;
        $data['gci_importtime']  = THIS_DATETIME;

        $card_num = get_param("inum");

        // 添加数据
        if ($act == 'add' && $flag != 'up') {
            // 检查是否选择游戏
            if (empty($data['gci_gid'])) {
                showinfo("请选择游戏!", "index.php?module=spree_managed&method=import_spree",3);
            }

            // 检查是否上传礼包码
            if (empty($card_num)) {
                showinfo("请上传礼包码文件!", "index.php?module=spree_managed&method=import_spree",3);
            }

            // 检查是否选择礼包码类型
            if (empty($data['gci_keytypeid'])) {
                showinfo("请选择礼包卡类型!", "index.php?module=spree_managed&method=import_spree",3);
            }

            // 添加记录
            $tmp_array = file($card_num);
            //var_dump($tmp_array);exit;
            // $q = preg_replace('/\n|\r\n/','дк',$tmp_array);
            // $arr = explode('дк',$q);
            $sql  ="insert into ".get_table("cardid_info")."(gci_gid,gci_sid,gci_cardnum,gci_ctypeid,gci_keytypeid,gci_importtime) values ";
            $ns   = 1;
            $sql_val = "";
            foreach($tmp_array as $k=>$v){
                $v = trim($v);
                if($v=="" || empty($v)){
                    continue;
                }
                $sql_val .= "('".$gameid."','".$serverid."','".trim($v)."',".$ctypeid.",".$ktypeid.",".THIS_DATETIME."),";
                //一次插入1000条数据
                if($ns==1000){
                    $sql_val = $sql.rtrim($sql_val, ",");
                    $rs      = $this->db->query($sql_val);
                    $sql_val = "";
                    $ns      = 0;
                }
                $ns++;
            }

            $sql = $sql.rtrim($sql_val, ",");
            $rs  = $this->db->query($sql);
            //写入管理员日志
            // $msg = "管理员：".$logname." 成功导入 ".$g_name."-".$s_name." 新手卡";
            // admin_log($admin_id,$logname,$msg,THIS_DATETIME,$done_ip);
            echo "<script>alert('添加礼包成功');location.href='index.php?module=spree_managed&method=list_spreenum';</script>";
            exit();
        }

        // 上传文件
        if ($flag == 'up'&&!empty($type)) {

            // 重复上传，删除之前上传的文件
            //if (!empty($data['gfs_photo'])&&$type=='photo') @unlink(WEBPATH_DIR.$data['gfs_photo']);

            $url = $type.'_url';
            $err = 'error_'.$type;
            $suc = 'success_'.$type;

            $file = $_FILES[$type];
            $res = $this->fileobj->isDir($this->cpath);
            if (!$res) {
                $this->fileobj->mkDir($this->cpath);
            }
            $file['path'] = $this->cpath;
            $info = $this->upimg->textupload($file);
            if ($this->upimg->errinfo) {
                $error = $this->upimg->errinfo;
            }
            if ($info) {
                $upload_url = $info; //生成上传文件路径
                $success = '恭喜您，上传成功！';
                $files = $info['paths'];
            }
        }
        $size = $GLOBALS['IMG_UP']['text_upload_size'] / 1000000;
        $imgArr = array($url => $info, $err => $error, $suc => $success);

        $this->assign("imgArr", $imgArr);
        $this->assign('files',$files);
        $this->assign('data',$data);
        $this->assign('gameArr',get_game($this->db));
        $this->assign('ctypeArr',$ctypeid);
        $this->assign('ktypeArr',get_ctype($this->db,1));
        $this->assign('type','import');
        $this->display('spree_managed.html');
    }

    /**
     * @explain 礼包码信息列表
     *
     */
    public function list_spreenum(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("list_spreenum")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);

        $where      = " where 1";
        $gid        = get_param("gid");
        $mtype      = get_param("mtype");
        $ctype      = get_param("ctype");
        $action     = get_param("action");
        //条件判定
        if ($action != "submit") {
            $where .= " and 1=0 ";
        }
        $gid?$where         .= " and gci_gid=".get_param("gid"):'';
        $mtype?$where       .= " and gci_keytypeid=".get_param("mtype"):'';
        $ctype?$where       .= " and gci_draw=".get_param("ctype"):'';

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT count(*) c FROM " .get_table('cardid_info'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from " .get_table('cardid_info'). " $where order by sysid desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);
        $str    =  "";
        $draw = array('1'=>'已领取','2'=>'未领取');
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["gci_importtime"] = date('Y-m-d',$rows["gci_importtime"]);
            $rows["gci_drawtime"]   = $rows["gci_drawtime"]?date('Y-m-d',$rows["gci_drawtime"]):'-';
            $rows["gci_gid"]        = $garr[$rows["gci_gid"]]."[".$rows["gci_gid"]."]";
            $rows["gci_draw"]       = $draw[$rows["gci_draw"]];
            $rows['action'] = '-';//"<a href='index.php?module=adsite&method=parentlist&sysid=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin || $this->checkright("parentlist")){
                $rows["action"] = '&nbsp;&nbsp;&nbsp;<a href='."'index.php?module=spree_managed&method=spreedel&sysid=".$rows['sysid']."' onclick='return confirm(".'"确认要删除?"'.")'>[删除]</a>";
            }
            $str .= "<td>".$rows["sysid"]."</td>"."<td>".$rows["gci_importtime"]."</td>"."<td>".$rows["gci_gid"]."</td>"."<td>".$rows["gci_keytypeid"]."</td>"
            ."<td>".$rows["gci_cardnum"]."</td>"."<td>".$rows["gci_uname"]."</td>"."<td>".$rows["gci_drawtime"]."</td>"."<td>".$rows["gci_draw"]."</td><td>".$rows['action']."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=spree_managed&method=list_spreenum&action=submit&gid=$gid&ctype=$ctype&mtype=$mtype";
        $multi = multi($totalrecord["c"], $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord["c"],
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord["c"]/$pagesize),
            'multi' => $multi
        );

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("gamestr",$this->get_select($gid));
        $this->assign("ctypeStr",$this->get_ctype($mtype));
        $this->assign("str",$str);
        $this->assign("gid",$gid);
        $this->assign("mtype",$mtype);
        $this->assign("ctype",$ctype);
        $this->assign("type","spreelist");
        $this->assign('meg','您已进入礼包码列表！<br>--在对应的列输入搜索信息');
        $this->display("spree_managed.html");
    }

    /**
     * 添加礼包类型
     */
    public function add_spreetype()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('add_spreetype')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $act = get_param('act');
        $flag = get_param('flag');
        $type = get_param('type');
        $data['gst_name']     = get_param('name');
        $data['gst_desc']     = get_param('desc');
        $data['gst_open']     = get_param('open')? get_param('open'):1;
        $data['gst_allownum'] = get_param('ctype')? get_param('ctype'):0;
        $data['gst_allowday'] = get_param('allowday')? get_param('allowday'):0;
        $data['gst_platform'] = get_param('plat')? get_param('plat'):1;
        $data['gst_adduid']   = $this->my_admin_id;
        $data['gst_addtime']  = THIS_DATETIME;

        // 添加数据
        if ($act == 'add' ) {
            // 检查参数
            if (empty($data['gst_name'])) {
                showinfo("礼包分类名称不能为空!", "index.php?module=spree_managed&method=add_spreetype",3);
            }

            // 添加记录
            $result = add_record($this->db,'spree_type',$data);
            if ($result['rows'] > 0) {

                $this->admin_log("添加礼包码类型". $data['gst_name'] ." 成功");
                showinfo("添加成功!", "index.php?module=spree_managed&method=list_spreetype", 4);
            } else {
                $this->admin_log("添加礼包码类型 ". $data['gst_name'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=spree_managed&method=add_spreetype", 3);
            }
        }

        $this->assign('data',$data);
        $this->assign('type','addtype');
        $this->display('spree_managed.html');
    }

    /**
     * 修改礼包类型
     */
    public function spreetypeedit()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('spreetypeedit')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $act = get_param('act');
        $flag = get_param('flag');
        $type = get_param('type');
        $data['sysid']        = get_param('sysid');
        $data['gst_name']     = get_param('name');
        $data['gst_desc']     = get_param('desc');
        $data['gst_open']     = get_param('open')? get_param('open'):1;
        $data['gst_allownum'] = get_param('ctype')? get_param('ctype'):0;
        $data['gst_allowday'] = get_param('allowday')? get_param('allowday'):0;
        $data['gst_platform'] = get_param('plat')? get_param('plat'):1;
        $data['gst_upuid']    = $this->my_admin_id;
        $data['gst_uptime']   = THIS_DATETIME;

        // 添加数据
        if ($act == 'edit' ) {
            // 检查参数
            if (empty($data['gst_name'])) {
                showinfo("礼包分类名称不能为空!", "index.php?module=spree_managed&method=spreetypeedit",3);
            }

            // 更新数据
            $result = update_record($this->db,'spree_type',$data,array('sysid'=>$data['sysid']),'',1);

            if ($result) {
                $this->admin_log("修改礼包卡分类成功");
                showinfo("修改成功!", "index.php?module=spree_managed&method=list_spreetype", 4);
            } else {
                $this->admin_log("修改礼包卡分类失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=spree_managed&method=spreetypeedit&id=".$data['sysid'], 3);
            }
        }else{
            //查询出当前修改数据
            $data = $this->db->getOne($this->db->query("select * from " . get_table("spree_type") . " where sysid=".$data['sysid']));
        }

        $this->assign('data',$data);
        $this->assign('sysid',$data['sysid']);
        $this->assign('plat',$data['gst_platform']);
        $this->assign('open',$data['gst_open']);
        $this->assign('type','spreetypeedit');
        $this->display('spree_managed.html');
    }


    /**
     * @explain 礼包码类型信息列表
     *
     */
    public function list_spreetype(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("list_spreetype")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);

        $where      = " where 1";
        $name       = get_param("name");

        $name?$where         .= " and gst_name like '%".get_param("name")."%'":'';

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT count(*) c FROM " .get_table('spree_type'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from " .get_table('spree_type'). " $where order by sysid desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);
        $str    =  "";
        $open = array('1'=>'开启','2'=>'关闭');
        $plat = array('1'=>'是','2'=>'否');
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["gst_open"]       = $open[$rows["gst_open"]];
            $rows["gst_platform"]   = $plat[$rows["gst_platform"]];
            $rows['action'] = "<a href='index.php?module=spree_managed&method=spreetypeedit&sysid=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin || $this->checkright("list_spreetype")){
                $rows["action"] .= '&nbsp;&nbsp;&nbsp;<a href='."'index.php?module=spree_managed&method=spreetypedel&sysid=".$rows['sysid']."&name=".$rows['gst_name']."' onclick='return confirm(".'"确认要删除?"'.")'>[删除]</a>";
            }
            $str .= "<td>".$rows["sysid"]."</td>"."<td>".$rows["gst_name"]."</td>"."<td>".$rows["gst_open"]."</td>"."<td>".$rows["gst_platform"]."</td>"
            ."<td>".$rows["gst_adduid"]."</td><td>".$rows['action']."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=spree_managed&method=list_spreetype&name=$name";
        $multi = multi($totalrecord["c"], $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord["c"],
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord["c"]/$pagesize),
            'multi' => $multi
        );

        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("str",$str);
        $this->assign("gst_name",$name);
        $this->assign("type","spreetype");
        $this->assign('meg','您已进入礼包码类型列表！<br>--在对应的列输入搜索信息');
        $this->display("spree_managed.html");
    }

	/**
	 * 修改礼包
	 */
	public function edit_spree()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('edit_spree')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$act = get_param('act');
		$flag = get_param('flag');
		$type = get_param('type');
		$data['sysid']        = get_param('id');
		$data['gfs_name']     = get_param('sname');
		$data['gfs_desc']     = get_param('desc');
		$data['gfs_content']  = htmlspecialchars(get_param('content'));
		$data['gfs_integral'] = get_param('integral')? get_param('integral'):0;
		$data['gfs_vip']      = get_param('vip')? get_param('vip'):0;
		$data['gfs_ctypeid']  = get_param('ctypeid');
		$data['gfs_keytypeid']= get_param('ktypeid');
		$data['gfs_gid']      = get_param('gid');
		$data['gfs_icon']     = get_param('icon');
		$data['gfs_photo']    = get_param('url');
		$data['gfs_open']     = get_param('open')? get_param('open'):1;
		$data['gfs_hot']      = get_param('hot')? get_param('hot'):2;
		$data['gfs_give']     = get_param('goods')? get_param('goods'):2;
		$data['gfs_goods']    = get_param('goodsname');
		$data['gfs_goodsid']  = get_param('goodsid');
		$data['gfs_goodsnum'] = get_param('goodsnum');
		$data['gfs_allownum'] = get_param('allownum')? get_param('allownum'):0;
		$data['gfs_allowday'] = get_param('allowday')? get_param('allowday'):0;
		$data['gfs_upuid']    = $this->my_admin_id;
		$data['gfs_uptime']   = THIS_DATETIME;

		// 修改
		if ($act == 'edit' && $flag != 'up') {
			// 检查是否填写礼包名称
			if (empty($data['gfs_name'])) {
				showinfo("请填写礼包名称!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
			}

			// 检查是否填写礼包内容
			if (empty($data['gfs_content'])) {
				showinfo("请填写礼包内容!","index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
			}

			// 检查是否选择礼包类型
			if (empty($data['gfs_ctypeid'])) {
				showinfo("请选择礼包类型!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
			}

			// 检查是否选择游戏
			if (empty($data['gfs_gid'])) {
				showinfo("请选择游戏!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
			}

			// 检查是否上传礼包icon
			if (empty($data['gfs_icon'])) {
				showinfo("请上传礼包icon!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 检查是否上传礼包截图
			if (empty($data['gfs_photo'])) {
				showinfo("请上传礼包截图!", "index.php?module=spree_managed&method=add_spree",3);
			}

			// 物品
			if ($data['gfs_give'] == 1) {
				// 检查是否填写物品名称
				if (empty($data['gfs_goods'])) {
					showinfo("请填写物品名称!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
				}

				// 检查是否填写物品ID
				if (empty($data['gfs_goodsid'])) {
					showinfo("请填写物品ID!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
				}

				// 检查是否填写物品数量
				if (empty($data['gfs_goodsnum'])) {
					showinfo("请填写物品数量!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
				}
			} else {
				// 检查是否选择游戏
				if (empty($data['gfs_keytypeid'])) {
					showinfo("请选择礼包卡类型!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'],3);
				}
			}

			// 更新数据
			$result = update_record($this->db,'frontend_spree',$data,array('sysid'=>$data['sysid']),'',1);

			if ($result) {
                $this->admin_log("修改礼包 " . $data['gfs_name'] . " 成功");
                showinfo("修改成功!", "index.php?module=spree_managed&method=spree_list", 4);
            } else {
                $this->admin_log("修改礼包 " . $data['gfs_name'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=spree_managed&method=edit_spree&id=".$data['sysid'], 3);
            }
		}

		// 上传文件
		if ($flag == 'up'&&!empty($type)) {
			// 检查是否和数据库中的一样，否则删除
            if (!empty($data['gfs_photo'])  && $data['gfs_photo'] != $photo['gfs_photo'] && $type == 'photo') {
            	@unlink(WEBPATH_DIR.$data['gfs_photo']);
            }
            if (!empty($data['gfs_icon'])  && $data['gfs_icon'] != $photo['gfs_icon'] && $type == 'icon') {
            	@unlink(WEBPATH_DIR.$data['gfs_icon']);
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
			// 查询原始数据
			$sql = "select * from ".get_table('frontend_spree')." where sysid = ".$data['sysid'];
			$data = $this->db->getOne($this->db->Query($sql));
		}
		$img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;
        $imgArr = array($url => $info, $err => $error, $suc => $success);

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("imgArr", $imgArr);
		$this->assign('data',$data);
		$this->assign('gameArr',get_game($this->db));
		$this->assign('ctypeArr',get_ctype($this->db,1));
		$this->assign('ktypeArr',get_ctype($this->db,2));
		$this->assign('type','edit');
		$this->display('spree_managed.html');
	}

	/**
	 * 删除礼包
	 */
	public function del_spree()
	{
		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('del_spree')) {
			showinfo("你没有权限执行该操作。","",2);
		}

		// 接收参数
		$name = get_param('name');
		$img  = get_param('img');
		$img2 = get_param('img2');
		$where['sysid'] = get_param('id');

		$result = delete_record($this->db,'frontend_spree',$where);
		if ($result) {
			if(!empty($img) && $this->fileobj->isExists($img)){
                $this->fileobj->rm($img);
            }
            if(!empty($img2) && $this->fileobj->isExists($img2)){
                $this->fileobj->rm($img2);
            }
			$this->admin_log("删除礼包 ". $name ." 成功");
            showinfo("删除礼包 ".$name." 成功!", "index.php?module=spree_managed&method=spree_list", 4);
		} else {
			// 删除失败
			$this->admin_log("删除礼包 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除礼包 ".$name." 失败,请重试!", "", 3);
		}
	}

    /**
     * 删除礼包码
     */
    public function spreedel()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('spreedel')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $where['sysid'] = get_param('sysid');

        $result = delete_record($this->db,'cardid_info',$where);
        if ($result) {
            $this->admin_log("删除礼包码成功");
            showinfo("删除礼包码成功!", "index.php?module=spree_managed&method=list_spreenum", 4);
        } else {
            // 删除失败
            $this->admin_log("删除礼包码失败，原因:数据库删除失败");
            showinfo("删除礼包码失败,请重试!", "", 3);
        }
    }

    /**
     * 删除礼包码类型
     */
    public function spreetypedel()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('spreetypedel')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 接收参数
        $name = get_param('name');
        $where['sysid'] = get_param('sysid');

        $result = delete_record($this->db,'spree_type',$where);
        if ($result) {
            $this->admin_log("删除礼包码类型 ". $name ." 成功");
            showinfo("删除礼包码 ".$name." 成功!", "index.php?module=spree_managed&method=list_spreetype", 4);
        } else {
            // 删除失败
            $this->admin_log("删除礼包码类型 ". $name ." 失败，原因:数据库删除失败");
            showinfo("删除礼包码类型 ".$name." 失败,请重试!", "", 3);
        }
    }
}
?>