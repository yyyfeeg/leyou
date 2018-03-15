<?php
#=======================================
# 	FileName: signin.class.php
# 		Desc: 签到控制器文件
# 	  Author: cooper
# 	   Email: 459576285@qq.com
# 		Date: 2016.05.24
# LastChange: 
#=======================================

class M_signin extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		cutover_db_base();//切换到count数据库
		//判断是否登录
		if (!$this->check_login()) {
			show_info('请先登录哦！','index.php?mo=m_login&me=index',4);
		}
		$this->assign('callback_url',WEBPATH_DIR_INC.HTML_DIR.'/mobile/index.html');// 返回地址
	}

	/**
	 * 展示签到页面
	 * @return [type] [description]
	 */
	public function index(){
		//确定统一时间
		$time 	= get_param("date")?strtotime(get_param("date")):time();//获取过来时间，默认当前
		$type 	= get_param("type")?get_param("type"):'';//签到标识
		$ntime 	= time();//当前时间戳
		$now_date = date('Ymd');//当前年月日
		$year 	= date("Y",$time);//时间年，日历用
		$month 	= date("m",$time);//时间月，日历用
		$allday = date("t",$time);//时间总天数，日历用
		$nday 	= date("j");	 //当前号数，判断使用
		$stime 	= $year.$month."01";//本月跨度，日历使用
		$etime 	= $year.$month.$allday;//本月跨度,日历使用
		$nstime = date('Ym01', strtotime(date("Y-m-d"))); //本月跨度，权限使用
		$netime = date('Ymd', strtotime("$BeginDate +1 month -1 day"));//本月跨度，权限使用
		$give_arr = array(3=>5,7=>10,$allday=>30);
		//礼包权限
		$sql = "select max(sl_days) as days from ".get_table('sign_log')." where sl_uid=".$this->_uid." AND FROM_UNIXTIME( sl_time, '%Y%m%d' )<=".$netime." and FROM_UNIXTIME( sl_time, '%Y%m%d' )>=".$nstime;
		$gift = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql));
		//是否领取过
		$sql = "select gil_integral from ".get_table('get_integral_log')." where gil_uid=".$this->_uid." AND gil_submitid=1 AND FROM_UNIXTIME( gil_time, '%Y%m%d' )<=".$netime." and FROM_UNIXTIME( gil_time, '%Y%m%d' )>=".$nstime." AND gil_integral in (5,10,30)";
		$query = $GLOBALS['base']->Query($sql);
		$give_arr2 = array_flip($give_arr);
		while ($rows = $GLOBALS['base']->FetchArray($query)) {
			$gifted[$rows['gil_integral']] = $give_arr2[$rows['gil_integral']];
		}
		switch ($type) {
			case '1': //签到
				//检测时间
				if(date(Ym,$time) != date(Ym)){
					//弹出提示信息
					exit(json_encode(array('msg'=>"签到已过期！")));
				}
				//检测参数
				$day = get_param("day")?get_param("day"):exit(json_encode(array('msg'=>"参数错误！请重试！")));//获取天数
				$day != $nday && exit(json_encode(array('msg'=>"只能签到当天！")));
				//检测是否已经签到过
				$sql = "select 1 from ".get_table('sign_log')." where sl_uid=".$this->_uid." AND FROM_UNIXTIME( sl_time, '%Y%m%d' )=".date('Ymd');
				$rows = $GLOBALS['base']->NumRows($GLOBALS['base']->Query($sql));
				if($rows){
					exit(json_encode(array('msg'=>"您已经签到过！")));
				}
				//获取上次签到数据
				$sql = "select sl_lasttime,sl_days,sl_time from ".get_table('sign_log')." where sl_uid=".$this->_uid." AND FROM_UNIXTIME( sl_time, '%Y%m%d' )<=".$netime." and FROM_UNIXTIME( sl_time, '%Y%m%d' )>=".$nstime." order by sl_time desc limit 1";
				$last = $GLOBALS['base']->getOne($GLOBALS['base']->Query($sql));
				if(empty($last)){
					$last['sl_days'] = 0;
					$last['sl_time'] = 0;
				}
				//计算连续签到天数
				$last['sl_days'] = date('Ymd')-date('Ymd',$last['sl_time'])>1 ? 1 :($last['sl_days']+1);
				//计算月数
				$last['sl_days'] = date('Ym')-date('Ym',$last['sl_time'])<1 ? $last['sl_days'] :1;

				//升级用户积分
				cutover_db_count();//切换到count数据库
				$sql = "UPDATE ".get_table('user_info')." SET ui_integral = ui_integral + 1 where sysid=".$this->_uid;
				$res1= $GLOBALS['count']->Query($sql);
				if($res1){
					// 获取当前用户积分
					$user_inte = get_user_inte_vip($GLOBALS['count'],$this->_uid);

					cutover_db_base();//切换到base数据库
					//插入签到记录表
					$sql = "INSERT INTO ".get_table('sign_log')." values (null,".$this->_uid.",".$ntime.",".$last['sl_time'].",". $last['sl_days'] .",'".get_user_ip()."')";
					$GLOBALS['base']->Query($sql);
					//插入积分记录表
					$sql = "INSERT INTO ".get_table('integral_log')." values (null,".$this->_uid.",'".$this->_uname."',1,1,".(int)$user_inte['ui_integral'].",".time().",5,'每天签到','".get_user_ip()."')";
					$GLOBALS['base']->Query($sql);
					exit(json_encode(array('msg'=>"恭喜你签到成功！",'flage'=>1)));
				}
				exit(json_encode(array('msg'=>"签到失败,请重试！")));
				break;

			case '2'://领取礼包
				//检测参数,1 3天 2 7天 3 一个月
				$gifts 	= get_param("gifts");
				if($gifts!=3 && $gifts!=7 && $gifts!=$allday){
					exit(json_encode(array('msg'=>"参数错误~请重试！")));
				}
				//是否过期
				if(date(Ym,$time) != date(Ym)){
					exit(json_encode(array('msg'=>"礼包已经过期！")));
				}
				//是否有权限
				if($gift['days']<$gifts){
					exit(json_encode(array('msg'=>"你还没有该礼包权限！")));
				}
				//是否领取过
				if(@in_array($gifts, $gifted)){
					exit(json_encode(array('msg'=>"你已经领取过！")));
				}		
				//升级用户积分
				cutover_db_count();//切换到count数据库
				$sql = "UPDATE ".get_table('user_info')." SET ui_integral = ui_integral + ".$give_arr[$gifts]." where sysid=".$this->_uid;
				$res2 = $GLOBALS['count']->Query($sql);
				if($res2){
					// 获取当前用户积分
					$user_inte = get_user_inte_vip($GLOBALS['count'],$this->_uid);

					cutover_db_base();//切换到base数据库
					// 插入积分记录表
					$sql = "INSERT INTO ".get_table('integral_log')." values (null,".$this->_uid.",'".$this->_uname."',".$give_arr[$gifts].",1,".(int)$user_inte['ui_integral'].",".time().",4,'".$gifts."天签到礼包','".get_user_ip()."')";
					$GLOBALS['base']->Query($sql);
					exit(json_encode(array('msg'=>'恭喜你领取'.$give_arr[$gifts].'积分成功','flage'=>1)));
				}
				exit(json_encode(array('msg'=>'领取'.$give_arr[$gifts].'积分失败！请重试！')));
				break;
		}
		//当月签到(这里用到传过来的时间)
		$sql = "select * from ".get_table('sign_log')." where sl_uid=".$this->_uid." AND FROM_UNIXTIME( sl_time, '%Y%m%d' )<=".$etime." and FROM_UNIXTIME( sl_time, '%Y%m%d' )>=".$stime;
		$query = $GLOBALS['base']->Query($sql);
		while ($rows = $GLOBALS['base']->FetchArray($query)) {
			$time_arr[date('j',$rows['sl_time'])] = date('j',$rows['sl_time']);//获取时间天数
		}
		//计算前一个月和后一个月
 		$pre_date = date("Ymd", strtotime("-1 months", strtotime($stime)));
 		$next_date = date("Ymd", strtotime("+1 months", strtotime($stime)));
 		if($next_date>date("Ymd")){
 			$next_date = date("Ymd",$time);
 		}

		//领取过的礼包
		$this->assign('gifted',$gifted);
		$this->assign('gift',$gift['days']);
		$this->assign('start',date('w', strtotime(date("Y-m-01",$time))));//当月第一天星期几
		$this->assign('date',date('Ym',$time));//传值年月
		$this->assign('ndate',date('Ym'));//当前年月
		$this->assign('y',$year);//显示年
		$this->assign('m',$month);//显示月
		$this->assign('nday',$nday);//显示日
		$this->assign('end',$allday);
		$this->assign('pre',$pre_date);
		$this->assign('next',$next_date);
		$this->assign('time_arr',$time_arr);
		$this->assign('menu_title',"签到");
		$this->display('mobile/sign_in.html');
	}
}
?>