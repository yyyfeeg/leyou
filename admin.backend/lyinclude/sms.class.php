<?php

class SMS{
	private $account; 	// 接口账号
	private $pwd;	// 接口密码

	/**
	 * 构造方法
	 * @param [type] $gid 游戏ID
	 * @param [type] $key 后台接口加密KEY
	 * @param [type] $url 后台接口地址
	 */
	public function __construct()
	{
		$this->account = "xinghui@xinghui";
		$this->pwd = 'H6FSq9X8';
	}
	
	/**
	 * 短信群发
	 * $url                     接口地址
	 * $mobiles                 群发号码，多个号码以英文逗号分隔，号码总数不超过500个
	 * $content                 短信内容，最大不超过600个字符。中英文字符均按一个字符计算
	 * $expandno                拓展码，必须是数字字符串。若不需要拓展，则留空
	 * $batchno                 批次号
	 * @return [type]           接口返回的内容
	 */
	public function MassSend($url,$mobiles,$content,$expandno="",$batchno=""){
		$timestamp=$this->time_stamp();//获取时间戳
		$pwd16=substr(md5($this->pwd),8,16);//获取16位md5加密后的密码
		$digest=md5($this->account.$pwd16.$mobiles.$content.$timestamp);//获取数据认证
		
		//接口参数信息数组
		$data=array(
			'account'=>$this->account,
			'timestamp'=>$timestamp,
			'mobiles'=>$mobiles,
			'content'=>$content,
			'expandno'=>$expandno,
			'batchno'=>$batchno,
			'digest'=>$digest
		);
		$return=$this->post_request_interface($url, $data);
		return $return;
	}
	
	/**
	 * 短信组发
	 * $url                     接口地址
	 * $groupmsg                组发消息，JSON数组
	 * $expandno                拓展码，必须是数字字符串。若不需要拓展，则留空
	 * $batchno                 批次号
	 * @return [type]           接口返回的内容
	 */
	public function GroupSend($url,$groupmsg,$expandno="",$batchno=""){
		$timestamp=$this->time_stamp();//获取时间戳
		$pwd16=substr(md5($this->pwd),8,16);//获取16位md5加密后的密码
		$digest=md5($this->account.$pwd16.$groupmsg.$timestamp);//获取数据认证
		
		//接口参数信息数组
		$data=array(
			'account'=>$this->account,
			'timestamp'=>$timestamp,
			'groupmsg'=>$groupmsg,
			'expandno'=>$expandno,
			'batchno'=>$batchno,
			'digest'=>$digest
		);
		
		$return=$this->post_request_interface($url, $data);
		return $return;
	}
	
	/**
	 * 查询余额
	 * $url                     接口地址
	 * @return [type]           接口返回的内容
	 */
	public function CheckBalance($url){
		$timestamp=$this->time_stamp();//获取时间戳
		$pwd16=substr(md5($this->pwd),8,16);//获取16位md5加密后的密码
		$digest=md5($this->account.$pwd16.$timestamp);//获取数据认证
		
		//接口参数信息数组
		$data=array(
			'account'=>$this->account,
			'timestamp'=>$timestamp,
			'digest'=>$digest
		);
		
		$return=$this->post_request_interface($url, $data);
		return $return;
	}
	
	/**
	 * 获取状态报告
	 * $url                     接口地址
	 * @return [type]           接口返回的内容
	 */
	public function GetState($url){
		$timestamp=$this->time_stamp();//获取时间戳
		$pwd16=substr(md5($this->pwd),8,16);//获取16位md5加密后的密码
		$digest=md5($this->account.$pwd16.$timestamp);//获取数据认证
		
		//接口参数信息数组
		$data=array(
			'account'=>$this->account,
			'timestamp'=>$timestamp,
			'digest'=>$digest
		);
		
		$return=$this->post_request_interface($url, $data);
		return $return;
	}
	
	/**
	 * 5、获取上行短信
	 * $url                     接口地址
	 * @return [type]           接口返回的内容
	 */
	public function GetUpSMS($url){
		$timestamp=$this->time_stamp();//获取时间戳
		$pwd16=substr(md5($this->pwd),8,16);//获取16位md5加密后的密码
		$digest=md5($this->account.$pwd16.$timestamp);//获取数据认证
		
		//接口参数信息数组
		$data=array(
			'account'=>$this->account,
			'timestamp'=>$timestamp,
			'digest'=>$digest
		);
		
		$return=$this->post_request_interface($url, $data);
		return $return;
	}
	
	/**
	 * POST方式请求远程接口
	 * $url
	 * $data
	 * $timeout
	 * @return [type]           接口返回的内容
	 */
	public function post_request_interface($url, $data, $timeout=10){
		$get_data = http_build_query($data);
	    $curl = curl_init();// 初始化一个 cURL 对象
        curl_setopt($curl, CURLOPT_URL, $url);// 设置你需要抓取的URL
        if($get_data) {
            curl_setopt($curl, CURLOPT_POST, 1);//正规的HTTP POST，设置这个选项为一个非零值
			curl_setopt($curl, CURLOPT_POSTFIELDS, $get_data);//传递一个作为HTTP "POST"操作的所有数据的字符串
        }
        curl_setopt($curl, CURLOPT_HEADER, false);//设置header
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//连接服务器时超时设置，如果服务器10秒内没有响应，脚本就会断开连接
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);//接收数据时超时设置，如果100秒内数据未接收完，直接退出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        $return = curl_exec($curl);// 运行cURL，请求网页
        if (curl_errno($curl)) {//返回一个包含当前会话错误信息的数字编号
            return curl_error($curl);//返回一个包含当前会话错误信息的字符串
        }
        curl_close($curl);//关闭一个curl会话
//		$return=$get_data;
		return $return;
	}
	
	/* 
     *  
     *返回带毫秒数的时间戳 
     */  
    public function time_stamp()  
    {  
		$time=date("Ymdhis",time());//获取格式化时间
		list($usec, $sec) = explode(" ", microtime());  
		$msec=round($usec*1000);  //获取毫秒
		return $time.$msec;
    }
	
}
?>