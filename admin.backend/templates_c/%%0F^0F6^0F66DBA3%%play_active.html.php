<?php /* Smarty version 2.6.20, created on 2018-03-03 10:19:24
         compiled from play_active.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'play_active.html', 279, false),)), $this); ?>

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
                    <?php if ('active' == $this->_tpl_vars['active']): ?><!--活跃列表-->
                    <div class="widget-body no-padding">
                     <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>新增玩家：</strong>实时统计当日新增玩家账号数量<br/>
                                <strong>付费玩家：</strong>统计所选时期内，每日成功充值的玩家数量，去重<br>
                                <strong>老玩家：</strong>实时统计当日有登陆过游戏的老玩家的账号数量<br/>
                                <strong>DAU（日活跃玩家数量）：</strong>统计所选时期内，每日成功登录游戏的玩家数量<br/>
                                <strong>WAU（周活跃玩家数量）：</strong>统计所选时期内，当日往前推7日（当日计入天数）期间内，登陆过游戏的玩家总数量，按照玩家ID排重<br/>
                                <strong>MAU（月活跃玩家数量）：</strong>统计所选时期内，当日往前推30日（当日计入天数）期间内，登陆过游戏的玩家总数量，按照玩家ID排重<br/>
                                <strong>有效DAU：</strong>统计所选时期内，每日至少登录过3次游戏的玩家（新增玩家/活跃玩家）数量<br>
                                <strong>DAU/MAU：</strong>统计所选时期内，当日活跃玩家数量与当月活跃玩家数量的比例。此比例越趋近于1，说明游戏玩家的活跃度越高<br></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                    <form action="index.php" name="form1" id="form1" style="margin:0px;"onsubmit="return check_s();">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='play_active'/>
                                <input type='hidden' name="method" value='active'/>
                                <div style="margin-left:10px;float:left;">开始时间：<input type="text" value="<?php echo $this->_tpl_vars['starttime2']; ?>
" style="width:200px" class="form-control date-picker" name="starttime2" id="starttime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="<?php echo $this->_tpl_vars['endtime2']; ?>
" style="width:200px;" class="form-control date-picker" name="endtime2" id="endtime2" data-date-format="yyyy-mm-dd"></div>
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
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo' class="btn btn-blue" value="查询" /></div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' id='export' class="btn btn-blue" value="导出" onclick="btn_export()"/></div>
                                 </thead>
                        </table>
                        </form>
                        <hr style="clear:both;margin-top:10px;"/>
                        <div style="margin-left:10px;float:left;">更新时间：<input type="text" value="" style="width:200px" class="form-control date-picker update_stime" name="starttime" id="starttime" data-date-format="yyyy-mm-dd"></div>
                                   <?php if ($this->_tpl_vars['isadmin'] == 1): ?> <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="" style="width:200px;" class="form-control date-picker update_etime" name="endtime" id="endtime" data-date-format="yyyy-mm-dd"></div><?php endif; ?>
                                    <div style="margin-left:10px;float:left;">更新类型选择：
                                    <div class="form-group">
                                        <select name="forceupdate" id='forceupdate' style="width:100%;">
                                            <option value="1" selected='selected'>普通更新</option>
                                            <?php if ($this->_tpl_vars['isadmin'] == 1): ?><option value="2" >强制更新</option><?php endif; ?>
                                        </select>
                                    </div>
                                    </div>
                                    <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' class="btn btn-blue" value="更新数据"  onclick="update_url();"/></div>
                        <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                                <div id="main" style="width: 1600px;height:400px;"></div>
                                <script type="text/javascript">
                                // 基于准备好的dom，初始化echarts实例
                                var myChart = echarts.init(document.getElementById('main'));
                                // 指定图表的配置项和数据
                                var option = {
                                  tooltip : {
                                        trigger: 'axis'
                                    },
                                    legend: {
                                        data:['新增玩家','付费玩家','非付费玩家','DAU','有效DAU','老玩家']
                                    },
                                    toolbox: {
                                        show : true,
                                        feature : {
                                            mark : {show: true},
                                            dataView : {show: true, readOnly: false},
                                            magicType : {show: true, type: ['line', 'bar', 'stack','tiled']},
                                            restore : {show: true},
                                            saveAsImage : {show: true}
                                        }
                                    },
                                    calculable : true,
                                    xAxis : [
                                    ],
                                    yAxis : [
                                        {
                                            type : 'value'
                                        }
                                    ],
                                    series : [
                                    ]
                                };
                                var view  = <?php echo $this->_tpl_vars['view']; ?>
;
                                var category   = <?php echo $this->_tpl_vars['category']; ?>
;
                                var sym = new Array("emptyCircle","diamond","rectangle","square","triangle","circle","emptyDiamond","emptyRectangle","emptyTriangle");
                                //图表数据
                                var bol = 0;
                                for(var key in view){
                                    var databand = [];
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'line',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                //X轴数据展示
                                var xdata = [];
                                for(var key in category){
                                    xdata.push(category[key]);
                                }
                                var axis = {type:'category',boundaryGap:'false',data:xdata}
                                option.xAxis.push(axis);
                                // 使用刚指定的配置项和数据显示图表。
                                myChart.setOption(option);
                            </script>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 80;text-align: center;" >日期</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 80;text-align: center;" >广告渠道</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 100;text-align: center;" >子渠道</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 80;text-align: center;" >游戏</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >新增玩家</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >付费玩家</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >非付费玩家</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >DAU</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >WAU</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >MAU</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >有效DAU</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >老玩家</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 120;text-align: center;" >DAU/MAU</th>
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
                    <?php endif; ?> 
                    <?php if ('newplay' == $this->_tpl_vars['active']): ?><!--新增列表-->
                    <div class="widget-body no-padding">
                    <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>打开设备：</strong>统计所选时期内，每日玩家打开游戏客户端，并运行游戏的可连接网络设备的数量，每台设备只计算一次<br/>
                                <strong>激活设备：</strong>统计所选时期内，每日新增的玩家安装游戏客户端，并运行游戏的可连接网络设备的数量，每台设备只计算一次<br/>
                                <strong>新增设备：</strong>实时统计当日激活的设备数量，如果已安装的游戏激活标识被移除的话，则设备激活不会被去重<br/>
                                <strong>新增注册：</strong>实时统计当日新增玩家账号数量<br/>
                                <strong>玩家转化率：</strong>实时统计当日，新玩家激活游戏后，进行了自动或者手动注册有ID信息或者账户信息的玩家设备数量，单设备中多个帐号只计算一次成功转化<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='play_active'/>
                                <input type='hidden' name="method" value='newplay'/>
                                <input type='hidden' name="actions" value='1'/>
                                <div style="margin-left:10px;float:left;">开始时间：<input type="text" value="<?php echo $this->_tpl_vars['starttime2']; ?>
" style="width:200px" class="form-control date-picker" name="starttime2" id="starttime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="<?php echo $this->_tpl_vars['endtime2']; ?>
" style="width:200px;" class="form-control date-picker" name="endtime2" id="endtime2" data-date-format="yyyy-mm-dd"></div>
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
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo2' class="btn btn-blue" value="查询" /></div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' id='export' class="btn btn-blue" value="导出" onclick="btn_export()" /></div>
                                                           </thead>
                        </table>
                        </form>
                        <hr style="clear:both;margin-top:10px;"/>
                        <div style="margin-left:10px;float:left;">更新时间：<input type="text" value="" style="width:200px" class="form-control date-picker update_stime" name="starttime" id="starttime" data-date-format="yyyy-mm-dd"></div>
                                   <?php if ($this->_tpl_vars['isadmin'] == 1): ?> <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="" style="width:200px;" class="form-control date-picker update_etime" name="endtime" id="endtime" data-date-format="yyyy-mm-dd"></div><?php endif; ?>
                                    <div style="margin-left:10px;float:left;">更新类型选择：
                                    <div class="form-group">
                                        <select name="forceupdate" id='forceupdate' style="width:100%;">
                                            <option value="1" selected='selected'>普通更新</option>
                                            <?php if ($this->_tpl_vars['isadmin'] == 1): ?><option value="2" >强制更新</option><?php endif; ?>
                                        </select>
                                    </div>
                                    </div>
                                    <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' class="btn btn-blue" value="更新数据"  onclick="update_url();"/></div>
                        <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                                <div style="clear:both;margin:10px auto;"><span style="margin-left: 10px; font-size: 16px;">SUM   激活 | 注册 | 设备： <font style="font-size: 16px;font-weight: bold;color: #e25856;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrsum']['s_act'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 | <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrsum']['s_new'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 | <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrsum']['s_dev'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</font> &nbsp;&nbsp;&nbsp;&nbsp; AVG   激活 | 注册 | 设备： <font style="font-size: 16px;font-weight: bold;color: #e25856;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['arrsum']['a_act'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 | <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrsum']['a_new'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 | <?php echo ((is_array($_tmp=@$this->_tpl_vars['arrsum']['a_dev'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</font></span></div>
                                <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                                <div id="main" style="width: 1600px;height:400px;"></div>
                                <script type="text/javascript">
                                // 基于准备好的dom，初始化echarts实例
                                var myChart = echarts.init(document.getElementById('main'));
                                // 指定图表的配置项和数据
                                var option = {
                                  tooltip : {
                                        trigger: 'axis'
                                    },
                                    legend: {
                                        data:['打开设备','激活设备','新增设备','新增注册']
                                    },
                                    toolbox: {
                                        show : true,
                                        feature : {
                                            mark : {show: true},
                                            dataView : {show: true, readOnly: false},
                                            magicType : {show: true, type: ['line', 'bar', 'stack','tiled']},
                                            restore : {show: true},
                                            saveAsImage : {show: true}
                                        }
                                    },
                                    calculable : true,
                                    xAxis : [
                                    ],
                                    yAxis : [
                                        {
                                            type : 'value'
                                        }
                                    ],
                                    series : [
                                    ]
                                };
                                var view  = <?php echo $this->_tpl_vars['view']; ?>
;
                                var category   = <?php echo $this->_tpl_vars['category']; ?>
;
                                var sym = new Array("emptyCircle","diamond","rectangle","square","triangle","circle","emptyDiamond","emptyRectangle","emptyTriangle");
                                //图表数据
                                var bol = 0;
                                for(var key in view){
                                    var databand = [];
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'line',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                //X轴数据展示
                                var xdata = [];
                                for(var key in category){
                                    xdata.push(category[key]);
                                }
                                var axis = {type:'category',boundaryGap:'false',data:xdata}
                                option.xAxis.push(axis);
                                // 使用刚指定的配置项和数据显示图表。
                                myChart.setOption(option);
                            </script>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >子渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >打开设备</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >激活设备</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >新增设备</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >新增注册</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >玩家转换率</th>
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
                    <?php endif; ?> 
                    <?php if ('retained' == $this->_tpl_vars['active']): ?><!--留存列表-->
                    <div class="widget-body no-padding">
                    <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>新增玩家：</strong>实时统计当日新增玩家账号数量<br/>
                                <strong>次日留存率：</strong>统计所选时期内，当日成功登陆游戏的新增玩家中，第二日再次登陆游戏的玩家数量，占当日游戏新增玩家数量的比例<br/>
                                <strong>每日留存率：</strong>统计所选时期内，当日成功登陆游戏的新增玩家中，往后推第3日/7日/14日/30日（当日不计入天数）登陆游戏的玩家数量，占当日游戏新增玩家数量的比例<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='play_active'/>
                                <input type='hidden' name="method" value='retained'/>
                                <div style="margin-left:10px;float:left;">开始时间：<input type="text" value="<?php echo $this->_tpl_vars['starttime2']; ?>
" style="width:200px" class="form-control date-picker" name="starttime2" id="starttime2" data-date-format="yyyy-mm-dd"></div>
                                <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="<?php echo $this->_tpl_vars['endtime2']; ?>
" style="width:200px;" class="form-control date-picker" name="endtime2" id="endtime2" data-date-format="yyyy-mm-dd"></div>
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
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='submit' id='getinfo2' class="btn btn-blue" value="查询" /></div>
                                <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' id='export' class="btn btn-blue" value="导出" onclick="btn_export()"/></div>
                                       </thead>
                        </table>
                        </form>
                        <hr style="clear:both;margin-top:10px;"/>
                        <div style="margin-left:10px;float:left;">更新时间：<input type="text" value="" style="width:200px" class="form-control date-picker update_stime" name="starttime" id="starttime" data-date-format="yyyy-mm-dd"></div>
                                   <?php if ($this->_tpl_vars['isadmin'] == 1): ?> <div style="margin-left:10px;float:left;">结束时间：<input type="text" value="" style="width:200px;" class="form-control date-picker update_etime" name="endtime" id="endtime" data-date-format="yyyy-mm-dd"></div><?php endif; ?>
                                    <div style="margin-left:10px;float:left;">更新类型选择：
                                    <div class="form-group">
                                        <select name="forceupdate" id='forceupdate' style="width:100%;">
                                            <option value="1" selected='selected'>普通更新</option>
                                            <?php if ($this->_tpl_vars['isadmin'] == 1): ?><option value="2" >强制更新</option><?php endif; ?>
                                        </select>
                                    </div>
                                    </div>
                                    <div style="margin-left:10px;float:left;margin-top:20px;"><input type='button' class="btn btn-blue" value="更新数据"  onclick="update_url();"/></div>
                        <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                                <div id="main" style="width: 1600px;height:400px;"></div>
                                <script type="text/javascript">
                                // 基于准备好的dom，初始化echarts实例
                                var myChart = echarts.init(document.getElementById('main'));
                                // 指定图表的配置项和数据
                                var option = {
                                  tooltip : {
                                        trigger: 'axis'
                                    },
                                    legend: {
                                        data:['次日留存','3日留存','7日留存','14日留存','30日留存']
                                    },
                                    toolbox: {
                                        show : true,
                                        feature : {
                                            mark : {show: true},
                                            dataView : {show: true, readOnly: false},
                                            magicType : {show: true, type: ['line', 'bar', 'stack','tiled']},
                                            restore : {show: true},
                                            saveAsImage : {show: true}
                                        }
                                    },
                                    calculable : true,
                                    xAxis : [
                                    ],
                                    yAxis : [
                                        {
                                            type : 'value'
                                        }
                                    ],
                                    series : [
                                    ]
                                };
                                var view  = <?php echo $this->_tpl_vars['view']; ?>
;
                                var category   = <?php echo $this->_tpl_vars['category']; ?>
;
                                var sym = new Array("emptyCircle","diamond","rectangle","square","triangle","circle","emptyDiamond","emptyRectangle","emptyTriangle");
                                //图表数据
                                var bol = 0;
                                for(var key in view){
                                    var databand = [];
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'line',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                //X轴数据展示
                                var xdata = [];
                                for(var key in category){
                                    xdata.push(category[key]);
                                }
                                var axis = {type:'category',boundaryGap:'false',data:xdata}
                                option.xAxis.push(axis);
                                // 使用刚指定的配置项和数据显示图表。
                                myChart.setOption(option);
                            </script>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 206px;text-align: center;" >日期</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >广告渠道</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >子渠道</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >游戏</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;"">新增玩家</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >次日留存</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >3日留存</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >7日留存</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >14日留存</th>
                                    <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 201px;text-align: center;" >30日留存</th>
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
                 $.post("index.php?module=play_active&method=getadson",{tid:tids},
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
             $.post("index.php?module=play_active&method=getadson",{aid: ids},
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
            var date1  = $("#starttime").val();
            var date2  = $("#endtime").val();
            if(date1 =='' || date1==null){
                alert("更新日期不能为空!");
                return false;
            }
            var uptype = $("#forceupdate").find("option:selected").val();
            var updatephp = '<?php echo $this->_tpl_vars['updatephp']; ?>
';    //更新URL
            location.href="updates.php?date1="+date1+"&date2="+date2+"&forceupdate="+uptype+"&updatephp="+updatephp;
        }
        function btn_export(){
            var starttime2  = $("#starttime2").val();
            var endtime2    = $("#endtime2").val();
            var gid         = $('#gid option:selected').val();
            var aid         = $('#aid option:selected').val();
            var adsons      = $('#adsons option:selected').val();
            var tid         = $('#tid option:selected').val();
            var method      = '<?php echo $this->_tpl_vars['active']; ?>
';
            location.href="index.php?module=play_active&method="+method+"&export=1&starttime2="+starttime2+"&endtime2="+endtime2+"&gid="+gid+"&aid="+aid+"&adsons="+adsons+"&tid="+tid;
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
<script src="templates/echarts/dist/echarts.js"></script>
<!--Bootstrap Date Picker-->
<script src="templates/js/datetime/bootstrap-datepicker.js"></script>
<script type="text/javascript">        
    //--Bootstrap Date Picker--
    $('.date-picker').datepicker();
</script>
</html>