<?php
/* * ************
  @用户创建角色时上报
  @CopyRight teamtop
  @file:create_role.php
  @author jericho
  @2017-07-15
 * ************* */
include_once "../config.inc.php";
$ts     = get_param("ts","int");  // 创角时间
$gid    = get_param("gid","int"); // 游戏ID
$uaid   = get_param("uaid","int") ? get_param("uaid","int") : 0; // 渠道ID
$uwid   = get_param("uwid","int") ? get_param("uwid","int") : 0; // 子渠道ID
$sid    = get_param("sid","int"); // 游戏服ID
$uid    = get_param("uid");       // 用户ID
$uname  = get_param("uname");     // 账号
$mac    = get_param("mac");       // MAC
$dnum   = get_param("dnumber");   // 移动设备唯一标识  IOS为IDFA  
$ip     = get_param("ip");        // IP地址
$roleid = get_param("roleid");    // 角色ID
$rname  = get_param("rolename");  // 角色名
$types  = get_param("types");     // 账号状态 1：天拓账号  2:第三方渠道账号
$sign   = get_param("sign");      // 加密串
$vender = get_param("vender");    // 厂商
$dmodel = get_param("dmodel");    // 设备型号
$uptime = time();                 // 上报时间

$my_sign = md5($ts.$gid.$uaid.$uwid.$sid.$uid.$GLOBALS["CONF_GAME_KEY"][$gid]);

$uname  = urldecode($uname);
$rname  = urldecode($rname);

// IP地址过滤
if (empty($ip)) {
  $ip = get_user_ip();
} else {
  $ip_arr = @explode('.', $ip);
  $count_ip = @array_sum($ip_arr);
  if ($count_ip <= 0) {
      $ip = get_user_ip();
  }
}

// 上报时间戳过滤
if (empty($ts)) {
  $ts = time();
} else {
  if (date('Ymd',$ts) > date('Ymd',time())) {
    $ts = time();
  }
}


// 拼接字串
$str_tmp = $ts."дк".$gid."дк".$uaid."дк".$uwid."дк".$sid."дк".$uid."дк".$uname."дк".$mac."дк".$dnum."дк".$ip."дк".$uptime."дк".$rname."дк".$roleid."дк".$types."дк".$vender."дк".$dmodel; 

if ( $my_sign != $sign ) {
	  // 验证失败
    echo "1001";
} else {
    //入redis
    $GLOBALS["redis"]->sadd("create_role",$str_tmp);
    echo "1000";
}
?>
