<?php
#==================================================
# 	FileName: function.base.inc.php
# 		Desc: 基础公共函数文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.03
# LastChange: 
#==================================================

/**
 * 通过$_POST,$_GET方式获取传递的参数值
 * @param  string $param_name [参数名称]
 * @param  string $convert    [类型转换]
 * @return [type]             [返回参数值]
 */
function get_param($param_name,$convert='')
{
	$param_value = '';
	if(isset($_POST[$param_name])){
		$param_value = trim($_POST[$param_name]);
	}elseif (isset($_GET[$param_name])) {
		$param_value = trim($_GET[$param_name]);
	}
	if(!get_magic_quotes_gpc()){ //加上数据检查防sql注入
		$param_value = sql_addslashes($param_value);
	}
	if('int' == strtolower($convert)){ //类型转换(strtolower()将字符串转化为小写)
		if(strlen($param_value)>10){
			$param_value = preg_replace('/[^\d]/is', '', $param_value); // “/[^\d]/is”除数字以外的所有字符
		}else{
			$param_value = intval($param_value); //获取变量的整数值
		}
	}
	$encode = mb_detect_encoding($param_value, array("ASCII","UTF-8","GB2312","GBK","BIG5")); //检测字符的编码
	$param_value = mb_convert_encoding($param_value, 'UTF-8', $encode); //转换字符的编码
	return $param_value;
}

/**
 * 使用反斜线引用字符串(添加转义符，防sql注入)
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function sql_addslashes($value)
{
	if(empty($value)){
		return $value;
	}else{
		return (is_array($value)) ? (array_map('sql_addslashes', $value)) : (addslashes($value));
	}
}

/**
 * 载入controllers目录下对应功能模块的类文件
 * 说明：
 * 		如在controller目录下有创建文件夹，则$types='文件夹名称'。
 * 		类名(首字母大写)和模块的类文件名一致。
 * @param  string $module [模块名称]
 * @param  string $types  [类型名称]
 * @return [type]         [初始化类]
 */
function load_controller($module='',$types='')
{
	$name = false; //类名
	$path = (empty($types)) ? "" : ($types.'/');
	define('CONTROLLERS_DIR_NEW', WEBPATH_DIR.'controllers'.DIRECTORY_SEPARATOR.$path); //整站控制器路径
	if(file_exists(CONTROLLERS_DIR_NEW.$module.'.class.php')){ //检查文件或目录是否存在
		$name = ucfirst($module); //类名首字母大写
		if(class_exists($name) === false){ //类没有定义
			require(CONTROLLERS_DIR_NEW.$module.'.class.php');
		}
	}
	if($name === false){ //类文件不存在
		exit('Unable to locate the specified class file: '.$module.'.class.php');
	}
	return new $name(); //初始化类
}

/**
 * 获取用户IP
 * @return [type] [用户IP]
 */
function get_user_ip()
{
	$online_ip = "127.0.0.1";
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
		$online_ip = getenv("HTTP_CLIENT_IP");
	}elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$online_ip = getenv('HTTP_X_FORWARDED_FOR');
	}elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$online_ip = getenv('REMOTE_ADDR');
	}elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$online_ip = $_SERVER['REMOTE_ADDR'];
	}
	return $online_ip;
}

/**
 * 跳板函数(提示信息，跳转)
 * @param  string  $message [信息文本]
 * @param  string  $url     [跳转地址]
 * @param  integer $target  [类型标志]
 * @return [type]           [description]
 */
