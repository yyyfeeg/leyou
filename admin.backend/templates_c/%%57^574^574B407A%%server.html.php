<?php /* Smarty version 2.6.20, created on 2017-12-26 18:06:10
         compiled from server.html */ ?>

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
     <?php if ('serverlist' == $this->_tpl_vars['server']): ?>  <!--游戏列表-->
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
                        <span class="widget-caption">服务器列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索服务器名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索服务器ID" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索开服时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索游戏" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索渠道" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索停运时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索状态" class="form-control input-sm"></th>
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
                /*
                 * Insert a 'details' column to the table
                 */
                var oTable = $('#searchable').dataTable({
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
                
                "data": <?php echo $this->_tpl_vars['data']; ?>
,
                "columns": [
                    { "sTitle": "系统ID",class:'123'},
                    { "sTitle": "服务器名" },
                    { "sTitle": "服务器ID" },
                    { "sTitle": "开服时间" },
                    { "sTitle": "游戏" },
                    { "sTitle": "渠道" },
                    { "sTitle": "停运时间" },
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
    <?php endif; ?>  <!--游戏列表-->

    <?php if ('serveradd' == $this->_tpl_vars['server']): ?> <!--服务器添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>服务器添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">服务器添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=server&method=serveradd" method="post" id='myform' name='myform'>
                                <input name="act" value="serveradd" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">服务器名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="sname" name="sname" placeholder="游戏名称" value="<?php echo $this->_tpl_vars['data']['gs_sname']; ?>
" onblur="clean_error('sname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">服务器ID：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="sid" name="sid" placeholder="游戏名称" value="<?php echo $this->_tpl_vars['data']['gs_sid']; ?>
" onblur="clean_error('sid')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">游戏选择：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onchange="clean_error('gid')">
                                        <?php echo $this->_tpl_vars['gamestr']; ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">渠道选择：</label><span style="color:red">*</span>
                                    <select name="transport" id='transport' style="width:100%;" onchange="clean_error('transport')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gs_transport'] == 0): ?>selected="selected"<?php endif; ?>>渠道选择</option>
                                        <option value="1" <?php if ($this->_tpl_vars['data']['gs_transport'] == 1): ?>selected="selected"<?php endif; ?>>安卓</option>
                                        <option value="2" <?php if ($this->_tpl_vars['data']['gs_transport'] == 2): ?>selected="selected"<?php endif; ?>>ios</option>
                                        <option value="3" <?php if ($this->_tpl_vars['data']['gs_transport'] == 3): ?>selected="selected"<?php endif; ?>>ios越狱</option2>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">开服时间：</label><span style="color:red">*</span>
                                    <input type="text" data-date-format="dd-mm-yyyy" id="starttime" name="starttime" class="form-control date-picker" onblur="clean_error('starttime')" value="<?php echo $this->_tpl_vars['data']['gs_starttime']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">停运时间：</label><span style="color:red">*</span>
                                    <input type="text" data-date-format="dd-mm-yyyy" id="endtime" name="endtime" class="form-control date-picker" value="<?php echo $this->_tpl_vars['data']['gs_endtime']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否停运：</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yes no" type="checkbox" id='stopoperation' name='stopoperation'>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=server&method=serveradd','myform',0)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--服务器添加-->

    <?php if ('edit' == $this->_tpl_vars['server']): ?> <!--服务器修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>服务器修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">服务器修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=server&method=edit" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden">
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">服务器名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="sname" name="sname" placeholder="游戏名称" value="<?php echo $this->_tpl_vars['data']['gs_sname']; ?>
" onblur="clean_error('sname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">服务器ID：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="sid" name="sid" placeholder="游戏名称" value="<?php echo $this->_tpl_vars['data']['gs_sid']; ?>
" onblur="clean_error('sid')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">游戏选择：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onchange="clean_error('gid')">
                                        <?php echo $this->_tpl_vars['gamestr']; ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">渠道选择：</label><span style="color:red">*</span>
                                    <select name="transport" id='transport' style="width:100%;" onchange="clean_error('transport')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gs_transport'] == 0): ?>selected="selected"<?php endif; ?>>渠道选择</option>
                                        <option value="1" <?php if ($this->_tpl_vars['data']['gs_transport'] == 1): ?>selected="selected"<?php endif; ?>>安卓</option>
                                        <option value="2" <?php if ($this->_tpl_vars['data']['gs_transport'] == 2): ?>selected="selected"<?php endif; ?>>ios</option>
                                        <option value="3" <?php if ($this->_tpl_vars['data']['gs_transport'] == 3): ?>selected="selected"<?php endif; ?>>ios越狱</option2>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">开服时间：</label><span style="color:red">*</span>
                                    <input type="text" data-date-format="dd-mm-yyyy" id="starttime" name="starttime" class="form-control date-picker" onblur="clean_error('starttime')" value="<?php echo $this->_tpl_vars['data']['gs_starttime']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">停运时间：</label><span style="color:red">*</span>
                                    <input type="text" data-date-format="dd-mm-yyyy" id="endtime" name="endtime" class="form-control date-picker" value="<?php echo $this->_tpl_vars['data']['gs_endtime']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否停运：</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yes no" type="checkbox" id='stopoperation' name='stopoperation'>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=server&method=edit','myform',0)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
    function addGameCheck(url,id,name)
    {
        //检测数据
        var sname       = clean_error('sname');
        var sid         = clean_error('sid');
        var gid         = clean_error('gid');
        var transport   = clean_error('transport');
        var starttime   = clean_error('starttime');
        if(sname && sid && gid && transport && starttime){
            formaction(url,id,name);
        }else{
            return false;
        }
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

<!--Bootstrap Date Picker-->
<script src="templates/js/datetime/bootstrap-datepicker.js"></script>
<script type="text/javascript">        
    //--Bootstrap Date Picker--
    $('.date-picker').datepicker();
</script>
</html>