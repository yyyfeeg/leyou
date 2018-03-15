<?php
#========================================================================================================
# 	FileName: floatUrl.api.php
# 		Desc: 游戏内悬浮窗获取三个网页地址接口文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.05.17
# LastChange: 
#    TestUrl: http://192.168.11.184/game_count/api/floatUrl.api.php?
#    		  sign=3729598cd99306e4e998a572403e4011&uid=35040912&uname=aa146830674182&gid=5&sid=1&uaid=13&uwid=14  35040912aa14683067418251314ttg@#fun^tzf&3737@teamtopgame
#========================================================================================================

// 包含总配置文件
include_once('../config.inc.php');

// 定义常量
define('FLOATURL_KEY','ttg@#fun^tzf&3737@teamtopgame');
define('PAGEURL_KEY','teamtop#$3737!tang@ttgfun.com');

// 接收参数
$sign  = get_param('sign');// 加密验证字串
$uid   = get_param('uid','int');// 用户ID
$uname = get_param('uname');// 用户名称
$gid   = get_param('gid','int');// 游戏ID
$uaid  = get_param('uaid','int');// 渠道ID
$uwid  = get_param('uwid','int');// 子渠道ID

// 检查参数是否正确
if (empty($uid) || empty($uname) || empty($gid) || empty($uaid) || empty($uwid)) {
	$res = array('code'=>'1002','msg'=>'param error','data'=>'');
	exit(json_encode($res));
}

// 检查sign是否合法
$my_sign = md5($uid.$uname.$gid.$uaid.$uwid.FLOATURL_KEY);
if ($sign != $my_sign) {
	$res = array('code'=>'1002','msg'=>'sign error','data'=>'');
	exit(json_encode($res));
}

// 返回三个链接地址
$user_url  = WEBPATH_DIR_INC.'/index.php?mo=g_users&me=index&v=';
$spree_url = WEBPATH_DIR_INC.'/index.php?mo=g_gifts&me=index&v=';
$help_url  = WEBPATH_DIR_INC.'/index.php?mo=g_kf&me=index&v=';
$sign      = md5($uid.$uname.$gid.$uaid.$uwid.PAGEURL_KEY);
$param_str = "sign=$sign&uid=$uid&uname=$uname&gid=$gid&uaid=$uaid&uwid=$uwid";
$param_v   = base64_encode(simple_xor($param_str,'ttgfun.com@3737^tang!'));
$urlArr    = array(
	'user_url'  => $user_url.$param_v,
	'spree_url' => $spree_url.$param_v,
	'help_url'  => $help_url.$param_v
	);
exit(json_encode($urlArr,JSON_HEX_AMP));
?>