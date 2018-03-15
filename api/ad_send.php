<?php
/**
* 广告监控--回调
*
**/
include_once "../config.inc.php";

$callback 					=  get_param("callback");  	
$param['ciw_os'] 			=  get_param('os');     
$param['ciw_dev'] 			=  get_param('dev');     
$param['ciw_event_type'] 	=  get_param('event_type');     
$param['ciw_conv_time'] 	=  get_param("conv_time");
$param['ciw_conv_date']		=  date("Ymd",$param["ciw_conv_time"]);
$param['ciw_tp'] 			=  get_param("tp");
$param['ciwid']				=  get_param("ciwid");
$param['ciw_gid']			=  get_param("gid");
$param['ciw_ch']			=  get_param("ch");
switch ($param['tp'] ) {
	//今日头条
	case 1:
		$url = urldecode($callback)."&event_type"=.$param["ciw_event_type"];
		break;
}

//查询是否存在:
$condition  =  "";
foreach($param as $k=>$v){
	$condition .=" and $k='" . $v . "'";
}
$sql =  "select 1 from ".get_table("integral_wall_log")." where 1 $condition";
$isExist = $GLOBALS["conn"]->getOne($GLOBALS["conn"]->query($sql));
if (empty($isExist)) {
	//写入广告log表
	add_record($GLOBALS["conn"], 'integral_wall_log', $param);
}
post_request($url,"","get");
exit("200");
?>
