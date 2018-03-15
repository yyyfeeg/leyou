<?php
function toHtml($txt){
	$txt = str_replace("  ","　",$txt);
	$txt = str_replace("<","&lt;",$txt);
	$txt = str_replace(">","&gt;",$txt);
	$txt = preg_replace("/[\r\n]{1,}/is","<br/>",$txt);
	return $txt;
}
 
function delHtml($txt){
	$txt = str_replace("<p>","",$txt);
	$txt = str_replace("</p>","",$txt);
	return $txt;
}

//把字符串转化为可以在JS数组里输出的字符串
function jsToHtml($mystr){
	if(empty($mystr)){
		return "";
	}
	$mystr = str_replace("\r\n","",$mystr);
	$mystr = str_replace("'","\\'",$mystr);
	return $mystr;
}

/* *
*	功能：判断是否中文字符
*
*	$mystr 	要判断的字符串
*	返回：true/false
* */
function check_is_cn($mystr){
	if(preg_match("/[".chr(228).chr(128).chr(128)."-".chr(233).chr(191).chr(191)."]/",$mystr)){
		return true;
	}else{
		return false;
	}
}

function cutstr($string, $length, $dot = ' ...',$charset='utf-8') {
/*
功能:按长度截取字符串
$string, $length, $dot,$charset
字符串,要取长度,截断符,编码
*/
	if(strlen($string) <= $length) {
		return $string;
	}

	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	if(strtolower($charset) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}

function isemail($email) {
/*
功能:邮箱地址
$email,
字符串
*/
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//=========================begin 时间处理
function is_time($time){
/**
 * 功能:检查是否为一个合法的时间格式
 * @param   string  $time
 * @return  void
 */
    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';
	
    return preg_match($pattern, $time);
}

function is_date($date){
/**
 * 功能:检查是否为一个合法的日期格式
 * @param   string  $date
 */
	$pattern = '/^[\d]{4}-[\d]{1,2}-[\d]{1,2}$/';
	return preg_match($pattern, $date);
}

function change_format($time,$time2,$myformat="d"){
/**
*功能:返回两个时间差
*参数:$time,$time2 时间1,时间2
*参数:$myformat 返回的时间差格式
*/
	$sY = 31536000;//年
	$sM = 2592000;//月(三十天计算)
	$sW = 604800;//星期
	$sD = 86400;//天
	$sH = 3600;//小时
	$sI = 60;//分钟
	$sS = 1;//秒

	$tmp_time = strtotime($time)-strtotime($time2);
	switch($myformat){
		case "y"://年
			$tmp_time = $tmp_time/$sY;
			break;
		case "w"://星期
			$tmp_time = $tmp_time/$sW;
			break;
		case "d"://天
			$tmp_time = $tmp_time/$sD;
			break;
		case "h"://小时
			$tmp_time = $tmp_time/$sH;
			break;
		case "i"://分钟
			$tmp_time = $tmp_time/$sI;
			break;
		case "s"://秒
			$tmp_time = $tmp_time;
			break;
		default://默认返回天
			$tmp_time = $tmp_time/$sD;
			break;
	}
	return $tmp_time;
}





/*
 * $_POST,$_GET获取数据
 * $param_name 传递参数
 * $convert 类型转换
 */
function get_param($param_name,$convert='')
{
	$param_value = "";
	if(isset($_POST[$param_name])){
		$param_value = trim($_POST[$param_name]);
	}else if(isset($_GET[$param_name])){
		$param_value = trim($_GET[$param_name]);
	}
    $param_value = RemoveXSS($param_value);
	if(!get_magic_quotes_gpc()){//加上检查数据防sql注入
		$param_value = sql_addslashes($param_value);
	}
	if ('int' == strtolower($convert)){
		if (strlen($param_value)>10){
			$param_value = preg_replace('/[^\d]/is','',$param_value);
		}
		else $param_value = intval($param_value);
	}
    $encode = mb_detect_encoding($param_value, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
	$param_value = mb_convert_encoding ($param_value, 'UTF-8', $encode);
	return $param_value;
}

function sql_addslashes($value){
    if (empty($value)){
        return $value;
    }else{
        return is_array($value) ? array_map('sql_addslashes', $value) : addslashes($value);
    }
}

//返回随机密码
function cai_get_pwd($mylen=8){
//密码长度:$mylen
	if(empty($mylen)){
		$mylen = 8;
	}
	$array="0123456789";
	for($i=0;$i<$mylen;$i++){
		$authnum .=substr($array,rand(0,9),1);
	}
	return 	$authnum;
}

//返回访问者的IP
function return_user_ip(){
	$onlineip="127.0.0.1";
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
}



/* *
*	提示并跳转
*
*	$mssage 数据库表名
*	$url 跳转URL
*	$target 跳转方式1，2，3，4
* */
function showinfo($mssage,$url='',$target=2){
	if($target=="1"){
	//只传向，不提示
		echo("<script>location.href='".$url."';</script>");
		exit;
	}
	if($target=="2"){
	//只提示，不传向
		echo("<script>alert('".$mssage."');</script>");
		exit;
	}
	if($target=="3"){
	//提示，并返回上一页
		echo("<script>alert('".$mssage."');".chr(10));
		echo("history.back();</script>");
		exit;
	}
	if($target=="4"){
	//提示并跳转到指定页
		echo("<script>alert('".$mssage."');".chr(10));
		echo("location.href='".$url."';</script>");
		exit;
	}
	if($target=="5"){
	//过2秒钟之后，只传向，不提示
		echo("<script>
		function top_js_goto(){
			location.href='".$url."';
		}
		setTimeout(top_js_goto,2000);
		</script>");
		exit;
	}
	if($target=="6"){
	//过3秒钟之后，传向，提示
		echo("<script>
		function top_js_goto(){
			location.href='".$url."';
		}
		alert('".$mssage."');
		setTimeout(top_js_goto,1000);
		</script>");
		exit;
	}
}

/**
*分页函数
*参数：$num，$perpage,$curr_page,$mpurl,
*说明：总数量,每页条数,当前页号,连接的URL
*
**/
//分页
function multi($num, $perpage, $curr_page, $mpurl,$showtype=1) {
	$multipage = '';
	if($num > $perpage) {
		$pages = ceil($num / $perpage);//求总页数
		$multipage .= "<a href=\"$mpurl&page=1\">首页</a>&nbsp<a href=\"$mpurl&page=".(($curr_page>0?$curr_page:1)-1)."\">上一页</a>&nbsp;";
		
		$multipage .= "<a href=\"$mpurl&page=".($curr_page>=$pages?$pages:$curr_page+1)."\">下一页</a>&nbsp<a href=\"$mpurl&page=$pages\" >最后页</a>";
		if($showtype==3){
			$multipage .= "";
		}
		else if($showtype==2){
			$multipage .='<input name="mynumpage" type="text" id="mynumpage" value="'.$curr_page.'" size="5" /><input onclick="location.href=\''.$mpurl.'&page=\'+document.getElementById(\'mynumpage\').value;" type="button" name="Submit" value="Go" />';
		}else{
			$multipage .= "<select name='showpage' id='showpage' onchange='location.href=\"".$mpurl."&page=\"+this.options[this.selectedIndex].value;'>";
			for($i=1;$i<=$pages;$i++){
				if($i==$curr_page){
					$multipage .= '<option value="'.$i.'" selected="selected">'.$i.'/'.$pages.'</option>';
				}else{
					$multipage .= '<option value="'.$i.'">'.$i.'/'.$pages.'</option>';
				}
			}
			$multipage .= "</select>";
		}
	}
	return $multipage;
}


//=========begin 操作数据库通用
/* *
*	检查相关记录是否存在
*
*	$table 数据库表名
*	$value 与$fields对应的值
* */
function exist_check($conn,$table,$value = array(),$type=1){
	$where = '';
	foreach($value as $key=>$val){
		if($key!=""){
			$where .= " AND `".$key."`='".$val."' ";
		}
	}
	if($type == 1){
		$exist= "SELECT 1 FROM ".get_table($table)." WHERE 1 ".$where;
	}else{
		$exist= "SELECT 1 FROM ".$table." WHERE 1 ".$where;
	}
	$res = $conn->Query($exist);
	if($conn->NumRows($res)>0)
	{
		return true;
	}else{
		return false;
	}
}

/* *
 *	在数据库表$table中增加一条记录
*
*	$table 数据库表名
*	$value 与$field对应的值
*   $type 1 采用get_table方法  2 则不采用 ace modified 2013/12/31
* */
function add_record($conn,$table,$value = array(),$show_bug=false,$type=1)
{
	$field_str = '';
	$value_str = '';
	foreach($value as $key=>$val)
	{
		if($key!=""){
			$field_str .= '`'.$key.'`,';
			$value_str .= "'".$val."',";
		}
	}

	$field_str = substr($field_str,0,-1);
	$value_str = substr($value_str,0,-1);
	if($type == 1)
	{
		$insert_sql = "INSERT INTO ".get_table($table)." ($field_str) VALUES($value_str);";
	}else
	{
		$insert_sql = "INSERT INTO ".$table." ($field_str) VALUES($value_str);";
	}
	//var_dump($insert_sql);exit();
	$conn->Query($insert_sql);
	$result = array();
	$result['rows'] = $conn->AffectedRows();
	$result['id'] = $conn->InsertID();
	if($show_bug==true){
		$result['error'] = $insert_sql;
	}
	return $result;
}
/* *
*	在数据库表$table中更新一条记录
*
*	$table 	数据库表名
*	$value 	与$field对应的值
*	$where_ 条件字段与值
*   $type 1 采用get_table方法  2 则不采用
* */

function update_record($conn,$table,$value = array(),$where_value = array(),$where='',$type=1)
{	
	$update_str = '';
	
	foreach($value as $key=>$val)
	{
		if($key!=""){
			$update_str .= "`".$key."`='".$val."',";
		}
	}
	if(empty($where))
	{
		foreach($where_value as $key=>$val)
		{
			if($key!=""){
				$where .= " AND `".$key."`='".$val."' ";
			}
		}
	}
	
	$update_str = substr($update_str,0,-1);
	if($type ==1)
	{
		$sql = "UPDATE ".get_table($table)." SET $update_str WHERE 1 $where;";
	}else
	{
		$sql = "UPDATE ".$table." SET $update_str WHERE 1 $where;";
	}

	$rs = $conn->Query($sql);
	return $conn->AffectedRows();
}

function update_record_nodyh($conn,$table,$value = array(),$where_value = array(),$where='',$type=1)
{	
	$update_str = '';
	
	foreach($value as $key=>$val)
	{
		if($key!=""){
			$update_str .= "`".$key."`=".$val.",";
		}
	}
	if(empty($where))
	{
		foreach($where_value as $key=>$val)
		{
			if($key!=""){
				$where .= " AND `".$key."`='".$val."' ";
			}
		}
	}
	
	$update_str = substr($update_str,0,-1);
	if($type ==1)
	{
		$sql = "UPDATE ".get_table($table)." SET $update_str WHERE 1 $where;";
	}else
	{
		$sql = "UPDATE ".$table." SET $update_str WHERE 1 $where;";
	}
	//echo $sql;exit;
	$rs = $conn->Query($sql);
	return $conn->AffectedRows();
}
/* *
*	在数据库表$table中删除(物理删除)或更新(逻辑删除)一条记录
*
*	$table 	数据库表名
*	$where_ 条件字段与值
*	$value 	与$field对应的值
*   $type 1 采用get_table方法  2 则不采用
* */

function delete_record($conn,$table,$where_value = array(),$value = array(),$type=1)
{	
	$update_str = '';
	$where = '';
	foreach($where_value as $key=>$val)
	{
		if($key!=""){
			$where .= " AND `".$key."`='".$val."' ";
		}
	}
	if(count($value)>0)
	{
		foreach($value as $key=>$val)
		{
			if($key!=""){
				$update_str .= "`".$key."`='".$val."',";
			}		
		}
		
		$update_str = substr($update_str,0,-1);
		if($type ==1)
		{
			$sql = "UPDATE ".get_table($table)." SET $update_str WHERE 1 $where;";
		}else
		{
			$sql = "UPDATE ".$table." SET $update_str WHERE 1 $where;";
		}
	}
	else
	{
		if($type == 1)
		{
			$sql = "DELETE FROM ".get_table($table)." WHERE 1 $where;";
		}else
		{
			$sql = "DELETE FROM ".$table." WHERE 1 $where;";	
		}
	}
        
	$conn->Query($sql);
	
	return $conn->AffectedRows();
}

/**
*	获取数据库表$table中字段$info的信息
*
*	$table 数据库表名
*	$fields 条件字段名
*	$value 字段($fields)对应值
*	$info 返回信息字段名
*	$all 是否返回所有记录
**/
function get_info($conn,$table,$info = array(),$where='',$OrderBy='',$all = false){
	if(!empty($info))
	{
		$str = implode(',',$info);
	}
	else
	{
		$str = '*';
	}
	if ($OrderBy != ""){
        $where .= $OrderBy;
	}
	$sql= "SELECT $str FROM ".get_table($table)." WHERE 1 ".$where.'';
	$res = $conn->Query($sql);
	
	//echo $sql;echo '<br/>';
	if($conn->NumRows($res)>0)
	{
		if(!$all)
		{
			$arr = $conn->FetchArray($res);
			return $arr;
		}
		else
		{
			$all_record = array();
			//$all_record[] = $arr;
			while($arr = $conn->FetchArray($res))
			{
				$all_record[] = $arr;
			}
			
			return $all_record;
		}
	}
	else
	{
		return array();
	}
}

function get_table($myname){
	global $TABLE_NAME_INC,$MYSQL_DB;
	return $MYSQL_DB.".`".$TABLE_NAME_INC.$myname."`";
}
//=========end 操作数据库通用


//获取随机数
function get_rand($proArr) {
    $result = '';

    //概率数组的总概率精度 
    $proSum = array_sum($proArr);

    //概率数组循环 
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);

        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset($proArr);
    return $result;
}

//返回毫秒的时间
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

//==================begin 模拟post发数据
function post_request($url,$param=null,$method='post',$cookie_path="",$download="")
{                
    $tmp_sources = "";
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
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
//==================end 模拟post发数据


/**
* @去除XSS（跨站脚本攻击）的函数
* @par $val 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
* @return  处理后的字符串
* @Recoded By Androidyue
**/
function RemoveXSS($val) {  
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
   // this prevents some character re-spacing such as <java\0script>  
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs  
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);  
 
   // straight replacements, the user should never need these since they're normal characters  
   // this prevents like <IMG SRC=@avascript:alert('XSS')>  
   $search = 'abcdefghijklmnopqrstuvwxyz'; 
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';  
   $search .= '1234567890!@#$%^&*()'; 
   $search .= '~`";:?+/={}[]-_|\'\\'; 
   for ($i = 0; $i < strlen($search); $i++) { 
      // ;? matches the ;, which is optional 
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
 
      // @ @ search for the hex values 
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ; 
      // @ @ 0{0,7} matches '0' zero to seven times  
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
   } 
 
   // now the only remaining whitespace attacks are \t, \n, and \r 
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
   $ra = array_merge($ra1, $ra2); 
 
   $found = true; // keep replacing as long as the previous round replaced something 
   while ($found == true) { 
      $val_before = $val; 
      for ($i = 0; $i < sizeof($ra); $i++) { 
         $pattern = '/'; 
         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
            if ($j > 0) { 
               $pattern .= '(';  
               $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
               $pattern .= '|';  
               $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
               $pattern .= ')*'; 
            } 
            $pattern .= $ra[$i][$j]; 
         } 
         $pattern .= '/i';  
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag  
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
         if ($val_before == $val) {  
            // no replacements were made, so exit the loop  
            $found = false;  
         }  
      }  
   }  
   return $val;  
}

/**
 * 载入controllers目录的文件
 * author xiaobei
 */
function load_controller($module='',$types="")
{
	$name = false;
        $path = empty($types)?"":$types."/";
        define("CONTROLLERS_DIR_NEW",WEBPATH_DIR."controllers".DIRECTORY_SEPARATOR.$path); //整站控制器路径
	if (file_exists(CONTROLLERS_DIR_NEW.$module.'.class.php'))
	{
		$name = ucfirst($module);
		if (class_exists($name) === FALSE)
		{
			require(CONTROLLERS_DIR_NEW.$module.'.class.php');
		}
	}
	if ($name === FALSE)
	{
		exit('Unable to locate the specified class: '.$module.'.class.php');
	}
	return new $name();
        
}

//写入日志
function admin_log($userid,$uname,$msg,$do_time,$lip){
	$sql = "insert into ".get_table("admin_log")."(al_userid,al_logname,al_content,al_inserttime,al_ip) values('".$userid."','".$uname."','".$msg."','".$do_time."','".$lip."')";
	$rs = $GLOBALS["conn"]->query($sql);
	if($rs){
		return true;
	}else{
		return false;
	}

}

/*
功能：二维数组根据二维关键字的值排序
$arr 二维数组,
$mykey 二维关键字名称,
$mytype=1(1从大到小，2从小到大)
*/
function sort_array_to_max($arr,$mykey,$mytype=1){
	$num = count($arr);
	if($num<2){
		return $arr;
	}
	$tmp_arr = "";
	$tmp_arr2 = "";
	for($j=0;$j<$num;$j++){
		for($i=0;$i<$num;$i++){
			if($mytype==1){
				if($arr[$j][$mykey]>$arr[$i][$mykey]){
					$tmp_arr = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp_arr;
				}
			}else{
				if($arr[$j][$mykey]<$arr[$i][$mykey]){
					$tmp_arr = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp_arr;
				}
			}
		}
	}
	return $arr;
}

/* *
 * 将数据表中某个字段值分割
 * $table 数据表名
 * $field  字段名
 * $delim 分隔符,不为空返回数组
 * $where 不带where的条件
 * 返回数组，或者某字段值
 * */
function split_filed($conn, $table, $field, $where, $delim='', $show_bug=false){
	$where =' where '.$where;
	if (empty($table) or empty($field) or empty($where))return FALSE;
	else {
		$sql = 'select `'.$field.'` from '.get_table($table).$where.' limit 1';
		$rst = $conn->Query($sql);
		$record = $conn->getOne($rst);
	}
	if($show_bug)return $sql;
	if (!empty($delim)) //将字符串转换成数组，并返回
		if (''!= $record[$field])return explode($delim, $record[$field]);
	return $record[$field];
}


/*
 * $data 要添加符号（"" 和 `）的一维数组，或者可分割的字符串
 * 如：数组 array('ad','df')将合成串：'"ad","df"'
 * 	   字符串 'ad,df' 将合成'"ad","df"'
 * $delim 被替换的串
 * 当data不是数组并且quote为空时，返回数组
 * 在mysql in集合查询中直接使用 
 * */
function addquote($delim=',',$data, $quote = '"'){
	$string = '';
	if (!is_array($data) and '' == $quote)return explode($delim, $data);
	$replace = $quote.','.$quote;
	if (is_array($data)){
		$data = implode($delim, $data);
	}
	$string = $data;
	$string = str_replace($delim, $replace, $string);
	$string = $quote.$string.$quote;
	return $string;
}

/*
 * 判断一个字符串是否包含另一个字符串
 * $str 包含字符串
 * $needle 被包含串
 */
function strfind($str, $needle, $offset = null){
	$rst = strpos($str, $needle, $offset = null);
	return $rst;
}


/**
 * 获取无限级分类菜单
 * @param type $items   数组
 * @param type $id      对应权限id
 * @param type $pid     上级目录ID号
 * @param type $son     如果是三级或三级以上目录，则为son作为键值匹配
 * @param type $menu    一二级目录，menus作为键值匹配
 * @return type 
 */
function getTrees($items,$id='menuid',$pid='pid',$son='child' ,$menu = 'menus'){
    $tree = array(); 
    $tmpMap = array();
    foreach ($items as $item) {
        $item['icon'] = get_icon();
        $tmpMap[$item[$id]] = $item;
        if($item[$pid]==0){
        	$dimension[]		= $item[$id];
        }
    }
    foreach ($items as $k=>$item) {
	   if (isset($tmpMap[$item[$pid]])) {
	   		if(!is_array($dimension)){
	   			continue;
	   		}
			if(!in_array($item[$pid],$dimension)){
	       		$tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
			}else{
				$tmpMap[$item[$pid]][$menu][] = &$tmpMap[$item[$id]];
			}	
	    } 
	    else {
	    	$tree[$menu][] = &$tmpMap[$item[$id]];
	    }
    }
    unset($tmpMap);
	
    return $tree;

}

/* *
 * 获取$_POST $_GET 一维数组
 * $delim  不为空，则以其为间隔符号合并成字符串
 * $unique 检查是否唯一
 * $convert 类型转换,int,目前只对数字类型转换
*/
function get_array($array_name,$delim='',$unique=FALSE,$convert=''){
	$param_value = array();
	if(isset($_POST[$array_name])){
		$param_value = $_POST[$array_name];
	}else if(isset($_GET[$array_name])){
		$param_value = $_GET[$array_name];
	}
	$array = array();
	foreach ($param_value as $key=>$val) {
		$array[$key] = trim($val);
		if ('int' == $convert){
			if (strlen($param_value)>10){
				$param_value = preg_replace('/[^\d]/is','',$param_value);
			}
			else $param_value = intval($param_value);
		}
	}
	if ($unique)$array = array_flip(array_flip($array));//消除重复值，效率比array_unique()高很多
	if(!get_magic_quotes_gpc()){//加上检查数据防sql注入
		$array = sql_addslashes($array);
	}
	if(!empty($delim))return implode($delim, $array);
	return $array;
}

/**
 * 替换url中的/符号
 * */
function dealurl($url){
	$url = str_replace('\\', '/', $url);
	$url = str_replace('\\', '//', $url);
	return $url;
}

/**
 * 处理中文字符进行urlencode处理
 * $svar  数组|字符串
 */

function var_json_encode(&$svar)
{
	if(is_array($svar))
	{
		foreach($svar as $_k => $_v)
		{
			$svar[$_k] = var_json_encode($_v);
		}
	}
	else
	{
		$svar = urlencode($svar);
	}
   
	return $svar;
}	

/**
 *	返回json_encode处理后的中文字符
 */
function get_json_encode($arr)
{
	$arr = var_json_encode($arr);
	$t = json_encode($arr);
	return urldecode($t);	
}

/**
 * 随机得到icon值，用于jquery easyui的图标展示
 */
function get_icon()
{
    $icon = array('1' => 'icon-set',
                 '2' => 'icon-sys',
                 '3' => 'icon-nav',
                 '4' => 'icon-users',
                 '5' => 'icon-role',
                 '6' => 'icon-set',
                 '7' => 'icon-log',
                 '8' => 'icon-magic',
                 '9' => 'icon-database'
                 );
    $rand = rand(1,9);
    return !empty($icon[$rand]) ? $icon[$rand]: "icon-set"; 
}


//连接后台数据库
function get_base_conn(){
    require(WEBPATH_DIR."lyinclude/db.config.inc.php");
    $GLOBALS["conn"] = new DB_ZDE();//定义基础数据库的数据库连接
}


//连接hive
function get_hive_conn(){
    $transport = new TSocket('192.168.7.138', 10000);
//    $transport = new TSocket('203.195.154.197', 10000);
    $transport->setSendTimeout(600 * 1000);
    $transport->setRecvTimeout(600 * 1000);
    
    $protocol = new TBinaryProtocol($transport);
    $GLOBALS["client"] = new ThriftHiveClient($protocol);
    $transport->open();
}


 /**
     * @param date $start
     * @param date $end
     * @param array $arr
     * @param string $type  1为time()类型   2为date(Ymd)类型  3只判断起止时间 4date(Ymd)类型起止时间 5date(Y-m-d H:i:s)类型
     * @return type 
     */
    function checktime($start,$end,$type=2){
        $date  = date('Y-m-d');
        $time1 = ($start>$end)?$end:$start;
        $time2 = ($start>$end)?$start:$end;

        if($time2>$date){
           $time2 = $arr["time2"] = date('Ymd');
        }else{
            $arr["time2"] = date("Ymd",strtotime($time2)+86400);
        }
        
        if($time1>$date){
           $time1  = date("Ymd");
        }
        
        switch($type){
            case 1:
                $time1        = str_replace("-", "", $time1);
                $arr["time1"] = strtotime($time1)-1;
                $arr["time2"] = strtotime($arr["time2"]);
                break;
            case 2:
                $time1        = str_replace("-", "", $time1);
                $arr["time1"] = date("Ymd",strtotime($time1)-86400);
                $arr["time2"] = intval($arr["time2"]);
                break;
            case 5:
                $time1        = str_replace("-", "", $time1);
                $arr["time1"] = "'".date("Y-m-d H:i:s",strtotime($time1)-1)."'";
                $arr["time2"] = "'".date("Y-m-d H:i:s",strtotime($arr["time2"]))."'";
                break;
            default :
                $arr["time1"] = strtotime($time1)-1;
                //判断结束时间是否为当前日期
                if(date("Y-m-d")==$time2){
                    $arr["time2"] = THIS_DATETIME+1;
                }else{
                    $arr["time2"] = strtotime($time2." 23:59:59")+1;
                }
                break;
        }
        return $arr;
    }    


//获取当前货币兑换率
function getExchangeRate($from,$to)
{
        $amount = urlencode($amount);
        $from = urlencode($from);
        $to = urlencode($to);
        $url = "download.finance.yahoo.com/d/quotes.html?s=".$from.$to."=X&f=sl1d1t1ba&e=.html";
        $ch = curl_init();
        $timeout = 0;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $data = explode(',', $rawdata);
        return $data[1];
}

//转义
function filter($data){//add slashs for datum or data
	if(is_array($data)){
		foreach($data as $k=>$v){
			 $data[$k]=is_array($v)?filter($v):addslashes($v);
		}
	}else{
		 $data=addslashes($data);
	}
	return $data;
}

//获取两时间月份差
function getMonthNum( $date1, $date2, $tags='-' ){
  $date1_stamp=strtotime($date1);
  $date2_stamp=strtotime($date2);
  list($date_1['y'],$date_1['m'])=explode("-",date('Y-m',$date1_stamp));
  list($date_2['y'],$date_2['m'])=explode("-",date('Y-m',$date2_stamp));
  return abs(($date_2['y']-$date_1['y'])*12 +$date_2['m']-$date_1['m']);
 }

 
 /**
 *
 * @统计文件行数
 * @param string filepath 
 * @return int
 */
 function countLines($filepath) 
 {
    $handle = fopen( $filepath, "r" );
    $count = 0;
    while( fgets($handle) ) 
    {
        $count++;
    }
    fclose($handle);
    return $count;
 }

/**
 * 异步连接
 * @param int $type       发送类型，默认为POST
 * @param string $url     url地址
 * @param port $port      端口号
 * @param array $data     所需要传递的参数,当请求方式为GET时，该参数为字符串类型，反之则为数据
 * @param array $cookie   
 * @param int $timeout    超时时间,默认1秒
 * @param bool $is_return 是否需要返回值，默认为不接收返回
 * @return string|boolean
 */
function async($url, $port = 80, $data = array(), $cookie = array(), $timeout = 1,$type="POST",$is_return=FALSE){
     $info      =  parse_url($url);
     $host      =  $info['host'];
     $page      =  $info['path'] . ($info['query'] ? '?' . $info['query'] : '');
     $errno     =  $errstr = null;
     $Content 	=  array();
     
     if($type == 'POST' && !empty($data))
     { 
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $Content[] = $k . "=" . rawurlencode($v);
            }
            $Content = implode("&", $Content);
        } else {
            $Content = $data;
        }
    }
    $fp = fsockopen($host, $port, $errno, $errstr, $timeout);
    if(!$fp){
        return false;
    }
    $stream = "{$type} /{$page} HTTP/1.0\r\n";
    $stream .= "Host: {$host}\r\n";
    if ($Content && $type == 'POST') {
        $stream .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $stream .= "Content-Length: " . strlen($Content) . "\r\n";
    }
    if ($cookie && is_array($cookie)) {
        $stream .= "Connection: Close\r\n";
        $stream .= 'Cookie:';
        $tmp = array();
        foreach ($cookie as $k => $v){
           $tmp[] = $k."=".$v."; ";
        }
        $stream .= base64_encode($tmp)."\r\n\r\n";
    } else {
        $stream .= "Connection: Close\r\n\r\n";
    }

    fwrite($fp, $stream);
    if (!empty($Content)) {
        fwrite($fp, $Content);
    }
    if($is_return){
        stream_set_timeout($fp, $timeout);
        //返回值
        $res = stream_get_contents($fp);
        $info = stream_get_meta_data($fp);
        fclose($fp);
        if ($info['timed_out']) {
           return '连接超时';
        } else {
           return array(true, substr(strstr($res, "\r\n\r\n"), 4));
        }
    }else{
        fclose($fp);
        return true;
    }
}
 
 
 /*
*功能：加密解密函数
@param string 要加密字符串
@param operation 动作 DECODE 解密， ENCODE 加密
@param key 加密key
@param expiry 
*/
function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : "");
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
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
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


