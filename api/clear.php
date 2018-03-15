<?php
//清理redis表
include_once "../config.inc.php";
$tps    = get_param("type");
$tables = $GLOBALS["redis"]->keys("*");
if($tps==1){
  foreach($tables as $v){
    $GLOBALS["redis"]->del($v);
  }
  exit("清理完成");
}else{
  foreach($tables as $v){
    if(strstr($v,"2017")) continue;
    $counts = $GLOBALS["redis"]->scard($v);
    echo $v."表内存在数据：".$counts."条<br>";
  }
}
?>
