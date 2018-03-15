<?php /* Smarty version 2.6.20, created on 2018-02-09 16:22:20
         compiled from attest_tel.html */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <style> ul{ list-style: none; } </style>
	</head>
<body>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loading.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<?php if ($this->_tpl_vars['type'] == 'list'): ?><!--手机认证日志列表-->
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">手机认证日志列表</span>
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
                                <div style="margin-left:10px;float:left;">开始时间：<input type="text" value="" style="width:200px" class="form-control date-picker" name="start_time" id="start_time" data-date-format="dd-mm-yyyy"></div>
                                <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="" style="width:200px;" class="form-control date-picker" name="end_time" id="end_time" data-date-format="dd-mm-yyyy"></div>
                                <div style="margin-left:10px;float:left;">游戏：
                                    <div class="form-group">
                                        <select name="gid" id='gid' style="width:100%;">
                                            <option value="">所有</option>
                                            <option value="0">系统</option>
                                            <?php $_from = $this->_tpl_vars['gameArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                            <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gci_gid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">事件：
                                    <div class="form-group">
                                        <select name="event" id='event' style="width:100%;">
                                            <option value="0">所有</option>
                                            <option value="1">注册</option>
                                            <option value="2">忘记密码</option>
                                            <option value="3">绑定手机</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">发送状态：
                                    <div class="form-group">
                                        <select name="status" id='status' style="width:100%;">
                                            <option value="">所有</option>
                                            <option value="1">成功</option>
                                            <option value="2">失败</option>
                                            <option value="3">已发送至用户手机</option>
                                            <option value="4">发送至用户手机失败</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">验证状态：
                                    <div class="form-group">
                                        <select name="verify" id='verify' style="width:100%;">
                                            <option value="0">所有</option>
                                            <option value="1">已验证</option>
                                            <option value="2">未验证</option>
                                            <option value="2">超时</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' id='getinfo' class="btn btn-blue" value="查询"/></div>
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
               /*异步条件请求*/
            $("#getinfo").bind("click",function(){
                var start_time  = $('#start_time').val();
                var end_time    = $('#end_time').val();
                var gid         = $('#gid').val();
                var event       = $('#event').val();
                var status      = $('#status').val();
                var verify      = $('#verify').val();
                //异步请求数据
                $.post("index.php?module=attest_tel&method=tel_list",{start_time: start_time,end_time: end_time,gid: gid,event:event,status:status,verify:verify,flag:'get_data'},
                     function(data){
                        data = eval("("+data+")");
                        $('#searchable').dataTable({"bDestroy":true,"sScrollX": "100%",
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
                                { "sTitle": "手机号码"},
                                { "sTitle": "短信内容"},
                                { "sTitle": "活动类型"},
                                { "sTitle": "发送时间"},
                                { "sTitle": "发送状态"},
                                { "sTitle": "是否群发"},
                                { "sTitle": "操作人员"},
                                { "sTitle": "操作IP"},
                                { "sTitle": "状态"}
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
        });
    </script>
	<?php endif; ?>

</body>
<script src="templates/js/bootstrap.min.js"></script>
<script src="templates/js/beyond.min.js"></script>
<!--Bootstrap Date Picker-->
<script src="templates/js/datetime/bootstrap-datepicker.js"></script>
<script type="text/javascript">        
    //--Bootstrap Date Picker--
    $('.date-picker').datepicker();
</script>
</html>