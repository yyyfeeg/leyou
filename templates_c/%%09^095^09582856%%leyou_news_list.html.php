<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:05
         compiled from leyou_news_list.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="wap-font-scale"  content="no"/><!-- 禁止浏览器优化 -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>公司动态 - <?php echo $this->_tpl_vars['title']; ?>
</title>
	<meta name="Keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
"/> 
	<meta name="Description" content="<?php echo $this->_tpl_vars['description']; ?>
"/>
	<script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
	
	<link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/css/index.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/css/common.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/index_c510ece.css">
	
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/memenu.js"></script>
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/global.css"><!-- 头部 -->
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/common-header9.css"><!-- 头部 -->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/common-header.js"></script><!-- 头部 -->
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/index.min.css" /> <!-- 新闻 -->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/m/js/main.js"></script> <!-- 登陆弹出框 -->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['web_url']; ?>
/m/css/style.css" /><!-- 登陆弹出框 -->
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/css/news_list.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->_tpl_vars['web_url']; ?>
/list/list_news.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--  PC端 -->
<div class="wrap">
	<!--菜单-->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--菜单 end-->

	<!-- 头部 -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'list_header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!-- 头部 end -->

	<!-- 内容 -->
		<!-- 新闻页列表内容 -->
		    <!-- 新闻列表 -->
    	<div class="box">
        	<div class="wrapper news">
            	<div class="container">
                	<div class="list-container ">
                    <h3>
                    	<?php echo $this->_tpl_vars['title']; ?>
<span>当前位置：<a href="news.html"><?php echo $this->_tpl_vars['title']; ?>
</a> &gt; </span>
                    </h3>
					<ul class="overflow">
					<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['index']['show'] = true;
$this->_sections['index']['max'] = $this->_sections['index']['loop'];
$this->_sections['index']['step'] = 1;
$this->_sections['index']['start'] = $this->_sections['index']['step'] > 0 ? 0 : $this->_sections['index']['loop']-1;
if ($this->_sections['index']['show']) {
    $this->_sections['index']['total'] = $this->_sections['index']['loop'];
    if ($this->_sections['index']['total'] == 0)
        $this->_sections['index']['show'] = false;
} else
    $this->_sections['index']['total'] = 0;
if ($this->_sections['index']['show']):

            for ($this->_sections['index']['index'] = $this->_sections['index']['start'], $this->_sections['index']['iteration'] = 1;
                 $this->_sections['index']['iteration'] <= $this->_sections['index']['total'];
                 $this->_sections['index']['index'] += $this->_sections['index']['step'], $this->_sections['index']['iteration']++):
$this->_sections['index']['rownum'] = $this->_sections['index']['iteration'];
$this->_sections['index']['index_prev'] = $this->_sections['index']['index'] - $this->_sections['index']['step'];
$this->_sections['index']['index_next'] = $this->_sections['index']['index'] + $this->_sections['index']['step'];
$this->_sections['index']['first']      = ($this->_sections['index']['iteration'] == 1);
$this->_sections['index']['last']       = ($this->_sections['index']['iteration'] == $this->_sections['index']['total']);
?>
						<li>
							<div class="fl news-img">
								<a href="<?php if ($this->_tpl_vars['data'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" title="<?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['title']; ?>
" <?php if ($this->_tpl_vars['data'][$this->_sections['index']['index']]['virtue'] == 'true'): ?>target="_blank"<?php endif; ?>>
									<img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['sphoto']; ?>
" alt="<?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['title']; ?>
" >
								</a>
							</div>
							<div class="fl news-detail">
								<h2>
	                            	<a href="<?php if ($this->_tpl_vars['data'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" title="<?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['title']; ?>
" <?php if ($this->_tpl_vars['data'][$this->_sections['index']['index']]['virtue'] == 'true'): ?>target="_blank"<?php endif; ?>><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['title']; ?>
</a>
	                        	</h2>
	                        	<p>
	                        		<a href="<?php if ($this->_tpl_vars['data'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" title="<?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['title']; ?>
" <?php if ($this->_tpl_vars['data'][$this->_sections['index']['index']]['virtue'] == 'true'): ?>target="_blank"<?php endif; ?>><?php echo $this->_tpl_vars['data'][$this->_sections['index']['index']]['desc']; ?>
</a>
	                        	</p>
							</div>
						</li>
					<?php endfor; endif; ?>
					</ul>
					</div>
            	</div>
        	</div>
    	</div>
		<!-- 新闻页列表内容 end -->
		
		<!-- 新闻页列表页数 -->
		<?php if ($this->_tpl_vars['page_flag'] == 'true'): ?>
		<div class="newslist_content_page">
			<ul>
				<li <?php if ($this->_tpl_vars['page'] == 0): ?>class="on"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['index_page']; ?>
" title="首页">首页</a></li>
				<?php $_from = $this->_tpl_vars['page_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
				<li <?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['k']+1): ?>class="on"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['v']; ?>
" title="<?php echo $this->_tpl_vars['k']+1; ?>
"><?php echo $this->_tpl_vars['k']+1; ?>
</a></li>
				<?php endforeach; endif; unset($_from); ?>
				<li <?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['last_page_flag']): ?>class="on"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['last_page']; ?>
" title="尾页">尾页</a></li>
			</ul>
		</div>
		<?php endif; ?>
		<!-- 新闻页列表页数 end -->
	<!-- 内容 end -->

	<!-- 脚部 -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!-- 脚部 end -->
</div>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/js/jquery.SuperSlide.js"></script>
<script>
	jQuery(".slideBox").slide({mainCell:".bd ul",effect:"left",autoPlay:true,trigger:"click"});
</script>
</body>
</html>