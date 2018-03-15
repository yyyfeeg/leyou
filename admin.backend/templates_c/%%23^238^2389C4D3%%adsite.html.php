<?php /* Smarty version 2.6.20, created on 2018-03-03 10:19:07
         compiled from adsite.html */ ?>
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
     <?php if ('adsitelist' == $this->_tpl_vars['adsite']): ?>  <!--广告列表-->
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
                        <span class="widget-caption">广告列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索广告" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索广告描述" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索渠道" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索添加人" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索添加时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索状态" class="form-control input-sm"></th>
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
                "sScrollX": "100%",
                "bScrollCollapse": true,
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
                
                "data": <?php echo $this->_tpl_vars['data']; ?>
,
                "columns": [
                    { "sTitle": "系统ID",class:'123'},
                    { "sTitle": "广告" },
                    { "sTitle": "广告描述" },
                    { "sTitle": "广告渠道" },
                    { "sTitle": "添加人" },
                    { "sTitle": "添加时间" },
                    { "sTitle": "状态" },
                    { "sTitle": "操作"}
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
    <?php endif; ?>  <!--广告列表-->

    <?php if ('adsiteadd' == $this->_tpl_vars['adsite']): ?> <!--广告添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>广告添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">广告添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=adsite&method=adsiteadd" method="post" id='myform' name='myform'>
                                <input name="act" value="partadadd" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">广告名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="广告名" value="<?php echo $this->_tpl_vars['data']['gp_name']; ?>
" onblur="clean_error('name')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">广告描述：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="descrip" name="descrip" placeholder="广告描述" value="<?php echo $this->_tpl_vars['data']['gp_descrip']; ?>
" onblur="clean_error('descrip')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">渠道选择：</label><span style="color:red">*</span>
                                    <select name="transport" id='transport' style="width:100%;" onchange="clean_error('transport')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gp_transport'] == 0): ?>selected="selected"<?php endif; ?>>渠道选择</option>
                                        <option value="1" <?php if ($this->_tpl_vars['data']['gp_transport'] == 1): ?>selected="selected"<?php endif; ?>>安卓</option>
                                        <option value="2" <?php if ($this->_tpl_vars['data']['gp_transport'] == 2): ?>selected="selected"<?php endif; ?>>ios</option>
                                        <option value="3" <?php if ($this->_tpl_vars['data']['gp_transport'] == 3): ?>selected="selected"<?php endif; ?>>ios越狱</option2>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=adsite&method=adsiteadd','myform',0)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--广告添加-->

    <?php if ('edit' == $this->_tpl_vars['edit']): ?> <!--广告修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>广告修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">广告修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=adsite&method=edit" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden">
                                <input name="sysid" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">广告名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="广告名" value="<?php echo $this->_tpl_vars['data']['gp_name']; ?>
" onblur="clean_error('name')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">广告描述：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="descrip" name="descrip" placeholder="广告描述" value="<?php echo $this->_tpl_vars['data']['gp_descrip']; ?>
" onblur="clean_error('descrip')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">渠道选择：</label><span style="color:red">*</span>
                                    <select name="transport" id='transport' style="width:100%;" onchange="clean_error('transport')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gp_transport'] == 0): ?>selected="selected"<?php endif; ?>>渠道选择</option>
                                        <option value="1" <?php if ($this->_tpl_vars['data']['gp_transport'] == 1): ?>selected="selected"<?php endif; ?>>安卓</option>
                                        <option value="2" <?php if ($this->_tpl_vars['data']['gp_transport'] == 2): ?>selected="selected"<?php endif; ?>>ios</option>
                                        <option value="3" <?php if ($this->_tpl_vars['data']['gp_transport'] == 3): ?>selected="selected"<?php endif; ?>>ios越狱</option2>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=adsite&method=edit','myform',0)">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--广告修改-->

    <?php if ('partadlist' == $this->_tpl_vars['adsite']): ?>  <!--子广告列表-->
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
                        <span class="widget-caption">子广告列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索子广告" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索广告描述" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索主广告" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索添加人" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索添加时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索状态" class="form-control input-sm"></th>
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
                "sScrollX": "100%",
                "bScrollCollapse": true,
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
                
                "data": <?php echo $this->_tpl_vars['data']; ?>
,
                "columns": [
                    { "sTitle": "系统ID",class:'123'},
                    { "sTitle": "子广告" },
                    { "sTitle": "广告描述" },
                    { "sTitle": "主广告" },
                    { "sTitle": "添加人" },
                    { "sTitle": "添加时间" },
                    { "sTitle": "状态" },
                    { "sTitle": "操作"}
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
    <?php endif; ?>  <!--子广告列表-->

    <?php if ('partadadd' == $this->_tpl_vars['adsite']): ?> <!--子广告添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>广告添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">广告添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=adsite&method=partadadd" method="post" id='myform' name='myform'>
                                <input name="act" value="partadadd" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">广告名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="广告名" value="<?php echo $this->_tpl_vars['data']['gp_name']; ?>
" onblur="clean_error('name')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">广告描述：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="descrip" name="descrip" placeholder="广告描述" value="<?php echo $this->_tpl_vars['data']['gp_descrip']; ?>
" onblur="clean_error('descrip')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">主广告选择：</label><span style="color:red">*</span>
                                    <select name="aid" id='aid' style="width:100%;" onchange="clean_error('aid')">
                                        <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                            <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['data']['gp_aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=adsite&method=partadadd','myform',0)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--子广告添加-->
    <?php if ('editson' == $this->_tpl_vars['edit']): ?> <!--子广告修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>广告修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">广告修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=adsite&method=editson" method="post" id='myform' name='myform'>
                                <input name="act" value="editson" type="hidden">
                                <input name="sysid" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">广告名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="广告名" value="<?php echo $this->_tpl_vars['data']['gp_name']; ?>
" onblur="clean_error('name')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">广告描述：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="descrip" name="descrip" placeholder="广告描述" value="<?php echo $this->_tpl_vars['data']['gp_descrip']; ?>
" onblur="clean_error('descrip')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">主广告选择：</label><span style="color:red">*</span>
                                    <select name="aid" id='aid' style="width:100%;" onchange="clean_error('aid')">
                                        <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                            <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['data']['gp_aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=adsite&method=editson','myform',0)">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--子广告修改-->


     <?php if ('adinfoadd' == $this->_tpl_vars['adsite']): ?> <!--分包配置添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>分包配置添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">分包配置添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=adsite&method=adinfoadd" method="post" id='myform' name='myform'>
                                <input name="act" value="adinfoadd" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">类型：</label>
                                    <label>
                                        <input type="radio" value='1' name='screen' id='screen' onclick="showdown(1)" checked="true">
                                        <span class="text">后台分包</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='screen' id='screen' onclick="showdown(2)" >
                                        <span class="text">研发分包</span>
                                    </label> 
                                </div>
                                <div id='mydiv' class="form-group" style="display:none;">
                                    <label for="definpu">游戏包下载地址：</label>
                                    <textarea class="form-control" id="gameurl" name="gameurl" value="" placeholder="请填写游戏包下载地址"></textarea> 
                                </div>
                                <div class="form-group">
                                   <label for="definpu">游戏选择：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onchange="getgames()">
                                        <option value='0'>请选择游戏</option>
                                        <?php echo $this->_tpl_vars['gamestr']; ?>

                                    </select>
                                </div>
                                <div id='selectgame' class="form-group" style="display:black;">
                                    <label for="definpu">游戏母包选择：</label><span style="color:red">*</span>
                                    <div id='choosegame'></div>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">渠道类型选择：</label><span style="color:red">*</span>
                                    <select name="tid" id='tid' style="width:100%;" onchange="select_ad()">
                                        <option value="0" selected="selected">渠道选择</option>
                                        <option value="1">安卓</option>
                                        <option value="2">ios</option>
                                        <option value="3">ios越狱</option2>
                                    </select>
                                </div>

                                <div class="form-group">
                                   <label for="definpu">主广告选择：</label><span style="color:red">*</span>
                                    <select name="aid" id='aid' style="width:100%;">
                                            <option value="0">主广告选择</option>
                                        <?php if ($this->_tpl_vars['tps'] == 1): ?>
                                                <?php echo $this->_tpl_vars['adstr']; ?>

                                            <?php else: ?>
                                                <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                            <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">广告名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" style="width: 150px" id="name" name="name" value="" placeholder="请填写子广告名称">   
                                </div>
                                <div class="form-group">
                                    <label for="definpu">配置投放ID：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" style="width: 150px" id="start" name="start" value="<?php echo $this->_tpl_vars['maxid']; ?>
">
                                    至 
                                    <input type="text" class="form-control" style="width: 150px" id="end" name="end"  value="" placeholder="填写结束范围值">
                                    默认开始值为当前已配置最大ID号，只需填写结束范围值即可。仅为数值整型
                                </div><br/>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=adsite&method=adinfoadd','myform',0,1)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--分包配置添加-->

    <?php if ('adinfolist' == $this->_tpl_vars['adsite']): ?> <!--分包配置列表-->
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">分包配置列表</span>
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
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='adsite'/>
                                <input type='hidden' name="method" value='adinfolist'/>
                                <div style="margin-left:10px;float:left;">请选择游戏：
                                <select name="gid" id='gid' style="width:100%;" style="width:200px;">
                                    <option value="0">请选择游戏</option>
                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                </select>
                                </div>
                                <div style="margin-left:10px;float:left;">渠道选择：
                                    <div class="form-group">
                                        <select name="tid" id='tid' style="width:100%;" onchange="select_ad()">
                                            <option value="0" <?php if ($this->_tpl_vars['tid'] == 0): ?>selected='selected'<?php endif; ?>>渠道选择</option>
                                            <option value="1" <?php if ($this->_tpl_vars['tid'] == 1): ?>selected='selected'<?php endif; ?>>安卓</option>
                                            <option value="2" <?php if ($this->_tpl_vars['tid'] == 2): ?>selected='selected'<?php endif; ?>>ios</option>
                                            <option value="3" <?php if ($this->_tpl_vars['tid'] == 3): ?>selected='selected'<?php endif; ?>>ios越狱</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">主广告选择：
                                    <div class="form-group">
                                        <select name="aid" id='aid' style="width:100%;" onchange="select_adsons()">
                                            <option value="0">主广告选择</option>
                                            <?php if ($this->_tpl_vars['tps'] == 1): ?>
                                                <?php echo $this->_tpl_vars['adstr']; ?>

                                            <?php else: ?>
                                                <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">分包类型：
                                    <div class="form-group">
                                     <select name="mtype" id='mtype' style="width:100%;">
                                            <option value="0" <?php if ($this->_tpl_vars['mtype'] == 0): ?>selected='selected'<?php endif; ?>>请选择</option>
                                            <option value="1" <?php if ($this->_tpl_vars['mtype'] == '1'): ?>selected='selected'<?php endif; ?>>后台分包</option>
                                            <option value="2" <?php if ($this->_tpl_vars['mtype'] == '2'): ?>selected='selected'<?php endif; ?>>研发分包</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo2' class="btn btn-blue" value="查询" /></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >系统ID</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 100px;text-align: center;" >子渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >游戏</th>
                            <!-- <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >SVN地址</th> -->
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >游戏包下载地址</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >投放地址</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >状态</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >分包类型</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 50px;text-align: center;" >操作</th>
                        </tr>
                        <?php $_from = $this->_tpl_vars['arrs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
                            <tr id="odd"><input type='hidden' id='oldurl' value="<?php echo $this->_tpl_vars['v']['gam_down_address']; ?>
"/>
                                <td><?php echo $this->_tpl_vars['v']['sysid']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gam_uaid']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gam_uwid']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gam_gid']; ?>
</td>
                                <!-- <td>{$v.gam_svn_address}</td> -->
                                <td><?php if ($this->_tpl_vars['v']['gam_down_address'] != '' && $this->_tpl_vars['v']['gam_type'] == '研发分包'): ?><input type='text' id='newdown' onblur='getnewurl()'  class='form-control' value='<?php echo $this->_tpl_vars['v']['gam_down_address']; ?>
'/><?php else: ?><a href="<?php echo $this->_tpl_vars['v']['gam_down_address']; ?>
"><?php echo $this->_tpl_vars['v']['gam_down_address']; ?>
</a><?php endif; ?></td>
                                <td><?php echo $this->_tpl_vars['v']['gam_tf_address']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gam_state']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gam_type']; ?>
</td>
                                <td><?php if ($this->_tpl_vars['v']['gam_type'] == '研发分包'): ?><a id='myId' name='myId' href='javascript:void(0);' onclick='subgo(<?php echo $this->_tpl_vars['v']['sysid']; ?>
)'>[修改]</a><?php endif; ?><?php echo $this->_tpl_vars['v']['del']; ?>
</td>
                            </tr>
                        <?php endforeach; endif; unset($_from); ?>
                        <tr bgcolor="#FFFFFF">
                            <td colspan="14"><div align="right">每页 <font color="#FF0000"><?php echo $this->_tpl_vars['pageinfo']['pagesize']; ?>
