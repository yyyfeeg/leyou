<?php
/* * ************
  @用户选择/创建角色后，登录进游戏内时上报
  @CopyRight teamtop
  @file:gamelogin.php
  @author jericho
  @2017-07-15
 * ************* */
include_once "../config.inc.php";
$ts      = get_param("ts","int");  // 登录时间
$gid     = get_param("gid","int"); // 游戏ID
$uaid    = get_param("uaid","int") ? get_param("uaid","int") : 0; // 渠道ID
$uwid    = get_param("uwid","int") ? get_param("uwid","int") : 0; // 子渠道ID
$sid     = get_param("sid","int"); // 游戏服ID
$uid     = get_param("uid");       // 用户ID
$uname   = get_param("uname");     // 账号
$mac     = get_param("mac");       // MAC
$dnum    = get_param("dnumber");   // 移动设备唯一标识  IOS为IDFA  
$ip      = get_param("ip");        // IP地址
$sign    = get_param("sign");      // 加密串
$types   = get_param("types");     // 账号状态 1：天拓账号  2:第三方渠道账号
$roleid  = get_param("roleid");    // 角色ID
$rname   = get_param("rolename");  // 角色名
$vender  = get_param("vender");    // 厂商
$dmodel  = get_param("dmodel");    // 设备型号
$uptime  = time();                 // 上报时间
$date    = date('Ymd',time());     // 当天日期
$my_sign = md5($ts.$gid.$uaid.$uwid.$sid.$uid.$roleid.$GLOBALS["CONF_GAME_KEY"][$gid]);
$uname   = urldecode($uname);


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
$str_tmp = $ts."дк".$gid."дк".$uaid."дк".$uwid."дк".$sid."дк".$uid."дк".$uname."дк".$mac."дк".$dnum."дк".$ip."дк".$uptime."дк".$types."дк".$roleid."дк".$rname."дк".$vender."дк".$dmodel; 
$date    = date("Ymd",$ts);  // 以上报时日期为准

if ( $my_sign != $sign ) {
	// 验证失败
    echo "2001";
} else {
    //判断是否有重复数据:
    $keys   = "{$gid}дк{$uid}дк{$date}дк{$sid}дк{$roleid}дк{$uaid}дк{$uwid}";
    $result = $GLOBALS["redis"]->sismember("{$date}-login_tmp",$keys);
    if(!$result){
      //存入登录key
      $GLOBALS["redis"]->sadd("gamelogin",$str_tmp);
      $GLOBALS["redis"]->sadd("{$date}-login_tmp",$keys);  //登录游戏去重
      $GLOBALS["redis"]->hset("{$date}-login_time",$keys,$ts); //最后登录时间
      $GLOBALS["redis"]->hset("{$date}-login_nums",$keys,1); //最后登录次数
    }else{
      //更新最后登录时间
      $GLOBALS["redis"]->hset("{$date}-login_time",$keys,$ts);

      //更新登录次数
      $checks = $GLOBALS["redis"]->hget("{$date}-login_nums",$keys);
      if($checks){
        $checks += 1;
      }else{
        $checks = 1;
      }
      //存入登录次数表
      $GLOBALS['redis']->hset("{$date}-login_nums",$keys,$checks);
    }
    echo "2000";
}
?>