<?php /* Smarty version 2.6.20, created on 2018-03-06 15:26:45
         compiled from right.html */ ?>

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
	<?php if ('rightlist' == $this->_tpl_vars['right']): ?>  <!--权限列表-->
	<div class="page-body">
	    <div class="row">
	        <div class="col-xs-12 col-md-12">
	            <div class="widget">
	                <div class="widget-header bordered-bottom bordered-yellow">
	                    <span class="widget-caption">权限列表</span>
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

	                    <table class="table table-bordered table-hover table-striped" id="searchable">
	                        <thead class="bordered-darkorange">
	                        	<tr>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_engine" placeholder="搜索系统ID" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_browser" placeholder="搜索权限编号" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_platform" placeholder="搜索权限名称" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_version" placeholder="搜索是否目录" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索功能名称" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索功能连接" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索排序号(降序)" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加时间" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加人登录名" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="搜索添加人真实名" class="form-control input-sm"></th>
	                                <th rowspan="1" colspan="1"><input type="text" name="search_grade" placeholder="" class="form-control input-sm"></th>
	                            </tr>
	                            <tr role="row">
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
	    	    var oTable = $('#searchable').dataTable({
	    	    "sScrollX": "200",
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
		            { "sTitle": "系统ID" },
		            { "sTitle": "权限编号" },
		            { "sTitle": "权限名称" },
		            { "sTitle": "是否目录" },
		            { "sTitle": "功能名称" },
		            { "sTitle": "功能连接" },
		            { "sTitle": "排序号(降序)" },
		            { "sTitle": "添加时间" },
		            { "sTitle": "添加人登录名" },
		            { "sTitle": "添加人真实名" },
		            { "sTitle": "操作" },
		        ]
			});
			$("thead input").keyup(function () {
			    /* Filter on the column (the index) of this element */
			    oTable.fnFilter(this.value, $("thead input").index(this));
			}); 
	    } );

	</script>
	<?php endif; ?>  <!--权限列表-->

	<?php if ('addright' == $this->_tpl_vars['right']): ?> <!--添加权限-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
		<h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>权限添加</h5>
		<div class="row">
		    <div class="col-lg-6 col-sm-6 col-xs-12">
		        <div class="widget">
		            <div class="widget-header bordered-top bordered-palegreen">
		                <span class="widget-caption">权限添加表单</span>
		            </div>
		            <div class="widget-body">
		                <div class="collapse in">
		                    <form method="post" action="index.php?module=rights&method=add">
		                    	<input type="hidden" name="act" value="insertright">
								<input name="id"  id="id" value="0" type="hidden"/>

		                        <div class="form-group">
		                            <label for="definpu">权限编号</label>
		                            <span style='color:red'>*</span>
		                            <input type="text" class="form-control" id="rightid" name="rightid" placeholder="权限编号">
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">权限名称</label>
		                            <span style='color:red'>*</span>
		                            <input type="text" class="form-control" id="rightname" name="rightname" placeholder="权限名称">
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">所在目录</label>
		                            <select id="parentid" name="parentid" style="width:100%;" onChange="getParent(this.value);">
	                            		<option value="0" >作为目录</option>
		                                <?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['category']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
									  		<option value="<?php echo $this->_tpl_vars['category'][$this->_sections['c']['index']]['sysid']; ?>
