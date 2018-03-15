<?php
#========================================================
# 	FileName: clear_test_data.php
# 		Desc: 计划任务执行清除redis测试数据
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.08.25
# LastChange:
#========================================================

// 包含总配置文件
include_once(dirname(__FILE__).'/../config.inc.php');
$date = date("Ymd",strtotime("-2 day"));

// 获取测试数据条数
// $test_gameopen_num = $GLOBALS['redis']->sCard('gameopen_test');

// $test_gamelogin_num = $GLOBALS['redis']->sCard('gamelogin_test');

// $test_create_role_num = $GLOBALS['redis']->sCard('create_role_test');

// $test_pay_log_num = $GLOBALS['redis']->sCard('pay_log_test');

//清除缓存redis表

$GLOBALS['redis']->delete('{$date}-login_tmp_test');

$GLOBALS['redis']->delete('{$date}-login_time_test');

$GLOBALS['redis']->delete('{$date}-login_nums_test');

$GLOBALS['redis']->delete('{$date}-open_tmp_test');

$GLOBALS['redis']->delete('{$date}-pay_tmp_test');


$GLOBALS['redis']->delete('{$date}-login_tmp');

$GLOBALS['redis']->delete('{$date}-login_time');

$GLOBALS['redis']->delete('{$date}-login_nums');

$GLOBALS['redis']->delete('{$date}-open_tmp');

$GLOBALS['redis']->delete('{$date}-pay_tmp');

exit;
?>