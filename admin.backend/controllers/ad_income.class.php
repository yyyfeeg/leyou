<?php

#=============================================================================
#     FileName: ad_income.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 广告总收入
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2016-02-18
#      History:
#=============================================================================

class Ad_income extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }
    /*用户注册登录数据*/
    public function regNum(){
        if ($this->isadmin != 1 && !$this->checkright("ad_income")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }
        // 接收参数
        $act = get_param('act');
        $explode = get_param('explode');
        $gid = get_param('gid','int');
        $uaid = get_param('uaid','int');
        $stime = get_param('stime') ? (strtotime(get_param('stime')) - 1) : strtotime(date('Ymd'))-1;
        $etime = get_param('etime') ? (strtotime(get_param('etime')) +86400) : strtotime(date('Ymd'))+86400;
        $warr = ad_get($this->db,'b','','',$uaid);//子渠道数据
        if ($act == 'search') {
            // 判断参数
            if (empty($gid) && empty($uaid)) {
                showinfo("请选择条件", "", 2);
            }
            $rlData = array();
            $data = array();
            //游客转正
            $trunSql = "SELECT COUNT(distinct `dul_name`) AS nums,`dul_uwid` FROM dcenter_count.`count_user_log` WHERE `dul_gid` = '" . $gid . "' AND `dul_uaid` = '" . $uaid . "' AND `dul_inserttime` > '" . $stime . "' AND `dul_inserttime` < '" . $etime . "' GROUP BY `dul_uwid`";
            $query = $this->db->Query($trunSql);
            while ($rows = $this->db->FetchArray($query)) {
                $rlData[$rows['dul_uwid']]['turnData'] = $rows['nums'];
            }
            //手机注册
            $phoneReg = "SELECT COUNT(distinct `ui_name`) AS nums,`ui_uwid` FROM dcenter_count.`count_user_info` WHERE `ui_gid` = '" . $gid . "' AND `ui_uaid` = '" . $uaid . "' AND `ui_phone` = `ui_name` AND `ui_regtime` > '" . $stime . "' AND `ui_regtime` < '" . $etime . "' GROUP BY `ui_uwid`";
            $query = $this->db->Query($phoneReg);
            while ($rows = $this->db->FetchArray($query)) {
                $rlData[$rows['ui_uwid']]['phoneData'] = $rows['nums'];
            }
            //一键注册
            $keyReg = "SELECT COUNT(distinct `ui_name`) AS nums,`ui_uwid` FROM dcenter_count.`count_user_info` WHERE `ui_gid` = '" . $gid . "' AND `ui_uaid` = '" . $uaid . "' AND `ui_utype` = '2' AND `ui_regtime` > '" . $stime . "' AND `ui_regtime` < '" . $etime . "' GROUP BY `ui_uwid`";
            $query = $this->db->Query($keyReg);
            while ($rows = $this->db->FetchArray($query)) {
                $rlData[$rows['ui_uwid']]['keyData'] = $rows['nums'];
            }
            //直接登录
            $loginSql = "SELECT COUNT(distinct `dg_uid`) AS nums,`dg_uwid` FROM dcenter_count.`count_gamelogin_log` WHERE `dg_gid` = '" . $gid . "' AND `dg_uaid` = '" . $uaid . "' AND `dg_type` = '1' AND `dg_logtime` > '" . $stime . "' AND `dg_logtime` < '" . $etime . "' GROUP BY `dg_uwid`";
            $query = $this->db->Query($loginSql);
            while ($rows = $this->db->FetchArray($query)) {
                $rlData[$rows['dg_uwid']]['loginData'] = $rows['nums'];
            }
            if (count($rlData) != '0') {
                foreach ($rlData as $key => $value) {
                    if (!array_key_exists($key,$warr)) {
                        continue;
                    }
                    //游客转正
                    $turnNum = $value['turnData'] ? $value['turnData'] : '0';
                    //手机注册数
                    $phoneNum = $value['phoneData'] ? $value['phoneData'] : '0';
                    //一键注册
                    $keyNum = $value['keyData'] ? $value['keyData'] : '0';
                    //直接帐号登录
                    $loginNum = $value['loginData'] ? $value['loginData'] : '0';
                    //数据判断
                    $loginNumRes = ($loginNum - $turnNum) < 0 ? 0 : ($loginNum - $turnNum);
                    //返回数据
                    $data[] = array(
                        $warr[$key],
                        $phoneNum,
                        $keyNum + $turnNum,
                        $loginNumRes
                    );
                }
                //导出数据
                if (isset($explode) && $explode == '1') {
                    include_once WEBPATH_DIR . "lyinclude/toexcel.class.php";
                    $head[0] = array('子广告站' , '手机注册数' , '一键注册' , '天拓帐号直接登录数');
                    $explodes   = array_merge($head,$data);
                    $xls = new Excel_XML( 'UTF-8', false, 'My Test Sheet' );
                    $xls->addArray ( $explodes );
                    $names = iconv("UTF-8", "GBK","注册登录数据");
                    $xls->generateXML(date('Ymd',($stime + 1)) . "-" . date('Ymd',($etime - 86400)) . '-' . $names);
                    exit;
                } else {
                    echo json_encode($data);
                }
            } else {
                echo json_encode(array('code' => 1001,'msg' => '没有数据！'));
            }
            exit;
        }
        $this->assign("gamestr",$this->get_select());
        $this->assign('flag','regNum');
        $this->display("ad_income.html");
    }
    /*注册留存列表*/
    public function income(){
        if ($this->isadmin != 1 && !$this->checkright("ad_income")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $this->assign("gamestr",$this->get_select());
        $this->assign("income", "income");
        $this->assign('meg','您已进入总收入列表中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("ad_income.html");
    }
    
    /*注册留存列表*/
    public function incomeinfo(){
        if ($this->isadmin != 1 && !$this->checkright("ad_income")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $aarr = ad_get($this->db);
        $garr = get_game($this->db);
        //获取详细信息
        $gid   = get_param("gid")?get_param("gid"):''; //游戏id
        $aid   = get_param("aid")?get_param("aid"):''; //主渠道
        $wid   = get_param("adsons")?get_param("adsons"):''; //子渠道
        $stime = get_param("starttime2")?get_param("starttime2"):$stime1=1;
        $etime = get_param("endtime2")?get_param("endtime2"):$etime1=1;

        //开始时间结束时间限制
        if($stime1 || $etime1){
            echo json_encode(array('str'=>'1001','meg'=>'开始时间或者结束时间不能为空！'));
            die;
        }else{
            $stime = strtotime($stime)-1;
            $etime = strtotime($etime)+86400;
        }

        if(strtotime($etime) - strtotime($stime)<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        get_games_conn();
        /*登录、付费、注册star*/
        $where          = " where 1";
        $stime?$where   .= " and gn_date>".date('Ymd',$stime):'';
        $etime?$where   .= " and gn_date<".date('Ymd',$etime):'';
        $gid?$where     .= " and gn_gameid=".$gid:'';
        $aid?$where     .= " and gn_aid=".$aid:'';
        $wid?$where     .= " and gn_wid=".$wid:'';
        $sql    = "SELECT gn_paynum,gn_logins,gn_date,gn_gameid,gn_aid,gn_wid,gn_paymoney,gn_reg FROM " .get_table('game_newpay'). " $where group by gn_gameid,gn_aid,gn_wid,gn_date";
        $query  = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $key = $rows['gn_date']."_".$rows['gn_gameid']."_".$rows['gn_wid'];
            $keys[$key] = $rows['gn_date']."_".$rows['gn_gameid']."_".$rows['gn_aid']."_".$rows['gn_wid'];
            $data1[$key]['logins'] = $rows['gn_logins'];
            $data1[$key]['pays'] = $rows['gn_paynum'];
            $data1[$key]['paymoney'] = $rows['gn_paymoney'];
            $data1[$key]['gn_reg'] = $rows['gn_reg'];
        }
        /*登录、付费、注册end*/

        //查询留存
        $where  = " where 1";
        $stime?$where .= " and cgr_regdate>".date('Ymd',$stime):'';
        $etime?$where .= " and cgr_regdate<".date('Ymd',$etime):'';
        $gid?$where .= " and cgr_gameid=".$gid:'';
        $aid?$where .= " and cgr_uaid=".$aid:'';
        $wid?$where .= " and cgr_uwid=".$wid:'';
        $cashinfo = 0;//缓存第一天数据

        $sql = "SELECT Group_concat(CONCAT_WS('||',cgr_remain,cgr_diff) order by cgr_diff asc ) as info,cgr_uaid,cgr_uwid,cgr_gameid,cgr_regdate FROM " .get_table('game_remain'). " $where group by cgr_gameid,cgr_uaid,cgr_uwid,cgr_regdate";

        $query = $this->db->Query($sql);
        
        while ($rows = $this->db->FetchArray($query)) {
            $arr2 = array('cgr_regdate'=>'空','info0'=>0,'info1'=>0,'info2'=>0,'info3'=>0,'info4'=>0,'info5'=>0,'info6'=>0,'info7'=>0,'info14'=>0,'info30'=>0,'info90'=>0);
            $key = $rows['cgr_regdate']."_".$rows['cgr_gameid']."_".$rows['cgr_uwid'];
            $keys[$key] = $rows['cgr_regdate']."_".$rows['cgr_gameid']."_".$rows['cgr_uaid']."_".$rows['cgr_uwid'];
            //php数据处理
            $arr = explode(',',$rows['info']);
            foreach($arr as $k=>$v){
                $rem_diff = explode('||',$v);
                //计算百分比所需基数
                if($rem_diff[1] == 0 && $cashinfo==0){
                    $cashinfo = $rem_diff[0];
                }
                if($rem_diff[1]<8 || $rem_diff[1]==14 || $rem_diff[1]==30){
                    $per = $cashinfo?round(($rem_diff[0]/$cashinfo)*100,2):0;
                    $arr2['info'.$rem_diff[1]] = $rem_diff[0]."[$per%]";
                }
            }
            $cashinfo = 0;
            $data2[$key]['info'] = $arr2;
            unset($arr2);
        }

        if(count($keys)>0){
            foreach ($keys as $k => $v) {
                $vals = explode('_', $v);
                $right_arr[]=array(
                    $vals[0],
                    $aarr[$vals[2]]?($aarr[$vals[2]]):$vals[2],
                    $aarr[$vals[3]]?($aarr[$vals[3]]):$vals[3],
                    $garr[$vals[1]]?($garr[$vals[1]]):$vals[1],

                    $data1[$k]['gn_reg']?$data1[$k]['gn_reg']:0,
                    $data1[$k]['logins']?$data1[$k]['logins']:0,
                    $data1[$k]['pays']?$data1[$k]['pays']:0,
                    $data1[$k]['paymoney']?$data1[$k]['paymoney']:0,
                    $data1[$k]['pays']?round($data1[$k]['paymoney'] / $data1[$k]['pays'],2):0,
                    $data1[$k]['logins']?round($data1[$k]['paymoney'] / $data1[$k]['logins'],2):0,
                    $data1[$k]['logins']?round(($data1[$k]['pays'] / $data1[$k]['logins'])*100,2):0,

                    $data2[$k]['info']['info0']?$data2[$k]['info']['info0']:0,
                    $data2[$k]['info']['info1']?$data2[$k]['info']['info1']:0,
                    $data2[$k]['info']['info2']?$data2[$k]['info']['info2']:0,
                    $data2[$k]['info']['info3']?$data2[$k]['info']['info3']:0,
                    $data2[$k]['info']['info4']?$data2[$k]['info']['info4']:0,
                    $data2[$k]['info']['info5']?$data2[$k]['info']['info5']:0,
                    $data2[$k]['info']['info6']?$data2[$k]['info']['info6']:0,
                    $data2[$k]['info']['info7']?$data2[$k]['info']['info7']:0,
                    $data2[$k]['info']['info14']?$data2[$k]['info']['info14']:0,
                    $data2[$k]['info']['info30']?$data2[$k]['info']['info30']:0,
                    $data2[$k]['info']['info90']?$data2[$k]['info']['info90']:0,
                );
            }
        }
            
        //查询留存end
        if(empty($right_arr)){
            echo json_encode(array('str'=>'1001','meg'=>'没有数据！'));
        }else{
            echo json_encode($right_arr);
        }
        die;
    }
}
?>

