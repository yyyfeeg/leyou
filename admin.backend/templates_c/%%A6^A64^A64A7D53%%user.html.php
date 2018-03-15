<?php /* Smarty version 2.6.20, created on 2018-03-06 14:04:39
         compiled from user.html */ ?>

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
     <?php if ('list' == $this->_tpl_vars['manager']): ?>  <!--权限列表-->
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
                        <span class="widget-caption">用户列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索登录名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索真实名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索登录时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索登陆ip" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索登陆次数" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索权限组" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加人登录名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加人真实名" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加时间" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索是否锁定" class="form-control input-sm"></th>
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

                /* Formatting function for row details */
                function fnFormatDetails(oTable, nTr) {
                    var str = '<ul>';
                    $.each( nTr['rightinfo'], function(i, n){str += '<li>'+(i+1)+'、'+n['rightname']+'</li>'});
                    str += '</ul>';
                    var sOut = '<table>';
                    if(nTr['isadmin'] || nTr['true']){
                        sOut += '<tr><td>权限操作:</td><td>' + '[<a title="在此修改用户数据权限" href="index.php?module=user&method=edit&uid='+nTr['baseinfo']['sysid']+'&uname='+nTr['baseinfo']['a_name']+'">修改个人信息</a>]&nbsp;&nbsp;[<a title="在此修改用户功能权限" href="index.php?module=user&method=edit_right&uid='+nTr['baseinfo']['sysid']+'">修改个人权限</a>]</td></tr>';
                    }
                    sOut += '<tr><td>锁定:</td><td>' + nTr['lockhtml'] + '</td></tr>';
                    sOut += '<tr><td>身份特征:</td><td>' + nTr['baseinfo']['myidentify'] + '</td></tr>';
                    sOut += '<tr><td>所在权限组:</td><td>' + nTr['baseinfo']['groupname'] + '</td></tr>';
                    sOut += '<tr><td>可执行权限:</td><td>' + str + '</td></tr>';
                    sOut += '<tr><td>电话:</td><td>' + nTr['baseinfo']['a_tel'] + '</td></tr>';
                    sOut += '<tr><td>QQ号码:</td><td>' + nTr['baseinfo']['a_qq'] + '</td></tr>';
                    sOut += '<tr><td>电子邮件:</td><td>' + nTr['baseinfo']['a_email'] + '</td></tr>';
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
                    { "sTitle": "登录名",class:'123'},
                    { "sTitle": "真实名" },
                    { "sTitle": "登录时间" },
                    { "sTitle": "登陆ip" },
                    { "sTitle": "登陆次数" },
                    { "sTitle": "权限组" },
                    { "sTitle": "添加人登录名" },
                    { "sTitle": "添加人真实名" },
                    { "sTitle": "添加时间" },
                    { "sTitle": "是否锁定" },
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
                    var url = $(this).html();
                    var reg = /\[\d*/;
                    var res = url.match(reg);
                    var id = res[0].replace('[','');
                    var url = "index.php?module=user&method=userinfo";
                    var json = {'uid':id};
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
    <?php endif; ?>  <!--权限列表-->

    <?php if ('adduser' == $this->_tpl_vars['manager']): ?> <!--管理员添加-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>管理员添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">管理员添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form action="index.php?module=user&method=adduser" enctype="multipart/form-data" method="post" id='myform' name='myform'>
                                <input name="act" value="addmnger" type="hidden">
                                <input name="upload_url" value="<?php echo $this->_tpl_vars['info']; ?>
" type="hidden">
                                <div class="form-group">
                                    <label for="definpu">登陆名称</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="logname" name="logname" placeholder="必须为字母或数字组合" value="<?php echo $this->_tpl_vars['data']['a_name']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">密码</label><span style="color:red">*</span>
                                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="密码" value="<?php echo $this->_tpl_vars['data']['a_pwd']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">重复密码</label><span style="color:red">*</span>
                                    <input type="password" class="form-control" id="repeatpwd" name="repeatpwd" placeholder="重复密码" value="<?php echo $this->_tpl_vars['data']['a_pwd']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">真实姓名</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="truename" name="truename" placeholder="真实姓名" value="<?php echo $this->_tpl_vars['data']['a_truename']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">联系电话</label><span style="color:red">*</span>
                                    <input type="tel" class="form-control" id="tel" name="tel" placeholder="11位纯数字格式" value="<?php echo $this->_tpl_vars['data']['a_tel']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">联系邮箱</label><span style="color:red">*</span>
                                    <input type="email" class="form-control" id="mail" name="mail" placeholder="联系邮箱" value="<?php echo $this->_tpl_vars['data']['a_email']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">QQ号码</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="qqnum" name="qqnum" placeholder="QQ号码" value="<?php echo $this->_tpl_vars['data']['a_qq']; ?>
">
                                </div>

                                <div class="form-group">
                                    <label for="definpu">用户级别</label>
                                    <label>
                                        <input type="radio" value='1' name='isadmin' <?php if ($this->_tpl_vars['data']['a_isadmin'] == 1): ?>selected="selected"<?php endif; ?>>
                                        <span class="text">超级管理员</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='isadmin' <?php if ($this->_tpl_vars['data']['a_isadmin'] == 3): ?>selected="selected"<?php endif; ?>>
                                        <span class="text">团队管理员</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='isadmin' <?php if ($this->_tpl_vars['data']['a_isadmin'] == 2): ?>selected="selected"<?php endif; ?>>
                                        <span class="text">普通用户</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">分配权限组</label><span style="color:red">*</span>
                                    <select id="groupid" name="groupid" id='groupid' style="width:100%;" onChange="getrights(this.value);">
                                        <option value="0" >不分权限组</option>
                                        <?php unset($this->_sections['g']);
$this->_sections['g']['name'] = 'g';
$this->_sections['g']['loop'] = is_array($_loop=$this->_tpl_vars['group']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['g']['show'] = true;
$this->_sections['g']['max'] = $this->_sections['g']['loop'];
$this->_sections['g']['step'] = 1;
$this->_sections['g']['start'] = $this->_sections['g']['step'] > 0 ? 0 : $this->_sections['g']['loop']-1;
if ($this->_sections['g']['show']) {
    $this->_sections['g']['total'] = $this->_sections['g']['loop'];
    if ($this->_sections['g']['total'] == 0)
        $this->_sections['g']['show'] = false;
} else
    $this->_sections['g']['total'] = 0;
if ($this->_sections['g']['show']):

            for ($this->_sections['g']['index'] = $this->_sections['g']['start'], $this->_sections['g']['iteration'] = 1;
                 $this->_sections['g']['iteration'] <= $this->_sections['g']['total'];
                 $this->_sections['g']['index'] += $this->_sections['g']['step'], $this->_sections['g']['iteration']++):
$this->_sections['g']['rownum'] = $this->_sections['g']['iteration'];
$this->_sections['g']['index_prev'] = $this->_sections['g']['index'] - $this->_sections['g']['step'];
$this->_sections['g']['index_next'] = $this->_sections['g']['index'] + $this->_sections['g']['step'];
$this->_sections['g']['first']      = ($this->_sections['g']['iteration'] == 1);
$this->_sections['g']['last']       = ($this->_sections['g']['iteration'] == $this->_sections['g']['total']);
?>
                                                <option value="<?php echo $this->_tpl_vars['group'][$this->_sections['g']['index']]['sysid']; ?>
"><?php echo $this->_tpl_vars['group'][$this->_sections['g']['index']]['ag_groupname']; ?>
</option>
                                        <?php endfor; endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">权限列表</label>
                                    <label>
                                        <span class="text" id="show_per"></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">数据权限</label>
                                    <br/>
                                    <label>
                                        <input type="checkbox" name="all_terrace" id="all_terrace">
                                        <span class="text">所有</span>
                                    </label>
                                    <br/>
                                    <?php $_from = $this->_tpl_vars['gameinfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['v']):
?>
                                        <label>
                                            <input type="checkbox" name="terrace[]" value="<?php echo $this->_tpl_vars['key']; ?>
">
                                            <span class="text"><?php echo $this->_tpl_vars['v']; ?>
</span>
                                        </label>
                                    <?php endforeach; endif; unset($_from); ?>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道权限</label>
                                    <br/>
                                    <label>
                                        <input type="checkbox" name="all_tran" id="all_tran">
                                        <span class="text">所有</span>
                                    </label>
                                    <br/>
                                    <?php $_from = $this->_tpl_vars['traninfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['v']):
?>
                                        <label>
                                            <input type="checkbox" name="tran[]" value="<?php echo $this->_tpl_vars['key']; ?>
">
                                            <span class="text"><?php echo $this->_tpl_vars['v']; ?>
</span>
                                        </label>
                                    <?php endforeach; endif; unset($_from); ?>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">是否锁定</label>
                                    <br/>
                                    <label>
                                        <input class="checkbox-slider toggle yesno" type="checkbox" id='islock' name='islock'>
                                        <span class="text"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addManagerCheck();">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--管理员添加-->

    <?php if ('edit' == $this->_tpl_vars['manager']): ?> <!--修改个人信息-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>个人信息修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">个人信息修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form action="index.php?module=user&method=edit" enctype="multipart/form-data" method="post" id='myform' name='myform'>
                                <input name="act" value="goedit" type="hidden">
                                <input name="uid" value="<?php echo $this->_tpl_vars['uid']; ?>
" type="hidden">
                                <input name="uname" value="<?php echo $this->_tpl_vars['uname']; ?>
" type="hidden">
                                <input name="upload_url" value="<?php echo $this->_tpl_vars['info']; ?>
" type="hidden">

                                <div class="form-group">
                                    <label for="definpu">管理员</label>
                                    <input type="text" class="form-control" disabled='disabled' id="rightid" name="rightid" placeholder="管理员" value='<?php echo $this->_tpl_vars['uname']; ?>
'>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">密码</label>
                                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="密码" value="<?php echo $this->_tpl_vars['data']['a_pwd']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">重复密码</label>
                                    <input type="password" class="form-control" id="repeatpwd" name="repeatpwd" placeholder="重复密码" value="<?php echo $this->_tpl_vars['data']['a_pwd']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">真实姓名</label>
                                    <input type="text" class="form-control" id="truename" name="truename" placeholder="真实姓名" value="<?php echo $this->_tpl_vars['data']['a_truename']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">联系电话</label>
                                    <input type="tel" class="form-control" id="tel" name="tel" placeholder="联系电话" value="<?php echo $this->_tpl_vars['data']['a_tel']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">联系邮箱</label>
                                    <input type="email" class="form-control" id="mail" name="mail" placeholder="联系邮箱" value="<?php echo $this->_tpl_vars['data']['a_email']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">QQ号码</label>
                                    <input type="text" class="form-control" id="qqnum" name="qqnum" placeholder="QQ号码" value="<?php echo $this->_tpl_vars['data']['a_qq']; ?>
">
                                </div>
                                <div class="form-group">
                                    <label for="definpu">用户头像：</label><span style="color:red">*</span>
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
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=user&method=edit&flag=up','myform',0)">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" value='1' name='isadmin' >
                                        <span class="text">超级管理员</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">数据权限</label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"  name="all_terrace" id="all_terrace">
                                        <span class="text" id="all_terrace2">所有</span>
                                    </label>
                                    <br/>
                                    <?php $_from = $this->_tpl_vars['gameinfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['v']):
?>
                                        <label>
                                            <input type="checkbox" name="terrace[]"  value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if (in_array ( $this->_tpl_vars['key'] , $this->_tpl_vars['mygames'] )): ?>checked="checked"<?php endif; ?>>
                                            <span class="text"><?php echo $this->_tpl_vars['v']; ?>
</span>
                                        </label>
                                    <?php endforeach; endif; unset($_from); ?>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">渠道权限</label>
                                    <br/>
                                    <label>
                                        <input type="checkbox" name="all_tran" id="all_tran">
                                        <span class="text">所有</span>
                                    </label>
                                    <br/>
                                    <?php $_from = $this->_tpl_vars['traninfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['v']):
?>
                                        <label>
                                            <input type="checkbox" name="tran[]"  value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if (in_array ( $this->_tpl_vars['key'] , $this->_tpl_vars['mytrans'] )): ?>checked="checked"<?php endif; ?>>
                                            <span class="text"><?php echo $this->_tpl_vars['v']; ?>
</span>
                                        </label>
                                    <?php endforeach; endif; unset($_from); ?>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onClick="return updateManagerCheck('index.php?module=user&method=edit&flag=up','myform',0);">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ('edit_right' == $this->_tpl_vars['manager']): ?><!--修改权限-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>权限修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">权限修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form action="index.php?module=user&method=edit_right" method="post" name="myForm">
                                <input type="hidden" name="acts" value="save_right"/>
                                <input type="hidden" name="uid" value="<?php echo $this->_tpl_vars['id']; ?>
"/>
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
                                <button type="submit" class="btn btn-blue">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <?php endif; ?>

    <?php if ('privileges' == $this->_tpl_vars['manager']): ?> <!--权限组修改-->
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>权限组修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">权限组修改列表</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form action="index.php?module=user&method=updatepri" method="post" name="addmnger">
                                <input name="act" value="update" type="hidden"/>
                                <input name="upuid" value="<?php echo $this->_tpl_vars['uid']; ?>
" type="hidden"/>

                                <div class="form-group">
                                    <label for="definpu">登陆名称</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="logname" name="logname" placeholder="必须为字母或数字组合" value="<?php echo $this->_tpl_vars['userinfo']['a_name']; ?>
" disabled='disabled'>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">真实姓名</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="truename" name="truename" placeholder="真实姓名" value="<?php echo $this->_tpl_vars['userinfo']['a_truename']; ?>
" disabled='disabled'>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">联系电话</label><span style="color:red">*</span>
                                    <input type="tel" class="form-control" id="tel" name="tel" placeholder="11位纯数字格式" value="<?php echo $this->_tpl_vars['userinfo']['a_tel']; ?>
" disabled='disabled'>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">联系邮箱</label><span style="color:red">*</span>
                                    <input type="email" class="form-control" id="mail" name="mail" placeholder="联系邮箱" value="<?php echo $this->_tpl_vars['userinfo']['a_email']; ?>
" disabled='disabled'>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">所在权限组</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="qqnum" name="qqnum" placeholder="所在权限组" value="<?php echo $this->_tpl_vars['userinfo']['ag_groupname']; ?>
" disabled='disabled'>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">当前可执行权限</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="qqnum" name="qqnum" placeholder="当前可执行权限" value="<?php echo $this->_tpl_vars['userinfo']['a_truename']; ?>
" disabled='disabled'>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">用户级别</label>
                                    <label>
                                        <input type="radio" value='1' name='isadmin' <?php if ($this->_tpl_vars['userinfo']['isadmin'] == 1): ?> checked="checked" <?php endif; ?>>
                                        <span class="text">超级管理员</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='isadmin' <?php if ($this->_tpl_vars['userinfo']['isadmin'] == 3): ?> checked="checked" <?php endif; ?>>
                                        <span class="text">团队管理员</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='isadmin' <?php if ($this->_tpl_vars['userinfo']['isadmin'] == 2): ?> checked="checked" <?php endif; ?>>
                                        <span class="text">普通用户</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">当前可执行权限</label>
                                    <label>
                                        <span class="text">
                                            <ul>
                                                <?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['accessright']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
                                                <li style="float: left; width: 150px; height: 20px;list-style:none"><?php echo $this->_sections['r']['index']+1; ?>
.<?php echo $this->_tpl_vars['accessright'][$this->_sections['r']['index']]['rightname']; ?>
</li>
                                                <?php endfor; endif; ?>
                                            </ul>
                                        </span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">分配权限组</label><span style="color:red">*</span>
                                    <select id="groupid" name="groupid" id='groupid' style="width:100%;" onChange="getrights(this.value);">
                                        <option value="0" >不分权限组</option>
                                        <?php unset($this->_sections['g']);
$this->_sections['g']['name'] = 'g';
$this->_sections['g']['loop'] = is_array($_loop=$this->_tpl_vars['group']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['g']['show'] = true;
$this->_sections['g']['max'] = $this->_sections['g']['loop'];
$this->_sections['g']['step'] = 1;
$this->_sections['g']['start'] = $this->_sections['g']['step'] > 0 ? 0 : $this->_sections['g']['loop']-1;
if ($this->_sections['g']['show']) {
    $this->_sections['g']['total'] = $this->_sections['g']['loop'];
    if ($this->_sections['g']['total'] == 0)
        $this->_sections['g']['show'] = false;
} else
    $this->_sections['g']['total'] = 0;
if ($this->_sections['g']['show']):

            for ($this->_sections['g']['index'] = $this->_sections['g']['start'], $this->_sections['g']['iteration'] = 1;
                 $this->_sections['g']['iteration'] <= $this->_sections['g']['total'];
                 $this->_sections['g']['index'] += $this->_sections['g']['step'], $this->_sections['g']['iteration']++):
$this->_sections['g']['rownum'] = $this->_sections['g']['iteration'];
$this->_sections['g']['index_prev'] = $this->_sections['g']['index'] - $this->_sections['g']['step'];
$this->_sections['g']['index_next'] = $this->_sections['g']['index'] + $this->_sections['g']['step'];
$this->_sections['g']['first']      = ($this->_sections['g']['iteration'] == 1);
$this->_sections['g']['last']       = ($this->_sections['g']['iteration'] == $this->_sections['g']['total']);
?>
                                                <option value="<?php echo $this->_tpl_vars['group'][$this->_sections['g']['index']]['sysid']; ?>
"><?php echo $this->_tpl_vars['group'][$this->_sections['g']['index']]['ag_groupname']; ?>
</option>
                                        <?php endfor; endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="definpu">权限列表</label>
                                    <label>
                                        <span class="text" id="show_per"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>  <!--管理员添加-->

    <script type="text/javascript">
    /*提交方法*/
    function formaction(url,id,name){
        if(name==0){
            document.myform.action = url;
            $("#"+id).submit();
        }else{
            document.myform1.action = url;
            $("#"+id).submit();
        }
    }
    /*权限检查，返回出错的信息*/
     function checkRight(){ 
        var rightid = $("#rightid").val();
        var rightname = $("#rightname").val();
        var righturl = $("#righturl").val();
        var pid = $("#pid").val();
        $("#ppid").val(pid);
        if(''==rightid || undefined==rightid){
            alert("权限编号不能为空");
            $("#add_show2").show();

            return false;
        }
        if(''==rightname || undefined==rightname){
            alert("权限名称不能为空");
            return false;
        }
        var ismenu = $('#ismenu').is(':checked');
        if(ismenu && ''==righturl || undefined==righturl){
            alert("权限URL不能为空");
            return false;
        }
        return true;
    }
    function getParent(val){
            var id = $("#id").val();
            $.ajax({
            type:'POST',
            url:"index.php?module=rights&method=add&function=getParent",
            data:'pid='+val+'&id='+id,
            success:function(returnValue){
                $('#pmenu').html(returnValue);
            }
        });
    }
    $(document).ready(function(){
        $("#all_terrace").click(function(){
            var res = $(this).is(':checked');
            // 选择所有 或者 取消所有
            if(res){
                // 选择所有
                $("[name='terrace[]']").attr("checked", "true");
            }else{
                // 取消选择所有
                $("[name='terrace[]']").removeAttr("checked");
            }
            $("#child_wrap").hide();
            $("#child").html("");
        });

        $("#all_tran").click(function(){
            var res = $(this).is(':checked');
            // 选择所有 或者 取消所有
            if(res){
                // 选择所有
                $("[name='tran[]']").attr("checked", "true");
            }else{
                // 取消选择所有
                $("[name='tran[]']").removeAttr("checked");
            }
            $("#child_wrap").hide();
            $("#child").html("");
        });
    });
    function getrights(val){
        $.ajax({
            type:'POST',
            url:"index.php?module=user&method=adduser&function=getrights",
            data:'groupid=' + val,
            success:function(returnValue){
                $('#show_per').html(returnValue);
            }
        });
    }
    function updateManagerCheck(){
        pwd = document.getElementsByName("pwd")[0].value;
        repeatpwd = document.getElementsByName("repeatpwd")[0].value;
        truename = document.getElementsByName("truename")[0].value;
        tel = document.getElementsByName("tel")[0].value;
        mail = document.getElementsByName("mail")[0].value;
        qq = document.getElementsByName("qqnum")[0].value;
        if(''!=pwd){
            if(pwd!=repeatpwd){
                alert("两次输入的密码不一样");
                return false;           
            }
        }
        if(''!=tel){
             if(tel.length!=11){
                 alert("电话格式不对");
                 return false;
             }
             if(!isNum(tel)){
                 alert("电话格式不对");
                 return false;
             }
         }
         if(''!=qq){
             if(!isNum(qq)){
                 alert("qq号码格式不对");
                 return false;
             }
         }
         if(''!=mail){
             ismail=isEmail(mail);
             if(!ismail){
                 alert("电子邮件格式不对");
                 return false;
             }
         }
        formaction(url,id,name);
    }
    /**
    * 检查个人信息
    * @index 索引
    * @neworold 新添加用户 或者修改用户信息
    */
    function addManagerCheck(){
            logname = document.getElementsByName("logname")[0].value;
            groups=document.getElementById('groupid').options[document.getElementById('groupid').selectedIndex].value;
            pwd = document.getElementsByName("pwd")[0].value;
            repeatpwd = document.getElementsByName("repeatpwd")[0].value;
            truename = document.getElementsByName("truename")[0].value;
            tel = document.getElementsByName("tel")[0].value;
            mail = document.getElementsByName("mail")[0].value;
            serverid = document.getElementById("serverid");
            objs=document.getElementsByName('terrace[]');
            isSel=false;//判断是否有选中项，默认为无
            if(''!=logname || undefined!=logname){
                if(/[\u4e00-\u9fa5]/.test(logname)){
                    alert("登录名不能为中文");
                    return false;
                }
            }
            if(''==logname){
                alert("登录名不能为空");
                return false;
            }
            if(''!=pwd || undefined!=pwd){
                if(''!=repeatpwd || undefined!=repeatpwd){
                    if(pwd!=repeatpwd){
                            alert("两次输入的密码不一样！");
                            return false;
                    }
                }
            }
            if(''==truename || undefined==truename){
                alert("真实姓名不能为空");
                return false;
            }
            if(''==tel || undefined==tel){
                alert("电话格式，不对11为纯数字")
                return false;
            }
            if(''!=tel){
                if(tel.length!=11){
                        alert("电话格式不对，11为纯数字");
                        return false;
                }
                if(!isNum(tel)){
                        alert("电话格式不对，11为纯数字");
                        return false;
                }
            }
            if(''==mail || undefined==mail){
                alert("电子邮件不能为空");
                return false;
            }
            if(''!=mail){
                ismail=isEmail(mail);
                if(!ismail){
                        alert("电子邮件格式不对");
                        return false;
                }
            }
            if(''==groups || undefined==groups || 0==groups){
                alert("所属分组不能为空");
                return false;
            }
            
            for(var i=0;i<objs.length;i++)
            {
                if(objs[i].checked==true)
                {
                    isSel=true;
                    break;
                }
            };
            if(isSel==false)
            {
                alert("请选择对应的数据权限！"); 
                return false;
            }
            return true;
    }

    /**
    * 检查是否为电子邮件
    * @return true：电子邮件，false:不是电子邮件;
    */
    function isEmail(str) {
        var re = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        return re.test(str);
    }
    /**
    * 检查是否为数字
    * @return true：数字，false:不是数字;
    */
    function isNum(str) {
        var re = /^[\d]+$/;
        return re.test(str);
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