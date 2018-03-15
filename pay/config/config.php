<?php
ini_set('default_charset', 'utf-8');
class Config{
    public $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        'mchId'=>'175510359638',
        'key'=>'61307e5f2aebcacecbcca6fe5296df9c',
        'version'=>'1.0'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }

    //支付类型
    public $pifg = array(
    	//微信 -- 威富通
    	1 => array(
    		'url'=>'https://pay.swiftpass.cn/pay/gateway',
        	'mchId'=>'175510359638',
        	'key'=>'61307e5f2aebcacecbcca6fe5296df9c',
        	'version'=>'1.0'
    	),

        //支付宝  -- 官方
        2 => array(
           'app_id' => "2017121800952808",

            //商户私钥，您的原始格式RSA私钥
            'merchant_private_key' => "MIIEowIBAAKCAQEAyEfLS9/YLcrclYTb3cH8PoWh1sxD9PSrtUzJBeJRPnKqbnRbfR+L3NTJ1G4fah9e1ZWs8k6JAw7Zcquuc2rqzU/A+ZcD/UxU+ZBf8jMsfuUd0+tjfw0/EhUdGPUS/jvsUgteFtuBmRcBRW/lNhl0VgSHZoD78VapRsGJM1lSroPqfYLdiqwfDQF3cwLY89iIMD07QT72rED++7Ea7ty5gQKic4r14o/Rbvb/v8q2N5z4uqHJHDwbA9McOOxMqlKBjhMLoSIQF9SzF73RBd+o7gSzBOM1jWUBFGEwTAKbyhf/8s8uNOgCIf60RnCgxDQUrTkIdBK9FWbzOAZ4OBTQSQIDAQABAoIBAF9iMeQU23QTpTJgcAYRGPz9z7Ho3oSO8igKRcLTojXHjnvEVr4khAvMG8NcHwH/pr655gAQfxhBvjXqpc66INFfJly4G2JLND0XEVrcHFji7W28MUqVGHCYNZ9pOH50M3xvhYQFTpeGaUiUnelB/jHpPkMHMvYhe8UC70DRTZtmxys+3oO5ZGipYXhvNsKstgxjI4gytQsKfgJCnZHTUiXZ5ktgIpwjfABf/sqzoeZf1O2slaDxqibY40bTktwFt4osMAeb+X9U/9sN96JPboCkOwm+kSYuiNekW/Hwnti1VKpmlFoDEdyt0fpgSUvGVgR+wvS/XU7UfSH1nuIypHECgYEA+4cUm3n0XOKAkuP90bXZwCV7VuElX/7ULl0hpe6OBP9EZrUGOpnPfF0RR5/289p83gr8BBWysptKth9SaKo2YHziGK/PQCe+KpE1wKc00kLG2TePvADMKRpNj4gVBuQ+5EPHd/j3hkH/yQvBFSWD6X1LrYIB6j7ek3L/evsCHfcCgYEAy9dxfdpStYSlGGFYbrxPWgc0U6Hk6/awW5Zi1vZCy8px68fqF3jdAVhmytXkIBy9+0bQXCiyEHI4EVOl0aGeUIIeQ2Oosbo601O63H+Ma0FZHeUhphA5k9mN36Oj9ErUTpIqVZwrcNIe4oAv5H73SBFYBKoyiIKYat8WRVGp878CgYEA966ZvUxHNBwazgrSS2qORSbjoLbOTgwqtokg9DvH/+W6XoUN4DDL30PqGyTLUm+pHCGm7wLK2BQRauvHA6fxKexv3C1roVpBabtaOh6s5gu5sfaDhHcok6UCET/IrOOfhrmzt1EjtUJSdZAsjv1FO2wVVIM8DYXhOUM5jsmT2RsCgYBQqDMUJWtMv+vDteEUEBcl/GvMG+dFJxupYdxCSrcrhDOHpFcQapaojZ4+7FuUCUtzPhX8IW4z674bvQmD5XuLR7FJ4QlDKflU3XC3BxE872Kf3aZu20StKAxnTYz2gRV48YUm0uCth7cI0MgilcqrDZHSZrYQJfzBbPyW+TVSaQKBgBki4/X7HZf9j9OYCUaHEoXasNLJ2Ae9sE05iBsgKd1+xkSuFE1C8WiIPYxRUC3Tab44PH1lkgohNRUoqOhMmMSaRRFLKFEQ4BloS9HPuZKmXTQXBBW48zN4Q9bEe+Vc6Mtu9SDeZKs9AITN7l+5vUe6TNY7ScN+t+GZ3e7XXLsp",
        
            //异步通知地址
            'notify_url' => "http://www.hlwy.com/pay/alipay/notify.php",

            //编码格式
            'charset' => "UTF-8",

            'format'  => "json",

            //签名方式
            'sign_type'=>"RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgsaygeEUW+BBlxT0MseGZVv5qef6xXhYSO7DzWqgYhr/BvJ2X8mAYMxC5aHJdiqDN6lOGmcSOTH3no9DsgOMl69R1psYO+ZMD7nilFJEYxJzEztenx8FBaOaUNTbymwO2NhW85IkcSR3yUdIp97N+cw3trxcfywIKHp2BURjyU58MEZnVn52btxj2X1wfeULstH8n//DWTBKUBSgrPbEyhugbcu2mSIJRChOS2d9MzVvfz6A2UQdEJjjZnggVlK5CcBmr+hLuoxbqzmTtwmzK1jDwW2/p1kaaGGnOHCUhZj8ME8FEtmF+MC1ILdMPmNMVzQzRtRzcGT7wIxvQ5raLwIDAQAB",
        ),
    );


    public function P($pifgtype,$pifgname){
    	return $this->pifg[$pifgtype][$pifgname];
    }
    

    //游戏配置信息
    public $ginfo  = array(
    	//测试游戏
    	9999	=>	array(
    		'key'	=>	'123123',	//加密key
    		'desc'	=>	'',			//描述信息
            'name'  =>  '',         //游戏名称
     	),

    	//游戏1
    	//游戏2
    );
}
?>
