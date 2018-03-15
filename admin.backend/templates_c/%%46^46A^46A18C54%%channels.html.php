<?php /* Smarty version 2.6.20, created on 2018-03-03 09:52:19
         compiled from channels.html */ ?>

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
     <?php if ('clist' == $this->_tpl_vars['channels']): ?>  <!--渠道列表-->
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
                        <span class="widget-caption">渠道列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索游戏" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索渠道游戏名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索渠道包名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索渠道名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索状态" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="" class="form-control input-sm"></th>
                                </tr>
                                <tr role="row" id='test'>
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
                    sOut += '<tr><td>系统ID:</td><td>' + nTr['sysid']+ '</td></tr>';
                    sOut += '<tr><td>游戏:</td><td>' + nTr['gc_gid']+ '</td></tr>';
                    sOut += '<tr><td>渠道名称:</td><td>' + nTr['gc_cname'] + '</td></tr>';
                    sOut += '<tr><td>渠道描述:</td><td>' + nTr['gc_cdescription'] + '</td></tr>';
                    sOut += '<tr><td>渠道包名:</td><td>' + nTr['gc_packagename'] + '</td></tr>';
                    sOut += '<tr><td>appid:</td><td>' + nTr['gc_appid'] + '</td></tr>';
                    sOut += '<tr><td>appkey:</td><td>' + nTr['gc_appkey'] + '</td></tr>';
                    sOut += '<tr><td>app_secret:</td><td>' + nTr['gc_appsecret'] + '</td></tr>';
                    sOut += '<tr><td>渠道回调地址:</td><td>' + nTr['gc_callback'] + '</td></tr>';
                    sOut += '<tr><td>渠道游戏名:</td><td>' + nTr['gc_gname'] + '</td></tr>';
                    sOut += '<tr><td>渠道游戏icon:</td><td>' + nTr['gc_icon'] + '</td></tr>';
                    sOut += '<tr><td>闪屏图片:</td><td>' + nTr['gc_splashscreen'] + '</td></tr>';
                    sOut += '<tr><td>状态:</td><td>' + nTr['gc_status'] + '</td></tr>';
                    sOut += '<tr><td>添加人:</td><td>' + nTr['gc_addid'] + '</td></tr>';
                    sOut += '<tr><td>添加时间:</td><td>' + nTr['gc_addtime'] + '</td></tr>';
                    sOut += '<tr><td>修改人:</td><td>' + nTr['gc_upid'] + '</td></tr>';
                    sOut += '<tr><td>修改时间:</td><td>' + nTr['gc_uptime'] + '</td></tr>';
                    sOut += '</table>';
                    return sOut;
                }
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
                    { "sTitle": "游戏" },
                    { "sTitle": "渠道游戏名" },
                    { "sTitle": "渠道包名" },
                    { "sTitle": "渠道名称" },
                    { "sTitle": "状态" },
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
                    var url = "index.php?module=channels&method=cinfo";
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
    <?php endif; ?>  <!--渠道列表-->

    <?php if ('cadd' == $this->_tpl_vars['channels']): ?> <!--渠道添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>渠道添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">渠道添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=channels&method=cadd" method="post" id='myform' name='myform'>
                                <input name="act" value="cadd" type="hidden">
                                <input name="upload_url" value="<?php echo $this->_tpl_vars['info']; ?>
" type="hidden">
                                <input name="upload_url2" value="<?php echo $this->_tpl_vars['info2']; ?>
" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">游戏选择：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onchange="clean_error('gid')">
                                        <?php echo $this->_tpl_vars['gamestr']; ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="cname" name="cname" placeholder="渠道名称" value="<?php echo $this->_tpl_vars['data']['gc_cname']; ?>
