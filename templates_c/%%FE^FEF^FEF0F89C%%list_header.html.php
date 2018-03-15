<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:05
         compiled from list_header.html */ ?>
<!-- 头部 -->
<div class="newslist_head">
	<!-- 新闻页大轮播 -->
	<div class="newslist_head_pic slideBox">
		<div class="nl_head_pic_img bd">
			<ul>
			<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['ppt_array']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<a href="<?php if ($this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['virtue'] == 'true'): ?><?php echo $this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['jurl']; ?>
<?php else: ?><?php echo $this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['url']; ?>
<?php endif; ?>" title="<?php echo $this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['title']; ?>
" <?php if ($this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['virtue'] == 'true'): ?>target="_blank"<?php endif; ?>>
						<img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['bphoto']; ?>
" alt="<?php echo $this->_tpl_vars['ppt_array'][$this->_sections['index']['index']]['title']; ?>
" width="100%" height="560">
					</a>
				</li>
			<?php endfor; endif; ?>
			</ul>
		</div>
		<div class="nl_head_pic_nav hd">
			<ul>
			<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['ppt_array']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<li><a href="javascript:void(0);"></a></li>
			<?php endfor; endif; ?>
			</ul>
		</div>
	</div>
	<!-- 新闻页大轮播 end -->


</div>
<!-- 头部 end -->