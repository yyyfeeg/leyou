<?php
/* * ************
  @用户充值成功时上报
  @CopyRight teamtop
  @file:pay_log.php
  @author jericho
  @2017-07-15
  @alter 2016.03.02 tang
 * ************* */
include_once "../config.inc.php";
$ts     = get_param("ts","int");    // 充值时间
$gid    = get_param("gid","int");   // 游戏ID
$uaid   = get_param("uaid","int") ? get_param("uaid","int") : 0; // 渠道ID
$uwid   = get_param("uwid","int") ? get_param("uwid","int") : 0; // 子渠道ID
$sid    = get_param("sid","int");  // 游戏服ID
$uid    = get_param("uid");        // 用户ID
$uname  = get_param("uname");      // 账号
$uoid   = get_param("uoid");       // 第三方订单号
$money  = get_param("money");      // 充值金额(元)
$nums   = get_param("nums","int"); // 游戏币数量
$mac    = get_param("mac");        // MAC
$dnum   = get_param("dnumber");    // 移动设备唯一标识  IOS为IDFA  
$ip     = get_param("ip");         // IP地址
$types  = get_param("types");      // 账号状态 1：乐游账号  2:第三方渠道账号
$sign   = get_param("sign");       // 加密串
$rid    = get_param("roleid");     // 付费角色ID
$rname  = get_param("rolename");   // 付费角色名
$plv    = get_param("lv");         // 付费等级
$vender = get_param("vender");     // 厂商
$dmodel = get_param("dmodel");     // 设备型号
$uptime = time();                  // 上报时间

$my_sign = md5($ts.$gid.$uaid.$uwid.$sid.$uid.$uoid.$GLOBALS["CONF_GAME_KEY"][$gid]);

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
$str_tmp = $ts."дк".$gid."дк".$uaid."дк".$uwid."дк".$sid."дк".$uid."дк".$uname."дк".$uoid."дк".$money."дк".$nums."дк".$mac."дк".$dnum."дк".$rid."дк".$rname."дк".$ip."дк".$uptime."дк".$types."дк".$plv."дк".$vender."дк".$dmodel; 
$date    = date("Ymd",$ts);  // 以上报时日期为准

if ( $my_sign != $sign ) {
  // 验证失败
  echo "4001";
} else {
  $keys   = "{$gid}дк{$uid}дк{$date}дк{$sid}дк{$rid}дк{$uaid}дк{$uwid}дк{$uoid}";
  $result = $GLOBALS["redis"]->sismember("{$date}-pay_tmp",$keys);

  if(!$result){
      //存入充值key
      $GLOBALS["redis"]->sadd("pay_log",$str_tmp);
      $GLOBALS["redis"]->sadd("{$date}-pay_tmp",$keys);  //充值信息去重
  }
  echo "4000";
}
?>