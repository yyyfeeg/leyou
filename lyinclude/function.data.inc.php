<?php
#==================================================
# 	FileName: function.data.inc.php
# 		Desc: 数据(数据库)操作函数文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.03
# LastChange: 
#==================================================

/**
 * 切换到dcenter_base数据库
 * @return [type] [description]
 */
function cutover_db_base()
{
	// 判断shng一个数据链接是否存在
	if (!empty($GLOBALS['count'])) $GLOBALS['count']->Close();

	$filedir = WEBPATH_DIR.'lyinclude/db.base.inc.php';
	if (file_exists($filedir)) {
		require($filedir);
		$GLOBALS['base'] = new Mysql();
		return;
	}
	exit('数据库配置文件不存在！'.$filedir);
}

/**
 * 切换到dcenter_count数据库
 * @return [type] [description]
 */
function cutover_db_count()
{
	if (!empty($GLOBALS['base'])) $GLOBALS['base']->Close();

	$filedir = WEBPATH_DIR.'lyinclude/db.count.inc.php';
	if (file_exists($filedir)) {
		require($filedir);
		$GLOBALS['count'] = new Mysql();
		return;
	}
	exit('数据库配置文件不存在！'.$filedir);
}

/**
 * 获取退出登录提示信息
 * @param  [type] $conn 数据库链接资源
 * @param  [type] $gid  游戏ID
 * @param  [type] $uaid 渠道ID
 * @param  [type] $uwid 子渠道ID
 * @return [type]       返回信息数组
 */
function get_quit_tips($conn, $gid, $uaid, $uwid=0)
{
	// 先查询具体渠道的信息
	$where = " and lt_status = 2 order by lt_printtime desc limit 1";
	$sql = "select lt_picture,lt_contents,lt_url from ".get_table('logout_tips')." where lt_gid = $gid and lt_uaid = $uaid and lt_uwid = $uwid $where";
	$query = $conn->Query($sql);
	$result = $conn->getOne($query);

	// 渠道信息为空，查询游戏下的信息
	if (empty($result)) {
		$sql = "select lt_picture,lt_contents,lt_url from ".get_table('logout_tips')." where lt_gid = $gid $where";
		$query = $conn->Query($sql);
		$result = $conn->getOne($query);
	}
	return $result;
}

/**
 * 取数据表表名
 * @param  string $tableName [表名称(省略相同前缀)]
 * @return [type]            [description]
 */
function get_table($tableName)
{
	global $TABLE_NAME_INC,$MYSQL_DB;
	return $MYSQL_DB.".`".$TABLE_NAME_INC.$tableName."`";
}

/**
 * 在数据库表$table中增加一条记录
 * @param  [type]  $conn     [数据库连接]
 * @param  [type]  $table    [数据表名称]
 * @param  array   $value    [要增加的值(键值对)]
 * @param  boolean $show_bug [调试参数,默认: false, true：返回$result['error']=SQL语句]
 * @param  integer $type     [SQL语句类型,默认: 1 采用get_table方法, 2 则要求$table为数据表的完整名称]
 * @return array   $result 	 ['id' 增加记录的ID号, 'rows' 影响的行数, 'error' 调试信息]
 */
function add_record($conn,$table,$value=array(),$show_bug=false,$type=1)
{
	$field_str = '';
	$value_str = '';
	foreach($value as $key => $val){
		if(!empty($key)){
			$field_str .= '`'.$key.'`,';
			$value_str .= "'".$val."',";
		}
	}
	$field_str = substr($field_str, 0, -1);
	$value_str = substr($value_str, 0, -1);
	if($type == 1){
		$insert_sql = "insert into ".get_table($table)." ($field_str) values($value_str);";
	}else{
		$insert_sql = "insert into ".$table." ($field_str) values($value_str);";
	}
	$conn->Query($insert_sql);

	$result = array();
	$result['id'] = $conn->InsertID();
	$result['rows'] = $conn->AffectedRows();
	if($show_bug == true){
		$result['error'] = $insert_sql;
	}
	return $result;
}

