<?php
#============================================
# 	FileName: handle.php
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.12.25
# LastChange: 支付界面
#============================================

// 包含总配置文件
include_once('config.inc.php');
include_once('config/config.php');

$cfg        =   new Config();
$url 		=	WEBPATH_DIR_INC."pay.php";
$uid		=	get_param("uid")?get_param("uid"):rand(1,1000000);					//用户ID
$sid		=	get_param("sid")?get_param("sid"):rand(1,1000000);					//游戏服ID
$money		=	get_param("money",'double')?get_param("money",'double'):"100";		//金额,单位(分)
$money2		=	sprintf("%.2f",$money/100);											//页面显示金额(元)
$ip 		=	get_user_ip(); 
$again		=	get_param("again");
$ptype		=	get_param("ptype");

if(empty($ptype)){
	//存入session值
	$_SESSION["pars"]				=   $_POST;	 //首次进入页面，则记录数据至session			

	$_SESSION["pars"]["money"] 		=   $money;			//测试
	$_SESSION["pars"]["uid"] 		=   $uid;			//测试
}else{
	$_SESSION["pars"]["ptype"] = $ptype;
	$_SESSION["pars"]["ip"]	   = $ip;
	header("Location: ".$url);
	exit; 
}

// if(empty($_GET)){
// 	echo '<html>
// 	<head><title>404 Not Found</title></head>
// 	<body bgcolor="white">
// 	<center><h1>404 Not Found</h1></center>
// 	<hr><center>nginx</center>
// 	</body>
// 	</html>';
// 	exit;
// }

?>

<!DOCTYPE html>
<html lang="en">
<head> 
	<title>支付界面</title> 
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">  
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>   
		(function (doc, win) {
		        var docEl = doc.documentElement,
		            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
		            recalc = function () {
		                var clientWidth = docEl.clientWidth;
		                if (!clientWidth) return;
		                if(clientWidth>=640){
		                    docEl.style.fontSize = '100px';
		                }else{
		                    docEl.style.fontSize = 100 * (clientWidth / 640) + 'px';
		                }
		            };
		
		        if (!doc.addEventListener) return;
		        win.addEventListener(resizeEvt, recalc, false);
		        doc.addEventListener('DOMContentLoaded', recalc, false);
		    })(document, window);
		</script> 
		<script>
			function closewin(){
			     var userAgent = navigator.userAgent;
		        if (userAgent.indexOf("Firefox") != -1 || userAgent.indexOf("Chrome") !=-1) {
		            window.location.href="about:blank";
		        } else {
		            window.opener = null;
		            window.open("", "_self");
		             window.close();
		        }
			}

		</script>
</head>
<body>
	<div id="wrap">
		<div class="zhe">
			<form name="form1" method="post" action="./handle.php">
			<div class="top_icon" style="position: absolute;z-index: 9;left: 97.5px;"><img style="width: 100%;height: 100%;" src="img/zhifu.png" /></div>
			<div class="popup">
				<div class="popup_top" style="position: relative;">
					<p>确认购买</br><a style="font-size: 20px;">¥&nbsp;<span style="color: red;"><?php echo $money2;?></span></a></p>
				</div>
				<div class="popup_body">
					<a href="./handle.php?ptype=1"><div class="button" style="background-image: url(img/zhong.png);" style="position: relative;"><img style="height: 28px;width: 32px;position: absolute;left: 80px;bottom: 149px;" src="img/wei.png" /><span style="font-size: 18px;position: absolute;bottom: 150px;left: 123px;color: #FFFFFF;font-weight: 200;">&nbsp;微&nbsp;&nbsp;信</span></div></a>
					<a href="./handle.php?ptype=2"><div class="button" style="background-image: url(img/zhong.png);" style="position: relative;"><img style="height: 30px;width: 30px;position: absolute;left: 80px;bottom: 88px;" src="img/zhi.png" /><span style="font-size: 18px;position: absolute;bottom: 90px;left: 123px;color: #FFFFFF;font-weight: 200;">支付宝</span></div></a>
					<a href="" onclick="closewin()"><div class="button" style="background-image: url(img/cancel.png);" style="position: relative;"><span style="font-size: 18px;position: absolute;bottom: 33px;left: 108px;color: #FFFFFF;font-weight: 200;">取&nbsp;消</span></div></a>
				</div>
			</form>
			</div>
		</div>
	</div>
	<script>
	total = document.documentElement.clientHeight;
	document.getElementById("wrap").style.height=total+"px";
	</script>
</body>
</html>
