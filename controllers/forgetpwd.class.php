<?php
#===========================================================
# 	FileName: forgetpwd.class.php
# 		Desc: 忘记密码控制器类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.01.18
# LastChange: 
#===========================================================

class Forgetpwd extends Controller
{
	/**
	 * 构造函数，初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 展示找回密码页面
	 * @return [type] [description]
	 */
	public function index()
	{
		$flag = get_param('flag');
		$name = get_param('name');
		$code = get_param('code');

		if ($flag == 'get_bind_info') {
			if (empty($name)) {
				$res = array('code'=>'1001','msg'=>'请输入账号名','data'=>'');
				exit(json_encode($res));
			}
			if (empty($code)) {
				$res = array('code'=>'1002','msg'=>'请输入验证码','data'=>'');
				exit(json_encode($res));
			}
			if ($_SESSION['code'] != $code) {
				$res = array('code'=>'1002','msg'=>'验证码不正确','data'=>'');
				exit(json_encode($res));
			}
			// 查询账号绑定信息
			$user_info = $GLOBALS['redis']->lRange("{$name}_info",0,-1);
			if (!empty($user_info)) {
				$bind_phone = empty($user_info[2])? 0:add_covert_str($user_info[2]);
				$bind_email = empty($user_info[1])? 0:add_covert_str($user_info[1]);

				$str = $name.','.$user_info[1].','.$user_info[2].','.getRandChar(rand(5,20));
				$sign = encode_sinfo_func($str,'3737@crm_tang');

				// session记录信息
				$_SESSION['findpwd_name'] = $name;
				$_SESSION['bind_phone']   = $bind_phone;
				$_SESSION['bind_email']   = $bind_email;
				$_SESSION['SIGN']         = $sign;

				$res = array('code'=>'1000','msg'=>'成功','data'=>'');
				exit(json_encode($res));
			} else {
				$res = array('code'=>'1003','msg'=>'账号不存在','data'=>'');
				exit(json_encode($res));
			}
		} else {
			$this->assign('step','step1');
			$this->assign('flag','input_account');
		}
		$this->display('forgetpassword.html');
	}

	/**
	 * 选择找回方式
	 * @return [type] [description]
	 */
	public function choose_way()
	{
		$flag = get_param('flag');
		$name = $_SESSION['findpwd_name'];
		$sign = $_SESSION['SIGN'];
		$bind_phone = $_SESSION['bind_phone'];
		$bind_email = $_SESSION['bind_email'];

		if (!empty($name) && !empty($sign)) {
			switch ($flag) {
				// 通过手机找回
				case 'phone_way':
					$this->assign('flag','phone_way');
					$this->assign('name',$name);
					$this->assign('phone',$bind_phone);
					$this->assign('step','step3');
					break;
				
				// 通过邮箱找回
				case 'email_way':
					$this->assign('flag','email_way');
					$this->assign('name',$name);
					$this->assign('mail',$bind_email);
					$this->assign('step','step3');

					// 获取当前用户信息
					$user_info = $GLOBALS['redis']->lRange("{$name}_info",0,-1);
					// 发送验证邮件
					$paramArr = array('setpwd','step4',$name,$user_info[1]);
					$sendmail = send_email_sign($this->_db,$GLOBALS['mail'],$user_info[1],3,$paramArr,'3737@crm_tang');
					break;

				default:
					$this->assign('flag','change_find_way');
					$this->assign('name',$name);
					$this->assign('bind_phone',$bind_phone);
					$this->assign('bind_email',$bind_email);
					$this->assign('step','step2');
					break;
			}

		} else {
			show_info('','index.php?mo=forgetpwd&me=index',1);
		}

		$this->display('forgetpassword.html');
	}

