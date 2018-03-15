<?php /* Smarty version 2.6.20, created on 2018-03-03 17:08:27
         compiled from frontend_essay.html */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <link href="myeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" charset="utf-8" src="myeditor/umeditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="myeditor/umeditor.min.js"></script>
    <script type="text/javascript" src="myeditor/lang/zh-cn/zh-cn.js"></script>
	</head>
<body>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loading.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<?php if ($this->_tpl_vars['type'] == 'list'): ?><!--文章列表-->
	<style> ul{ list-style: none; } </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">文章列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索文章标题" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索发布时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索录入时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索栏目" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索点击量" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索HTML" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索显示选项" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索发布人" class="form-control input-sm"></th>
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
                "aaSorting": [[0,'desc']],
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
                    { "sTitle": "文章标题" },
                    { "sTitle": "发布时间" },
                    { "sTitle": "录入时间" },
                    { "sTitle": "栏目" },
                    { "sTitle": "点击量"},
                    { "sTitle": "HTML"},
                    { "sTitle": "显示选项"},
                    { "sTitle": "发布人"},
                    { "sTitle": "状态"},
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

    <?php if ($this->_tpl_vars['type'] == 'add'): ?><!--添加文章-->
    <div class="col-lg-12 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>文章添加</h5>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">文章添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_essay&method=add_essay" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <input name="sphoto" value="<?php if ($this->_tpl_vars['imgArr']['fe_sphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_sphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_sphoto']; ?>
<?php endif; ?>" type="hidden"/>
                                <input name="bphoto" value="<?php if ($this->_tpl_vars['imgArr']['fe_bphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_bphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_bphoto']; ?>
<?php endif; ?>" type="hidden" />
                                <div class="form-group"><span class="btn btn-success">常规信息</span></div>
                                <div class="form-group">
                                    <div class="row">
                                       <div class="col-xs-5">
                                            <div>
                                                <label for="definpu">文章标题：<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="" value="<?php echo $this->_tpl_vars['data']['fe_title']; ?>
" onblur="clean_error('title')"/>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div>
                                               <label for="definpu">作者：<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="author" name="author" placeholder="" value="<?php if ($this->_tpl_vars['data']['fe_author'] != ''): ?><?php echo $this->_tpl_vars['data']['fe_author']; ?>
<?php else: ?>欢乐游戏<?php endif; ?>" onblur="clean_error('author')"/> 
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">自定义属性：</label>
                                    <label>
                                        <input type="checkbox" value='1' name='virtue[]' id="virtue1" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 1): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">头条[1]</span>
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" value='2' name='virtue[]' id="virtue2" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 2): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">推荐[2]</span>
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" value='3' name='virtue[]' id="virtue3" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 3): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">跳转[3]</span>
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" value='4' name='virtue[]' id="virtue4" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 4): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">幻灯[4]</span>
                                    </label>
                                </div>
                                <div id="jurl" class="form-group">
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <div>
                                                <label for="definpu">跳转地址：<span style="color:red">(自定义属性勾选了“跳转”,请填写跳转地址)</span></label>
                                                <input type="text" class="form-control" name="jurl" placeholder="格式：http://域名" value="<?php echo $this->_tpl_vars['data']['fe_jurl']; ?>
