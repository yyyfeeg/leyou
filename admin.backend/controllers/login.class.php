<?php

/* * ************
  @登录，退出，获取菜单列表
  @CopyRight teamtop
  @file:login.class.php
  @author cooper
  @email 459576285@qq.com
  @2015-07-30
 * ************* */

class Login extends Controller {

    private $user;      //用户名
    private $pwd;       //密码
    private $code;      //验证码
    private $ip;        //ip地址
    private $act;
    private $msg;       //日志内容
    private $counts = 10; //密码错误次数
    private $groupid;   //用户组ID

    function __construct() {
        parent::__construct();
    }

    function index() {
        if (!$this->checklogin()) {
            $this->act = get_param("act");
            $this->user = get_param("username");
            $this->code = get_param("CheckCode","int");
            $this->pwd = get_param("password");

            if ($this->act == 'gologin') {
                if (empty($this->user)) {
                    showinfo("请输入用户名!", "index.php", 4);
                }

                if (empty($this->pwd)) {
                    showinfo("请输入密码!", "index.php", 4);
                }

                $pwd_len  = strlen($this->pwd);
                if ($pwd_len < 6 || $pwd_len > 20 || !preg_match('/^\S{6,20}$/',$this->pwd)) {
                    showinfo("请输入6-20的字符密码!", "index.php", 4);
                }

                if (empty($this->code)) {
                    showinfo("请输入验证码!", "index.php", 4);
                }

                if (!empty($_SESSION['code']) && $this->code != $_SESSION['code']) {
                    $_SESSION['code'] = "";
                    showinfo("验证码输入错误", "index.php", 4);
                }

                $ip = return_user_ip();
                $sql = "select * from " . get_table("admin") . " where a_name='" . $this->user . "'";
                $query = $this->db->query($sql);
                $info = $this->db->getOne($query);

                if (!empty($info)) {
                    //是否被锁定
                    if ($info['a_islock'] == 1) {
                        showinfo("账号被冻结，请联系管理员", "index.php", 4);
                    }

                    //密码不正确
                    if (md5($this->pwd) != $info['a_pwd']) {
                        if ($info['a_failtimes'] >= $this->counts) {
                            $this->db->query("update " . get_table("admin") . " set a_islock=1 where sysid=" . $info['sysid']);
                            echo "<script>alert('您已连续登录10次,账号已被锁定!');location.href='index.php';</script>";
                            $this->msg = "登录失败次数超过10次，该账号已被锁定";
                        }
                        echo "<script>alert('密码错误,请重新输入!');location.href='index.php';</script>";
                        $this->db->query("update " . get_table("admin") . " set a_lastdate=" . THIS_DATETIME . ", a_lastip='$ip', a_lognum=a_lognum+1,a_failtimes=a_failtimes+1 where sysid=" . $info['sysid']);
                        $this->msg = "登录后台失败,密码错误";
                    } else {
                        $_SESSION["my_admin_id"]     =  $info["sysid"];
                        $_SESSION['my_admin']        =  $info['a_name'];
                        $_SESSION['myad_realname']   =  $info['a_truename'];
                        $_SESSION['isadmin']         =  $info['a_isadmin'];
                        $_SESSION['myad_groupid']    =  $info['a_groupid'];
                        $_SESSION['myad_permission'] =  $info['a_permission'];
                        $_SESSION['myad_permission2'] =  $info['a_permission2'];
                        $_SESSION["adduser"]         =  $info["a_userid"];
                        $_SESSION["jmenu"]           =  $info["a_jmenu"];
                        $_SESSION["lastdate"]        =  $info["a_lastdate"];
                        $_SESSION["email"]           =  $info["a_email"];
                        if(empty($info["a_rightid"])){
                            //获取该用户对应的权限
                            $rightinfo  =   $this->db->getOne($this->db->query("select ag_rightid from ".get_table("admin_group")." where sysid=".$info["a_groupid"]));   
                            $_SESSION['myrights']        =  $rightinfo['ag_rightid'];
                        }else{
                            $_SESSION["myrights"]        =  $info["a_rightid"];
                        }
                        $sql = "update " . get_table("admin") . " set a_lastdate=" . THIS_DATETIME . ", a_lastip='$ip', a_lognum=a_lognum+1,a_failtimes=0 where sysid=" . $info['sysid'];
                        $this->db->query($sql);
                        $this->msg = "成功登录进入后台";
                        echo "<script>alert('登录成功!');location.href='index.php'</script>";
                    }
                } else {
                    $info['sysid'] = 0; //当id为0时则代表不存在该管理员
                    echo "<script>alert('管理员不存在!');location.href='index.php'</script>";
                    $this->msg = $this->user."尝试登录后台,但失败了,原因:不存在该管理员";
                }
                //写入日志
                $this->admin_log($this->msg,$info['sysid'],$info['a_name'],$info['a_permission'],$info['a_permission2']);
            }
            $this->display('login.html');
        } else {
            $this->background();
        }
    }

    //获取当前用户信息
    function background() {
        if(!empty($_SESSION["jmenu"]) && $_SESSION["jmenu"] != '""'){
            $one['menu'] = $_SESSION["jmenu"];
        }else{
            $sql   = "select ag_jmenu as menu from ".get_table("admin_group")." where sysid=".$_SESSION['myad_groupid'];
            $one   = $this->db->getOne($this->db->query($sql));
        }
        $ltime = $this->db->getOne($this->db->query("select a_lastdate,a_email,a_img,a_name from ".get_table("admin")." where sysid=".$_SESSION["my_admin_id"]));
        $this->assign("admin_name",$_SESSION['my_realname']);
        $this->assign("time",date("m/d H:i",$ltime['a_lastdate']));
        $this->assign("email",$_SESSION['email']);
        $this->assign("img",$ltime['a_img']);
        $this->assign('menus',get_menue($this->db,$_SESSION['myad_groupid']));
        $this->assign('edit',"./index.php?module=user&method=edit&uid=".$_SESSION['my_admin_id']."&uname=".$ltime['a_name']);
        $this->display('common.html');
    }

    //获取用户权限信息
    function getrights() {
        $isadmin = $_SESSION['isadmin'];
        $sql = "select ag_rightid,ag_groupname,ag_trid from " . get_table("admin_group") . " where sysid=" . $_SESSION['myad_groupid'];
        $query = $this->db->query($sql);
        $groupinfo = $this->db->getOne($query);
        $groupright = $groupinfo['ag_rightid'];
        $all = $isadmin == 1 ? true : false;
        $menu = get_tree($this->db, $_SESSION['myad_groupid'], $all);
        return $menu;
    }

    //退出
    function logout() {
        $_SESSION["my_admin_id"] = '';
        unset($_SESSION["my_admin_id"]);
        session_destroy();
        showinfo("您已成功退出管理后台!","index.php",4);
    }

}
?>


