<?php
#================================================================
# 	FileName: user_center.class.php
# 		Desc: 用户中心控制器文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.21
# LastChange: 
#    TestUrl: index.php?mo=user_center&me=index
#================================================================

class User_center extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();

		// 判断是否已登录
		if (!$this->check_login()) {
			show_info('','index.php?mo=login&me=index',1);
		}
	}

	/**
	 * 用户中心初始化
	 * @return [type] [description]
	 */
	public function index()
	{
		// 游戏对应的服务器名称
		$game_sever = get_game_server($this->_db);
		$game_info  = get_game_info($this->_db);

		// 最近玩过的游戏
		cutover_db_count();
		$login_game = array();
		$login_game2 = array();

		$sql = "select dg_gid,dg_logtime,dg_sid from ".get_table('gamelogin_log')." where dg_uid = ".$this->_uid." order by sysid desc limit 5";
		$query = $GLOBALS['count']->Query($sql);
		while ($rows = $GLOBALS['count']->FetchArray($query)) {
			// 最近登录
			if (count($login_game) < 4) {
				$login_game[] = array(
					'time'  => date('Y-m-d H:i:s',$rows['dg_logtime']),
					'game'  => $game_info[$rows['dg_gid']]['gname'],
					'sname' => $game_sever[$rows['dg_gid']][$rows['dg_sid']]
					); 
			}
			// 最近玩过的游戏
			$login_game2[] = array(
				'gname'  => $game_info[$rows['dg_gid']]['gname'],
				'icon'   => str_replace('..','',$game_info[$rows['dg_gid']]['icon']),
				'rating' => $game_info[$rows['dg_gid']]['rating'],
				'url'    => $game_info[$rows['dg_gid']]['url']
				);
		}

		// 用户信息
		$sql = "select * from ".get_table('user_info')." where sysid = ".$this->_uid;
		$userinfo = $GLOBALS['count']->getOne($GLOBALS['count']->Query($sql));

		// 绑定手机
		$userinfo['ui_phone'] = empty($userinfo['ui_phone'])? '':$userinfo['ui_phone'];
		$phone = array('phone'=>$userinfo['ui_phone']);

		// 绑定邮箱
		$userinfo['ui_email'] = empty($userinfo['ui_email'])? '':$userinfo['ui_email'];
		$email = array('email'=>$userinfo['ui_email']);

		// 基本信息
		$baseInfo = array(
			'ulogo'     => str_replace('..','',$userinfo['ui_ulogo']),
			'qq'        => $userinfo['ui_qq'],
			'sex'       => $userinfo['ui_sex'],
			'year'      => $userinfo['ui_year'],
			'month'     => $userinfo['ui_month'],
			'day'       => $userinfo['ui_day'],
			'marriage'  => $userinfo['ui_marriage'],
			'job'       => $userinfo['ui_job'],
			'income'    => $userinfo['ui_income'],
			'education' => $userinfo['ui_education'],
			'address'   => $userinfo['ui_address'],
			'zip'       => $userinfo['ui_zip'],
			'country'   => $userinfo['ui_country'],
			'province'  => $userinfo['ui_province'],
			'city'      => $userinfo['ui_city'],
			// 'online' => $userinfo['ui_online']
			);

		$uinfo = (in_array('',$baseInfo) or in_array('0',$baseInfo)) ? '':1;

		// 实名认证
		$idcard = array('truename'=>$userinfo['ui_truename'],'idnum'=>$userinfo['ui_idnum']);
		
		// 常见问题
		// $fileDir = WEBPATH_DIR.'/';

		$this->assign('username',$this->_uname);
		$this->assign('ulogo',$baseInfo['ulogo']);
		$this->assign('login_game',$login_game);
		$this->assign('login_game2',$login_game2);
		$this->assign('phone',$phone);
		$this->assign('email',$email);
		$this->assign('baseInfo',$baseInfo);
		$this->assign('uinfo',$uinfo);
		$this->assign('idcard',$idcard);
		$this->display('user_center.html');
	}

	/**
	 * 修改密码
	 * @return [type] [description]
	 */
	public function changePwd()
	{
		$flag   = get_param('flag');//修改密码标识
		$name   = get_param('name');//用户名
		$oldpwd = get_param('oldpwd');//旧密码
		$newpwd = get_param('newpwd');//新密码
		$renewpwd = get_param('renewpwd');//确认新密码

		if ($flag == 'changepwd' && $name == $this->_uname) {

			$newpwd_len = strlen($newpwd);
			if (empty($oldpwd)) {
				$res = array('code'=>'1001','msg'=>'请输入旧密码','data'=>'');
				exit(json_encode($res));
			}
			if (empty($newpwd)) {
				$res = array('code'=>'1002','msg'=>'请设置新密码','data'=>'');
				exit(json_encode($res));
			}
			if ($newpwd_len < 6 || $newpwd_len > 20 || !preg_match('/^\S{6,20}$/',$newpwd)) {
				$res = array('code'=>'1003','msg'=>'新密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)','data'=>'');
				exit(json_encode($res));
			}
			if (empty($renewpwd)) {
				$res = array('code'=>'1004','msg'=>'请确认新密码','data'=>'');
				exit(json_encode($res));
			}
			if ($newpwd != $renewpwd) {
				$res = array('code'=>'1005','msg'=>'新密码与确认密码不一致','data'=>'');
				exit(json_encode($res));
			}
			if ($oldpwd == $newpwd) {
				$res = array('code'=>'1006','msg'=>'新密码与旧密码相同，请设置不同的新密码','data'=>'');
				exit(json_encode($res));
			}

			// 获取当前用户信息
			$redis_uinfo = $GLOBALS['redis']->lRange($this->_uname.'_info',0,-1);

			if ($redis_uinfo) {
				$mail  = $redis_uinfo[1];
				$phone = $redis_uinfo[2];

				// 检查旧密码
				if (md5($oldpwd) == $redis_uinfo[0]) {
					// 修改密码
					$GLOBALS["redis"]->lSet($this->_uname."_info",0,md5($newpwd));

					// 同时修改绑定表中的密码
					$bind_mail_exist = $GLOBALS['redis']->exists("bind_{$mail}_info");
					if ($bind_mail_exist) {
						$band_mail_info = $GLOBALS['redis']->lRange("bind_{$mail}_info",0,-1);
						// uid一致，修改密码
						if ($redis_uinfo[12] == $band_mail_info[2]) {
							$GLOBALS["redis"]->lSet("bind_{$mail}_info",1,md5($newpwd));
						}
					}

					$bind_phone_exist = $GLOBALS['redis']->exists("bind_{$phone}_info");
					if ($bind_phone_exist) {
						$band_phone_info = $GLOBALS['redis']->lRange("bind_{$phone}_info",0,-1);
						// uid一致，修改密码
						if ($redis_uinfo[12] == $band_phone_info[2]) {
							$GLOBALS["redis"]->lSet("bind_{$phone}_info",1,md5($newpwd));
						}
					}

					// 同步mysql
					cutover_db_count();
					$mq_data = array('ui_pass'=>md5($newpwd));
					$where = " and sysid = ".$this->_uid;
	                update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where);

	                $res = array('code'=>'1000','msg'=>'修改密码成功','data'=>'');

				} else {
					$res = array('code'=>'1007','msg'=>'旧密码错误','data'=>'');
				}
				exit(json_encode($res));

			} else {
				$res = array('code'=>'0000','msg'=>'修改失败','data'=>'');
			}
			exit(json_encode($res));

		} else {
			$res = array('code'=>'0000','msg'=>'系统错误','data'=>'');
		}
		exit(json_encode($res));
	}

	/**
	 * 绑定操作及实名认证
	 * @return [type] [description]
	 */
	public function bing()
	{
		$flag  = get_param('flag');//绑定标识
		$name  = get_param('name');//用户名
		$act   = get_param('act','int');// 操作标识符
		$tel   = get_param('tel');// 电话号码
		$code  = get_param('code');// 验证码
		$mail  = get_param('mail');// 邮箱地址
		$tname = get_param('tname');// 真实姓名
		$idnum = get_param('idnum');// 身份证号码
		$sign  = get_param('sign');//验证加密串

		if (!empty($sign)) {
			$sign = decode_sinfo_func($sign,'3737@crm_tang');
			$tmp  = explode(',', $sign);
			$flag = $tmp[0];
			$name = $tmp[1];
			$act  = $tmp[2];
			$mail = $tmp[3];
			$code = $tmp[4];
		}

		if ($flag == 'bing' && $name == $this->_uname && !empty($act)) {
			switch ($act) {
				// 绑定手机
				case 1:
					if (empty($tel)) {
						$res = array('code'=>'1001','msg'=>'请输入手机号','data'=>'');
						exit(json_encode($res));
					}
					if (!preg_match("/1[34578]{1}\d{9}$/",$tel)) {
						$res = array('code'=>'1002','msg'=>'手机号码不正确','data'=>'');
						exit(json_encode($res));
					}

					// 查看该手机号是否已被注册
					$exist = $GLOBALS['redis']->sIsMember('name',$tel);
					if ($exist) {
						$res = array('code'=>'1003','msg'=>'该手机账号已被注册，不能用于绑定哦！','data'=>'');
						exit(json_encode($res));
					}

					// 查看验证码正确性
					$result = check_sms_code($this->_db,$tel,$code,60*60*24);
					if ($result == 1) {
						$res = array('code'=>'1004','msg'=>'验证码无效，请点击重新发送！','data'=>'');
					} elseif ($result == 2) {
						$res = array('code'=>'1005','msg'=>'验证码不正确！','data'=>'');
					} else {
						$res = array('code'=>'1000','msg'=>'绑定成功！绑定的手机号可用于本账号的登陆哦！','data'=>'');

						$uinfo = $GLOBALS['redis']->lRange($this->_uname.'_info',0,0);
						// 写入绑定表
						// 判断之前是否已经有绑定其他账号
						$bind_phone_exist = $GLOBALS['redis']->exists("bind_{$tel}_info");
						if ($bind_phone_exist) {
							$GLOBALS['redis']->lSet("bind_{$tel}_info",0,$this->_uname);
							$GLOBALS['redis']->lSet("bind_{$tel}_info",1,$uinfo[0]);
							$GLOBALS['redis']->lSet("bind_{$tel}_info",2,$this->_uid);
						} else {
							$GLOBALS["redis"]->lpush("bind_{$tel}_info", $this->_uname);//对应账号
							$GLOBALS["redis"]->rpush("bind_{$tel}_info", $uinfo[0]);//密码
							$GLOBALS["redis"]->rpush("bind_{$tel}_info", $this->_uid);//uid
						}

						// 同步到redis和mysql
						$GLOBALS["redis"]->lSet($this->_uname.'_info',2,$tel);

						// 切换到dcenter_count数据库
                    	cutover_db_count();
						$where = " and sysid=".$this->_uid;
                    	update_record($GLOBALS['count'],'user_info',array('ui_phone'=>$tel),array(),$where);
					}
					exit(json_encode($res));
					break;

				// 发短信
				case 2:
					if (empty($tel)) {
						$res = array('code'=>'1001','msg'=>'请输入手机号','data'=>'');
						exit(json_encode($res));
					}
					if (!preg_match("/1[34578]{1}\d{9}$/",$tel)) {
						$res = array('code'=>'1002','msg'=>'手机号码不正确','data'=>'');
						exit(json_encode($res));
					}

					// 查看该手机号是否已被注册
					$exist = $GLOBALS['redis']->sIsMember('name',$tel);
					if ($exist) {
						$res = array('code'=>'1003','msg'=>'该手机账号已被注册，不能用于绑定哦！','data'=>'');
						exit(json_encode($res));
					}
					cutover_db_base();//切换到dcenter_base数据库

					$startTime = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
					$endTime = mktime(23,59,59,date("m",time()),date("d",time()),date("Y",time()));

					$sql = "select count(*) as num from `dcenter_base`.gsys_attest_tel where at_tel = '".$phone."'";
					$queryRes = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql));
					// 累计限制10次
					if ($queryRes['num']>=10) {
						$res = array('code'=>'1004','msg'=>'系统繁忙，请稍后重试','data'=>'');
						exit(json_encode($res));
					}

					// 每天限制3次发送短信
					$sql_day = $sql." and at_time >= $startTime and at_time <= $endTime";
					$dayRes = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql_day));
					if ($dayRes['num']>=3) {
						$res = array('code'=>'1004','msg'=>'系统繁忙，请稍后重试','data'=>'');
						exit(json_encode($res));
					}

					// 是否达到重发时间
					$send_time = $GLOBALS['redis']->get($tel."_sms");
					if (empty($send_time)) {
		            	$GLOBALS['redis']->setex($tel."_sms",120,THIS_DATETIME);//设置当前重复获取验证码的时间
						$sms_res = send_sms_checkcode($GLOBALS['base'],$GLOBALS['sms'],$tel,2);//发送短信验证码

						if ($sms_res) {
							$res = array('code'=>'1000','msg'=>'验证短信已发送','data'=>'');
						} else {
							$res = array('code'=>'1004','msg'=>'系统繁忙，请稍后重试','data'=>'');
						}

					} elseif (!empty($send_time) && THIS_DATETIME - $send_time < 120) {
						$res = array('code'=>'1005','msg'=>'请求过于频繁!请稍后重试！','data'=>'');
					}
					exit(json_encode($res));
					break;

				// 发验证邮件
				case 3:
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
						$res = array('code'=>'1004','msg'=>'该邮箱已被用于注册，不能用于绑定哦！','data'=>'');
						exit(json_encode($res));
					}
					
					// 检查该邮箱是否已绑定其他账号
					$bind_exist = $GLOBALS['redis']->exists("bind_{$mail}_info");
					if ($bind_exist) {
						$account = $GLOBALS['redis']->lRange("bind_{$mail}_info",0,0);
						$mid_name = add_covert_str($account[0]);
						$res = array('code'=>'1005','msg'=>'该邮箱已绑定账号：'.$mid_name.'，请换其他邮箱','data'=>array('name'=>$mid_name));
						exit(json_encode($res));
					}

					$paramArr = array('bing',$this->_uname,4,$mail);
					// 发送验证邮件
					$sendmail = send_email_sign($GLOBALS['base'],$GLOBALS['mail'],$mail,2,$paramArr,'3737@crm_tang');
					if ($sendmail) {
						$res = array('code'=>'1000','msg'=>'验证邮件已发送，请前往查看','data'=>'');
					} else {
						$res = array('code'=>'1006','msg'=>'验证邮件发送失败，请重试','data'=>'');
					}
					exit(json_encode($res));
					break;

				// 绑定邮箱
				case 4:
					// 检查验证字段是否成功
					$check = check_email_code($GLOBALS['base'],$mail,$code,60*60*24);
					if ($check == 1) {
						show_info('绑定链接已失效','index.php?mo=user_center&me=index',4);
					} elseif ($check == 2) {
						show_info('绑定邮箱失败','index.php?mo=user_center&me=index',4);
					} elseif ($check == 3) {
						// 绑定成功
						$uinfo = $GLOBALS['redis']->lRange($this->_uname.'_info',0,0);
						// 写入绑定表
						$GLOBALS["redis"]->lpush("bind_{$mail}_info", $this->_uname);//对应账号
						$GLOBALS["redis"]->rpush("bind_{$mail}_info", $uinfo[0]);//密码
						$GLOBALS["redis"]->rpush("bind_{$mail}_info", $this->_uid);//uid
						// 同步到redis和mysql
						$GLOBALS["redis"]->lSet($this->_uname.'_info',1,$mail);

						// 切换到dcenter_count数据库
                    	cutover_db_count();
						$where = " and sysid=".$this->_uid;
                    	update_record($GLOBALS['count'],'user_info',array('ui_email'=>$mail),array(),$where);

						show_info('绑定成功！绑定的邮箱可用于本账号的登陆哦！','index.php?mo=user_center&me=index',4);
					}
					break;
				
				default:
					$res = array('code'=>'0000','msg'=>'非法请求！','data'=>'');
					exit(json_encode($res));
					break;
			}
		} else {
			$res = array('code'=>'0000','msg'=>'系统错误，请联系管理员！','data'=>'');
		}
		exit(json_encode($res));
	}
}
?>