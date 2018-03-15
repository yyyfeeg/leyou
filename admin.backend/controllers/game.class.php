<?php
/**
 * Copyright (C) 广东星辉天拓互动娱乐有限公司-游戏发行中心技术部
 * @project : 微信官网平台
 * @explain : 游戏管理类
 * @filename : game.class.php
 * @author : cooper
 * @codetime : 2015817
 * @modifier : cooper xiaobei
 * @modifytime: 20141124
 * 
 * @LastChange: Tang 799345505@qq.com 2015.11.13
 * 
 */
class Game extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $GLOBALS['IMG_UP']['image_upload_size'] = 3000000; //更改图片大小为3M
        $this->upimg = new upload_image();
        $this->path  = '../lyuploads/games/'.date("Y")."/".date("m")."/";
    }

    /**
     * @explain 游戏列表
     *
     */
    public function listgame()
    {
        if($this->isadmin!=1 && !$this->checkright("listgame")) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 推荐等级
        $rating_arr = array('','一星','两星','三星','四星','五星');
        $gtype_arr  = array('','页游','手游','双端');
        $virtue_arr = array('','Hot','New','推荐','幻灯');
        $team_arr   = get_team($this->db);// 团队列表

        $act = get_param('act');
        $sql = "select gi_key,gi_paykey,gi_repay_addr,sysid,gi_gname,gi_screen,gi_team,gi_description,gi_virtue,gi_rating,gi_icon,gi_photo,gi_azewm,gi_iosewm,gi_yyewm,gi_url,gi_azdlurl,gi_iosdlurl,gi_yydlurl,gi_gtype,gi_addtime,gi_status,gi_show,gi_custom from ".get_table("game_info")." order by gi_addtime desc";
        $query = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $str = '';
            $tmpArr = explode(',',$rows['gi_virtue']);
            foreach ($tmpArr as $value) {
                $str .= $virtue_arr[$value].' ,';
            }
            $rows['gi_virtue']  = substr($str,0,strlen($str)-1);
            
            $rows['gi_addtime'] = date('Y-m-d H:i:s',$rows['gi_addtime']);
            $rows['gi_status']  = ($rows['gi_status'] == 1) ? '开启':'关闭';
            $rows['gi_screen']  = ($rows['gi_screen'] == 1) ? '横屏':'竖屏';
            $rows['gi_show']    = ($rows['gi_show'] == 1) ? '展示':'不展示';
            $rows['gi_custom']  = ($rows['gi_custom'] == 1) ? '接入':'不接入';
            $rows['gi_gtype']   = $gtype_arr[$rows['gi_gtype']];
            $rows['gi_rating']  = $rating_arr[$rows['gi_rating']];
            $rows["gi_gname"]   = $rows["gi_gname"];
            $rows['gi_icon']    = "<img src='".$rows['gi_icon']."' width='100%' height='100px'/>";
            $rows['gi_photo']   = "<img src='".$rows['gi_photo']."' width='100%' height='100px'/>";
            $rows['gi_azewm']   = "<img src='".$rows['gi_azewm']."' width='100px' height='100px'/>";
            $rows['gi_iosewm']  = "<img src='".$rows['gi_iosewm']."' width='100px' height='100px'/>";
            $rows['gi_yyewm']   = "<img src='".$rows['gi_yyewm']."' width='100px' height='100px'/>";
            $rows['action']     = "<a href='index.php?module=game&method=edit&id=".$rows['sysid']."'>[修改]</a>";
            if($this->isadmin==1){
                $rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=game&method=del&id=".$rows['sysid']."'>[删除]</a>";
                $rows['gi_key']         = $rows['gi_key'];
                $rows['gi_repay_addr']  = $rows['gi_repay_addr'];
                $rows['gi_paykey']      = $rows['gi_paykey'];
            }else{
                $rows['gi_key']         = "";
                $rows['gi_repay_addr']  = "";
                $rows['gi_paykey']      = "";
            }

            $data[]=array(
                    $rows['sysid'],
                    $rows['gi_gname'],
                    $rows['gi_gtype'],
                    $rows['gi_virtue'],
                    $rows['gi_rating'],
                    $rows['gi_screen'],
                    $team_arr[$rows['gi_team']],
                    $rows['gi_url'],
                    $rows['gi_azdlurl'],
                    $rows['gi_iosdlurl'],
                    $rows['gi_yydlurl'],
                    $rows['gi_description'],
                    $rows['gi_icon'],
                    $rows['gi_photo'],
                    $rows['gi_azewm'],
                    $rows['gi_iosewm'],
                    $rows['gi_yyewm'],
                    $rows['gi_status'],
                    $rows['gi_show'],
                    $rows['gi_custom'],
                    $rows['gi_addtime'],
                    $rows['action'],
                    $rows['gi_key'],
                    $rows['gi_paykey'],
                    $rows['gi_repay_addr'],
                );
        }
        $this->assign("game","gamelist");
        $this->assign("data",get_json_encode($data));
        $this->assign('meg','游戏列表页面！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display('game.html');
    }

    /**
     * @explain 游戏添加
     *
     */
    public function add()
    {
        if ($this->isadmin != 1 && !$this->checkright("addgames")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        $act                    = get_param("act");
        $flag                   = get_param("flag");
        $type                   = get_param("type");
        $data['gi_gname']       = get_param("gname");
        $data['gi_gtype']       = get_param("gtype")?get_param("gtype"):1;
        $data['gi_virtue']      = get_array("virtue",',',true);
        $data['gi_rating']      = get_param("rating");
        $data['gi_screen']      = get_param("screen")?get_param("screen"):1;
        $data['gi_description'] = get_param("description");
        $data['gi_team']        = get_param("team");
        $data['gi_url']         = get_param("url");
        $data['gi_azdlurl']     = get_param("azdlurl");
        $data['gi_iosdlurl']    = get_param("iosdlurl");
        $data['gi_yydlurl']     = get_param("yydlurl");
        $data['gi_kfphone']     = get_param("kfphone");
        $data['gi_icon']        = get_param("icon_url");
        $data['gi_photo']       = get_param("photo_url");
        $data['gi_azewm']       = get_param("azewm_url");
        $data['gi_iosewm']      = get_param("iosewm_url");
        $data['gi_yyewm']       = get_param("yyewm_url");
        $data['gi_order']       = get_param("order");
        $data['gi_key']         = StrOrderOne();
        $data['gi_paykey']      = getRandChar(16);
        $data['gi_show']        = get_param("show")?get_param("show"):1;
        $data['gi_custom']      = get_param("custom")?get_param("custom"):2;
        $data['gi_status']      = get_param("status")?2:1;
        $data['gi_repay_addr']  = get_param("repay_addr");
        $data['gi_addtime']     = THIS_DATETIME;
        $data['gi_addid']       = $this->my_admin_id;

        $tmpArr = array('','icon','photo','azewm','iosewm','yyewm');
        $team_arr = get_team($this->db);// 团队列表

        if ($act == 'addgame' && $flag != 'up')
        {
            //判断是否为空
            if ($data['gi_gname'] == '') {
                showinfo("游戏名称不能为空!", "index.php?module=game&method=add",2);
            }
            if ($data['gi_description'] == '') {
                showinfo("游戏描述不能为空!", "index.php?module=game&method=add",2);
            }
            if ($data['gi_team'] == '') {
                showinfo("选择所属游戏团队!", "index.php?module=game&method=add",2);
            }
            // if ($data['gi_icon'] == '') {
            //     showinfo("请上传游戏icon!", "index.php?module=game&method=add",2);
            // }
            if ($data['gi_url'] == '') {
                showinfo("官网地址不能为空!", "index.php?module=game&method=add",2);
            }

            $sql      = "select 1 from ".get_table("game_info")." where gi_gname='".$data["gi_gname"]."'";
            $is_exist = $this->db->getOne($this->db->query($sql));

            if ($is_exist) {

                //如果存在，上传的图片就删除
                foreach ($tmpArr as $value) {
                    if(!empty($data['gi_'.$value]) && $this->fileobj->isExists($data['gi_'.$value])){
                        $this->fileobj->rm($data['gi_'.$value]);
                    }
                }
                
                $this->admin_log("添加新游戏" . $data['gi_gname'] . "失败，原因：游戏已存在");
                showinfo("游戏或名称已存在!", "", 3);

            } else {
                // 添加记录
                $res = add_record($this->db, "game_info", $data);
                if ($res['rows'] > 0) {

                    $this->admin_log("添加游戏" . $data['gi_gname'] . "成功");
                    showinfo("添加成功!", "index.php?module=game&method=listgame", 4);
                } else {

                    //如果添加失败，上传的图片就删除
                    foreach ($tmpArr as $value) {
                        if(!empty($data['gi_'.$value]) && $this->fileobj->isExists($data['gi_'.$value])){
                            $this->fileobj->rm($data['gi_'.$value]);
                        }
                    }

                    $this->admin_log("添加游戏" . $data['gi_gname'] . "失败，原因：数据库插入失败");
                    showinfo("添加失败!请重新再试!", "index.php?module=game&method=add", 2);
                }
            }
        }

        //文件上传开始
        if ($flag == 'up' && !empty($type)) 
        {
            $file = $_FILES[$tmpArr[$type]];
            $url = $tmpArr[$type].'_url';
            $err = 'error'.$type;
            $suc = 'success'.$type;

            // 重复上传，删除之前上传的文件
            if (!empty($data['gi_'.$tmpArr[$type]])) @unlink(WEBPATH_DIR.$data['gi_'.$tmpArr[$type]]);

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
        $virtueArr = (!empty($data['gi_virtue'])) ? explode(',', $data['gi_virtue']):'';

        $this->assign("game", "add");
        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("imgArr", $imgArr);
        $this->assign('virtueArr',$virtueArr);
        $this->assign("teamArr",$team_arr);
        $this->assign("data", $data);
        $this->assign("isadmin", $this->isadmin);
        $this->display("game.html");
    }

    /**
     * @explain 编辑游戏
     *
     */
    public function edit()
    {
        if ($this->isadmin != 1 && !$this->checkright("addgames")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        $act                    = get_param("act");
        $flag                   = get_param("flag");
        $type                   = get_param("type");
        $data['sysid']          = get_param("id");
        $data['gi_gname']       = get_param("gname");
        $data['gi_gtype']       = get_param("gtype")?get_param("gtype"):1;
        $data['gi_virtue']      = get_array("virtue",',',true);
        $data['gi_rating']      = get_param("rating");
        $data['gi_screen']      = get_param("screen")?get_param("screen"):1;
        $data['gi_description'] = get_param("description");
        $data['gi_team']        = get_param("team");
        $data['gi_url']         = get_param("url");
        $data['gi_azdlurl']     = get_param("azdlurl");
        $data['gi_iosdlurl']    = get_param("iosdlurl");
        $data['gi_yydlurl']     = get_param("yydlurl");
        $data['gi_kfphone']     = get_param("kfphone");
        $data['gi_icon']        = get_param("icon_url");
        $data['gi_photo']       = get_param("photo_url");
        $data['gi_azewm']       = get_param("azewm_url");
        $data['gi_iosewm']      = get_param("iosewm_url");
        $data['gi_yyewm']       = get_param("yyewm_url");
        $data['gi_order']       = get_param("order");
        $data['gi_key']         = get_param("up_key");
        $data['gi_paykey']      = get_param("pay_key");
        $data['gi_show']        = get_param("show")?get_param("show"):1;
        $data['gi_custom']      = get_param("custom")?get_param("custom"):2;
        $data['gi_status']      = get_param("status")?2:1;
        $data['gi_repay_addr']  = get_param("repay_addr");
        $data['gi_uptime']      = THIS_DATETIME;
        $data['gi_upid']        = $this->my_admin_id;
        $team_arr = get_team($this->db);// 团队列表

        // 查询图片信息
        $photo_sql = "select gi_icon,gi_photo,gi_azewm,gi_iosewm,gi_yyewm from ".get_table('game_info')." where sysid = ".$data['sysid'];
        $photo = $this->db->getOne($this->db->Query($photo_sql));

        if ($act == 'addgame' && $flag != 'up')
        {
            //判断是否为空
            if ($data['gi_gname'] == '') {
                showinfo("游戏名称不能为空!", "index.php?module=game&method=add",2);
            }
            if ($data['gi_description'] == '') {
                showinfo("游戏描述不能为空!", "index.php?module=game&method=add",2);
            }
            if ($data['gi_team'] == '') {
                showinfo("选择所属游戏团队!", "index.php?module=game&method=add",2);
            }
            // if ($data['gi_icon'] == '') {
            //     showinfo("请上传游戏icon!", "index.php?module=game&method=add",2);
            // }
            if ($data['gi_url'] == '') {
                showinfo("官网地址不能为空!", "index.php?module=game&method=add",2);
            }

            $sql = "select 1 from ". get_table("game_info") . " where sysid != {$data['sysid']} and gi_gname='".$data["gi_gname"]."'";
            $is_exist = $this->db->getOne($this->db->query($sql));

            if ($is_exist) {

                //如果存在，上传的图片与数据库的图片不一致就删除当前图片
                foreach ($photo as $key => $value) {
                    if ($photo[$key] != $data[$key]) {
                        if(!empty($data[$key]) && $this->fileobj->isExists($data[$key])){
                            $this->fileobj->rm($data[$key]);
                        }
                   }
                }
                $this->admin_log("修改新游戏" . $data['gi_gname'] . "失败，原因：游戏已存在");
                showinfo("游戏或名称已存在!", "", 3);
            } else {

                $result = update_record($this->db,'game_info',$data,array('sysid'=>$data['sysid']),'',1);
                if ( $result ) {
                    // 修改成功，删除之前数据库中的图片
                    foreach ($photo as $key => $value) {
                        if (!empty($photo[$key]) && $photo[$key] != $data[$key]) {
                            if(!empty($data[$key]) && $this->fileobj->isExists($photo[$key])){
                                $this->fileobj->rm($photo[$key]);
                            }
                       }
                    }
                    $this->admin_log("修改游戏" . $data['gi_gname'] . "成功");
                    showinfo("修改成功!", "index.php?module=game&method=listgame", 4);
                } else {

                    //如果存在，上传的图片与数据库的图片不一致就删除当前图片
                    foreach ($photo as $key => $value) {
                        if ($photo[$key] != $data[$key]) {
                            if(!empty($data[$key]) && $this->fileobj->isExists($data[$key])){
                                $this->fileobj->rm($data[$key]);
                            }
                       }
                    }
                    $this->admin_log("修改游戏" . $data['gi_gname'] . "失败，原因：数据库插入失败");
                    showinfo("修改失败!请重新再试!", "index.php?module=game&method=add", 2);
                }
            }
        }
        
        //文件上传开始
        if ($flag == 'up' && !empty($type)) {

            $tmpArr = array('','icon','photo','azewm','iosewm','yyewm');
            // 删除重复上传的图片
            if (!empty($data['gi_'.$tmpArr[$type]]) && $data['gi_'.$tmpArr[$type]] != $photo['gi_'.$tmpArr[$type]]) {
                if($this->fileobj->isExists($data['gi_'.$tmpArr[$type]])) {
                    $this->fileobj->rm($data['gi_'.$tmpArr[$type]]);
                }
            }

            $file = $_FILES[$tmpArr[$type]];
            $url = $tmpArr[$type].'_url';
            $err = 'error'.$type;
            $suc = 'success'.$type;
            
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
        }else{
            //查询出当前修改数据
            $data = $this->db->getOne($this->db->query("select * from " . get_table("game_info") . " where sysid=".$data['sysid']));
        }

        $img = implode('，', str_replace("image/", "", $GLOBALS['IMG_UP']['image_mime']));
        $size = $GLOBALS['IMG_UP']['image_upload_size'] / 1000000;
        $imgArr = array($url => $info, $err => $error, $suc => $success);
        $virtueArr = (!empty($data['gi_virtue'])) ? explode(',', $data['gi_virtue']):'';

        $this->assign("game", "edit");
        $this->assign("img", $img);
        $this->assign("size", $size);
        $this->assign("imgArr", $imgArr);
        $this->assign('virtueArr',$virtueArr);
        $this->assign("teamArr",$team_arr);
        $this->assign("data", $data);
        $this->assign("isadmin", $this->isadmin);
        $this->display("game.html");
    }

    /**
     * @explain 删除游戏
     *
     */
    public function del()
    {
        if ($this->isadmin != 1 && !$this->checkright("delgames")) {
            showinfo("你没有权限执行该操作。", "", 3);
        }

        $where['sysid'] = get_param("id", "int");
        $sql  = "select gi_icon,gi_photo,gi_azewm,gi_iosewm,gi_yyewm,gi_gname from ".get_table("game_info")." where sysid=".$where["sysid"];
        $pics = $this->db->getOne($this->db->query($sql));
        $ret  = delete_record($this->db, "game_info", $where);

        if ($ret) {

            //删除图片
            foreach ($pics as $key => $value) {
                if ($key == 'gi_gname') continue;
                if(!empty($pics[$key]) && $this->fileobj->isExists($pics[$key])){
                    $this->fileobj->rm($pics[$key]);
                }
            }
            $this->admin_log("删除游戏 " . $pics["gi_gname"] . " 成功");
            showinfo("删除成功!", "index.php?module=game&method=listgame", 4);
        } else {
            
            $this->admin_log("删除游戏 " . $pics["gi_gname"] . " 失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
        }
    }
}
?>