//转换时区
function time2date($time, $date_formate='Ymd', $from="-8", $to="-8"){
    date_default_timezone_set("Etc/GMT" . $from);
    $date = date($date_formate, $time);

    $differ = (int)$to - (int)$from;


    $todate = date($date_formate, $time-3600*$differ);
    $todate_tmp = date("Y-m-d H:i:s", $time-3600*$differ);

    date_default_timezone_set("Etc/GMT" . $to);

    $totime = strtotime($todate_tmp);

    // 重置时区
    date_default_timezone_set("Etc/GMT-8");

    return array(
        'date'   => $date,
        'totime' => $totime,
        'todate' => $todate,
        );
}


//转义字符串，用于插入数据库
function remSpecialChars($string) { 
    $string = stripslashes($string); 
    $string = eregi_replace("'","'",$string); 
    $string = eregi_replace('"','"',$string); 
    return $string; 
} 


//反转义，用于输出 
function addSpecialChars($string) { 
    $string = eregi_replace("'","'",$string); 
    $string = eregi_replace('"','"',$string); 
    return $string; 
} 

/*
 * 二维数组根据某一key排序，根据汉字编码排序
 */
function my_sort2($ArrayData,$KeyName,$order_type = "ASC")
{
	$temp_arr = array();
	foreach($ArrayData as $k => $v)
	{
		$temp_arr[$k][$KeyName] =iconv('UTF-8','GBK//IGNORE',$v[$KeyName]);
	}
	if ($order_type == 'DESC')
	{
		arsort($temp_arr);
	}else
	{
		asort($temp_arr);
	}
	$result = array();
	foreach($temp_arr as $k => $v)
	{
		$result[$k] = $ArrayData[$k];
	}
	return $result;
}


