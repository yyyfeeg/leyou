<?php
include_once("config.inc.php");
include_once('../configs/config.php');


//实例化
$cfg   = new Config();
$pars  = $_POST;
$fname = WEBPATH_DIR."cache/jz_order".date("Ymd")."_notify.txt";
file_put_contents($fname,serialize($pars).chr(10),FILE_APPEND);		//记录日志
if(empty($pars)){
	header("Location: "."../index.php");
	exit;
}


$UserID	  = '357p';//此项固定为357p
$Key 	  = $cfg->conf["key"];//此项需要您设置，和金猪平台一致
$ProID 	  = $pars['ProID'];//产品ID
$OrderID  = $pars['OrderID'];//订单号
$Num	  = $pars['Num'];//充值数量
$UserName = $pars['UserName'];//充值账号或角色名
$Money	  = $pars['Money'];//充值金额
$yuanbao  = $pars['yuanbao'];//货币数量
$Sign     = $pars['Sign'];//与金猪服务器通讯加密字符串
$fencheng = $pars['fencheng'];//商户分成金额，适用于纯接口模式
$jinzhua  = $pars['jinzhua'];//预留回调1
$gameinfo = empty($GLOBALS["CONF_GAME"])?$cfg->ginfo:$GLOBALS["CONF_GAME"];
$Str      = 'UserID='.$UserID.'&ProID='.$ProID.'&OrderID='.$OrderID.'&Num='.$Num.'&yuanbao='.$yuanbao.'&UserName='.$UserName.'&Money='.$Money.'&Key='.$Key;
$MySign   = md5($Str);
if($MySign==$Sign){
	$newarrs = array(
		"attach"			=>	$pars["jinzhua"],
		"transaction_id"	=>	$OrderID,
		"time_end"			=>	THIS_DATETIME,
		"out_transaction_id"=>	"",
	);
	$str_tmp =  $newarrs["attach"];
	$pinfo   =  explode("д",$str_tmp);
	$isexist =  $GLOBALS["redis"]->sismember("orders",$str_tmp);
	$Money   =  $Money*100;
	if($isexist && $pinfo[6]==$Money){
		$orderid  	=  $pinfo[11];			//平台订单号
		$replace	=  "д".$orderid;

		//查询订单信息是否一致
		$wheres  = array(
			"ol_gid"		=>	$pinfo[1],
			"ol_uwid"		=>	$pinfo[3],
			"ol_sid"		=>	$pinfo[4],
			"ol_rid"		=>	$pinfo[7],
			"ol_orderid"	=>	$orderid,
		);
		//更新数据表
		$data  = array(
			"ol_ortherid"	=>	$newarrs["out_transaction_id"],
			"ol_transorder"	=>	$newarrs["transaction_id"],
			"ol_payresult"	=>	1,

			//暂时定义游戏发货失败
			"ol_giveresult" =>  2,
		);

		#游戏发货接口 TODO...
		//查询游戏订单号
		$sql = "select ol_gameorder as gorder from ".get_table("orderform_log")." where ol_gid=".$pinfo[1]." and ol_uwid=".$pinfo[3]." and ol_orderid='".$orderid."'";
		$query = $GLOBALS["count"]->query($sql);
		$ginfos = $GLOBALS["count"]->getOne($query);

		//回调给游戏
		$gkey     = $gameinfo[$pinfo[1]]["key"];
		$posdata  = array(
			"gid"		 => $pinfo[1],					//游戏ID
			"uid"		 => $pinfo[5],					//UID
			"sid"		 =>	$pinfo[4],					//游戏服ID
			"roleid"	 =>	$pinfo[7],					//角色ID
			"orderid"	 =>	$orderid,					//平台订单号
			"transorder" => $newarrs["transaction_id"],	//第三方订单号
			"goodsid"	 =>	$pinfo[8],					//物品ID
			"money"		 => $pinfo[6],					//充值金额 分
			"nums"		 => $pinfo[10],					//游戏币数量
			"paytime"    => $newarrs["time_end"],		//支付完成时间
			"ptype"		 => $pinfo[9],					//支付类型 1：微信  2：支付宝  
			"gorder"     => $ginfos["gorder"],			//游戏订单号
			"sign"       => md5($pinfo[4].$pinfo[7].$orderid.$newarrs["transaction_id"].$pinfo[8].$pinfo[6].$pinfo[10].$newarrs["time_end"].$pinfo[9].$gkey),
		);

		//游戏回调地址
		$gameurl = $gameinfo[$pinfo[1]]['notify'];
		
		if(!empty($gameurl)){
			// $gret  = curl($gameurl,$posdata);
			$gret = post_request($gameurl,$posdata);
			if(!empty($gret)){
				$gret  = json_decode($gret,'true');
				$data["ol_giveresult"] = ($gret["status"]==100)?1:2;	//发货结果	1:成功   2：失败
				$data["ol_givetime"]   = time();						//发货时间
			}else{
				//记录信息至redis -- 定时推送补单
				$redata['ac_time']  = time();   //操作时间
				$redata['ac_nums']  = 0;        //推送次数
				$redata['param']    = $posdata; //参数
				$redata['url'] 	    = $gameurl; //游戏回调地址
				$redata['wheres']   = $wheres;	//查询条件
				$redata['data'] 	= $data;	//更新数据
				$GLOBALS["redis"]->sadd("pushorder",serialize($redata));
			}
			// file_put_contents(WEBPATH_DIR.'cache/a.txt', serialize($gret));    //游戏回调
		}

		update_record($GLOBALS["count"],'orderform_log',$data,$wheres);

		//从redis中移除数据
		$GLOBALS["redis"]->srem("orders",$str_tmp);
		$GLOBALS["redis"]->srem("orders_tmp",str_replace($replace, "", $str_tmp));

		echo '357papiSuccess357papi';
	}else{
		echo '357papiSQLFALSE357papi';
	}
	exit();
} else {
	file_put_contents($fname,"mysign--->".$MySign."，sign--->".$Sign.chr(10),FILE_APPEND);
    //验证失败
    echo "357papiSQLFALSE357papi";
}
exit();
