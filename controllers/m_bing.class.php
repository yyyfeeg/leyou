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

class M_bing extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();

		$this->assign('menu_title','绑定手机');// 栏目标题
		$this->assign('callback_url',WEBPATH_DIR_INC.'/index.php?mo=m_users&me=index');// 返回地址
	}
	
	/**
	 * 展示绑定页面
	 * @return [type] [description]
	 */
	public function index()
	{
		// 判断是否已登录
		if (!$this->check_login()) {
			show_info('','index.php?mo=m_login&me=index',1);
		}

		$type = get_param('t');//绑定类型（手机绑定或实名证）
		$result = get_param('r');//操作成功

		// 若已登录，其他页面直接跳转到首页
		if ($type == 'bp') {
			$this->assign('menu_title',"绑定手机");
		}elseif ($type == 'rz') {
			$this->assign('menu_title',"实名认证");
		}
		$this->assign('username',$this->_uname);
		$this->assign('type',$type);
		$this->assign('result',$result);
		$this->display('mobile/bing.html');
	}

	/**
	 * 绑定操作及实名认证
	 * @return [type] [description]
	 */
	public function bing()
	{
		// 判断是否已登录
		if (!$this->check_login()) {
			$res = array('code'=>'-1','msg'=>'请先登录','data'=>'');
			exit(json_encode($res));
		}

		$flag  = get_param('flag');//绑定标识
		$name  = get_param('name');//用户名
		$act   = get_param('act','int');// 操作标识符
		$tel   = get_param('tel');// 电话号码
		$code  = get_param('code');// 验证码
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
					$result = check_sms_code($this->_db,3,'',$tel,$code,60*60*24);
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
					
				// 验证真实姓名
				case 3:
					if (empty($tname)) {
						$res = array('code'=>'3001','msg'=>'参数有误！','data'=>'');
						exit(json_encode($res));
					}
					if (preg_match("[^\x80-\xff]",$tname)) {
						$res = array('code'=>'3002','msg'=>'请输入正确的姓名！','data'=>'');
						exit(json_encode($res));
					}
					// 检查是否带有违禁字
					$wordList = @file(WEBPATH_DIR."lyinclude/badword.php");
					$isTrue = false;
					foreach($wordList as $v) {
			            if( @strpos($tname,trim($v)) === 0 ) {
			                $isTrue = true;
			                break;
			            }
			        }
					if ($isTrue) {
						$res = array('code'=>'3003','msg'=>'您输入的姓名包含违禁字！','data'=>'');
						exit(json_encode($res));
					}
					$res = array('code'=>'3000','msg'=>'通过','data'=>'');
					exit(json_encode($res));
					break;

				// 验证身份证号
				case 4:
					if (empty($idnum)) {
						$res = array('code'=>'4001','msg'=>'参数有误！','data'=>'');
						exit(json_encode($res));
					}
					if (check_idnum($idnum) != 6 && check_idnum($idnum) != 7) {
						$res = array('code'=>'4002','msg'=>'请输入正确的身份证号！','data'=>'');
						exit(json_encode($res));
					}
					$res = array('code'=>'4000','msg'=>'通过','data'=>'');
					exit(json_encode($res));
					break;

				// 请求身份查询接口
				case 5:
					if (empty($idnum) || empty($tname)) {
						$res = array('code'=>'5001','msg'=>'参数有误！','data'=>'');
						exit(json_encode($res));
					}
					// 检查是否带有违禁字
					$wordList = @file(WEBPATH_DIR."lyinclude/badword.php");
					$isTrue = false;
					foreach($wordList as $v) {
			            if( @strpos($tname,trim($v)) === 0 ) {
			                $isTrue = true;
			                break;
			            }
			        }
					if ($isTrue) {
						$res = array('code'=>'5002','msg'=>'您输入的姓名包含违禁字！','data'=>'');
						exit(json_encode($res));
					}
					// 检查身份证号是否符合规则
					if (check_idnum($idnum) != 6 && check_idnum($idnum) != 7) {
						$res = array('code'=>'5003','msg'=>'请输入正确的身份证号！','data'=>'');
						exit(json_encode($res));
					}
					// 调用外部接口查询真实姓名和身份证号是否匹配
					$check_res = false;
					if ($check_res) {
						$res = array('code'=>'5004','msg'=>'实名认证失败！','data'=>'');
					} else {
						// 通过身份证获取用户出生年月日
						$ymd = get_user_birth($idnum);

						// 更新redis信息
						$GLOBALS["redis"]->lSet($this->_uname.'_info',23,$tname);
						$GLOBALS["redis"]->lSet($this->_uname.'_info',29,$idnum);
						$GLOBALS["redis"]->lSet($this->_uname.'_info',30,$ymd['m_birthyear']);
						$GLOBALS["redis"]->lSet($this->_uname.'_info',31,$ymd['m_birthmonth']);
						$GLOBALS["redis"]->lSet($this->_uname.'_info',32,$ymd['m_birthday']);

						// 切换到dcenter_count数据库
                    	cutover_db_count();
						$where = " and sysid=".$this->_uid;
                    	update_record($GLOBALS['count'],'user_info',array('ui_idnum'=>$idnum,'ui_truename'=>$tname,'ui_year'=>$ymd['m_birthyear'],'ui_month'=>$ymd['m_birthmonth'],'ui_day'=>$ymd['m_birthday']),array(),$where);

                    	$res = array('code'=>'5000','msg'=>'实名认证成功！','data'=>'');
					}
					exit(json_encode($res));
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