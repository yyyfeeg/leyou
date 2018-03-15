<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:04
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'index.html', 122, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="wap-font-scale"  content="no"/><!-- 禁止浏览器优化 -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->_tpl_vars['title']; ?>
 - 首页</title>
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
	
	<link href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/style.css" rel="stylesheet" type="text/css" media="all" />	
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/memenu.js"></script>
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/global.css"><!-- 头部 -->
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/common-header9.css"><!-- 头部 -->
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/css/index.min.css" /> <!-- 新闻 -->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/m/js/main.js"></script> <!-- 登陆弹出框 -->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['web_url']; ?>
/m/css/style.css" /><!-- 登陆弹出框 -->
	<script type="text/javascript">
	try {
	var urlhash = window.location.hash;
	if (!urlhash.match("fromapp"))
	{
	if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i)))
	{
	window.location="html/mobile/index.html"; //这里的网址请改为你手机站的网址
	}
	}
	}
	catch(err)
	{
	}
	</script>
</head>
<body>
<div class="index_wrap">
<!--  PC端 -->
	<!--底部-->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--底部 end-->

	<!-- 头部 -->
	<!-- 轮播图 -->
	<div style="z-index: 2;"> 
	    <div class="slider">
		  <div class="callbacks_container" style="position: absolute;top: 70px;">
		     <ul class="rslides" id="slider">
		     	<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['index_ppt']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                        <a href="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['jurl']; ?>
" title="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['title']; ?>
" target="_blank" >
                            <img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['purl']; ?>
" style="width: 100%;height: 710px;">      
                        </a>
                    </li>
                <?php endfor; endif; ?>
		      </ul>
		  </div>
	    </div>
	    
	    <!-- 新闻 -->	
	    	<div class="container news-box ">
                <div class="news-box-list">
                    <!-- left-banner -->
                    <div class="new-banner"  id="newsList">
                        <!--小圆点-->
                        <div class="ol-nav"></div>
                        <!--左边-->
						<div class="new-index">
						<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['games_ppt']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
							<div class="banner-description <?php if ($this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['gid'] == 1): ?>active<?php endif; ?>"> <i></i> <b><?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['gid']; ?>
</b>
                                <dl>
                                    <dd><?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['title']; ?>
</dd>
			                    </dl>
                            </div>
                        <?php endfor; endif; ?>
					    </div>
						<!--图片-->
                        <div class="new-total">
							<div class="news-img-box">
							<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['games_ppt']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<div class="news-img">
                                    <div class="banner-img-model">
                                        <div>
                                            <h4><?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['title']; ?>
</h4>
                                            <p><?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['desc']; ?>
</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['jurl']; ?>
" title="<?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['title']; ?>
" target="_blank">
										<img style="height: 330px;width: 460px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['games_ppt'][$this->_sections['index']['index']]['purl']; ?>
