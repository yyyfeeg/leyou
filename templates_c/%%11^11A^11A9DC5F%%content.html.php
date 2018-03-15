<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:05
         compiled from content.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'content.html', 116, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="wap-font-scale"  content="no"/><!-- 禁止浏览器优化 -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->_tpl_vars['content_arr']['rubricid']; ?>
 - <?php echo $this->_tpl_vars['title']; ?>
</title>
	<meta name="Keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
"/> 
	<meta name="Description" content="<?php echo $this->_tpl_vars['description']; ?>
"/>
	<link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/css/news_content.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/css/common.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/m/js/main.js"></script> <!-- 登陆弹出框 -->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['web_url']; ?>
/m/css/style.css" /><!-- 登陆弹出框 -->
    <link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/global.css"><!-- 头部 -->
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/common-header9.css"><!-- 头部 -->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/common-header.js"></script><!-- 头部 -->
</head>
<body>
<!--  PC端 -->
<div class="index_wrap">
	<!--菜单-->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--菜单 end-->

	<!-- 新闻页 详细内容 -->
	<div class="newscontent_content">
		<!-- 新闻页 标题 -->
		<div class="nc_title">
			<h4><?php echo $this->_tpl_vars['content_arr']['rubricid']; ?>
</h4>
			<p class="current fr">
			    当前位置：
			    <a href="<?php echo $this->_tpl_vars['web_url']; ?>
/index.html" title="首页">首页</a> &gt; 
			    <a href="<?php echo $this->_tpl_vars['content_arr']['rubricurl']; ?>
" title="<?php echo $this->_tpl_vars['content_arr']['rubricid']; ?>
"><?php echo $this->_tpl_vars['content_arr']['rubricid']; ?>
</a>
			</p>
		</div>
		<!-- 新闻页 标题 end -->

		<!-- 新闻 内容 -->
		<div class="nc_content">
			<div class="nc_ct_title">
				<h3><?php echo $this->_tpl_vars['content_arr']['title']; ?>
</h3>
				<p>
					<span>作者：<?php echo $this->_tpl_vars['content_arr']['author']; ?>
</span>
					<span>发布时间 : <?php echo $this->_tpl_vars['content_arr']['printtime']; ?>
</span>
				</p>
			</div>
			<div class="nc_ct_content">
			<?php echo $this->_tpl_vars['content_arr']['contents']; ?>

			</div>

			<!-- 文章分享 -->
			<!-- <div class="nc_ct_share">
				<p>分享到:</p>
				<ul>
					<li><a class="nc_share_wechat" href="" title="分享到朋友圈"></a></li>
					<li><a class="nc_share_tencnet" href="" title="分享到QQ好友"></a></li>
					<li><a class="nc_share_space" href="" title="分享到QQ空间"></a></li>
					<li><a class="nc_share_sina" href="" title="分享到新浪微博"></a></li>
					<li><a class="nc_share_renren" href="" title="分享到人人网"></a></li>
					<li><a class="nc_share_tcfirend" href="" title="分享到QQ朋友"></a></li>
					<li><a class="nc_share_more" href="" title="更多"></a></li>
				</ul>
			</div> -->
			<!--<div class="bdsharebuttonbox nc_ct_share">
				<p>分享到:</p>
				<ul>
					<a href="#" title="分享到QQ空间" class="bds_qzone" data-cmd="qzone"></a>
					<a href="#" title="分享到新浪微博" class="bds_tsina" data-cmd="tsina"></a>
					<a href="#" title="分享到腾讯微博" class="bds_tqq" data-cmd="tqq"></a>
					<a href="#" title="分享到人人网" class="bds_renren" data-cmd="renren"></a>
					<a href="#" title="分享到微信" class="bds_weixin" data-cmd="weixin"></a>
					<a href="#" title="更多" class="bds_more" data-cmd="more"></a>
				</ul>
			</div>
			<script>
				window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdPic":"","bdStyle":"0","bdSize":"16"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
			</script>-->
			<!-- 文章分享 end -->

		</div>
		<!-- 新闻 内容 content -->

		<!-- 热游预告 -->
		<div class="nc_hotgame">
			<div class="nc_hotgame_title">
				<em></em>
				<h4>热点游戏</h4>
			</div>
			<div class="nc_hotgame_content">
				<ul>
				<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['hot_games']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<a href="<?php echo $this->_tpl_vars['hot_games'][$this->_sections['index']['index']]['jurl']; ?>
" title="<?php echo $this->_tpl_vars['hot_games'][$this->_sections['index']['index']]['title']; ?>
" target="_blank">
							<img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['hot_games'][$this->_sections['index']['index']]['purl']; ?>
" width="230" height="120">
						</a>
					</li>
				<?php endfor; endif; ?>
				</ul>
			</div>
		</div>
		<!-- 热游预告 end -->

		<!-- 热门动态 -->
		<div class="nc_hotnews">
			<div class="nc_hotgame_title">
				<em></em>
				<h4>热门动态</h4>
			</div>
			<div class="nc_hotnews_list">
				<ul>
				<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['dynamic']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<span>[<?php echo $this->_tpl_vars['content_arr']['rubricid']; ?>
]</span>
						<a href="<?php if ($this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" title="<?php echo $this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['title']; ?>
" <?php if ($this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['virtue'] == 'true'): ?> target="_blank"<?php endif; ?>><?php echo $this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['title']; ?>
</a>
						<em><?php echo ((is_array($_tmp=$this->_tpl_vars['dynamic'][$this->_sections['index']['index']]['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m/%d") : smarty_modifier_date_format($_tmp, "%m/%d")); ?>
</em>
					</li>
				<?php endfor; endif; ?>
				</ul>
			</div>
		</div>
		<!-- 热门动态 end -->
	</div>
	<!-- 新闻页 详细内容 end -->

	<!-- 脚部 -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!-- 脚部 end -->
</div>
</body>
</html>