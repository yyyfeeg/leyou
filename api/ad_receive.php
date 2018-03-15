<?php
/**
* 广告监控--接收
*
* 头条     www.hlwy.com/api/ad_receive.php?adid=__AID__&cid=__CID__&timestamp=__TS__&os=__OS__&callback_url=__CALLBACK_URL__&ch={广告系统ID}&gid={游戏ID}&tp=1&idfa=__IDFA__&imei=__IMEI__ (imei/idfa看具体渠道为安卓/IOS)
*
*
**/
include_once "../config.inc.php";

$idfa   				=  get_param("idfa");
$imei   				=  get_param("imei");
$ts     				=  get_param("timestamp")?THIS_DATETIME:get_param("timestamp");
$ck_url 				=  get_param("callback_url");
$param["tp"]     		=  get_param("tp"); 
$param['ciw_ch'] 		=  get_param("ch");  	 //渠道参数
$param['ciw_gid'] 		=  get_param('gid');     //游戏id
$param['ciw_aid'] 		=  get_param('aid');     //aid
$param['ciw_cid'] 		=  get_param('cid');     //cid
$param['ciw_os']		=  get_param("os");		 //系统
$param["ciw_date"] 		=  date("Ymd",$ts);
$param['ciw_callback'] 	=  $ck_url;
switch ($param["tp"]) {
	//今日头条
	case 1:
		$param['ciw_dev'] = (empty($idfa))?$imei:$idfa;
        $content = "";
		break;
}

//判断参数：
if (empty($param['ciw_dev']) || empty($param['ciw_ch']) || empty($ck_url)) {
    exit("400");
}

//查询是否存在:
$condition  =  "";
foreach($param as $k=>$v){
	$condition .=" and $k='" . $v . "'";
}
$sql =  "select sysid from ".get_table("integral_wall")." where 1 $condition";
$isExist = $GLOBALS["conn"]->getOne($GLOBALS["conn"]->query($sql));
if (empty($isExist)) {
	$param["ciw_time"] = $ts;
    add_record($GLOBALS["conn"], 'integral_wall', $param);
}

exit("200");
?>
