<?php /* Smarty version 2.6.20, created on 2018-02-09 16:22:26
         compiled from member.html */ ?>
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
    
    <!-- Main Container -->
    <?php if ('mloglist' == $this->_tpl_vars['mlog']): ?>  <!--用户日志列表-->
    <style>
        ul{
            list-style: none;
        }
    </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">用户日志列表</span>
                        <a href="javascript:void(0);" onclick="showhelp();" style="font-weight:bold">显示功能帮助</a>
                        <div class="widget-buttons">
                            <a href="#" data-toggle="maximize">
                                <i class="fa fa-expand"></i>
                            </a>
                            <a href="#" data-toggle="collapse">
                                <i class="fa fa-minus"></i>
                            </a>
                            <a href="#" data-toggle="dispose">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body no-padding">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <tr id='foot'>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索系统ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索帐号ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索游戏ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索渠道ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索子渠道ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索新账号名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索原账号名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="无法使用" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="无法使用" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="无法使用" class="form-control input-sm"></th>
                                </tr>
                                <tr role="row" id='test'>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="templates/js/datatable/jquery.dataTables.min.js"></script>
    <script src="templates/js/datatable/ZeroClipboard.js"></script>
    <script src="templates/js/datatable/dataTables.tableTools.min.js"></script>
    <script src="templates/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="templates/js/datatable/datatables-init.js"></script>
    <script>
        InitiateSimpleDataTable.init();
        InitiateEditableDataTable.init();
        InitiateExpandableDataTable.init();
        $(document).ready(function() {
                /*
                 * Insert a 'details' column to the table
                 */
                var oTable = $('#searchable').dataTable({
                "bProcessing":true,
                "bServerSide":true,
                "sAjaxSource":"index.php?module=member&method=mlistest",
                "sScrollX": "100%",
                "bScrollCollapse": true,
                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                "aaSorting": [[1, 'asc']],
                "aLengthMenu": [
                   [5, 15, 20],
                   [5, 15, 20]
                ],
                "iDisplayLength": 10,
                "oTableTools": {
                    "aButtons": []
                },
                "language": {
                    "search": "",
                    "sLengthMenu": "显示 _MENU_ 项结果",
                    "sProcessing": "处理中...",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上页",
                        "sNext": "下页",
                        "sLast": "末页"
                    },
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                    "infoFiltered":   "(总过滤_MAX_条)",
                    "zeroRecords":    "没有匹配结果",
                },
                "columns": [
                    { "sTitle": "系统ID",class:'123'},
                    { "sTitle": "账号ID" },
                    { "sTitle": "游戏ID" },
                    { "sTitle": "渠道ID" },
                    { "sTitle": "子渠道ID" },
                    { "sTitle": "新帐号名" },
                    { "sTitle": "老帐号名" },
                    { "sTitle": "操作ip" },
                    { "sTitle": "操作内容" },
                    { "sTitle": "操作时间" },
                ]
            });
            $("thead input").keyup(function () {
                /* Filter on the column (the index) of this element */
                oTable.fnFilter(this.value, $("thead input").index(this));
            });

            $('#expandabledatatable_column_toggler input[type="checkbox"]').change(function () {
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
            });

            $('body').on('click', '.dropdown-menu.hold-on-click', function (e) {
                e.stopPropagation();
            })
        } );
    </script>
    <?php endif; ?>  <!--用户日志列表-->

    <?php if ('mlist' == $this->_tpl_vars['mlist']): ?>  <!--用户列表-->
    <style>
        ul{
            list-style: none;
        }
    </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">用户列表</span>
                        <div class="widget-buttons">
                            <a href="#" data-toggle="maximize">
                                <i class="fa fa-expand"></i>
                            </a>
                            <a href="#" data-toggle="collapse">
                                <i class="fa fa-minus"></i>
                            </a>
                            <a href="#" data-toggle="dispose">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body no-padding">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <tr id='foot'>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索系统ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索账号" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索用户类型" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索设备号" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索注册游戏" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索注册渠道" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索注册子渠道" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索注册时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                </tr>
                                <tr role="row" id='test'>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="templates/js/datatable/jquery.dataTables.min.js"></script>
    <script src="templates/js/datatable/ZeroClipboard.js"></script>
    <script src="templates/js/datatable/dataTables.tableTools.min.js"></script>
    <script src="templates/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="templates/js/datatable/datatables-init.js"></script>
    <script>
        InitiateSimpleDataTable.init();
        InitiateEditableDataTable.init();
        InitiateExpandableDataTable.init();
        $(document).ready(function() {
                /* Formatting function for row details */
                function fnFormatDetails(oTable, nTr) {
                    var sOut = '<table>';
                    sOut += '<tr><td colspan="2" align="center">详细列表</td></tr>';
                    sOut += '<tr><td>帐号:</td><td>' + nTr['ui_name']+ '</td></tr>';
                    sOut += '<tr><td>邮箱:</td><td>' + nTr['ui_email']+ '</td></tr>';
                    sOut += '<tr><td>电话:</td><td>' + nTr['ui_phone'] + '</td></tr>';
                    sOut += '<tr><td>注册时间:</td><td>' + nTr['ui_regtime'] + '</td></tr>';
                    sOut += '<tr><td>游戏:</td><td>' + nTr['ui_gid'] + '</td></tr>';
                    sOut += '<tr><td>渠道:</td><td>' + nTr['ui_uaid'] + '</td></tr>';
                    sOut += '<tr><td>操作ip:</td><td>' + nTr['ui_lastip'] + '</td></tr>';
                    sOut += '<tr><td>最后登陆:</td><td>' + nTr['ui_lasttime'] + '</td></tr>';
                    sOut += '<tr><td>会员类型:</td><td>' + nTr['ui_utype'] + '</td></tr>';
                    sOut += '<tr><td>设备号:</td><td>' + nTr['ui_dnum'] + '</td></tr>';
                    sOut += '<tr><td>QQ:</td><td>' + nTr['ui_qq'] + '</td></tr>';
                    sOut += '<tr><td>性别:</td><td>' + nTr['ui_sex'] + '</td></tr>';
                    sOut += '<tr><td>真实姓名:</td><td>' + nTr['ui_truename'] + '</td></tr>';
                    sOut += '<tr><td>操作:</td><td>' + nTr['action'] + '</td></tr>';
                    sOut += '</table>';
                    return sOut;
                }

                /*
                 * Insert a 'details' column to the table
                 */
                var oTable = $('#searchable').dataTable({
                "bProcessing":true,
                "bServerSide":true,
                "sAjaxSource":"index.php?module=member&method=listest",
                "sScrollX": "100%",
                "bScrollCollapse": true,
                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                "aaSorting": [[0, 'asc']],
                "aLengthMenu": [
                   [5, 15, 20],
                   [5, 15, 20]
                ],
                "iDisplayLength": 10,
                "oTableTools": {
                    "aButtons": []
                },
                "language": {
                    "search": "",
                    "sLengthMenu": "显示 _MENU_ 项结果",
                    "sProcessing": "处理中...",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上页",
                        "sNext": "下页",
                        "sLast": "末页"
                    },
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                    "infoFiltered":   "(总过滤_MAX_条)",
                    "zeroRecords":    "没有匹配结果",
                },
                
                "columns": [
                    { "sTitle": "系统ID",class:'123'},
                    { "sTitle": "账号" },
                    { "sTitle": "用户类型" },
                    { "sTitle": "设备号" },
                    { "sTitle": "注册游戏" },
                    { "sTitle": "注册渠道" },
                    { "sTitle": "注册子渠道" },
                    { "sTitle": "注册时间" },
                    { "sTitle": "操作"}
                ]
            });
            $("thead input").keyup(function () {
                /* Filter on the column (the index) of this element */
                oTable.fnFilter(this.value, $("thead input").index(this));
            });

            $('#searchable').on('click', ' tbody tr .123', function () {
                var nTr = $(this).parents('tr')[0];
                if (oTable.fnIsOpen(nTr)) {
                    /* This row is already open - close it */
                    oTable.fnClose(nTr);
                }
                else {
                    /* Open this row */
                    //去异步获取数据
                    var id = $(this).html();
                    var url = "index.php?module=member&method=minfo";
                    var json = {'sysid':id};
                    $.post(url,json,function(data){
                        oTable.fnOpen(nTr, fnFormatDetails(oTable, data), 'details');
                    }, "json");
                }
            });

            $('#expandabledatatable_column_toggler input[type="checkbox"]').change(function () {
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
            });

            $('body').on('click', '.dropdown-menu.hold-on-click', function (e) {
                e.stopPropagation();
            })
        } );
    </script>
    <?php endif; ?>  <!--用户列表-->

    <?php if ('medit' == $this->_tpl_vars['medit']): ?> <!--修改个人信息-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>会员资料修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">会员资料</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">会员ID：</label>
                                    <input type="text" class="form-control" id="uid" name="uid" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['sysid']; ?>
