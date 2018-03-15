<?php
#=======================================
# 	FileName: login.class.php
# 		Desc: 登录控制器类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2016.01.05
# LastChange: 
#=======================================

class M_login extends Controller
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
		// 接收跳转页面地址
		$url = get_param('goto')? get_param('goto'):$_SERVER['HTTP_REFERER'];
		$gotoUrl = $url? $url:WEBPATH_DIR_INC.'/index.php?mo=m_users&me=index';
		
		//判断是否已登录
		if ($this->check_login()) {
			show_info('',$gotoUrl,1);
		}

		$this->assign('gotoUrl',$gotoUrl);
		$this->assign('menu_title','账号登录');// 栏目标题
		$this->display('mobile/log_in.html');
	}

	/**
	 * 检查是否已经登录，若已登录返回用户信息
	 * @return [type] [description]
	 */
	public function checkLogin()
	{
		if ($this->check_login()) {
			// 查看用户数据库信息
            $sql = "select ui_ulogo,ui_truename,ui_grow,ui_vip,ui_nickname from ".get_table('user_info')." where sysid = ".$_SESSION['_uid'];
            $result = $GLOBALS['count']->getOne($GLOBALS['count']->Query($sql));

            // 查看用户签到情况
            cutover_db_base();//切换到base数据库
            $sql = "select sl_time from ".get_table('sign_log')." where sl_uid = ".$_SESSION['_uid']." order by sysid desc limit 1";
            $sign_res = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql));
            $sign_flag = (date('Ymd',$sign_res['sl_time']) == date('Ymd',THIS_DATETIME)) ? true:false;
			$data = array(
				'account'  => $_SESSION['_uname'],
				'nickname' => $result['ui_nickname'],
				'vip'      => $result['ui_vip'],
				'ulogo'    => $result['ui_ulogo'],
				'grow'     => $result['ui_grow'],
				'sign_flag'=> $sign_flag
				);
			$res = array('code'=>'1000','msg'=>'已登录','data'=>$data);
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
		$jump = get_param('jump');
		$name = get_param('name');
		$pwd  = get_param('pwd');
		$ip   = get_user_ip();

		if ($flag == 'login') {
			if (empty($name)) {
				$res = array('code'=>'1001','msg'=>'请填写用户名','data'=>'');
				exit(json_encode($res));
			}
			if (empty($pwd)) {
				$res = array('code'=>'1002','msg'=>'请填写密码','data'=>'');
				exit(json_encode($res));
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
        unset($_SESSION["_uid"]);
		unset($_SESSION["_uname"]);
        session_destroy();
        $res = array('code'=>'1000','msg'=>'退出成功','data'=>'');
        exit(json_encode($res));
	}
}
?>