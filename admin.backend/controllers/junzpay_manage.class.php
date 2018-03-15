<?php
#============================================
# 	FileName: junzpay_manage.class.php
# 		Desc: 钧展web支付管理程序文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.12.26
# LastChange: 
#============================================

class Junzpay_manage extends Controller
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

		// 检查权限
		if ($this->isadmin != 1 && !$this->checkright('junzpay_manage')) {
			showinfo("你没有权限执行该操作。","",2);
		}
	}

	/**
	 * 成功充值订单查询
	 */
	public function junzpay_success()
	{
		if ($this->isadmin != 1 && !$this->checkright("junzpay_success")) {
            showinfo("你没有权限执行该操作。", "", 2);
        }

        // 接收参数
        $type = get_param('type');

        if ($type == 'json') {

        	// 支付方式
        	$payway = array('1'=>'支付宝','2'=>'微信','3'=>'银联');

        	// 展示字段
        	$rols_arr = array('sysid','ol_orderid','ol_ortherid','ol_uid','ol_money','ol_gid','ol_sid','ol_rid','ol_paytime','ol_payway','ol_giveresult','ol_givetime','ol_goodsnum','ol_giftnum');

        	//获得游戏相关
	        $gameArr   = get_game($this->db);

	        //获得参数
	        $where = " where 1";
	        $sEcho = get_param('sEcho');// DataTables 用来生成的信息
	        $star  = get_param('iDisplayStart')?get_param('iDisplayStart'):0;//显示的起始索引
	        $lenth = get_param('iDisplayLength')?get_param('iDisplayLength'):10;//显示的行数
	        $cols  = get_param('iSortCol_0');//被排序的列
	        $asc   = get_param('sSortDir_0');//排序的方向 "desc" 或者 "asc"

	        //循环查询条件
	        for($i=0;$i<9;$i++){
	            if($i==2){
	                $where .=  get_param('sSearch_'.$i)?" and {$rols_arr[$i]} like '%".get_param('sSearch_'.$i)."%'":'';
	            }else{
	                $where .=  get_param('sSearch_'.$i)?" and {$rols_arr[$i]} = '".get_param('sSearch_'.$i)."'":'';
	            }
	        }

        	// 查询成功充值订单信息
        	$sql = "select * from `dcenter_count`.count_orderform_log ".$where. " limit {$star},{$lenth}";
        	$query = $this->db->Query($sql);

        	while ($rows = $this->db->FetchArray($query)) {

        		// 查询账号名
        		$sql = "select ui_name from `dcenter_count`.count_user_info where sysid = ".$rows['ol_uid'];
        		$uname = $this->db->getOne($this->db->Query($sql));


        		$data['aaData'][] = array(
        			$rows['sysid'],
        			$rows['ol_orderid'],
        			$rows['ol_ortherid'],
        			$uname['ui_name']."[".$rows['ol_uid']."]",
        			$rows['ol_money'],
        			$gameArr[$rows['ol_gid']]."[".$rows['ol_gid']."]",
        			$rows['ol_sname']."[".$rows['ol_sid']."]",
        			$rows['ol_rname']."[".$rows['ol_rid']."]",
        			date('Y-m-d H:i:s',$rows['ol_paytime']),
        			$payway[$rows['ol_payway']],
        			($rows['ol_giveresult']==1)? '成功':'失败',
        			date('Y-m-d H:i:s',$rows['ol_givetime']),
        			$rows['ol_goodsnum'],
        			$rows['ol_giftnum']
        			);
        	}

        	if (empty($data)) $data['aaData'] = array();

        	//得到所有条数
	        $sql   = "select count(*) as total from  `dcenter_count`.count_orderform_log ".$where;
	        $query = $this->db->Query($sql);
	        $total = $this->db->getOne($query);

        	$data['sEcho'] = $sEcho;
	        $data['iTotalDisplayRecords'] = $total['total'];
	        $data['iTotalRecords'] = $total['total'];
        	echo json_encode($data);
        	exit;
        }

        $this->assign("list", "list");
        $this->display("junzpay_manage.html");
	}
}
?>