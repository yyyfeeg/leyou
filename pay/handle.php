<?php
#============================================
# 	FileName: handle.php   处理回调、查询接口
# 	  Author: Jericho
# 	   Email: 190777721@qq.com
# 		Date: 2017.08.15
# LastChange: 
#============================================

// 包含总配置文件
include_once('./config.inc.php');
include_once('../configs/config.php');

//接收基础参数
$cfg 		=	new Config();
$date 		=	date("Ymd");
$act		=	get_param("act");							//1：查询  2：回调
$ft 	    =   get_param("ft")?get_param("ft"):1;			//1：浦发银行微信   2：支付宝官方APP   3:支付宝官方WAP 
$url 		=	WEBPATH_NW."wft/request.php";
$ip			=	get_user_ip();
$gameinfo	=   empty($GLOBALS["CONF_GAME"])?$cfg->ginfo:$GLOBALS["CONF_GAME"];
switch($act){
	//查询订单信息
	case 1:
		$url  	   .= 	"?method=queryOrder";
		$gid 		=	get_param("gid");			//游戏ID
		$uid		=	get_param("uid");			//用户ID
		$sid		=	get_param("sid");			//游戏服ID
		$uaid  	 	= 	get_param("uaid","int");	//渠道信息
		$uwid   	= 	get_param("uwid","int");	//子渠道
		$roleid 	=	get_param("roleid");		//角色ID
		$sign		=	get_param("sign");			//加密sign值
		$ptype		=	get_param("ptype");			//支付类型 1：微信  2：支付宝  
		$info 		=	get_param("info");			//订单参数  
		$money		=	get_param("money");			//充值金额
		$nums		=	get_param("nums");			//游戏币数量
		$orderid 	=	get_param("orders");		//平台订单号
		$info 		=   base_decode($info);

		//判断基础参数
		if(empty($gid) || empty($uid) || empty($sid) || empty($sign) || empty($uaid) || empty($uwid) || empty($info)){
			exit("1001");
		}

		//对比加密信息
		$key		=	$gameinfo[$gid]['key'];
		$mysign		=	md5($gid.$uid.$sid.$uaid.$uwid.$roleid.$info.$key);
		if($mysign!=$sign){
			exit("1002");
		}
		unset($key);

		//解密订单信息
		$info 		= base_code($info);
		$info       = decrypt($info);
		$orderinfo  = explode('д',$info);

		//判断参数是否一致
		if($gid!=$orderinfo[1] || $uaid!=$orderinfo[2] || $uwid!=$orderinfo[3] || $sid!=$orderinfo[4] || $uid!=$orderinfo[5] || $roleid!=$orderinfo[7]  || $ptype!=$orderinfo[9] || $money!=$orderinfo[6] || $nums!=$orderinfo[10] || $orderid!=$orderinfo[11]){
			exit("1003");
		}

		//查询当前订单状态		
		$_POST["ptype"] 			= $ptype;
		$_POST["out_trade_no"]		= $orderid;
		$_POST["notify_url"]		= "";
		$ret = post_request($url,$_POST);
		if(!empty($ret)){
			$ret = json_decode($ret,true);
			if(isset($ret["data"]["trade_state"]) && !empty($ret["data"]["trade_state"])){
				$states = $ret["data"]["trade_state"];
				switch($states){
					//支付成功
					case 'SUCCESS':
						$code = 1000;			
						break;
					//未支付
					case 'NOTPAY':
						$code = 1005;
						break;
					//已关闭
					case 'CLOSED':
						$code = 1006;
						break;
					//支付失败
					case 'PAYERROR':
						$code = 1007;
						break;
				}
			}else{
				$code = 1004;
			}
		}else{
			//服务器响应失败
			$code = 1008;
		}
		echo $code;
		exit;
		break;


	//充值订单回调
	case 2:
		$fname = WEBPATH_DIR."cache/".date("Ymd")."-order_callback.txt";		//记录日志文件
		if($ft==1){
			$xml = file_get_contents('php://input');								//获取xml
			file_put_contents($fname,$xml.chr(10),FILE_APPEND);
			if(empty($xml)){
				exit("failure");
			}
			$newarrs			=  xmlToArray($xml);
			$res_money			=  $newarrs["total_fee"];		//回调金额，单位： 分
		}else{ // 支付宝回调
			$alipost = $_POST;
			file_put_contents($fname,serialize($alipost).chr(10),FILE_APPEND);
			$notify_url = "notify.php";
			$result  	= post_request(WEBPATH_NW."alipay/".$notify_url,$alipost);
			if($result=='success'){
				$alipost["notify_time"] = strpos($alipost["notify_time"],"-")?strtotime($alipost["notify_time"]):$alipost["notify_time"];
				$newarrs = array(
					"attach"			=>	$alipost["passback_params"],
					"out_trade_no"		=>	$alipost["out_trade_no"],			//平台订单号
					"transaction_id"	=>	$alipost["trade_no"],				//渠道订单号
					"time_end"			=>	$alipost["notify_time"],
					"out_transaction_id"=>	"",
				);
				//仅当充值成功时赋值：
				if($alipost["trade_status"]=="TRADE_SUCCESS"){
					$newarrs["result_code"] = 0;
					$newarrs["status"]      = 0;
					$res_money				= ($alipost["total_amount"]*100);
					$newarrs["other"]		= $alipost;		//other时代表支付宝回调	
				}
			}else{
				exit("failure");
			}
		}
		$str_tmp 				=  decrypt(base_code($newarrs["attach"]));		//先转换html特殊字符，后解码(与加密相反)
		$pinfo  				=  explode("д",$str_tmp);
		$newarrs["ptype"]  		=  $pinfo[9]; 
		$newarrs["notify_url"]	=  "";
		$orderid  				=  $newarrs["out_trade_no"];			//平台订单号
		$str_tmp			   .=  "д".$orderid;

		//查询是否存在该订单
		$isexist = $GLOBALS["redis"]->sismember("orders",$str_tmp);
		if($isexist){
			$replace	    =  "д".$orderid;

			//查询订单信息是否一致
			if($newarrs["result_code"]==0 && $newarrs["status"]==0 && $res_money==$pinfo[6]){
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
				);
				
				$gkey = $gameinfo[$pinfo[1]]['key'];

				//查询游戏订单号
				$sql = "select ol_gameorder as gorder from ".get_table("orderform_log")." where ol_gid=".$pinfo[1]." and ol_uwid=".$pinfo[3]." and ol_orderid='".$orderid."'";
				$query = $GLOBALS["count"]->query($sql);
				$ginfos = $GLOBALS["count"]->getOne($query);

				//回调给游戏
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
					// $gret  = curl($gameurl,$posdata);  // header为x-www-form-urlencoded
					//单独给斗破乾坤使用get模式
					switch($pinfo[1]){
						case "10001":
							$dp = "";
							foreach($posdata as $key=>$val){
								$dp .= $key."=".$val."&";
							}
							$dp   	  = substr($dp,0,-1);
							$gameurl .= "?".$dp;
							$gret = post_request($gameurl,"","get");
							break;
						default:
							$gret = post_request($gameurl,$posdata);
							break;
					}
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
					file_put_contents(WEBPATH_DIR.'cache/'.date("Ymd").'-game_post.txt', "url:".$gameurl."datas:".serialize($posdata).chr(10),FILE_APPEND);   //游戏数据
					file_put_contents(WEBPATH_DIR.'cache/'.date("Ymd").'-game_return.txt',serialize($gret).chr(10),FILE_APPEND);    //游戏回调
				}

				//更新mysql数据
				$result = update_record($GLOBALS["count"],'orderform_log',$data,$wheres);
				if(!$result){
					//记录至文件内
					$fname = WEBPATH_DIR."cache/".date("Ymd")."-order_callback_error.txt";
					file_put_contents($fname,"更新数据：".serialize($data)."更新条件：".serialize($wheres).chr(10),FILE_APPEND);
				}

				//从redis中移除数据
				$GLOBALS["redis"]->srem("orders",$str_tmp);


				$GLOBALS["redis"]->srem("orders_tmp",str_replace($replace, "", $str_tmp));

			}else{
				$fname = WEBPATH_DIR."cache/".date("Ymd")."-order_callback_error.txt";
				file_put_contents($fname, serialize($newarrs));
			}
		}
		exit("success");
		break;

	//接口测试
	case 3:
		exit;
		$check_url = WEBPATH_NW."handle.php?act=2&ft=1";
		$arrs	   = '<xml><attach><![CDATA[VQEBVgBXXQ4KV%2BCEWwsJDbWNCQEB6Y0KV1G2jA0LDly0gFAFWbPQCOjSAQJR4oQAUQ3ohALpjQk=]]></attach>
