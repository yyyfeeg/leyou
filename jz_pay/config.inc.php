	<?php
#============================================
# 	FileName: config.inc.php
# 		Desc: 总配置文件
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.12
# LastChange: 
#============================================

// 基础设置
session_start();
// error_reporting(0);
ini_set('default_socket_timeout', -1);
error_reporting(E_ALL & ~E_NOTICE);
ini_set('default_charset', 'utf-8');
date_default_timezone_set('Asia/Shanghai'); 
define("WEBPATH_NW","http://10.135.184.90/jz_pay/");//内网路径
 
// 定义常量
define('WEBPATH_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR); //系统整站文件路径
define('WEBPATH_DIR_INC', 'http://' . $_SERVER['HTTP_HOST'].'/jz_pay/'); //整站网页路径
define('THIS_DATETIME', time()); //系统中插入数据库的日期格式 

// 包含必须文件
include_once(WEBPATH_DIR.'../lyinclude/db.count.inc.php');//基础数据库配置文件
include_once(WEBPATH_DIR.'../lyinclude/function.base.inc.php');//公共函数文件
include_once(WEBPATH_DIR.'../lyinclude/function.data.inc.php');//数据函数文件
include_once(WEBPATH_DIR.'../class/Mysql.class.php');//数据库操作类文件
include_once(WEBPATH_DIR.'../class/File.class.php');//文件操作类文件

$_SESSION['webpath']  = WEBPATH_DIR_INC;

// 初始化基础操作对象
$GLOBALS['count']  = new Mysql();
$GLOBALS['file']   = new File();
$GLOBALS['redis']  = new redis();
$GLOBALS['redis']->connect("127.0.0.1",'6379');
// $GLOBALS['redis']->auth("myRedis");

//获取游戏信息
$GLOBALS['CONF_GAME']["9999"]['key'] = "123123";	//测试游戏ID
$sql   = "select sysid,gi_paykey,gi_repay_addr from view_game_info";
$query = $GLOBALS["count"]->query($sql);
while($rows = $GLOBALS["count"]->fetcharray($query)){
	if(empty($rows["gi_paykey"])) continue;
	$GLOBALS["CONF_GAME"][$rows["sysid"]]['key'] 	= $rows["gi_paykey"];
	$GLOBALS["CONF_GAME"][$rows["sysid"]]['notify'] = $rows["gi_repay_addr"];
} 
?>