function show_info($message,$url='',$target=2)
{
	if($target == '1'){ // 1 只跳转，不提示
		echo("<script>location.href='".$url."';</script>");
		exit;
	}
	if($target == '2'){ // 2 只提示，不跳转
		echo("<script>alert('".$message."');</script>");
		exit;
	}
	if($target == '3'){ // 3 提示，并返回上一页
		echo("<script>alert('".$message."');".chr(10));
		echo("history.back();</script>");
		exit;
	}
	if($target == '4'){ // 4 提示，并跳转
		echo("<script>alert('".$message."');".chr(10));
		echo("location.href='".$url."';</script>");
		exit;
	}
	if($target == '5'){ // 5 过2秒后跳转，不提示
		echo("<script>
			function js_goto_url(){location.href='".$url."';}
			setTimeout(js_goto_url,2000);
			</script>");
		exit;
	}
	if($target == '6'){ // 6 过3秒后跳转，并提示
		echo("<script>
			function js_goto_url(){location.href='".$url."';}
			alert('".$message."');
			setTimeout(js_goto_url,3000);
			</script>");
		exit;
	}
}

/**
 * 返回随机密码
 * @param  integer $mylen 密码长度
 * @return [type]         [description]
 */
function cai_get_pwd($mylen=8)
{
	if (empty($mylen)) $mylen = 8;

	$num = '0123456789';
	for ($i=0; $i < $mylen; $i++) { 
		$authnum .=substr($num,rand(0,9),1);
	}
	return 	$authnum;
}

/**
*  单独给青龙偃月刀处理 
*/
function curl($url,$param,$timeout=3){
	$postData = http_build_query($param);
    $curl 	  = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);  
	curl_setopt($curl, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}

function post_request($url,$param=null,$method='post',$cookie_path="",$download="",$is_header="",$timeout=3)
{                
    $tmp_sources = "";
    $ch = curl_init();
    if($is_header){
    	$this_header = array(
			"Content-Type: application/x-www-form-urlencoded; 
			charset=UTF-8"
		);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
	}else{
		curl_setopt($ch, CURLOPT_HEADER, 0);
	}
    curl_setopt ($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
    
    //如果有cookie
    if($cookie_path){
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);    
    }
    //如果post传值数据
    if($method=='post')
    {
        $send_data = $param;
        curl_setopt($ch, CURLOPT_POST, 1);
        //添加变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_data);
    }

    //判断是否为https网址:
    if(strpos($url, "https")===false){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //判断是否下载文件：
    if($download){
        curl_setopt($ch, CURLOPT_FILE, $download);
    }else{
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)');
    }
//    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $tmp_sources = curl_exec($ch); 
    //捕抓异常
    if (curl_errno($ch)) {
       return 'Errno'.curl_error($ch);
    }
    curl_close($ch); 
    return $tmp_sources;
}


/**
 * 往redis中写入用户信息
 * @param  [type] $redisObj redis实例化对象
 * @param  [type] $key      key
 * @param  [type] $arr      用户信息一维数组
 * @return [type]           
 */
function userinfo_write_redis($redisObj,$key,$arr)
{
	if (!is_array($arr)) exit('$arr not array');
	foreach ($arr as $value) {
		$redisObj->rpush($key,$value);
	}
}

/**
 * 发邮件
 * @param  [type] $mailObj      邮件实例对象
 * @param  array  $to           收件人（可多个）
 * @param  array  $cc           抄送人（可多个）
 * @param  [type] $mailtitle    邮件标题
 * @param  [type] $mailcontent  邮件内容
 * @param  [type] $mailcontent2 邮件不支持html时的邮件内容
 * @return [type]               [description]
 */
function send_mail($mailObj,$to,$cc,$mailtitle,$mailcontent,$mailcontent2)
{
	ini_set("magic_quotes_runtime",0);
	$index = mt_rand(0,19);//随机取一个邮箱

	try {
		$mailObj->IsSMTP();
		$mailObj->SMTPSecure= 'ssl';
		$mailObj->CharSet   = 'UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
		$mailObj->SMTPAuth  = true;                  //开启认证
		$mailObj->Port      = 465;
		$mailObj->Host      = "smtp.exmail.qq.com";
		$mailObj->Username  = "leyou@hlwy.com";
		$mailObj->Password  = "Fanger520";
		$mailObj->AddReplyTo("leyou@hlwy.com","mckee");//回复地址
		$mailObj->From      = "leyou@hlwy.com";
		$mailObj->FromName  = "乐游科技";

		//获取收件人地址
		if(!empty($to)){
			$charge=explode(",", $to);
			foreach($charge as $toKey =>$toVlaue){
				$mailObj->AddAddress($toVlaue);
			}
		}
		//获取抄送人地址
		if (!empty($cc)) {
			foreach($cc as $ccKey =>$ccVlaue){
				$mailObj->AddCC($ccVlaue);
			}
		}

		$mailObj->Subject  = $mailtitle;
		$mailObj->Body     = $mailcontent;
		$mailObj->AltBody  = $mailcontent2;//当邮件不支持html时备用显示，可以省略
		$mailObj->WordWrap = 80;//设置每行字符串的长度
		//$mailObj->AddAttachment("f:/test.png");  //可以添加附件
		$mailObj->IsHTML(true); 
		$mailObj->Send();
		return true;
	} catch (phpmailerException $e) {
		return false;
	};
}

/**
 * 将字符串的某部分替换成 * 号
 * @param [type] $str 字符串
 */
function add_covert_str($str)
{
	$start = intval(strlen($str)/4);
	$mid = intval(strlen($str)/2);
	$mid_str = str_repeat('*',$mid);
	return substr_replace($str,$mid_str,$start,$mid);
}

/**
*功能：加密解密函数
*@param string $string 要加密字符串
*@param string $operation 动作 DECODE 解密， ENCODE 加密
*@param string $key 加密key
*@param int expiry 
*@return string 加密或解密的字符串
*/
function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
	$ckey_length = 4;
	$key = md5($key ? $key : KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 100; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 100; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 100;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 100;
		$j = ($j + $box[$a]) % 100;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 100]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

/**
*功能：还原encode_sinfo_func加密后的信息
*@param string $sinfo 调用函数encode_sinfo_func加密后的字符串
*@param string $key 加密key
*@return type 加密前的值
*/
function decode_sinfo_func($sinfo,$key)
{
    $tmp_str = base64_decode($sinfo);//BASE64 解码
	if(empty($tmp_str))
	{
		return false;
	}
	$tmp_str = uc_authcode($tmp_str,"DECODE",$key);
	if(empty($tmp_str))
	{
		return false;
	}
	return json_decode($tmp_str,true);
}

/**
*功能：将信息进行json编码，并用uc_authcode加密
*@param string $arr 要加密信息，如字符串、数组等
*@param string $key 加密key
*@return string 加密后的字符串
*/
function encode_sinfo_func($arr,$key)
{
    $tmp_str = json_encode($arr);
	return base64_encode(uc_authcode($tmp_str,"ENCODE",$key));
}

/**
 * 随机字符串
 * @param  [type] $length 长度
 * @return [type]         [description]
 */
function getRandChar($length){
   $str = '';
   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;
   for ($i=0;$i<$length;$i++) {
    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }
   return $str;
}

/*
 * 广点通异或加密处理
 * 
 */
function simple_xor($source,$key){
    $retval = "";
    $j      = 0;
    for($i=0;$i<strlen($source);$i++){
        $retval = $retval.chr(ord($source[$i])^ord($key[$j]));
        $j      = $j+1;
        $j      = $j%(strlen($key));
    }
    return $retval;
}

/**
 * 广点通回调V参数加密处理
 * @param  [type] $url         回调地址
 * @param  [type] $click_id    广点通点击ID
 * @param  [type] $muid        设备标识ID
 * @param  [type] $conv_time   激活时间
 * @param  [type] $sign_key    签名密钥
 * @param  [type] $encrypt_key 加密密钥
 * @return [type]              [description]
 */
function v_sign($url,$click_id,$muid,$conv_time,$sign_key,$encrypt_key)
{
	$query_string = 'click_id='.urlencode($click_id).'&muid='.urlencode($muid).'&conv_time='.$conv_time;
	$page_string = $url.'?'.$query_string;
	$encode_page = urlencode($page_string);
	$property = $sign_key.'&GET&'.$encode_page;
	$signature = md5($property);
	$base_data = $query_string.'&sign='.urlencode($signature);
	// 进行异或处理
	$data = base64_encode(simple_xor($base_data,$encrypt_key));
	return $data;
}

/**
 * 处理用户等级积分展示
 * @param  [type] $vip_level  VIP等级
 * @param  [type] $grow_value 成长值
 * @return [type]             返回当前用户成长值占比
 */
function show_vip($vip_level,$grow_value)
{
	$grow = array(300,1000,2000,5000,10000,20000,50000,100000);
	if (is_numeric($grow_value)) {
		if ($grow_value <= 0) {
			return 0;
		} else {
			return ($grow_value/$grow[$vip_level])*100;
		}
	} else {
		die('params error');
	}
}

/**
 * 返回当前用户对应的VIP等级
 * @param  [type] $grow_value 成长值
 * @return [type]             返回当前用户对应的VIP等级
 */
function vip_level($grow_value)
{
	if ($grow_value < 300) return 0;
	if ($grow_value >= 300 && $grow_value < 1000) return 1;
	if ($grow_value >= 1000 && $grow_value < 2000) return 2;
	if ($grow_value >= 2000 && $grow_value < 5000) return 3;
	if ($grow_value >= 5000 && $grow_value < 10000) return 4;
	if ($grow_value >= 10000 && $grow_value < 20000) return 5;
	if ($grow_value >= 20000 && $grow_value < 50000) return 6;
	if ($grow_value >= 50000 && $grow_value < 100000) return 7;
	if ($grow_value > 100000) return 8;
}

/**
 * func 验证中文姓名
 * @param $name
 * @return bool
 */
function isChineseName($name){
    if (preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $name)) {
        return true;
    } else {
        return false;
    }
}

/**
 * func 验证邮箱格式
 * @param $email
 * @return bool
 */
function isEmail($email){
    $mode = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
    if(preg_match($mode,$email)){
        return true;
    }else{
        return false;
	}
}

/**
 * func 验证手机号的准确性
 * @param $phone
 * @return bool
 */
function check_phone($phone){
	$search ='/^1[34578]\d{9}$/';
	if(preg_match($search,$phone)) {
	 	return true;
	}else {
	 	return false;
	}
}

/**
 * 检验身份证号码的正确性
 * @param  [type] $cardnum 身份证号码
 * @return [type]        2格式错误 3地区错误 4 校验码错误 5生日错误 6格式正确成年人 7格式正确未成年人
 */
function check_idnum($cardnum)
{
	$aCity = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");

	// 身份证号码格式错误
	if ( !preg_match('/^([\d]{15}|[\d]{18}|[\d]{17}x)$/i',$cardnum) ) return 2;

	// 15位的转化成18位
	if ( strlen($cardnum) == 15 ) $cardnum = idcard_15to18($cardnum);

	// 地区错误
	if ( !array_key_exists(substr($cardnum,0,2),$aCity) ) return 3;

	// 校验码错误
	if ( idcard_verify_number($cardnum) != strtoupper(substr($cardnum, 17, 1)) ) return 4;

	// 生日错误 18岁以下未成年人
	if ( !checkdate(substr($cardnum,10,2),substr($cardnum,12,2),substr($cardnum,6,4)) ) return 5;

	//判断是否是成年人
	if (checkadult($cardnum)) {
		//格式正确成年人
		return 6;
	} else {
		//格式正确未成年人
		return 7;
	}
}

/**
 * 将15位身份证升级到18位 
 * @param  [type] $idcard 身份证号码
 * @return [type]         [description]
 */
function idcard_15to18($idcard)
{ 
	if (strlen($idcard)!=15) return false;

	//如果身份证顺序码是996 997 998 999,这些是为百岁以上老人的特殊编码 
	if(array_search(substr($idcard,12,3),array('996','997','998','999')) != false) { 

		$idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
	} else {
		$idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
	}

	return $idcard.idcard_verify_number($idcard);
}

/**
 * 计算身份证校验码，根据国家标准GB 11643-1999 
 * @param  [type] $idcard_base [description]
 * @return [type]              [description]
 */
function idcard_verify_number($idcard_base)
{ 
	//加权因子 
	$factor = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);

	//校验码对应值 
	$verify_number_list = array('1','0','X','9','8','7','6','5','4','3','2');

	$checksum = 0; 
	for ($i = 0; $i < strlen($idcard_base); $i++) { 
		$checksum += substr($idcard_base,$i,1)*$factor[$i];
	}

	$mod = strtoupper($checksum % 11);
	
	return $verify_number_list[$mod];
} 

