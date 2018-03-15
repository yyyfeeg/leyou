<?php
#============================================
# 	FileName: pay.php
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.12
# LastChange: 
#============================================

// 包含总配置文件
include_once('./config.inc.php');
include_once('../configs/config.php');

//接收基础参数
$cfg 		=	new Config();
$ginfo 		=   empty($GLOBALS["CONF_GAME"])?$cfg->ginfo:$GLOBALS["CONF_GAME"];
$date 		=	date("Ymd");
$goref      =   1;
$par 		=	get_param("par");			//如存在，则为wap支付

if(empty($par)){
	$uid		=	get_param("uid");			//用户ID
	$gid 		=	get_param("gid");			//游戏ID
	$sid		=	get_param("sid");			//游戏服ID
	$uaid  	 	= 	get_param("uaid","int");	//渠道信息
	$uwid   	= 	get_param("uwid","int");	//子渠道
	$sign		=	get_param("sign");			//加密sign值
	$roleid 	=	get_param("roleid");		//角色ID
	$rolename   =   get_param("rolename");		//角色名	需urlencode
	$ptype		=	get_param("ptype");			//支付类型 1：浦发银行--微信  2：支付宝
	$goodsid	=	get_param("goodsid");		//商品ID
	$goodsname  =   get_param("goodsname");		//商品名称	需urlencode
	$nums		=	get_param("nums");			//游戏币数量
	$money		=	get_param("money",'double');//金额,单位(分)
	$uptime 	= 	get_param("ts","int");		//充值时间  
	$ip 		=	get_user_ip();
	$wap		=   get_param("wap")?get_param("wap"):1;		//充值方式 1：SDK   2：wap微信   3:wap支付宝
	$gameorder  =   get_param("gameorder");     //游戏订单号   
}else{
	//ptype 、 wap 为传值
	$pars  		= 	unserialize(decrypt(base_code($par)));
	$ptype		=	get_param("ptype");			//支付类型 1：微信   2：支付宝 
	$wap		=   get_param("wap");			//充值方式 1：SDK   2：wap微信   3:wap支付宝

	$uid		=	$pars["uid"];			//用户ID
	$gid 		=	$pars["gid"];			//游戏ID
	$sid		=	$pars["sid"];			//游戏服ID
	$uaid  	 	= 	$pars["uaid"];			//渠道信息
	$uwid   	= 	$pars["uwid"];			//子渠道
	$sign		=	$pars["sign"];			//加密sign值
	$gameorder  =   $pars["gameorder"];     //游戏订单号  
	$roleid 	=	$pars["roleid"];		//角色ID
	$rolename   =   $pars["rolename"];		//角色名	
	$goodsid	=	$pars["goodsid"];		//商品ID
	$goodsname  =   $pars["goodsname"];		//商品名称
	$nums		=	$pars["nums"];			//游戏币数量
	$money		=	$pars["money"];			//金额,单位(分)
	$uptime 	= 	$pars["ts"];			//充值时间  
	$ip 		=	$pars["ip"]; 			//IP地址
	$ref        =   $_SERVER['HTTP_REFERER'];  

	//判断上级来源
	if(strstr($ref, WEBPATH_DIR_INC."wap.php")>0){
        $goref = 0;
    }
}


//判断基础参数
if(empty($gid) || empty($uid) || empty($money)  || empty($sid) || empty($sign) || empty($uaid) || empty($uwid) || empty($roleid) ||empty($cfg->pifg[$ptype]) || empty($ginfo[$gid]) || empty($goref)){
	exit("1001");
}


//对比加密信息
$rolename	=	urldecode($rolename);
$goodsname	=	urldecode($goodsname);
$key		=	$ginfo[$gid]['key'];
$mysign		=	md5($uptime.$gid.$uid.$sid.$uaid.$uwid.$roleid.$goodsid.$key);
if($mysign!=$sign){
	exit("1002");
}
unset($key);


//生成唯一订单号
$param  		=   $uid.$gid.$sid.rand(1,1000);		
$out_trade_no 	=  	StrOrderOne($param);						//生成唯一订单号
$body			=	$goodsname.'['.$goodsid.']';				//商品描述


//充值信息入redis，防止高并发
$str_tmp = $uptime."д".$gid."д".$uaid."д".$uwid."д".$sid."д".$uid."д".$money."д".$roleid."д".$goodsid."д".$ptype."д".$nums;
// $exist   = $GLOBALS["redis"]->sismember("orders_tmp",$str_tmp);  
// if(!$exist){
	$GLOBALS["redis"]->sadd("orders_tmp",$str_tmp);  
// }else{
	// exit("9999");
// }


