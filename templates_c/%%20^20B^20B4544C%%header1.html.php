<?php /* Smarty version 2.6.20, created on 2017-12-12 17:11:31
         compiled from header1.html */ ?>
<div class="index_top" >
<!-- <div class="index_top"> -->
	<div class="index_top_content" >
		<!--logo-->
		<div class="index_top_logo">
			<a href="<?php echo $this->_tpl_vars['web_url']; ?>
/index.html" title="欢乐游戏">
				<img src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/images/leyou_logo.png" width="165" alt="欢乐游戏" />
			</a>
		</div>
		<!--logo end-->
		<!--导航-->
		<div class="index_top_nav">
			<ul>
				<li <?php if ($this->_tpl_vars['menu_flag'] == 'index'): ?>class="on"<?php endif; ?>><a class="index_top_index" href="<?php echo $this->_tpl_vars['web_url']; ?>
/index.html" title="首页"></a></li>
				<li <?php if ($this->_tpl_vars['menu_flag'] == 'news'): ?>class="on"<?php endif; ?>><a class="index_top_news" href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/news/ttdt/index.html" title="新闻"></a></li>
				<li><a class="index_top_usercenter" href="<?php echo $this->_tpl_vars['web_url']; ?>
/index.php?mo=user_center&me=index"  title="用户中心" target="_blank"></a></li>
				<li class="index_t_gc <?php if ($this->_tpl_vars['menu_flag'] == 'games'): ?>on<?php endif; ?>">
					<a class="index_top_gamecenter" href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/games/index.html"  title="游戏中心"></a>
				<!--游戏中心展开页-->
					<div class="index_top_gamecenterlist">
						<ul class="index_top_left">
							<!-- 最近游戏 -->
						</ul>
						<ul class="index_top_right">
							<li><h5>手机游戏</h5></li>
							<?php unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['menu_mobile_games']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['id']['show'] = true;
$this->_sections['id']['max'] = $this->_sections['id']['loop'];
$this->_sections['id']['step'] = 1;
$this->_sections['id']['start'] = $this->_sections['id']['step'] > 0 ? 0 : $this->_sections['id']['loop']-1;
if ($this->_sections['id']['show']) {
    $this->_sections['id']['total'] = $this->_sections['id']['loop'];
    if ($this->_sections['id']['total'] == 0)
        $this->_sections['id']['show'] = false;
} else
    $this->_sections['id']['total'] = 0;
if ($this->_sections['id']['show']):

            for ($this->_sections['id']['index'] = $this->_sections['id']['start'], $this->_sections['id']['iteration'] = 1;
                 $this->_sections['id']['iteration'] <= $this->_sections['id']['total'];
                 $this->_sections['id']['index'] += $this->_sections['id']['step'], $this->_sections['id']['iteration']++):
$this->_sections['id']['rownum'] = $this->_sections['id']['iteration'];
$this->_sections['id']['index_prev'] = $this->_sections['id']['index'] - $this->_sections['id']['step'];
$this->_sections['id']['index_next'] = $this->_sections['id']['index'] + $this->_sections['id']['step'];
$this->_sections['id']['first']      = ($this->_sections['id']['iteration'] == 1);
$this->_sections['id']['last']       = ($this->_sections['id']['iteration'] == $this->_sections['id']['total']);
?>
							<li>
								<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['menu_mobile_games'][$this->_sections['id']['index']]['virtue_array']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<?php if ($this->_tpl_vars['menu_mobile_games'][$this->_sections['id']['index']]['virtue_array'][$this->_sections['index']['index']] == 1): ?><span></span><?php endif; ?>
								<?php if ($this->_tpl_vars['menu_mobile_games'][$this->_sections['id']['index']]['virtue_array'][$this->_sections['index']['index']] == 2): ?><em></em><?php endif; ?>
								<?php endfor; endif; ?>
								<a style="color:#c2c2c2;" href="<?php echo $this->_tpl_vars['menu_mobile_games'][$this->_sections['id']['index']]['gw_url']; ?>
" title="<?php echo $this->_tpl_vars['menu_mobile_games'][$this->_sections['id']['index']]['game_name']; ?>
" target="_blank" ><?php echo $this->_tpl_vars['menu_mobile_games'][$this->_sections['id']['index']]['game_name']; ?>
</a>
							</li>
							<?php endfor; endif; ?>
						</ul>
					</div>
				<!--游戏中心展开页 end-->
				</li>
				<li><a class="index_top_service" href="<?php echo $this->_tpl_vars['web_url']; ?>
/index.php?mo=service&me=index" title="客服中心" target="_blank"></a></li>
			</ul>
		</div>
		<!--导航 end-->
		<!-- 登录展示 -->
		<!-- 登录 -->
		<div id="top_login" class="index_top_login">
			<ul>
				<li><a style="border:none;" href="javascript:void(0)" title="登录" onclick="base.displayHandle('popupbox_login','popupbox_wrap','','s',300);">登录</a></li>
				<span>|</span>
				<li><a href="javascript:void(0)" title="注册" onclick="base.displayHandle('fast_reg','popupbox_wrap','','s',300);">注册</a></li>
			</ul>
		</div>
		<!-- 登录 end -->
		<!-- 登录 -->
		<div id="login_true" class="index_top_login" style="display:none">
			<ul>
				<li><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/index.php?mo=user_center&me=index" target="_blank"><p id="uname"></p></a></li>
				<span>|</span>
				<li><a id="logout" href="javascript:void(0);" title="退出">退出</a></li>
			</ul>
		</div>
		<!-- 登录 end -->
		<!-- 登录展示end -->
	</div>
</div>