/**
 * 功能：通过身份证获取用户出生年月日
 * @param  [type] $cardnum 用户身份证号
 * @return [type]          [description]
 */
function get_user_birth($cardnum)
{
	$year_month_day = array('m_birthyear'=>0,'m_birthmonth'=>0,'m_birthday'=>0);
	if(check_idnum($cardnum) != 6 && check_idnum($cardnum) != 7) {
		return $year_month_day;
	}else{
		if(strlen($cardnum) == 15) {
			$cardnum = idcard_15to18($cardnum);//15位转18位身份证号码
		}
		$year_month_day['m_birthyear'] = intval(substr($cardnum,6,4));
		$year_month_day['m_birthmonth'] = intval(substr($cardnum,10,2));
		$year_month_day['m_birthday'] = intval(substr($cardnum,12,2));
		
		return $year_month_day;
	}
}

/**
 * 简单判断用户是否是成年人
 * @param  [type] $cardnum 身份证号码(必须保证是18位的，15位的要先转换成18位)
 * @return [type]          1成年人 0未成年人
 */
function checkadult($cardnum)
{
	if(strlen($cardnum) == 15) {
		$cardnum = idcard_15to18($cardnum);//15位转18位身份证号码
	}
	$tyear = intval(substr($cardnum,6,4));
	$tmonth = intval(substr($cardnum,10,2));
	$tday = intval(substr($cardnum,12,2));
	$yeardiff = intval(date("Y",THIS_DATETIME))-$tyear;

	if ( $yeardiff == 18 ) {//年满18

		$monthdiff = intval(date("m",THIS_DATETIME))-$tmonth;
		if ( $monthdiff > 0 ) {//月份已满18
			return 1;
		} elseif ( $monthdiff == 0 ) {//如果月份刚好满18岁

			$daydiff = intval(date("d",THIS_DATETIME))-$tday;
			if ( $daydiff > -1 ) {//日已满18
				return 1;
			} else {//日未满18
				return 0;
			}
		} else {//月份未满18
			return 0;
		}
	} elseif ( $yeardiff > 18 ) {//大于18
		return 1;
	}else{//未满18
		return 0;
	}
}

