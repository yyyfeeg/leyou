<?PHP
#================================================================
# 	FileName: updates.php
# 		Desc: 平台统计功能更新控制器
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.26
# LastChange: 
#================================================================

include_once("./config.inc.php");
$logname 		 = 	$_SESSION['admin'];      //管理员登录名
$admin_id 		 = 	$_SESSION['admin_id'];   //管理员ID
$done_ip 		 = 	return_user_ip();        //管理员IP
$date1			 = 	get_param("date1");		
$date2			 = 	get_param("date2");
$forceupdate 	 = 	intval(get_param("forceupdate"))!=2 ? 1 : 2;	//是否强制更新
$forceupdateinfo = 	$forceupdate==2 ? '强制更新了' : '普通更新了';
// $upurl			 =  WEBPATH_DIR."update/";	//系统函数更新模式
$upurl			 =  WEBPATH_DIR_INC."update/";	//url更新模式
$updatephp 		 =  get_param("updatephp");	 //更新数据的php文件名


//至少有一天日期
if(empty($date1)){
	exit("<script>alert('date error!');history.go(-1);</script>");
}

if(empty($date2) || $date2=='undefined'){
	$date2 = $date1;
}

if(empty($updatephp)){
	exit("<script>alert('url error!');history.go(-1);</script>");
}

$upurl  .= $updatephp.".php";
//更新文件是否存在 -- 仅在系统函数更新模式下使用
// if(!is_file($upurl)){
// exit("<script>alert('invalid path!');history.go(-1);</script>");
// }

//判定日期
if(!empty($date2)){
	if($date2==$date1){
		$diff = 0;
	}else{
		$diff = change_format($date2,$date1,'d');
		if($diff>31){
			exit("<script>alert('更新日期不能超过31天!');history.go(-1);</script>");
		}elseif($diff<0){
			exit("<script>alert('结束时间必须大于开始时间!');history.go(-1);</script>");
		}
	}
}


//判断账号权限
if($_SESSION["isadmin"]!=1 && $diff>1){
	exit("<script>alert('permission error!');history.go(-1);</script>");
}

//循环更新
if($diff>0){
	for($i=0;$i<($diff+1);$i++){
		$dts = date("Ymd",strtotime($date1)+86400*$i);
		curl($upurl.'?date='.$dts.'&forceupdate='.$forceupdate);
		// 仅在系统函数更新模式下使用
		// $str = $upurl." ".$dts."_".$forceupdate;
		// exec($upurl." ".$str);
		sleep(1);
	}
}else{
	$dts = date("Ymd",strtotime($date1));
	curl($upurl.'?date='.$dts.'&forceupdate='.$forceupdate);
	// 仅在系统函数更新模式下使用
	// $str = $upurl." ".$dts."_".$forceupdate;
	// exec($upurl." ".$str);
}
exit("<script>alert('已加入更新进程，请稍后查看数据!');history.go(-1);</script>");
?>
