<?php /* Smarty version 2.6.20, created on 2018-03-03 14:28:14
         compiled from spree_managed.html */ ?>
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
	<?php if ($this->_tpl_vars['type'] == 'list'): ?><!--礼包列表-->
	<style> ul{ list-style: none; } </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">礼包列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索礼包名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
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
                    { "sTitle": "礼包名称" },
                    { "sTitle": "描述" },
                    { "sTitle": "礼包内容" },
                    { "sTitle": "兑换积分"},
                    { "sTitle": "VIP等级"},
                    { "sTitle": "礼包类型"},
                    { "sTitle": "游戏"},
                    { "sTitle": "发放形式"},
                    { "sTitle": "物品名称"},
                    { "sTitle": "物品ID"},
                    { "sTitle": "物品数量"},
                    { "sTitle": "领取数量"},
                    { "sTitle": "允许领取次数"},
                    { "sTitle": "领取间隔天数"},
                    { "sTitle": "添加时间"},
                    { "sTitle": "是否开启"},
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

	<?php if ($this->_tpl_vars['type'] == 'add'): ?><!--礼包添加-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>礼包添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">礼包添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=spree_managed&method=add_spree" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <input id="url" name="url" value="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_photo']; ?>
<?php endif; ?>" type="hidden"/>
                                <input id="icon" name="icon" value="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_icon']; ?>
<?php endif; ?>" type="hidden"/>
                                <div class="form-group">
                                   <label for="definpu">礼包名称：</label><span style="color:red">*</span>
                                   <input type="text" class="form-control" id="sname" name="sname" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_name']; ?>