	/**
	 * 通过绑定的手机号找回密码
	 * @return [type] [description]
	 */
	public function phone_getpwd()
	{
		$flag = get_param('flag');
		$step = get_param('step');
		$name = $_SESSION['findpwd_name'];
		$type = get_param('type','int');
		$code = get_param('code');
		$sign = $_SESSION['SIGN'];

		if (empty($sign)) {
			$res = array('code'=>'0000','msg'=>'非法操作','data'=>'');
			exit(json_encode($res));
		} else {
			$sign = decode_sinfo_func($sign,'3737@crm_tang');
			$tmp  = explode(',', $sign);
			$true_name = $tmp[0];
			if ($name != $true_name) {
				$res = array('code'=>'0000','msg'=>'非法操作','data'=>'');
				exit(json_encode($res));
			}
		}

		if ($flag == 'phone_getpwd' && $step == 'step3' && !empty($name) && !empty($type)) {
			// 查询用户信息
			$user_info = $GLOBALS['redis']->lRange("{$name}_info",0,-1);
			$phone = $user_info[2];
			switch ($type) {
				// 验证验证正确性
				case '1':
					if (empty($code)) {
						$res = array('code'=>'1001','msg'=>'请输入验证码','data'=>'');
						exit(json_encode($res));
					}
					if (!preg_match('/^\d{6}$/',$code)) {
						$res = array('code'=>'1002','msg'=>'验证码为6位数字','data'=>'');
						exit(json_encode($res));
					}
					cutover_db_base();//切换到dcenter_base数据库
					$check_res = check_sms_code($GLOBALS['base'],2,'',$phone,$code,60*30);
					if ($check_res == 1) {
						$res = array('code'=>'1003','msg'=>'验证码无效，请重新获取！','data'=>'');
					} elseif ($check_res == 2) {
						$res = array('code'=>'1004','msg'=>'验证码错误','data'=>'');
					} elseif ($check_res == 3) {
						$res = array('code'=>'1000','msg'=>'验证成功','data'=>'');

						// 修改sign
						$str = '*@pass_phone_check@*,'.$name.','.$user_info[1].','.$user_info[2].','.getRandChar(rand(10,20));
						$sign = encode_sinfo_func($str,'3737@crm_tang&pass');
						$_SESSION['SIGN'] = $sign;
					}
					exit(json_encode($res));
					break;
				
				// 发送短信验证码
				case '2':
					if (!empty($phone)) {
						cutover_db_base();//切换到dcenter_base数据库
						$startTime = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
						$endTime = mktime(23,59,59,date("m",time()),date("d",time()),date("Y",time()));

						$sql = "select count(*) as num from `dcenter_base`.gsys_attest_tel where at_tel = '".$phone."'";
						$queryRes = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql));
						// 累计限制10次
						if ($queryRes['num']>=10) {
							$res = array('code'=>'2001','msg'=>'系统繁忙，请稍后重试','data'=>'');
							exit(json_encode($res));
						}

						// 每天限制3次发送短信
						$sql_day = $sql." and at_time >= $startTime and at_time <= $endTime";
						$dayRes = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql_day));
						if ($dayRes['num']>=3) {
							$res = array('code'=>'2001','msg'=>'系统繁忙，请稍后重试','data'=>'');
							exit(json_encode($res));
						}

