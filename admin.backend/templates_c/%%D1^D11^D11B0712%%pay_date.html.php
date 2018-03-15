<?php /* Smarty version 2.6.20, created on 2018-02-09 16:10:00
         compiled from pay_date.html */ ?>

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
                    <?php if ('payinfo' == $this->_tpl_vars['active']): ?><!--列表-->
                    <div class="widget-body no-padding">
                    <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>当日付费金额：</strong>统计所选时期内，每日玩家成功充值的金额总值<br/>
                                <strong>当日付费人数：</strong>统计所选时期内，每日玩家成功充值总次数<br/>
                                <strong>当日付费次数：</strong>统计所选时期内，每日玩家成功充值总次数<br/>
                                <strong>首次付费玩家：</strong>统计所选时期内，在当日进行第一次付费的玩家数量以及金额<br/>
                                <strong>首日付费玩家：</strong>统计所选时期内，在首日新增当天进行付费的玩家数量、金额以及次数<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='payinfo'/>
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
                                    legend: [
                                    ],
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
                                var dataleg  = [];
                                for(var key in view){
                                    var databand = [];
                                    dataleg.push(key);
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'line',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                var leg = {data:dataleg};
                                option.legend.push(leg);
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
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 108px;text-align: center;line-height: 70px;" >日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 180px;text-align: center;line-height: 70px;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 180px;text-align: center;line-height: 70px;" >子渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 201px;text-align: center;line-height: 70px;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="3" style="width: 118px;text-align: center;" >活跃玩家</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="3" style="width: 118px;text-align: center;" >首次付费玩家</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="3" style="width: 118px;text-align: center;" >首日付费玩家</th>
                        </tr>
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >当日付费金额</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >当日付费人数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >当日付费次数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >首次付费金额</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >首次付费人数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >首次付费次数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >首日付费金额</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >首日付费人数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;" >首日付费次数</th>
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
                    <?php if ($this->_tpl_vars['active'] == 'top_pay'): ?>
                     <div class="widget-body no-padding">
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='top_pay'/>
                                <input type='hidden' name="action" value="go"/>
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
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:80px"/>
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 108px;text-align: center;" >排名</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 180px;text-align: center;" >账号</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 180px;text-align: center;" >新增日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 201px;text-align: center;" >首充日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >充值总额</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >充值总次数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >活跃天数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >最近登录时间</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >最近充值时间</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >未登录天数</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >未充值天数</th>
                        </tr>
                        <tr id="odd"><?php echo $this->_tpl_vars['str']; ?>
</tr> 
                        </thead>
                        </table>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['active'] == 'today_payinfo'): ?>
                     <div class="widget-body no-padding">
                     <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>注册当日付费跟踪：</strong>统计所选时期内，当日新增玩家中各个时间段的付费人数<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='today_payinfo'/>
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
                                    legend: [
                                    ],
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
                                var dataleg  = [];
                                for(var key in view){
                                    var databand = [];
                                    dataleg.push(key);
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'bar',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                var leg = {data:dataleg};
                                option.legend.push(leg);
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
                        <tr role="row" id="foot">
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 180px;text-align: center;" >日期</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >0时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >1时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >2时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >3时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >4时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >5时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >6时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >7时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >8时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >9时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >10时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >11时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >12时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >13时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >14时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >15时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >16时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >17时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >18时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >19时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >20时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >21时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >22时</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >23时</th>
                        </tr>
                        <tr id="odd"><?php echo $this->_tpl_vars['str']; ?>
</tr> 
                        </thead>
                        </table>
                    </div>
                    <?php endif; ?>
                     <?php if ($this->_tpl_vars['active'] == 'today_paytime'): ?>
                     <div class="widget-body no-padding">
                     <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>注册当日付费跟踪：</strong>统计所选时期内，当日注册多少小时后付费的玩家数量<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='today_paytime'/>
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
                                    legend: [
                                    ],
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
                                var dataleg  = [];
                                for(var key in view){
                                    var databand = [];
                                    dataleg.push(key);
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'bar',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                var leg = {data:dataleg};
                                option.legend.push(leg);
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
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册1小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册2小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册3小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册4小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册5小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册6小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册7小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册8小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册9小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册10小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册11小时</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >注册12小时</th>
                        </tr>
                        <tr id="odd"><?php echo $this->_tpl_vars['str']; ?>
</tr> 
                        </thead>
                        </table>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['active'] == 'pay_conversion'): ?>
                     <div class="widget-body no-padding">
                     <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>首日付费率：</strong>统计所选时期内，当日新增玩家中成功付费玩家的数量占当日新增玩家数量的比例<br/>
                                <strong>首周付费率：</strong>统计所选时期内，当日新增玩家中在当日推后7天（当日计入天数）的时间内有成功充值行为的玩家数量，占当日新增玩家数量的比例<br/>
                                <strong>首月付费率：</strong>统计所选时期内，当日新增玩家中在当日推后30天（当日计入天数）的时间内有成功充值行为的玩家数量，占当日新增玩家数量的比例<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='pay_conversion'/>
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
                                    legend: [
                                    ],
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
                                var dataleg  = [];
                                for(var key in view){
                                    var databand = [];
                                    dataleg.push(key);
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'line',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                var leg = {data:dataleg};
                                option.legend.push(leg);
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
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 108px;text-align: center;" >日期</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >首日付费人数（%）</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >首周付费人数（%）</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 118px;text-align: center;" >首月付费人数（%）</th>
                        </tr>
                        <tr id="odd"><?php echo $this->_tpl_vars['str']; ?>
</tr>
                        </thead> 
                        </table>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['active'] == 'pay_ltv'): ?>
                    <div class="widget-body no-padding">
                    <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>LTV：</strong>指用户在某周期内所付费的总价值。N日LTV=X日新增玩家账号数N日充值总额/X日新增玩家数。例如：1日新增1000个在未来7日共充值1万元，那么LTV7=10000/1000<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='pay_ltv'/>
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
                        <div  style="overflow: auto; width: 100%;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr id='foot' role="row">
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 260px;text-align: center;" >日期</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 200px;text-align: center;" >新增</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >当日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >1日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >2日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >3日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >4日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >5日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >6日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >7日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >8日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >9日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >10日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >11日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >12日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >13日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >14日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >15日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >16日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >17日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >18日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >19日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >20日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >21日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >22日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >23日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >24日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >25日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >26日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >27日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >28日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >29日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >30日</th>
                            <th class="" tabindex="0" aria-controls="searchable" rowspan="2" colspan="1" style="width: 100px;text-align: center;" >31日</th>
                        </tr>
                        <tr role="row" id='test'><?php echo $this->_tpl_vars['str']; ?>
</tr> 
                        </thead>
                        </table>
                    </div>
                    <?php endif; ?>
                    <?php if ('pay_behavior' == $this->_tpl_vars['active']): ?><!--付费行为列表-->
                    <div class="widget-body no-padding">
                    <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>付费金额（等级）：</strong>统计所选时期内，玩家在所处等级进行充值时，充值总金额和等级对应分布情况<br/>
                                <strong>付费金额（次数）：</strong>统计所选时期内，当日新增玩家中在当日推后7天（当日计入天数）的时间内有成功充值行为的玩家数量，占当日新增玩家数量的比例统计所选时期内，玩家在所处等级付费总次数与等级对应分布<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='pay_behavior'/>
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
                                    legend: [
                                    ],
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
                                var dataleg  = [];
                                for(var key in view){
                                    var databand = [];
                                    dataleg.push(key);
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'bar',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                var leg = {data:dataleg};
                                option.legend.push(leg);
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
                            </thead>
                        </table>
                        </form>
                        <table  class="table table-bordered table-hover table-striped table-hover" id="searchable">
                        <thead class="bordered-darkorange">
                        <tr role="row" id="test">
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 100px;text-align: center;" >广告渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 100px;text-align: center;" >子渠道</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 100px;text-align: center;" >游戏</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >付费等级</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >付费金额</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >付费次数</th>
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
                    <?php if ('pay_range' == $this->_tpl_vars['active']): ?><!--付费区间分析-->
                    <div class="widget-body no-padding">
                    <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <div style="float:left;margin:20px 10px;font-size: 12px">
                                <strong>付费金额/次数区间分布：</strong>统计所选日期内，当日/当周/当月在某个金额区间中进行成功充值的玩家数量和次数分布<br/></div>
                                <hr style="clear:both;border-bottom:3px solid red;margin-top:20px"/>
                            </thead>
                        </table>
                        <form action="index.php" name="form1" id="form1" style="margin:0px;">
                        <table class="table table-bordered table-hover table-striped table-hover" id="searchable">
                            <thead class="bordered-darkorange">
                                <br/>
                                <input type='hidden' name="module" value='pay_date'/>
                                <input type='hidden' name="method" value='pay_range'/>
                                <div style="margin-left:10px;float:left;">日期：<input type="text" value="<?php echo $this->_tpl_vars['starttime2']; ?>
" style="width:200px" class="form-control date-picker" name="starttime2" id="starttime2" data-date-format="yyyy-mm-dd"></div>
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
                                    legend: [
                                    ],
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
                                var dataleg  = [];
                                for(var key in view){
                                    var databand = [];
                                    dataleg.push(key);
                                    for(var item in view[key]){
                                        databand.push(view[key][item]);
                                    }
                                    var seriesx={name:key,type:'bar',symbol:sym[bol],data:databand};
                                    option.series.push(seriesx);
                                    bol += 1;
                                }
                                var leg = {data:dataleg};
                                option.legend.push(leg);
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
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >付费金额区间</th>
                            <!-- <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >付费次数区间</th> -->
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >每日付费玩家</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >每周付费玩家</th>
                            <th class="sorting" tabindex="0" aria-controls="searchable" rowspan="1" colspan="1" style="width: 118px;text-align: center;" >每月付费玩家</th>
                        </tr>
                        <tr id="odd"><?php echo $this->_tpl_vars['str']; ?>
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
                 $.post("index.php?module=pay_date&method=getadson",{tid:tids},
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
             $.post("index.php?module=pay_date&method=getadson",{aid: ids},
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

            location.href="index.php?module=pay_date&method="+method+"&export=1&starttime2="+starttime2+"&endtime2="+endtime2+"&gid="+gid+"&aid="+aid+"&adsons="+adsons+"&tid="+tid;
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