/**
 * 在数据库表$table中更新一条记录
 * @param  [type]  $conn        [数据库连接]
 * @param  [type]  $table       [数据表名称]
 * @param  array   $value       [要更新的值(键值对)]
 * @param  array   $where_value [约束条件(键值对)]
 * @param  string  $where       [约束条件]
 * @param  integer $type        [SQL语句类型,默认: 1 采用get_table方法, 2 则要求$table为数据表的完整名称]
 * @return [type]               [返回操作所影响的记录行数]
 */
function update_record($conn,$table,$value=array(),$where_value=array(),$where='',$type=1)
{
	$update_str = '';
	foreach ($value as $key => $val) {
		if(!empty($key)){
			$update_str .= "`".$key."`='".$val."',";
		}
	}
	if(empty($where)){
		foreach ($where_value as $key => $val) {
			if(!empty($key)){
				$where .= " and `".$key."` = '".$val."' ";
			}
		}
	}
	$update_str = substr($update_str, 0, -1);
	if($type == 1){
		$sql = "update ".get_table($table)." set $update_str where 1 $where;";
	}else{
		$sql = "update ".$table." set $update_str where 1 $where;";
	}

	$conn->Query($sql);
	return $conn->AffectedRows();
}

/**
 * 在数据库表$table中删除(物理删除)一条记录
 * @param  [type]  $conn        [数据库连接]
 * @param  [type]  $table       [数据表名称]
 * @param  array   $where_value [约束条件(键值对)]
 * @param  integer $type        [SQL语句类型,默认: 1 采用get_table方法, 2 则要求$table为数据表的完整名称]
 * @return [type]               [返回操作所影响的记录行数]
 */
function delete_record($conn,$table,$where_value=array(),$type=1)
{
	$where = '';
	foreach ($where_value as $key => $val) {
		if(!empty($key)){
			$where .=" and `".$key."`='".$val."' ";
		}
	}
	if($type == 1){
		$sql = "delete from ".get_table($table)." where 1 $where;";
	}else{
		$sql = "delete from ".$table." where 1 $where;";
	}
	$conn->Query($sql);
	return $conn->AffectedRows();
}

/**
 * 获取栏目数组
 * @param  [type] $conn 数据库链接资源
 * @return [type]       [description]
 */
function get_rubric_keyvalue($conn,$type=1)
{
	$tmp = array();
	$sql = "select sysid,fr_name,fr_dir,fr_template from ".get_table('frontend_rubric')." order by sysid asc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		// 获取sysid和栏目名称数组
		if ($type == 1) {
			$tmp[$rows['sysid']] = $rows['fr_name'];
		}

		// 获取sysid和栏目文件保存目录数组
		if ($type == 2) {
			$tmp[$rows['sysid']] = $rows['fr_dir'];
		}

		// 获取sysid和栏目模板名称数组
		if ($type == 3) {
			$tmp[$rows['sysid']] = $rows['fr_template'];
		}
		
	}
	return $tmp;
}


/**
 * 获取上级栏目
 * @param  [type]  $dataArr 所有栏目数组
 * @param  integer $cid     当前栏目ID
 * @return [type]           [description]
 */
function get_rubric_fid($dataArr, $cid=0)
{
	$tmp = array();
	if (is_array($dataArr)) {
		foreach ($dataArr as $value) {
			if ($value['sysid'] == $cid) {
				$tmp[] = array(
					'sysid' => $value['sysid'],
					'fr_dir' => $value['fr_dir']
					);
				$tmp = array_merge($tmp, get_rubric_fid($dataArr,$value['fr_fid']));
			}
		}
		return array_reverse($tmp);
	}
	return false;
}

/**
 * 获取所有栏目列表
 * @return [type] [description]
 */
function get_rubric($conn)
{
	$sql = "select * from ".get_table('frontend_rubric')." order by fr_order asc,sysid asc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[] = $rows;
	}
	return $tmp;
}