"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">会员账号：</label>
                                   <input type="text" class="form-control" id="uname" name="uname" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_name']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">会员类型：</label>
                                    <input type="text" class="form-control" id="utype" name="utype" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_utype']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">最后登录：</label>
                                    <input type="text" class="form-control" id="lasttime" name="lasttime" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_lasttime']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">绑定手机：</label><span style="color:red">(若会员本无绑定手机,则不允许人为修改)</span>
                                    <input type="tel" class="form-control" id="phone" name="phone" <?php if ($this->_tpl_vars['uinfo']['ui_phone'] == '无'): ?>disabled="disabled"<?php endif; ?> value="<?php echo $this->_tpl_vars['uinfo']['ui_phone']; ?>
"/>
                                    <input type="hidden" class="form-control" id="phone1" name="phone1" value="<?php echo $this->_tpl_vars['uinfo']['ui_phone']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">绑定邮箱：</label><span style="color:red">(若会员本无绑定邮箱,则不允许人为修改)</span>
                                    <input type="email" class="form-control" id="email" name="email" <?php if ($this->_tpl_vars['uinfo']['ui_email'] == '无'): ?>disabled="disabled"<?php endif; ?> value="<?php echo $this->_tpl_vars['uinfo']['ui_email']; ?>
"/>
                                    <input type="hidden" class="form-control" id="email1" name="email1" value="<?php echo $this->_tpl_vars['uinfo']['ui_email']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">真实姓名：</label>
                                    <input type="text" class="form-control" id="truename" name="truename" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_truename']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">注册时间：</label>
                                    <input type="text" class="form-control" id="reg_time" name="reg_time" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_regtime']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">注册游戏：</label>
                                    <input type="text" class="form-control" id="reg_game" name="reg_game" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_gid']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">注册渠道：</label>
                                    <input type="text" class="form-control" id="reg_uaid" name="reg_uaid" disabled="disabled" value="<?php echo $this->_tpl_vars['uinfo']['ui_uaid']; ?>
