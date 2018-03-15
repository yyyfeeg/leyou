<?php /* Smarty version 2.6.20, created on 2018-03-03 17:07:24
         compiled from game.html */ ?>
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

    <?php if ('gamelist' == $this->_tpl_vars['game']): ?>  <!--游戏列表-->
    <style> ul{ list-style: none; } </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">游戏列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索游戏名称" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索游戏类型" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索自定义项" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索推荐等级" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索横竖屏" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索所属团队" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索官网地址" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索安卓下载地址" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索IOS下载地址" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索越狱下载地址" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索游戏描述" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索游戏ICON" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索游戏截图" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索安卓二维码" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索IOS二维码" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索越狱二维码" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索状态" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索前端展示" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索接入客服" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索操作" class="form-control input-sm"></th>
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
                    { "sTitle": "游戏名称" },
                    { "sTitle": "游戏类型" },
                    { "sTitle": "自定义项" },
                    { "sTitle": "推荐等级" },
                    { "sTitle": "横竖屏" },
                    { "sTitle": "所属团队" },
                    { "sTitle": "官网地址" },
                    { "sTitle": "安卓下载地址" },
                    { "sTitle": "IOS下载地址" },
                    { "sTitle": "越狱下载地址" },
                    { "sTitle": "游戏描述"},
                    { "sTitle": "游戏ICON"},
                    { "sTitle": "游戏截图"},
                    { "sTitle": "安卓二维码"},
                    { "sTitle": "IOS二维码"},
                    { "sTitle": "越狱二维码"},
                    { "sTitle": "状态"},
                    { "sTitle": "前端展示"},
                    { "sTitle": "接入客服"},
                    { "sTitle": "添加时间"},
                    { "sTitle": "操作"},
                    { "sTitle": "数据上报key"},
                    { "sTitle": "充值key"},
                    { "sTitle": "回调地址"},
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

    <?php if ('add' == $this->_tpl_vars['game']): ?> <!--游戏添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>游戏添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">游戏添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=game&method=add" method="post" id='myform' name='myform'>
                                <input name="act" value="addgame" type="hidden">
                                <input id="icon_url" name="icon_url" value="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_icon']; ?>
<?php endif; ?>" type="hidden">
                                <input name="photo_url" value="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_photo']; ?>
<?php endif; ?>" type="hidden">
                                <input name="azewm_url" value="<?php if ($this->_tpl_vars['imgArr']['azewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['azewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_azewm']; ?>
<?php endif; ?>" type="hidden">
                                <input name="iosewm_url" value="<?php if ($this->_tpl_vars['imgArr']['iosewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['iosewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_iosewm']; ?>
<?php endif; ?>" type="hidden">
                                <input name="yyewm_url" value="<?php if ($this->_tpl_vars['imgArr']['yyewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['yyewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_yyewm']; ?>
