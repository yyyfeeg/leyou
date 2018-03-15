<?php
/* * ************   短信、验证码发送暂时屏蔽 -- 2018.1.4
   用户中心(用于游戏内)
  @账号系统，包含接口：登录、注册、忘记密码、一键注册、游客转正式、修改密码
  @CopyRight teamtop
  @file:user.php
  @author jericho
  @2017-07-17
 * ************* */
include_once "../config.inc.php";
// 切换到dcenter_count数据库

cutover_db_count();

// 请求类型(act)1:普通注册2:普通登录3:一键注册(游客模式)4:修改密码5:游客转正式6:找回密码7:手机注册8:第三方登录9:退出提示10:短信接口11:判断账号是否存在12:登录验证(游戏后端请求)13:实名制认证14:充值记录查询
$act   = get_param("act");
$ts    = get_param("ts");     //时间
$name  = get_param("name");   //账号
$pwd   = get_param("pwd");    //密码   md5后的密码
$oname = get_param("oldname");//原账号
$opwd  = get_param("oldpwd"); //原密码 md5后的密码
$npwd  = get_param("newpwd"); //新密码 md5后的密码
$uaid  = get_param("uaid");   //渠道ID
$uwid  = get_param("uwid");   //子渠道ID
$email = get_param("email");  //邮箱
$phone = get_param("phone");  //电话
$gid   = get_param("gid");    //游戏ID
$mac   = get_param("mac");    //MAC
$ip    = get_param("ip");     //ip地址
$sign  = get_param("sign");   //加密串
$code  = get_param("code");   //验证码
$type  = get_param("type");   //第三方类型，1QQ 2微信 3新浪微博
$mark  = get_param("mark");   //第三方登录标识
$uid   = get_param("uid");    //用户ID
$stype = get_param("stype");  //发验证码类型 1注册 2找回密码 3账号绑定
$etype = get_param("etype");  //验证码发送/绑定 方式 1手机   2邮箱
$token = get_param("token");  //验证账号加密串
$cardid= get_param("cardid"); //身份证号码
$tname = get_param("tname");  //真实姓名
$dnums = get_param("dnums");  //用户设备号
$vender= get_param("vender"); // 厂商
$dmodel= get_param("dmodel"); // 设备型号

$pagesize = 20;               //分页条数
$page     = empty($page)?1:intval($page);   //第几页
if($page<1) $page=1;
$start    = ($page-1)*$pagesize;

//短信&邮箱限制 -- 设备号进行限制
$interval    = 60;      //默认60秒间隔时间（短信+邮件）
$tel_maxtime = 60*60;
$tel_maxnums = 3;       //maxtime时间内最大发送条数
$tel_daymax  = 10;      //一天最多发送条数

$email_maxtime = 60*30;
$email_maxnums = 1;
$email_daymax  = 3;

//decode email
if(!empty($email)){
    $email = urldecode($email);
}

// IP地址过滤
if (empty($ip)) {
  	$ip = get_user_ip();
} else {
	$ip_arr = @explode('.', $ip);
	$count_ip = @array_sum($ip_arr);
	if ($count_ip <= 0) {
	    $ip = get_user_ip();
	}
}

//封禁对应操作类型
$bannd_act = array(1,2,3,4,5,6,7,8,10,12);
if(in_array($act,$bannd_act)){
    //判断是否被封禁
    $sql   = "select uc_timelimit,uc_closetime from view_user_closure where uc_status=1 and  FIND_IN_SET(case uc_type when 1  then '{$name}' when 2 then '{$ip}' when 3 then '{$dnums}' end ,uc_uids) order by uc_closetime desc limit 1";
    $query = $GLOBALS["count"]->query($sql);
    $bannd = $GLOBALS["count"]->getOne($query);
    if($bannd["uc_timelimit"]===0){
        $str= json_encode(array('s'=>"9998",'m'=>"您的账号已被禁，请联系客服!"));
        exit($str);
    }elseif(THIS_DATETIME-$bannd["uc_closetime"]<($bannd["uc_timelimit"]*60)){
        $str= json_encode(array('s'=>"9997",'m'=>"您的账号已被封禁，请联系客服!"));
        exit($str);
    }
}