" ><?php echo $this->_tpl_vars['category'][$this->_sections['c']['index']]['ar_rightname']; ?>
</option>
									  	<?php endfor; endif; ?>
		                            </select>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">二级菜单</label>
		                            <div id="pmenu"></div>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">是否作为菜单</label>
		                            <br/>
									<label>
	                                    <input class="checkbox-slider toggle yesno" type="checkbox" id='ismenu' name='ismenu'>
	                                    <span class="text"></span>
	                                </label>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">功能名称</label>
		                            <textarea class="form-control" rows="3" placeholder="功能名称" id='righttit' name='righttit'></textarea>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">功能连接</label>
		                            <textarea class="form-control" rows="3" placeholder="连接地址" id='righturl' name='righturl'></textarea>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">排序号(降序)</label>
		                            <input type="text" class="form-control" id="order" name='order' placeholder="排序数">
		                        </div>
		                        <button type="submit" class="btn btn-blue" onClick="return checkRight();">提 交</button>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ('changeright' == $this->_tpl_vars['right']): ?> <!--修改权限-->
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
		<h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>权限添加</h5>
		<div class="row">
		    <div class="col-lg-6 col-sm-6 col-xs-12">
		        <div class="widget">
		            <div class="widget-header bordered-top bordered-palegreen">
		                <span class="widget-caption">权限添加表单</span>
		            </div>
		            <div class="widget-body">
		                <div class="collapse in">
		                    <form method="post" action="index.php?module=rights&method=edit">
		                    <input name="act" value="updateright" type="hidden">
							<input name="id"  id="id" value="<?php echo $this->_tpl_vars['id']; ?>
" type="hidden"/>

		                        <div class="form-group">
		                            <label for="definpu">权限编号</label>
		                            <span style='color:red'>*</span>
		                            <input type="text" class="form-control" id="rightid" name="rightid" placeholder="权限编号" value='<?php echo $this->_tpl_vars['changeyesmenu']['ar_rightid']; ?>
'>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">权限名称</label>
		                            <span style='color:red'>*</span>
		                            <input type="text" class="form-control" id="rightname" name="rightname" placeholder="权限名称" value='<?php echo $this->_tpl_vars['changeyesmenu']['ar_rightname']; ?>
'>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">所在目录</label>
		                            <select id="parentid" name="parentid" style="width:100%;" onChange="getParent(this.value);">
	                            		<option value="0" >作为目录</option>
										<?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['category']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
											<option value="<?php echo $this->_tpl_vars['category'][$this->_sections['c']['index']]['sysid']; ?>
" <?php if (in_array ( $this->_tpl_vars['changeyesmenu']['ar_parentid'] , $this->_tpl_vars['category'][$this->_sections['c']['index']] )): ?>selected="selected"<?php endif; ?> ><?php echo $this->_tpl_vars['category'][$this->_sections['c']['index']]['ar_rightname']; ?>
</option>
										<?php endfor; endif; ?>
		                            </select>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">二级菜单</label>
		                            <div id="pmenu"><?php echo $this->_tpl_vars['str']; ?>
</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">是否作为菜单</label>
		                            <br/>
									<label>
	                                    <input class="checkbox-slider toggle yesno" type="checkbox" id='ismenu' name='ismenu' <?php if ($this->_tpl_vars['changeyesmenu']['ar_ismenu'] == 1): ?>checked="checked"<?php endif; ?>>
	                                    <span class="text"></span>
	                                </label>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">功能名称</label>
		                            <textarea class="form-control" rows="3" placeholder="功能名称" id='righttit' name='righttit'><?php echo $this->_tpl_vars['changeyesmenu']['ar_title']; ?>
</textarea>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">功能连接</label>
		                            <textarea class="form-control" rows="3" placeholder="连接地址" id='righturl' name='righturl'><?php echo $this->_tpl_vars['changeyesmenu']['ar_url']; ?>
</textarea>
		                        </div>
		                        <div class="form-group">
		                            <label for="definpu">排序号(降序)</label>
		                            <input type="text" class="form-control" id="order" name='order' placeholder="排序数" value="<?php echo $this->_tpl_vars['changeyesmenu']['ar_order']; ?>
">
		                        </div>
		                       	<div class="form-group">
		                            <label for="definpu">是否锁定</label>
		                            <br/>
									<label>
	                                    <input class="checkbox-slider toggle yesno" type="checkbox" id='islock' name='islock' <?php if ($this->_tpl_vars['changeyesmenu']['ar_islock'] == 1): ?>checked="checked"<?php endif; ?>>
	                                    <span class="text"></span>
	                                </label>
		                        </div>
		                        <button type="submit" class="btn btn-blue" onClick="return checkRight();">修 改</button>
		                        <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<?php endif; ?>

	<script type="text/javascript">
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