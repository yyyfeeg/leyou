<?php /* Smarty version 2.6.20, created on 2018-03-13 14:05:56
         compiled from nav.html */ ?>
<div class="navbar">
    <div class="navbar-inner">
        <div class="navbar-container">
            <!-- Navbar Barnd -->
            <div class="navbar-header pull-left">
                <a href="#" class="navbar-brand">
                    <small>
                        <img src="templates/img/logo.png" alt="" />
                    </small>
                </a>
            </div>
            <!-- /Navbar Barnd -->
            <!-- Sidebar Collapse -->
            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="collapse-icon fa fa-bars"></i>
            </div>
            <!-- /Sidebar Collapse -->
            <!-- Account Area and Settings --->
            <div class="navbar-header pull-right">
                <div class="navbar-account">
                    <ul class="account-area">
                        <li>
                            <a class="wave in dropdown-toggle" data-toggle="dropdown" title="Help" href="#">
                                <i class="icon fa fa-warning"></i>
                            </a>
                            <!--Notification Dropdown-->
                            <ul class="pull-right dropdown-menu dropdown-arrow dropdown-notifications">
                                <!-- <li>
                                    <a href="./templates/welcome.html" target='main'>
                                        <div class="clearfix">
                                            <div class="notification-body">
                                                <span class="title">平台使用方法</span>
                                                <span class="description">洞悉一切</span>
                                            </div>
                                        </div>
                                    </a>
                                </li> -->
                                <li class="dropdown-footer ">
                                    <span>
                                        <?php 
                                            echo date("Y/m/d");
                                         ?>
                                    </span>
                                    <span class="pull-right">
                                        <?php 
                                            include("/api/weather.php");
                                         ?>
                                    </span>
                                </li>
                            </ul>
                            <!--/Notification Dropdown-->
                        </li>
                        <li>
                            <a class="wave in dropdown-toggle" data-toggle="dropdown" title="Help" href="#">
                                <i class="icon fa fa-envelope"></i>
                            </a>
                            <!--Messages Dropdown-->
                            <ul class="pull-right dropdown-menu dropdown-arrow dropdown-messages">
                                <li>
                                    <a href="http://exmail.qq.com/login" target="_blank">
                                        <div class="message">
                                            <span class="message-sender">
                                                go to
                                            </span>
                                            <span class="message-body">
                                                QQexmail
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <!--/Messages Dropdown-->
                        </li>

                        <!-- <li> -->
                            <!-- <a class="wave in dropdown-toggle" data-toggle="dropdown" title="Tasks" href="#">
                                <i class="icon fa fa-tasks"></i>
                            </a> -->
                            <!--Tasks Dropdown-->
                           <!--  <ul class="pull-right dropdown-menu dropdown-tasks dropdown-arrow ">
                                <li class="dropdown-header bordered-darkorange">
                                    <i class="fa fa-tasks"></i>
                                    快捷功能
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">快捷功能1</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">快捷功能2</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">快捷功能3</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">快捷功能3</span>
                                        </div>
                                    </a>
                                </li>
                            </ul> -->
                            <!--/Tasks Dropdown-->
                        <!-- </li> -->
                        <li>
                            <a class="login-area dropdown-toggle" data-toggle="dropdown" style="width:188px">
                                <div class="avatar" title="View your public profile">
                                    <img src="<?php echo $this->_tpl_vars['img']; ?>
">
                                </div>
                                <section>
                                    <h2><span class="profile"><span><?php echo $this->_tpl_vars['admin_name']; ?>
</span></span></h2>
                                </section>
                            </a>
                            <!--Login Area Dropdown-->
                            <ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                                <li class="username"><a><?php echo $this->_tpl_vars['admin_name']; ?>
</a></li>
                                <li class="email"><a><?php echo $this->_tpl_vars['email']; ?>
</a></li>
                                <!--Avatar Area-->
                                <li>
                                    <div class="avatar-area">
                                        <img src="<?php echo $this->_tpl_vars['img']; ?>
" class="avatar">
                                    </div>
                                </li>
                                <!--Avatar Area-->
                                <li class="edit">
                                    <a href="#" class="pull-left">上次:<?php echo $this->_tpl_vars['time']; ?>
</a>
                                    <a href="<?php echo $this->_tpl_vars['edit']; ?>
" class="pull-right" target='main'>信息修改</a>
                                </li>
                                <li class="dropdown-footer">
                                    <a href="javascript:void(0);" onclick="loginout()";>
                                        点我退出
                                    </a>
                                </li>
                            </ul>
                            <!--/Login Area Dropdown-->
                        </li>
                </div>
            </div>
            <!-- /Account Area and Settings -->
        </div>
    </div>
</div>
<script type="text/javascript">
    function loginout(){
        if(confirm('系统提示,您确定要退出本次登录吗?')){
            location.href = './index.php?module=login&method=logout';    //返回登录页面
        }
    }
</script>