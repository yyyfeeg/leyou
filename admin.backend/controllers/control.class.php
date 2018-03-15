<?php
#=============================================================================
#     FileName: control.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 控制器基类
#       Author: xiaobei
#        Email: xiaobei [AT] 3737.com
#     HomePage: http://www.3737.com/
#      Version: 0.0.1
#   LastChange: 2013-05-31
#      History:
#=============================================================================
    class Controller extends game_Smarty
    {
        /**
         * @db resource 
	     * @access protected
         */
        protected $db            = null;
        
        /**
         * @file resource
         */
        protected $fileobj        = null;


        /**
         * @my_admin_id var
	     * @access protected
         * 用户id
         */

        protected $my_admin_id = '';

        /**
         * @my_admin var
	     * @access protected
         * 用户的名称
         */

        protected $my_admin    = '';

        /**
         * @myad_realname var
	     * @access protected
         * 用户的真实姓名
         */

        protected $myad_realname = '';

        /**
         * @myad_is_admin var
	     * @access protected
         * 用户是否为超级管理员
         */

        protected $isadmin = '';

        /**
         * @myad_groupid var
	     * @access protected
         * 用户组id
         */

        protected $myad_groupid  = '';

        /**
         * @myad_permission var
	     * @access protected
         * 用户数据权限
         */

        protected $myad_permission= '';

        /**
         * 渠道权限
         * @var string
         */
        protected $myad_permission2 = '';
        
        /**
         * 添加人
         * @var string
         */
        protected $adduser = '';

        /**
         * @myrights var
	     * @access protected
         * 用户权限值
         */

        protected $myrights      = '';

        /**
	     * @param checkParamerIsTrue bool
	     * 用于判断接受的参数是否为空，默认为true
	     */
        protected $checkParamerIsTrue = true;

        /**
	     * Construct
         *
         */

        public function __construct()
        {
            parent::__construct();
            global $DB;
            global $File;
            global $payrange;
            $this->db      = $DB;
            $this->fileobj = $File;
            $this->payrange = $payrange;
            $this->_initalize();
            $this->assign('isadmin',$this->isadmin);
        }

        /**
         * initalize
         * 初始化变量值 包括session数组里面的内容
         *
         */
        private function _initalize()
        {
            if(count($_SESSION)>0)
            {
                foreach($_SESSION as $var => $value)
                {
                    if($var == "code"){
                        continue;
                    }
                    @$this->$var = $value;
                }
            }
        }
        
        /**
         * 检查是否登录
         */
        public function checklogin()
        {
            if(!$_SESSION["my_admin_id"]){
                return false;
            }
            return true;
        }

        /**
         * 检查权限
         */
        public function checkright($rights)
        {
            $arr = explode(',',$_SESSION['myrights']);
            if(!in_array($rights,$arr)){
                return false;
            }
            return true;
        }

        /**
         * 检查登录和权限
         */
        public function check($right)
        {
            if (!$this->checklogin()) 
            {
                echo '<script>location.href="index.php"</script>';
            }
            if($this->isadmin!=1 && !$this->checkright($right) ){
                $this->admin_log("用户于".date('Y-m-d H:i:s')."进行非法操作。");
                exit("你没有权限执行该操作。");
            }
        }

        /**
         * 写入日志
         */
        public function admin_log($msg,$userid="",$uname="",$permission="")
        {
            $do_time    =  THIS_DATETIME;
            $lip        =  return_user_ip();
            
            if(empty($userid)){
                $userid     =  $this->my_admin_id;
            }
            
            if(empty($uname)){
                $uname      =  $this->my_admin;
            }
            
            get_base_conn();//重新切换基本库
    	    $sql = "insert into ".get_table("admin_log")."(al_userid,al_logname,al_content,al_inserttime,al_ip) values('".$userid."','".$uname."','".$msg."','".$do_time."','".$lip."')";
            $rs = $this->db->query($sql);
    	    if($rs){
    		    return true;
    	    }else{
    		    return false;
    	    }
        }
    
        protected function get_child_group($gid=0)
        {
            $sql = "SELECT `sysid` , `ag_fid` FROM ".  get_table('admin_group');
            $rst = $this->db->getAll($this->db->Query($sql));
            $arr = array($gid);
            if (!empty($rst)){
                    $data=$this->travel($rst, $arr);
            }
            return implode(',', $data);	
        }

        /**
         * @explain 递归遍历，返回一个问题分类下的所有子分类
         * @author Ace 411064201@qq.com
         */
        protected  function travel($rst,$arr)
        {
            $mark = 0;
            $tmp = array();
            foreach ($rst as $each)
            {
                    if (in_array($each['ag_fid'], $arr)) {
                            array_push($arr, $each['sysid']);
                            $mark++;
                    }else {
                            $tmp[]=$each;
                    }
            }
            if($mark==0)
            {
                    return $arr;
            }else
            {
                    return $this->travel($tmp, $arr);
            }
        }
        
        /**
         * @explain 刷新权限组（刷新显示排序，图标）
         * @author zexin 516137566@qq.com
         * $gid 权限组ID
         */
        protected  function refresh_group($gid=0){
        
            $and = '';
            if($gid != 0){
                $and = ' and sysid ='.$gid;
            }
            $sql = 'select sysid,ag_rightid from '.get_table('admin_group').' where 1 '.$and;
            $query = $this->db->Query($sql);
            while ($row = $this->db->FetchArray($query)) {
                $new_right = get_json_encode(get_tree($this->db,'',false,false,  explode(',', $row['ag_rightid']))); 
                $data['ag_jmenu'] = $new_right;
                update_record($this->db, 'admin_group', $data, '', ' and sysid='.$row['sysid']);
                $sql_u = 'select sysid,a_rightid from '.get_table('admin').' where a_groupid = '.$row['sysid'];
                $query_u = $this->db->Query($sql_u);
                
                while ($rows = $this->db->FetchArray($query_u)) {
                    if($rows['a_rightid'] == '' || $rows['a_rightid'] == '""'){
                        continue;
                    }
                    $new_right = get_json_encode(get_tree($this->db,'',false,false,  explode(',', $rows['a_rightid']))); 
                    $data_u['a_jmenu'] = $new_right;
                    update_record($this->db, 'admin', $data_u, '', ' and sysid='.$rows['sysid']);
                }
            }
        }
        

        /**
         * @explain 根据操作当前的权限获取 游戏下拉框
         * @param gid 游戏id
         * @return string
         *
         */
        protected function get_select($gid = 0)
        {
            $gameArr = array();
            $gameStr = '';
            $gameArr = get_game($this->db);
            if( count($gameArr) )
            {
                foreach( $gameArr as $k=>$v )
                {
                    $gs = ($gid==$k)? 'selected="selected"':'';
    				$gameStr .="<option value='".$k."' ".$gs.">".$v."</option>";
                }
            }
            return $gameStr;
        }
		
		/**
         * @explain 根据操作当前的权限获取 活动下拉框
         * @param gid 游戏id
         * @return string
         *
         */
        protected function get_select_ac($aid = 0)
        {
            $gameArr = array();
            $gameStr = '';
            $gameArr = get_activity($this->db);
            if( count($gameArr) )
            {
                foreach( $gameArr as $k=>$v )
                {
                    $gs = ($aid==$k)? 'selected="selected"':'';
    				$gameStr .="<option value='".$k."' ".$gs.">".$v."</option>";
                }
            }
            return $gameStr;
        }

        /**
         * @explain 根据操作当前的权限获取 活动下拉框
         * @param gid 游戏id
         * @return string
         *
         */
        protected function get_select_team($tid = 0)
        {
            $gameArr = array();
            $gameStr = '';
            $gameArr = get_team($this->db);
            if( count($gameArr) )
            {
                foreach( $gameArr as $k=>$v )
                {
                    $gs = ($tid==$k)? 'selected="selected"':'';
                    $gameStr .="<option value='".$k."' ".$gs.">".$v."</option>";
                }
            }
            return $gameStr;
        }

        /**
         * @explain 根据操作当前的权限获取 活动下拉框
         * @param gid 游戏id
         * @return string
         *
         */
        protected function get_select_transport($tid = 0)
        {
            $gameArr = array();
            $gameStr = '';
            $gameArr = get_transport($this->db);
            if( count($gameArr) )
            {
                foreach( $gameArr as $k=>$v )
                {
                    $gs = ($tid==$k)? 'selected="selected"':'';
                    $gameStr .="<option value='".$k."' ".$gs.">".$v."</option>";
                }
            }
            return $gameStr;
        }

        /**
         * @explain 根据操作当前的权限获取 礼包卡类型下拉框
         * @param tid 礼包类型id
         * @return string
         *
         */
        protected function get_ctype($tid = 0)
        {
            $ctypeArr = array();
            $ctypeStr = '';
            $ctypeArr = get_ctype($this->db,1);
            if( count($ctypeArr) )
            {
                foreach( $ctypeArr as $k=>$v )
                {
                    $gs = ($tid==$k)? 'selected="selected"':'';
                    $ctypeStr .="<option value='".$k."' ".$gs.">".$v."</option>";
                }
            }
            return $ctypeStr;
        }
    
        /**
    	 * @explain 检验参数
    	 * @param $inputParamer array
    	 * 该数组保存着接收的参数
    	 * @param $checkParamerArr array
    	 * 该数组保存着要验证的参数
    	 * @return string
    	 */
    	protected function checkParamer($inputParamer = array(),$checkParamerArr=array())
    	{
    		foreach($inputParamer as $paramerKey =>$paramerVlaue)
    		{
    			if(array_key_exists($paramerKey,$checkParamerArr))
    			{
    				if(empty($paramerVlaue))
    				{
    					$this->checkParamerIsTrue = false;
    					return $checkParamerArr[$paramerKey];
    				}
    			}
    		}
    	}

        /**
         * @explain 检查游戏id是否在自身的游戏权限里面
         *          超管返回true 非超管则进行判断 存在返回true 相反则返回false
         * @param gid int 游戏id
         * @return boolean
         *
         */
        protected function check_owngame($gid)
        {
            if( $this->isadmin == 1 )
            {
                return true;
            }
            $tmp = explode(',',$this->myad_permission);
            ///return (array_search($gid,$gid) === false )?false:true;
			return ( in_array($gid,$tmp) )?true:false;
        }

        /**
    	 * @explain 析构方法 主要用于对一些资源的回收
    	 *
    	 */
    	public function __destruct()
    	{
            if ( isset($this->db))
            {
                $this->db->Close();
            }
    	}

        /**
         * 写入会员日志
         */
        public function member_log($msg,$userid="",$uname="")
        {
            $do_time    =  THIS_DATETIME;
            $lip        =  return_user_ip();
            
            if(empty($userid)){
                $userid     =  $this->my_admin_id;
            }
            if(empty($uname)){
                $uname      =  $this->my_admin;
            }
            get_base_conn();//重新切换基本库
            $sql = "insert into dcenter_count.`dc_user_log` (dul_userid,dul_name,dul_content,dul_inserttime,dul_ip) values('".$userid."','".$uname."','".$msg."','".$do_time."','".$lip."')";
            $rs = $GLOBALS["conn"]->query($sql);
            if($rs){
                return true;
            }else{
                return false;
            }
        }
    }
?>
