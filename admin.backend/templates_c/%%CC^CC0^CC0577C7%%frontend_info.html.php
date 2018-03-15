<?php /* Smarty version 2.6.20, created on 2018-03-13 11:32:48
         compiled from frontend_info.html */ ?>
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
	<div class="col-lg-12 col-lg-offset-3 col-sm-12 col-xs-12" style="0 auto">
        <h5 class="row-title before-pink"><i class="fa fa-expand pink"></i>整站信息管理</h5>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="widget">
                    <div class="widget-header bordered-top bordered-palegreen">
                        <span class="widget-caption">整站信息表单</span>
                    </div>
                    <div class="widget-body">
                        <div class="collapse in">
                            <form enctype="multipart/form-data" action="index.php?module=frontend_info&method=add_info" method="post" id='myform' name='myform'>
                                <input name="act" value="add" type="hidden"/>
                                <input name="id" value="<?php echo $this->_tpl_vars['data']['sysid']; ?>
" type="hidden"/>
                                <div class="form-group">
                                    <label for="definpu">站点根网址：</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" id="basehost" name="basehost" placeholder="例如：http://www.hlwy.com" value="<?php echo $this->_tpl_vars['data']['fi_basehost']; ?>
" onblur="clean_error('basehost')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">网站名称：</label><span style="color:red">*</span>
                                   	<input type="text" class="form-control" id="webname" name="webname" placeholder="例如：乐游官网" value="<?php echo $this->_tpl_vars['data']['fi_webname']; ?>
" onblur="clean_error('webname')"/>
                                </div>
                                <div class="form-group">
                                   <label for="definpu">文档HTML默认保存路径：</label><span style="color:red">*</span>
                                   <input type="text" class="form-control" id="arcdir" name="arcdir" placeholder="例如：/html" value="<?php echo $this->_tpl_vars['data']['fi_arcdir']; ?>
" onblur="clean_error('arcdir')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">图片/上传文件默认路径：</label><span style="color:red">*</span>
                                   <input type="text" class="form-control" id="upload_dir" name="upload_dir" placeholder="例如：/uploads" value="<?php echo $this->_tpl_vars['data']['fi_upload_dir']; ?>
" onblur="clean_error('upload_dir')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">网站版权信息：</label>
                                    <textarea class="form-control" rows="3" placeholder="例如：乐游版权所有" id='powerby' name='powerby'><?php echo $this->_tpl_vars['data']['fi_powerby']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">站点默认关键字：</label><span style="color:red">*</span>
                                 	<input type="text" class="form-control" id="keywords" name="keywords" placeholder="例如：乐游(多个关键字之间用英文逗号分隔)" value="<?php echo $this->_tpl_vars['data']['fi_keywords']; ?>
" onblur="clean_error('keywords')"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">站点描述：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="3" placeholder="例如：乐游用户中心。。。" id='desc' name='desc' onblur="clean_error('desc')"><?php echo $this->_tpl_vars['data']['fi_desc']; ?>
</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">网站备案号：</label>
                                    <input type="text" class="form-control" id="beian" name="beian" placeholder="例如：文网游备字〔2015〕Ｍ-RPG 0582号" value="<?php echo $this->_tpl_vars['data']['fi_beian']; ?>
"/>
                                </div>
                                <div class="form-group">
                                    <label for="definpu">生成首页地址：</label><span style="color:red">*</span>
                                    <textarea class="form-control" rows="3" placeholder="" id='indexurl' name='indexurl' onblur="clean_error('indexurl')" <?php if ($this->_tpl_vars['isadmin'] != 1): ?>readonly<?php endif; ?>><?php echo $this->_tpl_vars['data']['fi_indexurl']; ?>
</textarea>
                                </div>
                                <button type="submit" class="btn btn-blue" onclick="return addGameCheck('index.php?module=frontend_info&method=add_info','myform',0);">保 存</button>
                                <button type="submit" onClick="history.go(-1);return false;" value="取消" name="submit" class="btn btn-blue">取 消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
	    function addGameCheck(url,id,name)
	    {
	        //检测数据
	        var basehost = clean_error('basehost');
	        var webname = clean_error('webname');
	        var arcdir = clean_error('arcdir');
	        var upload_dir = clean_error('upload_dir');
	        var keywords = clean_error('keywords');
            var desc = clean_error('desc');
	        var indexurl = clean_error('indexurl');
	        if(basehost && webname && arcdir && upload_dir && keywords && desc && indexurl){
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