"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">所属栏目：<span style="color:red">*</span></label>
                                                <select name="rubricid" id='rubricid' style="width:100%;" onchange="clean_error('rubricid')">
                                                    <option value="0" <?php if ($this->_tpl_vars['data']['fe_rubricid'] == 0): ?>selected="selected"<?php endif; ?>>请选择栏目...</option>
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
" <?php if ($this->_tpl_vars['data']['fe_rubricid'] == $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['fr_name']; ?>
</option>
                                                    <?php endfor; endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">所属游戏：</label>
                                                <select name="gameid" id='gameid' style="width:100%;">
                                                    <option value="0" <?php if ($this->_tpl_vars['data']['fe_gameid'] == 0): ?>selected="selected"<?php endif; ?>>请选择游戏...</option>
                                                    <?php $_from = $this->_tpl_vars['gameArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['fe_gameid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                                    <?php endforeach; endif; unset($_from); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label for="definpu">小缩略图：</label><span style="color:red">(用于文章列表缩略图显示)</span><br/>
                                            <label style="float:left;margin-right:10px">
                                                <img src="<?php if ($this->_tpl_vars['imgArr']['fe_sphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_sphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_sphoto']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                            </label>
                                            <lable>
                                                <input name="fe_sphoto" type="file">
                                                <br>
                                                允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                                <br/>
                                                允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                                <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error1']; ?>
</b>
                                                <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success1']; ?>
</b>
                                            </lable>
                                            <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_essay&method=add_essay&flag=up&type=1','myform',0)">
                                        </div>
                                        <div class="col-xs-4">
                                            <label for="definpu">大缩略图：</label><span style="color:red">(用于幻灯片显示)</span><br/>
                                            <label style="float:left;margin-right:10px">
                                                <img src="<?php if ($this->_tpl_vars['imgArr']['fe_bphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_bphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_bphoto']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                            </label>
                                            <lable>
                                                <input name="fe_bphoto" type="file">
                                                <br>
                                                允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                                <br/>
                                                允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                                <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error2']; ?>
</b>
                                                <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success2']; ?>
</b>
                                            </lable>
                                            <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_essay&method=add_essay&flag=up&type=2','myform',0)">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="definpu">关键字：</label>
                                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="格式：多个关键字用','分开" value="<?php echo $this->_tpl_vars['data']['fe_keywords']; ?>
"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="definpu">内容摘要：</label>
                                            <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['fe_desc']; ?>
</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="definpu" style="color: #53a93f;font-size: 14px;">文章内容：</label>
                                    <script type="text/plain" id="myEditor" name="contents" style="width: 1100px;height:350px;"><?php echo $this->_tpl_vars['data']['fe_contents']; ?>
</script>
                                </div>
                                <div class="form-group"><span class="btn btn-success">高级参数</span></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">生成模板：</label>
                                            <input type="text" class="form-control" id="template" name="template" placeholder="" value="<?php echo $this->_tpl_vars['data']['fe_template']; ?>
"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">排序号：</label>
                                            <input type="text" class="form-control" id="order" name="order" placeholder="默认为0" value="<?php echo $this->_tpl_vars['data']['fe_order']; ?>
"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">发布时间：</label>
                                            <input type="text" value="<?php echo $this->_tpl_vars['data']['fe_printtime']; ?>
" class="form-control date-picker" name="printtime" id="printtime" data-date-format="yyyy-mm-dd">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">发布选项：</label><br>
                                            <label>
                                                <input type="radio" value='1' name='timing' id='timing' <?php if ($this->_tpl_vars['data']['fe_timing'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">生成HTML</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='timing' id='timing' <?php if ($this->_tpl_vars['data']['fe_timing'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">定时发布</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">显示选项：</label>
                                            <label>
                                                <input type="radio" value='1' name='showtype' id='showtype' <?php if ($this->_tpl_vars['data']['fe_showtype'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">手机端显示</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='showtype' id='showtype' <?php if ($this->_tpl_vars['data']['fe_showtype'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">PC端显示</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='3' name='showtype' id='showtype' <?php if ($this->_tpl_vars['data']['fe_showtype'] == 3): ?>checked="true"<?php endif; ?>>
                                                <span class="text">双端显示</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">状态：</label>
                                            <label>
                                                <input type="radio" value='1' name='status' id='status' <?php if ($this->_tpl_vars['data']['fe_status'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">草稿</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='status' id='status' <?php if ($this->_tpl_vars['data']['fe_status'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">发布</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='3' name='status' id='status' <?php if ($this->_tpl_vars['data']['fe_status'] == 3): ?>checked="true"<?php endif; ?>>
                                                <span class="text">下架</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_essay&method=add_essay','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['type'] == 'edit'): ?><!--修改文章-->
    <div class="col-lg-12 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>文章修改</h5>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">文章修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_essay&method=edit_essay" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <input name="sphoto" value="<?php if ($this->_tpl_vars['imgArr']['fe_sphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_sphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_sphoto']; ?>
<?php endif; ?>" type="hidden"/>
                                <input name="bphoto" value="<?php if ($this->_tpl_vars['imgArr']['fe_bphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_bphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_bphoto']; ?>
<?php endif; ?>" type="hidden" />
                                <div class="form-group"><span class="btn btn-success">常规信息</span></div>
                                <div class="form-group">
                                    <div class="row">
                                       <div class="col-xs-5">
                                            <div>
                                                <label for="definpu">文章标题：<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="" value="<?php echo $this->_tpl_vars['data']['fe_title']; ?>
" onblur="clean_error('title')"/>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div>
                                               <label for="definpu">作者：<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="author" name="author" placeholder="" value="<?php if ($this->_tpl_vars['data']['fe_author'] != ''): ?><?php echo $this->_tpl_vars['data']['fe_author']; ?>
<?php else: ?>欢乐游戏<?php endif; ?>" onblur="clean_error('author')"/> 
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">自定义属性：</label>
                                    <label>
                                        <input type="checkbox" value='1' name='virtue[]' id="virtue1" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 1): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">头条[1]</span>
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" value='2' name='virtue[]' id="virtue2" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 2): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">推荐[2]</span>
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" value='3' name='virtue[]' id="virtue3" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 3): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">跳转[3]</span>
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" value='4' name='virtue[]' id="virtue4" <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 4): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">幻灯[4]</span>
                                    </label>
                                </div>
                                <div id="jurl" class="form-group">
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <div>
                                                <label for="definpu">跳转地址：<span style="color:red">(自定义属性勾选了“跳转”,请填写跳转地址)</span></label>
                                                <input type="text" class="form-control" name="jurl" placeholder="格式：http://域名" value="<?php echo $this->_tpl_vars['data']['fe_jurl']; ?>
"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">所属栏目：<span style="color:red">*</span></label>
                                                <select name="rubricid" id='rubricid' style="width:100%;" onchange="clean_error('rubricid')">
                                                    <option value="0" <?php if ($this->_tpl_vars['data']['fe_rubricid'] == 0): ?>selected="selected"<?php endif; ?>>请选择栏目...</option>
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
" <?php if ($this->_tpl_vars['data']['fe_rubricid'] == $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['rubricArr'][$this->_sections['a']['index']]['fr_name']; ?>
</option>
                                                    <?php endfor; endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">所属游戏：</label>
                                                <select name="gameid" id='gameid' style="width:100%;">
                                                    <option value="0" <?php if ($this->_tpl_vars['data']['fe_gameid'] == 0): ?>selected="selected"<?php endif; ?>>请选择游戏...</option>
                                                    <?php $_from = $this->_tpl_vars['gameArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['fe_gameid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                                    <?php endforeach; endif; unset($_from); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label for="definpu">小缩略图：</label><span style="color:red">(用于文章列表缩略图显示)</span><br/>
                                            <label style="float:left;margin-right:10px">
                                                <img src="<?php if ($this->_tpl_vars['imgArr']['fe_sphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_sphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_sphoto']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                            </label>
                                            <lable>
                                                <input name="fe_sphoto" type="file">
                                                <br>
                                                允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                                <br/>
                                                允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                                <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error1']; ?>
</b>
                                                <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success1']; ?>
</b>
                                            </lable>
                                            <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_essay&method=edit_essay&flag=up&type=1','myform',0)">
                                        </div>
                                        <div class="col-xs-4">
                                            <label for="definpu">大缩略图：</label><span style="color:red">(用于幻灯片显示)</span><br/>
                                            <label style="float:left;margin-right:10px">
                                                <img src="<?php if ($this->_tpl_vars['imgArr']['fe_bphoto'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['fe_bphoto']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fe_bphoto']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                            </label>
                                            <lable>
                                                <input name="fe_bphoto" type="file">
                                                <br>
                                                允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                                <br/>
                                                允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                                <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error2']; ?>
</b>
                                                <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success2']; ?>
</b>
                                            </lable>
                                            <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_essay&method=edit_essay&flag=up&type=2','myform',0)">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="definpu">关键字：</label>
                                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="格式：多个关键字用','分开" value="<?php echo $this->_tpl_vars['data']['fe_keywords']; ?>
"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="definpu">内容摘要：</label>
                                            <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['fe_desc']; ?>
</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="definpu" style="color: #53a93f;font-size: 14px;">文章内容：</label>
                                    <script type="text/plain" id="myEditor" name="contents" style="width: 1100px;height:350px;"><?php echo $this->_tpl_vars['data']['fe_contents']; ?>
</script>
                                </div>
                                <div class="form-group"><span class="btn btn-success">高级参数</span></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">生成模板：</label>
                                            <input type="text" class="form-control" id="template" name="template" placeholder="" value="<?php echo $this->_tpl_vars['data']['fe_template']; ?>
"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">排序号：</label>
                                            <input type="text" class="form-control" id="order" name="order" placeholder="默认为0" value="<?php echo $this->_tpl_vars['data']['fe_order']; ?>
"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">发布时间：</label>
                                            <input type="text" value="<?php echo $this->_tpl_vars['data']['fe_printtime']; ?>
" class="form-control date-picker" name="printtime" id="printtime" data-date-format="yyyy-mm-dd">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">发布选项：</label><br>
                                            <label>
                                                <input type="radio" value='1' name='timing' id='timing' <?php if ($this->_tpl_vars['data']['fe_timing'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">生成HTML</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='timing' id='timing' <?php if ($this->_tpl_vars['data']['fe_timing'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">定时发布</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">显示选项：</label>
                                            <label>
                                                <input type="radio" value='1' name='showtype' id='showtype' <?php if ($this->_tpl_vars['data']['fe_showtype'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">手机端显示</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='showtype' id='showtype' <?php if ($this->_tpl_vars['data']['fe_showtype'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">PC端显示</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='3' name='showtype' id='showtype' <?php if ($this->_tpl_vars['data']['fe_showtype'] == 3): ?>checked="true"<?php endif; ?>>
                                                <span class="text">双端显示</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">状态：</label>
                                            <label>
                                                <input type="radio" value='1' name='status' id='status' <?php if ($this->_tpl_vars['data']['fe_status'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">草稿</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='status' id='status' <?php if ($this->_tpl_vars['data']['fe_status'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">发布</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='3' name='status' id='status' <?php if ($this->_tpl_vars['data']['fe_status'] == 3): ?>checked="true"<?php endif; ?>>
                                                <span class="text">下架</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_essay&method=edit_essay','myform',0);">修 改</button>
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
            var title = clean_error('title');
            var author = clean_error('author');
            var rubricid = clean_error('rubricid');
            if(title && author && rubricid){
                formaction(url,id,name);
            }else{
                return false;
            }
        }

        function clean_error(name) {
            $('#'+name).next().remove();
            var names = $('#'+name).val();
            if(names == "" || names == '0' || names == 'undefined'){
                $('#'+name).parent().addClass("has-error");
                $('#'+name).focus();
                $('#'+name).after('<small class="help-block" style="" data-bv-validator="notEmpty" data-bv-validator-for="address"></small>');
                $('#'+name).next().html('内容填写有误！');
                return false;
            }else{
                $('#'+name).parent().removeClass("has-error");
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

        function check_upload(id,error)
        {
            var val = $('#'+id).val();
            if (val == '') {
                alert(error);
                return false;
            }
            return true;
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
<script src="templates/js/datetime/bootstrap-datepicker.js"></script>
<script src="templates/js/datetime/bootstrap-timepicker.js"></script>
<script>
    $('.date-picker').datepicker();
    //var um = UM.getEditor('myEditor');
    var editor = UM.getEditor('myEditor',{
    toolbar:
        [
            'source | undo redo | bold italic underline strikethrough | superscript subscript | forecolor backcolor | removeformat |',
            'insertorderedlist insertunorderedlist | selectall cleardoc paragraph | fontfamily fontsize' ,
            '| justifyleft justifycenter justifyright justifyjustify |',
            'link unlink | emotion image',
            '| horizontal print preview fullscreen', 'formula'
        ]
});
</script>
</html>