"/>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return modify_uinfo();">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function modify_uinfo() {
            var sysid = $.trim($('#uid').val());
            var uname = $.trim($('#uname').val());
            var phone = $.trim($('#phone').val());
            var phone1 = $.trim($('#phone1').val());
            var email = $.trim($('#email').val());
            var email1 = $.trim($('#email1').val());
            var str = '';
            var regex  = /^1[3|4|5|7|8][0-9]\d{4,8}$/;

            if (phone == '') {
                alert('手机号码不能为空哦~~');
                return false;
            }
            if (email == '') {
                alert('邮箱地址不能为空哦~~');
                return false;
            }
            if ((phone != phone1 && !regex.test(phone)) || (phone != phone1 && !isNum(phone)) || (phone != phone1 && phone.length != 11)) {
                alert('填写的手机号码不正确');
                return false;
            }
            if (email != email1 && !isEmail(email)) {
                alert('填写的邮箱地址不正确');
                return false;
            }
            if (phone != phone1) {
                str += '绑定新手机：'+phone+"\n";
            } else {
                phone = '';
            }
            if (email != email1) {
                str += '绑定新邮箱：'+email+"\n";
            } else {
                email = '';
            }
            if (str != '') {
                if (confirm('修改内容：\n\n'+str+'您确定修改？')) {
                    $.getJSON('index.php?module=member&method=medit',{act:'modify_uinfo',phone:phone,email:email,sysid:sysid,uname:uname},function(data){
                        if (data.code == 1000) {
                            alert(data.msg);
                            window.location.href = 'index.php?module=member&method=mlist';
                        } else {
                            alert(data.msg);
                        }
                    });
                }
            } else {
                alert('您并没有修改会员资料哦~~');
            }
            return false;
        }
    </script>
    <?php endif; ?>

    <?php if ('mpwedit' == $this->_tpl_vars['mpwedit']): ?> <!--修改会员密码-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>修改会员密码</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">修改会员密码</span>
                    </div>
                    <div class="widget-body">
                    <form action="index.php?module=member&method=mpwedit" method="post" name="addmnger">
                        <input class="form-control" id="sysid" name="sysid" type="hidden" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
"/>
                        <input name="act" value="mpwedit" type="hidden">
                        <div class="form-group">
                            <label for="definpu">帐号名称</label>
                            <input type="text" class="form-control" id="ui_name" name="ui_name" placeholder="必须为字母或数字组合" disabled="disabled" value="<?php echo $this->_tpl_vars['data']['ui_name']; ?>