switch($act){
    //普通账号注册
    case 1:
        //判断参数是否完整
        if(empty($name) || empty($pwd) || empty($gid) || empty($ts) || empty($sign)){
    		$str = json_encode(array('s'=>"1001",'m'=>"参数不完整!"));
    		exit($str);
    	}
        // 判断后台是否配置对应游戏接口的加密串
        if(!isset($GLOBALS["CONF_GAME_KEY"][$gid])){
    		$str = json_encode(array('s'=>"1002",'m'=>"未配置游戏参数!"));
    		exit($str);
    	}
        //验证加密码串
    	$my_sign2 = md5($name.$pwd.$ts.$gid.$uaid.$uwid.$mac.$GLOBALS["CONF_GAME_KEY"][$gid]);
    	if($my_sign2!=$sign){
    		$str = json_encode(array('s'=>"1003",'m'=>"加密验证串不正确!"));
    		exit($str);
    	}

        //帐号验证
    	if(strlen($name)<6 || strlen($name)>20 || !preg_match("/^([a-zA-Z0-9]){6,20}$/",$name)) {
    		$str = json_encode(array('s'=>"1004",'m'=>"账号为6-20个字符，全字母或字母+数字的形式！"));
    		exit($str);
    	}
        //判定账号是否存在
        $res = exist_check($GLOBALS["count"],"user_info",array("ui_name" => $name));
        if ( !$res ) {
            //同步用户信息到mySQL
            $mq_data = array(
                "ui_name"     => $name,
                "ui_pass"     => $pwd,
                "ui_regtime"  => THIS_DATETIME,
                "ui_gid"      => $gid,
                "ui_uaid"     => $uaid,
                "ui_uwid"     => $uwid,
                "ui_mac"      => $mac,
                "ui_dnum"     => $dnums,
                "ui_lasttime" => THIS_DATETIME,
                "ui_lastip"   => $ip,
                "ui_lgid"     => $gid,
                "ui_utype"    => 1,
                "ui_source"   => 1,
                "ui_mark"     => '',
                'ui_vender'   => $ui_vender,
                'ui_dmodel'   => $ui_dmodel,
            );
            // 获取当前ID号
            $mq_res =  add_record($GLOBALS['count'],"user_info",$mq_data);
            
            //返回的数据
            $data = array(
                "uname" => $name,
                "uid"   => $mq_res["id"],
                "gid"   => $gid,
                "utime" => THIS_DATETIME,
                "utype" => 1,
                "sign"  => md5($name.$mq_res["id"].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid])
            );
            $str = json_encode(array('s'=>"1000",'m'=>"注册成功!","d"=>$data));
        } else {
            $str = json_encode(array('s'=>"1005",'m'=>"用户名已经存在!"));
        }
        exit($str);
        break;
    
    //普通登录
    case 2:
        // 判断参数完整性
        if(empty($name) || empty($pwd) || empty($gid) || empty($ts) || empty($sign)){
            $str = json_encode(array('s'=>"2001",'m'=>"参数不完整!"));
            exit($str);
        }
        // 判断是否后台是否配置对应游戏的加密串
        if(!isset($GLOBALS["CONF_GAME_KEY"][$gid])){
    		$str = json_encode(array('s'=>"2002",'m'=>"未配置游戏参数!"));
    		exit($str);
    	}
        //验证加密码串
    	$my_sign2 = md5($name.$pwd.$ts.$gid.$uaid.$uwid.$GLOBALS["CONF_GAME_KEY"][$gid]);
    	if($my_sign2 != $sign){
    		$str = json_encode(array('s'=>"2003",'m'=>"加密验证串不正确!"));
    		exit($str);
    	}

        //检查账号是否存在
        $name_exist = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_utype from ".get_table("user_info")." where ui_name='{$name}'"));
        if ($name_exist) { 
            $uname = $name;
            $uid   = $name_exist["sysid"];
            $upwd  = $name_exist["ui_pass"];
            $utype = $name_exist["ui_utype"];

            // 判断密码是否正确
            if($pwd == $upwd){
                
                // 是否第一次登录,多带几个字段以判断是否第一次登录的用户
                $sql = "select count(1) as nums from ".get_table('user_info')." where sysid = $uid and ui_gid = $gid and ui_uaid = $uaid and ui_uwid = $uwid and ui_regtime = ui_lasttime";
                $result = $GLOBALS['count']->getOne($GLOBALS['count']->Query($sql));
                $isFrist = empty($result['nums']) ? 0:1;// 0:不是第一次登录 1:第一次登录

                //更新用户信息
                $mq_data = array("ui_lasttime"=>THIS_DATETIME,"ui_lastip"=>$ip,"ui_lgid"=>$gid);
                $where_t = " and sysid=".$uid;
                update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where_t);

                // 生成sign
                $loginSign = md5($uname.$uid.$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid]);

                // 生成验证token
                $token = base64_encode(uc_authcode($uname.'дк'.$uid.'дк'.$pwd,'ENCODE','ttgfun^#^3737!'));

                //返回的数据
                $data = array(
                    "uname"   => $uname,
                    "uid"     => $uid,
                    "gid"     => $gid,
                    "utime"   => THIS_DATETIME,
                    "utype"   => $utype,
                    "isFrist" => $isFrist,
                    "sign"    => $loginSign,
                    "token"   => $token
                );
                $str = json_encode(array('s'=>"2000",'m'=>"登录成功!","d"=>$data));
            }else{
                $str = json_encode(array('s'=>"2004",'m'=>"密码错误!"));
            }
        }else{
            $str = json_encode(array('s'=>"2005",'m'=>"账号不存在!"));
        }
        exit($str);
        break;
    
    //一键注册(游客模式)
    case 3:
        $name = "Ly".time().mt_rand(0,1000);	 //用户名
        $mpwd = cai_get_pwd(8);				     //明文密码
	    $pwd  = md5($mpwd);          		     //md5后的密码

        // 判断参数是否完整
        if(empty($name) || empty($pwd) || empty($gid) || empty($ts) || empty($sign)){
            $str= json_encode(array('s'=>"3001",'m'=>"参数不完整!"));
            exit($str);
	    }
        // 判断后台是否配置对应游戏接口的加密串
        if(!isset($GLOBALS["CONF_GAME_KEY"][$gid])){
    		$str = json_encode(array('s'=>"3002",'m'=>"未配置游戏参数!"));
    		exit($str);
    	}
        //验证加密码串
    	$my_sign2 = md5($ts.$gid.$uaid.$uwid.$mac.$GLOBALS["CONF_GAME_KEY"][$gid]);
    	if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"3003",'m'=>"加密验证串不正确!"));
            exit($str);
    	}
        
        //同步信息到mySQL数据库
        $mq_data = array(
            "ui_name"     => $name,
            "ui_pass"     => $pwd,
            "ui_email"    => $email,
            "ui_phone"    => $phone,
            "ui_regtime"  => THIS_DATETIME,
            "ui_gid"      => $gid,
            "ui_uaid"     => $uaid,
            "ui_uwid"     => $uwid,
            "ui_mac"      => $mac,
            "ui_dnum"     => $dnums,
            "ui_lasttime" => THIS_DATETIME,
            "ui_lastip"   => $ip,
            "ui_utype"    => 2,
            "ui_source"   => 1,
            "ui_lgid"     => $gid,
            "ui_mark"     => '',
            'ui_vender'   => $ui_vender,
            'ui_dmodel'   => $ui_dmodel,
        );

        // 获取当前ID号
        $mq_res = add_record($GLOBALS['count'],"user_info" ,$mq_data);
        
        // 生成sign
        $loginSign = md5($name.$mq_res["id"].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid]);
        
        // 生成验证token
        $token = base64_encode(uc_authcode($name.'дк'.$mq_res["id"].'дк'.$pwd,'ENCODE','ttgfun^#^3737!'));

        //返回的数据
        $data = array(
            "uname"   => $name,
            "pwd"     => $pwd,
            "mpwd"    => $mpwd,
            "uid"     => $mq_res["id"],
            "gid"     => $gid,
            "utime"   => THIS_DATETIME,
            "utype"   => 2,
            "isFrist" => 1,
            "sign"    => $loginSign,
            "token"   => $token
        );
        $str = json_encode(array('s' => "3000", 'm' => "注册成功!", "d" => $data));
        exit($str);
        break;
    
    //修改密码
    case 4:
        // 判断参数完整性
        if (empty($oname) || empty($opwd) || empty($pwd) || empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s'=>'4001','m'=>'参数不完整!'));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"4002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        // 验证加密串
        $my_sign2 = md5($oname.$opwd.$pwd.$ts.$gid.$uaid.$uwid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if ($my_sign2 != $sign) {
            $str = json_encode(array('s'=>"4003",'m'=>"加密验证串不正确!"));
            exit($str);
        }

        // 验证当前用户是否存在
        $exists = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_utype from ".get_table("user_info")." where ui_name='{$oname}'"));

        if ($exists) {

            // 验证原密码是否正确
            if ($opwd == $exists["ui_pass"]) {
                // 更新mySQL
                $mq_data = array(
                    "ui_pass"     => $pwd,
                    "ui_lasttime" => THIS_DATETIME,
                    "ui_lastip"   => $ip,
                    "ui_lgid"     => $gid,
                    );

                $where_t = " and sysid=".$exists["sysid"];
                update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where_t);
                        
                //返回的数据
                $data = array(
                    "uname" => $oname,
                    "uid"   => $exists["sysid"],
                    "gid"   => $gid,
                    "utime" => THIS_DATETIME,
                    "utype" => $exists["ui_utype"],
                    "sign"  => md5($oname.$exists["sysid"].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid])
                );
                $str = json_encode(array('s' => "4000", 'm' => "修改成功!", "d" => $data)); 

            } else {
                $str = json_encode(array('s'=>"4004",'m'=>"原密码错误!"));
            }
        } else {
            $str = json_encode(array('s'=>"4005",'m'=>"账号不存在!"));
        }
        exit($str);
        break;
    
    //游客转正式
    case 5:
        // 判断参数完整性
        if(empty($uid) || empty($oname) || empty($name) || empty($pwd) || empty($gid) || empty($ts) || empty($sign)){
            $str = json_encode(array('s' => "5001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"5002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        //验证加密码串
    	$my_sign2 = md5($uid.$oname.$name.$pwd.$ts.$gid.$uaid.$uwid.$GLOBALS["CONF_GAME_KEY"][$gid]);
    	if($my_sign2!=$sign){
            $str = json_encode(array('s'=>"5003",'m'=>"加密验证串不正确!"));
            exit($str);
    	}
        //帐号验证
    	if(strlen($name)<6 || strlen($name)>20 || !preg_match("/^([a-zA-Z0-9]){6,20}$/",$name)) {
    		$str = json_encode(array('s'=>"5004",'m'=>"账号为6-20个字符，全字母或字母+数字的组合!"));
    		exit($str);
    	}

        //验证原账号是否存在
        $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_utype from ".get_table("user_info")." where ui_name='{$oname}'"));

        if($arrs){
            // 取出当前游客所有信息
            $utype = $arrs["ui_utype"];
            if($utype!=2){
                $str = json_encode(array('s'=>"5007",'m'=>"账号类型不正确!"));
                exit($str);
            }

            // 设置的用户名是否已存在
            $exist_name = exist_check($GLOBALS["count"],"user_info",array("ui_name" => $name));

            // 判断uid是否正确
            if($uid == $arrs["sysid"] && !$exist_name && $utype == 2){
                
                //MYSQL更新
                $mq_data = array(
                    "ui_name"     =>  $name,
                    "ui_pass"     =>  $pwd,
                    "ui_lasttime" =>  THIS_DATETIME,
                    "ui_lastip"   =>  $ip,
                    "ui_lgid"     =>  $gid,
                    "ui_utype"    =>  1
                );
                
                $where_t = " and sysid=".$arrs["sysid"];
                update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where_t);

                // 记录转正日志
                $log_data = array(
                    'dul_uid'   => $arrs["sysid"],
                    'dul_name'  => $name,
                    'dul_oname' => $oname,
                    'dul_ip'    => $ip,
                    'dul_content' => '游客转正',
                    'dul_inserttime' => THIS_DATETIME,
                    'dul_gid'  => $gid,
                    'dul_uaid' => $uaid,
                    'dul_uwid' => $uwid
                    );
                add_record($GLOBALS['count'],"user_log",$log_data);
                        
                //返回的数据
                $data = array(
                    "uname" => $name,
                    "uid"   => $arrs["sysid"],
                    "gid"   => $gid,
                    "utime" => THIS_DATETIME,
                    "utype" => 1,
                    "sign"  => md5($name.$arrs["sysid"].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid])
                );
                $str = json_encode(array('s' => "5000", 'm' => "转正成功!", "d" => $data));
            }else{
               $str = json_encode(array('s'=>"5005",'m'=>"转正失败!用户名已经存在"));
            }
        }else{
            $str = json_encode(array('s'=>"5006",'m'=>"账号不存在!"));
        }
        exit($str);
        break;
    
    //找回密码(通过手机号码或邮箱找回,用户必须已绑定手机或邮箱)
    case 6:
        // 验证参数完整性
        if (empty($name) || empty($dnums) || empty($etype) || empty($code) || empty($npwd) || empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s' => "6001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"6002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        // 验证加密串
        $my_sign2 = md5($name.$dnums.$code.$npwd.$ts.$gid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"6003",'m'=>"加密验证串不正确!"));
            exit($str);
        }
        // 验证用户是否存在
        $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_utype,ui_email,ui_phone from ".get_table("user_info")." where ui_name='{$name}'"));
        //切换到dcenter_base数据库
        cutover_db_base();
        if ($arrs) {
            $u_data = array(
                "ui_pass"     => $npwd,
                "ui_lasttime" => THIS_DATETIME,
                "ui_lastip"   => $ip,
                "ui_lgid"     => $gid
            );
            $where_t = " and ui_name='{$name}'";
            switch ($etype) {
                //通过手机找回
                case 1:
                    if ($arrs["ui_phone"]) {
                        $status = check_sms_code($GLOBALS['base'],2,$dnums,$arrs["ui_phone"],$code,300);
                        if ($status == 3) {
                            // 更新mySQL
                            cutover_db_count();//切换到dcenter_count数据库
                            update_record($GLOBALS['count'],'user_info',$u_data,array(),$where_t);
                            $str = json_encode(array('s'=>"6000",'m'=>"密码修改成功"));
                        }else{
                            $str = json_encode(array('s'=>"6005",'m'=>"验证码验证错误"));
                        }
                    }else{
                        $str = json_encode(array('s'=>"6004",'m'=>"未绑定手机号码"));
                    }
                    exit($str);
                    break;
                //通过邮箱找回
                case 2:
                    if ($arrs["ui_email"]) {
                        $status = check_email_code($GLOBALS['base'],2,$dnums,$arrs["ui_email"],$code,300);
                        if ($status == 3) {
                            // 更新mySQL
                            cutover_db_count();//切换到dcenter_count数据库
                            update_record($GLOBALS['count'],'user_info',$u_data,array(),$where_t);
                            $str = json_encode(array('s'=>"6000",'m'=>"密码修改成功"));
                        }else{
                            $str = json_encode(array('s'=>"6005",'m'=>"验证码验证错误"));
                        }
                    }else{
                        $str = json_encode(array('s'=>"6004",'m'=>"未绑定邮箱"));
                    }
                    exit($str);
                    break;
                default:
                    $res = json_encode(array('s'=>'6006','m'=>'参数etype不正确'));
                    exit($res);
                    break;
            }
        }else{
            $str = json_encode(array('s'=>"6007",'m'=>"账号不存在!"));
        }
        exit($str);
        break;

    // 手机注册
    case 7:
        $name = $phone; // 用户名
        // $pwd  = cai_get_pwd(8); //密码

        // 验证参数完整性
        if (empty($name) || empty($phone) || empty($pwd) || empty($code) || empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s' => "7001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"7002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        //验证加密码串
        $my_sign2 = md5($phone.$code.$ts.$gid.$uaid.$uwid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"7003",'m'=>"加密验证串不正确!"));
            exit($str);
        }

        // 切换到dcenter_base数据库
        cutover_db_base();

        // 验证短信验证码是否正确
        $status = check_sms_code($GLOBALS['base'], 1, $dnums, $phone, $code, 300);

        // 切换到dcenter_count数据库
        cutover_db_count();

        if ($status == 3) {
            // 判断手机号是否已经存在
            $exists = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select count(1) c from ".get_table("user_info")." where ui_name='{$name}'"));

            if (empty($exists) || $exists["c"] == 0) {
                //同步用户信息到mySQL
                $mq_data = array(
                    "ui_name"     => $name,
                    "ui_pass"     => $pwd,
                    "ui_email"    => "",
                    "ui_phone"    => $phone,
                    "ui_regtime"  => THIS_DATETIME,
                    "ui_gid"      => $gid,
                    "ui_uaid"     => $uaid,
                    "ui_uwid"     => $uwid,
                    "ui_mac"      => $mac,
                    "ui_dnum"     => $dnums,
                    "ui_lasttime" => THIS_DATETIME,
                    "ui_lastip"   => $ip,
                    "ui_lgid"     => $gid,
                    "ui_utype"    => 3,         //手机号注册用户
                    "ui_source"   => 1,
                    "ui_mark"     => '',
                    'ui_vender'   => $ui_vender,
                    'ui_dmodel'   => $ui_dmodel,
                );

                $mq_res =  add_record($GLOBALS['count'],"user_info",$mq_data);

                // 生成验证token
                $token = base64_encode(uc_authcode($name.'дк'.$mq_res["id"].'дк'.$pwd,'ENCODE','ttgfun^#^3737!'));
                
                //返回的数据
                $data = array(
                    "uname" => $name,
                    "uid"   => $mq_res["id"],
                    "gid"   => $gid,
                    "utime" => THIS_DATETIME,
                    "utype" => 1,
                    "sign"  => md5($name.$mq_res["id"].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid]),
                    "token" => $token
                );
                $str = json_encode(array('s'=>"7000",'m'=>"注册成功!","d"=>$data));

            } else {
                // 账号已存在
                $str = json_encode(array('s'=>"7004",'m'=>"账号已存在!"));
            }
        } elseif ($status == 2) {
            // 验证码错误
            $str = json_encode(array('s'=>"7005",'m'=>"验证码错误!"));
        } elseif ($status == 1) {
            $str = json_encode(array('s'=>"7006",'m'=>"验证码超过有效时间，请重新发送!"));
        } else {
            $str = json_encode(array('s'=>"7007",'m'=>"注册失败!"));
        }
        exit($str);
        break;

    // 第三方登陆
    case 8:
        // 判断登录类型
        $head_arr   = array(1=>'qq', 2=>'wx', 3=>'wb');
        $source_arr = array(1=>'2', 2=>'4', 3=>'0');
        $name = $head_arr[$type].time().mt_rand(0,100);//用户名
        $pwd  = md5(cai_get_pwd(8));//密码

        // 验证参数完整性
        if (empty($type) || !in_array($type, array(1,2,3)) || empty($name) || empty($pwd) || empty($gid) || empty($ts) || empty($sign) || empty($mark)) {
            $str = json_encode(array('s' => "8001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"8002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        //验证加密码串
        $my_sign2 = md5($ts.$gid.$uaid.$uwid.$mac.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"8003",'m'=>"加密验证串不正确!"));
            exit($str);
        }

        // 验证是否有登录过
        $status =  $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_name,ui_gid from ".get_table("user_info")." where ui_mark='{$mark}'"));

        if ($status) {
             //同步用户信息到mySQL
            $mq_data = array(
                "ui_name"     => $name,
                "ui_pass"     => $pwd,
                "ui_email"    => "",
                "ui_phone"    => "",
                "ui_regtime"  => THIS_DATETIME,
                "ui_gid"      => $gid,
                "ui_uaid"     => $uaid,
                "ui_uwid"     => $uwid,
                "ui_mac"      => $mac,
                "ui_dnum"     => $dnums,
                "ui_lasttime" => THIS_DATETIME,
                "ui_lastip"   => $ip,
                "ui_utype"    => 1,
                "ui_source"   => $source_arr[$type],
                "ui_mark"     => $mark,
                'ui_vender'   => $ui_vender,
                'ui_dmodel'   => $ui_dmodel,
            );

            // 获取当前ID号
            $mq_res =  add_record($GLOBALS['count'],"user_info",$mq_data);

            // 生成验证token
            $token = base64_encode(uc_authcode($name.'дк'.$mq_res["id"].'дк'.$pwd,'ENCODE','ttgfun^#^3737!'));
            
            //返回的数据
            $data = array(
                "uname" => $name,
                "uid"   => $mq_res['id'],
                "gid"   => $gid,
                "utime" => THIS_DATETIME,
                "utype" => 1,
                "sign"  => md5($name.$mq_res['id'].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid]),
                "token" => $token
            );
        } else {

            // 更新MYSQL
            $mq_data = array(
                "ui_lasttime" => THIS_DATETIME,
                "ui_lastip"   => $ip,
                "ui_lgid"     => $gid,
            );
            $where_t = " and sysid=".$status["sysid"];
            update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where_t);

            // 生成验证token
            $token = base64_encode(uc_authcode($arrs["ui_name"].'дк'.$arrs["sysid"].'дк'.$arrs["ui_name"],'ENCODE','ttgfun^#^3737!'));
     
            //返回的数据
            $data = array(
                "uname" => $status["ui_name"],
                "uid"   => $status["sysid"],
                "gid"   => $gid,
                "utime" => THIS_DATETIME,
                "utype" => 1,
                "sign"  => md5($status["ui_name"].$status["sysid"].$gid.THIS_DATETIME.$GLOBALS["CONF_GAME_KEY"][$gid]),
                "token" => $token
            );
        }
        $str = json_encode(array('s' => "8000", 'm' => "登录成功!", "d" => $data));
        exit($str);
        break;

    // 退出登录提示
    case 9:
        // 参数完整性验证
        if (empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s' => "9001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"9002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        //验证加密码串
        $my_sign2 = md5($ts.$gid.$uaid.$uwid.$mac.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"9003",'m'=>"加密验证串不正确!"));
            exit($str);
        }

        // 切换到dcenter_base数据库
        cutover_db_base();

        // 查询后台推送的信息
        $info = get_quit_tips($GLOBALS['base'], $gid, $uaid, $uwid);

        if (!empty($info)) {
            // 返回数据
            $data = array(
                'picture'  => WEBPATH_DIR_INC.str_replace('..','',$info['lt_picture']),
                'jump_url' => $info['lt_url'],
                'contents' => $info['lt_contents']
                );
            $str = json_encode(array('s'=>'9000','m'=>'获取信息成功！','d'=>$data));
        } else {
            $str = json_encode(array('s'=>'9004','m'=>'获取信息失败！'));
        }
        exit($str);
        break;

    // 发送验证码(短信+邮件)  etype  1.短信  2.邮件
    case 10:
        // 验证参数完整性
        if (empty($stype) || empty($etype) || empty($gid) || empty($ts) || empty($sign) || empty($dnums)) {
            $str = json_encode(array('s' => "10001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"10002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        //验证加密码串
        $my_sign2 = md5($etype.$ts.$gid.$uaid.$uwid.$dnums.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"10003",'m'=>"加密验证串不正确!"));
            exit($str);
        }
        //判断格式
        if($etype == 1){
            // 判断手机号码格式
            if (!check_phone($phone)) {
                $str = json_encode(array('s'=>"10004",'m'=>"手机号码不正确!"));
                exit($str);
            }
        }else{
            if(!isEmail($email)){
                $str = json_encode(array('s'=>"10004",'m'=>"邮箱格式不正确!"));
                exit($str);
            }
        }

        $calls  = ($etype==1)?$phone:$email;
        $ms     = ($etype==1)?"手机":"邮箱";
        $cond   = ($etype==1)?"ui_phone":"ui_email";

        // 判断发验证码类型
        switch ($stype) {
            // 手机&邮箱注册
            case 1:
                // 判断手机号是否已被注册
                $name_exist = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_utype,ui_phone from ".get_table("user_info")." where ui_name='{$calls}'"));

                if ($name_exist) {
                    $res = json_encode(array('s'=>'10005','m'=>$ms.'已被注册'));
                    exit($res);
                }

                // 判断手机/邮箱是否已绑定其他账号
                // $bind_exist =  $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_utype,ui_name from ".get_table("user_info")." where {$cond}='{$calls}'"));

                // if ($bind_exist) {
                //     $mid_name = add_covert_str($bind_exist["ui_name"]);
                //     $res = json_encode(array('s'=>'10006','m'=>'该'.$ms.'已绑定账号：'.$mid_name.',可直接登录'));
                //     exit($res);
                // }
                $event = 1;
                break;
            
            // 找回密码
            case 2:
                // 判断账号是否为空
                if (empty($name)) {
                    $res = json_encode(array('s'=>'10007','m'=>'账号不能为空'));
                    exit($res);
                }

                // 判断账号是否存在
                $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_utype,ui_phone,ui_email from ".get_table("user_info")." where ui_name='{$name}'"));
                if ($arrs) {
                    // 取出用户信息
                    $u_phone = $arrs[$cond];
                    if (empty($u_phone)) {
                        $res = json_encode(array('s'=>'10008','m'=>'未绑定'.$ms));
                        exit($res);
                    } elseif ($u_phone != $calls) {
                        $res = json_encode(array('s'=>'10009','m'=>'填写的'.$ms.'与绑定的不一致'));
                        exit($res);
                    }
                } else {
                    $res = json_encode(array('s'=>'10010','m'=>'账号不存在'));
                    exit($res);
                }
                $event = 2;
                break;

            //绑定手机/邮箱
            case 3:
                // 判断账号是否为空
                if (empty($name)) {
                    $res = json_encode(array('s'=>'10007','m'=>'账号不能为空'));
                    exit($res);
                }
                // 判断账号是否存在
                $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_utype,ui_phone,ui_email from ".get_table("user_info")." where ui_name='{$name}'"));
                if ($arrs) {
                    $te_exists =  $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select {$cond} as c from ".get_table("user_info")." where ui_name='{$calls}'"));
                    //查询是否绑定过邮箱&手机号
                    if ($te_exists) {
                        $res = json_encode(array('s'=>'10005','m'=>$ms.'已被注册'));
                        exit($res);
                    }
                } else {
                    $res = json_encode(array('s'=>'10010','m'=>'账号不存在'));
                    exit($res);
                }
                $event = 3;
                break;

            default:
                $res = json_encode(array('s'=>'10013','m'=>'参数stype不正确'));
                exit($res);
                break;
        }

        cutover_db_base();//切换到dcenter_base数据库

        $startTime = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
        $endTime = mktime(23,59,59,date("m",time()),date("d",time()),date("Y",time()));

        //限制验证
        if($etype==1){
            //时效限制
            $t1    = THIS_DATETIME-($tel_maxtime+1);
            $t2    = THIS_DATETIME+1;
            $sql   = "select count(*) as num from ".get_table("attest_tel")." where at_event=".$stype." and at_dnum='".$dnums."'";
            $sql1  = $sql." and at_time > {$t1} and at_time < {$t2}";
            $query = $GLOBALS['base']->getOne($GLOBALS['base']->query($sql1));
            if($query["num"]>=$tel_maxnums){
                $res = array('s'=>"10011",'m'=>"请求过于频繁!");
                exit(json_encode($res));
            }
            unset($query);

            //每天限制
            $sql2  = $sql." and at_time>".($startTime-1)." and at_time<".($endTime+1);
            $query = $GLOBALS['base']->getOne($GLOBALS['base']->query($sql2));
            if($query["num"]>=$tel_daymax){
                $res = array('s'=>"10012",'m'=>"请求过于频繁!已达到当天限制次数！");
                exit(json_encode($res));
            } 
            unset($query);

            //验证是否达到重发时间
            $send_time = $GLOBALS["base"]->getOne($GLOBALS["base"]->query("select if(".THIS_DATETIME."-at_time<".$interval.",1,0) stype from ".get_table("attest_tel")." where at_event=".$stype." and at_tel=".$phone." order by sysid desc limit 1"));
            if (empty($send_time["stype"]) || $send_time["stype"]==0) {
                //生成验证码
                srand((double)microtime()*1000000);
                $code = rand(100000,999999);
                $content ="验证码".$code."，请勿将验证码告诉他人（乐游客服绝不会向您索要验证码），如非本人操作请及时修改账号密码。";

                // 发送短信
                $result = $GLOBALS['sms']->send_sms($phone,$content);
                $result = json_decode($result,true);
                if (isset($result['respcode']) && $result['respcode'] == 0) {
                    $data['at_status']   = 1;
                    $data['at_batchno']  = $result["batchno"];
                    $codes  = "10000";
                    $fanhui = "成功";
                } else {
                    $data['at_status'] = 2;
                    $codes   = "10099";
                    $fanhui  = "失败";
                }

                // 入库数据
                $data['at_tel']      = $phone;
                $data['at_contents'] = $code;
                $data['at_uid']      = 0;
                $data['at_time']     = THIS_DATETIME;
                $data['at_bulk']     = 1; //单发
                $data['at_verify']   = 2; //未验证
                $data['at_ip']       = $ip;
                $data['at_type']     = 1; //验证码
                $data['at_gid']      = $gid;
                $data['at_event']    = $event;
                $data['at_dnum']     = $dnums;
                $data['at_suid']     = $name;

                // 添加记录
                add_record($GLOBALS['base'],"attest_tel",$data);
                //验证码单独处理 第7~12位数字为验证码
                $d  = cai_get_pwd(6).$code.cai_get_pwd(6);
                $str= json_encode(array('s'=>$codes,'m'=>"验证码发送".$fanhui,'d'=>$d));
            } else {
                $str= json_encode(array('s'=>"10014",'m'=>"请求过于频繁!请稍后重试！"));
            }
        }else{  //邮件
            //时效限制
            $t1    = THIS_DATETIME-($email_maxtime+1);
            $t2    = THIS_DATETIME+1;
            $sql   = "select count(*) as num from ".get_table("attest_email")." where ae_event=".$stype." and ae_dnum='".$dnums."'";
            $sql1  = $sql." and ae_time > {$t1} and ae_time < {$t2}";
            $query = $GLOBALS['base']->getOne($GLOBALS['base']->query($sql1));
            if($query["num"]>=$email_maxnums){
                $res = array('s'=>"10011",'m'=>"请求过于频繁!");
                exit(json_encode($res));
            }
            unset($query);

            //每天限制
            $sql2  = $sql." and ae_time>".($startTime-1)." and ae_time<".($endTime+1);
            $query = $GLOBALS['base']->getOne($GLOBALS['base']->query($sql2));
            if($query["num"]>=$email_daymax){
                $res = array('s'=>"10012",'m'=>"请求过于频繁!已达到当天限制次数！");
                exit(json_encode($res));
            } 
            unset($query);

            //验证是否达到重发时间
            $send_time = $GLOBALS["base"]->getOne($GLOBALS["base"]->query("select if(".THIS_DATETIME."-ae_time<".$interval.",1,0) stype from ".get_table("attest_email")." where ae_event=".$stype." and ae_email='".$email."' order by sysid desc limit 1"));
            if (empty($send_time["stype"]) || $send_time["stype"]==0) {
                //生成验证码
                srand((double)microtime()*1000000);
                $code = rand(100000,999999);
                $mailtitle = "欢乐游戏";
                $content ="验证码".$code."，请勿将验证码告诉他人（乐游客服绝不会向您索要验证码），如非本人操作请及时修改账号密码。";

                // 发送短信
                $mailtitle = "乐游科技通知提醒";
                $send_res  = send_mail($GLOBALS['mail'],$email,'',$mailtitle,$content,'');

                // 入库数据
                $data['ae_email']    = $email;
                $data['ae_contents'] = $code;
                $data['ae_uid']      = 0;
                $data['ae_time']     = THIS_DATETIME;
                $data['ae_bulk']     = 1;
                $data['ae_verify']   = 2;
                $data['ae_ip']       = $ip;
                $data['ae_type']     = 1;
                $data['ae_event']    = $event;
                $data['ae_dnum']     = $dnums;
                $data['ae_suid']     = $name;

                if($send_res){
                    $data['ae_status'] = 1;
                    $codes = "10000";
                }else{
                    $data['ae_status'] = 2;
                    $codes = "10099";
                }
                // 添加记录
                add_record($GLOBALS['base'],"attest_email",$data);
                //验证码单独处理 第7~12位数字为验证码
                $d  = cai_get_pwd(6).$code.cai_get_pwd(6);
                $str= json_encode(array('s'=>$codes,'m'=>"验证码发送".(($send_res)?"成功":"失败"),'d'=>$d));
            } else {
                $str= json_encode(array('s'=>"10014",'m'=>"请求过于频繁!请稍后重试！"));
            }
        }
        exit($str);
        break;
        
    // 判断手机账号是否存在
    case 11:
        // 验证参数完整性
        if (empty($name) || empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s' => "11001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"11002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        //验证加密码串
        $my_sign2 = md5($name.$ts.$gid.$uaid.$uwid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"11003",'m'=>"加密验证串不正确!"));
            exit($str);
        }

        // 检查账号是否存在
        $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_utype,ui_phone from ".get_table("user_info")." where ui_name='{$name}'"));

        if ($arrs) {
            // 读取用户信息
            // $u_phone = $arrs["ui_phone"];
            // if (empty($u_phone)) {
            //     $str = json_encode(array('s'=>"11004",'m'=>"此账号未绑定手机号"));
            // } else {
                $str = json_encode(array('s'=>"11000",'m'=>"账号正确",'d'=>$u_phone));
            // }
        } else {
            $str = json_encode(array('s'=>"11005",'m'=>"账号不存在"));
        }
        exit($str);
        break;

    // 登录验证(验证用户合法登录)
    case 12:
        // 判断传递参数
        if (empty($ts) || empty($gid) || empty($sign) || empty($token)) {
            $str = array('s'=>'12001','m'=>'参数不完整');
            exit(json_encode($str));
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = array('s'=>"12002",'m'=>"未配置游戏参数!");
            exit(json_encode($str));
        }
        // 验证加密串
        $my_sign = md5($ts.$gid.$token.$GLOBALS['CONF_GAME_KEY'][$gid]);
        if ($my_sign != $sign) {
            $str = array('s'=>'12003','m'=>'加密验证串不正确');
            exit(json_encode($str));
        }

        // 验证用户是否合法登录
        $uinfo = uc_authcode(base64_decode($token),'DECODE','ttgfun^#^3737!');
        $uinfo_arr = explode('дк', $uinfo);
        $name = $uinfo_arr[0];
        $tuid = $uinfo_arr[1];
        $pwd  = $uinfo_arr[2];

        //检查账号是否存在
        $name_exist = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_name from ".get_table("user_info")." where ui_name='{$name}'"));

        if ($name_exist) {
            $uid   = $name_exist["sysid"];
            $upwd  = $name_exist["ui_pass"];
            $uname = $name_exist["ui_name"];

            if ($name == $uname && $tuid == $uid && $pwd == $upwd) {
                $date = array(
                    "uname" => $uname,
                    "uid"   => $uid,
                    "gid"   => $gid,
                    "utime" => THIS_DATETIME,
                    "sign"  => md5($uname.$uid.$gid.THIS_DATETIME.$GLOBALS['CONF_GAME_KEY'][$gid])
                    );
                $str = array('s'=>'12000','m'=>'合法登录','d'=>$date);
            } else {
                $str = array('s'=>'12004','m'=>'非法登录');
            }
        } else {
            $str = array('s'=>'12005','m'=>'非法账号');
        }
        exit(json_encode($str));
        break;

    // 实名制认证
    case 13:
        $wps   = get_param("wps");  //1：查询 2：提交实名认证
        // 判断传递参数
        if (empty($uid) || empty($ts) || empty($wps) || empty($gid) || empty($sign)) {
            $str = array('s'=>'13001','m'=>'参数不完整');
            exit(json_encode($str));
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = array('s'=>"13002",'m'=>"未配置游戏参数");
            exit(json_encode($str));
        }
        // 验证当前用户是否存在
        cutover_db_count();//切换到dcenter_count数据库
        $c = 0;
        $iscard = $data = $n_data = array();
        $exists = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_name,ui_phone from ".get_table("user_info")." where sysid='{$uid}'"));
        if ($exists) {
            $iscard = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select ui_uid,ui_name,ui_truename,ui_idnum,ui_phone from ".get_table("user_details")." where ui_uid='{$uid}'"));
            if ($iscard) {
                if (!empty($iscard["ui_idnum"])) {
                    $lens = strlen($iscard["ui_truename"]);
                    $mod  = $lens>0?$lens/3:0;
                    if($mod>1){
                        for($i=1;$i<$mod;$i++){
                            $cond .= "*";
                        }
                    }else{
                        $cond = "";
                    }
                    $t_data = array(
                        'uid'      => $uid,
                        'name'     => $exists["ui_name"],
                        'truename' => substr($iscard["ui_truename"], 0,3).$cond,
                        'idnum'    => add_covert_str($iscard["ui_idnum"]),
                        'phone'    => $iscard["ui_phone"]
                    );
                    $str = array('s'=>'13008','m'=>'该用户已实名认证','d'=>$t_data);
                    exit(json_encode($str));
                }
                $c = 1;//认证表有记录但无身份证信息，update
            }else{
                $c = 2;//认证表无记录，insert
            }
        }else{
            $str = array('s'=>'13007','m'=>'用户不存在');
            exit(json_encode($str));
        }
        if ($wps == 1 && $c > 0) {
            $str = array('s'=>'13010','m'=>'该用户未实名认证');
            exit(json_encode($str));
        }
        // 判断传递参数
        if (empty($tname) || empty($phone) || empty($cardid)) {
            $str = array('s'=>'13001','m'=>'参数不完整');
            exit(json_encode($str));
        }
        // 验证加密串
        $my_sign = md5($uid.$ts.$gid.$tname.$phone.$cardid.$GLOBALS['CONF_GAME_KEY'][$gid]);
        if ($my_sign != $sign) {
            $str = array('s'=>'13003','m'=>'加密验证串不正确');
            exit(json_encode($str));
        }
        // 验证中文姓名
        $name_true = isChineseName($tname);
        if (!$name_true) {
            $str = array('s'=>'13004','m'=>'真实姓名不正确');
            exit(json_encode($str));
        }
        // 验证手机号码
        $phone_true = check_phone($phone);
        if (!$phone_true) {
            $str = array('s'=>'13005','m'=>'手机号格式不正确');
            exit(json_encode($str));
        }
        // 验证身份证号码是否正确
        $status = check_idnum($cardid);
        if ($status > 1) {
            if ($status >= 6) {
                $n_data = array(
                    'ui_uid'      => $uid,
                    'ui_name'     => $exists["ui_name"],
                    'ui_truename' => $tname,
                    "ui_phone"    => $phone,
                    'ui_idnum'    => $cardid
                );
                if ($c == 1) {
                    // 更新MYSQL
                    $d_data = array(
                        "ui_truename"   => $tname,
                        "ui_phone"      => $phone,
                        "ui_idnum"      => $cardid
                    );
                    $where_d = " and ui_uid='{$uid}'";
                    update_record($GLOBALS['count'],'user_details',$d_data,array(),$where_d);
                }
                if ($c == 2) {
                    // 添加记录
                    add_record($GLOBALS['count'],"user_details",$n_data);
                }
                $lens = strlen($tname);
                $mod  = $lens>0?$lens/3:0;
                if($mod>1){
                    for($i=1;$i<$mod;$i++){
                        $cond .= "*";
                    }
                }else{
                    $cond = "";
                }
                $data = array(
                    'uid'      => $uid,
                    'name'     => $exists["ui_name"],
                    'truename' => substr($tname,0,3).$cond,
                    'idnum'    => add_covert_str($cardid),
                    'phone'    => $phone
                );
                $str = array('s'=>'13000','m'=>'身份实名制认证成功','d'=>$data);
            }else{
                $str = array('s'=>'13006','m'=>'身份证号码格式不正确');
            }
        }else{
            $str = array('s'=>'13009','m'=>'此身份证号码不在五行中');
        }
        exit(json_encode($str));
        break;

    // 充值记录
    case 14:
        // 判断传递参数
        if (empty($uid) || empty($ts) || empty($gid) || empty($sign) || empty($page)) {
            $str = array('s'=>'14001','m'=>'参数不完整');
            exit(json_encode($str));
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = array('s'=>"14002",'m'=>"未配置游戏参数");
            exit(json_encode($str));
        }
        // 验证加密串
        $my_sign = md5($uid.$ts.$gid.$GLOBALS['CONF_GAME_KEY'][$gid]);
        if ($my_sign != $sign) {
            $str = array('s'=>'14003','m'=>'加密验证串不正确');
            exit(json_encode($str));
        }
        cutover_db_count();//切换到dcenter_count数据库
        // 验证当前用户是否存在
        $exists = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_name from ".get_table("user_info")." where sysid='{$uid}'"));
        if ($exists) {
            $exists_detail = $GLOBALS["count"]->getAll($GLOBALS["count"]->query("select ol_orderid,ol_paytime,ol_money from ".get_table("orderform_log")." where ol_payresult=1 and ol_uid='{$uid}' order by ol_paytime desc limit $start, $pagesize "));
            $count = count($exists_detail);
            if ($exists_detail) {
                $pagecount= ceil($count/$pagesize);
                foreach ($exists_detail as $key => $val) {
                    $data[] = $val;
                }
                $str = array('s'=>'14000','m'=>'充值记录查询成功','c'=>$pagecount,'d'=>$data);
            }else{
                $str = array('s'=>'14004','m'=>'该用户没有成功充值记录');
            }
        }else{
            $str = array('s'=>'14005','m'=>'用户名不存在');
        }
        exit(json_encode($str));
        break;

    //获取客服QQ  先写死
    case 15:
        if(empty($gid) || empty($sign)){
            $str = array('s'=>'15001','m'=>'参数不完整');
            exit(json_encode($str));
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = array('s'=>"15002",'m'=>"未配置游戏参数");
            exit(json_encode($str));
        }
        // 验证加密串
        $my_sign = md5($uid.$ts.$gid.$GLOBALS['CONF_GAME_KEY'][$gid]);
        if ($my_sign != $sign) {
            $str = array('s'=>'15003','m'=>'加密验证串不正确');
            exit(json_encode($str));
        }
        $qq  = 2096789934;
        $str = array('s'=>'15000','m'=>'ok','d'=>$qq);
        exit(json_encode($str));
        break;

    //验证是否绑定手机或邮箱
    case 16:
        // 验证参数完整性
        if (empty($name) || empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s' => "16001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"16002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        // 验证加密串
        $my_sign2 = md5($name.$ts.$gid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"16003",'m'=>"加密验证串不正确!"));
            exit($str);
        }
        // 验证用户是否存在
        $d_phone = $d_email = '';
        $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_utype,ui_email,ui_phone from ".get_table("user_info")." where ui_name='{$name}'"));
        if ($arrs) {
            // 判断是否绑定手机号或邮箱
            if (empty($arrs["ui_phone"]) && empty($arrs["ui_email"])) {
                $str = json_encode(array('s'=>"16004",'m'=>"用户未绑定手机和邮箱"));
            }else{
                if (!empty($arrs["ui_phone"])) {
                    $d_phone = $arrs["ui_phone"];
                }
                if (!empty($arrs["ui_email"])) {
                    $d_email = $arrs["ui_email"];
                }
                $data = array(
                    "ui_phone" => $d_phone,
                    "ui_email" => $d_email
                );
                $str = json_encode(array('s'=>'16000','m'=>'已绑定手机或邮箱','d'=>$data));
            }
        }else {
            $str = json_encode(array('s'=>"16005",'m'=>"账号不存在!"));
        }
        exit($str);
        break;

    // 游戏公告
    case 17:
        // 判断传递参数
        if (empty($ts) || empty($gid) || empty($sign)) {
            $str = array('s'=>'17001','m'=>'参数不完整');
            exit(json_encode($str));
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = array('s'=>"17002",'m'=>"未配置游戏参数");
            exit(json_encode($str));
        }
        // 验证加密串
        $my_sign = md5($ts.$gid.$GLOBALS['CONF_GAME_KEY'][$gid]);
        if ($my_sign != $sign) {
            $str = array('s'=>'17003','m'=>'加密验证串不正确');
            exit(json_encode($str));
        }
        cutover_db_base();//切换到dcenter_base数据库
        $time1 = date('Ymd');
        // 查询当前游戏公告
        $exists = $GLOBALS["base"]->getAll($GLOBALS["base"]->query("select sysid,gn_title,gn_contents,gn_startdate from ".get_table("game_notice")." where gn_status=2 and gn_startdate<={$time1} and (gn_enddate>={$time1} or gn_enddate is null) and gn_gid={$gid} order by gn_sort desc,sysid desc limit 3 "));
        if ($exists) {
            $str = array('s'=>'17000','m'=>'公告查询成功','d'=>$exists);
        }else{
            $str = array('s'=>'17004','m'=>'该游戏暂无公告列表');
        }
        exit(json_encode($str));
        break;

    //绑定账号
    case 18:
        // 验证参数完整性
        if (empty($dnums) || empty($etype) || empty($name) || empty($code) || empty($gid) || empty($ts) || empty($sign)) {
            $str = json_encode(array('s' => "18001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"18002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        // 验证加密串
        $my_sign2 = md5($dnums.$etype.$name.$ts.$gid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"18003",'m'=>"加密验证串不正确!"));
            exit($str);
        }
        // 验证用户是否存在
        $arrs = $GLOBALS["count"]->getOne($GLOBALS["count"]->query("select sysid,ui_pass,ui_utype,ui_email,ui_phone from ".get_table("user_info")." where ui_name='{$name}'"));
        if ($arrs) {
            // 判断绑定类型
            switch ($etype) {
                // 手机
                case 1:
                    // 判断是否绑定手机号
                    if (empty($arrs["ui_phone"])) {
                        // 判断手机号码格式
                        if (empty($phone)) {
                            $str = json_encode(array('s' => "18001", 'm' => "手机号不能为空!"));
                            exit($str);
                        }else{
                            //验证手机格式
                            if (!check_phone($phone)) {
                                $str = json_encode(array('s'=>"18004",'m'=>"手机号码不正确!"));
                                exit($str);
                            }
                            //验证账号类型
                            if ($arrs["ui_utype"] == 3) {
                                $str = json_encode(array('s'=>"18007",'m'=>"手机注册用户无需绑定!"));
                                exit($str);
                            }
                            //验证验证码是否匹配
                            cutover_db_base();//切换到dcenter_base数据库
                            $status = check_sms_code($GLOBALS['base'],3,$dnums,$phone,$code,300);
                            if ($status == 3) {
                                // 更新MYSQL
                                $d_data = array(
                                    "ui_phone"   => $phone
                                );
                                $where_d = " and ui_name='{$name}'";
                                cutover_db_count();//切换到dcenter_count数据库
                                update_record($GLOBALS['count'],'user_info',$d_data,array(),$where_d);

                                $str = json_encode(array('s'=>'18000','m'=>'绑定手机号成功'));
                                exit($str);
                            }elseif ($status == 1) {
                                $str = json_encode(array('s'=>"18005",'m'=>"验证码超过有效时间，请重新发送!"));
                            } elseif ($status == 2) {
                                $str = json_encode(array('s'=>"18006",'m'=>"验证码不正确!"));
                            }
                        }
                    }else{
                        $str = json_encode(array('s'=>'18007','m'=>'已绑定手机号'));
                        exit($str);
                    }
                    break;
                // 邮箱
                case 2:
                    // 判断是否绑定邮箱
                    if (empty($arrs["ui_email"])) {
                        // 判断手机号码格式
                        if (empty($email)) {
                            $str = json_encode(array('s' => "18001", 'm' => "邮箱不能为空!"));
                            exit($str);
                        }else{
                            //验证邮箱格式
                            if (!isEmail($email)) {
                                $str = json_encode(array('s'=>"18004",'m'=>"邮箱格式不正确!"));
                                exit($str);
                            }
                            //验证账号类型
                            // if ($arrs["ui_utype"] == 4) {
                            //     $str = json_encode(array('s'=>"18007",'m'=>"邮箱注册用户无需绑定!"));
                            //     exit($str);
                            // }
                            //验证验证码是否匹配
                            cutover_db_base();//切换到dcenter_base数据库
                            $status = check_email_code($GLOBALS['base'],3,$dnums,$email,$code,300);
                            if ($status == 3) {
                                // 更新MYSQL
                                $d_data = array(
                                    "ui_email"   => $email
                                );
                                $where_d = " and ui_name='{$name}'";
                                cutover_db_count();//切换到dcenter_count数据库
                                update_record($GLOBALS['count'],'user_info',$d_data,array(),$where_d);

                                $str = json_encode(array('s'=>'18000','m'=>'绑定邮箱成功'));
                                exit($str);
                            }elseif ($status == 1) {
                                $str = json_encode(array('s'=>"18005",'m'=>"验证码超过有效时间，请重新发送!"));
                            } elseif ($status == 2) {
                                $str = json_encode(array('s'=>"18006",'m'=>"验证码不正确!"));
                            }
                        }
                    }else{
                        $str = json_encode(array('s'=>'18007','m'=>'已绑定邮箱'));
                        exit($str);
                    }
                    break;
                default:
                    $str = json_encode(array('s'=>'18008','m'=>'参数etype不正确'));
                    exit($str);
                    break;
            }
        }else {
            $str = json_encode(array('s'=>"18009",'m'=>"账号不存在!"));
        }
        exit($str);
        break;

    //消息
    case 19:
        cutover_db_base();
        $tps   = get_param("tps");  //1：标题 2：内容
        $mid   = get_param("mid");  //消息ID  
        
        // 验证参数完整性
        if (empty($dnums) || empty($name) || empty($tps) ||  empty($gid) || empty($ts) || empty($sign) || empty($page)) {
            $str = json_encode(array('s' => "19001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"19002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        // 验证加密串
        $my_sign2 = md5($uid.$ts.$gid.$uaid.$uwid.$tps.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"19003",'m'=>"加密验证串不正确!"));
            exit($str);
        }
        $wheres  = " and ul_name='".$name."'";
        $limit   = " order by ul_time desc limit $start, $pagesize";
        $sql     = "select * from ".get_table("user_msg_log")." where 1";
        switch($tps){
            case 1:
                $total =  $GLOBALS["base"]->getOne($GLOBALS["base"]->query("select count(*) c from ".get_table("user_msg_log")." where 1 $wheres"));
                if($total["c"]>0){
                    $query =  $GLOBALS["base"]->query($sql.$wheres.$limit);
                    while($row = $GLOBALS["base"]->Fetcharray($query)){
                        $rows["title"]  = $row["ul_title"];
                        $rows["mid"]    = $row["sysid"];
                        $rows["user"]   = $row["ul_pushname"];
                        $rows["state"]  = $row["ul_state"];
                        $rows["time"]   = $row["ul_time"];//date("Y-m-d H:i:s",$row["ul_time"]);
                        $uinfo[]        = $rows;
                    }
                    $pagecount= ceil($total["c"]/$pagesize);
                    $str= json_encode(array('s'=>"19100",'m'=>"ok",'d'=>$uinfo,'c'=>$pagecount));
                }else{
                    $str= json_encode(array('s'=>"19005",'m'=>"暂无消息!"));
                }
                break;
            case 2:
                $sql  .= " and sysid=".$mid;
                $query = $GLOBALS["base"]->query($sql);
                while($row = $GLOBALS['base']->Fetcharray($query)){
                    $rows["title"]      = $row["ul_title"];
                    $rows["user"]       = $row["ul_pushname"]; 
                    $rows["time"]       = $row["ul_time"];//date("Y-m-d H:i:s",$row["ul_time"]);
                    $rows["content"]    = $row["ul_content"];
                    $uinfo[]            = $rows;
                    if($row["ul_state"]==1){
                        $GLOBALS["base"]->query("update ".get_table("user_msg_log")."set ul_state=2 where sysid=".$mid);
                    }
                }
                $str= json_encode(array('s'=>"19200",'m'=>"ok",'d'=>$uinfo));
                break;
            default:
                $str= json_encode(array('s'=>"19999",'m'=>"操作出错!"));
                break;
        }
        echo $str;
        exit;
        break;


    //退出登录
    case 20:
        // 验证参数完整性
        if ( empty($gid) || empty($ts) || empty($sign) || empty($uid)) {
            $str = json_encode(array('s' => "20001", 'm' => "参数不完整!"));
            exit($str);
        }
        // 判断后台是否配置对应游戏接口的加密串
        if (!isset($GLOBALS['CONF_GAME_KEY'][$gid])) {
            $str = json_encode(array('s'=>"20002",'m'=>"未配置游戏参数!"));
            exit($str);
        }
        // 验证加密串
        $my_sign2 = md5($uid.$ts.$gid.$GLOBALS["CONF_GAME_KEY"][$gid]);
        if($my_sign2 != $sign){
            $str = json_encode(array('s'=>"20003",'m'=>"加密验证串不正确!"));
            exit($str);
        }
        session_destroy();
        $str= json_encode(array('s'=>"20000",'m'=>"ok"));
        exit($str);
        break;

    default:
        $str= json_encode(array('s'=>"9999",'m'=>"操作出错!"));
	    exit($str);
        break;
}
?>