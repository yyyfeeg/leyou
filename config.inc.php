<?PHP

session_start();
#--------------------------------------------------------------
#	文字 gb2312、utf-8
#--------------------------------------------------------------
//error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);
ini_set('default_charset', "utf-8");
date_default_timezone_set('Asia/Shanghai'); //定义时区
define('WEBPATH_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR); //整站系统路径
define('WEBPATH_DIR_INC', 'http://' . $_SERVER['HTTP_HOST'].'/game_count/admin.backend/'); //整站网页路径
define("CONTROLLERS_DIR", WEBPATH_DIR . "controllers" . DIRECTORY_SEPARATOR); //整站控制器路径
define('SERVERDB', WEBPATH_DIR . 'serverdb/');        //数据库配置文件
define("THIS_DATETIME", time()); //系统中用入插入数据库的日期时间格式

//引入图像功能
$IMG_UP = array(
    'image_upload_size' => 1000000, //图片大小 图片上传不能大于1M
    'image_mime' => array('image/png','image/jpg','image/gif','image/jpeg'),
    'text_upload_size' => 10000000, //文件大小 图片上传不能大于10M
);

include_once(WEBPATH_DIR . "lyinclude/db.config.inc.php");      //数据库配置信息

include_once(WEBPATH_DIR . "lyinclude/mysql.class.php");

include_once(WEBPATH_DIR . "lyinclude/function.inc.php");

include_once(WEBPATH_DIR . "lyinclude/function.right.php");

include_once(WEBPATH_DIR . "lyinclude/config.arr.inc.php");

include_once(WEBPATH_DIR . "class/data_file.class.php");    //文件操作类

include_once(CONTROLLERS_DIR . "game.smarty.class.php");      //smarty配置

include_once(CONTROLLERS_DIR . "control.class.php");          //载入基类文件

include_once(WEBPATH_DIR . "class/upload_image.class.php");   //上传图片类

$DB = new DB_ZDE(); //定义公共的数据库连接

$File = new data_file();

$payrange = $arr_config["payrange"];
/*
$GLOBALS['redis']= new redis();
$GLOBALS['redis']->connect("10.251.240.57",6379);
$GLOBALS['redis']->auth("@p^44ry1tt");
*/
?>