<?php endif; ?>" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">游戏名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="gname" name="gname" placeholder="例如：倚天" value="<?php echo $this->_tpl_vars['data']['gi_gname']; ?>
" onblur="clean_error('gname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏类型：</label>
                                    <label>
                                        <input type="radio" value='1' name='gtype' id='gtype' <?php if ($this->_tpl_vars['data']['gi_gtype'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">网页游戏</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='gtype' id='gtype' <?php if ($this->_tpl_vars['data']['gi_gtype'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">手机游戏</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='gtype' id='gtype' <?php if ($this->_tpl_vars['data']['gi_gtype'] == 3): ?>checked="true"<?php endif; ?>>
                                        <span class="text">双端游戏</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">自定义项：</label>
                                    <label>
                                        <input type="checkbox" value='1' name='virtue[]' id='virtue1' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 1): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">HOT</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" value='2' name='virtue[]' id='virtue2' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 2): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">NEW</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" value='3' name='virtue[]' id='virtue3' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 3): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">推荐</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" value='4' name='virtue[]' id='virtue4' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 4): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">幻灯</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">评价等级：</label>
                                    <label>
                                        <input type="radio" value='1' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">一星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">两星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 3): ?>checked="true"<?php endif; ?>>
                                        <span class="text">三星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='4' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 4): ?>checked="true"<?php endif; ?>>
                                        <span class="text">四星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='5' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 5): ?>checked="true"<?php endif; ?>>
                                        <span class="text">五星</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">屏幕展示：</label>
                                    <label>
                                        <input type="radio" value='1' name='screen' id='screen' <?php if ($this->_tpl_vars['data']['gi_screen'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">横屏</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='screen' id='screen' <?php if ($this->_tpl_vars['data']['gi_screen'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">竖屏</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏描述：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="3" placeholder="例如：倚天是一款爆棒的游戏......" id='description' name='description' onblur="clean_error('description')"><?php echo $this->_tpl_vars['data']['gi_description']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">数据上报key：</label>
                                     <input type="text" class="form-control" id="up_key" name="up_key" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_key']; ?>
" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">充值key</label>
                                     <input type="text" class="form-control" id="pay_key" name="pay_key" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_paykey']; ?>
" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">充值回调地址：</label>
                                     <input type="text" class="form-control" id="repay_addr" name="repay_addr" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_repay_addr']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">所属游戏团队：</label><span style="color:red">*</span>
                                    <select name="team" id='team' style="width:100%;" onchange="clean_error('team')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gi_team'] == 0): ?>selected="selected"<?php endif; ?>>所属游戏团队</option>
                                        <?php $_from = $this->_tpl_vars['teamArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gi_team'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏官网地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="gurl" name="url" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_url']; ?>
" onblur="clean_error('gurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">安卓下载地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="azdlurl" name="azdlurl" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_azdlurl']; ?>
" onblur="clean_error('azdlurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">IOS下载地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="iosdlurl" name="iosdlurl" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_iosdlurl']; ?>
" onblur="clean_error('iosdlurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">越狱下载地址：</label><span style="color:red">*</span>
                                     <input type="text" class="form-control" id="yydlurl" name="yydlurl" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_yydlurl']; ?>
" onblur="clean_error('yydlurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">客服电话：</label>
                                     <input type="text" class="form-control" id="kfphone" name="kfphone" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_kfphone']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏icon：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_icon']; ?>
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
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error1']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success1']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=add&flag=up&type=1','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏截图：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_photo']; ?>
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
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error2']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success2']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=add&flag=up&type=2','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">安卓下载二维码：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['azewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['azewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_azewm']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="azewm" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error3']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success3']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=add&flag=up&type=3','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">IOS下载二维码：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['iosewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['iosewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_iosewm']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="iosewm" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error4']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success4']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=add&flag=up&type=4','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">越狱下载二维码：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['yyewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['yyewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_yyewm']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="yyewm" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error5']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success5']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=add&flag=up&type=5','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序：</label>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="默认为 0 " value="<?php echo $this->_tpl_vars['data']['gi_order']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否前台展示：</label>
                                    <label>
                                        <input type="radio" value='1' name='show' id='show' <?php if ($this->_tpl_vars['data']['gi_show'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='show' id='show' <?php if ($this->_tpl_vars['data']['gi_show'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否接入客服系统：</label>
                                    <label>
                                        <input type="radio" value='1' name='custom' id='custom' <?php if ($this->_tpl_vars['data']['gi_custom'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='custom' id='custom' <?php if ($this->_tpl_vars['data']['gi_custom'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否锁定：</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yes no" type="checkbox" id='status' name='status' <?php if ($this->_tpl_vars['data']['gi_status'] == 2): ?>checked="checked"<?php endif; ?>>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=game&method=add','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--游戏添加-->

    <?php if ('edit' == $this->_tpl_vars['game']): ?> <!--游戏修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>游戏修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">游戏修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=game&method=edit" method="post" id='myform' name='myform'>
                                <input name="act" value="addgame" type="hidden">
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden">
                                <input id="icon_url" name="icon_url" value="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_icon']; ?>
<?php endif; ?>" type="hidden">
                                <input name="photo_url" value="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_photo']; ?>
<?php endif; ?>" type="hidden">
                                <input name="azewm_url" value="<?php if ($this->_tpl_vars['imgArr']['azewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['azewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_azewm']; ?>
<?php endif; ?>" type="hidden">
                                <input name="iosewm_url" value="<?php if ($this->_tpl_vars['imgArr']['iosewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['iosewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_iosewm']; ?>
<?php endif; ?>" type="hidden">
                                <input name="yyewm_url" value="<?php if ($this->_tpl_vars['imgArr']['yyewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['yyewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_yyewm']; ?>
<?php endif; ?>" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">游戏名称：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="gname" name="gname" placeholder="例如：倚天" value="<?php echo $this->_tpl_vars['data']['gi_gname']; ?>
" onblur="clean_error('gname')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏类型：</label>
                                    <label>
                                        <input type="radio" value='1' name='gtype' id='gtype' <?php if ($this->_tpl_vars['data']['gi_gtype'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">网页游戏</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='gtype' id='gtype' <?php if ($this->_tpl_vars['data']['gi_gtype'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">手机游戏</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='gtype' id='gtype' <?php if ($this->_tpl_vars['data']['gi_gtype'] == 3): ?>checked="true"<?php endif; ?>>
                                        <span class="text">双端游戏</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">自定义项：</label>
                                    <label>
                                        <input type="checkbox" value='1' name='virtue[]' id='virtue1' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 1): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">HOT</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" value='2' name='virtue[]' id='virtue2' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 2): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">NEW</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" value='3' name='virtue[]' id='virtue3' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 3): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">推荐</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" value='4' name='virtue[]' id='virtue4' <?php $_from = $this->_tpl_vars['virtueArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?><?php if ($this->_tpl_vars['value'] == 4): ?>checked="true"<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
                                        <span class="text">幻灯</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">评价等级：</label>
                                    <label>
                                        <input type="radio" value='1' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">一星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">两星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 3): ?>checked="true"<?php endif; ?>>
                                        <span class="text">三星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='4' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 4): ?>checked="true"<?php endif; ?>>
                                        <span class="text">四星</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='5' name='rating' id='rating' <?php if ($this->_tpl_vars['data']['gi_rating'] == 5): ?>checked="true"<?php endif; ?>>
                                        <span class="text">五星</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">屏幕展示：</label>
                                    <label>
                                        <input type="radio" value='1' name='screen' id='screen' <?php if ($this->_tpl_vars['data']['gi_screen'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">横屏</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='screen' id='screen' <?php if ($this->_tpl_vars['data']['gi_screen'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">竖屏</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏描述：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="3" placeholder="例如：倚天是一款爆棒的游戏......" id='description' name='description' onblur="clean_error('description')"><?php echo $this->_tpl_vars['data']['gi_description']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">数据上报key：</label>
                                     <input type="text" class="form-control" id="up_key" name="up_key" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_key']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>readonly="readonly"<?php endif; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">充值key</label>
                                     <input type="text" class="form-control" id="pay_key" name="pay_key" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_paykey']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>readonly="readonly"<?php endif; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">充值回调地址：</label>
                                     <input type="text" class="form-control" id="repay_addr" name="repay_addr" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_repay_addr']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">所属游戏团队：</label><span style="color:red">*</span>
                                    <select name="team" id='team' style="width:100%;" onchange="clean_error('team')">
                                        <option value="0" <?php if ($this->_tpl_vars['data']['gi_team'] == 0): ?>selected="selected"<?php endif; ?>>所属游戏团队</option>
                                        <?php $_from = $this->_tpl_vars['teamArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['gi_team'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏官网地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="gurl" name="url" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_url']; ?>
" onblur="clean_error('gurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">安卓下载地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="azdlurl" name="azdlurl" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_azdlurl']; ?>
" onblur="clean_error('azdlurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">IOS下载地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="iosdlurl" name="iosdlurl" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_iosdlurl']; ?>
" onblur="clean_error('iosdlurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">越狱下载地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="yydlurl" name="yydlurl" placeholder="例如：http://www.hlwy.com (还没有地址，请填写 # )" value="<?php echo $this->_tpl_vars['data']['gi_yydlurl']; ?>
" onblur="clean_error('yydlurl')">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">客服电话：</label>
                                     <input type="text" class="form-control" id="kfphone" name="kfphone" placeholder="" value="<?php echo $this->_tpl_vars['data']['gi_kfphone']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏icon：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['icon_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['icon_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_icon']; ?>
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
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error1']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success1']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=edit&flag=up&type=1','myform',0)">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">游戏截图：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['photo_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['photo_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_photo']; ?>
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
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error2']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success2']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=edit&flag=up&type=2','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">安卓下载二维码：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['azewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['azewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_azewm']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="azewm" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error3']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success3']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=edit&flag=up&type=3','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">IOS下载二维码：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['iosewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['iosewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_iosewm']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="iosewm" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error4']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success4']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=edit&flag=up&type=4','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">越狱下载二维码：</label>
                                    <br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['yyewm_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['yyewm_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['gi_yyewm']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="yyewm" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error5']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success5']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=game&method=edit&flag=up&type=5','myform',0)">
                                    <hr/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序：</label>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="排序号" value="<?php echo $this->_tpl_vars['data']['gi_order']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否前台展示：</label>
                                    <label>
                                        <input type="radio" value='1' name='show' id='show' <?php if ($this->_tpl_vars['data']['gi_show'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='show' id='show' <?php if ($this->_tpl_vars['data']['gi_show'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否接入客服系统：</label>
                                    <label>
                                        <input type="radio" value='1' name='custom' id='custom' <?php if ($this->_tpl_vars['data']['gi_custom'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">是</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='custom' id='custom' <?php if ($this->_tpl_vars['data']['gi_custom'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否锁定：</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yes no" type="checkbox" id='status' name='status' <?php if ($this->_tpl_vars['data']['gi_status'] == 2): ?>checked="checked"<?php endif; ?>>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=game&method=edit','myform',0)">修 改</button>
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
        function addGameCheck(url,id,name)
        {
            //检测数据
            var names = clean_error('gname');
            var description = clean_error('description');
            var team = clean_error('team');
            var gurl = clean_error('gurl');
            var dlurl= clean_error('dlurl');
            // var icon = check_upload('icon_url','请上传游戏ICON！');
            if(names && description && team && gurl && dlurl && icon){
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