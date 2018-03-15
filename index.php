<?php
#============================================
# 	FileName: index.php
# 		Desc: 整站入口文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.21
# LastChange: 
#============================================

// 包含总配置文件
include_once('./config.inc.php');

// 获取URL参数
$folders = (get_param('f')) ? (get_param('f')) : '';// 控制器文件夹
$module  = (get_param('mo')) ? (get_param('mo')) : 'index';// 控制器文件名
$method  = (get_param('me')) ? (get_param('me')): 'show_index';// 控制器中方法名

// 参数判断
if (empty($module) || empty($method)) exit('Parameter Wrong!');

// 加载控制器，并实例化对象
$object = load_controller($module, $folders);

// 检查类是否存在该方法
if (!method_exists($object, $method)) exit('Method ( '.$method.' ) Is Not Found!');

// 返回方法执行的结果
call_user_func_array(array($object, $method), array());

?>


