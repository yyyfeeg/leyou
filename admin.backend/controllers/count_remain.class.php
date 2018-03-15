<?php

#=============================================================================
#     FileName: count_remain.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 注册留存类
#       Author: cooper
#        Email: cooper
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2015-08-26
#      History:
#=============================================================================

class Count_remain extends Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /*注册留存列表*/
    public function remainlist(){
        if ($this->isadmin != 1 && !$this->checkright("count_remain")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        $this->assign("gamestr",$this->get_select());
        $this->assign("remain", "remainlist");
        $this->assign('meg','您已进入注册留存列表中心！<br>搜索：1、在对应的列输入搜索信息2、存在“[2]”输入括号内的进行搜索');
        $this->display("count_remain.html");
    }
    
    /*注册留存列表*/
    public function remaininfo(){
        if ($this->isadmin != 1 && !$this->checkright("count_remain")) {
            echo json_encode(array('str'=>'1001','meg'=>'你没有权限执行该操作！'));
            die;
        }
        //获取详细信息
        $where  = " where 1";
        if(strtotime(get_param("endtime2")) - strtotime(get_param("starttime2"))<0){
            echo json_encode(array('str'=>'1001','meg'=>'结束日期不能小于开始日期！'));
            die;
        }
        $aarr = ad_get($this->db);
        $garr = get_game($this->db);
        get_param("starttime2")?$where .= " and cgr_regtime>".(strtotime(get_param("starttime2"))-1):'';
        get_param("endtime2")?$where .= " and cgr_regtime<".(strtotime(get_param("endtime2"))+86400):'';
        get_param("gid")?$where .= " and cgr_gameid=".get_param("gid"):'';
        get_param("aid")?$where .= " and cgr_uaid=".get_param("aid"):'';
        get_param("adsons")?$where .= " and cgr_uwid=".get_param("adsons"):'';
        get_games_conn();
        $cashinfo = 0;//缓存第一天数据


        $sql = "SELECT Group_concat(CONCAT_WS('||',cgr_remain,cgr_diff) order by cgr_diff asc) as info,cgr_uaid,cgr_uwid,cgr_gameid,cgr_regdate FROM " .get_table('game_remain'). " $where group by cgr_gameid,cgr_uaid,cgr_uwid,cgr_regdate";

        $query = $this->db->Query($sql);
        while ($rows = $this->db->FetchArray($query)) {
            $arr2 = array('cgr_regtime'=>'空','info0'=>0,'info1'=>0,'info2'=>0,'info3'=>0,'info4'=>0,'info5'=>0,'info6'=>0,'info7'=>0,'info14'=>0,'info30'=>0);
            //php数据处理
            $arr = explode(',',$rows['info']);
            foreach($arr as $k=>$v){
                $rem_diff = explode('||',$v);
                //计算百分比所需基数
                if($rem_diff[1] == 0 && $cashinfo==0){
                    $cashinfo = $rem_diff[0];
                }
                if($rem_diff[1]<8 || $rem_diff[1]==14 || $rem_diff[1]==30){
                    $per = $cashinfo?round($rem_diff[0]/$cashinfo,4)*100:0;
                    $arr2['info'.$rem_diff[1]] = $rem_diff[0]."[$per%]";
                }
            }
            $cashinfo = 0;
            $right_arr[]=array(
                $aarr[$rows['cgr_uaid']]?($aarr[$rows['cgr_uaid']]):$rows['cgr_uaid'],
                $aarr[$rows['cgr_uwid']]?($aarr[$rows['cgr_uwid']]):$rows['cgr_uwid'],
                $garr[$rows['cgr_gameid']]?($garr[$rows['cgr_gameid']]):$rows['cgr_gameid'],
                $rows['cgr_regdate'],
                $arr2['info0'],
                $arr2['info1'],
                $arr2['info2'],
                $arr2['info3'],
                $arr2['info4'],
                $arr2['info5'],
                $arr2['info6'],
                $arr2['info7'],
                $arr2['info14'],
                $arr2['info30'],
            );
            unset($arr2);
        }
        if(empty($right_arr)){
            echo json_encode(array('str'=>'1001','meg'=>'没有数据！'));
        }else{
            echo json_encode($right_arr);
        }
        die;
    }
}
?>

