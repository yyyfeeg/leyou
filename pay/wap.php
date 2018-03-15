<?php
#============================================
# 	FileName: wap.php
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.12.28
# LastChange: 支付界面		
#============================================

// 包含总配置文件
ini_set('default_charset', 'utf-8');
include_once('config.inc.php');
include_once('../configs/config.php');

$cfg        =   new Config();
$ginfo 		=   empty($GLOBALS["CONF_GAME"])?$cfg->ginfo:$GLOBALS["CONF_GAME"];
$money      =   get_param("money");	// 金额,单位(分)
$money2		=	sprintf("%.2f",$money/100);
if(empty($_POST)){
	echo '<html>
		<head><title>404 Not Found</title></head>
		<body bgcolor="white">
		<center><h1>404 Not Found</h1></center>
		<hr><center>nginx</center>
		</body>
		</html>';
	exit;
}

$pars  =  $_POST;
if(empty($pars["ip"])){
	$pars["ip"]   =  get_user_ip();
}

$jiami 		= 	base_decode(encrypt(serialize($pars)));

$uid		=	$pars["uid"];			//用户ID
$gid 		=	$pars["gid"];			//游戏ID
$sid		=	$pars["sid"];			//游戏服ID
$uaid  	 	= 	$pars["uaid"];			//渠道信息
$uwid   	= 	$pars["uwid"];			//子渠道
$sign		=	$pars["sign"];			//加密sign值 
$roleid 	=	$pars["roleid"];		//角色ID
$goodsid	=	$pars["goodsid"];		//商品ID
$uptime 	= 	$pars["ts"];			//充值时间  


//判断参数
if(empty($gid) || empty($uid) || empty($money)  || empty($sid) || empty($sign) || empty($roleid)  || empty($ginfo[$gid])){
	exit("1001");
}

//对比加密信息
$key		=	$ginfo[$gid]['key'];
$mysign		=	md5($uptime.$gid.$uid.$sid.$uaid.$uwid.$roleid.$goodsid.$key);
if($mysign!=$sign){
	exit("1002");
}
unset($key);


/**************** 

金猪微信支付：
<a href="http://www.hlwy.com/jz_pay/handle2.php?ptype=1&par=<?php echo $jiami;?>"><div class="button" style="background-image: url(<?php echo WEBPATH_DIR_INC;?>img/zhong.png);" style="position: relative;"><img style="height: 28px;width: 32px;position: absolute;left: 80px;bottom: 149px;" src="<?php echo WEBPATH_DIR_INC;?>img/wei.png" /><span style="font-size: 18px;position: absolute;bottom: 150px;left: 123px;color: #FFFFFF;font-weight: 200;">&nbsp;<img src="<?php echo WEBPATH_DIR_INC;?>img/weifont.png" /></span></div></a>


中信支付：
<a href="<?php echo WEBPATH_DIR_INC;?>pay.php?ptype=1&wap=2&par=<?php echo $jiami;?>"><div class="button" style="background-image: url(<?php echo WEBPATH_DIR_INC;?>img/zhong.png);" style="position: relative;"><img style="height: 28px;width: 32px;position: absolute;left: 80px;bottom: 149px;" src="<?php echo WEBPATH_DIR_INC;?>img/wei.png" /><span style="font-size: 18px;position: absolute;bottom: 150px;left: 123px;color: #FFFFFF;font-weight: 200;">&nbsp;<img src="<?php echo WEBPATH_DIR_INC;?>img/weifont.png" /></span></div></a>


*********/

?>

<!DOCTYPE html>
<html lang="en">
<head> 
	<title>支付界面</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">  
    <link rel="stylesheet" type="text/css" href="<?php echo WEBPATH_DIR_INC;?>css/style.css">
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
			     // var userAgent = navigator.userAgent;
		      //   if (userAgent.indexOf("Firefox") != -1 || userAgent.indexOf("Chrome") !=-1) {
		      //       window.location.href="about:blank";
		      //   } else {
		      //       window.opener = null;
		      //       window.open("", "_self");
		      //        window.close();
		      //   }
		      payPage.close();
			}

		</script>
</head>
<body>
	<div id="wrap">
		<div class="zhe">
			<form name="form1" method="post" action="<?php echo WEBPATH_DIR_INC;?>pay.php">
			<div class="top_icon" style="position: absolute;z-index: 9;left: 97.5px;"><img style="width: 100%;height: 100%;" src="<?php echo WEBPATH_DIR_INC;?>img/zhifu.png" /></div>
			<div class="popup">
				<div class="popup_top" style="position: relative;">
					<p><img src="<?php echo WEBPATH_DIR_INC;?>img/payfont.png" /></br><a style="font-size: 20px;">¥&nbsp;<span style="color: red;"><?php echo $money2;?></span></a></p>
				</div>
				<div class="popup_body">

					<a href="<?php echo WEBPATH_DIR_INC;?>pay.php?ptype=1&wap=2&par=<?php echo $jiami;?>"><div class="button" style="background-image: url(<?php echo WEBPATH_DIR_INC;?>img/zhong.png);" style="position: relative;"><img style="height: 28px;width: 32px;position: absolute;left: 80px;bottom: 149px;" src="<?php echo WEBPATH_DIR_INC;?>img/wei.png" /><span style="font-size: 18px;position: absolute;bottom: 150px;left: 123px;color: #FFFFFF;font-weight: 200;">&nbsp;<img src="<?php echo WEBPATH_DIR_INC;?>img/weifont.png" /></span></div></a>

					<a href="<?php echo WEBPATH_DIR_INC;?>pay.php?ptype=2&wap=3&par=<?php echo $jiami;?>"><div class="button" style="background-image: url(<?php echo WEBPATH_DIR_INC;?>img/zhong.png);" style="position: relative;"><img style="height: 30px;width: 30px;position: absolute;left: 80px;bottom: 88px;" src="<?php echo WEBPATH_DIR_INC;?>img/zhi.png" /><span style="font-size: 18px;position: absolute;bottom: 90px;left: 123px;color: #FFFFFF;font-weight: 200;"><img src="<?php echo WEBPATH_DIR_INC;?>img/zhifont.png" /></span></div></a>
					<a href="" onclick="closewin()"><div class="button" style="background-image: url(<?php echo WEBPATH_DIR_INC;?>img/cancel.png);" style="position: relative;"><span style="font-size: 18px;position: absolute;bottom: 33px;left: 108px;color: #FFFFFF;font-weight: 200;"><img src="<?php echo WEBPATH_DIR_INC;?>img/canfont.png" /></span></div></a>
				</div>
			</form>
			</div>
		</div>
	</div>
	<script>
	total = document.documentElement.clientHeight;
	document.getElementById("wrap").style.height=total+"px";
	
	function CloseDiv(wrap)
	{
	document.getElementById(wrap).style.display='none';
	};
	</script>
</body>
</html>
