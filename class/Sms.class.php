<?php
#============================================
# 	FileName: Sms.class.php
# 		Desc: http短信类(运营商：耀海科技)
# 	  Author: Tang
# 	   Email: 190777721@qq.com
# 		Date: 2017.09.07
# LastChange: 
#============================================

class SMS
{
	const GROUP_SENDSMS_URL  = 'http://www.qybor.com:8500/shortMessage';
	const GROUP_SENDSMS_URL2 = 'http://www.qybor.com:8500/shortMessage';
	const GET_SMS_STATUS_URL = 'http://www.qybor.com:8500/shortMessage';
	const GET_UPLINK_SMS_URL = 'http://www.qybor.com:8500/shortMessage';

	private $username;//账号
	private $passwd;//密码
	private $sendtime;//时间戳(yyyyMMddHHmmssSSS)

	/**
	 * 构造函数
	 */
	public function __construct($sms_username,$sms_passwd)
	{
		$this->username = $sms_username;
		$this->passwd   = $sms_passwd;
		$this->sendtime = $this->time_stamp();
	}


	/**
	 * 一对一/群发短信接口
	 * (向一个或多个电话号码发送相同的短信内容)
	 * @param  array $phoneArr 群发号码,号码总数不超过500个(一维数组)
	 * @param  string $msg 短信内容,最大不超过600个字符,中英文字符均按一个字符计算
	 * @return [type]          json格式结果
	 */
	public function send_sms($phoneArr,$msg)
	{
		$phone = strpos($phoneArr,",")?implode(',',$phoneArr):$phoneArr;
		// 参数
		$params = array(
			'username'   	=> $this->username,
			// 'sendtime' 		=> $this->sendtime,
			'sendtime'		=> "",
			'phone'   		=> $phone,
			'msg'   		=> $msg."【乐游科技】",
			'needstatus' 	=> "true",
			'passwd'		=> $this->passwd,
			);
		return $this->http_sms(self::GROUP_SENDSMS_URL,$params);
	}

	/**
	 * 组发接口
	 * @param  [type] $groupmsg 组发消息,二维数组。
	 * 例：array(array('mobile'=>'123','msg'=>'内容1'),array('mobile'=>'456','msg'=>'内容2'));
	 * @return [type]           json格式结果
	 */
	public function send_sms2($groupmsgArr)
	{
		$groupmsg = json_encode($groupmsgArr);
		// 参数
		$params = array(
			'username'  => $this->username,
			'passwd'	=> $this->passwd,
			// 'sendtime' 	=> $this->sendtime,
			'sendtime'	=> "",
			'msg'   	=> $msg."【乐游科技】",
			);
		return $this->http_sms(self::GROUP_SENDSMS_URL2,$params);
	}


	/**
	 * 获取状态报告
	 * @return [type] [description]
	 */
	// public function get_sms_status()
	// {
	// 	// 参数
	// 	$params = array(
	// 		'username'   => $this->username,
	// 		'sendtime' => $this->sendtime,
	// 		'digest'    => md5($this->username.substr(md5($this->passwd),8,16).$this->sendtime)
	// 		);
	// 	return $this->http_sms(self::GET_SMS_STATUS_URL,$params);
	// }

	/**
	 * 获取上行短信
	 * @return [type] [description]
	 */
	// public function get_uplink_sms()
	// {
	// 	// 参数
	// 	$params = array(
	// 		'username'   => $this->username,
	// 		'sendtime' => $this->sendtime,
	// 		'digest'    => md5($this->username.substr(md5($this->passwd),8,16).$this->sendtime)
	// 		);
	// 	return $this->http_sms(self::GET_UPLINK_SMS_URL,$params);
	// }

	/**
	 * 获取时间戳(yyyyMMddHHmmssSSS)
	 * @return [type] [description]
	 */
	private function time_stamp()
	{
		$time = date('Ymdhis',time());
		$tmp  = explode(' ',microtime());
		$msec = round($tmp[0]*1000);
		return $time.$msec;
	}

	/**
	 * 执行一个HTTP请求
	 * @param  [type] $url      执行请求的URL地址
	 * @param  [type] $params   表单参数，可以是array, 也可以是经过url编码之后的string
	 * @param  [type] $cookie   cookie参数，可以是array, 也可以是经过拼接的string
	 * @param  string $method   请求方法 post / get
	 * @param  string $protocol http协议类型 http / https
	 * @return array            结果数组
	 */
	private function http_sms($url, $params, $method='get', $protocol='http', $cookie='')
	{
		// 字符串
		if (!is_array($params)) {
			$query_string = $params;
		} else {
			// 参数数组处理
			$query_string = http_build_query($params);
		}

		// 初始化一个cURL会话
		$ch = curl_init();
		if (strtoupper($method) == 'GET') {
			// GET请求
			curl_setopt($ch, CURLOPT_URL, "$url?$query_string");
		} else {
			// POST请求
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
		}
		// 不输出头文件
		curl_setopt($ch, CURLOPT_HEADER, false);

		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// 在发起连接前等待的时间，如果设置为0，则无限等待
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

		// 禁用http头部Expect:100-continue
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

		// cookie存在
		if (!empty($cookie)) {
			curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
		}

		// 采用https
		if ($protocol == 'https') {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		// 执行一个cURL会话
		$result = curl_exec($ch);
		$error  = curl_error($ch);

		// 错误
		if ($result === false || !empty($eror)) {
			// 返回最后一次的错误号
			$errno = curl_errno($ch);
			$info  = curl_getinfo($ch);
			curl_close($ch);
			return array('status'=>false,'errno'=>$errno,'msg'=>$error,'info'=>$info);
		}
		curl_close($ch);
		return $result;
	}
}
?>