<?php
/* * ************
  @用户管理类
  @CopyRight teamtop
  @file:user.class.php
  @author jericho
  @email 190777721@qq.com
  @2013-06-03
 * ************* */

class User extends Controller{
    function __construct() {
        parent::__construct();
        if (!$this->checklogin()) 
    	{
                showinfo("","index.php",1);
    	}
        $this->upimg        = new upload_image();
        $this->path         = 'lyuploads/user/'.date("Ymd")."/";
        $this->fonts        = WEBPATH_DIR."templates/fonts/msyh.ttf";
    }
    
    //用户列表
    function listuser(){
	if($this->isadmin!=1 && !$this->checkright("listuser") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $condition =" where 1=1 ";
        if($this->isadmin!=1){
            $condition .= " and a.a_groupid in(".$this->get_child_group($this->myad_groupid).")";
        }
        $group_list = get_group($this->db);
        $act = get_param('act');
        if($act == 'search'){
            $a_name = get_param('a_name');
            $a_truename = get_param('a_truename');
            $a_groupid = get_param('a_groupid');
            
            if(!empty($a_name)){
                $condition .=" and a.a_name like '%".$a_name."%'";
            }
            if(!empty($a_truename)){
                $condition .=" and a.a_truename like '%".$a_truename."%'";
            }
            if($a_groupid != 0){
                $condition .=" and a.a_groupid = ".$a_groupid;
            }
        }
        $totalrecord = $this->db->getOne($this->db->Query("select count(*) as nums from " . get_table("admin") ." as a left join ".get_table("admin_group")." as g on a.a_groupid=g.sysid $condition"));
        $sql  = "select a.a_name,a.sysid,a.a_truename,a.a_islock,a.a_insertdate,a.a_lastdate,a.a_userid,a.a_lastip,a.a_lognum,a.a_groupid,a.a_img,g.ag_groupname,b.a_name logname,b.a_truename truename from ".  get_table("admin")." as a left join ".get_table("admin_group")." as g on a.a_groupid=g.sysid left join `gsys_admin` as b on a.a_userid = b.sysid $condition";
        $query = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            $rows['a_lastdate'] = date('Y-m-d H:i:s',$rows['a_lastdate']);
            $rows['a_insertdate'] = date('Y-m-d H:i:s',$rows['a_insertdate']);
            $rows['a_name'] = $rows['a_name'].'['.$rows['sysid'].'][点我]';

            if($this->isadmin == 1 || $this->myad_groupid != $rows['a_groupid']){
                if($rows['a_islock'] == 1){
                    $rows['action'] = "<a href='index.php?module=user&method=userlock&uid=".$rows['sysid']."&act=unlock'>[解锁] </a>&nbsp;&nbsp;&nbsp;";
                }else{
                    $rows['action'] = "<a href='index.php?module=user&method=userlock&uid=".$rows['sysid']."&act=lock'>[锁定]</a>&nbsp;&nbsp;&nbsp;";
                }
                //如果是超级管理员权限或者用户本身或者添加该用户的人：
                if($this->isadmin==1 || $this->my_admin_id==$rows["sysid"] || $this->checkright("edit_right")){
                    $rows["action"] .= "<a href='index.php?module=user&method=edit_right&uid=".$rows['sysid']."&act=edit'>[修改权限] </a>&nbsp;&nbsp;&nbsp;";
                }
                
                $rows['action'] .= "<a href='index.php?module=user&method=updatepri&uid=".$rows['sysid']."'>[修改权限组]</a>&nbsp;&nbsp;&nbsp;";
            }else{
                $rows['action'] = "-";
            }
            if($this->isadmin == 1){
                $rows["action"] .= "<a href='index.php?module=user&method=userdel&uid=".$rows['sysid']."&img=".$rows['a_img']."'>[删除] </a>";
            }
            $rows['a_islock'] = $rows['a_islock'] == 1 ? '锁定' : '正常' ;
            
            $right_arr[]=array(
                $rows['a_name'],
                $rows['a_truename'],
                $rows['a_lastdate'],
                $rows['a_lastip'],
                $rows['a_lognum'],
                $rows['ag_groupname'],
                $rows['logname'],
                $rows['a_truename'],
                $rows['a_insertdate'],
                $rows['a_islock'],
                $rows['action'],
            );
        }
        $this->assign("manager","list");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign('meg','您已进入用户中心！');
        $this->display('user.html');
    }
    
