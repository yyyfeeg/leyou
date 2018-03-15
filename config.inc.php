	<?php
#============================================
# 	FileName: config.inc.php
# 		Desc: 总配置文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.03
# LastChange: 
#============================================

// 基础设置
session_start();
// error_reporting(0);
ini_set('default_socket_timeout', -1);
ini_set("session.cookie_httponly", 1); 
error_reporting(E_ALL & ~E_NOTICE);
ini_set('default_charset', 'utf-8');
date_default_timezone_set('Asia/Shanghai');

// 定义常量
define('WEBPATH_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR); //系统整站文件路径
define('CONTROLLERS_DIR', WEBPATH_DIR.'controllers'.DIRECTORY_SEPARATOR); //整站控制器路径
define('THIS_DATETIME', time()); //系统中插入数据库的日期格式

// 包含必须文件
@include_once(WEBPATH_DIR.'config.frontend_info.php');//前端基础信息配置文件
// include_once(WEBPATH_DIR.'configs/email.config.php');//邮箱信息配置文件
include_once(WEBPATH_DIR.'lyinclude/db.base.inc.php');//基础数据库配置文件
include_once(WEBPATH_DIR.'lyinclude/function.base.inc.php');//公共函数文件
include_once(WEBPATH_DIR.'lyinclude/function.data.inc.php');//数据函数文件
include_once(WEBPATH_DIR.'class/MySmarty.class.php');//smarty配置文件
include_once(WEBPATH_DIR.'class/Mysql.class.php');//数据库操作类文件
include_once(WEBPATH_DIR.'class/File.class.php');//文件操作类文件
include_once(WEBPATH_DIR.'class/Sms.class.php');//短信操作类文件
include_once(WEBPATH_DIR.'class/Mailer.class.php');//邮件操作类文件
include_once(CONTROLLERS_DIR.'controller.class.php');//控制器类文件

$_SESSION['webpath']  = WEBPATH_DIR_INC;

// 初始化基础操作对象
$GLOBALS['base']  = new Mysql();
$GLOBALS['sms']   = new SMS('lyhl168','776551');
$GLOBALS['mail']  = new PHPMailer(true);
$GLOBALS['file']  = new File();
#$GLOBALS['redis'] = new redis();
#$GLOBALS['redis']->connect("localhost",'6379');
// $GLOBALS['redis']->auth("myRedis");


//获取游戏key值
$GLOBALS['CONF_GAME_KEY']["9999"] = "123456";	//测试游戏ID
$sql   = "select sysid,gi_key from ".get_table("game_info");
$query = $GLOBALS["base"]->query($sql);
while($rows = $GLOBALS["base"]->fetcharray($query)){
	if(empty($rows["gi_key"])) continue;
	$GLOBALS["CONF_GAME_KEY"][$rows["sysid"]] = $rows["gi_key"];
} 


// 查询前端信息表数据
$frontend_SQL  = "select * from ".get_table('frontend_info');
$frontend_DATA = $GLOBALS['base']->getOne($GLOBALS['base']->Query($frontend_SQL));
define("WEBPATH_DIR_INC",$frontend_DATA['fi_basehost']);
define("WEBNAME",$frontend_DATA['fi_webname']);
define("HTML_DIR",$frontend_DATA['fi_arcdir']);
define("UPLOAD_DIR",$frontend_DATA['fi_upload_dir']);
define("KEYWORDS",$frontend_DATA['fi_keywords']);
define("DESC",$frontend_DATA['fi_desc']);
define("POWERBY",$frontend_DATA['fi_powerby']);
define("BEIAN",$frontend_DATA['fi_beian']);
define("CREATE_INDEX_URL",$frontend_DATA['fi_indexurl']);

unset($frontend_SQL);
unset($frontend_DATA);
?>
