<?php /* Smarty version 2.6.20, created on 2018-03-06 13:59:05
         compiled from games_center_left.html */ ?>
<div class="gl_hotoffers">
	<div class="gl_hotoffers_title">
		<em></em>
		<h4>热门动态</h4>
	</div>
	<div class="gl_hotoffers_list">
		<ul>
		<?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['games_list_left']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<div class="gl_hotoffers_icon">
					<a href="<?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['url']; ?>
" title="<?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['gname']; ?>
" target="_blank" >
						<img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['icon']; ?>
" width="50" height="50" alt="<?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['gname']; ?>
" >
					</a>
				</div>
				<div class="gl_hotoffers_content">
					<a href="<?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['url']; ?>
" title="<?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['gname']; ?>
" target="_blank">
						<h5><?php echo $this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['gname']; ?>
</h5>
					</a>
					<div class="gl_hotoffers_star">
						<ul>
							<li <?php if ($this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['rating'] >= 1): ?>class="on"<?php endif; ?>><a></a></li>
							<li <?php if ($this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['rating'] >= 2): ?>class="on"<?php endif; ?>><a></a></li>
							<li <?php if ($this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['rating'] >= 3): ?>class="on"<?php endif; ?>><a></a></li>
							<li <?php if ($this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['rating'] >= 4): ?>class="on"<?php endif; ?>><a></a></li>
							<li <?php if ($this->_tpl_vars['games_list_left'][$this->_sections['index']['index']]['rating'] >= 5): ?>class="on"<?php endif; ?>><a></a></li>
						</ul>
					</div>
				</div>
			</li>
		<?php endfor; endif; ?>	
		</ul>
	</div>
</div>