/**
 * 获取一个栏目文件保存目录
 * @param  [type]  $conn [description]
 * @param  integer $cid  [description]
 * @return [type]        [description]
 */
function get_rubric_dir($conn,$cid=0)
{
	$dir = '';
	$rubric_arr = get_rubric($conn);
	$rubric = get_rubric_fid($rubric_arr,$cid);
	foreach ($rubric as $value) {
		$dir .= $value['fr_dir'];
	}
	return $dir;
}

/**
 * 获取游戏服务器名称
 * @param  [type] $conn [description]
 * @return [type]       [description]
 */
function get_game_server($conn)
{
	$sql = "select gs_gid,gs_sid,gs_sname from ".get_table('game_server');
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[$rows['gs_gid']][$rows['gs_sid']] = $rows['gs_sname'];
	}
	return $tmp;
}

/**
 * 获取游戏信息数组
 * @param  [type] $conn [description]
 * @return [type]       [description]
 */
function get_game_info($conn)
{
	$sql = "select sysid,gi_gname,gi_icon,gi_virtue,gi_rating,gi_url from ".get_table('game_info')." where gi_status=1 and gi_show=1";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[$rows['sysid']] = array(
			'gname'  => $rows['gi_gname'],
			'icon'   => $rows['gi_icon'],
			'virtue' => $rows['gi_virtue'],
			'rating' => $rows['gi_rating'],
			'url'    => $rows['gi_url']
			);
	}
	return $tmp;
}

/**
 * 发送手机验证码
 * @param  [type] $conn   数据库链接资源
 * @param  [type] $smsObj 发短信实例对象
 * @param  [type] $phone  手机号码
 * @param  [type] $type   短信类型：1注册，2绑定，3取回密码
 * @return [type]         [description]
 */
function send_sms_checkcode($conn,$smsObj,$phone,$type,$uid=0,$gid=0)
{
	if (empty($phone)) {
		exit('error:手机号为空');
	}
	if (!preg_match("/1[34578]{1}\d{9}$/",$phone)) {
		exit('error:手机号格式不正确');
	}

	srand((double)microtime()*1000000);	//播下一个生成随机数字的种子
	$checkcode = rand(100000,999999);//验证码

	// 短信内容
	switch($type){
		case 1:
			$event = 1;
			$content = "验证码".$checkcode."，您正在注册天拓游戏账户，请勿将验证码告诉他人。";
			break;
		case 2:
			$event = 3;
			$content = "验证码".$checkcode."，您正在绑定手机号码，请在页面输入以完成绑定，如非本人操作请及时修改账号密码。";
			break;
		case 3:
			$event = 2;
			$content = "验证码".$checkcode."，您正在修改账户密码（天拓游戏客服绝不会向您索要验证码），如非本人操作请及时修改账户信息";
			break;
	}

	// 暂时屏蔽CRM平台的短信发送
	if ($uid == 0 || $gid == 0) return false;
	
	// 发送验证短信
	$res = $smsObj->send_sms($phone,$content);
	$resArr = json_decode($res,true);
	if ($resArr['code'] == 200) {
		$result = true;
		$data["at_status"] = 1;  //发送状态
	} else {
		$result = false;
		$data["at_status"] = 2;  //发送状态
	}
	
	// 将短信内容写入mysql
	$data["at_tel"]      = $phone;        //手机号码
	$data["at_contents"] = $checkcode;    //生成随机激活识别码
	$data["at_time"]     = time();	      //发送时间
	$data["at_ip"]       = get_user_ip(); //获取注册会员IP
	$data["at_type"]     = 0;			  //短信类型
	$data["at_uid"]      = $uid;             //操作用户 0系统
	$data["at_bulk"]     = 1;             //单发
	$data["at_gid"]      = $gid;             //0平台
	$data["at_event"]    = $event;         //事件

	add_record($conn,'attest_tel',$data);
	// 返回发送结果
	return $result;
}

