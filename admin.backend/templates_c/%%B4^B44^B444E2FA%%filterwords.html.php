<?php /* Smarty version 2.6.20, created on 2018-01-25 17:26:39
         compiled from filterwords.html */ ?>
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
    <?php if ($this->_tpl_vars['type'] == 'list'): ?>
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>敏感词信息查看</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">敏感词信息内容</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=filterwords&method=add_filter" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">已收录敏感词信息：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="10" readonly="true" placeholder="例如：非典|艾滋病|阳痿" id='filterwords' name='filterwords' onblur="clean_error('filterwords')"><?php echo $this->_tpl_vars['data']['filterwords']; ?>
</textarea>
                                </div>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['type'] == 'add'): ?>
    <div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>敏感词信息管理</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">敏感词信息表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=filterwords&method=add_filter" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">敏感词信息：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="10" placeholder="例如：非典|艾滋病|阳痿" id='filterwords' name='filterwords' onblur="clean_error('filterwords')"><?php echo $this->_tpl_vars['data']['filterwords']; ?>
</textarea>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=filterwords&method=add_filter','myform',0);">添 加</button>
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
            var filterwords = clean_error('filterwords');
            if(filterwords){
                formaction(url,id,name);
            }else{
                return false;
            }
        }

        function look()
        {
            //检测数据
            var = 
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
        <div class="toast fa-check toast-blue" ><button class="toast-close-button">×</button>
            <div class="toast-message"><?php echo $this->_tpl_vars['meg']; ?>
</div>
        </div>
    </div>
</body>
<script src="templates/js/bootstrap.min.js"></script>
<script src="templates/js/beyond.min.js"></script>
</html>