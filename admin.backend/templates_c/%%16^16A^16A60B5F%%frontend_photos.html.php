<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:29
         compiled from frontend_photos.html */ ?>
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

	<?php if ($this->_tpl_vars['type'] == 'list'): ?><!--图片列表-->
	<style> ul{ list-style: none; } </style>
    <div class="page-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="widget">
                    <div class="widget-header bordered-bottom bordered-yellow">
                        <span class="widget-caption">图片列表</span>
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
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索图片" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索图片" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索所属分类" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索图片标题" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索跳转地址" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索排序号" class="form-control input-sm"></th>
                                    <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索状态" class="form-control input-sm"></th>
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
                "iDisplayLength": 5,
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
                    { "sTitle": "小图" },
                    { "sTitle": "大图" },
                    { "sTitle": "所属分类" },
                    { "sTitle": "图片标题" },
                    { "sTitle": "跳转地址"},
                    { "sTitle": "排序号"},
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
	
	<?php if ($this->_tpl_vars['type'] == 'add'): ?><!--添加图片-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>图片添加</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">图片添加表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_photos&method=add_photos" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <input id="url" name="url" value="<?php if ($this->_tpl_vars['imgArr']['bphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['bphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_url']; ?>
<?php endif; ?>" type="hidden"/>
                                <input id="sphoto" name="sphoto" value="<?php if ($this->_tpl_vars['imgArr']['sphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['sphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_sphoto']; ?>
<?php endif; ?>" type="hidden"/>
                                 <input id="qrphoto" name="qrphoto" value="<?php if ($this->_tpl_vars['imgArr']['qrphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['qrphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_sphoto']; ?>
<?php endif; ?>" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">图片分类：</label><span style="color:red">*</span>
                                    <select name="typeid" id='typeid' style="width:100%;">
                                        <?php $_from = $this->_tpl_vars['typeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['fp_typeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option><hr/>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">图片标题：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="例如：倚天游戏截图" value="<?php echo $this->_tpl_vars['data']['fp_title']; ?>
" onblur="clean_error('title')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">跳转地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="jurl" name="jurl" placeholder="例如：http://www.hlwy.com(还没有，请填 # )" value="<?php echo $this->_tpl_vars['data']['fp_jurl']; ?>
" onblur="clean_error('jurl')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">图片描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['fp_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">小图：</label><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['sphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['sphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_sphoto']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="sphoto" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_sphoto']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_sphoto']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_photos&method=add_photos&flag=up&type=sphoto','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">大图：</label><span style="color:red">*</span><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['bphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['bphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_url']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="bphoto" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_bphoto']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_bphoto']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_photos&method=add_photos&flag=up&type=bphoto','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序号：</label>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="默认为 0 " value="<?php echo $this->_tpl_vars['data']['fp_order']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">状态：</label>
                                    <label>
                                        <input type="radio" value='1' name='status' id='status' <?php if ($this->_tpl_vars['data']['fp_status'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">草稿</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='status' id='status' <?php if ($this->_tpl_vars['data']['fp_status'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">发布</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='status' id='status' <?php if ($this->_tpl_vars['data']['fp_status'] == 3): ?>checked="true"<?php endif; ?>>
                                        <span class="text">下架</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_photos&method=add_photos','myform',0);">添 加</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['type'] == 'edit'): ?><!--修改图片-->
		<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>图片修改</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">图片修改表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_photos&method=edit_photos" method="post" id='myform' name='myform'>
                                <input name="act" value="edit" type="hidden"/>
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <input id="url" name="url" value="<?php if ($this->_tpl_vars['imgArr']['bphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['bphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_url']; ?>
<?php endif; ?>" type="hidden"/>
                                <input id="sphoto" name="sphoto" value="<?php if ($this->_tpl_vars['imgArr']['sphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['sphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_sphoto']; ?>
<?php endif; ?>" type="hidden"/>
                                 <input id="qrphoto" name="qrphoto" value="<?php if ($this->_tpl_vars['imgArr']['qrphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['qrphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_sphoto']; ?>
<?php endif; ?>" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">图片分类：</label><span style="color:red">*</span>
                                    <select name="typeid" id='typeid' style="width:100%;">
                                        <?php $_from = $this->_tpl_vars['typeArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
                                        <option value="<?php echo $this->_tpl_vars['sysid']; ?>
" <?php if ($this->_tpl_vars['data']['fp_typeid'] == $this->_tpl_vars['sysid']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option><hr/>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">图片标题：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="例如：倚天游戏截图" value="<?php echo $this->_tpl_vars['data']['fp_title']; ?>
" onblur="clean_error('title')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">跳转地址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="jurl" name="jurl" placeholder="例如：http://www.hlwy.com(还没有，请填 # )" value="<?php echo $this->_tpl_vars['data']['fp_jurl']; ?>
" onblur="clean_error('jurl')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">图片描述：</label>
                                    <textarea class="form-control" rows="3" placeholder="" id='desc' name='desc'><?php echo $this->_tpl_vars['data']['fp_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">小图：</label><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['sphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['sphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_sphoto']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="sphoto" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_sphoto']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_sphoto']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_photos&method=edit_photos&flag=up&type=sphoto','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">大图：</label><span style="color:red">*</span><br/>
                                    <label style="float:left;margin-right:10px">
                                        <img src="<?php if ($this->_tpl_vars['imgArr']['bphoto_url'] != ''): ?><?php echo $this->_tpl_vars['imgArr']['bphoto_url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['fp_url']; ?>
<?php endif; ?>"  width='100px' height='100px'/>
                                    </label>
                                    <lable>
                                        <input name="bphoto" type="file">
                                        <br>
                                        允许上传的文件类型为:<b style='color:red'><?php echo $this->_tpl_vars['img']; ?>
</b>
                                        <br/>
                                        允许上传的文件大小为:<b style='color:red'><?php echo $this->_tpl_vars['size']; ?>
M</b>
                                        <br/><b style='color:red'><?php echo $this->_tpl_vars['imgArr']['error_bphoto']; ?>
</b>
                                        <br/><b style='color:blue'><?php echo $this->_tpl_vars['imgArr']['success_bphoto']; ?>
</b>
                                    </lable>
                                    <input type="submit" value="上传" onclick="formaction('index.php?module=frontend_photos&method=edit_photos&flag=up&type=bphoto','myform',0)"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">排序号：</label>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="默认为 0 " value="<?php echo $this->_tpl_vars['data']['fp_order']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">状态：</label>
                                    <label>
                                        <input type="radio" value='1' name='status' id='status' <?php if ($this->_tpl_vars['data']['fp_status'] == 1): ?>checked="true"<?php endif; ?>>
                                        <span class="text">草稿</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='2' name='status' id='status' <?php if ($this->_tpl_vars['data']['fp_status'] == 2): ?>checked="true"<?php endif; ?>>
                                        <span class="text">发布</span>
                                    </label>
                                    <label>
                                        <input type="radio" value='3' name='status' id='status' <?php if ($this->_tpl_vars['data']['fp_status'] == 3): ?>checked="true"<?php endif; ?>>
                                        <span class="text">下架</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_photos&method=edit_photos','myform',0);">修 改</button>
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
            var jurl = clean_error('jurl');
            var photo = check_upload('url','请上传图片！');
            if(title && jurl && photo){
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
</html>