/**
 * 验证手机验证码是否正确
 * @param  [type] $conn    数据库链接资源
 * @param  [type] $event   验证事件1：注册2：找回密码3：账号绑定
 * @param  [type] $phone   手机号码
 * @param  [type] $dnums   用户设备号
 * @param  [type] $code    验证码
 * @param  [type] $timeout 验证码超时时间(秒)
 * @return [type]          1.验证码超时 2.验证码错误 3.通过
 */
function check_sms_code($conn, $event, $dnums, $phone, $code, $timeout)
{
	$sql = "select * from ".get_table('attest_tel')." where at_verify = 2 and at_type = 1 and at_dnum='".$dnums."' and at_event =".$event." and at_tel = '".$phone."' order by sysid desc limit 1";
	$result = $conn->getOne($conn->Query($sql));

	if ($code != $result['at_contents']) {
		return 2;//验证码不正确
	} elseif (time()-$result['at_time'] > $timeout) {
		update_record($conn,'attest_tel',array('at_verify'=>'3'),array('sysid'=>$result['sysid']));//更新手机认证日志表
		return 1;// 验证码超时
	}
	update_record($conn,'attest_tel',array('at_verify'=>'1'),array('sysid'=>$result['sysid']));//更新手机认证日志表
	return 3;
}

/**
 * 发送邮箱验证邮件
 * @param  [type] $conn    数据库链接资源
 * @param  [type] $mailObj 邮件实例对象
 * @param  [type] $mail    邮箱地址
 * @param  [type] $type    类型，1注册，2绑定，3取回密码
 * @param  [type] $paramArr 参数数组
 * @param  [type] $key      加密key
 * @return [type]          [description]
 */
function send_email_sign($conn,$mailObj,$mail,$type,$paramArr,$key)
{
	$str  = implode(',', $paramArr);
	$code = md5($mail.time().rand(1000,999));//验证字符串
	$sign = encode_sinfo_func($str.','.$code,$key);//激活链接加密串

	$mailtitle = '天拓游戏邮箱验证(系统邮件，请勿回复)';
	//邮件发送的主内容
	switch($type) {
		//注册验证
		case 1:
			$mailcontent = "<p >您好！<br />感谢您注册天拓游戏账号，请点击下方链接进行验证您的邮箱地址<a href='".WEBPATH_DIR_INC."/index.php?mo=register&me=email_reg&sign=".$sign."' target='_blank'>".WEBPATH_DIR_INC."/index.php?mo=register&me=email_reg&sign=".$sign."</a><br />验证后即可登录天拓游戏账号，此链接24小时内有效！（如无法打开此链接，请复制粘贴到浏览器打开）</p><p >您的账号为：".$mail."</p>";
			break;

		//绑定验证
		case 2:
			$mailcontent = "<p >亲爱的&nbsp;".$_SESSION["_uname"]."：<br/>感谢支持天拓游戏，绑定邮箱可以更好的保护您的账号安全，你在遗忘密码的时候可以通过安全邮箱来找回密码。<br />你可以点击下面的链接来绑定你的安全邮箱：<br /><a href='".WEBPATH_DIR_INC."/index.php?mo=user_center&me=bing&sign=".$sign."' target='_blank'>".WEBPATH_DIR_INC."/index.php?mo=user_center&me=bing&sign=".$sign."</a><br /></p><p>（如果上面的链接无法直接点击，可以将其复制到浏览器地址栏进行验证） </p>";
			break;
			
		//取回密码
		case 3:
			$mailcontent = "<p>亲爱的&nbsp;".$_SESSION["findpwd_name"]."，您好： </p><p>请您点击下面链接来重置登录密码:<br /><a href='".WEBPATH_DIR_INC."/index.php?mo=forgetpwd&me=email_getpwd&sign=".$sign."' target='_blank'>".WEBPATH_DIR_INC."/index.php?mo=forgetpwd&me=email_getpwd&sign=".$sign."</a><br />为了确保您的帐号安全，该链接仅3天内访问有效。（如无法打开此链接，请复制粘贴到浏览器打开）</p>";
			break;
	}

	// 发送邮件
	$send_res = send_mail($mailObj,$mail,'',$mailtitle,$mailcontent,'');
	if ($send_res) {
		$data['ae_status'] = 1;
	} else {
		$data['ae_status'] = 2;
	}

	$data['ae_email']    = $mail;
	$data['ae_contents'] = $code;
	$data['ae_uid']      = 0;
	$data['ae_time']     = time();
	$data['ae_bulk']     = 1;
	$data['ae_verify']   = 2;
	$data['ae_ip']       = get_user_ip();
	$data['ae_type']     = 0;

	// 写入邮件日志表
	$res = add_record($conn, 'attest_email', $data);
	return $res['rows'];
}