						// 是否达到重发时间
						$send_time = $GLOBALS['redis']->get($phone."_sms");
						if (empty($send_time)) {
							
			            	$GLOBALS['redis']->setex($phone."_sms",120,THIS_DATETIME);//设置当前重复获取验证码的时间
							$sms_res = send_sms_checkcode($GLOBALS['base'],$GLOBALS['sms'],$phone,3);//发送短信验证码

							if ($sms_res) {
								$res = array('code'=>'2000','msg'=>'验证短信已发送','data'=>'');
							} else {
								$res = array('code'=>'2001','msg'=>'系统繁忙，请稍后重试','data'=>'');
							}

						} elseif (!empty($send_time) && THIS_DATETIME - $send_time < 120) {
							$res = array('code'=>'2002','msg'=>'请求过于频繁!请稍后重试！','data'=>'');
						}
					} else {
						$res = array('code'=>'2003','msg'=>'系统错误!请联系客服！','data'=>'');
					}
					exit(json_encode($res));
					break;
			}

		} else {
			$res = array('code'=>'0000','msg'=>'系统错误!请联系客服！','data'=>'');
			exit(json_encode($res));
		}

		if ($type != 1) {
			$this->assign('step','step1');
			$this->assign('flag','set_new_pwd');
		}
		
		$this->display('forgetpassword.html');
	}

	/**
	 * 通过绑定的邮箱找回密码
	 * @return [type] [description]
	 */
	public function email_getpwd()
	{
		$sign = get_param('sign');
		if (!empty($sign)) {
			$sign = decode_sinfo_func($sign,'3737@crm_tang');
			$tmp  = explode(',', $sign);
			$flag = $tmp[0];
			$step = $tmp[1];
			$name = $tmp[2];
			$mail = $tmp[3];
			$code = $tmp[4];

			// 验证邮件验证码
			$check = check_email_code($this->_db,$mail,$code,60*60*24);
			if ($check == 1) {
				show_info('激活链接已失效','index.php?mo=forgetpwd&me=index',4);
			} elseif ($check == 2) {
				show_info('邮箱验证失败','index.php?mo=forgetpwd&me=index',4);
			} elseif ($check == 3) {
				// 查询用户信息
				$user_info = $GLOBALS['redis']->lRange("{$name}_info",0,-1);
				// 修改sign
				$str = '*@pass_email_check@*,'.$name.','.$user_info[1].','.$user_info[2].','.getRandChar(rand(10,20));
				$sign = encode_sinfo_func($str,'3737@crm_tang&pass');
				$_SESSION['SIGN'] = $sign;

				$this->assign('flag',$flag);
				$this->assign('step',$step);
				$this->assign('name',$name);
			}

		} else {
			show_info('链接地址不正确','index.php?mo=forgetpwd&me=index',4);
		}

		$this->display('forgetpassword.html');
	}

	/**
	 * 设置新的密码
	 */
	public function set_newpwd()
	{
		$flag  = get_param('flag');
		$step  = get_param('step');
		$pwd   = get_param('pwd');
		$repwd = get_param('repwd');
		$sign  = $_SESSION['SIGN'];

		if (empty($sign)) {
			$res = array('code'=>'1001','msg'=>'非法操作','data'=>'');
			exit(json_encode($res));
		} else {
			$sign = decode_sinfo_func($sign,'3737@crm_tang&pass');
			$tmp  = explode(',', $sign);
			$name = $tmp[1];
			$mail = $tmp[2];
			$phone = $tmp[3];
		}

		if ($flag == 'set_newpwd' && $step == 'step4' && !empty($name) && !empty($sign)) {
			$pwd_len = strlen($pwd);
			if (empty($pwd)) {
				$res = array('code'=>'1002','msg'=>'请设置新密码','data'=>'');
				exit(json_encode($res));
			}
			if (empty($repwd)) {
				$res = array('code'=>'1003','msg'=>'请确认新密码','data'=>'');
				exit(json_encode($res));
			}
			if ($pwd_len < 6 || $pwd_len > 20 || !preg_match('/^\S{6,20}$/',$pwd)) {
				$res = array('code'=>'1004','msg'=>'新密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)','data'=>'');
				exit(json_encode($res));
			}
			if ($pwd != $repwd) {
				$res = array('code'=>'1005','msg'=>'新密码与确认密码不一致','data'=>'');
				exit(json_encode($res));
			}

			// 查看用户信息
			$user_info = $GLOBALS['redis']->lRange("{$name}_info",0,-1);

			// 修改redis用户表密码
			$GLOBALS["redis"]->lSet("{$name}_info",0,md5($pwd));

			// 同时修改绑定表中的密码
			$bind_mail_exist = $GLOBALS['redis']->exists("bind_{$mail}_info");
			if ($bind_mail_exist) {
				$band_mail_info = $GLOBALS['redis']->lRange("bind_{$mail}_info",0,-1);
				// uid一致，修改密码
				if ($user_info[12] == $band_mail_info[2]) {
					$GLOBALS["redis"]->lSet("bind_{$mail}_info",1,md5($pwd));
				}
			}

			$bind_phone_exist = $GLOBALS['redis']->exists("bind_{$phone}_info");
			if ($bind_phone_exist) {
				$band_phone_info = $GLOBALS['redis']->lRange("bind_{$phone}_info",0,-1);
				// uid一致，修改密码
				if ($user_info[12] == $band_phone_info[2]) {
					$GLOBALS["redis"]->lSet("bind_{$phone}_info",1,md5($pwd));
				}
			}

			// 同步mysql
			cutover_db_count();
			$mq_data = array('ui_pass'=>md5($pwd));
			$where = " and sysid = ".$user_info[12];
            update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where);

            $res = array('code'=>'1000','msg'=>'修改密码成功','data'=>'');
            exit(json_encode($res));
		}

		$this->assign('flag','setpwd');
		$this->assign('step','step4');
		$this->assign('name',$_SESSION['findpwd_name']);
		$this->display('forgetpassword.html');
	}

	/**
	 * 设置新密码成功
	 * @return [type] [description]
	 */
	public function setpwd_success()
	{
		// session记录信息
		$_SESSION['findpwd_name'] = '';
		$_SESSION['bind_phone']   = '';
		$_SESSION['bind_email']   = '';
		$_SESSION['SIGN']         = '';
		unset($_SESSION['findpwd_name']);
		unset($_SESSION['bind_phone']);
		unset($_SESSION['bind_email']);
		unset($_SESSION['SIGN']);

		$this->assign('flag','setpwd_success');
		$this->assign('step','step5');
		$this->assign('name',$_SESSION['findpwd_name']);
		$this->display('forgetpassword.html');
	}
}
?>