//希尔排序(比普通的冒泡排序快)
function shell_sort(&$arr)
{
    if(!is_array($arr))return;  
    $n=count($arr);
    for($gap=floor($n/2);$gap>0;$gap=floor($gap/=2))
    {
        for($i=$gap;$i<$n;++$i)
        {
            for($j=$i-$gap;$j>=0&&$arr[$j+$gap]<$arr[$j];$j-=$gap)
            {
                $temp=$arr[$j];
                $arr[$j]=$arr[$j+$gap];
                $arr[$j+$gap]=$temp;
            }
        }
    }
    return $arr;
}

//判断是否登录
function check_login(){
    if(!$_SESSION["my_admin_id"]){
        return false;
    }
    return true;
}

#获取外网IP地址
function getClientIp(){  
    $socket = socket_create(AF_INET, SOCK_STREAM, 6);  
    $ret = socket_connect($socket,'ns1.dnspod.net',6666);  
    $buf = socket_read($socket, 16);  
    socket_close($socket);  
    return $buf;      
}  

//发邮件
/**
 * param $to array
 * 保存收件人（可多个）
 * param $cc array
 * 保存抄送人（可多个）
 * $mailtitle
 * 邮件标题 $string
 * $mailcontent
 * 邮件内容 $string
 * $mailcontent2
 * 邮件不支持html时的邮件内容 $string
 */
