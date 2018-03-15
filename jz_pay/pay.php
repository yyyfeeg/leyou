<?php
#============================================
# 	FileName: pay.php
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.12.26
# LastChange: 暂时取消 --- 12.27 
#============================================


// 包含总配置文件
include_once('./config.inc.php');
include_once('../configs/config.php');

//接收基础参数
$cfg        =   new Config();
$gameinfo	=   empty($GLOBALS["CONF_GAME"])?$cfg->ginfo:$GLOBALS["CONF_GAME"];
$date 		=	date("Ymd");
$goref      =   1;

if(empty($_SESSION["pars"])){
	$uid		=	get_param("uid");			//用户ID
	$gid 		=	get_param("gid");			//游戏ID
	$sid		=	get_param("sid");			//游戏服ID
	$uaid  	 	= 	get_param("uaid","int");	//渠道信息
	$uwid   	= 	get_param("uwid","int");	//子渠道
	$sign		=	get_param("sign");			//加密sign值
	$gameorder  =   get_param("gameorder");     //游戏订单号  
	$roleid 	=	get_param("roleid");		//角色ID
	$rolename   =   get_param("rolename");		//角色名
	$ptype		=	get_param("ptype");			//支付类型 1：微信   2：支付宝 
	$goodsid	=	get_param("goodsid");		//商品ID
	$goodsname  =   get_param("goodsname");		//商品名称
	$nums		=	get_param("nums");			//游戏币数量
	$money		=	get_param("money",'double');//金额,单位(分)
	$uptime 	= 	get_param("ts","int");		//充值时间  
	$ip 		=	get_user_ip(); 
}else{
	$uid		=	$_SESSION["pars"]["uid"];			//用户ID
	$gid 		=	$_SESSION["pars"]["gid"];			//游戏ID
	$sid		=	$_SESSION["pars"]["sid"];			//游戏服ID
	$uaid  	 	= 	$_SESSION["pars"]["uaid"];			//渠道信息
	$uwid   	= 	$_SESSION["pars"]["uwid"];			//子渠道
	$sign		=	$_SESSION["pars"]["sign"];			//加密sign值
	$gameorder  =   $_SESSION["pars"]["gameorder"];     //游戏订单号  
	$roleid 	=	$_SESSION["pars"]["roleid"];		//角色ID
	$rolename   =   $_SESSION["pars"]["rolename"];		//角色名
	$ptype		=	$_SESSION["pars"]["ptype"];			//支付类型 1：微信   2：支付宝 
	$goodsid	=	$_SESSION["pars"]["goodsid"];		//商品ID
	$goodsname  =   $_SESSION["pars"]["goodsname"];		//商品名称
	$nums		=	$_SESSION["pars"]["nums"];			//游戏币数量
	$money		=	$_SESSION["pars"]["money"];			//金额,单位(分)
	$uptime 	= 	$_SESSION["pars"]["ts"];			//充值时间  
	$ip 		=	$_SESSION["pars"]["ip"]; 
	$ref        =   $_SERVER['HTTP_REFERER'];

	//判断上级来源
	if(strstr($ref, WEBPATH_DIR_INC."handle.php")>0){
        $goref = 0;
    }

}

/********* 测试 **********/
// if(empty($money) || empty($ptype) || empty($uid) || empty($goref)){
// 	echo '<html>
// <head><title>404 Not Found</title></head>
// <body bgcolor="white">
// <center><h1>404 Not Found</h1></center>
// <hr><center>nginx</center>
// </body>
// </html>';
// exit;
// }
/********* 测试 **********/


//判断基础参数
if(empty($gid) || empty($uid) || empty($money)  || empty($sid) || empty($sign) || empty($uaid) || empty($uwid) || empty($roleid)  || empty($gameinfo[$gid])){
	exit("1001");
}


//对比加密信息
$PayID  	=	($ptype==1)?29:26;
$rolename	=	urldecode($rolename);
$goodsname	=	urldecode($goodsname);
$key		=	$gameinfo[$gid]['key'];
$mysign		=	md5($uptime.$gid.$uid.$sid.$uaid.$uwid.$roleid.$goodsid.$key);
if($mysign!=$sign){
	exit("1002");
}
unset($key);


if(empty($uptime)){
	$uptime = THIS_DATETIME;	
}


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
// 	exit("9999");
// }
$str_tmp2 = $str_tmp."д".$out_trade_no;


$params			=	array(
	"UserName"		=>		$uid,
	"Price"			=>		($money/100),
	"shouji"		=>		"",
	"PayID"			=>		$PayID,
	"userid"		=>		$cfg->conf["userid"],
	"wooolID"		=>		$cfg->conf["fqid"],
	"jinzhua"		=>		base_decode(encrypt($str_tmp2)),
);

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
	"ol_paytype"		=>	-1,			//金猪
	"ol_paydate"		=>	date("Ymd",$uptime),
	"ol_giveresult"		=>	0,
	"ol_gameorder"		=>	$gameorder,
);
add_record($GLOBALS["count"], "orderform_log", $data);

foreach($params as $k=>$v){
	$str .= $k."=".$v."&";
}
$str = substr($str,0,-1);
header("Location: ".($cfg->conf["payurl"]."?".$str));
?>
