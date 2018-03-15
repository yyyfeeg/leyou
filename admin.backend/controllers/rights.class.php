<?php
/* * ************
  @权限管理类
  @CopyRight teamtop
  @file:rights.class.php
  @author jericho
  @email 190777721@qq.com
  @2013-06-05
 * ************* */
class Rights extends Controller{
    
    function __construct() {
        parent::__construct();
        if (!$this->checklogin()) 
		{
			showinfo("","index.php",1);
		}
    }
   
    function rightlist(){
        if($this->isadmin!=1 && !$this->checkright("rightlist") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $act = get_param('act');
        $totalrecord = $this->db->getOne($this->db->Query("select count(*) as nums from " . get_table("admin_right") ." order by ar_parentid,ar_order desc"));
        $sql        =   "select a.*,b.a_name,b.a_truename from ".get_table("admin_right")." a left join ".  get_table("admin")." b on a.ar_userid = b.sysid order by ar_parentid,ar_order desc";
        $query = $this->db->Query($sql);
        while($rows = $this->db->FetchArray($query)){
            //获取datatables数据
            $rows['ar_addtime'] = date('Y-m-d H:i:s',$rows['ar_addtime']);
            $rows['ar_parentid'] = $rows['ar_parentid'] == 0 ? '是' : '否' ;
            $rows['action'] = "<a href='index.php?module=rights&method=edit&id=".$rows['sysid']."&name=".$rows['ar_rightid']."'>[修改]</a>";
            if($this->isadmin){
                $rows['action'] .= "&nbsp;&nbsp;&nbsp;<a href='index.php?module=rights&method=del&id=".$rows['sysid']."&name=".$rows['ar_rightid']." '>[删除]</a>";
            }
            $right_arr[]=array(
                    $rows['sysid'],
                    $rows['ar_rightid'],
                    $rows['ar_rightname'],
                    $rows['ar_parentid'],
                    $rows['ar_title'],
                    $rows['ar_url'],
                    $rows['ar_order'],
                    $rows['ar_addtime'],
                    $rows['a_name'],
                    $rows['a_truename'],
                    $rows['action'],
                );
        }

        $this->assign("right","rightlist");
        $this->assign("data",get_json_encode($right_arr));
        $this->assign("admin_name",$_SESSION['my_realname']);
        $this->assign("time",date("m/d",$_SESSION['lastdate']));
        $this->assign("email",$_SESSION['email']);
        $this->assign('menus',get_menue($this->db,$_SESSION['myad_groupid']));
        $this->assign('meg','游戏渠道中心欢迎您！');
        $this->display('right.html');
    }
    
    function add(){
        if($this->isadmin!=1 && !$this->checkright("addrights") ){
            showinfo("你没有权限执行该操作。","",2);
        }
        $act    =   get_param("act");
        $func    =   get_param("function");
        if($func == 'getParent'){
            $pid =  get_param("pid",'int');
            $id  =  get_param("id","int");
            $and = ($id)? "and sysid!=".$id:"";
            $str = "";
            if(!$pid)
            {
                echo $str;
                exit;
            }
            $sql = "SELECT `sysid`,`ar_rightname`  FROM ".get_table("admin_right")." WHERE  `ar_parentid`=".$pid; 
            $query = $this->db->query($sql);
            $res = $this->db->getAll($query);
            if(count($res)>0)
            {
                $str     =  "<select name='pid' id='pid'><option value='0'>二级菜单</option>";
                foreach($res as $v){
                    $str .= '<option value="'.$v['sysid'].'">'.$v['ar_rightname'].'</option>';
                }
                $str .= "</select>";
            }
            echo $str;
            exit;
        }

        if($act=="insertright"){
            $data['ar_rightid']    =   get_param("rightid");
            $data['ar_rightname']  =   get_param("rightname");
            $data['ar_parentid']   =   get_param("pid","int")? get_param("pid","int"):get_param("parentid","int");
            $data['ar_title']      =   get_param("righttit");
            $data['ar_url']        =   get_param("righturl");
            $data['ar_order']      =   get_param("order","int");
            $data['ar_ismenu']     =   get_param("ismenu");
            $data['ar_ismenu']     =   (empty($data['ar_ismenu']))?2:1;
            $data['ar_islock']     =   get_param("islock");
            $data['ar_islock']     =   (empty($data['ar_islock']))?2:1;
            $data['ar_addtime']    =   THIS_DATETIME;
            $data['ar_userid']     =   $this->my_admin_id;
            $where                 =   array("ar_rightid"=>$data['ar_rightid']);
            $isexits               =   exist_check($this->db, "admin_right", $where);       //判断是否已经存在此权限编号
            if($isexits){
                $this->admin_log("添加新权限".$data['ar_rightid']."失败，原因：权限编号已存在");
                showinfo("该权限已存在!","",3);
            }
            $rst = add_record($this->db, "admin_right",$data);
            if($rst['rows']>0){ 
                $this->admin_log("添加权限".$data['ar_rightid']."成功");
                showinfo("添加成功!","index.php?module=rights&method=rightlist",4);
            }else{
                $this->admin_log("添加权限".$data['ar_rightid']."失败，原因：数据库插入失败");
                showinfo("添加失败,请重新再试!","",3);  
            }
        }
        //目录,当且仅当ar_ismenu为1,并且ar_parentid=0时,结果为目录
	    $categorysql = 'select sysid, ar_rightname from '.get_table("admin_right").' where ar_ismenu=1 and ar_parentid=0';
	    $categoryrst = $this->db->query($categorysql);
	    $categoryArr = $this->db->getAll($categoryrst);
        //var_dump($categoryArr);die;
        $this->assign("right","addright");
        $this->assign("category",$categoryArr);
        $this->assign("admin_name",$_SESSION['my_realname']);
        $this->assign("time",date("m/d",$_SESSION['lastdate']));
        $this->assign("email",$_SESSION['email']);
        $this->assign('menus',get_menue($this->db,$_SESSION['myad_groupid']));
        $this->assign('meg','已进入添加页面！');
        $this->display('right.html');
    }
    
    function edit(){
		if($this->isadmin!=1){
            showinfo("仅超级管理员才能够执行该操作。","",2);
        }
        $where['sysid']   =   get_param("id","int");
        $act              =   get_param("act");

        if($act == 'updateright'){
            $data['ar_rightid']    =   get_param("rightid");
            $data['ar_rightname']  =   get_param("rightname");
            $ppid                  =   get_param("pid","int")? get_param("pid","int"):get_param("ppid","int");
            $data['ar_parentid']   =   ($ppid)? $ppid:get_param("parentid","int");
            $data['ar_title']      =   get_param("righttit");
            $data['ar_url']        =   get_param("righturl");
            $data['ar_order']      =   get_param("order","int");
            $data['ar_islock']     =   get_param("islock");
            $data['ar_ismenu']     =   get_param("ismenu");
            $data['ar_ismenu']     =   (empty($data['ar_ismenu']))?2:1;
            $data['ar_islock']     =   (empty($data['ar_islock']))?2:1;
            $data['ar_addtime']    =   THIS_DATETIME;
            $isexits               =   $this->db->getOne($this->db->query("select sysid from ".get_table("admin_right")." where ar_rightid='".$data['ar_rightid']."' and sysid!=".$where['sysid']));
            if(!empty($isexits['sysid'])){
                $this->admin_log("修改权限".$data['ar_rightid']."失败，原因：权限编号已存在");
                showinfo("该权限已存在!","",3);
            }
            $data['ar_userid']     =   $this->my_admin_id;

            $res  =  update_record($this->db, "admin_right",$data,$where,"");
            if($res){
                $this->admin_log("修改权限".$data['ar_rightid']."成功");
                //刷新权限组
                $this->refresh_group();
                showinfo("修改成功!","index.php?module=rights&method=rightlist",4);
                
                
            }else{
                $this->admin_log("修改权限".$data['ar_rightid']."失败，原因：数据库插入失败");  
                showinfo("修改失败!请重新再试","",3);
            }
            exit;
        }
        $sql              =   "select * from ".get_table("admin_right")." where sysid=".$where['sysid'];
        $rightinfo        =   $this->db->getOne($this->db->query($sql));
        $str              = "";
        
        if($rightinfo['ar_parentid'] != 0)
        {
        
            $psql         = "SELECT a2.sysid as gpid,a2.ar_rightname as gpname,a1.`sysid` as pid,a1.`ar_rightname` as pname FROM ".get_table("admin_right")."  as a1 left join ".get_table("admin_right")." as a2 on a1.ar_parentid=a2.sysid WHERE  a1.`ar_ismenu`=1 and a1.sysid=".$rightinfo['ar_parentid'];
            $prightinfo   =   $this->db->getOne($this->db->query($psql));
            if($prightinfo['gpid'] && $prightinfo['gpname'])
            {
                $str      =  "<select name='pid' id='pid'><option value='".$prightinfo['pid']."'>".$prightinfo['pname']."</option></select>";
                $rightinfo['ar_parentid'] = $prightinfo['gpid']; 
            }
        }

        //目录,当且仅当ar_ismenu为1,并且ar_parentid=0时,结果为目录
	    $categorysql = 'select sysid, ar_rightname from '.get_table("admin_right").' where ar_ismenu=1 and ar_parentid=0';
	    $categoryrst = $this->db->query($categorysql);
	    $categoryArr = $this->db->getAll($categoryrst);
        $this->assign("id",$where['sysid']);
        $this->assign("str",$str);
        $this->assign("changeyesmenu",$rightinfo);
        $this->assign("category",$categoryArr);
        $this->assign("right","changeright");
        
        $this->assign("admin_name",$_SESSION['my_realname']);
        $this->assign("time",date("m/d",$_SESSION['lastdate']));
        $this->assign("email",$_SESSION['email']);
        $this->assign('menus',get_menue($this->db,$_SESSION['myad_groupid']));
        $this->assign('meg','已进入添加页面！');
        $this->display('right.html');
    }
    
    function del(){
		if($this->isadmin!=1){
            showinfo("仅超级管理员才能够执行该操作。","",2);
        }
        $where['sysid']    =   get_param("id","int");
        $name              =   get_param("name");
        $ret = delete_record($this->db, "admin_right",$where);
        if($ret){
             $this->admin_log("删除权限 ".$name."成功");
             showinfo("删除成功!","index.php?module=rights&method=rightlist",4);
        }else{
             $this->admin_log("删除权限 ".$name."失败，原因:数据库删除失败");
             showinfo("删除失败,请重试!","",3);
        }
    }
}
?>