function sendmail($to,$cc,$mailtitle,$mailcontent,$mailcontent2)
{
	ini_set("magic_quotes_runtime",0);
	include_once(WEBPATH_DIR . "class/class.phpmailer.php");//包含邮件发送文件（在class文件夹，共两个文件：class.phpmailer.php和class.smtp.php）
	try {
		$mail = new PHPMailer(true); 
		$mail->IsSMTP();
		$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
		$mail->SMTPAuth   = true;                  //开启认证
		$mail->Port       = 25;                    
		$mail->Host       = "smtp.163.com"; 
		$mail->Username   = "woshishuaa@163.com";    
		$mail->Password   = "4568520";            
		//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现"Could  not execute: /var/qmail/bin/sendmail "的错误提示
		$mail->AddReplyTo("woshishuaa@163.com","mckee");//回复地址
		$mail->From       = "woshishuaa@163.com";
		$mail->FromName   = "天拓游戏";
		
		if(!empty($to)){
			//获取收件人地址
			$charge=explode(",", $to);
			foreach($charge as $toKey =>$toVlaue){
				$mail->AddAddress($toVlaue);
			}
		}
		//获取抄送人地址
		/* foreach($cc as $ccKey =>$ccVlaue){
			$mail->AddCC($ccVlaue);
		} */

		$mail->Subject  = $mailtitle;
		$mail->Body = $mailcontent;
		$mail->AltBody    = $mailcontent2; //当邮件不支持html时备用显示，可以省略
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		//$mail->AddAttachment("f:/test.png");  //可以添加附件
		$mail->IsHTML(true); 
		$mail->Send();
		//echo '邮件已发送';
		return true;
	} catch (phpmailerException $e) {
		//echo "邮件发送失败：".$e->errorMessage();
		return false;
	}

}


