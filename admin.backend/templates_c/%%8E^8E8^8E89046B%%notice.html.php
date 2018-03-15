<?php /* Smarty version 2.6.20, created on 2018-03-03 09:52:26
         compiled from notice.html */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- Head -->
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
                        <span class="widget-caption"><?php echo $this->_tpl_vars['title']; ?>
</span>
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
                    <?php if ('nlist' == $this->_tpl_vars['active']): ?><!--列表-->
                    <div class="widget-body no-padding">
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='notice'/>
                                <input type='hidden' name="method" value='nlist'/>
                                <div style="margin-left:10px;float:left;">生效时间：<input type="text" value="<?php echo $this->_tpl_vars['starttime2']; ?>
" style="width:200px" class="form-control date-picker" name="starttime2" id="starttime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">失效时间：<input type="text" value="<?php echo $this->_tpl_vars['endtime2']; ?>
" style="width:200px;" class="form-control date-picker" name="endtime2" id="endtime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">请选择游戏：
                                <select name="gid" id='gid' style="width:100%;" style="width:200px;">
                                    <option value="0">请选择游戏</option>
                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                </select>
                                </div>
                                <div style="margin-left:10px;float:left;">主广告选择：
                                    <div class="form-group">
                                        <select name="aid" id='aid' style="width:100%;" onchange="select_adsons()">
                                            <option value="0">主广告选择</option>
                                                <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">发布状态：
                                    <div class="form-group">
                                        <select name="status" id='status' style="width:100%;">
                                            <option value="0">请选择</option>
                                            <option value="1" <?php if ($this->_tpl_vars['status'] == 1): ?>selected='selected'<?php endif; ?>>未发布</option>
                                            <option value="2" <?php if ($this->_tpl_vars['status'] == 2): ?>selected='selected'<?php endif; ?>>已发布</option>
                                            <option value="3" <?php if ($this->_tpl_vars['status'] == 3): ?>selected='selected'<?php endif; ?>>已下架</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo2' class="btn btn-blue" value="查询" /></div>
                                </thead>
                        </table>
                        </form>
                        <hr style="clear:both;border-bottom:3px solid red;margin-top:10px"/>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >系统ID</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >公告标题</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >生效日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >失效日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >公告状态</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >操作</th>
                        </tr>
                        <?php $_from = $this->_tpl_vars['arrs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
                            <tr id="odd">
                                <td><?php echo $this->_tpl_vars['v']['sysid']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gn_uaid']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gn_gid']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gn_title']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gn_startdate']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gn_enddate']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['gn_status']; ?>
</td>
                                <td><?php echo $this->_tpl_vars['v']['edit']; ?>
<?php echo $this->_tpl_vars['v']['del']; ?>
</td>
                            </tr>
                        <?php endforeach; endif; unset($_from); ?>
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
                    <?php endif; ?> 
                    <?php if ('nadd' == $this->_tpl_vars['active']): ?>
                     <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=notice&method=nadd" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <input type='hidden' name="module" value='notice'/>
                                <input type='hidden' name="method" value='nadd'/>
                                <div class="form-group"><span class="btn btn-success">常规信息</span></div>
                                <div class="form-group">
                                    <div class="row">
                                       <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">公告标题：<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="" value="<?php echo $this->_tpl_vars['data']['gn_title']; ?>
" onblur="clean_error('title')"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">游戏选择：</label>
                                                <select name="gid" id='gid' style="width:100%;" onchange="getgames()">
                                                    <option value='0'>请选择游戏</option>
                                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">主广告选择：</label>
                                                <select name="aid" id='aid' style="width:100%;">
                                                        <option value="0">主广告选择</option>
                                                    <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="definpu" style="color: #53a93f;font-size: 14px;">文章内容：</label>
                                    <script type="text/plain" id="myEditor" name="contents" style="width: 1100px;height:350px;"><?php echo $this->_tpl_vars['data']['gn_contents']; ?>