    //修改权限
    function edit_right(){
        $id  = intval(get_param("uid"));
        $act = get_param("acts");
        
        if($this->isadmin!=1){//检查是否超出权限
            $myu = array();
            $sql = "select sysid from ".  get_table('admin')." where a_groupid in(".$this->get_child_group($this->myad_groupid).") and a_groupid != ".$this->myad_groupid;
            $query_myu = $this->db->query($sql);
            while($row = $this->db->FetchArray($query_myu)){
                array_push($myu, $row['sysid']);
            }
        }
        
        $sql = 'select a.sysid, a.a_name,a.a_groupid,a.a_rightid, a.a_isadmin from '.get_table("admin").' as a where a.sysid = '.$id;
        $rst = $this->db->Query($sql);
        $dataArr = $this->db->getOne($rst);
        $names  = $dataArr["a_name"];
        $gid    = $dataArr["a_groupid"];
        $myright= explode(',',$dataArr["a_rightid"]);
        
        //仅超管,用户本身或有该权限的用户或用户添加人
        if($this->isadmin!=1 && $id!=$this->my_admin_id && !$this->checkright("edit_right") && !in_array($id, $myu)){
            showinfo("你没有权限执行该操作。","",3);
        }
        
        $groupsql = 'select sysid,ag_groupname,ag_rightid,ag_addtime,ag_userid,ag_content from ' . get_table('admin_group') . ' where sysid = ' . $gid;
        $group_list = $this->db->getOne($this->db->query($groupsql));
        $rights = explode(',',$group_list['ag_rightid']);
         
        $dataArr = array();
        $rsql = "select sysid as menuid,ar_rightid,ar_rightname as menuname,ar_ismenu,ar_parentid as pid from " . get_table("admin_right") . " where ar_islock=2 and ar_rightid in (" . addquote(',', $rights, '"') . ")  order by sysid";
        $query = $this->db->query($rsql);
        $dataArr = $this->db->getAll($query);
        $menu = getTrees($dataArr);

        foreach ($menu['menus'] as $gpk => $gpv) {
            if (isset($gpv['menus']) && count($gpv['menus']) > 0) {
                foreach ($gpv['menus'] as $pk => $pv) {
                    $pcheckd = (in_array($pv['ar_rightid'], $myright)) ? 'checked' : '';

                    $list[$pv['pid']]['p'][$pv['menuid']] = "<label><input type='checkbox' " . $pcheckd . " onclick='gets(".$pv['menuid'].");getp(".$gpv['menuid'].");' name='rightid[]' value='" . $pv['ar_rightid'] . "' class='" . $gpv['menuid'] . "oo box " . $gpv['menuid'] . "c " . $gpv['menuid'] . "_" . $pv['menuid'] . "pp' id='" . $pv['menuid'] . "c' alt='1'><span class='text' for='" . $pv['ar_rightid'] . "c'>" . $pv['menuname'] . "</span></label>";

                    if (isset($pv['child']) && count($pv['child']) > 0) {
                        $list[$pv['pid']]['p'][$pv['menuid']] .= "<label class='box'>─▶</label>";
                        foreach ($pv['child'] as $ck => $cv) {
                            $ccheckd = (in_array($cv['ar_rightid'], $myright)) ? 'checked' : '';
                            if($cv['ar_ismenu']==2){
                               $color = "style='color:red'";
                            }else{
                               $color = "";
                            }
                            $list[$pv['pid']]['p'][$pv['menuid']] .= "<label><input type='checkbox' " . $ccheckd . " onclick='getpp(" . $gpv['menuid'] . "," . $pv['menuid'] . ")' name='rightid[]' value='" . $cv['ar_rightid'] . "' class='" . $pv['menuid'] . "o box " . $gpv['menuid'] . "c' id='" . $cv['ar_rightid'] . "c' alt='1'><span class='text' $color for='" . $cv['ar_rightid'] . "c'>" . $cv['menuname'] . "</span></label>";
                        }
                        $list[$pv['pid']]['p'][$pv['menuid']] .='<br/>';
                    }
                }

                $lsit[$pv['pid']]['p'][$pv['menuid']] ='<label><input type="checkbox" value="2" name="terrace[]"><span class="text">123</span></label>';
                $gcheckd = (in_array($gpv['ar_rightid'],$rights))? 'checked':'';
                $list[$gpv['menuid']]['gp'] = "<label><input type='checkbox' " . $gcheckd . " onclick='get(" . $gpv['menuid'] . ")' value='" . $gpv['ar_rightid'] . "'  id='" . $gpv['menuid'] . "p' class='p " . $gpv['menuid'] . "p " . $gpv['menuid'] . "pp' name='rightid[]' alt='1' /><span class='text'>".$gpv['menuname'].'</span></label>';
            }
        }

        
        //保存权限信息
        if($act == "save_right"){
             $rights = get_array("rightid");
             $data['a_rightid'] = get_array("rightid", ',', true); //转换成字符串，并且消除重复
             $data['a_jmenu']   = get_json_encode(get_tree($this->db, '', false, false, $rights));
             $where["sysid"]    = $id;
             $res = update_record($this->db, 'admin', $data, $where, '');
            if ($res) {
                $message = '管理员'.$this->myad_realname.'修改用户:' .$names."个人权限成功";
                $this->admin_log($message);
                showinfo("个人权限修改成功", "",3);
            } else {
                $message = '管理员'.$this->myad_realname.'修改用户:' .$names."个人权限失败!";
                $this->admin_log($message);
                showinfo("个人权限修改失败！请重试", "", 3);
            }
        }
        $this->assign('id',$id);
        $this->assign('list', $list);
        $this->assign("manager","edit_right");
        $this->display("user.html");
    }
    
    
    //我的权限
    function myright(){
        $baseinfo  =  array();
        $baseinfo['myidentify']   =  ($this->isadmin==1)?"超级管理员":"管理员";
        $baseinfo['uid']          =  $this->my_admin_id;
        $groupname                =  $this->db->getOne($this->db->query("select sysid,ag_groupname from ".get_table("admin_group")." where sysid=".$this->myad_groupid));
        $baseinfo['ag_groupname'] =  $groupname['ag_groupname'];
        $rightinfo                =  get_groupright($groupname['sysid'],$this->db);
        
        $myright = '';
        $i=1;
        //初始化可执行权限
        foreach($rightinfo[$groupname['sysid']."right"] as $k => $v){
            $myright .= "<li style='float: left; width: 165px; height: 20px;'>".$i.".".$v['rightname']."</li>";
            $i++;
        }
        $mydata = '';
        $i=1;
        
        //如果具有超级管理员权限，则展示所有数据项：
        if($_SESSION['isadmin']==1){
            $mydata = "<li style='color:red'>所有数据查看权限</li>";
            $action = "<a href='index.php?module=user&method=updatepri&uid=".$baseinfo['uid']."'>[分配权限]</a>&nbsp;&nbsp;&nbsp;";
        }
        $action .= "<a href='index.php?module=user&method=userinfo&uid=".$baseinfo['uid']."'>[个人信息]</a>";
        
        $data[] = array('title' => '身份特征：','content' => $baseinfo['myidentify']);
        $data[] = array('title' => '所在权限组：','content' => empty($baseinfo['ag_groupname']) ? '[空]' : $baseinfo['ag_groupname']);
        $data[] = array('title' => '可执行权限：','content' => $myright);
        $data[] = array('title' => '数据权限：','content' => $mydata);
        $data[] = array('title' => '操作：','content' => $action);
        
        $tlist['total']     =  count($data);
        $tlist['rows']      =  $data;
        $this->assign("str",get_json_encode($tlist));
        
        $this->assign("baseinfo",$baseinfo);
        $this->assign("manager","myright");
        $this->assign("accessright",$rightinfo[$groupname['sysid']."right"]);
        $this->display("user.html");
    }
    