function get_year($year=''){
    $cur_yy = date('Y',THIS_DATETIME);
    for($y=2010;$y<=$cur_yy;$y++){
        if($y==$year){
                $selected = "selected=\"selected\"";
        }else{
                $selected ="";
        }
        $year_str .="<option value=\"".$y."\" ".$selected.">".$y."</option>";
    }
    return $year_str;
}
//type 是否需要<<全部>>
function get_month($month='',$type=FALSE){
    $type = !empty($type)?0:1;
    for($m=$type;$m<=12;$m++){
        if($m==0 && $type==0){
            $mm = "全部";
        }else{
            if($m<10){
                    $mm = "0".$m;
            }else{
                    $mm = $m;
            }
        }
        if($m==$month){
                $selected = "selected=\"selected\"";
        }else{
                $selected ="";
        }
        $month_str .="<option value=\"".$mm."\" ".$selected.">".$mm."</option>";
    }
    return $month_str;
}

/**
 * 获取月份的第一天和最后一天（当前月的话 是当天）
 *
 * @param int $year 年份 2011
 * @param int $month 月份 01，10
 * @return array
 */
function getmonthday($year,$month){
	$Ym = $year.$month;
	$nowmonth = date('Ym',THIS_DATETIME);//当前月份
	switch ($Ym){
		case $Ym>$nowmonth:
			echo "<script>alert('请不要选择未来时间，还没有数据哦亲！');history.go(-1);</script>";
			exit();
			break;
		case $Ym==$nowmonth:
			$lastday = date('Ymd',THIS_DATETIME);
			break;
		case $Ym<$nowmonth:
			//获取月份最后一天
			$days = date('t',strtotime($year.'-'.$month.'-01'));
			$lastday = date('Ymd',mktime(0,0,0,$month,$days,$year));
			break;
	}
	//获取月份的第一天
	$firstday  = date('Ymd',mktime(0,0,0,$month,1,$year));
	return array('lastday'=>$lastday,'firstday'=>$firstday);
}