/**
 * 校验身份证号码和姓名
 * @param  [type] $obj            第三方接口对象
 * @param  [type] $name_idnum_arr 查询信息数组，exp：array('身份号码1'=>'姓名1','身份号码2'=>'姓名2');
 * @return [type]                 
 */
function check_truename_idnum($obj,$name_idnum_arr,$area_code='440106',$type='身份验证')
{
	if (!empty($name_idnum_arr) && is_object($obj)) {
		return $obj->checkNciic($name_idnum_arr,$area_code,$type);
	} else {
		exit('error in '.__FILE__.' '.__LINE__.' line');
	}
}


//随机生成唯一订单号
function StrOrderOne($param=''){
	// $codes = md5(uniqid(date('YmdHis').microtime(true).str_pad(mt_rand(1, 99999), 10, '0', STR_PAD_LEFT).sprintf('d',rand(0,1000).$param)));

	$codes = date('YmdHis').str_pad(mt_rand(1, 99999), 5, mt_rand(1,1000), STR_PAD_LEFT);
    return $codes;
}


//加密函数
function encrypt($data)
{
	$key 	= md5($_key);
	$x 		= 0;
	$len 	= strlen($data);
	$l 		= strlen($key);
	for ($i = 0; $i < $len; $i++){
		if ($x == $l){
			$x = 0;
		}
		$b .= $key[$x];
		$x++;
	}
	for ($i = 0; $i < $len; $i++){
		$s= $data[$i] ^ $b[$i];
		$str.=$s;
	}
	return base64_encode($str);
}