</font> 条 共 <font color="#FF0000"><?php echo $this->_tpl_vars['pageinfo']['totalpage']; ?>
</font> 页 共 <font color="#FF0000"><?php echo $this->_tpl_vars['pageinfo']['totalrecord']; ?>
</font> 条记录&nbsp;<?php echo $this->_tpl_vars['pageinfo']['multi']; ?>
</div></td>
                        </tr>
                        </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>  <!--分包配置添加-->
   <?php if ('parentlist' == $this->_tpl_vars['adsite']): ?> <!--游戏母包信息列表-->
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">游戏母包信息列表 </span>
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
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='adsite'/>
                                <input type='hidden' name="method" value='parentlist'/>
                                <div style="margin-left:10px;float:left;">请选择游戏：
                                <select name="gid" id='gid' style="width:100%;" style="width:200px;">
                                    <option value="0">请选择游戏</option>
                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                </select>
                                </div>

                                <div style="margin-left:10px;float:left;">上传状态：
                                    <div class="form-group">
                                     <select name="mtype" id='mtype' style="width:100%;">
                                            <option value="0" <?php if ($this->_tpl_vars['mtype'] == 0): ?>selected='selected'<?php endif; ?>>请选择</option>
                                            <option value="1" <?php if ($this->_tpl_vars['mtype'] == '1'): ?>selected='selected'<?php endif; ?>>成功</option>
                                            <option value="2" <?php if ($this->_tpl_vars['mtype'] == '2'): ?>selected='selected'<?php endif; ?>>失败</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo2' class="btn btn-blue" value="查询" /></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >系统ID</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >操作日期</th>
                            <!-- <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 100px;text-align: center;" >子渠道</th> -->
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >原文件名</th>
                            <!-- <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >新文件名</th> -->
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >游戏母包说明</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >上传状态</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >上传路径</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >文件大小</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >操作</th>
                        </tr>
                        <tr id="odd"><?php echo $this->_tpl_vars['str']; ?>