/**
 * 验证邮箱验证码是否正确
 * @param  [type] $conn    数据库链接资源
 * @param  [type] $dnums   用户设备号
 * @param  [type] $email   邮箱地址
 * @param  [type] $code    验证码
 * @param  [type] $event   验证事件1:注册2：找回密码3：绑定
 * @param  [type] $timeout 验证码超时时间(秒)
 * @return [type]          1.验证码超时 2.验证码错误 3.通过
 */
function check_email_code($conn, $event, $dnums, $email, $code, $timeout)
{
	$sql = "select * from ".get_table('attest_email')." where ae_verify = 2 and ae_type = 1 and ae_dnum='".$dnums."' and ae_event=".$event." and ae_email = '".$email."' order by sysid desc limit 1";
	$result = $conn->getOne($conn->Query($sql));
	
	if (!empty($result)) {

		if (time()-$result['ae_time'] > $timeout) {
			update_record($conn,'attest_email',array('ae_verify'=>'3'),array('sysid'=>$result['sysid']));//更新邮箱认证表的状态为超时
			return 1;// 验证码超时
		} elseif ($code != $result['ae_contents']) {
			return 2;// 验证码不正确
		}
		update_record($conn,'attest_email',array('ae_verify'=>'1'),array('sysid'=>$result['sysid']));//更新邮箱认证表的状态为超时
		return 3;//通过
	} else {
		return 2;// 该邮箱的验证信息不存在
	}
}

/**
 * 获取上一篇或下一篇文章的sysid
 * @param  [type] $conn 		数据库链接资源
 * @param  [type] $flag 		标识 上一篇 up  下一篇 next
 * @param  [type] $rubricid   	当前栏目 0显示所有栏目文章
 * @param  [type] $type  		显示选项 1手机端2 PC端 3 双端
 * @param  [type] $id  			当前文章sysid
 * @return [type]       		返回文章sysid,没有则返回 no
 */
function get_up_next_pen($conn,$flag,$rubricid,$type,$id)
{
	if ($rubricid) {
		$where = ' and fe_rubricid='.$rubricid;
	}else{
		$where = '';
	}
	$sql = "select sysid from ".get_table('frontend_essay')." where fe_status=2 $where and fe_showtype in ($type,3) order by fe_order asc,sysid desc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[] = $rows['sysid'];
	}
	$key = @array_search($id, $tmp);
	if ($key !== false) {
		if ($flag == 'up') {
			$up_key = $key - 1;
			if (empty($tmp[$up_key])) {
				$result = 'NO';
			} else {
				$result = $tmp[$up_key];
			}
		} elseif ($flag == 'next') {
			$next_key = $key + 1;
			if (empty($tmp[$next_key])) {
				$result = 'NO';
			} else {
				$result = $tmp[$next_key];
			}
		}
	} else {
		$result = 'NO';
	}
	return $result;
}

/**
 * 获取用户积分及VIP等级
 * @param  [type] $conn 数据库资源
 * @param  [type] $uid  用户ID
 * @return [type]       返回一维数组
 */
function get_user_inte_vip($conn,$uid)
{
	$sql = "select ui_integral,ui_vip from ".get_table('user_info')." where sysid = $uid";
	$result = $conn->getOne($conn->Query($sql));
	return $result;
}

