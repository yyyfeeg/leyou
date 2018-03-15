<?php
#============================================
# 	FileName: index.php
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.12
# LastChange: 
#============================================

// 包含总配置文件
include_once('config.inc.php');

$haha = $_GET["test"];
if($haha=="123"){
#$url   = "http://www.hlwy.com/jz_pay/notify.php";
#$fname = WEBPATH_DIR."cache/aa.txt";	
#$arrs  = file($fname);
#$haha  = unserialize($arrs[0]);
#$return = post_request($url,$haha);
#var_dump($return);
# exit;
}


echo '<html>
<head><title>404 Not Found</title></head>
<body bgcolor="white">
<center><h1>404 Not Found</h1></center>
<hr><center>nginx</center>
</body>
</html>';
exit;

?>