" onblur="clean_error('cname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道描述：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="3" placeholder="游戏描述" id='cdescription' name='cdescription' onblur="clean_error('cdescription')"><?php echo $this->_tpl_vars['data']['gc_cdescription']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道包名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="packagename" name="packagename" placeholder="渠道包名" value="<?php echo $this->_tpl_vars['data']['gc_packagename']; ?>
" onblur="clean_error('packagename')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">appid：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="appid" name="appid" placeholder="appid" value="<?php echo $this->_tpl_vars['data']['gc_appid']; ?>
" onblur="clean_error('appid')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">appkey：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="appkey" name="appkey" placeholder="appkey" value="<?php echo $this->_tpl_vars['data']['gc_appkey']; ?>
" onblur="clean_error('appkey')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">appsecret：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="appsecret" name="appsecret" placeholder="appsecret" value="<?php echo $this->_tpl_vars['data']['gc_appsecret']; ?>
" onblur="clean_error('appsecret')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道回调地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="callback" name="callback" placeholder="渠道回调地址" value="<?php echo $this->_tpl_vars['data']['gc_callback']; ?>
" onblur="clean_error('callback')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道游戏名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="gname" name="gname" placeholder="渠道游戏名" value="<?php echo $this->_tpl_vars['data']['gc_gname']; ?>
" onblur="clean_error('gname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道游戏icon：</label><span style="color:red">*</span>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php echo $this->_tpl_vars['info']; ?>
"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="file" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['error']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['success']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=channels&method=cadd&flag=up','myform',0)">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">闪屏图片地址：</label><span style="color:red">*</span>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php echo $this->_tpl_vars['info2']; ?>
"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="file2" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['error2']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['success2']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=channels&method=cadd&flag=up2','myform',0)">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否锁定：</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yes no" type="checkbox" id='status' name='status'>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=channels&method=cadd','myform',0)">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--渠道添加-->

    <?php if ('cedit' == $this->_tpl_vars['channels']): ?> <!--渠道修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>渠道添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">渠道添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=channels&method=cedit" method="post" id='myform' name='myform'>
                                <input name="act" value="cadd" type="hidden">
                                <input name="upload_url" value="<?php echo $this->_tpl_vars['info']; ?>
" type="hidden">
                                <input name="upload_url2" value="<?php echo $this->_tpl_vars['info2']; ?>
" type="hidden">
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">游戏选择：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onchange="clean_error('gid')">
                                        <?php echo $this->_tpl_vars['gamestr']; ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="cname" name="cname" placeholder="渠道名称" value="<?php echo $this->_tpl_vars['data']['gc_cname']; ?>
" onblur="clean_error('cname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道描述：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="3" placeholder="游戏描述" id='cdescription' name='cdescription' onblur="clean_error('cdescription')"><?php echo $this->_tpl_vars['data']['gc_cdescription']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道包名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="packagename" name="packagename" placeholder="渠道包名" value="<?php echo $this->_tpl_vars['data']['gc_packagename']; ?>
" onblur="clean_error('packagename')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">appid：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="appid" name="appid" placeholder="appid" value="<?php echo $this->_tpl_vars['data']['gc_appid']; ?>
" onblur="clean_error('appid')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">appkey：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="appkey" name="appkey" placeholder="appkey" value="<?php echo $this->_tpl_vars['data']['gc_appkey']; ?>
" onblur="clean_error('appkey')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">appsecret：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="appsecret" name="appsecret" placeholder="appsecret" value="<?php echo $this->_tpl_vars['data']['gc_appsecret']; ?>
" onblur="clean_error('appsecret')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道回调地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="callback" name="callback" placeholder="渠道回调地址" value="<?php echo $this->_tpl_vars['data']['gc_callback']; ?>
" onblur="clean_error('callback')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道游戏名：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="gname" name="gname" placeholder="渠道游戏名" value="<?php echo $this->_tpl_vars['data']['gc_gname']; ?>
" onblur="clean_error('gname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道游戏icon：</label><span style="color:red">*</span>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php echo $this->_tpl_vars['info']; ?>
"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="file" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['error']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['success']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=channels&method=cedit&flag=up','myform',0)">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">闪屏图片地址：</label><span style="color:red">*</span>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php echo $this->_tpl_vars['info2']; ?>
"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="file2" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['error2']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['success2']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=channels&method=cedit&flag=up2','myform',0)">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否锁定：</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yes no" type="checkbox" id='status' name='status'>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=channels&method=cedit','myform',0)">修 改</button>
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
        var gid             = clean_error('gid');
        var cname           = clean_error('cname');
        var cdescription    = clean_error('cdescription');
        var packagename     = clean_error('packagename');
        var appid           = clean_error('appid');
        var appkey          = clean_error('appkey');
        var appsecret       = clean_error('appsecret');
        var callback        = clean_error('callback');
        var gname           = clean_error('gname');
        var splashscreen    = clean_error('splashscreen');
        if(gid && cname && cdescription && packagename && appid && appkey && appsecret && callback && gname && splashscreen){
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
</html>