//区别App与Wap
if($wap == 3){
	$ret = "wap_request.php";
	$ns  = "2";
}else{
	$ret = "app_request.php";
	$ns  = "3";
}

switch($ptype){
	//浦发--微信
	case 1:		
		//获取对应信息
		$url			=	$cfg->P($ptype,'url');
		$mchId			=	$cfg->P($ptype,'mchId');
		$key			=	$cfg->P($ptype,'key');
		$version		=	$cfg->P($ptype,'version');

		//异步执行充值相关
		$url = WEBPATH_NW."wft/request.php";
		$params = array(
			"ptype"			=> $ptype,
			"wap"			=> $wap,
			"method"		=> "submitOrderInfo",
			"out_trade_no"	=> $out_trade_no,
			"body"			=> empty($goodsname)?"游戏礼包":$goodsname,
			"attach"		=> base_decode(encrypt($str_tmp)),		//把带+号等特殊字符处理为%2X
			"total_fee"		=> $money,
			"mch_create_ip"	=> $ip,
			"notify_url"	=> WEBPATH_DIR_INC."handle1.php",	//WEBPATH_DIR_INC."handle-2-1.html",		//伪静态
		);
		break;
	//支付宝--官方
	case 2:
		$url 	= WEBPATH_NW."alipay/".$ret;
		$params = array(
			'body'			  =>  $body,

			'subject' 		  =>  empty($goodsname)?"游戏礼包":$goodsname,

            'out_trade_no'    =>  $out_trade_no,   //订单号必须是唯一

            'total_amount'    =>  sprintf("%.2f",$money/100),//金額最好能要保留小数点后两位数

            'passback_params' =>  base_decode(encrypt($str_tmp)),

            "notify_url"	  =>  WEBPATH_DIR_INC."handle2.php",//WEBPATH_DIR_INC."handle-2-".$ns.".html",
		);

		//WAP支付有同步回调页参数设置
		if($wap == 3){
			$params["return_url"] = "http://www.hlwy.com/pay/outcome.php";		//测试
		}
		break;
}


//记录日志文件
// $fname = WEBPATH_DIR."pay/cache/".date("Ymd")."-order_pay.txt";	
// file_put_contents($fname,serialize($params).chr(10),FILE_APPEND);

$returns = post_request($url,$params);
// var_dump($returns);exit;
if(!empty($returns)){
	$rtu = json_decode($returns,'true');
	$str_tmp2 = $str_tmp."д".$out_trade_no;

	//返回json数组及对应状态码
	if($rtu["status"]=="100"){
		$arrs = array(
			"status"	=>	1000,
			"msg"		=>	"ok",
			"ptype"		=>	$ptype,
			"orders"	=>	$out_trade_no,
			"info"		=>	base_decode(encrypt($str_tmp2)),		//订单信息，用于查询订单接口
			"params"	=>	$rtu["params"],
			"pay_info"	=>	$rtu["pay_info"],
			"token_id"	=>	$rtu["token_id"],
			"services"	=>	$rtu["services"],
		);

		$arrs = json_encode($arrs);

		//插入redis订单表
		$GLOBALS["redis"]->sadd("orders",$str_tmp2);	

		//入库订单日志表
		$data = array(
			"ol_orderid"		=>	$out_trade_no,
			"ol_uaid"			=>	$uaid,
			"ol_uwid"			=>	$uwid,
			"ol_uid"			=>	$uid,
			"ol_gid"			=>	$gid,
			"ol_sid"			=>	$sid,
			"ol_rid"			=>	$roleid,
			"ol_goodsid"		=>	$goodsid,
			"ol_rname"			=>	$rolename,
			"ol_money"			=>	($money/100),
			"ol_paytime"		=>	$uptime,
			"ol_goodsnum"		=>	$nums,
			"ol_payway"			=>	$ptype,
			"ol_paytype"		=>	$wap,
			"ol_paydate"		=>	date("Ymd",$uptime),
			"ol_giveresult"		=>	0,
			"ol_gameorder"		=>	$gameorder,
		);
		$res = add_record($GLOBALS["count"], "orderform_log", $data);
		if ($res['rows'] < 1) {
			//记录本地文件
			$fname = WEBPATH_DIR."pay/cache/".date("Ymd")."-order_log_error.txt";
			file_put_contents($fname,"插入数据：".serialize($data),FILE_APPEND);
		}
		//如果为wap微信支付
		if($wap==2 || $wap==3){
			header("Location: ".$rtu["pay_info"]);
			exit; 
		}
	}else{
		$arrs  = json_encode(array("status"=>1003,"msg"=>"error params!"));
	}
}else{
	$arrs  = json_encode(array("status"=>1004,"msg"=>"system error!"));
}

exit($arrs);

?>