//解密函数
function decrypt($data)
{
	$key 	= md5($_key);
	$x 		= 0;
	$data 	= base64_decode($data);
	$len 	= strlen($data);
	$l 		= strlen($key);
	for ($i = 0; $i < $len; $i++){
		if ($x == $l){
			$x = 0;
		}
		$b .= substr($key, $x, 1);
		$x++;
	}
	for ($i = 0; $i < $len; $i++){
		$s=$data[$i]^$b[$i];
		$str.=$s;
	}
	return $str;
}

//处理参数中带有的特殊字符
function base_code($str){
	$str = str_replace("%2B", "+", $str);
	$str = str_replace("%20", " ", $str);
	$str = str_replace("%2F", "/", $str);
	$str = str_replace("%23", "#", $str);
	$str = str_replace("%3D", "=", $str);
	$str = str_replace("%25", "%", $str);
	$str = str_replace("%26", "&", $str);
	$str = str_replace("%3F", "?", $str);
	return $str;
}

//反处理特殊字符
function base_decode($str){
	$str = str_replace("+", "%2B", $str);
	$str = str_replace(" ", "%20", $str);
	$str = str_replace("/", "%2F", $str);
	$str = str_replace("#", "%23", $str);
	$str = str_replace("=", "%3D", $str);
	$str = str_replace("&", "%26", $str);
	$str = str_replace("?", "%3F", $str);
	return $str;
}

//xml转数组
function xmlToArray($xml){ 
 
 	//禁止引用外部xml实体 
 
	libxml_disable_entity_loader(true); 
	 
	$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
	 
	$val = json_decode(json_encode($xmlstring),true); 
	 
	return $val; 
}


//数组转xml
function arrayToXml($arr,$dom=0,$item=0){ 
	if (!$dom){ 
		$dom = new DOMDocument("1.0"); 
	} 
	if(!$item){ 
		$item = $dom->createElement("root"); 
		$dom->appendChild($item); 
	} 
	foreach ($arr as $key=>$val){ 
		$itemx = $dom->createElement(is_string($key)?$key:"item"); 
		$item->appendChild($itemx); 
		if (!is_array($val)){ 
			$text = $dom->createTextNode($val); 
			$itemx->appendChild($text);  
		}else { 
			arrayToXml($val,$dom,$itemx); 
		} 
	} 
	return $dom->saveXML(); 
} 
?>