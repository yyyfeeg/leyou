<?php /* Smarty version 2.6.20, created on 2017-12-28 10:55:50
         compiled from ../pay/jinzhu/index.html */ ?>
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
</head>
<body>
	<div id="wrap">
		<div class="zhe">
			<div class="top_icon" style="position: absolute;z-index: 9;left: 97.5px;"><img style="width: 100%;height: 100%;" src="http://127.0.0.1/game_counttemplates/img/zhifu.png" /></div>
			<div class="popup">
				<div class="popup_top" style="position: relative;">
					<p>确认购买</br><a style="font-size: 20px;">¥&nbsp;<span style="color: red;">650</span></a></p>
				</div>
				<div class="popup_body">
					<a href="send.php?payid=29"><div class="button" style="background-image: url(http://127.0.0.1/game_counttemplates/img/zhong.png);" style="position: relative;"><img style="height: 28px;width: 32px;position: absolute;left: 80px;bottom: 149px;" src="http://127.0.0.1/game_counttemplates/img/wei.png" /><span style="font-size: 18px;position: absolute;bottom: 150px;left: 123px;color: #FFFFFF;font-weight: 200;">&nbsp;微&nbsp;&nbsp;信</span></div></a>
					<a href="send.php?payid=26"><div class="button" style="background-image: url(http://127.0.0.1/game_counttemplates/img/zhong.png);" style="position: relative;"><img style="height: 30px;width: 30px;position: absolute;left: 80px;bottom: 88px;" src="http://127.0.0.1/game_counttemplates/img/zhi.png" /><span style="font-size: 18px;position: absolute;bottom: 90px;left: 123px;color: #FFFFFF;font-weight: 200;">支付宝</span></div></a>
					<a onclick="CloseDiv('wrap')" href="#"><div class="button" style="background-image: url(http://127.0.0.1/game_counttemplates/img/cancel.png);" style="position: relative;"><span style="font-size: 18px;position: absolute;bottom: 33px;left: 108px;color: #FFFFFF;font-weight: 200;">取&nbsp;消</span></div></a>
				</div>
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