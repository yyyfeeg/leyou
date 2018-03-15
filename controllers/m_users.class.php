<?php
#================================================================
# 	FileName: user_center.class.php
# 		Desc: 用户中心控制器文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.10.21
# LastChange: 
#    TestUrl: index.php?mo=m_users&me=index
#================================================================

class M_users extends Controller
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();

		// 判断是否已登录
		if (!$this->check_login()) {
			show_info('','index.php?mo=m_login&me=index',1);
		}
		$this->assign('menu_flag','users');// 导航标识
		$this->assign('menu_title','用户中心');// 栏目标题
	}

	/**
	 * 用户中心初始化
	 * @return [type] [description]
	 */
	public function index()
	{
		//近期一周领取的礼包码
		$startime =  time() - 3600 * 24 * 7;
		$endtime = time();
		$sql = "select c.sysid,b.gst_name,a.gci_cardnum from ".get_table('cardid_info')." a,".get_table('spree_type')." b,".get_table('frontend_spree')." c where a.gci_keytypeid=b.sysid and a.gci_gid=c.gfs_gid and a.gci_ctypeid=c.gfs_ctypeid and a.gci_keytypeid=c.gfs_keytypeid and a.gci_uid=".$this->_uid." and a.gci_drawtime>=$startime and a.gci_drawtime<=$endtime order by a.gci_drawtime desc";
		$gift = $GLOBALS['base']->getAll($GLOBALS['base']->Query($sql));
		
		cutover_db_count();
		// 用户信息
		$sql = "select * from ".get_table('user_info')." where sysid = ".$this->_uid;
		$userinfo = $GLOBALS['count']->getOne($GLOBALS['count']->Query($sql));
		
		//VIP信息
		switch($userinfo['ui_vip'])
		{
//			case 0:
//				$vip = "VIP0";
//				$vip_next = "VIP1";
//				$grow  = ($userinfo['ui_grow']/300*80)."px";
//			break;
			case 1:
				$vip = array('curr'=>"VIP1",'next'=>"VIP2",'grow'=>"width:".($userinfo['ui_grow']/1000*80)."px");
			break;
			case 2:
				$vip = array('curr'=>"VIP2",'next'=>"VIP3",'grow'=>"width:".($userinfo['ui_grow']/2000*80)."px");
			break;
			case 3:
				$vip = array('curr'=>"VIP3",'next'=>"VIP4",'grow'=>"width:".($userinfo['ui_grow']/5000*80)."px");
			break;
			case 4:
				$vip = array('curr'=>"VIP4",'next'=>"VIP5",'grow'=>"width:".($userinfo['ui_grow']/10000*80)."px");
			break;
			case 5:
				$vip = array('curr'=>"VIP5",'next'=>"VIP6",'grow'=>"width:".($userinfo['ui_grow']/20000*80)."px");
			break;
			case 6:
				$vip = array('curr'=>"VIP6",'next'=>"VIP7",'grow'=>"width:".($userinfo['ui_grow']/50000*80)."px");
			break;
			case 7:
				$vip = array('curr'=>"VIP7",'next'=>"VIP8",'grow'=>"width:".($userinfo['ui_grow']/100000*80)."px");
			break;
			case 8:
				$vip = array('curr'=>"VIP8",'next'=>"",'grow'=>"");
			break;
			default:
				$vip = array('curr'=>"VIP0",'next'=>"VIP1",'grow'=>"width:".($userinfo['ui_grow']/300*80)."px");
		}

		// 绑定手机
		$userinfo['ui_phone'] = empty($userinfo['ui_phone'])? '':$userinfo['ui_phone'];
		$phone = array('phone'=>$userinfo['ui_phone']);

		// 绑定邮箱
		// $email = array('email'=>$userinfo['ui_email']);
		
		//头像
		$ulogo = str_replace('..','',$userinfo['ui_ulogo']);
		
		// 实名认证
		$idcard = array('truename'=>$userinfo['ui_truename'],'idnum'=>$userinfo['ui_idnum']);
		
		$this->assign('username',$this->_uname);
		$this->assign('nickname',$userinfo['ui_nickname']);//昵称
		$this->assign('ulogo',$ulogo);
		$this->assign('phone',$phone);
		// $this->assign('email',$email);
		$this->assign('idcard',$idcard);
		$this->assign('gift',$gift);
		$this->assign('vip',$vip);
		$this->display('mobile/user_center.html');
	}
}
?>