//获取远程文件大小
function remote_filesize($uri, $user = '', $pw = '') {
    ob_start();
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    if (!empty($user) && !empty($pw)) {
        $headers = array('Authorization: Basic ' . base64_encode($user . ':' . $pw));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $okay = curl_exec($ch);
    curl_close($ch);
    $head = ob_get_contents();
    ob_end_clean();

    $regex = '/Content-Length:\s([0-9].+?)\s/';
    $count = preg_match($regex, $head, $matches);

    if (isset($matches[1])) {
        $size = $matches[1];
    } else {
        $size = 'unknown';
    }
    return $size;
}


function gsys_admin_log($db,$content,$robot=1){
    $data_log = array(
        'content' => $content,
        'robot' => $robot,'time' => THIS_DATETIME,'state' => 1,'ip' => return_user_ip()
    );
    add_record($db, 'robot_log',$data_log);
}

function curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

/**
 * 获取验证码
 * 
 * @access public
 * @param int $uid
 * @return array
 */
function check_verify($uid)
{
    $ch = curl_init("http://check.ptlogin2.qq.com/check?uin={$uid}&appid=1003903&r=0.14233942252344134");
    $cookie = "confirmuin=0; ptvfsession=b1235b1729e7808d5530df1dcfda2edd94aabec43bf450d8cf037510802aa1a7dbed494c66577479895c62efa3ef35ab; ptisp=cnc";
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, temp_dir."cookie");
    curl_setopt($ch, CURLOPT_COOKIEJAR, temp_dir."cookie");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    return $data;    
}

