<?php
/**
* 充值补单机制
* 
*/
include_once('./config.inc.php');
$tarray = array('15','30','60','180','1800','3600');	//重复推送间隔
$table  = "pushorder";
$ts     = time();

/*******************************   测试  begin  ******************/
$data   = unserialize('a:11:{s:3:"sid";s:7:"1410999";s:6:"roleid";s:6:"100116";s:7:"orderid";s:32:"5bdf5f36a661759740bf6a5ad099f77a";s:10:"transorder";s:28:"7552900037201709204202342061";s:7:"goodsid";s:2:"80";s:5:"money";s:1:"1";s:4:"nums";s:1:"1";s:7:"paytime";s:14:"20170920112526";s:5:"ptype";s:1:"1";s:6:"gorder";s:18:"150587791010768048";s:4:"sign";s:32:"14ebfd1d13cbe6d4e46385ed5f573498";}');
$data['ac_time'] = time();
$data['ac_nums'] = 0;
$GLOBALS['redis']->sadd($table,serialize($data));
/*******************************   测试  end   ******************/

$nums = $GLOBALS['redis']->scard($table);
if($nums>0){
	//循环遍历
	for($i=0;$i<$nums;$i++){
		$var = $GLOBALS['redis']->spop($table);
		//根据次数判定时间
		$var = unserialize($var);
		//查询是否到发送时间
		if($ts-$var["ac_time"]>=$tarray[$var["ac_nums"]]){
			$return = curl($var["url"],$var["param"]);
			if(empty($return)){
				$var["ac_nums"]  += 1; 
				$GLOBALS['redis']->sadd($table,serialize($var));
			}else{
				//更新数据库状态值
				$var["ol_givetime"]   = time();	
				$var["ol_giveresult"] = ($return['status']==100)?1:2;
				update_record($GLOBALS["count"],'orderform_log',$var["data"],$var["wheres"]);
			}
		}
	}
}
exit;
?>