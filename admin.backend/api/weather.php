<?php
/* * ************
  @天气预报接口
  @CopyRight teamtop
  @file:weather.php
  @author cooper
  @2015-10-12
 * ************* */
$city   = "guangzhou";
$ch     = curl_init();
$url    = "http://apis.baidu.com/apistore/weatherservice/weather?citypinyin=$city";
$header = array(
    'apikey: dc531e663e91f9d0c62cc4e3b848c227',
);
// 添加apikey到header
curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 执行HTTP请求
curl_setopt($ch , CURLOPT_URL , $url);
$res    = curl_exec($ch);
$ress   = json_decode($res,true);
echo $ress['retData']['city']."|".$ress['retData']['l_tmp']."-".$ress['retData']['h_tmp']."℃|".$ress['retData']['weather'];
?>

