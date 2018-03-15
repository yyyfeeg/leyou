<?php
#============================================
# 	FileName: service.class.php
# 		Desc: 客服中心
# 	  Author: dafan
# 	   Email: 316699855@qq.com
# 		Date: 2015.12.21
# LastChange: 
#============================================

class Service extends Controller
{
	private $gameset="api/service/game/";
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assign("uid",$this->_uid);//crm用户uid
		$this->assign("uname",$this->_uname);//crm用户名

		$this->assign("webname",WEBNAME);
		$this->assign("keywords",KEYWORDS);
		$this->assign("desc",DESC);
		$this->assign("powerby",POWERBY);
		$this->assign("beian",BEIAN);
	}
	
	/**
	 * 客服中心首页
	 */
	public function index() {

		$filepath=WEBPATH_DIR.$this->gameset."gamelist/game.txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		
		$this->assign("gamelist",$resArr["d"]);//游戏列表数组
		$this->assign("webpath",WEBPATH_DIR_INC);//网站路径
		
		$this->assign("method","questype");//下一步的方法：选择问题类型
		
		$this->assign("currnav","service_home");//当前选中的导航菜单
		$this->display('service_index.html');
    }
	
	/**
	 * 官方QQ验证
	 */
	public function qqverify(){
				
		$this->assign("uid",$this->_uid);//crm用户uid
		
		$this->assign("currnav","service_qqverify");//当前选中的导航菜单
		$this->display('service_qqverify.html');
	}
	
	/**
	 * 选择问题类型
	 */
	public function questype(){
		
		$tid         =   get_param('tid',"int");//获取团队ID
		$gid         =   get_param('gid',"int");//获取游戏ID
		
		$filepath=WEBPATH_DIR.$this->gameset."onetype/type_".$tid."_".$gid.".txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		
		$k=1;
		if($resArr["d"]){
			foreach($resArr["d"] as $key => $value){
				switch($k){
					case 1:
						$resArr["d"][$key]['class']='';
					break;
					case 2:
						$resArr["d"][$key]['class']=' class="ts_zh"';
					break;
					default:
						$resArr["d"][$key]['class']=' class="no_m ts_cz"';
					break;
				}
				$k++;
			}
		}
		
		$this->assign("onetype",$resArr["d"]);//一级问题类型数组

		$this->assign("tid",$tid);
		$this->assign("gid",$gid);
		
		$this->assign("currnav","service_home");//当前选中的导航菜单
		$this->display('service_question_type.html');
	}
	
	/**
	 * 问题提交
	 */
	public function submission(){
//		if(!$this->check_login()){
//			show_info("请先登录！", "index.php?mo=login&me=Index", 4);
//		}
		
		$tid         =   get_param('tid',"int");//获取团队ID
		$gid         =   get_param('gid',"int");//获取游戏ID
		
		$type        =   get_param('type',"int");//获取一级问题类型
		
		$filepath=WEBPATH_DIR.$this->gameset."onetype/type_".$tid."_".$gid.".txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		$this->assign("onetype",$resArr["d"]);//一级问题类型数组
		
		$filepath=WEBPATH_DIR.$this->gameset."twotype/type_".$tid."_".$gid."_".$type.".txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		$this->assign("twotype",$resArr["d"]);//二级问题类型数组
		
		$filepath=WEBPATH_DIR.$this->gameset."gamedb/db_".$tid."_".$gid.".txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		$this->assign("gamedb",$resArr["d"]);//游戏服数组
				
		$this->assign("tid",$tid);//团队ID
		$this->assign("gid",$gid);//游戏ID
				
		$this->assign("type",$type);
		
		$this->assign("currnav","service_home");//当前选中的导航菜单
		$this->display('service_submission.html');
	}
	
	/**
	 * 常见问题列表/详细
	 */
	public function problems(){
		
		$tid         =   get_param('tid',"int");//获取团队ID
		$gid         =   get_param('gid',"int");//获取游戏ID
		$id         =   get_param('id',"int");//获取问题ID
		$str         =   get_param('str');//获取搜索关键字

		$filepath=WEBPATH_DIR.$this->gameset."onetype/type_".$tid."_".$gid.".txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		$this->assign("onetype",$resArr["d"]);//一级问题类型数组
		
		$filepath=WEBPATH_DIR.$this->gameset."problem/wt_".$tid."_".$gid.".txt";
		$json = $this->_file->readAll($filepath);
		$resArr = json_decode($json, true);
		
		if(!empty($str)){
			foreach($resArr["d"] as $key => $value) {
				if(!strstr($value["title"],$str) && !strstr($value["content"],$str)){
					unset($resArr["d"][$key]); 
				}
			}
		}
		
		if($id != 0){
			$resArr["d"]=array("$id"=>$resArr["d"][$id]);
		}
		
		$totalrecord = count($resArr["d"],0);//count($resArr,COUNT_NORMAL)记录数量
		$pagesize = 5;//分页大小
		$pages = ceil($totalrecord/$pagesize);//总页数
		$currpage    =   get_param('page',"int");//获取当前页ID
		if(empty($currpage) || $currpage<1){$currpage=1;}//如果当前页为空、小于1，重置为1
		if($currpage>$pages){$currpage=$pages;}//如果当前页大于总页数时，重置为总页数
		
		$k=1;
		$i=1;
		$pblist=array();
		foreach($resArr["d"] as $key => $value) {
			if($k>($currpage-1)*$pagesize && $k<=$currpage*$pagesize){
				$pblist[$i]=$value;
				$i++;
			}
			$k++;
		}		
		
		$this->assign("pblist",$pblist);//常见问题数组
		$this->assign("totalpage",$pages);//分页数量
		$this->assign("currpage",$currpage);//当前分页
		$this->assign("totalrecord",$totalrecord);//总记录数量
		$this->assign("search",$str);//当前分页
		
		$this->assign("tid",$tid);//团队ID
		$this->assign("gid",$gid);//游戏ID
		$this->assign("id",$id);//问题ID
				
		$this->assign("currnav","service_home");//当前选中的导航菜单
		$this->display('service_problems.html');
	}
	
	/**
	 * 我的提单列表
	 */
	public function mylading() {
//		if(!$this->check_login()){
//			show_info("请先登录！", "index.php?mo=login&me=Index", 4);
//		}
		$time         =   get_param('time',"int");//获取查询的时间
		if($time > 0 && $time<=3){
			$timeStr="&time=".$time;
			$statustr=3;
		}else{
			$timeStr="";
			$time=0;
		}
		
		$currpage         =   get_param('page',"int");//获取当前页数
		if($currpage==0){$currpage=1;}
		$json = file_get_contents(WEBPATH_DIR_INC."/api/service/service.api.php?act=6&uid=".$this->_uid.$timeStr);
		$resArr = json_decode($json, true);
		
		if($timeStr==""){
			$dataArr=$resArr["d"]["1"];
		}else{
			$dataArr=$resArr["d"];
		}
		
		$pagesize=2;//分页大小
		$totalrecord=count($dataArr);//总记录数
		$totalpage = ceil($totalrecord/$pagesize);//总页数
		
		$k=1;
		$i=1;
		$mylist=array();
		if($dataArr){
			foreach($dataArr as $key => $value) {
				if($k>($currpage-1)*$pagesize && $k<=$currpage*$pagesize){
					$mylist[$i]=$value;
					$i++;
				}
				$k++;
			}
		}
		
		$this->assign("mylist",$mylist);//提单列表数组
		$this->assign("totalrecord",$totalrecord);
		$this->assign("totalpage",$totalpage);//总分页数
		$this->assign("currpage",$currpage);//当前分页
		$this->assign("time",$time);//当前页面的类型（处理中||已处理：最近三天、最近一周和最近半个月）
		$this->assign("timeStr",$timeStr);//分页链接的时间类型
				
		$this->assign("tid",$tid);//团队ID
		$this->assign("gid",$gid);//游戏ID
		$this->assign("id",$id);//问题ID
				
		$this->assign("currnav","service_home");//当前选中的导航菜单		
		$this->display('service_mylading.html');
    }
	/**
	 * 登录检测
	 */
    public function checklogin()
    {
        if(!$this->check_login()){
			$res = array('s'=>'1001','m'=>'请先登录！','d'=>'');
			$json = json_encode($res);
			exit($json);
		}else{
			$res = array('s'=>'1','m'=>'您已登录！','d'=>'','name'=>$_SESSION["this_member_uname"]);
			$json = json_encode($res);
			exit($json);
		}
    }
}
?>