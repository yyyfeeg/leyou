<?php
ini_set('default_charset', 'utf-8');
include_once('aop/AopClient.php');  
include_once('aop/request/AlipayTradeWapPayRequest.php');  
include_once('../../configs/config.php');

//实例化
$aop   = new AopClient;
$cfg   = new Config();
$pars  = $_POST;
if(empty($pars)){
	header("Location: "."../index.php");
	exit;
}

//传递参数
$aop->gatewayUrl 			=  $cfg->pifg[2]["gatewayUrl"];
$aop->appId 				=  $cfg->pifg[2]["app_id"];
$aop->rsaPrivateKey 		=  $cfg->pifg[2]["merchant_private_key"];
$aop->format 				=  $cfg->pifg[2]["format"];
$aop->charset 				=  $cfg->pifg[2]["charset"];
$aop->signType 				=  $cfg->pifg[2]["sign_type"];
$aop->alipayrsaPublicKey 	=  $cfg->pifg[2]["alipay_public_key"];


$request = new AlipayTradeWapPayRequest();

//********注意*************************下面除了body描述不是必填，其他必须有，否则失败
$bizcontent = json_encode(array(
            'body'			  =>  $pars['body'],

            'subject' 		  =>  $pars['subject'],

            'out_trade_no'    =>  $pars['out_trade_no'],//支付宝订单号必须是唯一

            'timeout_express' =>  "10m",//過期時間（分钟）

            'total_amount'    =>  $pars['total_amount'],//金額最好能要保留小数点后两位数

            'product_code' 	  =>  'QUICK_WAP_WAY',

            'passback_params' =>  $pars['passback_params'], //	公用回传参数，如果请求时传递了该参数，则返回给商户时会回传该参数。支付宝会在异步通知时将该参数原样返回。本参数必须进行UrlEncode之后才可以发送给支付宝
        ));

$request->setNotifyUrl($pars['notify_url']);//异步回调地址

$request->setReturnUrl($pars['return_url']);//同步回调地址

$request->setBizContent($bizcontent);

//wap使用的是pageExecute
$response = $aop->pageExecute($request,"GET");

$arr  = array("status"=>100,'pay_info'=>$response,'token_id'=>"",'services'=>"",'params'=>"");
echo json_encode($arr);
exit();