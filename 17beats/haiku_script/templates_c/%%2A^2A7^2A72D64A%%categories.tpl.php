<?php /* Smarty version 2.6.25, created on 2010-04-17 21:26:55
         compiled from categories.tpl */ ?>

<div id="categories" class="round white center">
    <?php if ($this->_tpl_vars['category'] == -1): ?>
        <i>All</i><br />
	<?php else: ?>
    	<a href="/" class="category">All</a><br />
    <?php endif; ?>
<?php unset($this->_sections['cat']);
$this->_sections['cat']['name'] = 'cat';
$this->_sections['cat']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat']['show'] = true;
$this->_sections['cat']['max'] = $this->_sections['cat']['loop'];
$this->_sections['cat']['step'] = 1;
$this->_sections['cat']['start'] = $this->_sections['cat']['step'] > 0 ? 0 : $this->_sections['cat']['loop']-1;
if ($this->_sections['cat']['show']) {
    $this->_sections['cat']['total'] = $this->_sections['cat']['loop'];
    if ($this->_sections['cat']['total'] == 0)
        $this->_sections['cat']['show'] = false;
} else
    $this->_sections['cat']['total'] = 0;
if ($this->_sections['cat']['show']):

            for ($this->_sections['cat']['index'] = $this->_sections['cat']['start'], $this->_sections['cat']['iteration'] = 1;
                 $this->_sections['cat']['iteration'] <= $this->_sections['cat']['total'];
                 $this->_sections['cat']['index'] += $this->_sections['cat']['step'], $this->_sections['cat']['iteration']++):
$this->_sections['cat']['rownum'] = $this->_sections['cat']['iteration'];
$this->_sections['cat']['index_prev'] = $this->_sections['cat']['index'] - $this->_sections['cat']['step'];
$this->_sections['cat']['index_next'] = $this->_sections['cat']['index'] + $this->_sections['cat']['step'];
$this->_sections['cat']['first']      = ($this->_sections['cat']['iteration'] == 1);
$this->_sections['cat']['last']       = ($this->_sections['cat']['iteration'] == $this->_sections['cat']['total']);
?>

	<?php if ($this->_tpl_vars['category'] == $this->_tpl_vars['categories'][$this->_sections['cat']['index']]['id']): ?>
    	<i><?php echo $this->_tpl_vars['categories'][$this->_sections['cat']['index']]['name']; ?>
</i>
    <?php else: ?>
    	<a href="/categories/<?php echo $this->_tpl_vars['categories'][$this->_sections['cat']['index']]['name']; ?>
" class="category"><?php echo $this->_tpl_vars['categories'][$this->_sections['cat']['index']]['name']; ?>
</a>
     <?php endif; ?>
     <br />
<?php endfor; endif; ?>

</div>