<?php
/* * ************
  @用户打开游戏时上报
  @CopyRight teamtop
  @file:gameopen.php
  @author jericho
  @2017-07-15
 * ************* */
include_once "../config.inc.php";
$ts     = get_param("ts","int");   // 打开时间
$gid    = get_param("gid","int");  // 游戏ID
$uaid   = get_param("uaid","int"); // 渠道ID
$uwid   = get_param("uwid","int"); // 子渠道ID
$mac    = get_param("mac");        // MAC
$dnum   = get_param("dnumber");    // 移动设备唯一标识  IOS为IDFA  
$ip     = get_param("ip");         // IP地址
$sign   = get_param("sign");       // 加密串
$vender = get_param("vender");    // 厂商
$dmodel = get_param("dmodel");    // 设备型号
$uptime = time();                  // 上报时间

$my_sign = md5($ts.$gid.$uaid.$uwid.$GLOBALS["CONF_GAME_KEY"][$gid]);

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
  if (date('Ymd',$ts) > date('Ymd',time()) || round((time()-$ts)/86400) > 1 ) {
    $ts = time();
  }
}

// 拼接字串
$str_tmp = $ts."дк".$gid."дк".$uaid."дк".$uwid."дк".$mac."дк".$dnum."дк".$ip."дк".$vender."дк".$dmodel;
$date    = date("Ymd",$ts);  // 以上报时日期为准 

if ( $my_sign != $sign ) {
	// 验证失败
    echo "3001";
} else {
    //判断是否有重复数据:
    $keys   = "{$gid}дк{$date}дк{$uaid}дк{$uwid}дк{$mac}дк{$dnum}";
    $result = $GLOBALS["redis"]->sismember("{$date}-open_tmp",$keys);

    //没有重复则入库 
    if(!$result){
      //存入登录key
      $GLOBALS["redis"]->sadd("gameopen",$str_tmp);
      $GLOBALS["redis"]->sadd("{$date}-open_tmp",$keys);  //打开游戏去重
    }
    echo "3000";
}
?>