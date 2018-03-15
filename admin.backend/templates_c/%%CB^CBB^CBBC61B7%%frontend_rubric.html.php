<?php /* Smarty version 2.6.20, created on 2018-03-03 17:06:48
         compiled from frontend_rubric.html */ ?>
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

	<?php if ($this->_tpl_vars['type'] == 'rubric_list'): ?><!--栏目列表-->
 	<style> ul{ list-style: none; } </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">栏目列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索栏目名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索栏目等级" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索排序号" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                </tr>
                                <tr role="row" id='test'>
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
                "aaSorting": [],
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
                    { "sTitle": "栏目名称" },
                    { "sTitle": "栏目等级" },
                    { "sTitle": "排序号" },
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

	<?php if ($this->_tpl_vars['type'] == 'add_rubric'): ?><!--添加栏目-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>栏目添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">栏目添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_rubric&method=add_rubric" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">上级栏目：</label><span style="color:red">*</span>
                                    <select name="fid" id='fid' style="width:100%;">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['fr_fid'] == 0): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;顶级栏目</option>
                                        <?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['rubricArr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>
                                        <option value="<?php echo $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['fr_fid'] == $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['sysid']): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;<?php echo $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['fr_name']; ?>
</option><hr/>
                                        <?php endfor; endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">栏目名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="例如：游戏动态" value="<?php echo $this->_tpl_vars['data']['fr_name']; ?>
" onblur="clean_error('name')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">文件保存目录：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="dir" name="dir" placeholder="例如：/yxdt" value="<?php echo $this->_tpl_vars['data']['fr_dir']; ?>
" onblur="clean_error('dir')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">模板名称：</label>
                                    <input type="text" class="form-control" id="template" name="template" placeholder="例如：index.html" value="<?php echo $this->_tpl_vars['data']['fr_template']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">栏目描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="例如：游戏动态栏目下管理游戏资讯等内容......" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['fr_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序号：</label>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="默认为 0 " value="<?php echo $this->_tpl_vars['data']['fr_order']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否支持投稿：</label>
                                    <label>
                                        <input type="radio" value='1' name='contribute' id='contribute' <?php if ($this->_tpl_vars['data']['fr_contribute'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='contribute' id='contribute' <?php if ($this->_tpl_vars['data']['fr_contribute'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否隐藏：</label>
                                    <label>
                                        <input type="radio" value='1' name='hide' id='hide' <?php if ($this->_tpl_vars['data']['fr_hide'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='hide' id='hide' <?php if ($this->_tpl_vars['data']['fr_hide'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_rubric&method=add_rubric','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['type'] == 'edit_rubric'): ?><!--修改栏目-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>栏目修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">栏目修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_rubric&method=edit_rubric" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">上级栏目：</label><span style="color:red">*</span>
                                    <select name="fid" id='fid' style="width:100%;">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['fr_fid'] == 0): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;顶级栏目</option>
                                        <?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['rubricArr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>
                                        <option value="<?php echo $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['fr_fid'] == $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['sysid']): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;<?php echo $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['fr_name']; ?>
</option><hr/>
                                        <?php endfor; endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">栏目名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="例如：游戏动态" value="<?php echo $this->_tpl_vars['data']['fr_name']; ?>
" onblur="clean_error('name')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">文件保存目录：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="dir" name="dir" placeholder="例如：/yxdt" value="<?php echo $this->_tpl_vars['data']['fr_dir']; ?>
" onblur="clean_error('dir')">
                                </div>
                                <div class="form-group">
                                   <label for="definpu">模板名称：</label>
                                    <input type="text" class="form-control" id="template" name="template" placeholder="例如：index.html" value="<?php echo $this->_tpl_vars['data']['fr_template']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">栏目描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="例如：游戏动态栏目下管理游戏资讯等内容......" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['fr_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序号：</label>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="默认为 0 " value="<?php echo $this->_tpl_vars['data']['fr_order']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否支持投稿：</label>
                                    <label>
                                        <input type="radio" value='1' name='contribute' id='contribute' <?php if ($this->_tpl_vars['data']['fr_contribute'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='contribute' id='contribute' <?php if ($this->_tpl_vars['data']['fr_contribute'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否隐藏：</label>
                                    <label>
                                        <input type="radio" value='1' name='hide' id='hide' <?php if ($this->_tpl_vars['data']['fr_hide'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='hide' id='hide' <?php if ($this->_tpl_vars['data']['fr_hide'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_rubric&method=edit_rubric','myform',0);">修 改</button>
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
            var name = clean_error('name');
            var dir = clean_error('dir');
            if(name && dir){
                formaction(url,id,name);
            }else{
                return false;
            }
        }

        function clean_error(name) {
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

        function formaction(url,id,name) {
            if(name==0){
                document.myform.action = url;
                $("#"+id).submit();
            }else{
                document.myform1.action = url;
                $("#"+id).submit();
            }
        }

        // 删除栏目
        function del_rubric(id) {
        	var answer = confirm('子栏目也会被删除哦！您确定要删除？');
        	if (answer) {
        		$.getJSON('index.php?module=frontend_rubric&method=del_rubric&id='+id,function(res){
        			alert(res.msg);
        			location.href = res.data;
        		});
        	} else {
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