/**
 * 获取用户领取过的礼包ID
 * @param  [type] $conn 数据库链接资源
 * @param  [type] $uid  用户ID
 * @return [type]       返回以为数组
 */
function get_draw_spreeid($conn,$uid)
{
	$temp1 = $temp2 = array();

	// 查询领取的礼包码
	$sql = "select sysid,gfs_gid,gfs_ctypeid,gfs_keytypeid,gfs_allownum,gfs_allowday from ".get_table('frontend_spree')." as a right join (select gci_gid,gci_ctypeid,gci_keytypeid from ".get_table('cardid_info')." where gci_uid = $uid) as b on a.gfs_gid = b.gci_gid and a.gfs_ctypeid = b.gci_ctypeid and a.gfs_keytypeid = b.gci_keytypeid";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		// 判断是否达到最大领取次数
		$sql = "select gci_drawtime,count(*) as num from ".get_table('cardid_info')." where gci_draw=1 and gci_gid = ".$rows['gfs_gid']." and gci_ctypeid = ".$rows['gfs_ctypeid']." and gci_keytypeid = ".$rows['gfs_keytypeid']." and gci_uid = ".$uid." order by gci_drawtime desc";
		$res_bridle = $conn->getOne($conn->Query($sql));
		$gap_date = round((strtotime(date('Ymd',time()))-strtotime(date('Ymd',$res_bridle['gci_drawtime'])))/3600/24);
		if (!empty($res_bridle['num']) && !empty($res_bridle['gci_drawtime'])) {
			if (!empty($rows['gfs_allownum']) && !empty($rows['gfs_allowday'])) {
				if ($res_bridle['num'] < $rows['gfs_allownum'] && $gap_date > $rows['gfs_allowday']) {
					continue;
				}
			} elseif (!empty($rows['gfs_allownum']) && empty($rows['gfs_allowday'])) {
				if ($res_bridle['num'] < $rows['gfs_allownum']) {
					continue;
				}
			} elseif (empty($rows['gfs_allownum']) && !empty($rows['gfs_allowday'])) {
				if ($gap_date >= $rows['gfs_allowday']) {
					continue;
				}
			}
		}
		$temp1[] = $rows['sysid'];
	}

	// 查询领取的物品
	$sql = "select gdg_spreeid from ".get_table('drawlog_goods')." where gdg_uid = $uid";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$temp2[] = $rows['gdg_spreeid'];
	}

	return array_merge($temp1,$temp2);
}

/**
 * 获取广告主站sysid信息
 * @param  [type] $conn 数据链接资源
 * @return [type]       返回一维数组
 */
function get_adsite_uaid($conn)
{
	$tmp = array();
	$sql = "select sysid from ".get_table("game_partad")." where gp_aid=0 order by gp_inserttime desc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[] = $rows['sysid'];
	}
	return $tmp;
}

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
*  检测是否为广告用户
*  
* */
function exist_aduser($conn,$value=array()){
	$where = '';
	foreach($value as $key=>$val){
		if($key!=""){
			$where .= " AND `".$key."`='".$val."' ";
		}
	}
	$exist= "SELECT * FROM ".get_table("integral_wall")." WHERE 1 ".$where;
	$res = $conn->Query($exist);
	while ($rows = $conn->FetchArray($res)) {
		$tmp[] = $rows;
	}
	return $tmp;
}


/**
 * 敏感词过滤
 * @param  [type] $str      原字符串
 * @return [type] $fileName [敏感词库txt]
 */
function Filter_word( $str ){
    $dir = WEBPATH_DIR."admin.backend/uploads/filterwords.txt";
    $file_path = iconv('UTF-8','GB2312',$dir);
    if ( !($words = file_get_contents( $file_path )) ){
        die('file read error!');
    }
    $str = strtolower($str);
    $word = preg_replace("/[1,2,3]\r\n|\r\n/i", '|', $words);
    $matched = preg_replace('/'.$word.'/i', '***', $str);
    return $matched;
}

?>