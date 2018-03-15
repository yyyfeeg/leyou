<?php
/**
 * Copyright (C) 广东星辉天拓互动娱乐有限公司-游戏发行中心技术部
 * @project : 微信官网平台
 * @explain : 渠道管路类
 * @filename : game.class.php
 * @author : cooper
 * @codetime : 20150819
 * @modifier : 
 * @modifytime: 
 */
class Channels extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->upimg        = new upload_image();
        $this->path         = 'lyuploads/channels/'.date("Ymd")."/";
    }

    /**
     * @explain 渠道列表
     *
     */
    public function clist()
    {
        if($this->isadmin!=1 && !$this->checkright("channelslist") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $act = get_param('act');
        $sql        =   "select sysid,gc_cname,gc_gname,gc_gid,gc_packagename,gc_status from ".get_table("game_channels")." order by gc_addtime desc";
        $query = $this->db->Query($sql);
        $garr = get_game($this->db);
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['gc_status'] = $rows['gc_status'] == 1 ? '关闭' : '开启' ;
            $rows['action'] = "<a href='index.php?module=channels&method=cedit&id=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin){
                $rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=channels&method=del&id=".$rows['sysid']."'>[删除]</a>";
            }
            $rows['gc_gid2'] = $garr[$rows['gc_gid']];
            $right_arr[]=array(
                    $rows['sysid'],
                    $rows['gc_gid2']."[".$rows['gc_gid']."]",
                    $rows['gc_gname'],
                    $rows['gc_packagename'],
                    $rows['gc_cname'],
                    $rows['gc_status'],
                    $rows['action'],
                );
        }
        $this->assign("channels","clist");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign('meg','渠道列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display('channels.html');
    }

    /**
     * @explain 渠道添加
     *
     */
    public function cadd()
    {
        if ($this->isadmin != 1 && !$this->checkright("channelsadd"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                        = get_param("act");
        $flag                       = get_param("flag");
        $data['gc_gid']             = get_param("gid")?get_param("gid"):'';
        $data['gc_cname']           = get_param("cname")?get_param("cname"):'';
        $data['gc_cdescription']    = get_param("cdescription")?get_param("cdescription"):'';
        $data['gc_packagename']     = get_param("packagename")?get_param("packagename"):'';
        $data['gc_appid']           = get_param("appid")?get_param("appid"):'';
        $data['gc_appkey']          = get_param("appkey")?get_param("appkey"):'';
        $data['gc_appsecret']       = get_param("appsecret")?get_param("appsecret"):'';
        $data['gc_callback']        = get_param("callback")?get_param("callback"):'';
        $data['gc_gname']           = get_param("gname")?get_param("gname"):'';
        $data['gc_icon']            = get_param("upload_url")?get_param("upload_url"):'';
        $data['gc_splashscreen']    = get_param("upload_url2")?get_param("upload_url2"):'';
        $data['gc_status']          = get_param("status")?1:2;
        $data['gc_addtime']         = THIS_DATETIME;
        $data['gc_addid']           = $this->my_admin_id;
        $upload_url                 = $data['gc_icon']?$data['gc_icon']:'';
        $upload_url2                = $data['gc_splashscreen']?$data['gc_splashscreen']:'';

        if ($act == 'cadd' && $flag != 'up' && $flag != 'up2')
        {
            //判断是否为空
            if(empty($data['gc_gid']) || empty($data['gc_cname']) || empty($data['gc_cdescription']) || empty($data['gc_packagename']) || empty($data['gc_appid']) || empty($data['gc_appkey']) || empty($data['gc_appsecret']) || empty($data['gc_callback']) || empty($data['gc_gname']) || empty($data['gc_icon']) || empty($data['gc_splashscreen'])){
               showinfo("参数有误!", "index.php?module=channels&method=cadd", 3); 
            }
           
            $sql            = "select 1 from ".get_table("game_channels")." where gc_cname='".$data["gc_cname"]."' and gc_gid='".$data["gc_gid"]."'";
            $is_exist       = $this->db->getOne($this->db->query($sql));

            if ($is_exist) {
                //如果存在上传的图片，就删除
                if(!empty($data['gc_icon']) && $this->fileobj->isExists($data['gc_icon'])){
                    $this->fileobj->rm($data['gc_icon']);
                }
                $this->admin_log("添加新渠道" . $data['gc_cname'] . "失败，原因：渠道已存在");
                showinfo("渠道已存在!", "", 3);
            }else{
                $res = add_record($this->db, "game_channels", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log("添加渠道" . $data['gc_cname'] . "成功");
                    showinfo("添加成功!", "index.php?module=channels&method=clist", 4);
                } else {
                    //如果存在上传的图片，就删除
                    if(!empty($data['gc_icon']) && $this->fileobj->isExists($data['gc_icon'])){
                        $this->fileobj->rm($data['gc_icon']);
                    }
                    if(!empty($data['gc_splashscreen']) && $this->fileobj->isExists($data['gc_splashscreen'])){
                        $this->fileobj->rm($data['gc_splashscreen']);
                    }
                    $this->admin_log("添加渠道" . $data['gc_cname'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=channels&method=clist", 4);
                }
            }
        }

        //文件上传开始
        if ($flag == 'up') {
            $file = $_FILES["file"];
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
        }else if($flag == 'up2'){
            $file = $_FILES["file2"];
            $res = $this->fileobj->isDir($this->path);
            if (!$res) {
                $this->fileobj->mkDir($this->path);
            }
            $file['path'] = $this->path;
            $info2 = $this->upimg->upload($file);
            if ($this->upimg->errinfo) {
                $error2 = $this->upimg->errinfo;
            }
            if ($info2) {
                $upload_url2 = $info2; //生成上传图片路径
                $success2 = '恭喜您，上传成功！';
            }
        }
        $img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;
        $this->assign("channels", "cadd");
        $this->assign("gamestr",$this->get_select($data['gc_gid']));

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("info", $upload_url);
        $this->assign("info2", $upload_url2);
        $this->assign("error", $error);
        $this->assign("success", $success);
        $this->assign("error2", $error2);
        $this->assign("success2", $success2);
        $this->assign("data", $data);
        $this->display("channels.html");
    }

    /**
     * @explain 编辑渠道
     *
     */
    public function cedit()
    {
        if ($this->isadmin != 1 && !$this->checkright("channelsadd"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $act                        = get_param("act");
        $flag                       = get_param("flag");
        $data['sysid']              = get_param("id");
        $data['gc_gid']             = get_param("gid")?get_param("gid"):'';
        $data['gc_cname']           = get_param("cname")?get_param("cname"):'';
        $data['gc_cdescription']    = get_param("cdescription")?get_param("cdescription"):'';
        $data['gc_packagename']     = get_param("packagename")?get_param("packagename"):'';
        $data['gc_appid']           = get_param("appid")?get_param("appid"):'';
        $data['gc_appkey']          = get_param("appkey")?get_param("appkey"):'';
        $data['gc_appsecret']       = get_param("appsecret")?get_param("appsecret"):'';
        $data['gc_callback']        = get_param("callback")?get_param("callback"):'';
        $data['gc_gname']           = get_param("gname")?get_param("gname"):'';
        $data['gc_icon']            = get_param("upload_url")?get_param("upload_url"):'';
        $data['gc_splashscreen']    = get_param("upload_url2")?get_param("upload_url2"):'';
        $data['gc_status']          = get_param("status")?1:2;
        $data['gc_uptime']          = THIS_DATETIME;
        $data['gc_upid']            = $this->my_admin_id;
        $upload_url                 = $data['gc_icon']?$data['gc_icon']:'';
        $upload_url2                = $data['gc_splashscreen']?$data['gc_splashscreen']:'';

        if ($act == 'cadd' && $flag != 'up' && $flag != 'up2')
        {
            //判断是否为空
            if(empty($data['gc_gid']) || empty($data['gc_cname']) || empty($data['gc_cdescription']) || empty($data['gc_packagename']) || empty($data['gc_appid']) || empty($data['gc_appkey']) || empty($data['gc_appsecret']) || empty($data['gc_callback']) || empty($data['gc_gname']) || empty($data['gc_icon']) || empty($data['gc_splashscreen'])){
               showinfo("参数有误!", "index.php?module=channels&method=cadd", 3); 
            }

            $sql            = "select 1 from ". get_table("game_channels") . " where gc_cname='".$data["gc_cname"]."' and sysid!={$data['sysid']} and gc_gid!={$data['gc_gid']}";
            $is_exist       = $this->db->getOne($this->db->query($sql));

            if ($is_exist) {
                //如果存在上传的图片，就删除
                if(!empty($data['gc_icon']) && $this->fileobj->isExists($data['gc_icon'])){
                    $this->fileobj->rm($data['gc_icon']);
                }
                $this->admin_log("修改新渠道" . $data['gc_cname'] . "失败，原因：渠道已存在");
                showinfo("渠道已存在!", "", 3);
            }else{
                $result = update_record($this->db,'game_channels',$data,array('sysid'=>$data['sysid']),'',1);

                if ($result) {
                    $this->admin_log("修改渠道" . $data['gc_cname'] . "成功");
                    showinfo("修改成功!", "index.php?module=channels&method=clist", 4);
                } else {
                    //如果存在上传的图片，就删除
                    if(!empty($data['gc_icon']) && $this->fileobj->isExists($data['gc_icon'])){
                        $this->fileobj->rm($data['gc_icon']);
                    }
                    if(!empty($data['gc_splashscreen']) && $this->fileobj->isExists($data['gc_splashscreen'])){
                        $this->fileobj->rm($data['gc_splashscreen']);
                    }

                    $this->admin_log("修改渠道" . $data['gc_cname'] . "失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=channels&method=clist", 4);
                }
            }
        }

        //文件上传开始
        if ($flag == 'up') {
            $file = $_FILES["file"];
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
        }else if($flag == 'up2'){
            $file = $_FILES["file2"];
            $res = $this->fileobj->isDir($this->path);
            if (!$res) {
                $this->fileobj->mkDir($this->path);
            }
            $file['path'] = $this->path;
            $info2 = $this->upimg->upload($file);
            if ($this->upimg->errinfo) {
                $error2 = $this->upimg->errinfo;
            }
            if ($info2) {
                $upload_url2 = $info2; //生成上传图片路径
                $success2 = '恭喜您，上传成功！';
            }
        }else{
            //查询出当前修改数据
            $data = $this->db->getOne($this->db->query("select * from " . get_table("game_channels") . " where sysid=".$data['sysid']));
            $upload_url            = $data['gc_icon'];
            $upload_url2           = $data['gc_splashscreen'];
        }
        $img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;
        $this->assign("channels", "cedit");
        $this->assign("gamestr",$this->get_select($data['gc_gid']));

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("info", $upload_url);
        $this->assign("info2", $upload_url2);
        $this->assign("error", $error);
        $this->assign("success", $success);
        $this->assign("error2", $error2);
        $this->assign("success2", $success2);
        $this->assign("data", $data);
        $this->display("channels.html");
    }

    /**
     * @explain 删除渠道
     *
     */
    public function del()
    {
        if ($this->isadmin != 1 && !$this->checkright("channelsadd")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }
        $where['sysid'] = get_param("id", "int");
        $sql  = "select gc_icon,gc_splashscreen,gc_cnaem from ".get_table("game_channels")." where sysid=".$where["sysid"];
        $pics = $this->db->getOne($this->db->query($sql));
        $ret  = delete_record($this->db, "game_channels", $where);

        if ($ret) {
            //删除图片
            if(!empty($data['gc_icon']) && $this->fileobj->isExists($data['gc_icon'])){
                $this->fileobj->rm($data['gc_icon']);
            }
            if(!empty($data['gc_splashscreen']) && $this->fileobj->isExists($data['gc_splashscreen'])){
                $this->fileobj->rm($data['gc_splashscreen']);
            }
             
            $this->admin_log("删除渠道 " . $pics["gc_cnaem"] . " 成功");
            showinfo("删除成功!", "index.php?module=channels&method=clist", 4);
        } else {
            $this->admin_log("删除渠道 " . $pics["gc_cnaem"] . " 失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
        }
    }

    /**
    * @explain 渠道详细信息
    *
    */
    public function cinfo(){
        //获取详细信息
        $sysid        =  get_param("sysid","int");
        $sql = "SELECT * FROM " . get_table("game_channels") . " where sysid=".$sysid;
        $baseinfo   =  $this->db->getOne($this->db->query($sql));
        $baseinfo['gc_icon']            = "<img src='".$baseinfo['gc_icon']."' width='50px' height='50px'>";
        $baseinfo['gc_splashscreen']    = "<img src='".$baseinfo['gc_splashscreen']."' width='50px' height='50px'>";
        $baseinfo['gc_status'] = $baseinfo['gc_status'] == 1 ? '关闭' : '开启' ;
        $baseinfo['gc_addtime'] = $baseinfo['gc_addtime']?date('Y-m-d H:i:s',$baseinfo['gc_addtime']):'无数据';
        $baseinfo['gc_uptime'] = $baseinfo['gc_uptime']?date('Y-m-d H:i:s',$baseinfo['gc_uptime']):'无数据';
        $garr = get_game($this->db);
        $baseinfo['gc_gid'] = $garr[$baseinfo['gc_gid']];
        $userArr = get_users();
        $baseinfo['gc_upid'] = $userArr[$baseinfo['gc_upid']];
        $baseinfo['gc_addid'] = $userArr[$baseinfo['gc_addid']];
        foreach($baseinfo as $k=>$v){
            if(empty($v) || $v==null){
                $baseinfo[$k] = "无";
            }
        }
        echo json_encode($baseinfo);
    }
}
?>