//密码加密
function jspassword($p, $vc, $vt) {
    $p = strtoupper(md5($p));
    for ($i = 0; $i < strlen($p); $i = $i + 2) $temp .= '\x' . substr($p, $i, 2);
    return strtoupper(md5(strtoupper(md5(hex2asc($temp) . hex2asc($vt))) . $vc));
 } 
 
 //十六进制转换
 function hex2asc($str) {
    $str = join('', explode('\x', $str));
    for ($i = 0;$i < strlen($str);$i += 2) $data .= chr(hexdec(substr($str, $i, 2)));
    return $data;
 } 

/**
 * 登录
 * 
 * @access public
 * @param int $uid
 * @param string $passwd
 * @param string $verify
 * @return array
 */
function login($uid, $passwd, $verify)
{
    $url = "http://ptlogin2.qq.com/login?u={$uid}&p={$passwd}&verifycode={$verify}&webqq_type=10&remember_uin=1&login2qq=1&aid=1003903&u1=http%3A%2F%2Fweb.qq.com%2Floginproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&h=1&ptredirect=0&ptlang=2052&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=8-38-447467&mibao_css=m_webqq&t=3&g=1";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_COOKIEFILE, temp_dir."cookie");
    curl_setopt($ch, CURLOPT_COOKIEJAR, temp_dir."cookie");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    if (preg_match("/ptuiCB\('(.*)','(.*)','(.*)','(.*)','(.*)',\s'(.*)'\);/U", $data, $verify))
    {   
        return array_slice($verify, 1);
    }       
}

 /**
 * 生成配置文件
 * @param array  $data      一维数组
 * @param string $path      生成文件的路径
 * @param string $errpath   记录错误日志路径
 */