<bank_type><![CDATA[ALIPAYACCOUNT]]></bank_type>
<buyer_logon_id><![CDATA[270***@qq.com]]></buyer_logon_id>
<buyer_user_id><![CDATA[2088012609456328]]></buyer_user_id>
<charset><![CDATA[UTF-8]]></charset>
<device_info><![CDATA[AND_SDK]]></device_info>
<fee_type><![CDATA[CNY]]></fee_type>
<fund_bill_list><![CDATA[[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]]]></fund_bill_list>
<gmt_create><![CDATA[20170816101529]]></gmt_create>
<mch_id><![CDATA[101520000465]]></mch_id>
<nonce_str><![CDATA[1502849856651]]></nonce_str>
<openid><![CDATA[2088012609456328]]></openid>
<out_trade_no><![CDATA[78f575db9ffb23f03c31341830ac5838]]></out_trade_no>
<out_transaction_id><![CDATA[2017081621001004320218680671]]></out_transaction_id>
<pay_result><![CDATA[0]]></pay_result>
<result_code><![CDATA[0]]></result_code>
<sign><![CDATA[8A6950F303F15F4DE6340D81141D38DD]]></sign>
<sign_type><![CDATA[MD5]]></sign_type>
<status><![CDATA[0]]></status>
<time_end><![CDATA[20170816101736]]></time_end>
<total_fee><![CDATA[1]]></total_fee>
<trade_type><![CDATA[pay.alipay.native]]></trade_type>
<transaction_id><![CDATA[101520000465201708161181919511]]></transaction_id>
<version><![CDATA[2.0]]></version>
</xml>';
		$ret = post_request($check_url,$arrs);
		echo($ret);
		break;

}

?>
