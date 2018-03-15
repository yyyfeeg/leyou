<?php /* Smarty version 2.6.20, created on 2018-01-30 16:33:30
         compiled from friend_link.html */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </head>
<body>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loading.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php if ($this->_tpl_vars['type'] == 'list'): ?><!--友链列表-->
    <style> ul{ list-style: none; } </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">友链列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索友链名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
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
                    { "sTitle": "系统ID"},
                    { "sTitle": "友链名称" },
                    { "sTitle": "友链地址" },
                    { "sTitle": "是否开启" },
                    { "sTitle": "排序号" },
                    { "sTitle": "添加日期"},
                    { "sTitle": "添加人"},
                    { "sTitle": "操作"},
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
    <?php endif; ?>

    <?php if ($this->_tpl_vars['type'] == 'add'): ?><!--友链添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>友链添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">友链添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=friend_link&method=add_link" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <div class="form-group">
                                   <label for="definpu">友链名称：</label><span style="color:red">*</span>
                                   <input type="text" class="form-control" id="lname" name="lname" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfl_linkname']; ?>
" onblur="clean_error('lname')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">友链地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="lurl" name="lurl" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfl_linkurl']; ?>
" onblur="clean_error('lurl')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">友链描述：</label>
                                   <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gfl_linkdesc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序号：</label>
                                    <input type="text" class="form-control" id="sort" name="sort" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfl_sort']; ?>
" onblur="clean_error('sort')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否开启：</label>
                                    <label>
                                        <input type="radio" value='1' name='open' <?php if ($this->_tpl_vars['data']['gfl_open'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='open' <?php if ($this->_tpl_vars['data']['gfl_open'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=friend_link&method=add_link','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['type'] == 'edit'): ?><!--友链修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>友链修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">友链修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=friend_link&method=edit_link" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <div class="form-group">
                                   <label for="definpu">友链名称：</label><span style="color:red">*</span>
                                   <input type="text" class="form-control" id="lname" name="lname" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfl_linkname']; ?>
" onblur="clean_error('lname')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">友链地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="lurl" name="lurl" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfl_linkurl']; ?>
" onblur="clean_error('lurl')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">友链描述：</label>
                                   <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gfl_linkdesc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序号：</label>
                                    <input type="text" class="form-control" id="sort" name="sort" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfl_sort']; ?>
" onblur="clean_error('sort')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否开启：</label>
                                    <label>
                                        <input type="radio" value='1' name='open' <?php if ($this->_tpl_vars['data']['gfl_open'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='open' <?php if ($this->_tpl_vars['data']['gfl_open'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=friend_link&method=edit_link','myform',0);">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function addGameCheck(url,id,name) {
            //检测数据
            var inum = $('#act').val();
            var lname = clean_error('lname');
            var lurl = clean_error('lurl');
            var lsort = clean_error('sort');
            if(lname&&lurl&&lsort){
                formaction(url,id,name);
            } else {
                return false;
            }
        }
        function addSpreeCheck(url,id,name) {
            formaction(url,id,name);
        }
        function clean_error(name) {
            $('#'+name).next().remove();
            var names = $('#'+name).val();
            if(names == "" || names == 'undefined'){
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

        function formaction(url,id,name) {
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
    <div id="toast-container" class="toast-bottom-right">
        <div class="toast fa-check toast-blue" >
            <button class="toast-close-button">×</button>
            <div class="toast-message"><?php echo $this->_tpl_vars['meg']; ?>
</div>
        </div>
    </div>
</body>
<script src="templates/js/bootstrap.min.js"></script>
<script src="templates/js/beyond.min.js"></script>
</html>