function create_file($data, $path = '', $errpath = '') {
    $exist = $file->isExists($path);
    if (!$exist) {
        $this->fileobj->touch($path);
    }
    if (!$this->fileobj->write($path, $data)) {
        $msg = "配置文件写入失败!文件名为:" . $path;
        $this->admin_log("生成文件：" . $path . "的数据库配置文件失败");
    } else {
        $this->admin_log("生成文件：" . $path . "的数据库配置文件成功");
    }
}

//文件上传方法
//返回值 ：0、文件上传失败
//返回值 ：2、文件格式不正确
//返回值 ：3、文件超出大小限制
//返回值 ：4、文件大小为0
// upurl 上传路径
function file_upload($filename,$savefilename,$upurl){
    
    //设定上传类型和文件大小
    $filetype = array("jpg", "jpeg", "gif", "pdf","txt","png");
    $filesize = 2048;
    if(!is_dir($upurl)){//判断该文件是否存在，不存在则创建
        mkdir($upurl);
    }
    $upurl .= $savefilename;
    
    $file = $_FILES[$filename];
    $file_size = ceil(filesize($file['tmp_name'])/1024);//文件大小(K)
    if($file_size>$filesize){
        return 3;
    }
    
    //如果当前文件大小为0,则提示
    if($file_size==0){
        return 4;
    }

    $filename=strtolower(basename($file['name']));//取上传文件的小写文件名
    $fileext=explode('.', $filename);//取后缀名
    $arraynum = count($fileext) - 1;
    if(!in_array($fileext[$arraynum], $filetype)){//检查文件类型  
        return 2;
    }

    //加上原文件后缀名
    $upurl = $upurl.".".$fileext[$arraynum];
    
    if (move_uploaded_file($file['tmp_name'], $upurl)) {
        return $upurl;
    } else {
        return 0;
    }
	
}


//得到菜单相关功能
/*
*db 			实例化数据
*groupid		权限组id
*
*/
function get_menue($db,$groupid){
	$menue = get_tree($db,$groupid);
	//return $menue;
	//$menue = json_decode($menue,true);
	$menue_back = '';
	foreach($menue['menus'] as $k=>$v){
		$menue_back .= '<li>';
		if(!empty($v['menus'])){
			$menue_back .= "<a href='".$v['url']."' class='menu-dropdown' target='main'><i class='menu-icon glyphicon glyphicon-tasks'></i><span class='menu-text'>".$v['menuname']."</span><i class='menu-expand'></i></a>";
			$menue_back .= "<ul class='submenu'>";
			foreach($v['menus'] as $k1=>$v1){
				if(isset($v1['child']) && count($v1['child'])){
					$menue_back.= "<li><a href='".$v1['url']."' class='menu-dropdown' target='main'><span class='menu-text'>".$v1['menuname']."</span><i class='menu-expand'></i></a><ul class='submenu'>";
					foreach($v1['child'] as $k2=>$v2){
						$menue_back.="<li>";
						$menue_back.="<a href='".$v2['url']."' class='menu-dropdown' target='main'><i class='menu-icon fa fa-rocket'></i><span class='menu-text'>".$v2['menuname']."</span></a>";
						$menue_back.="</li>";
					}
					$menue_back.= "</ul></li>";
				}else{
					$menue_back.="<li><a href='".$v1['url']."' target='main'><span class='menu-text'>".$v1['menuname']."</span></a></li>";
				}
			}
			$menue_back .= "</ul>";
		}else{
			$menue_back .= "<a href='".$v['url']."' target='main'><i class='".$v['icon']."'></i><span class='menu-text'>".$v['menuname']."</span></a>";
		}
		$menue_back .= '</li>';
	}
	return $menue_back;
}

//连接游戏数据库
function get_games_conn(){
    require(WEBPATH_DIR."lyinclude/db.count.inc.php");
    $DB = new DB_ZDE();//定义公共的数据库连接
}

//创建文字图片
function draw_img($file,$size,$msg,$fonts) {
    $width = 0;
    $height = 0;
    $offset_x = 0;
    $offset_y = 0;
    $bounds = array();
    $image = "";
    // 确定文字高度.
    $bounds = ImageTTFBBox($size, 0, $fonts, "W");
    $font_height = abs($bounds[7]-$bounds[1]);
    //$this->msg = iconv("GB2312", "UTF-8", $this->msg);
    // 确定边框高度.
    $bounds = ImageTTFBBox($size, 0, $fonts,$msg);
    $width = abs($bounds[4]-$bounds[6]);
    $height = abs($bounds[7]-$bounds[1]);
    $offset_y = $font_height;
    $offset_x = 0;
    $image = imagecreate($width+1,$height+1);
    $background = ImageColorAllocate($image, 255, 255, 255);
    $foreground = ImageColorAllocate($image, 0, 0, 0);
    ImageColorTransparent($image, $background);
    ImageInterlace($image, false);
    // 画图.
    ImageTTFText($image, $size, 0, $offset_x, $offset_y+5, $foreground, $fonts, $msg);
    // 输出为png格式
    imagePNG($image);
    //保存图片
    imagejpeg($image, $file);
}
//删除二维数组键值
function delkeys($arr){
	if(!is_array($arr)){
		return false;
	}
	//1
	$result = array_values($arr);
	foreach ($result as $k => $v) {
		$result[$k] = array_values($v);
	}
	return $result;
}

//匹配数组中是否含有关键词,匹配是否有关键词
function back_keys($arr,$str){
	foreach ($arr as $key=>$value){
		if (strpos($value,$str)!==false){
			return $key;
		}
	}
}

//随机生成唯一订单号
function StrOrderOne($param=''){
	$codes = md5(uniqid(date('YmdHis').microtime(true).str_pad(mt_rand(1, 99999), 10, '0', STR_PAD_LEFT).sprintf('d',rand(0,1000).$param)));
    return $codes;
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

?>