<?php
/* * ************
  短信接口回调
  @验证码、营销内容等
  @CopyRight LY
  @file:msg_return.php
  @author jericho
  @2017-09-13
 * ************* */
include_once "../config.inc.php";
$act 	  = get_param("act");		//请求类型  1：状态报告
$reports  = get_param("reports");	//回调信息

if(empty($act) || empty($reports)){
	echo '<html>
<head><title>404 Not Found</title></head>
<body bgcolor="white">
<center><h1>404 Not Found</h1></center>
<hr><center>nginx</center>
</body>
</html>';
	exit;
}

$reports = json_decode($reports,"true");
if(!empty($reports)){
	foreach($reports as $val){
		//过滤发送失败信息
		if(empty($val["status"])){continue;}
		$status = ($val["status"]!='DELIVRD')?4:3;
		//更新验证码状态
		$wheres = array(
			"at_status" => 1,
			"at_tel"	=> $val["phone"],
			"at_batchno"=> $val["batchno"],
		);
		$data = array(
			"at_status"   => $status,
			"at_attime"	  => $val["recvtime"],
		); 
		update_record($GLOBALS["base"],'attest_tel',$data,$wheres);
		unset($wheres);
		unset($data);
	}
	echo 'ok';
	exit;
}
?>