</tr> 
                        <tr bgcolor="#FFFFFF">
                            <td colspan="14"><div align="right">每页 <font color="#FF0000"><?php echo $this->_tpl_vars['pageinfo']['pagesize']; ?>
</font> 条 共 <font color="#FF0000"><?php echo $this->_tpl_vars['pageinfo']['totalpage']; ?>
</font> 页 共 <font color="#FF0000"><?php echo $this->_tpl_vars['pageinfo']['totalrecord']; ?>
</font> 条记录&nbsp;<?php echo $this->_tpl_vars['pageinfo']['multi']; ?>
</div></td>
                        </tr>
                        </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="templates/js/datatable/jquery.dataTables.min.js"></script>
        <script src="templates/js/datatable/ZeroClipboard.js"></script>
        <script src="templates/js/datatable/dataTables.tableTools.min.js"></script>
        <script src="templates/js/datatable/dataTables.bootstrap.min.js"></script>
        <script src="templates/js/datatable/datatables-init.js"></script>
        <script type="text/javascript">
            InitiateSimpleDataTable.init();
            InitiateEditableDataTable.init();
            InitiateExpandableDataTable.init();
            function select_ad(){
                //清空数据
                $("#aid").find("option").remove();
                $("#adsons").find("option").remove();
                $("#adsons").append('<option value="0">子广告选择</option>');
                $("#aid").append('<option value="0">广告选择</option>');
                //获取子分类数据
                var tids = $("#tid").val();
                if(tids!=0){
                     $.post("index.php?module=adsite&method=getadson",{tid:tids},
                         function(data){
                            data = eval("("+data+")");
                            $.each(data,function(i,item){
                                $("#aid").append('<option value="'+i+'" <?php if ($this->_tpl_vars['aid'] == "'+i+'"): ?>selected="selected"<?php endif; ?>>'+item+'</option>');
                            });
                         }
                     ); 
                }
            }
            function select_adsons(){
                //清空数据
                $("#adsons").find("option").remove();
                //获取子分类数据
                var ids  = $("#aid").val();
                 $.post("index.php?module=adsite&method=getadson",{aid: ids},
                     function(data){
                        data = eval("("+data+")");
                        //处理子展示
                        $("#adsons").append('<option value="0">子广告选择</option>');
                        $.each(data,function(i,item){
                            $("#adsons").append('<option value="'+i+'" <?php if ($this->_tpl_vars['aid'] == "'+i+'"): ?>selected="selected"<?php endif; ?>>'+item+'</option>');
                        });
                     }
                 );
            }
        </script>
    <?php endif; ?>  <!--分包配置添加-->