" alt="" />
									</a>
                                </div>
                            <?php endfor; endif; ?>
							</div>
						</div>
                    </div>
                </div>
                <!-- /left-banner -->
                <!-- news list -->
                <div class="new-list">
                    <div class="new-more">
                        <a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/news/yxxw/index.html">
                            <i><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/add.png" /></i>
                        </a>
                    </div>
					<div class="news-top text-left">
                        <h3>
                        	<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['headerArr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		                    <a href="<?php if ($this->_tpl_vars['headerArr'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['headerArr'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['headerArr'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" <?php if ($this->_tpl_vars['headerArr'][$this->_sections['index']['index']]['virtue'] == 'true'): ?> target="_blank"<?php endif; ?> title="<?php echo $this->_tpl_vars['headerArr'][$this->_sections['index']['index']]['title']; ?>
"><?php echo $this->_tpl_vars['headerArr'][$this->_sections['index']['index']]['title']; ?>
</a>
		                	<?php endfor; endif; ?>
                        </h3>
                        <!-- <p>
                            <a href="">
                            </a>
                        </p> -->
                    </div>
					<ul>
	                <?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['game_dynamic']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	                    <li><a href="<?php if ($this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" title="<?php echo $this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['title']; ?>
" <?php if ($this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['virtue'] == 'true'): ?> target="_blank"<?php endif; ?>><span><?php echo $this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['title']; ?>
</span><em style="float:right;font-size:14px;color:#565656"><?php echo ((is_array($_tmp=$this->_tpl_vars['game_dynamic'][$this->_sections['index']['index']]['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d") : smarty_modifier_date_format($_tmp, "%m-%d")); ?>
</em></a></li>
	                <?php endfor; endif; ?>
                	</ul>
				</div>
          </div>
	    
	<!---->
	</div>
	<!-- 头部 end -->

	<!-- 内容 -->
	<div class="index_content">
		<div class="index_gy_gnl">
		</div>
		<!--热点游戏-->
		<div class="index_gamehot" style="background-color: ;">
			<div class="index_gamehot_content">
				<div class="con-box f-cb">
				    <div id="mobileGameHot" class="g-hotBox">
					<p style="" class="con-box-title">热点游戏<span>/Hot game</span></p>
						<div class="g-hot-all">
							<div class="g-hot-wrap active">
								<?php $_from = $this->_tpl_vars['hot_games']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sysid'] => $this->_tpl_vars['value']):
?>
								<div class="cur-hot-box">
									<?php $_from = $this->_tpl_vars['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s'] => $this->_tpl_vars['val']):
?>
			                        <div class="hot-box">
			                            <a class="hot-link" href="<?php echo $this->_tpl_vars['val']['jurl']; ?>
" title="<?php echo $this->_tpl_vars['val']['title']; ?>
" target="_blank" ><img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['val']['purl']; ?>
" class="hot-bg-img" alt="<?php echo $this->_tpl_vars['val']['title']; ?>
">
			                                <div class="hot-font f-turn" style="background-image:url(<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['val']['qrurl']; ?>
);background-size: 100% 100%;)"></div>
			                            </a>
			                            <p class="hot-title"><?php echo $this->_tpl_vars['val']['title']; ?>
</p>
			                            <p class="hot-txt"><?php echo $this->_tpl_vars['val']['desc']; ?>
</p>
										<span class="hot-like ilikeSet" id="ilike_" data-ilike=""></span>
										<a href="<?php echo $this->_tpl_vars['val']['jurl']; ?>
" class="gw-link" target="_blank">查看详情</a>
			                        </div>
			                        <?php endforeach; endif; unset($_from); ?>
			                	</div>
			                	<?php endforeach; endif; unset($_from); ?>
							</div>
						</div> 
				    </div>
	            </div>
			</div>
		</div>
		<!--热点游戏 end-->		
		
		<!--全部游戏-->
		<div class="index_gameall" style="display: none;">
			<div class="index_gametitle">
				<em></em>
				<h4>全部游戏<span>/All game</span></h4>
				<a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/games/index.html" title="更多"><img style="width: 90%;height: 90%;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/more.gif" /></a>
			</div>
			<div class="index_gameall_list">
				<div class="index_gameall_content index_gameall_mobilegame">
					<h5>游戏</h5>
					<ul>
						<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['mobile_game']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                            <div class="index_ga_img"><img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['mobile_game'][$this->_sections['index']['index']]['icon']; ?>
" width="151" height="115"></div>
                            <p><?php echo $this->_tpl_vars['mobile_game'][$this->_sections['index']['index']]['gname']; ?>
</p>
                            <div class="index_ga_btn">
                                <a class="index_ga_btn_gw" href="<?php echo $this->_tpl_vars['mobile_game'][$this->_sections['index']['index']]['gwurl']; ?>
" title="官网" target="_blank">官网</a>
                                <a class="index_ga_btn_dl" href="<?php echo $this->_tpl_vars['mobile_game'][$this->_sections['index']['index']]['azurl']; ?>
" title="下载游戏">下载游戏</a>
                            </div>
                        </li>
                    	<?php endfor; endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<!--全部游戏 end-->
	</div>
	
	
	<!-- 联系我们 -->
	<div class="contact-us">
		<div class="index_content contact_us" style="">
			<div id="mobileGameHot" class="g-hotBox">
				<p style="" class="con-box-title">联系我们<span>/Contact us</span></p>
			</div>
			<div class="contactus1">
				<p style="font-size: 18px;color: #AECF32;font-weight: bolder;margin-bottom: 3px;">联系方式</p>
				<p>公司：广东乐游互联网科技有限公司</p>
				<p>地址：广州市天河区中山大道建工路13天信楼4楼406房</p>
				<p>电话：020-89917234</p>
				<p>邮编：510000</p>
				<p>邮箱：hr-leyou@hlwy.com</p>
				<div style="width: 70%;height: 55%;margin-left: 12%;"><img style="height: 100%;width: 100%;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/images/address.jpg" title="公司地址" /></div>
			</div>
			<div class="contactus2">
				<p style="font-size: 18px;color: #AECF32;font-weight: bolder;margin-bottom: 3px;">市场媒体合作</p>
				<p>市场媒体合作：媒体合作伙伴、广告公司、公关公司等</p>
				<p>联系人：</p>
				<p>E-mail：</p>
				<p style="font-size: 18px;color: #AECF32;font-weight: bolder;margin-bottom: 3px;margin-top: 38px;">联合运营合作</p>
				<p>联合运营合作全国各联合运营合作伙伴、其他商务合作等</p>
				<p>联系人：</p>
				<p>E-mail：</p>
			</div>	
			<div class="contactus3">
				<p style="font-size: 18px;color: #AECF32;font-weight: bolder;margin-bottom: 3px;">关注我们</p>
				<p>新浪微博：</p>
				<p>腾讯微博：</p>
				<p style="font-size: 18px;color: #AECF32;font-weight: bolder;margin-bottom: 3px;margin-top: 55px;">人力资源招聘</p>
				<p>人力资源招聘有志于加盟广州乐游共建未来的个人和团队</p>
				<p>联系人：</p>
				<p>E-mail：</p>
			</div>	
		</div>
	</div>
	<!-- 内容 end -->
    <!-- 友链 -->
    <div class="footer-link" style="display: none;">
		<div class="footer-con">
			<span style="font-weight: bolder;">友情链接：</span>
			<a target="_blank" href="http://www.app178.com/">APP178</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a target="_blank" href="http://www.gk99.com/">游戏港口</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a target="_blank" href="http://www.kaifu.com/">开服网</a>
		</div>
	</div>
	
	<!--底部-->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--底部 end-->

	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/jquery-1.11.0.min.js"></script> <!-- 新闻 -->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/jquerymixNIE.js"></script>
	<script src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/responsiveslides.min.js"></script>
	<script>  
	    $(function () {
	      $("#slider").responsiveSlides({
	      	auto: true,
	      	nav: true,
	      	speed: 500,
	        namespace: "callbacks",
	        pager: false,
	      });
	    });
	</script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/globalnews.js"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/auto_combine_3c6fb_dcceb11.js"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/js/index.min.js" ></script> <!-- 新闻 -->
	<style>
		#write0{background-color: #139BD8;color: #FFFFFF;}
	</style>
</div>
</body>
</html>