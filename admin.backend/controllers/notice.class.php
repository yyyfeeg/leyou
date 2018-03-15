<?php

#=============================================================================
#     FileName: notice.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 测试
#       Author: jericho
#        Email: jericho
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2017-09-18
#      History:
#=============================================================================
class Notice extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
        $tps  = get_param("tps")?get_param("tps"):0;
        $this->assign("tps",$tps);
    }
    
    /*
    *  公告列表
    */
    public function nlist(){
        //获取搜索条件
        if ($this->isadmin != 1 && !$this->checkright("notice_list")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        //获得游戏相关
        $garr   = get_game($this->db);
        $aarr   = ad_get($this->db);
        $sarr   = get_servers($this->db);

        $where      = " where 1";
        $gid        = get_param("gid");
        $aid        = get_param("aid");
        $tid        = get_param("tid");
        $mtype      = get_param("status");
        $starttime2 = get_param("starttime2")?get_param("starttime2"):date("Y-m-01");
        $endtime2   = get_param("endtime2")?get_param("endtime2"):'';

        //条件判定
        $starttime2?$where  .= " and gn_startdate>".date('Ymd',strtotime($starttime2)-1):'';
        $endtime2?$where    .= " and gn_enddate<".date('Ymd',strtotime($endtime2)+86400):'';
        $gid?$where         .= " and gn_gid=".get_param("gid"):'';
        $aid?$where         .= " and gn_uaid=".get_param("aid"):'';
        $mtype?$where       .= " and gn_status=".get_param("status"):'';

        $this->assign("gamestr",$this->get_select($gid));

        //分页
        $pagesize = 20;
        $page = empty($_GET['page'])?1:intval($_GET['page']);
        if($page<1) $page=1;
        $start = ($page-1)*$pagesize;
        $url = $_SERVER['PHP_SELF'];
        // 切换到dcenter_base数据库
        $sql    = "SELECT count(*) c FROM " .get_table('game_notice'). " $where";
        $totalrecord = $this->db->getOne($this->db->Query($sql));

        $sql =" select * from " .get_table('game_notice'). " $where order by sysid desc LIMIT $start, $pagesize";
        $query  = $this->db->Query($sql);
        $str    =  "";
        $gn_state = array('','未发布','已发布','已下架');
        while ($rows = $this->db->FetchArray($query)) {
            $str .= "<tr>";
            $rows["gn_gid"]     = $garr[$rows["gn_gid"]]."[".$rows["gn_gid"]."]";
            $rows["gn_uaid"]    = $aarr[$rows["gn_uaid"]];
            $rows["gn_status"]  = $gn_state[$rows["gn_status"]];
            if($this->checkright("notice_list")){
                $rows['edit'] = "&nbsp;&nbsp;&nbsp;<a href='index.php?module=notice&method=nedit&sysid=".$rows['sysid']."'>[修改]</a>";
                $rows['del'] = "&nbsp;&nbsp;&nbsp;<a href='index.php?module=notice&method=ndel&sysid=".$rows['sysid']."'>[删除]</a>";
            }
            $rows["gn_startdate"]  = date('Y-m-d',strtotime($rows["gn_startdate"]));
            $rows["gn_enddate"]  = $rows["gn_enddate"]?date('Y-m-d',strtotime($rows["gn_enddate"])):'-';
            $arrs[] = $rows;
        }
        $url    .=  "?module=notice&method=nlist&starttime2=$starttime2&endtime2=$endtime2&gid=$gid&aid=$aid&tid=$tid&mtype=$mtype";
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
        $this->assign("status",$mtype);
        $this->assign("aid",$aid);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gp_aids", ad_get($this->db,a));
        $this->assign("title",'公告列表');
        $this->assign("active","nlist");
        $this->assign('meg','您已进入公告管理列表！<br>--在对应的列输入搜索信息');
        $this->display("notice.html");
    }

    /*
    *   添加公告
    */
    public function nadd(){
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('notice_add')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 获取参数
        $act  = get_param('act');
        $flag = get_param('flag');
        $type = get_param('type');
        $data['gn_title']     = get_param('title');
        $data['gn_gid']       = get_param('gid');
        $data['gn_uaid']      = get_param('aid');
        $data['gn_contents']  = htmlspecialchars($_POST['contents']);
        $data['gn_sort']      = get_param('order');
        $data['gn_startdate'] = get_param('starttime') ? get_param('starttime'):date('Y-m-d',time());
        $data['gn_enddate']   = get_param('endtime') ? get_param('endtime'):0;
        $data['gn_status']    = get_param('status') ? get_param('status'):1;

        $data['gn_adduid']  = $this->my_admin_id;
        $data['gn_addtime'] = THIS_DATETIME;
        $data['gn_addname'] = $this->myad_realname;

        // 添加文章
        if ($act == 'add') {
            // 验证参数完整性
            if (empty($data['gn_title'])) {
                showinfo("公告标题不能为空!", "index.php?module=notice&method=nadd",3);
            }
            if ($data['gn_gid'] == '') {
                showinfo("请选择所属游戏!", "index.php?module=notice&method=nadd",3);
            }
            if ($data['gn_startdate'] == '') {
                showinfo("生效日期不能为空!", "index.php?module=notice&method=nadd",3);
            }
            $data['gn_startdate'] = date('Ymd',strtotime($data['gn_startdate']));
            $data['gn_enddate'] = $data['gn_enddate']?date('Ymd',strtotime($data['gn_startdate'])):0;
            // 添加记录
            $result = add_record($this->db,'game_notice',$data);
            if ($result['rows'] > 0) {
                $this->admin_log("添加新公告 ". $data['gn_title'] ." 成功");
                showinfo("添加文章成功!", "index.php?module=notice&method=nlist", 4);
            } else {
                $this->admin_log("添加新公告 ". $data['gn_title'] ." 失败，原因：数据库插入失败");
                showinfo("添加失败!请重新再试!", "index.php?module=notice&method=nadd", 3);
            }
        }

        // 返回数据处理
        $data['gn_contents'] = htmlspecialchars_decode($data['gn_contents']);

        $this->assign("gamestr",$this->get_select($data['gn_gid']));
        $this->assign("gp_aids", ad_get($this->db,a));
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign("adsons",$adsons);
        $this->assign('active',"nadd");
        $this->assign('title',"添加公告");
        $this->assign('meg','您已进入游戏公告添加！');
        $this->display("notice.html");
    }
   /**
     * 修改公告
     * @return [type] [description]
     */
    public function nedit()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('notice_add')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        // 获取参数
        $act  = get_param('act');
        $flag = get_param('flag');
        $type = get_param('type');
        $sysid= get_param('sysid');
        $data['gn_title']     = get_param('title');
        $data['gn_gid']       = get_param('gid');
        $data['gn_uaid']      = get_param('aid');
        $data['gn_contents']  = htmlspecialchars($_POST['contents']);
        $data['gn_sort']      = get_param('order');
        $data['gn_startdate'] = get_param('starttime') ? get_param('starttime'):date('Y-m-d',time());
        $data['gn_enddate']   = get_param('endtime') ? get_param('endtime'):0;
        $data['gn_status']    = get_param('status') ? get_param('status'):1;

        $data['gn_adduid'] = $this->my_admin_id;
        $data['gn_addtime'] = THIS_DATETIME;
        $data['gn_addname'] = $this->myad_realname;

        // 修改文章
        if ($act == 'edit') {
            // 验证参数完整性
            if (empty($data['gn_title'])) {
                showinfo("公告标题不能为空!", "index.php?module=notice&method=nedit",3);
            }
            if ($data['gn_gid'] == '') {
                showinfo("请选择所属游戏!", "index.php?module=notice&method=nedit",3);
            }
            if ($data['gn_startdate'] == '') {
                showinfo("生效日期不能为空!", "index.php?module=notice&method=nedit",3);
            }

            $data['gn_startdate'] = date('Ymd',strtotime($data['gn_startdate']));
            $data['gn_enddate'] = $data['gn_enddate']?date('Ymd',strtotime($data['gn_startdate'])):0;

            // 更新数据
            $result = update_record($this->db,'game_notice',$data,array('sysid'=>$sysid),'',1);

            if ($result) {
                $this->admin_log("修改公告 " . $data['gn_title'] . " 成功");
                showinfo("修改成功!", "index.php?module=notice&method=nlist", 4);

            } else {
                $this->admin_log("修改公告 " . $data['gn_title'] . " 失败，原因：数据库插入失败");
                showinfo("修改失败!请重新再试!", "index.php?module=notice&method=nedit&sysid=".$sysid, 3);
            }
        }

        // 查询原有数据
        $sql = "select * from ".get_table('game_notice')." where sysid = ".$sysid;
        $data = $this->db->getOne($this->db->Query($sql));

         // 返回数据处理
        $data['gn_contents'] = htmlspecialchars_decode($data['gn_contents']);
        $data['gn_startdate'] = date('Y-m-d',strtotime($data['gn_startdate']));
        $data['gn_enddate'] = $data['gn_enddate']?date('Y-m-d',strtotime($data['gn_enddate'])):0;

        $this->assign("gamestr",$this->get_select($data['gn_gid']));
        $this->assign("gp_aids", ad_get($this->db,a));
        $this->assign("str",$str);
        $this->assign("starttime2",$starttime2);
        $this->assign("endtime2",$endtime2);
        $this->assign("gid",$gid);
        $this->assign("tid",$tid);
        $this->assign("aid",$aid);
        $this->assign('data',$data);
        $this->assign('sysid',$sysid);
        $this->assign('active',"nedit");
        $this->assign('title',"修改公告");
        $this->assign('meg','您已进入游戏公告添加！');
        $this->display("notice.html");
    }

    /**
     * 删除文章
     * @return [type] [description]
     */
    public function ndel()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('notice_add')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        $where['sysid'] = get_param('sysid');

        $result = delete_record($this->db,'game_notice',$where);
        if ($result) {
            // 删除成功
            $this->admin_log("删除公告成功");
            showinfo("删除成功!", "index.php?module=notice&method=nlist", 4);
        } else {
            // 删除失败
            $this->admin_log("删除公告失败，原因:数据库删除失败");
            showinfo("删除失败,请重试!", "", 3);
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
}
?>