">
                        </div>
                        <div class="form-group">
                            <label for="definpu">新密码</label>
                            <input type="password" class="form-control" id="ui_pass" name="ui_pass" placeholder="输入密码">
                        </div>
                        <div class="form-group">
                            <label for="definpu">新密码确认</label>
                            <input type="password" class="form-control" id="reui_pass" name="reui_pass" placeholder="再次输入密码">
                        </div>
                        <button type="submit" class="btn btn-blue" onClick="return uppw();">修 改</button>
                        <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        /**
        * 检查个人信息
        * @index 索引
        * @neworold 新添加用户 或者修改用户信息
        */
        function uppw(){
            ui_pass     = $("#ui_pass").val();
            reui_pass   = $("#reui_pass").val();
            if(''==ui_pass || undefined==ui_pass){
                alert("密码怎么能为空呢？");
                return false;
            }
            if(''!=ui_pass || undefined!=ui_pass){
                if(''!=reui_pass || undefined!=reui_pass){
                    if(ui_pass!=reui_pass){
                        alert("两次输入的密码不一样！");
                        return false;
                    }
                }
            }
            return true;
        }
    </script>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['flag'] == 'search'): ?><!--会员快速搜索-->
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">会员搜索</span>
                        <a href="javascript:void(0);" onclick="showhelp();" style="font-weight:bold">显示功能帮助</a>
                        <div class="widget-buttons">
                            <a href="#" data-toggle="maximize">
                                <i class="fa fa-expand"></i>
                            </a>
                            <a href="#" data-toggle="collapse">
                                <i class="fa fa-minus"></i>
                            </a>
                            <a href="#" data-toggle="dispose">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body no-padding">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <div style="margin-left:10px;float:left;">会员ID：
                                    <div class="form-group">
                                      <input type="text" class="form-control" id="uid" name="uid" placeholder="" value=""/>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">会员账号：
                                    <div class="form-group">
                                      <input type="text" class="form-control" id="uname" name="uname" placeholder="" value=""/>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">会员类型：
                                    <div class="form-group">
                                        <select name="utype" id='utype' style="width:100%;">
                                            <option value="0">所有</option>
                                            <option value="1">普通会员</option>
                                            <option value="2">游客账号</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' id='getinfo' class="btn btn-blue" value="搜索"/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="templates/js/datatable/jquery.dataTables.min.js"></script>
    <script src="templates/js/datatable/ZeroClipboard.js"></script>
    <script src="templates/js/datatable/dataTables.tableTools.min.js"></script>
    <script src="templates/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="templates/js/datatable/datatables-init.js"></script>
    <script>
        InitiateSimpleDataTable.init();
        InitiateEditableDataTable.init();
        InitiateExpandableDataTable.init();
        var data='';
        $(document).ready(function() {
            var oTable = '';
            function fnFormatDetails(oTable, nTr) {
                var sOut = '<table>';
                sOut += '<tr><td colspan="2" align="center">详细列表</td></tr>';
                sOut += '<tr><td>帐号:</td><td>' + nTr['ui_name']+ '</td></tr>';
                sOut += '<tr><td>邮箱:</td><td>' + nTr['ui_email']+ '</td></tr>';
                sOut += '<tr><td>电话:</td><td>' + nTr['ui_phone'] + '</td></tr>';
                sOut += '<tr><td>注册时间:</td><td>' + nTr['ui_regtime'] + '</td></tr>';
                sOut += '<tr><td>游戏:</td><td>' + nTr['ui_gid'] + '</td></tr>';
                sOut += '<tr><td>渠道:</td><td>' + nTr['ui_uaid'] + '</td></tr>';
                sOut += '<tr><td>操作ip:</td><td>' + nTr['ui_lastip'] + '</td></tr>';
                sOut += '<tr><td>最后登陆:</td><td>' + nTr['ui_lasttime'] + '</td></tr>';
                sOut += '<tr><td>会员类型:</td><td>' + nTr['ui_utype'] + '</td></tr>';
                sOut += '<tr><td>设备号:</td><td>' + nTr['ui_dnum'] + '</td></tr>';
                sOut += '<tr><td>QQ:</td><td>' + nTr['ui_qq'] + '</td></tr>';
                sOut += '<tr><td>性别:</td><td>' + nTr['ui_sex'] + '</td></tr>';
                sOut += '<tr><td>真实姓名:</td><td>' + nTr['ui_truename'] + '</td></tr>';
                sOut += '<tr><td>操作:</td><td>' + nTr['action'] + '</td></tr>';
                sOut += '</table>';
                return sOut;
            }

               /*异步条件请求*/
            $("#getinfo").bind("click",function(){
                var uid   = $('#uid').val();
                var uname = $('#uname').val();
                var utype = $('#utype').val();
                if (uid == '' && uname == '') {
                    alert('请填写 会员ID 或 会员账号 ');
                    return false;
                }
                //异步请求数据
                $.post("index.php?module=member&method=search",{uid: uid,uname: uname,utype: utype,act:'search'},
                     function(data){
                        data = eval("("+data+")");
                        oTable = $('#searchable').dataTable({"bDestroy":true,"sScrollX": "100%",
                            "bScrollCollapse": true,
                            "searching":true,
                            "aLengthMenu":false,
                            "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                            "aaSorting": [[0, 'desc']],
                            "aLengthMenu": [
                               [5, 15, 20],
                               [5, 15, 20]
                            ],
                            "iDisplayLength": 10,
                            "oTableTools": {
                                "aButtons": []
                            },
                            "language": {
                                "search": "",
                                "sLengthMenu": "显示 _MENU_ 项结果",
                                "sProcessing": "处理中...",
                                "oPaginate": {
                                    "sFirst": "首页",
                                    "sPrevious": "上页",
                                    "sNext": "下页",
                                    "sLast": "末页"
                                },
                                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                                "infoFiltered":   "(总过滤_MAX_条)",
                                "zeroRecords":    "没有匹配结果",
                            },
                            "data": data,
                            "columns": [
                                { "sTitle": "系统ID",class:'123'},
                                { "sTitle": "账号"},
                                { "sTitle": "用户类型"},
                                { "sTitle": "设备号"},
                                { "sTitle": "注册游戏"},
                                { "sTitle": "注册渠道"},
                                { "sTitle": "注册子渠道"},
                                { "sTitle": "注册时间"},
                                { "sTitle": "操作"}
                        ]});//重新调用插件
                        $("thead input").keyup(function () {
                            /* Filter on the column (the index) of this element */
                            oTable.fnFilter(this.value, $("thead input").index(this));
                        });

                        $('#expandabledatatable_column_toggler input[type="checkbox"]').change(function () {
                            var iCol = parseInt($(this).attr("data-column"));
                            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
                        });

                        $('body').on('click', '.dropdown-menu.hold-on-click', function (e) {
                            e.stopPropagation();
                        })
                     }
                 );
            });

            $('#searchable').on('click', ' tbody tr .123', function () {
                var nTr = $(this).parents('tr')[0];
                if (oTable.fnIsOpen(nTr)) {
                    /* This row is already open - close it */
                    oTable.fnClose(nTr);
                }
                else {
                    /* Open this row */
                    //去异步获取数据
                    var id = $(this).html();
                    var url = "index.php?module=member&method=minfo";
                    var json = {'sysid':id};
                    $.post(url,json,function(data){
                        oTable.fnOpen(nTr, fnFormatDetails(oTable, data), 'details');
                    }, "json");
                }
            });

        });
    </script>
    <?php endif; ?>

    <script type="text/javascript">
        /**
        * 检查是否为电子邮件
        * @return true：电子邮件，false:不是电子邮件;
        */
        function isEmail(str) {
            var re = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            return re.test(str);
        }
        /**
        * 检查是否为数字
        * @return true：数字，false:不是数字;
        */
        function isNum(str) {
            var re = /^[\d]+$/;
            return re.test(str);
        }
    </script>
    <script>
        $(function(){
            if('<?php echo $this->_tpl_vars['meg']; ?>
' !=''){
                $("#toast-container").show();
            }else{
                $("#toast-container").hide();
            }
            $("#toast-container").bind('click',function(){
                $("#toast-container").hide();
            });
        });
        function showhelp(){
            $("#toast-container").show();
        }
    </script>
    <div id="toast-container" class="toast-bottom-right"><div class="toast fa-check toast-blue" ><button class="toast-close-button">×</button><div class="toast-message"><?php echo $this->_tpl_vars['meg']; ?>
</div></div></div>
        <!--显示主页信息-->
    <!-- /Main Container -->
    <!--消息提示-->
</body>
<!--  /Body -->
<script src="templates/js/bootstrap.min.js"></script>
<!--Beyond Scripts-->
<script src="templates/js/beyond.min.js"></script>
<!--Page Related Scripts-->
</html>