</script>
                                </div>
                                <div class="form-group"><span class="btn btn-success">高级参数</span></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">排序号：</label>
                                            <input type="text" class="form-control" id="order" name="order" placeholder="默认为0" value="<?php echo $this->_tpl_vars['data']['gn_sort']; ?>
"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">生效时间：</label><span style="color:red">*</span>
                                            <input type="text" value="<?php echo $this->_tpl_vars['data']['gn_startdate']; ?>
" class="form-control date-picker" name="starttime" id="starttime" data-date-format="yyyy-mm-dd">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">失效时间：</label>
                                            <input type="text" value="<?php echo $this->_tpl_vars['data']['gn_enddate']; ?>
" class="form-control date-picker" name="endtime" id="endtime" data-date-format="yyyy-mm-dd">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">状态：</label>
                                            <label>
                                                <input type="radio" value='1' name='status' id='status' <?php if ($this->_tpl_vars['data']['gn_status'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">草稿</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='status' id='status' <?php if ($this->_tpl_vars['data']['gn_status'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">发布</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='3' name='status' id='status' <?php if ($this->_tpl_vars['data']['gn_status'] == 3): ?>checked="true"<?php endif; ?>>
                                                <span class="text">下架</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=notice&method=nadd','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php if ('nedit' == $this->_tpl_vars['active']): ?>
                     <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=notice&method=nedit" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input type='hidden' name="module" value='notice'/>
                                <input type='hidden' name="method" value='nedit'/>
                                <input type='hidden' name="sysid" value="<?php echo $this->_tpl_vars['sysid']; ?>
"/>
                                <div class="form-group"><span class="btn btn-success">常规信息</span></div>
                                <div class="form-group">
                                    <div class="row">
                                       <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">公告标题：<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="" value="<?php echo $this->_tpl_vars['data']['gn_title']; ?>
" onblur="clean_error('title')"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">游戏选择：</label>
                                                <select name="gid" id='gid' style="width:100%;" onchange="getgames()">
                                                    <option value='0'>请选择游戏</option>
                                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div>
                                                <label for="definpu">主广告选择：</label>
                                                <select name="aid" id='aid' style="width:100%;">
                                                    <option value="0">主广告选择</option>
                                                    <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['data']['gn_uaid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="definpu" style="color: #53a93f;font-size: 14px;">文章内容：</label>
                                    <script type="text/plain" id="myEditor" name="contents" style="width: 1100px;height:350px;"><?php echo $this->_tpl_vars['data']['gn_contents']; ?>
</script>
                                </div>
                                <div class="form-group"><span class="btn btn-success">高级参数</span></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">排序号：</label>
                                            <input type="text" class="form-control" id="order" name="order" placeholder="默认为0" value="<?php echo $this->_tpl_vars['data']['gn_sort']; ?>
"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">生效时间：</label><span style="color:red">*</span>
                                            <input type="text" value="<?php echo $this->_tpl_vars['data']['gn_startdate']; ?>
" class="form-control date-picker" name="starttime" id="starttime" data-date-format="yyyy-mm-dd">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="definpu">失效时间：</label>
                                            <input type="text" value="<?php echo $this->_tpl_vars['data']['gn_enddate']; ?>
" class="form-control date-picker" name="endtime" id="endtime" data-date-format="yyyy-mm-dd">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="definpu">状态：</label>
                                            <label>
                                                <input type="radio" value='1' name='status' id='status' <?php if ($this->_tpl_vars['data']['gn_status'] == 1): ?>checked="true"<?php endif; ?>>
                                                <span class="text">草稿</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='2' name='status' id='status' <?php if ($this->_tpl_vars['data']['gn_status'] == 2): ?>checked="true"<?php endif; ?>>
                                                <span class="text">发布</span>
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" value='3' name='status' id='status' <?php if ($this->_tpl_vars['data']['gn_status'] == 3): ?>checked="true"<?php endif; ?>>
                                                <span class="text">下架</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=notice&method=nedit','myform',0);">修 改</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    <?php endif; ?>
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