<?php
ini_set('default_charset', 'utf-8');
include_once('aop/AopClient.php');   
include_once('../../configs/config.php');


//实例化
$cfg   = new Config();
$pars  = $_POST;
if(empty($pars)){
	header("Location: "."../index.php");
	exit;
}

$aop = new AopClient;
$aop->alipayrsaPublicKey = $cfg->pifg[2]["alipay_public_key"];;
$flag = $aop->rsaCheckV1($pars, NULL, "RSA2");
if($flag){
    echo 'success';//这个必须返回给支付宝，响应个支付宝，
} else {
    //验证失败
    echo "fail";
}
exit();