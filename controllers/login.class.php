<?php
#=======================================
# 	FileName: login.class.php
# 		Desc: 登录控制器类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.01.05
# LastChange: 
#=======================================

class Login extends Controller
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
	 * 展示登录页面
	 * @return [type] [description]
	 */
	public function index()
	{
		$this->display('login.html');
	}

	/**
	 * 检查是否已经登录，若已登录返回用户信息
	 * @return [type] [description]
	 */
	public function checkLogin()
	{
		if ($this->check_login()) {
			$res = array('code'=>'1000','msg'=>'已登录','data'=>array('name'=>$_SESSION['_uname']));
		} else {
			$res = array('code'=>'0000','msg'=>'','data'=>'');
		}
		exit(json_encode($res));
	}

	/**
	 * 用户登录
	 * @return [type] [description]
	 */
	public function gologin()
	{
		$flag = get_param('flag');
		$type = get_param('type','int');
		$jump = get_param('jump');
		$name = get_param('name');
		$pwd  = get_param('pwd');
		$code = get_param('code');
		$ip   = get_user_ip();

		if ($flag == 'login') {
			$pwd_len  = strlen($pwd);
			if (empty($name)) {
				$res = array('code'=>'1001','msg'=>'请填写用户名','data'=>'');
				exit(json_encode($res));
			}
			if (empty($pwd)) {
				$res = array('code'=>'1002','msg'=>'请填写密码','data'=>'');
				exit(json_encode($res));
			}
			if ($pwd_len < 6 || $pwd_len > 20 || !preg_match('/^\S{6,20}$/',$pwd)) {
				$res = array('code'=>'1002','msg'=>'密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)','data'=>'');
				exit(json_encode($res));
			}
			if ($type == 1) {
				if (empty($code)) {
					$res = array('code'=>'1003','msg'=>'请填写验证码','data'=>'');
					exit(json_encode($res));
				}
				if ($code != $_SESSION['code']) {
					$res = array('code'=>'1004','msg'=>'验证码错误','data'=>'');
					exit(json_encode($res));
				}
			}
			//查看redis账号表
        	$result = $GLOBALS["redis"]->sIsMember("name",$name);

        	// 查看redis绑定表
        	$bind_exist = $GLOBALS['redis']->exists("bind_{$name}_info");

        	if ($result || $bind_exist) {
        		if ($result) {
        			// 取出当前用户在redis中的所有信息
        			$arrs = $GLOBALS["redis"]->lRange("{$name}_info",0,-1);
        			$upwd = $arrs[0];
        			$uid  = $arrs[12];
        		} else {
        			// 读取redis绑定表
        			$arrs = $GLOBALS["redis"]->lRange("bind_{$name}_info",0,-1);
        			$upwd = $arrs[1];
        			$uid  = $arrs[2];
        			$name = $arrs[0];
        		}
            	
            	// 判断密码是否正确
	            if ( md5($pwd) == $upwd) {

	                //返回的数据
	                $data = array(
	                    "uname" => $name,
	                    "uid"   => $uid,
	                    "gid"   => $gid,
	                    "utime" => THIS_DATETIME,
	                );
	                //更新用户信息
	                $GLOBALS["redis"]->lSet("{$name}_info",4,THIS_DATETIME);
	                $GLOBALS["redis"]->lSet("{$name}_info",11,$ip);

	                // 同步信息到mysql
	                $mq_data = array(
	                    "ui_lasttime" => THIS_DATETIME,
	                    "ui_lastip"   => $ip
	                );

	                $where_t = " and sysid=".$uid;
	                update_record($GLOBALS['count'],'user_info',$mq_data,array(),$where_t);

	                // 将用户信息写入session,默认注册成功就登录
		            $_SESSION['_uid'] = $uid;
		            $_SESSION['_uname'] = $name;
		            $_SESSION['_truename'] = '';
	                
	                $res = array('code'=>"1000",'msg'=>"登录成功!","data"=>$data);
	            } else {
	                $res = array('code'=>"1005",'msg'=>"密码错误!",'data'=>'');
	            }
        	} else {
        		$res = array('code'=>'1006','msg'=>'登录失败，用户名不存在','data'=>'');
        	}
    		exit(json_encode($res));
		} else {
			$res = array('code'=>'0000','msg'=>'系统错误','data'=>'');
		}
		exit(json_encode($res));
	}

	/**
	 * 安全退出登录
	 * @return [type] [description]
	 */
	public function logout()
	{
		$_SESSION['_uid'] = '';
        $_SESSION['_uname'] = '';
        $_SESSION['_truename'] = '';
        unset($_SESSION["_uid"]);
		unset($_SESSION["_uname"]);
		unset($_SESSION["_truename"]);
        session_destroy();
        $res = array('code'=>'1000','msg'=>'退出成功','data'=>'');
        exit(json_encode($res));
	}

	/**
	 * 获取当前用户登录过哪些游戏
	 * @return [type] [description]
	 */
	public function get_login_game()
	{	
		if ($this->check_login()) {
			cutover_db_base();//切换到base数据库
			$this->_db = $GLOBALS['base'];
			$gameArr = $this->get_user_game(10);//用户最近登录游戏
			if (empty($gameArr)) {
				$res = array('code'=>'0000','msg'=>'你还没玩过游戏哦！','data'=>'');
			} else {
				$res = array('code'=>'1000','msg'=>'最近玩过的游戏','data'=>$gameArr);
			}
		} else {
			$res = array('code'=>'1001','msg'=>'请先登录！','data'=>'');
		}
		exit(json_encode($res));
	}
}
?>