" onblur="clean_error('sname')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包类型：</label><span style="color:red">*</span>
                                    <select name="ctypeid" id='ctypeid' style="width:100%;" onblur="clean_error('ctypeid')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gfs_ctypeid'] == 0): ?>selected="selected"<?php endif; ?>>请选择礼包类型</option>
                                        <?php $_from = $this->_tpl_vars['ctypeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gfs_ctypeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包卡类型：</label><span style="color:red">(礼包码时，为必选)</span>
                                    <select name="ktypeid" id='ktypeid' style="width:100%;">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gfs_keytypeid'] == 0): ?>selected="selected"<?php endif; ?>>请选择礼包卡类型</option>
                                        <?php $_from = $this->_tpl_vars['ktypeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gfs_keytypeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">所属游戏：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onblur="clean_error('gid')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gfs_gid'] == 0): ?>selected="selected"<?php endif; ?>>请选择游戏</option>
                                        <?php $_from = $this->_tpl_vars['gameArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gfs_gid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">描述：</label>
                                   <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gfs_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">兑换积分：</label><span style="color:red">(注意填写，默认为0)</span>
                                   <input type="text" class="form-control" id="integral" name="integral" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_integral']; ?>
" onblur="clean_error('integral')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">获取VIP等级：</label><span style="color:red">(注意填写，默认为0)</span>
                                   <input type="text" class="form-control" id="vip" name="vip" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_vip']; ?>
" onblur="clean_error('vip')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">内容：</label><span style="color:red">*</span>
                                   <textarea class="form-control" rows="3" placeholder="" id='content' name='content' onblur="clean_error('content')"><?php echo $this->_tpl_vars['data']['gfs_content']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包icon：</label><span style="color:red">*</span><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_icon']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="icon" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_icon']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_icon']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=spree_managed&method=add_spree&flag=up&type=icon','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包截图：</label><span style="color:red">*</span><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_photo']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="photo" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_photo']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_photo']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=spree_managed&method=add_spree&flag=up&type=photo','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否物品：</label>
                                    <label>
                                        <input type="radio" value='1' name='goods' <?php if ($this->_tpl_vars['data']['gfs_give'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='goods' <?php if ($this->_tpl_vars['data']['gfs_give'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">物品名称：</label><span style="color:red">(游戏内物品，必填)</span>
                                   <input type="text" class="form-control" id="goodsname" name="goodsname" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_goods']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">物品ID：</label><span style="color:red">(游戏内物品，必填)</span>
                                    <input type="text" class="form-control" id="goodsid" name="goodsid" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_goodsid']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">物品数量：</label><span style="color:red">(游戏内物品，必填)</span>
                                    <input type="text" class="form-control" id="goodsnum" name="goodsnum" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_goodsnum']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">允许领取次数：</label>
                                    <input type="text" class="form-control" id="allownum" name="allownum" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_allownum']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">领取间隔天数：</label>
                                    <input type="text" class="form-control" id="allowday" name="allowday" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_allowday']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否开启：</label>
                                    <label>
                                        <input type="radio" value='1' name='open' <?php if ($this->_tpl_vars['data']['gfs_open'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='open' <?php if ($this->_tpl_vars['data']['gfs_open'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否推荐：</label>
                                    <label>
                                        <input type="radio" value='1' name='hot' <?php if ($this->_tpl_vars['data']['gfs_hot'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='hot' <?php if ($this->_tpl_vars['data']['gfs_hot'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=spree_managed&method=add_spree','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['type'] == 'edit'): ?><!--礼包修改-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>礼包修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">礼包修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=spree_managed&method=edit_spree" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <input id="url" name="url" value="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_photo']; ?>
<?php endif; ?>" type="hidden"/>
                                <input id="icon" name="icon" value="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_icon']; ?>
<?php endif; ?>" type="hidden"/>
                                <div class="form-group">
                                   <label for="definpu">礼包名称：</label><span style="color:red">*</span>
                                   <input type="text" class="form-control" id="sname" name="sname" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_name']; ?>
" onblur="clean_error('sname')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包类型：</label><span style="color:red">*</span>
                                    <select name="ctypeid" id='ctypeid' style="width:100%;" onblur="clean_error('ctypeid')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gfs_ctypeid'] == 0): ?>selected="selected"<?php endif; ?>>请选择礼包类型</option>
                                        <?php $_from = $this->_tpl_vars['ctypeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gfs_ctypeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包卡类型：</label><span style="color:red">(礼包码时，为必选)</span>
                                    <select name="ktypeid" id='ktypeid' style="width:100%;">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gfs_keytypeid'] == 0): ?>selected="selected"<?php endif; ?>>请选择礼包卡类型</option>
                                        <?php $_from = $this->_tpl_vars['ktypeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gfs_keytypeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">所属游戏：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onblur="clean_error('gid')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gfs_gid'] == 0): ?>selected="selected"<?php endif; ?>>请选择游戏</option>
                                        <?php $_from = $this->_tpl_vars['gameArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gfs_gid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">描述：</label>
                                   <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gfs_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">兑换积分：</label><span style="color:red">(注意填写，默认为0)</span>
                                   <input type="text" class="form-control" id="integral" name="integral" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_integral']; ?>
" onblur="clean_error('integral')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">获取VIP等级：</label><span style="color:red">(注意填写，默认为0)</span>
                                   <input type="text" class="form-control" id="vip" name="vip" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_vip']; ?>
" onblur="clean_error('vip')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">内容：</label><span style="color:red">*</span>
                                   <textarea class="form-control" rows="3" placeholder="" id='content' name='content' onblur="clean_error('content')"><?php echo $this->_tpl_vars['data']['gfs_content']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包icon：</label><span style="color:red">*</span><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_icon']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="icon" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_icon']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_icon']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=spree_managed&method=edit_spree&flag=up&type=icon','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包截图：</label><span style="color:red">*</span><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gfs_photo']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="photo" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_photo']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_photo']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=spree_managed&method=edit_spree&flag=up&type=photo','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否物品：</label>
                                    <label>
                                        <input type="radio" value='1' name='goods' <?php if ($this->_tpl_vars['data']['gfs_give'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='goods' <?php if ($this->_tpl_vars['data']['gfs_give'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">物品名称：</label><span style="color:red">(游戏内物品，必填)</span>
                                   <input type="text" class="form-control" id="goodsname" name="goodsname" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_goods']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">物品ID：</label><span style="color:red">(游戏内物品，必填)</span>
                                    <input type="text" class="form-control" id="goodsid" name="goodsid" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_goodsid']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">物品数量：</label><span style="color:red">(游戏内物品，必填)</span>
                                    <input type="text" class="form-control" id="goodsnum" name="goodsnum" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_goodsnum']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">允许领取次数：</label>
                                    <input type="text" class="form-control" id="allownum" name="allownum" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_allownum']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">领取间隔天数：</label>
                                    <input type="text" class="form-control" id="allowday" name="allowday" placeholder="" value="<?php echo $this->_tpl_vars['data']['gfs_allowday']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否开启：</label>
                                    <label>
                                        <input type="radio" value='1' name='open' <?php if ($this->_tpl_vars['data']['gfs_open'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='open' <?php if ($this->_tpl_vars['data']['gfs_open'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否推荐：</label>
                                    <label>
                                        <input type="radio" value='1' name='hot' <?php if ($this->_tpl_vars['data']['gfs_hot'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='hot' <?php if ($this->_tpl_vars['data']['gfs_hot'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=spree_managed&method=edit_spree','myform',0);">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php endif; ?>

    <?php if ($this->_tpl_vars['type'] == 'import'): ?><!--导入礼包码-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>导入礼包码</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">导入礼包码表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=spree_managed&method=import_spree" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <input id="inum" name="inum" value="<?php echo $this->_tpl_vars['files']; ?>
" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">礼包类型：</label>
                                    <select name="ctypeid" id='ctypeid' style="width:100%;" onblur="clean_error('ctypeid')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gci_ctypeid'] == 0): ?>selected='selected'<?php endif; ?>>请选择</option>
                                        <option value="1" <?php if ($this->_tpl_vars['data']['gci_ctypeid'] == 1): ?>selected='selected'<?php endif; ?>>一次领取</option>
                                        <option value="2" <?php if ($this->_tpl_vars['data']['gci_ctypeid'] == 2): ?>selected='selected'<?php endif; ?>>重复领取</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包码类型：</label><span style="color:red">*</span>
                                    <select name="ktypeid" id='ktypeid' style="width:100%;">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gci_keytypeid'] == 0): ?>selected="selected"<?php endif; ?>>请选择礼包卡类型</option>
                                        <?php $_from = $this->_tpl_vars['ktypeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gci_keytypeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">所属游戏：</label><span style="color:red">*</span>
                                    <select name="gid" id='gid' style="width:100%;" onblur="clean_error('gid')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gci_gid'] == 0): ?>selected="selected"<?php endif; ?>>请选择游戏</option>
                                        <?php $_from = $this->_tpl_vars['gameArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gci_gid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包码：</label><span style="color:red">*</span><br/>
                                    <lable>
                                        <input name="inum" id="inum" type="file">
                                        <b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_inum']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_inum']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=spree_managed&method=import_spree&flag=up&type=inum','myform',0)"/>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addSpreeCheck('index.php?module=spree_managed&method=import_spree','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ('spreelist' == $this->_tpl_vars['type']): ?> <!--礼包码列表-->
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">礼包码信息列表 </span>
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
                                <input type='hidden' name="module" value='spree_managed'/>
                                <input type='hidden' name="method" value='list_spreenum'/>
                                <input type='hidden' name="action" value='submit'/>
                                <div style="margin-left:10px;float:left;">请选择游戏：
                                <select name="gid" id='gid' style="width:100%;" style="width:200px;">
                                    <option value="0">请选择游戏</option>
                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                </select>
                                </div>
                                <div style="margin-left:10px;float:left;">礼包码类型：
                                    <div class="form-group">
                                     <select name="mtype" id='mtype' style="width:100%;">
                                            <option value="0">请选择</option>
                                            <?php echo $this->_tpl_vars['ctypeStr']; ?>

                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">是否已领取：
                                    <div class="form-group">
                                     <select name="ctype" id='ctype' style="width:100%;">
                                            <option value="0" <?php if ($this->_tpl_vars['ctype'] == 0): ?>selected='selected'<?php endif; ?>>请选择</option>
                                            <option value="1" <?php if ($this->_tpl_vars['ctype'] == 1): ?>selected='selected'<?php endif; ?>>已领取</option>
                                            <option value="2" <?php if ($this->_tpl_vars['ctype'] == 2): ?>selected='selected'<?php endif; ?>>未领取</option>
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
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >导入日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >礼包码类型</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >礼包码卡号</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >领取人</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >领取时间</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >是否领取</th>
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
    <?php endif; ?>  <!--礼包码列表-->

    <?php if ($this->_tpl_vars['type'] == 'addtype'): ?><!--添加礼包码类型-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>添加礼包码类型</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">添加礼包码类型表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=spree_managed&method=add_spreetype" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">礼包分类名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->_tpl_vars['data']['gst_name']; ?>
" placeholder="请填写礼包分类名称">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包分类描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="请填写礼包分类描述" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gst_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label for="definpu">时间限制：</label>
                                    <input type="text" class="form-control" id="allowday" name="allowday" value="<?php echo $this->_tpl_vars['data']['gst_allowday']; ?>
" placeholder="请填写领取间隔天数">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="definpu">礼包类型：</label>
                                    <label>
                                        <input type="radio" value='1' name='ctype' id='ctype' onclick="showdown(1)" checked="true">
                                        <span class="text">一次领取</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='ctype' id='ctype' onclick="showdown(2)" >
                                        <span class="text">重复领取</span>
                                    </label> 
                                </div> -->
                                <div class="form-group">
                                    <label for="definpu">礼包状态：</label>
                                    <label>
                                        <input type="radio" value='1' name='plat' id='plat' onclick="showdown(1)" checked="true">
                                        <span class="text">开启</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='plat' id='plat' onclick="showdown(2)" >
                                        <span class="text">关闭</span>
                                    </label> 
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否平台展示：</label>
                                    <label>
                                        <input type="radio" value='1' name='open' id='open' onclick="showdown(1)" checked="true">
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='open' id='open' onclick="showdown(2)" >
                                        <span class="text">否</span>
                                    </label> 
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addSpreeCheck('index.php?module=spree_managed&method=add_spreetype','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['type'] == 'spreetypeedit'): ?><!--修改礼包码类型-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>修改礼包码类型</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">修改礼包码类型表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=spree_managed&method=spreetypeedit" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input type='hidden' name="module" value='spree_managed'/>
                                <input type='hidden' name="method" value='spreetypeedit'/>
                                <input name="sysid" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">礼包分类名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->_tpl_vars['data']['gst_name']; ?>
" placeholder="请填写礼包分类名称">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">礼包分类描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="请填写礼包分类描述" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['gst_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label for="definpu">时间限制：</label>
                                    <input type="text" class="form-control" id="allowday" name="allowday" value="<?php echo $this->_tpl_vars['data']['gst_allowday']; ?>
" placeholder="请填写领取间隔天数">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="definpu">礼包类型：</label>
                                    <label>
                                        <input type="radio" value='1' name='ctype' id='ctype' onclick="showdown(1)" checked="true">
                                        <span class="text">一次领取</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='ctype' id='ctype' onclick="showdown(2)" >
                                        <span class="text">重复领取</span>
                                    </label> 
                                </div> -->
                                <div class="form-group">
                                    <label for="definpu">礼包状态：</label>
                                    <label>
                                        <input type="radio" value='1' name='open' id='open' onclick="showdown(1)" <?php if ($this->_tpl_vars['open'] == 1): ?>checked='true'<?php endif; ?>>
                                        <span class="text">开启</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='open' id='open' onclick="showdown(2)" <?php if ($this->_tpl_vars['open'] == 2): ?>checked='true'<?php endif; ?>>
                                        <span class="text">关闭</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否平台展示：</label>
                                    <label>
                                        <input type="radio" value='1' name='plat' id='plat' onclick="showdown(1)" <?php if ($this->_tpl_vars['plat'] == 1): ?>checked='true'<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='plat' id='plat' onclick="showdown(2)" <?php if ($this->_tpl_vars['plat'] == 2): ?>checked='true'<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addSpreeCheck('index.php?module=spree_managed&method=spreetypeedit','myform',0);">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ('spreetype' == $this->_tpl_vars['type']): ?> <!--礼包码类型列表-->
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">礼包码类型信息列表 </span>
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
                                <input type='hidden' name="module" value='spree_managed'/>
                                <input type='hidden' name="method" value='list_spreetype'/>
                                <div style="margin-left:10px;float:left;">礼包码类型名称：
                                    <div class="form-group">
                                     <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->_tpl_vars['gst_name']; ?>
">
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
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >分类名称</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >礼包状态</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >是否平台展示</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >操作人ID</th>
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
    <?php endif; ?>  <!--礼包码类型列表-->

	<script>
        function addGameCheck(url,id,name) {
            //检测数据
            var inum = $('#act').val();
            var sname = clean_error('sname');
            var ctypeid = clean_error('ctypeid');
            var gid = clean_error('gid');
            var content = clean_error('content');
            var imgurl = $('#url').val();
            var iconurl = $('#icon').val();
            alert(gid);
            if(sname&&ctypeid&&gid&&content&&imgurl){
                formaction(url,id,name);
            } else if (imgurl == '') {
                alert('请上传礼包截图');
                return false;
            } else if (iconurl == '') {
                alert('请上传礼包icon');
                return false;
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