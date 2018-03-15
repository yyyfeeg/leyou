<?php
#=======================================
# 	FileName: register.class.php
# 		Desc: 注册控制器类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.01.05
# LastChange: 
#=======================================

class Register extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		cutover_db_count();//切换到count数据库
	}

	/**
	 * 展示注册页面
	 * @return [type] [description]
	 */
	public function index()
	{
		$type = get_param('t');//注册类型
		$step = get_param('s');//步骤
		$name = get_param('n');//账号
		$result = get_param('r');//注册成功

		// 若已登录，其他页面直接跳转到首页
		if ($result != 'success') {
			if ($this->check_login()) {
				show_info('','index.php',1);
			}
		}

		$this->assign('type',$type);
		$this->assign('step',$step);
		$this->assign('name',$name);
		$this->assign('result',$result);
		$this->display('register.html');
	}

	/**
	 * 自定义注册
	 * @return [type] [description]
	 */
	public function custom_reg()
	{
		$name  = get_param('name');//账号
		$pwd   = get_param('pwd');//密码
		$repwd = get_param('repwd');//确认密码
		$code  = get_param('code');//验证码
		$flag  = get_param('flag');//注册类型标识

		if ($flag == 'custom') {

			$name_len = strlen($name);
			$pwd_len  = strlen($pwd);

			if ($name_len < 6 || $name_len > 20 || !preg_match('/^[a-zA-Z]{1}([a-zA-Z0-9]){5,19}$/',$name)) {
				$res = array('code'=>'1001','msg'=>'账号为6-20个字符，字母和数字的组合，请以英文字母开头','data'=>'');
				exit(json_encode($res));
			}
			if ($pwd_len < 6 || $pwd_len > 20 || !preg_match('/^\S{6,20}$/',$pwd)) {
				$res = array('code'=>'1002','msg'=>'密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)','data'=>'');
				exit(json_encode($res));
			}
			if ($pwd != $repwd) {
				$res = array('code'=>'1003','msg'=>'确认密码与密码不匹配','data'=>'');
				exit(json_encode($res));
			}
			if ($code != $_SESSION['reg_code']) {
				$res = array('code'=>'1004','msg'=>'验证码不正确','data'=>'');
				exit(json_encode($res));
			}

			// 查看redis中是否存在该用户
			$exist = $GLOBALS['redis']->sadd('name',$name);

			if ($exist) {// 注册成功
				
				//同步用户信息到mySQL
	            $mq_data = array(
	                "ui_name"     => $name,
	                "ui_pass"     => md5($pwd),
	                "ui_email"    => "",
	                "ui_phone"    => "",
	                "ui_regtime"  => THIS_DATETIME,
	                "ui_gid"      => '',
	                "ui_uaid"     => '',
	                "ui_uwid"     => '',
	                "ui_mac"      => '',
	                "ui_idfa"     => '',
	                "ui_imei"     => '',
	                "ui_lasttime" => THIS_DATETIME,
	                "ui_lastip"   => get_user_ip(),
	                "ui_utype"    => 1,
	                "ui_source"   => 1,
	                "ui_mark"     => ''
	            );
	            // 获取当前ID号
	            $mq_res = add_record($GLOBALS['count'],"user_info",$mq_data);

	            // 写入redis
	            $user_info = array(md5($pwd),'','',THIS_DATETIME,THIS_DATETIME,'','','','','','',get_user_ip(),$mq_res["id"],1,1,'','','','','','','','','','','','','','','','','','','','','','','','','');
	            userinfo_write_redis($GLOBALS['redis'],"{$name}_info",$user_info);

	            // 将用户信息写入session,默认注册成功就登录
	            $_SESSION['_uid'] = $mq_res["id"];
	            $_SESSION['_uname'] = $name;
	            $_SESSION['_truename'] = '';

	            // 返回数据
	            $res = array('code'=>'1000','msg'=>'注册成功','data'=>'');

			} else {
				// 注册失败
				$res = array('code'=>'1005','msg'=>'注册失败，用户名已存在！','data'=>'');
			}
		} else {
			$res = array('code'=>'1006','msg'=>'系统错误','data'=>'');
		}
		exit(json_encode($res));
	}

	/**
	 * 手机号注册
	 * @return [type] [description]
	 */
	public function mobile_reg()
	{
		$flag  = get_param('flag');//注册类型标识
		$step  = get_param('step','int');//步骤
		$phone = get_param('phone');//手机号码
		$code  = get_param('code');//验证码
		$pwd   = get_param('pwd');//密码
		$repwd = get_param('repwd');//确认密码

		// 检查手机号的正确性
		if (empty($phone)) {
			$res = array('code'=>'1001','msg'=>'请填写手机号','data'=>'');
			exit(json_encode($res));
		}
		if (!preg_match("/1[34578]{1}\d{9}$/",$phone)) {
			$res = array('code'=>'1002','msg'=>'手机号格式不正确','data'=>'');
			exit(json_encode($res));
		}

		// 查看该手机号是否已被注册
		$exist = $GLOBALS['redis']->sIsMember('name',$phone);
		if ($exist) {
			$res = array('code'=>'1003','msg'=>'该手机账号已存在，请直接登录','data'=>'');
			exit(json_encode($res));
		}
		
		// 查看该手机号是否绑定其他账号
		$bind_exist = $GLOBALS['redis']->exists("bind_{$phone}_info");
		if ($bind_exist) {
			$account = $GLOBALS['redis']->lRange("bind_{$phone}_info",0,0);
			$mid_name = add_covert_str($account[0]);
			$res = array('code'=>'1004','msg'=>'该手机号已绑定账号：'.$mid_name.'，可直接登录','data'=>array('name'=>$mid_name));
			exit(json_encode($res));
		}
		
		if ($flag == 'mobile') {
			switch ($step) {
				// 检查短信验证码正确性
				case 1:
					if (empty($code)) {
						$res = array('code'=>'1101','msg'=>'请输入验证码','data'=>'');
						exit(json_encode($res));
					}
					if (!preg_match('/^\d{6}$/',$code)) {
						$res = array('code'=>'1102','msg'=>'验证码为6位数字','data'=>'');
						exit(json_encode($res));
					}
					cutover_db_base();//切换到dcenter_base数据库
					$check_res = check_sms_code($GLOBALS['base'],1,'',$phone,$code,60*30);
					if ($check_res == 1) {
						$res = array('code'=>'1103','msg'=>'验证码超时','data'=>'');
					} elseif ($check_res == 2) {
						$res = array('code'=>'1104','msg'=>'验证码错误','data'=>'');
					} elseif ($check_res == 3) {
						$res = array('code'=>'1105','msg'=>'通过','data'=>array('phone'=>$phone));
						$GLOBALS['redis']->sAdd('pass_phone',$phone);
					}
					exit(json_encode($res));
					break;
				
				// 检查该手机号是否已验证，后设置登录密码
				case 2:
					if (empty($pwd)) {
						$res = array('code'=>'1201','msg'=>'请输入密码','data'=>'');
						exit(json_encode($res));
					}
					if ($pwd != $repwd) {
						$res = array('code'=>'1202','msg'=>'确认密码与密码不匹配','data'=>'');
						exit(json_encode($res));
					}

					$exist_phone = $GLOBALS['redis']->sIsMember('pass_phone',$phone);
					if ($exist_phone) {
						// 添加到redis账号表
						$GLOBALS['redis']->sadd('name',$phone);

						// 同步用户信息到mysql
						 $mq_data = array(
			                "ui_name"     => $phone,
			                "ui_pass"     => md5($pwd),
			                "ui_email"    => "",
			                "ui_phone"    => $phone,
			                "ui_regtime"  => THIS_DATETIME,
			                "ui_gid"      => '',
			                "ui_uaid"     => '',
			                "ui_uwid"     => '',
			                "ui_mac"      => '',
			                "ui_idfa"     => '',
			                "ui_imei"     => '',
			                "ui_lasttime" => THIS_DATETIME,
			                "ui_lastip"   => get_user_ip(),
			                "ui_utype"    => 1,
			                "ui_source"   => 1,
			                "ui_mark"     => ''
			            );
			            // 获取当前ID号
			            $mq_res = add_record($GLOBALS['count'],"user_info",$mq_data);

			            // 用户信息写入redis
			            $user_info = array(md5($pwd),'',$phone,THIS_DATETIME,THIS_DATETIME,'','','','','','',get_user_ip(),$mq_res["id"],1,1,'','','','','','','','','','','','','','','','','','','','','','','','','');
			            userinfo_write_redis($GLOBALS['redis'],"{$phone}_info",$user_info);

			            // 将用户信息写入session,默认注册成功就登录
			            $_SESSION['_uid'] = $mq_res["id"];
			            $_SESSION['_uname'] = $phone;
			            $_SESSION['_truename'] = '';

						$res = array('code'=>'1200','msg'=>'注册成功','data'=>'');
					} else {
						$res = array('code'=>'1203','msg'=>'注册失败，请重试！','data'=>'');
					}
					exit(json_encode($res));
					break;
			}
		} elseif ($flag == 'sendsms') {
			cutover_db_base();//切换到dcenter_base数据库

			$startTime = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
			$endTime = mktime(23,59,59,date("m",time()),date("d",time()),date("Y",time()));

			$sql = "select count(*) as num from `dcenter_base`.gsys_attest_tel where at_tel = '".$phone."'";
			$queryRes = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql));
			// 累计限制10次
			if ($queryRes['num']>=10) {
				$res = array('code'=>'1007','msg'=>'系统繁忙，请稍后重试','data'=>'');
				exit(json_encode($res));
			}

			// 每天限制3次发送短信
			$sql_day = $sql." and at_time >= $startTime and at_time <= $endTime";
			$dayRes = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql_day));
			if ($dayRes['num']>=3) {
				$res = array('code'=>'1007','msg'=>'系统繁忙，请稍后重试','data'=>'');
				exit(json_encode($res));
			}

			// 是否达到重发时间
			$send_time = $GLOBALS['redis']->get($phone."_sms");
			if (empty($send_time)) {
				
            	$GLOBALS['redis']->setex($phone."_sms",120,THIS_DATETIME);//设置当前重复获取验证码的时间
				$sms_res = send_sms_checkcode($GLOBALS['base'],$GLOBALS['sms'],$phone,1);//发送短信验证码

				if ($sms_res) {
					$res = array('code'=>'1005','msg'=>'验证短信已发送','data'=>'');
				} else {
					$res = array('code'=>'1006','msg'=>'系统繁忙，请稍后重试','data'=>'');
				}

			} elseif (!empty($send_time) && THIS_DATETIME - $send_time < 120) {
				$res = array('code'=>'1007','msg'=>'请求过于频繁!请稍后重试！','data'=>'');
			}
			exit(json_encode($res));

		} else {
			$res = array('code'=>'0000','msg'=>'系统错误','data'=>'');
		}
		exit(json_encode($res));
	}

	/**
	 * 邮箱注册
	 * @return [type] [description]
	 */
	public function email_reg()
	{
		$flag = get_param('flag');//注册类型标识
		$step = get_param('step');//步骤
		$mail = get_param('mail');//邮箱
		$code = get_param('code');//验证码
		$sign = get_param('sign');//验证激活链接加密串
		$pwd  = get_param('pwd');//密码
		$repwd = get_param('repwd');//确认密码

		cutover_db_base();//切换dcenter_base数据库

		// 解密拆分sign加密串
		if (!empty($sign)) {
			$sign = decode_sinfo_func($sign,'3737@crm_tang');
			$tmp  = explode(',', $sign);
			$flag = $tmp[0];
			$step = $tmp[1];
			$mail = $tmp[2];
			$code = $tmp[3];
		}
		if ($flag == 'email') {
			switch ($step) {
				case 1:
					// 检查邮箱的正确性
					if (empty($mail)) {
						$res = array('code'=>'1001','msg'=>'请填写邮箱','data'=>'');
						exit(json_encode($res));
					}
					if (!preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+.[a-z]{2,4}/",$mail)) {
						$res = array('code'=>'1002','msg'=>'邮箱格式不正确','data'=>'');
						exit(json_encode($res));
					}
					if (strlen($mail) > 40) {
						$res = array('code'=>'1003','msg'=>'邮箱地址不应超过40个字符','data'=>'');
						exit(json_encode($res));
					}
					// 检查该邮箱是否已被注册
					$exist_mail = $GLOBALS['redis']->sIsMember('name',$mail);
					if ($exist_mail) {
						$res = array('code'=>'1004','msg'=>'邮箱账号已存在，请直接登录','data'=>'');
						exit(json_encode($res));
					}
					
					// 检查该邮箱是否已绑定其他账号
					$bind_exist = $GLOBALS['redis']->exists("bind_{$mail}_info");
					if ($bind_exist) {
						$account = $GLOBALS['redis']->lRange("bind_{$mail}_info",0,0);
						$mid_name = add_covert_str($account[0]);
						$res = array('code'=>'1005','msg'=>'该邮箱已绑定账号：'.$mid_name.'，可直接登录','data'=>array('name'=>$mid_name));
						exit(json_encode($res));
					}

					$paramArr = array('email',2,$mail);
					// 发送验证邮件
					$sendmail = send_email_sign($GLOBALS['base'],$GLOBALS['mail'],$mail,1,$paramArr,'3737@crm_tang');
					if ($sendmail) {
						$res = array('code'=>'1000','msg'=>'验证邮件已发送，请前往查看','data'=>'');
					} else {
						$res = array('code'=>'1006','msg'=>'验证邮件发送失败，请重试','data'=>'');
					}
					exit(json_encode($res));
					break;
				
				case 2:
					// 检查验证字段是否成功
					$check = check_email_code($GLOBALS['base'],$mail,$code,60*60*24);
					if ($check == 1) {
						show_info('激活链接已失效','index.php',4);
					} elseif ($check == 2) {
						show_info('邮箱验证失败','index.php',4);
					} elseif ($check == 3) {
						$GLOBALS['redis']->sAdd('pass_mail',$mail);
						show_info('','index.php?mo=register&me=index&t=email&s=2&n='.$mail,1);
					}
					break;

				case 3:
					// 设置登录密码
					if (empty($pwd)) {
						$res = array('code'=>'2003','msg'=>'请设置密码','data'=>'');
						exit(json_encode($res));
					}
					if ($pwd != $repwd) {
						$res = array('code'=>'2004','msg'=>'确认密码与密码不匹配','data'=>'');
						exit(json_encode($res));
					}

					// 查看redis邮箱验证表
					$exist_mail = $GLOBALS['redis']->sIsMember('pass_mail',$mail);
					if ($exist_mail) {
						// 添加到redis账号表
						$GLOBALS['redis']->sadd('name',$mail);

						// 同步用户信息到mysql
						 $mq_data = array(
			                "ui_name"     => $mail,
			                "ui_pass"     => md5($pwd),
			                "ui_email"    => $mail,
			                "ui_phone"    => "",
			                "ui_regtime"  => THIS_DATETIME,
			                "ui_gid"      => '',
			                "ui_uaid"     => '',
			                "ui_uwid"     => '',
			                "ui_mac"      => '',
			                "ui_idfa"     => '',
			                "ui_imei"     => '',
			                "ui_lasttime" => THIS_DATETIME,
			                "ui_lastip"   => get_user_ip(),
			                "ui_utype"    => 1,
			                "ui_source"   => 1,
			                "ui_mark"     => ''
			            );

						cutover_db_count();//切换到count数据库

			            // 获取当前ID号
			            $mq_res = add_record($GLOBALS['count'],"user_info",$mq_data);

			            // 写入redis
			            $user_info = array(md5($pwd),$mail,'',THIS_DATETIME,THIS_DATETIME,'','','','','','',get_user_ip(),$mq_res["id"],1,1,'','','','','','','','','','','','','','','','','','','','','','','','','');
			            userinfo_write_redis($GLOBALS['redis'],"{$mail}_info",$user_info);

			            // 将用户信息写入session,默认注册成功就登录
			            $_SESSION['_uid'] = $mq_res["id"];
			            $_SESSION['_uname'] = $mail;
			            $_SESSION['_truename'] = '';

						$res = array('code'=>'2000','msg'=>'注册成功','data'=>'');
					} else {
						$res = array('code'=>'2005','msg'=>'注册失败，请重试！','data'=>'');
					}
					exit(json_encode($res));
					break;
			}
		} else {
			$res = array('code'=>'0000','msg'=>'系统错误','data'=>'');
		}
		exit(json_encode($res));
	}
}
?>