<?php if ('parentadd' == $this->_tpl_vars['adsite']): ?> <!--游戏母包添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>游戏母包添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">游戏母包添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=adsite&method=parentadd" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden">
                                <input id="new_name" name="new_name" value="<?php echo $this->_tpl_vars['data']['gp_new_filename']; ?>
" type="hidden"/>
                                <input id="size" name="size" value="<?php echo $this->_tpl_vars['data']['gp_file_size']; ?>
" type="hidden"/>
                                <input id="state" name="state" value="<?php echo $this->_tpl_vars['data']['gp_upload_state']; ?>
" type="hidden"/>
                                <input id="path" name="path" value="<?php echo $this->_tpl_vars['data']['gp_upload_path']; ?>
" type="hidden"/>
                                <div class="form-group">
                                   <label for="definpu">游戏选择：<?php echo $this->_tpl_vars['msg']; ?>
</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;">
                                    <option value="0">请选择游戏</option>
                                        <?php echo $this->_tpl_vars['gamestr']; ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">母包名称：</label><span style="color:red">*</span>
                                    <input type="text" readonly="true" class="form-control" id="name" name="name" value="<?php echo $this->_tpl_vars['data']['gp_old_filename']; ?>
" placeholder="请填写母包名称">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">母包描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="请填写母包说明" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gp_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <iframe frameborder='0' width='100%' height='200px' src="./templates/uploads.html" name="uploadframe"></iframe>
                                </div>
                                <br/>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=adsite&method=parentadd','myform',0)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function formaction(url,id,name){
        if(name==0){
            document.myform.action = url;
            $("#"+id).submit();
        }else{
            document.myform1.action = url;
            $("#"+id).submit();
        }
    }
    </script>
    <?php endif; ?>  <!--分包配置添加-->
    <script type="text/javascript">
        function getnewurl(){
            var oldurl = $("#oldurl").val();
            var newurl = $("#newdown").val();
            if(oldurl!=newurl){
                $("#newurl").val(newurl);
            }
        }

        function getgames(){
            var gid = $("#gid").val();
            var screen = $("input[name='screen']:checked").val();
            if(screen == 1){
                $.post("index.php?module=adsite&method=selectgame",{gid:gid},
                    function(data){
                        var strs = JSON.parse(data);
                        $("#choosegame").find("label").remove();
                        if(strs.state=='fail'){
                            alert("该游戏尚未上传母包");
                            return false;
                        }

                        if(strs.state=='scuess'){
                            $("#choosegame").append(strs.data);
                        }
                    }
                ); 
            }
        }

        function showdown(id){
            if(id==1){
                $("#mydiv").hide();
                $("#selectgame").show();
            }else{
                $("#mydiv").show();
                $("#selectgame").hide();
            }
        }

        function subgo(sysid){
            var oldurl = $("#oldurl").val();
            var newurl = $("#newdown").val();
            if(newurl=='' || newurl == 'undefined'){
                alert("游戏包下载地址不能为空");
                return false;
            }
            if(newurl == oldurl){
                alert("游戏包下载地址未修改");
                return false;
            }

            $.post("index.php?module=adsite&method=adinfoedit",{sysid:sysid,downurl:newurl},
                function(data){
                    alert(data);
                }
            ); 
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

         function select_ad(){
            //清空数据
            $("#aid").find("option").remove();
            $("#aid").append('<option value="0">广告选择</option>');
            //获取子分类数据
            var tids = $("#tid").val();
            if(tids!=0){
                $.post("index.php?module=play_active&method=getadson",{tid:tids},
                    function(data){
                        data = eval("("+data+")");
                        $.each(data,function(i,item){
                            $("#aid").append('<option value="'+i+'">'+item+'</option>');
                        });
                    }
                ); 
            }
        }

        function addGameCheck(url,id,name,zt)
        {
            //检测数据
            var gid      = clean_error('gid');
            var aid      = clean_error('aid');
            var end      = clean_error('end');
            var tid      = clean_error('tid');
            var name     = clean_error('name');

            if(gid && start && end && aid && tid && name){
                var  s = parseInt($("#start").val());
                var  e = parseInt($("#end").val());
                if(!isInteger(s) || !isInteger(e)){
                    alert("配置ID必须为整型!");
                    return false;
                }else if(s>e || s==e){
                    alert("结束ID必须大于开始ID");
                    return false;
                }else{
                    if(zt=="1"){
                        alert("正在分包，请稍候!");
                    }
                    formaction(url,id,gid);
                }
            }else{
                return false;
            }
        }


        function isInteger(obj) {
         return obj%1 === 0
        }


        function clean_error(name){
            $('#'+name).next().remove();
            var names = $('#'+name).val();
            if(names == "" || names == '0' || names == 'undefined'){
                $('#'+name).parent().attr("class","has-error");
                $('#'+name).focus();
                $('#'+name).after('<small class="help-block" style="" data-bv-validator="notEmpty" data-bv-validator-for="address"></small>');
                $('#'+name).next().html('内容填写有误！');
                return false;
            }else{
                $('#'+name).parent().attr("class","form-group");
                $('#'+name).next().html();
                $('#'+name).next().remove();
                return true;
            }
        }

    function formaction(url,id,name){
        if(name==0){
            document.myform.action = url;
            $("#"+id).submit();
        }else{
            document.myform1.action = url;
            $("#"+id).submit();
        }
    }
    </script>
    <script>
        $(function(){
            if('<?php echo $this->_tpl_vars['meg']; ?>
' !=''){
                $("#toast-container").show();
    function formaction(url,id,name){
        if(name==0){
            document.myform.action = url;
            $("#"+id).submit();
        }else{
            document.myform1.action = url;
            $("#"+id).submit();
        }
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

<!--Bootstrap Date Picker-->
<script src="templates/js/datetime/bootstrap-datepicker.js"></script>
<script type="text/javascript">        
    //--Bootstrap Date Picker--
    $('.date-picker').datepicker();
</script>
</html>