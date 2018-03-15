<?php /* Smarty version 2.6.20, created on 2018-01-30 16:04:32
         compiled from permisson.html */ ?>

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
<?php if ($this->_tpl_vars['type'] == 'grouplist'): ?>
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
                    <span class="widget-caption">权限组列表</span>
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
                                <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索权限组名" class="form-control input-sm"></th>
                                <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索添加时间" class="form-control input-sm"></th>
                                <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索添加人登录名" class="form-control input-sm"></th>
                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加人真实名" class="form-control input-sm"></th>
                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索说明" class="form-control input-sm"></th>
                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                            </tr>
                            <tr role="row" id='test'>
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
                var str = '<ul>';
                str += nTr;
                str += '</ul>';
                var sOut = '<table>';
                sOut += '<tr><td width="90px">可执行权限:</td><td>' + str + '</td></tr>';
                sOut += '</table>';
                return sOut;
            }
            /*
             * Insert a 'details' column to the table
             */
            var oTable = $('#searchable').dataTable({
            "sScrollX": "200",
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
            
            "data": <?php echo $this->_tpl_vars['grouplist']; ?>
,
            "columns": [
                { "sTitle": "权限组名",class:'123'},
                { "sTitle": "添加时间" },
                { "sTitle": "添加人登录名" },
                { "sTitle": "添加人真实名" },
                { "sTitle": "说明 " },
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
                //获取sysid
                var url = $(this).html();
                var reg = /\[\d*/;
                var res = url.match(reg);
                var id = res[0].replace('[','');
                var data = <?php echo $this->_tpl_vars['right_arr']; ?>
;
                oTable.fnOpen(nTr, fnFormatDetails(oTable, data[id]), 'details');
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
<?php endif; ?>  <!--权限列表-->

<?php if ('addgroup' == $this->_tpl_vars['type']): ?><!--添加权限-->
<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
    <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>添加权限</h5>
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-xs-12">
            <div class="widget">
                <div class="widget-header bordered-top bordered-palegreen">
                    <span class="widget-caption">添加权限表单</span>
                </div>
                <div class="widget-body">
                    <div class="collapse in">
                        <form action="index.php?module=permigroup&method=add_group" method="post">
                            <input name="act" type="hidden" value="insert"/>

                            <div class="form-group">
                                <label for="definpu">权限组名</label><span style="color:red">*</span>
                                <input type="text" class="form-control" id="groupname" name="groupname" placeholder="权限组名" value="<?php echo $this->_tpl_vars['userinfo']['a_truename']; ?>
">
                            </div>

                            <div class="form-group">
                                <label for="definpu">权限组说明</label>
                                <textarea class="form-control" rows="3" placeholder="权限组说明" id='groupcont' name='groupcont'></textarea>
                            </div>
                                <div class="form-group">
                                    <label for="definpu">数据列表</label>
                                    <br/>
                                <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                    <?php echo $this->_tpl_vars['v']['gp']; ?>

                                    <br/>
                                    <?php $_from = $this->_tpl_vars['v']['p']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pk'] => $this->_tpl_vars['pv']):
?>
                                        <?php echo $this->_tpl_vars['pv']; ?>

                                    <?php endforeach; endif; unset($_from); ?>
                                    <hr/>
                                <?php endforeach; endif; unset($_from); ?>
                                </div>
                            <button type="submit" class="btn btn-blue" onClick="return checkGroupright();">添 加</button>
                            <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ('editgroup' == $this->_tpl_vars['type']): ?><!--修改权限-->
<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
    <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>修改权限组</h5>
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-xs-12">
            <div class="widget">
                <div class="widget-header bordered-top bordered-palegreen">
                    <span class="widget-caption">修改权限组表单</span>
                </div>
                <div class="widget-body">
                    <div class="collapse in">
                        <form action="index.php?module=permigroup&method=edit_group" method="post">
                            
                            <input name="act" type="hidden" value="update"/>
                            <input name="gsysid" type="hidden" value="<?php echo $this->_tpl_vars['gsysid']; ?>
"/>

                            <div class="form-group">
                                <label for="definpu">权限组名</label><span style="color:red">*</span>
                                <input type="text" class="form-control" id="groupname" name="groupname" placeholder="权限组名" value="<?php echo $this->_tpl_vars['groupname']; ?>
">
                            </div>

                            <div class="form-group">
                                <label for="definpu">权限组说明</label>
                                <textarea class="form-control" rows="3" placeholder="权限组说明" id='groupcont' name='groupcont'><?php echo $this->_tpl_vars['groupcontent']; ?>
</textarea>
                            </div>
                                <div class="form-group">
                                    <label for="definpu">数据列表</label>
                                    <br/>
                                <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                    <?php echo $this->_tpl_vars['v']['gp']; ?>

                                    <br/>
                                    <?php $_from = $this->_tpl_vars['v']['p']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pk'] => $this->_tpl_vars['pv']):
?>
                                        <?php echo $this->_tpl_vars['pv']; ?>

                                    <?php endforeach; endif; unset($_from); ?>
                                    <hr/>
                                <?php endforeach; endif; unset($_from); ?>
                                </div>
                            <button type="submit" class="btn btn-blue" onClick="return checkGroupright();">修改权限组</button>
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
    function deleteGroup()
    {
        if(confirm("确定要删除该权限组？"))
        {
            return true;
        }else{
            return false;   
        }
    }
</script>
<script>
$(document).ready(function(){
     $("#all").click(function(){
        var v = $("#all").val();
        if(v==1)
        {
            $(".box").attr('checked','checked');
            $(".p").attr('checked','checked');
            $(".p").attr('alt',0);
            $("#all").val(0);
        }else if(v==0)
        {
            $(".box").removeAttr('checked');
            $(".p").removeAttr('checked');
            $(".p").attr('alt',1);
            $("#all").val(1);
        }                     
     });
});
 function checkGroupright(index){
     var groupname = $("#groupname").val();
     if(groupname == "")
     {
         alert("权限组名不能为空");
         return false;
     }
     var str = '';
     var begin = true;
     $(":input[type=checkbox][checked]").each(function(){
         if(begin)
            str+=$(this).val();
         else
            str+=","+$(this).val();
         begin = false;
     })
     if(str.length==0){
        alert("至少要添加一个权限");
        return false;
     } 
}
function get(id)
{
    var p = id+"p";
    var c = id+"c";
    var v = $("#"+p).attr('alt');
    if(v==1)
    {
        $("."+c).attr('checked','checked');
        $("#"+p).attr('checked','checked');
        $("#"+p).attr('alt',0);
    }else if(v==0)
    {
        $("."+c).removeAttr('checked');
        $("#"+p).removeAttr('checked');
        $("#"+p).attr('alt',1);
    }
}

function getp(id)
{
    var p = id+"p";
    var c = id+"c";
    var type = getValue(c);
    if(type)
    {
        $("."+p).attr('checked','checked');
    }else{
        $("."+p).removeAttr('checked');
    }
}

function gets(id)
{
    var o = id+"o";
    var c = id+"c";
    var type = $("#"+c).is(':checked');
    if(type)
    {
        $("."+o).attr('checked','checked');
    }else{
        $("."+o).removeAttr('checked');
    }
}

function getpp(id,pid)
{
    var p = id+"pp";
    var pp = id+"_"+pid+"pp";
    var c = pid+"o";
    var cc = id+"oo";
    var type = getValue(c);

    if(type)
    {
        $("."+p).attr('checked','checked');
        $("."+pp).attr('checked','checked');
    }else{
        var typec = getValue(cc);
        
        if(typec == 1){
            $("."+p).removeAttr('checked');
        }
        $("."+pp).removeAttr('checked');
    }   
    
}

function getValue(obj)
{
    var obj = obj;
    var begin = false;
    var str = '';
    var count = 0;
    $("."+obj+"[type=checkbox][checked]").each(function(){
        if(begin)
            str+=$(this).val();
        else
            str+=","+$(this).val();
        begin = false;
        count +=1;
    });
    if(str.length>0)
    {
        //return true;
        return count;
    }else{
        return false;   
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