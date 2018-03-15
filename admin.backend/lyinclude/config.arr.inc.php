<?PHP
//充值区间
$arr_config["payrange"]  = array(
	1 	=> array('s'=>0,'e'=>6),
	2 	=> array('s'=>5,'e'=>11),
	3 	=> array('s'=>10,'e'=>31),
	4 	=> array('s'=>30,'e'=>51),
	5 	=> array('s'=>50,'e'=>101),
	6 	=> array('s'=>100,'e'=>501),
	7 	=> array('s'=>500,'e'=>1001),
	8 	=> array('s'=>1000,'e'=>3001),
	9 	=> array('s'=>3000,'e'=>5001),
	10 	=> array('s'=>5000,'e'=>8001),
	11 	=> array('s'=>8000,'e'=>10001),
	12 	=> array('s'=>10000),
);

//邮箱配置
$arr_config["mails"] = Array(
    0=>array(
        "smtpserver"        =>  "mail.huolug.com",
        "smtpserverport"    =>  25,
        "smtpusermail"      =>  "test@huolug.com",
        "smtpuser"          =>  "test@huolug.com",
        "smtppass"          =>  "111111",
        "sendername"        =>  "=?UTF-8?B?".base64_encode("3737")."?=",
    ),
);
