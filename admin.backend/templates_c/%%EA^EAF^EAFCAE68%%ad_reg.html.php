<?php /* Smarty version 2.6.20, created on 2018-02-09 16:22:16
         compiled from ad_reg.html */ ?>

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
    <?php if ('list' == $this->_tpl_vars['list']): ?><!--列表-->
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
                        <span class="widget-caption">第三方注册用户</span>
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
                                <input type='hidden' name="module" value='ad_reg'/>
                                <input type='hidden' name="method" value='reglist'/>
                                <div style="margin-left:10px;float:left;">开始时间：<input type="text" value="<?php echo $this->_tpl_vars['starttime2']; ?>
" style="width:100px" class="form-control date-picker" name="starttime2" id="starttime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="<?php echo $this->_tpl_vars['endtime2']; ?>
" style="width:100px;" class="form-control date-picker" name="endtime2" id="endtime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">请选择游戏：
                                <select name="gid" id='gid' style="width:100%;" style="width:200px;">
                                    <option value="0">请选择游戏</option>
                                    <?php echo $this->_tpl_vars['gamestr']; ?>

                                </select>
                                </div>
                                <div style="margin-left:10px;float:left;">渠道选择：
                                    <div class="form-group">
                                        <select name="tid" id='tid' style="width:100%;" onchange="select_ad()">
                                            <option value="0" <?php if ($this->_tpl_vars['tid'] == 0): ?>selected='selected'<?php endif; ?>>渠道选择</option>
                                            <option value="1" <?php if ($this->_tpl_vars['tid'] == 1): ?>selected='selected'<?php endif; ?>>安卓</option>
                                            <option value="2" <?php if ($this->_tpl_vars['tid'] == 2): ?>selected='selected'<?php endif; ?>>ios</option>
                                            <option value="3" <?php if ($this->_tpl_vars['tid'] == 3): ?>selected='selected'<?php endif; ?>>ios越狱</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">主广告选择：
                                    <div class="form-group">
                                        <select name="aid" id='aid' style="width:100%;" onchange="select_adsons()">
                                            <option value="0">主广告选择</option>
                                            <?php if ($this->_tpl_vars['tps'] == 1): ?>
                                                <?php echo $this->_tpl_vars['adstr']; ?>

                                            <?php else: ?>
                                                <?php $_from = $this->_tpl_vars['gp_aids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['aid'] == $this->_tpl_vars['k']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-left:10px;float:left;">子广告选择：
                                    <div class="form-group">
                                        <select name="adsons" id='adsons' style="width:100%;">
                                            <option value="0">子广告选择</option>
                                            <?php echo $this->_tpl_vars['wdstr']; ?>

                                        </select>
                                    </div>
                                </div>

                                <div style="margin-left:10px;float:left;">账号ID：
                                    <div class="form-group">
                                      <input type="text" class="form-control" id="uid" name="uid" placeholder="" value="<?php echo $this->_tpl_vars['uid']; ?>
"/>
                                    </div>
                                </div>

                                <div style="margin-left:10px;float:left;">账号：
                                    <div class="form-group">
                                      <input type="text" class="form-control" id="uname" name="uname" placeholder="" value="<?php echo $this->_tpl_vars['uname']; ?>
"/>
                                    </div>
                                </div>

                                <div style="margin-left:10px;float:left;">设备号：
                                    <div class="form-group">
                                      <input type="text" class="form-control" style="width:300px" id="dnumber" name="dnumber" placeholder="" value="<?php echo $this->_tpl_vars['dnumber']; ?>
"/>
                                    </div>
                                </div>


                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo2' class="btn btn-blue" value="查询" /></div>
                                <hr style="clear:both;margin-top:70px;"/>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >注册时间</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 100px;text-align: center;" >子渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 80px;text-align: center;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >账号</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 120px;text-align: center;" >账号ID</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable"  style="width: 180px;text-align: center;" >设备号</th>
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
        function select_ad(){
            //清空数据
            $("#aid").find("option").remove();
            $("#adsons").find("option").remove();
            $("#adsons").append('<option value="0">子广告选择</option>');
            $("#aid").append('<option value="0">广告选择</option>');
            //获取子分类数据
            var tids = $("#tid").val();
            if(tids!=0){
                 $.post("index.php?module=ad_reg&method=getadson",{tid:tids},
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
             $.post("index.php?module=ad_reg&method=getadson",{aid: ids},
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
    <?php endif; ?>  
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
        function update_url(){
            var stime = (new Date($(".update_stime").val())).getTime();
            var etime = (new Date($(".update_etime").val())).getTime();
            //判断开始时间不能小于结束时间
            if(etime-stime<0){
                alert('结束时间需大于开始时间！');
                return;
            }
            stime = $(".update_stime").val();
            etime = $(".update_etime").val();
            location.href="./update/update_game_active.php?date1="+stime+"&date2="+etime;
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
<script src="templates/js/datetime/bootstrap-datepicker.js"></script>
<script type="text/javascript">        
    //--Bootstrap Date Picker--
    $('.date-picker').datepicker();
</script>
</html>