<?php
#============================================
# 	FileName: url.class.php
# 		Desc: url处理文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.29
# LastChange: 
#============================================

class Url
{
	/**
	 * 构造函数，实例化错误信息对象
	 */
	public function __construct() {}

	/**
	 * 拼接url
	 * @param  [type] $baseURL 基础的url
	 * @param  [type] $keysArr 参数列表数组
	 * @return [type]          返回拼接的url
	 */
	public function combineURL($baseURL, $keysArr)
	{
		$valueArr = array();
		$combine = $baseURL.'?';

		if (!is_array($keysArr)) die('$keysArr not a array');

		foreach ($keysArr as $key => $value) {
			$valueArr[] = "$key=$value";
		}

		$keyStr = implode('&', $valueArr);
		$combine .= ($keyStr);
		return $combine;
	}

	/**
	 * 服务器通过get请求获得内容
	 * @param  [type] $url 请求的url,拼接后的
	 * @return [type]      请求返回的内容
	 */
	public function get_contents($url)
	{
		if (ini_get('allow_url_fopen') == '1') {
			$response = file_get_contents($url);
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			$response = curl_exec($ch);
			curl_close($ch);
		}

		if (empty($response)) {
			exit('服务器无法请求https协议或未开启curl支持');
		}
		return $response;
	}

	/**
	 * get方式请求资源
	 * @param  [type] $url     基础的url
	 * @param  [type] $keysArr 参数列表数组
	 * @return [type]          返回的资源内容
	 */
	public function get($url, $keysArr)
	{
		$combine = $this->combineURL($url, $keysArr);
		return $this->get_contents($combine);
	}

	/**
     * post
     * post方式请求资源
     * @param string $url       基础的url
     * @param array $keysArr    请求的参数列表
     * @param int $flag         标志位
     * @return string           返回的资源内容
     */
	public function post($url, $keysArr, $flag=0)
	{
		$ch = curl_init();
		if (!$flag) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr); 
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
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
	public function http($url, $params, $method='post', $protocol='http', $cookie='')
	{
		$query_string  = self::makeQueryString($params);

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

	/**
	 * 表单参数处理
	 * @param  [type] $params 表单参数，可以是array, 也可以是经过url编码之后的string
	 * @return string         返回处理后的string
	 */
	static public function makeQueryString($params)
	{
		// 字符串
		if (!is_array($params)) return $params;

		// 参数数组处理
		$query_string = array();
		foreach ($params as $key => $value)
		{
			array_push($query_string, rawurlencode($key).'='.rawurlencode($value));
		}
		$query_string = implode('&', $query_string);
		return $query_string;
	}
}
?>