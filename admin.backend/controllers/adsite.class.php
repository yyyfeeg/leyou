<?php
/**
 * Copyright (C) 广东星辉天拓互动娱乐有限公司-游戏发行中心技术部
 * @project : 微信官网平台
 * @explain : 广告管理类
 * @filename : adsite.class.php
 * @author : jericho
 * @codetime : 
 * @modifier :
 * @modifytime:
 */
class Adsite extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $this->upimg = new upload_image();
    }
    /**
     * @explain 广告列表
     *
     */
    public function adsitelist()
    {
        if($this->isadmin!=1 && !$this->checkright("adsitelist") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $sql        =   "select * from ".get_table("game_partad")." where gp_aid=0 order by gp_inserttime desc";
        $query = $this->db->Query($sql);
        $garr = get_game($this->db);
        $user = get_users();
        $transinfo = ad_get($this->db);
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['gp_inserttime']  = date('Y-m-d H:i:s',$rows['gp_inserttime']);
            $rows['gp_updatetime']  = date('Y-m-d H:i:s',$rows['gp_updatetime']);
            $rows['gp_insertuid2']   = $user[$rows['gp_insertuid']];
            $rows['gp_transport2']   = $rows['gp_transport'] == 1 ? '安卓' : ($rows["gp_transport"] == 2 ? "ios" : "ios越狱");
            $rows['action']         = "<a href='index.php?module=adsite&method=edit&sysid=".$rows['sysid']."'>[修改] </a>&nbsp;&nbsp;&nbsp;";
            if($this->isadmin== 1 || $transinfo[$rows['sysid']]){
                $rows['action']     .= "<a href='index.php?module=adsite&method=del&sysid=".$rows['sysid']."'>[删除] </a>&nbsp;&nbsp;&nbsp;";
            }
            if($this->isadmin == 1 || $this->checkright("adchecks")){
                if($rows['gp_statue'] == 1){
                    $rows['action'] .= "<a href='index.php?module=adsite&method=adchecks&sysid=".$rows['sysid']."&act=1'>[审核] </a>&nbsp;&nbsp;&nbsp;";
                }
                if($rows['gp_statue'] == 2){
                    $rows['action'] .= "<a href='index.php?module=adsite&method=adchecks&sysid=".$rows['sysid']."&act=2'>[关闭] </a>&nbsp;&nbsp;&nbsp;";
                }
                if($rows['gp_statue'] == 3){
                    $rows['action'] .= "<a href='index.php?module=adsite&method=adchecks&sysid=".$rows['sysid']."&act=3'>[开启] </a>&nbsp;&nbsp;&nbsp;";
                }
            }else{
                $rows['action'] = "-";
            }
            $rows['gp_statue2']  = $rows['gp_statue'] == 1 ? '审核' : ($rows['gp_statue'] == 2 ? '开启': '关闭');
            $right_arr[]=array(
                $rows['sysid'],
                $rows['gp_name'],
                $rows['gp_descrip'],
                $rows['gp_transport2']."[".$rows['gp_transport']."]",
                $rows['gp_insertuid2']."[".$rows['gp_insertuid']."]",
                $rows['gp_inserttime'],
                $rows['gp_statue2']."[".$rows['gp_statue']."]",
                $rows['action'],
            );
        }
        $this->assign("adsite","adsitelist");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign('meg','广告列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display('adsite.html');
    }
    /**
     * @explain 广告审核
     *
    */
    public function adchecks()
    {
      if ($this->isadmin != 1 && !$this->checkright("adchecks"))
        {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        $sysid  =   get_param("sysid");
        $act    =   get_param("act")==1 ? 2 : (get_param("act")==2 ? 3 : 2);
        $flage  =   get_param("flage");
        //更新状态
        
        $where  =   array("sysid"=>$sysid);
        if(empty($flage)){
            $value  =   array("gp_statue"=>$act);
            $res    =   update_record($this->db, "game_partad",$value,$where); 
        }else{
            $value  =   array("gp_statue"=>$act);
            $res    =   update_record($this->db, "game_partad",$value,$where); 
        }
        
        $this->admin_log("给ID为".$uid."的广告更新成功!");
        showinfo($msg."成功!","",3);
    }

    /**
     * @explain 广告添加
     *
     */
    public function adsiteadd()
    {
        if ($this->isadmin != 1 && !$this->checkright("adsiteadd"))
        {
            showinfo("你没有权限执行该操作!", "", 2);
        }
        $act                        = get_param("act");
        $data['gp_name']            = get_param("name");
        $data['gp_descrip']         = get_param("descrip");
        $data['gp_transport']       = get_param("transport");
        $data['gp_statue']          = 1;
        $data['gp_insertuid']       = $this->my_admin_id;
        $data['gp_inserttime']      = THIS_DATETIME;


        if ($act == 'partadadd')
        {
            //判断是否为空
            if (empty($data['gp_name'])) {
                showinfo("广告名不能为空！", "index.php?module=adsite&method=adsiteadd", 2);
            }

            $sql            = "select 1 from ".get_table("game_partad")." where gp_name='".$data["gp_name"]."' and gp_transport='".$data["gp_transport"]."'";
            $is_exist       = $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
                $this->admin_log("添加新广告" . $data['gp_name'] . "失败，原因：新广告已存在");
                showinfo("广告已存在!", "", 3);
            }else{
                $res = add_record($this->db, "game_partad", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log("添加广告" . $data['gp_name'] . "成功");
                    showinfo("添加成功!", "index.php?module=adsite&method=adsitelist", 4);
                } else {
                    $this->admin_log("添加广告" . $data['gp_name'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=adsite&method=adsiteadd", 2);
                }
            }
        }
        $this->assign("adsite", "adsiteadd");
        $this->assign("data", $data);
        $this->display("adsite.html");
    }

    /**
     * @explain 广告修改
     *
     */
    public function edit()
    {
        if ($this->isadmin != 1 && !$this->checkright("adsiteedit"))
        {
            showinfo("你没有权限执行该操作!", "", 2);
        }
        $act                        = get_param("act");
        $data['sysid']              = get_param("sysid");
        $data['gp_name']            = get_param("name");
        $data['gp_descrip']         = get_param("descrip");
        $data['gp_transport']       = get_param("transport");
        $data['gp_statue']          = 1;
        $data['gp_updateuid']       = $this->my_admin_id;
        $data['gp_updatetime']      = THIS_DATETIME;

        if ($act == 'edit')
        {
            //判断是否为空
            if (empty($data['gp_name'])) {
                showinfo("广告名不能为空！", "index.php?module=adsite&method=edit", 3);
            }
            $sql            = "select 1 from ".get_table("game_partad")." where gp_name='".$data["gp_name"]."' and gp_transport='".$data["gp_transport"]."' and sysid!={$data['sysid']}";

            $is_exist       = $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
                $this->admin_log("修改新广告" . $data['gp_name'] . "失败，原因：新广告已存在");
                showinfo("广告已存在!", "", 3);
            }else{
                $res = update_record($this->db, "game_partad", $data,array('sysid'=>$data['sysid']),'',1);
                if ($res) {
                    $this->admin_log("修改广告" . $data['gp_name'] . "成功");
                    showinfo("修改成功!", "index.php?module=adsite&method=adsitelist", 4);
                } else {
                    $this->admin_log("修改广告" . $data['gp_name'] . "失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=adsite&method=adsitelist", 4);
                }
            }
        }
        $data = $this->db->getOne($this->db->query("select * from " . get_table("game_partad") . " where sysid=".$data['sysid']));
        $this->assign("edit", "edit");
        $this->assign("data", $data);
        $this->display("adsite.html");
    }

    /**
     * @explain 删除广告
     *
    */
    public function del()
    {
        if ($this->isadmin != 1 && !$this->checkright("adsitedel")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }
        $where['sysid'] = get_param("sysid", "int");
        if(get_param("flage")){
            $ret            = delete_record($this->db, "game_partad", $where);
        }else{
            $ret            = delete_record($this->db, "game_partad", $where);
        }
        if ($ret) {
            $this->admin_log("删除广告 " . $where['sysid'] . " 成功");
            showinfo("删除成功!", "", 3);
        } else {
            $this->admin_log("删除广告" . $where['sysid'] . " 失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
        }
    }

    /**
     * @explain 广告子站列表
     *
     */
    public function partadlist()
    {
        if($this->isadmin!=1 && !$this->checkright("partadlist") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $sql    =   "select * from ".get_table("game_partad")." where gp_aid>0 order by gp_inserttime desc";
        $query  = $this->db->Query($sql);
        $garr   = get_game($this->db);
        $aid    = ad_get($this->db);
        $adsoninfo    = ad_get($this->db,2);
        $user   = get_users();
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['gp_inserttime']  = date('Y-m-d H:i:s',$rows['gp_inserttime']);
            $rows['gp_insertuid2']   = $user[$rows['gp_insertuid']];
            $rows['gp_aid']         = $aid[$rows['gp_aid']];
            $rows['action']         = "<a href='index.php?module=adsite&method=editson&sysid=".$rows['sysid']."'>[修改] </a>&nbsp;&nbsp;&nbsp;";
            if($this->isadmin==1 || $adsoninfo[$rows['sysid']]){
                $rows['action']     .= "<a href='index.php?module=adsite&method=del&sysid=".$rows['sysid']."&flage=1'>[删除] </a>&nbsp;&nbsp;&nbsp;";
            }
            if($this->isadmin == 1 || $this->checkright("adchecks")){
                if($rows['gp_statue'] == 1){
                    $rows['action'] .= "<a href='index.php?module=adsite&method=adchecks&sysid=".$rows['sysid']."&act=1&flage=1'>[审核] </a>&nbsp;&nbsp;&nbsp;";
                }
                if($rows['gp_statue'] == 2){
                    $rows['action'] .= "<a href='index.php?module=adsite&method=adchecks&sysid=".$rows['sysid']."&act=2&flage=1'>[关闭] </a>&nbsp;&nbsp;&nbsp;";
                }
                if($rows['gp_statue'] == 3){
                    $rows['action'] .= "<a href='index.php?module=adsite&method=adchecks&sysid=".$rows['sysid']."&act=3&flage=1'>[开启] </a>&nbsp;&nbsp;&nbsp;";
                }
            }else{
                $rows['action'] = "-";
            }
            $rows['gp_statue2']      = $rows['gp_statue'] == 1 ? '审核' : ($rows['gp_statue'] == 2 ? '开启': '关闭');
            $right_arr[]=array(
                $rows['sysid'],
                $rows['gp_name'],
                $rows['gp_descrip'],
                $rows['gp_aid'],
                $rows['gp_insertuid2']."[".$rows['gp_insertuid']."]",
                $rows['gp_inserttime'],
                $rows['gp_statue2']."[".$rows['gp_statue']."]",
                $rows['action']
            );
        }
        $this->assign("adsite","partadlist");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign('meg','子广告列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display('adsite.html');
    }

    /**
     * @explain 子广告添加
     *
     */
    public function partadadd()
    {
        if ($this->isadmin != 1 && !$this->checkright("partadadd"))
        {
            showinfo("你没有权限执行该操作!", "", 2);
        }
        $act                        = get_param("act");
        $data['gp_name']            = get_param("name");
        $data['gp_descrip']         = get_param("descrip");
        $data['gp_aid']             = get_param("aid");
        $data['gp_statue']          = 1;
        $data['gp_insertuid']       = $this->my_admin_id;
        $data['gp_inserttime']      = THIS_DATETIME;

        if ($act == 'partadadd')
        {
            //判断是否为空
            if (empty($data['gp_name'])) {
                showinfo("广告名不能为空！", "index.php?module=adsite&method=partadadd", 4);
            }
            //查询aid
            $sql            = "select gp_transport from ".get_table("game_partad")." where sysid = ".$data['gp_aid'];
            $aid_data       = $this->db->getOne($this->db->query($sql));
            $data['gp_transport'] = $aid_data["gp_transport"];

            $sql            = "select 1 from ".get_table("game_partad")." where gp_name='".$data["gp_name"]."' and gp_transport='".$data["gp_transport"]."'";
            $is_exist       = $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
                $this->admin_log("添加新子广告" . $data['gp_name'] . "失败，原因：新子广告已存在");
                showinfo("子广告已存在!", "", 3);
            }else{
                $res = add_record($this->db, "game_partad", $data);
                if ($res['rows'] > 0) {
                    $this->admin_log("添加新子广告" . $data['gp_name'] . "成功");
                    showinfo("添加成功!", "index.php?module=adsite&method=partadlist", 4);
                } else {
                    $this->admin_log("添加广告" . $data['gp_name'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=adsite&method=partadadd", 4);
                }
            }
        }
        $this->assign("gp_aids", ad_get($this->db,a));
        $this->assign("adsite", "partadadd");
        $this->assign("data", $data);
        $this->display("adsite.html");
    }

    /**
     * @explain 广告修改
     *
    */
    public function editson()
    {
        if ($this->isadmin != 1 && !$this->checkright("partadedit"))
        {
            showinfo("你没有权限执行该操作!", "", 2);
        }
        $act                        = get_param("act");
        $data['sysid']              = get_param("sysid");
        $data['gp_name']            = get_param("name");
        $data['gp_descrip']         = get_param("descrip");
        $data['gp_aid']             = get_param("aid");
        $data['gp_statue']          = 1;
        $data['gp_updateuid']       = $this->my_admin_id;
        $data['gp_updatetime']      = THIS_DATETIME;

        if ($act == 'editson')
        {
            //判断是否为空
            if (empty($data['gp_name'])) {
                showinfo("广告名不能为空！", "index.php?module=adsite&method=editson", 3);
            }
            //查询aid
            $sql            = "select gp_transport from ".get_table("game_partad")." where sysid = ".$data['gp_aid'];
            $aid_data       = $this->db->getOne($this->db->query($sql));
            $data['gp_transport'] = $aid_data["gp_transport"];
            
            $sql            = "select 1 from ".get_table("game_partad")." where gp_name='".$data["gp_name"]."' and gp_aid='".$data["gp_aid"]."' and sysid!={$data['sysid']}";

            $is_exist       = $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
                $this->admin_log("修改子广告" . $data['gp_name'] . "失败，原因：子广告已存在");
                showinfo("子广告已存在!", "", 3);
            }else{
                $res = update_record($this->db, "game_partad", $data,array('sysid'=>$data['sysid']),'',1);
                if ($res) {
                    $this->admin_log("修改子广告" . $data['gp_name'] . "成功");
                    showinfo("修改成功!", "index.php?module=adsite&method=partadlist", 4);
                } else {
                    $this->admin_log("修改子广告" . $data['gp_name'] . "失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=adsite&method=partadlist", 3);
                }
            }
        }
        
        $data = $this->db->getOne($this->db->query("select * from " . get_table("game_partad") . " where sysid=".$data['sysid']));
        $this->assign("edit", "editson");
        $this->assign("gp_aids", ad_get($this->db));
        $this->assign("data", $data);
        $this->display("adsite.html");
    }

    /**
     * @explain 广告分包配置添加
     *
     */
    public function adinfoadd()
    {
        if ($this->isadmin != 1 && !$this->checkright("adinfoadd"))
        {
            showinfo("你没有权限执行该操作!", "", 2);
        }
        $act                        = get_param("act");
        $data['gp_name']            = get_param("name");
        $data['gp_descrip']         = get_param("name");
        $data['gp_aid']             = get_param("aid");
        $descrip                    = $data['gp_descrip'];
        $start                      = get_param("start",'int');
        $end                        = get_param("end",'int');
        $screen                     = get_param("screen");
        $data['gp_insertuid']       = $this->my_admin_id;
        $data['gp_inserttime']      = THIS_DATETIME;
        $data['gp_statue']          = 2;
        $gameurl                    = get_param("gameurl");
        $gamemb                     = get_param("gamemb");
        $gid                        = get_param("gid");
        $aarr                       = ad_get($this->db,"","","","",1);

        //查询当前渠道最大id
        $this->db->query("lock tables ".get_table("game_partad")." write");
        $adinfo = $this->db->getOne($this->db->query("select max(sysid) id from ".get_table("game_partad")));
        $this->db->query("unlock tables");

        if ($act == 'adinfoadd')
        {
            //判断是否为空
            if (empty($data['gp_name'])) {
                showinfo("广告名不能为空！", "index.php?module=adsite&method=adinfoadd", 4);
            }

            //判断ID范围是否越界
            if($start<$adinfo["id"]){
                showinfo("投放ID初始值必须大于".$adinfo["id"], "index.php?module=adsite&method=adinfoadd", 4);
            }

            if($screen==1 && empty($gamemb)){
                showinfo("请选择游戏母包", "index.php?module=adsite&method=adinfoadd", 4);
            }


            //查询aid
            $sql                    =  "select gp_transport from ".get_table("game_partad")." where sysid = ".$data['gp_aid'];
            $aid_data               =  $this->db->getOne($this->db->query($sql));
            $data['gp_transport']   =  $aid_data["gp_transport"];
            $sql                    =  "select 1 from ".get_table("game_partad")." where gp_name='".$data["gp_name"]."' and gp_transport='".$data["gp_transport"]."'";
            $is_exist               =  $this->db->getOne($this->db->query($sql));
            if ($is_exist) {
                $this->admin_log("添加新子广告" . $data['gp_name'] . "失败，原因：新子广告已存在");
                showinfo("子广告已存在!", "", 3);
            }else{
                //获取密码信息
                $mima = $this->db->getOne($this->db->query("select gi_key,gi_paykey from ".get_table("game_info")." where sysid =".$gid));

                //获取游戏包上传地址
                $gdown  = $this->db->getOne($this->db->query("select gp_upload_path as path from ".get_table("game_parent")." where sysid=".$gamemb));
                $newarr = array();
                for($i=$start;$i<$end;$i++){
                    $data["gp_name"] = $descrip."-".($i+1);
                    $data["sysid"]   = ($i+1);
                    //添加至广告渠道配置表
                    $res = add_record($this->db, "game_partad", $data);
                    if ($res['rows'] > 0) {
                        $this->admin_log("添加新子广告" . $data['gp_name'] . "成功");
                    } else {
                        $this->admin_log("添加广告" . $data['gp_name'] . "失败，原因：数据库插入失败");
                    }

                    //同步添加到广告分包配置表
                    $arr = array(
                        "gam_gid"          => $gid,
                        "gam_uaid"         => $data['gp_aid'],
                        "gam_uwid"         => $res["id"],
                        "gam_type"         => $screen,
                        "gam_adduid"       => $data['gp_insertuid'],
                        "gam_addtime"      => THIS_DATETIME,
                        "gam_addname"      => $_SESSION["myad_realname"],
                        "gam_down_address" => $gameurl,
                    );
                    $ret = add_record($this->db, "adinfo_msg", $arr);
                    $ks  = $arr["gam_gid"]."_".$arr["gam_uaid"]."_".$res["id"];
                    $newarr[$ks] = $ret["id"];
                     
                    //如果为后台分包，则调用接口执行计划任务
                    if($screen==1 && !empty($gamemb)){
                        // $strs .= $arr['gam_uaid']."|".$arr["gam_gid"]."_".$arr["gam_uaid"]."_".$res["id"]."_".$mima["gi_key"].chr(10);
                        $strs .= $arr["gam_gid"]."_".$arr['gam_uaid']."_".$arr["gam_uwid"]."_".$mima["gi_key"]."_".$mima["gi_paykey"]."_D23DD2AE47CE18CF2F8BD8C3C9DEBB89_11111_wxb42b2f0f3dde6ed1".chr(10);
                    }
                }

                if($screen==1 && !empty($strs)){
                    $webpaths = str_replace("admin.backend/","",WEBPATH_DIR);
                    $urlpaths = str_replace("admin.backend/","",WEBPATH_DIR_INC);
                    $this->fileobj->write($webpaths."channels/channels.txt",$strs,"wb");

                    $returns = exec("python ".$webpaths."channels/channel.py ".WEBPATH_DIR.$gdown['path']);
                    if(!empty($returns)){
                        $newret = str_replace("'","",str_replace("[", "", str_replace("]", "", $returns)));
                        $newret = explode(",",$newret);
                        //循环更新
                        foreach($newret as $val){
                            $v   = explode("^",trim($val));
                            $url = $urlpaths."channels/".str_replace("/home/wwwroot/default/www.hlwy.com/channels/","",$v[1]);
                            $updata["gam_down_address"] = $url;
                            $updata["gam_state"]        = 2;
                            $upwhere["sysid"]           = $newarr[$v[0]];
                            $sql = "update ".get_table("adinfo_msg")." set gam_down_address='".$url."',gam_state=2 where sysid=".$newarr[$v[0]];
                            $this->db->query($sql);
                        }
                        unset($updata);
                        unset($upwhere);
                    }
                    $this->fileobj->write($webpaths."channels/channels.txt","");
                }
                showinfo("添加完成!", "index.php?module=adsite&method=adinfolist", 4);
            }
        }
        $this->assign("gp_aids", ad_get($this->db,a));
        $this->assign("maxid",$adinfo["id"]);
        $this->assign("gamestr",$this->get_select($data['gs_gid']));
        $this->assign("adsite", "adinfoadd");
        $this->assign("data", $data);
        $this->display("adsite.html");
    }


    /**
     * @explain 广告分包配置列表
     *
     */
    public function adinfolist(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("adinfolist")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);

        $where      = " where 1";
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $mtype      = get_param("mtype");

        //条件判定
        $gid?$where         .= " and gam_gid=".get_param("gid"):'';
        $aid?$where         .= " and gam_uaid=".get_param("aid"):'';
        $adsons?$where      .= " and gam_uwid=".get_param("adsons"):'';
        $mtype?$where       .= " and gam_type=".get_param("mtype"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and gam_uaid in (".$hehe["sysid"].")";
        }

        $this->assign("gamestr",$this->get_select($gid));

        //广告渠道选项
        if(!empty($tid)){
            $adstr = $wdstr = "";
            $tt    = ad_get($this->db,'a',"",$tid);
            if(!empty($tt)){
                foreach($tt as $k=>$v){
                    $check  = ($k==$aid)?"selected=selected":"";
                    $adstr .= "<option value='$k' ".$check.">$v</option>";
                }
            }
            unset($tt);

            $tt    = ad_get($this->db,"","",$tid,$aid);
            if(!empty($tt)){
                foreach($tt as $k=>$v){
                    $check  = ($k==$adsons)?"selected=selected":"";
                    $wdstr .= "<option value='$k' ".$check.">$v</option>";
                }
            }
            $this->assign('tps',1);
            $this->assign("adstr",$adstr);
            $this->assign("wdstr",$wdstr);
        }

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT count(*) c FROM " .get_table('adinfo_msg'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from " .get_table('adinfo_msg'). " $where order by sysid desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);
        $str    =  "";
        $gam_state = array('1'=>'未上传','2'=>'已上传');
        $gam_type = array('1'=>'后台分包','2'=>'研发分包');
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["gam_gid"]     = $garr[$rows["gam_gid"]]."[".$rows["gam_gid"]."]";
            $rows["gam_uaid"]    = $aarr[$rows["gam_uaid"]];
            $rows["gam_uwid"]    = $aarr[$rows["gam_uwid"]];
            if ($rows["gam_state"] == 1 && $rows["gam_type"] == 1) {
                $rows["gam_state"] = "未上传";
            }
            else{
                $rows["gam_state"]=$gam_state[$rows["gam_state"]];
            }
            $downurl = $rows["gam_down_address"];
            if($this->isadmin || $this->checkright("adinfolist")){
                $delstr =  " onclick='return confirm(".'"确认要删除?"'.")' ";
                $rows['del'] = "&nbsp;&nbsp;&nbsp;<a href='index.php?module=adsite&method=adinfodel&sysid=".$rows['sysid']."'".$delstr.">[删除]</a>";
            }
            $rows["gam_type"] = $gam_type[$rows["gam_type"]];
            $arrs[] = $rows;
        }
        $url    .=  "?module=adsite&method=adinfolist&gid=$gid&aid=$aid&adsons=$adsons&mtype=$mtype";
        $multi = multi($totalrecord["c"], $pagesize, $page, $url,2);
        
        $pageinfo = array(
            'page' => $page,
            'totalrecord' => $totalrecord["c"],
            'pagesize' => $pagesize,
            'totalpage' => ceil($totalrecord["c"]/$pagesize),
            'multi' => $multi
        );
        $this->assign('pageinfo', $pageinfo);//分页信息
        $this->assign("arrs",$arrs);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign("mtype",$mtype);
        $this->assign("adsite","adinfolist");
        $this->assign('meg','您已进入广告分包配置！<br>--在对应的列输入搜索信息');
        $this->display("adsite.html");
    }

    /**
     * @explain 游戏母包信息列表
     *
     */
    public function parentlist(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("parentlist")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);

        $where      = " where 1";
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $adsons     = get_param("adsons");
        $tid        = get_param("tid");
        $mtype      = get_param("mtype");

        //条件判定
        $gid?$where         .= " and gp_gid=".get_param("gid"):'';
        $mtype?$where       .= " and gp_upload_state=".get_param("mtype"):'';
        $adsons?$where      .= " and gp_uwid=".get_param("adsons"):'';

        //处理只选大渠道情况下：
        if(!empty($tid) && empty($aid) && empty($adsons)){
            $hehe = get_adinfo($this->db,$tid);
            $where .= " and gp_uaid in (".$hehe["sysid"].")";
        }

        $this->assign("gamestr",$this->get_select($gid));

        //广告渠道选项
        if(!empty($tid)){
            $adstr = $wdstr = "";
            $tt    = ad_get($this->db,'a',"",$tid);
            if(!empty($tt)){
                foreach($tt as $k=>$v){
                    $check  = ($k==$aid)?"selected=selected":"";
                    $adstr .= "<option value='$k' ".$check.">$v</option>";
                }
            }
            unset($tt);

            $tt    = ad_get($this->db,"","",$tid,$aid);
            if(!empty($tt)){
                foreach($tt as $k=>$v){
                    $check  = ($k==$adsons)?"selected=selected":"";
                    $wdstr .= "<option value='$k' ".$check.">$v</option>";
                }
            }
            $this->assign('tps',1);
            $this->assign("adstr",$adstr);  
            $this->assign("wdstr",$wdstr);
        }

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;

        $url = $_SERVER['PHP_SELF'];
        $sql    = "SELECT count(*) c FROM " .get_table('game_parent'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from " .get_table('game_parent'). " $where order by sysid desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);
        $str    =  "";
        $gam_state = array('1'=>'成功','2'=>'失败');
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["gp_addtime"] = date('Y-m-d',$rows["gp_addtime"]);
            $rows["gp_gid"]     = $garr[$rows["gp_gid"]]."[".$rows["gp_gid"]."]";
            $rows["gp_upload_state"]=$gam_state[$rows["gp_upload_state"]];
            $rows['action'] = '-';//"<a href='index.php?module=adsite&method=parentlist&sysid=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin || $this->checkright("parentlist")){
                $rows["action"] = '&nbsp;&nbsp;&nbsp;<a href='."'index.php?module=adsite&method=parentdel&sysid=".$rows['sysid']."&path=".$rows['gp_upload_path']."' onclick='return confirm(".'"确认要删除?"'.")'>[删除]</a>";
            }
            $str .= "<td>".$rows["sysid"]."</td>"."<td>".$rows["gp_addtime"]."</td>"."<td>".$rows["gp_gid"]."</td>"."<td>".$rows["gp_old_filename"]."</td>"
            ."<td>".$rows["gp_desc"]."</td>"."<td>".$rows["gp_upload_state"]."</td>"."<td>".$rows["gp_upload_path"]."</td>"."<td>".$rows["gp_file_size"]."</td><td>".$rows['action']."</td>";
            $str .= "</tr>";
        }
        $url    .=  "?module=adsite&method=parentlist&gid=$gid&aid=$aid&adsons=$adsons&mtype=$mtype";
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
        $this->assign("gid",$gid);
        $this->assign("mtype",$mtype);
        $this->assign("adsite","parentlist");
        $this->assign('meg','您已进入游戏母包列表！<br>--在对应的列输入搜索信息');
        $this->display("adsite.html");
    }

    function adinfodel(){
        if($this->isadmin!=1 || $this->checkright("adinfolist")==false){
            showinfo("你没有权限执行该操作。","",2);
        }
        $where['sysid']    =   get_param("sysid","int");
        //查询是否有分包
        $sql        =  "select gam_down_address from ".get_table("adinfo_msg")." where sysid=".$where["sysid"];
        $isexist    =  $this->db->getOne($this->db->query($sql));
        $ret        =  delete_record($this->db, "adinfo_msg",$where);
        if($ret){
             //删除对应文件
             $path = str_replace("http://www.hlwy.com/","/home/wwwroot/default/www.hlwy.com/",$isexist["gam_down_address"]);
             if(!empty($path) && $this->fileobj->isExists($path)){
                $this->fileobj->rm($path);
             }
             $this->admin_log("删除分包配置 ".$where['sysid']."成功");
             showinfo("删除成功!","index.php?module=adsite&method=adinfolist",4);
        }else{
             $this->admin_log("删除分包配置 ".$where['sysid']."失败，原因:数据库删除失败");
             showinfo("删除失败,请重试!","",3);
        }
    }

    function parentdel(){
        if($this->isadmin!=1 || $this->checkright("parentlist")==false){
            showinfo("你没有权限执行该操作。","",2);
        }
        $where['sysid']    =   get_param("sysid","int");
        $path   =   get_param("path");
        $ret = delete_record($this->db, "game_parent",$where);
        if($ret){
            if(!empty($path) && $this->fileobj->isExists($path)){
                $this->fileobj->rm($path);
            }
            $this->admin_log("删除母包配置 ".$where['sysid']."成功");
            showinfo("删除成功!","index.php?module=adsite&method=parentlist",4);
        }else{
            $this->admin_log("删除母包配置 ".$where['sysid']."失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!","",3);
        }
    }

    /**
     * @explain 广告分包修改
     *
     */
    function adinfoedit(){
        if($this->isadmin!=1 || $this->checkright("adinfolist")==false){
            showinfo("你没有权限执行该操作。","",2);
        }
        $data['gam_down_address'] = get_param("downurl");
        $where['sysid']           = get_param("sysid","int");
        $ret  = update_record($this->db, "adinfo_msg",$data,$where); 
        if($ret>-1){
             $this->admin_log("修改分包配置 ".$where['sysid']."成功");
             exit("修改成功");
        }else{
             $this->admin_log("修改分包配置 ".$where['sysid']."失败，原因:数据库修改失败");
             exit("修改失败");
        }
    }

    /*获取子渠道分类
    /*$t 渠道
    /*$t 主站
    */
    public function getadson(){
        $aid        =  get_param("aid","int");
        $tid        =  get_param("tid","int");
        if($aid){
            $res = ad_get($this->db,'','','',$aid);
            echo json_encode($res);
        }else{
            $res = ad_get($this->db,'a','',$tid);
            echo json_encode($res);
        }
    }

    /**
     * 添加游戏母包
     * @return [type] [description]
     */
    public function parentadd()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('parentadd')) {
            showinfo("你没有权限执行该操作。","",2);
        }
        $act = get_param('act');
        $flag = get_param('flag');
        $type = get_param('type');
        $gid  = get_param('gid');
        $data['gp_gid']          = get_param('gid');
        $data['gp_old_filename'] = get_param('name');
        $data['gp_file_size']    = get_param('size');
        $data['gp_desc']         = get_param('desc');
        $data['gp_adduid'] = $this->my_admin_id;
        $data['gp_addtime'] = THIS_DATETIME;

        // 上传文件
        if ($flag == 'up') {
            $size       = get_param('size');
            $fsize      = get_param('fsize');
            $findex     = get_param('indexCount');
            $ftotal     = get_param('totalCount');
            $ftype      = get_param('type');
            $fdata      = $_FILES['file'];
            $fname      = mb_convert_encoding(get_param('name'),"gbk","utf-8");
            $type       = @end(explode(".",$fname));
            $truename   = mb_convert_encoding(get_param('trueName'),"gbk","utf-8");

            $path = 'lyuploads/'.date("Y")."/".date("m");
            $dir = WEBPATH_DIR.$path;
            $fname = md5($fname.$gid.time());
            $save = $dir."/".$fname.".".$type;
            session_start();
            $_SESSION['new_name'] = $fname.".".$type;
            $_SESSION['path'] = $path."/".$fname.".".$type;
            if(!is_dir($dir))
            {
                $this->fileobj->mkDir($dir,0777);
            }
            //读取临时文件内容
            $temp = fopen($fdata["tmp_name"],"r+");//打开
            $filedata = fread($temp,filesize($fdata["tmp_name"]));//读取文件

            //将分段内容存放到新建的临时文件里面
            if(file_exists($dir.$findex.".tmp")) unlink($dir.$findex.".tmp");//是否存在当前的临时片名
            $tempFile = fopen($dir.$findex.".tmp","w+");//打开

            fwrite($tempFile,$filedata);//写入
            fclose($tempFile);//关闭
            fclose($temp);
            if($findex+1==$ftotal)
            {
                if(file_exists($save)) @unlink($save);
                //循环读取临时文件并将其合并置入新文件里面
                for($i=0;$i<$ftotal;$i++)
                {
                    $readData = fopen($dir.$i.".tmp","r+");
                    $writeData = fread($readData,filesize($dir.$i.".tmp"));//读取文件

                    $newFile = fopen($save,"a+");
                    fwrite($newFile,$writeData);
                    fclose($newFile);
                    fclose($readData);
                    $resu = @unlink($dir.$i.".tmp");
                }
                $fnewszie = filesize($dir.$fname.".".$type);
                if($size==$fnewszie)
                {
                    $_SESSION['state'] = 1;
                    $test = array("msg"=>"success");
                }else{
                    $_SESSION['state'] = 2;
                    $test = array("msg"=>"fail");
                }
                echo json_encode($test);
                die;
            }
        }
        if ($act == 'add' && $flag != 'up') {
            // 检查参数
            $data['gp_new_filename'] = $_SESSION['new_name'];
            $data['gp_upload_path']  = $_SESSION['path'];
            $data['gp_upload_state'] = $_SESSION['state']?$_SESSION['state']:get_param('state');
            if (empty($data['gp_gid'])) {
                showinfo("游戏名称不能为空!", "index.php?module=adsite&method=parentadd",3);
            }
            if (empty($data['gp_old_filename'])) {
                showinfo("文件名不能为空!", "index.php?module=adsite&method=parentadd",3);
            }
            if (empty($data['gp_upload_path'])) {
                showinfo("上传地址不能为空!", "index.php?module=adsite&method=parentadd",3);
            }

            // 添加记录
            $result = add_record($this->db,'game_parent',$data);
            if ($result['rows'] > 0) {

                $this->admin_log("上传新文件 ". $data['gp_new_filename'] ." 成功");
                showinfo("添加成功!", "index.php?module=adsite&method=parentlist", 4);
            } else {

                // 删除当前上传的图片
                 if(!empty($data['gp_upload_path']) && $this->fileobj->isExists($data['gp_upload_path'])){
                    $this->fileobj->rm($data['gp_upload_path']);
                }
                $this->admin_log("上传新文件 ". $data['gp_new_filename'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=adsite&method=parentadd", 3);
            }
        }
        $this->assign("gid",$gid);
        $this->assign("data", $data);
        $this->assign("adsite", "parentadd");
        $this->assign("gamestr",$this->get_select($data['gp_gid']));
        $this->display('adsite.html');
    }

    /**
     * @explain 查询游戏母包信息
     *
     */
    function selectgame(){
        if($this->isadmin!=1 || $this->checkright("adinfoadd")==false){
            showinfo("你没有权限执行该操作。","",2);
        }
        $str  = "";
        $gid  = get_param("gid");
        
        //查询游戏ID对应母包信息
        $sql   = "select sysid,gp_old_filename,gp_desc from ".get_table("game_parent")." where gp_gid=".$gid." and gp_upload_state=1";
        $query = $this->db->query($sql);
        while($rows = $this->db->FetchArray($query)){
            $str.= "<label><input type='radio' value='".$rows["sysid"]."' name='gamemb' id='gamemb' title='".$rows["gp_desc"]."'><span class='text'>".$rows["gp_old_filename"]."</span></label>";
        }
        if(!empty($str)){
            $arr = array("state"=>"scuess","data"=>$str);
        }else{
            $arr = array("state"=>"fail");
        }
        $this->db->FreeResult($query);
        echo json_encode($arr);
        exit;
    }
}
?>
