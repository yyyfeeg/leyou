<?php /* Smarty version 2.6.20, created on 2018-03-13 14:05:59
         compiled from create_html.html */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <style>
        #content
        {
            margin: 60px auto;
            width: 80%;
        }

        .loadBar
        {
            width: 500px;
            height: 30px;
            border: 3px solid #DBDBDB;
            border-radius: 20px;
            position: relative;
        }

        .loadBar div
        {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .loadBar div span, .loadBar div i
        {
            background-color: #A0D468;
            box-shadow: inset 0 -2px 6px rgba(0, 0, 0, .4);
            width: 0%;
            display: block;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 20px;
        }

        .loadBar div i
        {
            width: 100%;
            -webkit-animation: move .8s linear infinite;
            background: -webkit-linear-gradient(left top, #7ed047 0%, #7ed047 25%, #4ea018 25%, #4ea018 50%, #7ed047 50%, #7ed047 75%, #4ea018 75%, #4ea018 100%);
            background-size: 40px 40px;
        }

        .loadBar .percentNum
        {
            position: absolute;
            top: 5%;
            right: 50%;
            color: red;
            font-weight: bold;

        }

        @-webkit-keyframes move
        {
            0%
            {
                background-position: 0 0;
            }
            100%
            {
                background-position: 40px 0;
            }
        }
    </style>
    </head>
<body>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loading.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="url_info" class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>生成静态页管理</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">生成静态页表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=create_url&method=show_create_info" method="post" id='myform' name='myform'>
                                <input name="flag" value="write" type="hidden"/>
                                <b style="color:red">▶ PC端 </b><hr/>
                                <div class="form-group">
                                    <label>
                                         <!-- <input type="checkbox" value='1' name='virtue[]' id=""> -->
                                        <span class="text">▶生成首页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_1" name="url_1" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_1']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_1')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='2' name='virtue[]' id=""> -->
                                        <span class="text">▶生成动态列表</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_2" name="url_2" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_2']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_2')"/>
                                </div>
                                <div class="form-group">
                                   <label>
                                        <!-- <input type="checkbox" value='3' name='virtue[]' id=""> -->
                                        <span class="text">▶生成游戏新闻列表</span>
                                    </label>
                                   <input type="text" class="form-control" id="url_3" name="url_3" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_3']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_3')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='4' name='virtue[]' id=""> -->
                                        <span class="text">▶生成游戏中心</span>
                                    </label>
                                   <input type="text" class="form-control" id="url_4" name="url_4" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_4']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_4')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='5' name='virtue[]' id=""> -->
                                        <span class="text">▶生成内容页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_5" name="url_5" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_5']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_5')"/>
                                </div>
                                <b style="color:red">▶ 移动端</b><hr/>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='6' name='virtue[]' id=""> -->
                                        <span class="text">▶生成首页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_6" name="url_6" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_6']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_6')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='7' name='virtue[]' id=""> -->
                                        <span class="text">▶生成新闻页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_7" name="url_7" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_7']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_7')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='8' name='virtue[]' id=""> -->
                                        <span class="text">▶生成游戏页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_8" name="url_8" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_8']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_8')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='9' name='virtue[]' id=""> -->
                                        <span class="text">▶生成客服页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_9" name="url_9" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_9']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_9')"/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <!-- <input type="checkbox" value='10' name='virtue[]' id=""> -->
                                        <span class="text">▶生成内容页</span>
                                    </label>
                                    <input type="text" class="form-control" id="url_10" name="url_10" placeholder="" value="<?php echo $this->_tpl_vars['data']['url_10']; ?>
" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?> onblur="clean_error('url_10')"/>
                                </div>
                                <!-- <label>
                                    <input type="checkbox" id="all">
                                    <span class="text" style="color:red">全选</span>
                                </label>&nbsp;&nbsp;&nbsp;&nbsp; -->
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=create_url&method=show_create_info','myform',0);"<?php if ($this->_tpl_vars['isadmin'] != 1): ?>disabled="disabled" style="display:none"<?php endif; ?>>保存链接</button>
                                <span id="create_all" class="btn btn-blue">生成所有</span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="show_loading" class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="display: none">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>生成静态页进度管理</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">生成静态页进度</span>
                    </div>
                    <div class="widget-body">
                        <div id="content">
                            <div style="text-align:center;margin:20px auto;">进度信息：<span id="tips" style="color: red"></span></div>
                            <div id="loadBar01" class="loadBar">
                                <div>
                                     <span class="percent">
                                        <i></i>
                                     </span>
                                </div>
                                <span class="percentNum">0%</span>
                            </div>
                        </div>
                        <div style="text-align: center"><span id="hide_loading" class="btn btn-blue">关 闭</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="templates/js/bootstrap.min.js"></script>
<script src="templates/js/beyond.min.js"></script>
<script>
    $(function(){
        
        $('#create_all').click(function(){
            var resArr = [];
            var url_1 = $('#url_1').val();
            var url_2 = $('#url_2').val();
            var url_3 = $('#url_3').val();
            var url_4 = $('#url_4').val();
            var url_5 = $('#url_5').val();
            var url_6 = $('#url_6').val();
            var url_7 = $('#url_7').val();
            var url_8 = $('#url_8').val();
            var url_9 = $('#url_9').val();
            var url_10 = $('#url_10').val();
            var urlArr = [url_1,url_2,url_3,url_4,url_5,url_6,url_7,url_8,url_9,url_10];
            var loadbar = new LoadingBar("loadBar01");
            var max = urlArr.length-1;
            var j= 0;
            loadbar.setMax(max);
            for (var i in urlArr) {
                var res = 1;
                $.ajax({
                    url: urlArr[i],
                    type:'GET',
                    data: '',
                    dataType: 'json',
                    async: false,
                    beforeSend: function(){
                        $('#url_info').hide();
                        $('#show_loading').show();
                    },
                    success: function(data) {
                        res = data.code;
                       $('#tips').html(data.msg);
                    }
                });
                if (res == -1) {
                    alert('出错了哦！请刷新页面重试');
                    break;
                }
                loadbar.setProgress(j);
                j ++;
            }
        });

        $('#hide_loading').click(function(){
            $('#url_info').show();
            $('#show_loading').hide();
        });
    });

    function addGameCheck(url,id,name)
    {
        //检测数据
        var url_1 = clean_error('url_1');
        var url_2 = clean_error('url_2');
        var url_3 = clean_error('url_3');
        var url_4 = clean_error('url_4');
        var url_5 = clean_error('url_5');
        var url_6 = clean_error('url_6');
        var url_7 = clean_error('url_7');
        var url_8 = clean_error('url_8');
        var url_9 = clean_error('url_9');
        var url_10 = clean_error('url_10');
        if(url_1&&url_2&&url_3&&url_4&&url_5&&url_6&&url_7&&url_8&&url_9&&url_10){
            formaction(url,id,name);
        }else{
            return false;
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

    function clean_error(name)
    {
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
    // 进度条
    function LoadingBar(id)
    {
        this.loadbar = $("#" + id);
        this.percentEle = $(".percent", this.loadbar);
        this.percentNumEle = $(".percentNum", this.loadbar);
        this.max = 100;
        this.currentProgress = 0;
    }
    LoadingBar.prototype = {
        constructor: LoadingBar,
        setMax: function (maxVal)
        {
            this.max = maxVal;
        },
        setProgress: function (val)
        {
            if (val >= this.max)
            {
                val = this.max;
            }
            this.currentProgress = parseInt((val / this.max) * 100) + "%";
            this.percentEle.width(this.currentProgress);
            this.percentNumEle.text(this.currentProgress);
        }
    };
</script>
</html>