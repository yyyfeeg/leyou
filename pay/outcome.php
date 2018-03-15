<?php
#============================================
# 	FileName: outcome.php  
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2018.01.08
# LastChange: 支付宝同步页面
#============================================

// 包含总配置文件
include_once('config.inc.php');


$result    =  2;		//默认成功
$seller_id = get_param("seller_id");
$trade_no  = get_param("trade_no");
if(strlen($seller_id)<>16 && empty($trade_no)){
	echo '<html>
	<head><title>404 Not Found</title></head>
	<body bgcolor="white">
	<center><h1>404 Not Found</h1></center>
	<hr><center>nginx</center>
	</body>
	</html>';
	exit;
}
?>

<?php if($result == 2){?>
<!DOCTYPE html>
<html>
	<head>
		<title>支付成功</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">  
        <link rel="stylesheet" type="text/css" href="<?php echo WEBPATH_DIR_INC;?>css/style2.css">
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
		      payPage.close();
			}
	</script>
	</head>
	<body>
		<!-- 头部 -->
		<div class="head">
			<img src="<?php echo WEBPATH_DIR_INC;?>img/back.png" style="position: absolute;padding: 0.12rem;width: 0.5rem;" />
			<div style="width: 80%;"><p style="text-align: center;margin: 0;padding: 0;"><a style="font-size: 0.35rem;color:#FFFFFF;margin: 0;padding: 0;position: absolute;top: 0.15rem;">支付完成</a></p></div>
		</div>
		<!-- 图标 -->
		<div class="icon">
			<div class="icon_con"><img style="height: 100%;width: 100%;margin: 0;padding: 0;" src="<?php echo WEBPATH_DIR_INC;?>img/success.png" /></div>
		</div>
		<!-- 支付成功文字 -->
		<div class="font">
			<div class="font_top"><p style="text-align: center;margin: 0;padding: 0;font-size: .3rem;">您已支付成功</p></div>
			<div class="font_bottom"><img style="width: .5rem;height: .5rem;margin: 0;padding: 0;position: absolute;left: 46%;" src="<?php echo WEBPATH_DIR_INC;?>img/duigou.png" /></div>
		</div>
		<!-- 两个按钮 -->
		<div class="button">
			<a href="" onclick="closewin()"><div class="button1"><span>确认</span></div></a>
			<a href="" onclick="closewin()"><div class="button2"><span>返回</span></div></a>
		</div>
	</body>
</html>

<?php }?>