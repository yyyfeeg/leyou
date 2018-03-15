<?php /* Smarty version 2.6.20, created on 2018-03-13 14:05:56
         compiled from common.html */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- Head -->
<head>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<!-- /Head -->
<!-- Body -->
<body>
    <!-- Loading Container -->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loading.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <!--  /Loading Container -->
    <!-- Navbar -->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "nav.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <!-- /Navbar -->

    <!-- Main Container -->
        <div class="main-container container-fluid">
        <!-- Page Container -->
        <div class="page-container">
            <!-- left -->
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <!-- /left -->
            <!-- Page Content -->
            <div class="page-header position-relative">
                <!--Header Buttons-->
                <div class="header-buttons">
                    <a href="#" class="sidebar-toggler">
                        <i class="fa fa-arrows-h"></i>
                    </a>
                    <a href="" id="refresh-toggler" class="refresh">
                        <i class="glyphicon glyphicon-refresh"></i>
                    </a>
                    <a href="#" id="fullscreen-toggler" class="fullscreen">
                        <i class="glyphicon glyphicon-fullscreen"></i>
                    </a>
                </div>
                <!--Header Buttons End-->
            </div>
            <div class="page-content">
                <iframe id="main" scrolling="auto" name="main" src="templates/welcome.html" frameborder="0" width="100%" height="1000px"></iframe>
            </div>
            <!-- /Page Content --> 
        </div>
        <!-- /Page Container -->
        <!-- Main Container -->
    </div>
</body>
<!--  /Body -->
<script src="templates/js/bootstrap.min.js"></script>
<!--Beyond Scripts-->
<script src="templates/js/beyond.min.js"></script>
<!--Page Related Scripts-->
</html>