    //添加用户信息
    function adduser(){
	if($this->isadmin!=1 && !$this->checkright("adduser") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $condition  =  "";
        $gameinfo   =  get_game($this->db);
        $traninfo   =  ad_get($this->db,2);
        $func       =  get_param("function");
        $act        =  get_param("act");

        $data['a_name']         =   get_param('logname');
        $data['a_pwd']          =   get_param('pwd');
        $data['a_truename']     =   get_param('truename');
        $data['a_tel']          =   get_param('tel');
        $data['a_email']        =   get_param('mail');
        $data['a_qq']           =   get_param('qqnum');
        $data['a_groupid']      =   get_param('groupid','int');
        $data['a_permission']   =   implode(',',get_array("terrace"));
        $data['a_permission2']   =  implode(',',get_array("tran"));
        $data['a_islock']       =   get_param('islock');
        $data['a_insertdate']   =   THIS_DATETIME;
        $data['a_isadmin']      =   get_param("isadmin","int");
        $data['a_islock']       =   ('on' == $data['a_islock'])?1:2;
        $data['a_isadmin']      =   (empty($data['a_isadmin']))?2:$data['a_isadmin'];


        //获取游戏列表
        if($func == 'getrights'){
            $groupid =  get_param("groupid","int");
            $rights  =  get_groupright($groupid,$this->db,true);    //当前选中组权限
            $str     =  "<ul style='list-style:none'>";
            $i       =  0;
            if(empty($rights[$groupid.'right'])){
                echo '【没有分配！】';
                exit;
            }
            foreach($rights[$groupid.'right'] as $v){
                 $str .= '<li style="float: left; width: 150px; height: 20px;">'.($i+1).".".$v.'</li>';
                 $i++;
            }
            $str .= "</ul>";
            echo $str;
            exit;
        }
        if($act == "addmnger"){
            $data['a_pwd']          =   md5($data['a_pwd']);
            //判断是否越权添加
            if(!get_param('groupid','int')){
                showinfo("所属分组不能为空","",3);
            }
            $g_info = get_info($this->db, 'admin_group', array('ag_fid','ag_rightid','ag_jmenu')," and sysid = ".get_param('groupid','int'));
            if($g_info['ag_fid'] != $this->myad_groupid && $this->isadmin!=1){
                showinfo("请按规定操作。","",2);
            }
            if($data['a_isadmin']==1 && $this->isadmin!=1){
                showinfo("你没有该权限!", "", 3);
            }
            $ischinese = check_is_cn($data['a_name']);
            if ($ischinese){
                showinfo("用户名只能是字母或者数字的组合!", "", 3);
            }
            if (empty($data['a_name']) or empty($data['a_pwd']) or empty($data['a_truename']) or empty($data['a_email'])) {
                showinfo("登录名，密码，真实姓名，电子邮件均不能为空!", "", 3);
            }
            $isexits   =  $this->db->getOne($this->db->query("select sysid from ".get_table("admin")." where a_name='".$data['a_name']."'"));
            if(isset($isexits) && !empty($isexits)){
                $this->admin_log("添加管理员失败,管理员".$data['a_name']."已存在");
                showinfo("用户名已存在,请重新填写!", "", 3);
            }
            //生成图片，则默认姓为图片
            $data['a_rightid']  =  $g_info['ag_rightid'];
            $data['a_jmenu']    =  $g_info['ag_jmenu'];
            $data['a_img'] = $this->path.time().".jpg";
            $msg           = substr($data['a_truename'],0,3);
            $res = $this->fileobj->isDir($this->path);
            if (!$res) {
                $this->fileobj->mkDir($this->path);
            }
            draw_img($data['a_img'],'50',$msg,$this->fonts);

            $data['a_userid']       =   $this->my_admin_id;
            $rst = add_record($this->db, 'admin', $data);
            if($rst['rows']>0){
                $this->admin_log("添加管理员".$data['a_name']."成功!");
                showinfo("添加成功!","index.php?module=user&method=listuser",4);
            }else{
                //如果存在上传的图片，就删除
                if(!empty($data['a_img']) && $this->fileobj->isExists($data['a_img'])){
                    $this->fileobj->rm($data['a_img']);
                }
                $this->admin_log("添加管理员".$data['a_name']."失败,原因:数据库插入失败!");
                showinfo("添加失败,请重试!","",3);
            }
        }
        $and     =  ($this->isadmin!=1)?" and ag_fid=".$this->myad_groupid:"";   //非超级管理员只能分配用户组的子权限组
        $group   =  $this->db->getAll($this->db->query("select sysid,ag_groupname from ".get_table("admin_group")." where 1 $and order by sysid")); //权限组列表
        if($this->isadmin!=1){
            $condition = " and sysid in (".$this->myad_permission.")";
        }
        $this->assign("group",$group);
        $this->assign("gameinfo",$gameinfo);
        $this->assign("traninfo",$traninfo);
        $this->assign("manager","adduser");
    	$this->display("user.html");
    }
     
    //修改用户信息,仅用户本身和超级管理员可改
    function edit(){
        $uid = get_param("uid","int");
        if($this->my_admin_id!=$uid && $this->isadmin!=1){
            showinfo("仅超级管理员或用户本身才能修改个人信息。","",3);
        }
        $gameinfo       =   get_game($this->db);
        $traninfo       =   ad_get($this->db,2);//渠道信息
        $uname          =   get_param("uname");
        $permission     =   $this->db->getOne($this->db->query("select a_isadmin,a_permission,a_permission2,a_img from ".get_table("admin")." where sysid=".$uid));
        $act            =   get_param("act");
        $mygames        =   explode(",",$permission["a_permission"]);
        $mytrans        =   explode(",",$permission["a_permission2"]);
        $where['sysid'] =   $uid;
        $terrace        =   implode(',',get_array("terrace"));
        $tran           =   implode(',',get_array("tran"));
        $truename       =   get_param('truename');
        $tel            =   get_param('tel','int');
        $mail           =   get_param('mail');
        $qqnum          =   get_param('qqnum','int');
        $pwd            =   get_param('pwd');
        $checkadmin     =   get_param("isadmin","int");
        $flag           =   get_param("flag");
        $data['a_img']  =   get_param("upload_url")?get_param("upload_url"):'';
        if(!empty($terrace)){
            $data['a_permission']   =  $terrace;
        }
        $data['a_permission2']   =  $tran;
        $data["a_isadmin"]  =  (empty($checkadmin))?2:1;
        
        if(!empty($pwd)){
            $data['a_pwd']  =   md5($pwd);
        }
        
        if (!empty($truename)){
        	$data['a_truename'] = $truename;
        }
        
        if (!empty($tel)){
        	$data['a_tel'] = $tel;
        }
        
        if (!empty($mail)){
        	$data['a_email'] = $mail;
        }
        
        if (!empty($qqnum)){
        	$data['a_qq'] = $qqnum;
        }
        if($act == 'goedit' && $flag != 'up'){
            //判断如果没有上传图片，则默认姓为图片
            $res = $this->fileobj->isDir($this->path);
            if (!$res) {
                $this->fileobj->mkDir($this->path);
            }
            if(empty($data['a_img'])){
                $data['a_img'] = 'lyuploads/user/'.date("Ymd")."/".time().".jpg";
                $msg           = substr($data['a_truename'],0,3);
                draw_img($data['a_img'],'50',$msg,$this->fonts);
            }
            $res  =  update_record($this->db, 'admin',$data,$where);
            if($res){
                //如果存在上传的图片，就删除
                if(!empty($data['a_img']) && $this->fileobj->isExists($data['a_img'])){
                    $this->fileobj->rm($permission['a_img']);
                }
                $_SESSION['myad_permission'] = $terrace;
                $this->admin_log("给ID为".$uid."的用户修改个人信息成功!");
                showinfo("修改成功!","index.php?module=user&method=listuser",4);
            }else{
                //如果存在上传的图片，就删除
                if(!empty($data['a_img']) && $this->fileobj->isExists($data['a_img'])){
                    $this->fileobj->rm($data['a_img']);
                }
                $this->admin_log("修改管理员".$data['a_name']."失败,原因:数据库插入失败!");
                showinfo("修改失败,请重试!","",3);
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
        }
        $img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;

        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("info", $info);
        $this->assign("error", $error);
        $this->assign("success", $success);
        $this->assign("data",$data);

        $this->assign("manager","edit");
        $this->assign("uid",$uid);
        $this->assign("mygames",$mygames);
        $this->assign("isadmin",$this->isadmin);
        $this->assign("checkadmin",$permission["a_isadmin"]);
        $this->assign("uname",$uname);
        $this->assign("gameinfo",$gameinfo);
        $this->assign("traninfo",$traninfo);
        $this->assign("mytrans",$mytrans);
        $this->display("user.html");
    }
    
    //用户详细信息
    function userinfo(){
        $uid        =  get_param("uid","int");
        $sql        =  "select * from ".get_table("admin")." where sysid=$uid";
        $baseinfo   =  $this->db->getOne($this->db->query($sql));
        $group      =  $this->db->getOne($this->db->query("select ag_groupname,ag_rightid from ".get_table("admin_group")." where sysid=".$baseinfo['a_groupid'])); 
        $rightinfo  =  get_groupright($baseinfo['a_groupid'],$this->db);
        $baseinfo['groupname']   =   $group['ag_groupname'];
        $baseinfo['myidentify']  =   ($baseinfo['a_isadmin']==1)?"超级管理员":"普通管理员";
        if($baseinfo['a_islock']==1){
            $lockhtml = "已锁 ";
            if($this->isadmin==1){
                $lockhtml.=" <a href='index.php?module=user&method=userlock&uid=$uid&act=unlock'>[解锁]</a>";
            }
        }else{
            $lockhtml = "正常";
            if($this->isadmin==1){
                $lockhtml.=" <a href='index.php?module=user&method=userlock&uid=$uid&act=lock'>[锁定]</a>";
            }
        }

        $right_arr=array(
            'lockhtml'=>$lockhtml,
            'true'=>$true,
            'baseinfo'=>$baseinfo,
            'rightinfo'=>$rightinfo[$baseinfo['a_groupid'].'right'],
            'isadmin'=>$this->isadmin,
        );
        echo json_encode($right_arr);
    }
    
    //锁定，解锁用户
    function userlock(){
        $uid    =   get_param("uid","int");
        if($this->isadmin!=1){//检查是否超出权限
            $myu = array();
            $sql = "select sysid from ".  get_table('admin')." where a_groupid in(".$this->get_child_group($this->myad_groupid).") and a_groupid != ".$this->myad_groupid;
            $query_myu = $this->db->query($sql);
            while($row = $this->db->FetchArray($query_myu)){
                array_push($myu, $row['sysid']);
            }
            if(!in_array($uid, $myu)){
                showinfo("请按规定操作!","",3);
            }
        }
        
        $act    =   get_param("act");
        $ups    =   ($act=="lock")?1:2;
        $msg    =   ($ups==1)?"锁定":"解锁";
        $value  =   array("a_islock"=>$ups);
        $where  =   array("sysid"=>$uid);
        $res    =   update_record($this->db, "admin",$value,$where);
        $this->admin_log("给ID为".$uid."的用户".$msg."成功!");
        showinfo($msg."成功!","index.php?module=user&method=listuser",4);
    }
    
    //删除用户
    function userdel(){
        
        $uid    =   get_param("uid","int");
        $img    =   get_param("img");
        if($this->isadmin!=1){//检查是否超出权限
                showinfo("请按规定操作!","",3);
        }
        
        $sql = 'delete from '.get_table('admin').' where sysid = '.$uid;
        $this->db->query($sql);
        $this->admin_log("删除ID为".$uid."的用户成功!");
        //如果存在上传的图片，就删除
        if(!empty($img) && $this->fileobj->isExists($img)){
            $this->fileobj->rm($img);
        }
        showinfo("删除ID为".$uid."的用户成功!","index.php?module=user&method=listuser",4);
    }

    //重新分配权限组
    function updatepri()
    {
//	if($this->isadmin!=1){
//            showinfo("仅超级管理员才能够执行该操作。","",2);
//        }
        $sysid       = get_param('uid','int'); //接受用户的uid
        $act         = get_param("act");       //处理操作
        
        if($this->isadmin!=1){//检查是否超出权限
            $myu = array();
            $sql = "select sysid from ".  get_table('admin')." where a_groupid in(".$this->get_child_group($this->myad_groupid).") and a_groupid != ".$this->myad_groupid;
            $query_myu = $this->db->query($sql);
            while($row = $this->db->FetchArray($query_myu)){
                array_push($myu, $row['sysid']);
            }
            if(!in_array($sysid, $myu)){
                showinfo("请按规定操作!","",3);
            }
        }
        $sql = "SELECT a.sysid as uid,a.a_name,a.a_truename,a.a_tel,a.a_isadmin as isadmin,a.a_email,a.a_groupid,ag.sysid,ag.ag_groupname,ag.ag_rightid FROM ".get_table("admin")." as a left join ".get_table("admin_group")." as ag on a.a_groupid = ag.sysid WHERE a.sysid=$sysid";
        $and         =  "";
        $userinfo    =  $this->db->getOne($this->db->query($sql));
        if($act == "update")
        {
            $upuid   = get_param('upuid','int');
            $groupid = get_param('groupid','int');
            $isadmin=get_param("isadmin","int");
            if(empty($groupid))
            {
                showinfo("未能修改权限!","",3);
            }
            
            //判断是否超出权限
            if(!in_array($groupid,explode(',',$this->get_child_group($this->myad_groupid)))){
                showinfo("请按规定操作!","",3);
            }
            
            $uinfo = get_info($this->db, "admin", array('a_groupid'), ' and sysid='.$upuid);
            if($uinfo['a_groupid'] == $groupid && $isadmin==$userinfo['isadmin']){
                showinfo("权限组未修改!","",3);
            }else{
                $ginfo = get_info($this->db, "admin_group", array('ag_rightid','ag_jmenu'), ' and sysid='.$groupid);
                $value   =   array("a_groupid"=>$groupid,"a_isadmin"=>$isadmin,"a_rightid"=>$ginfo['ag_rightid'],"a_jmenu"=>$ginfo['ag_jmenu']);
                $where   =   array("sysid"=>$upuid);
                update_record($this->db, "admin",$value,$where);
                $this->admin_log("给ID为".$upuid."的用户重新分配权限成功!");
                showinfo("重新分配权限成功!","index.php?module=user&method=listuser",4);
            }
        }
       
        $rightinfo   =  get_groupright($userinfo['sysid'],$this->db);

        $and     =  ($this->isadmin!=1)?" and ag_fid=".$this->myad_groupid:"";   //非超级管理员只能分配用户组的子权限组
        
        $group       =  $this->db->getAll($this->db->query("select sysid,ag_groupname from ".get_table("admin_group")." where 1 $and order by sysid")); //权限组列表

        $this->assign("uid",$sysid);
        $this->assign("accessright",$rightinfo[$userinfo['sysid']."right"]);
        $this->assign("userinfo",$userinfo);
        $this->assign("group",$group);
        $this->assign("isadmin",$this->isadmin);
        $this->assign("manager","privileges");
        $this->display("user.html");
    }
}
