<?php /* Smarty version 2.6.25, created on 2010-04-19 05:29:36
         compiled from favorite_author.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="white round"><div class="pad">
<h2> Favorite authors of <?php echo $this->_tpl_vars['founduser']; ?>
</h2> 
(<a href="/profile/<?php echo $this->_tpl_vars['founduser']; ?>
">Visit profile</a>)
<?php if ($this->_tpl_vars['user']->isValid() && $this->_tpl_vars['foundid'] != $this->_tpl_vars['user']->getInfo('id')): ?> 
<?php if ($this->_tpl_vars['user']->hasAuthorFavorited($this->_tpl_vars['foundid'])): ?>
(<a href="" class="removeAuthor" author="<?php echo $this->_tpl_vars['foundid']; ?>
">Remove favorite author</a>)
<?php else: ?>
(<a href="" class="addAuthor" author="<?php echo $this->_tpl_vars['foundid']; ?>
">Add favorite author</a>)
<?php endif; ?><?php endif; ?>
</div></div><br />

<div class="white round full">
<div class="pad">
<?php unset($this->_sections['author']);
$this->_sections['author']['name'] = 'author';
$this->_sections['author']['loop'] = is_array($_loop=$this->_tpl_vars['authors']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['author']['show'] = true;
$this->_sections['author']['max'] = $this->_sections['author']['loop'];
$this->_sections['author']['step'] = 1;
$this->_sections['author']['start'] = $this->_sections['author']['step'] > 0 ? 0 : $this->_sections['author']['loop']-1;
if ($this->_sections['author']['show']) {
    $this->_sections['author']['total'] = $this->_sections['author']['loop'];
    if ($this->_sections['author']['total'] == 0)
        $this->_sections['author']['show'] = false;
} else
    $this->_sections['author']['total'] = 0;
if ($this->_sections['author']['show']):

            for ($this->_sections['author']['index'] = $this->_sections['author']['start'], $this->_sections['author']['iteration'] = 1;
                 $this->_sections['author']['iteration'] <= $this->_sections['author']['total'];
                 $this->_sections['author']['index'] += $this->_sections['author']['step'], $this->_sections['author']['iteration']++):
$this->_sections['author']['rownum'] = $this->_sections['author']['iteration'];
$this->_sections['author']['index_prev'] = $this->_sections['author']['index'] - $this->_sections['author']['step'];
$this->_sections['author']['index_next'] = $this->_sections['author']['index'] + $this->_sections['author']['step'];
$this->_sections['author']['first']      = ($this->_sections['author']['iteration'] == 1);
$this->_sections['author']['last']       = ($this->_sections['author']['iteration'] == $this->_sections['author']['total']);
?>
	<a href="/users/<?php echo $this->_tpl_vars['authors'][$this->_sections['author']['index']]['author']; ?>
"><?php echo $this->_tpl_vars['authors'][$this->_sections['author']['index']]['author']; ?>
</a> (<?php if ($this->_tpl_vars['user']->isValid() && $this->_tpl_vars['user']->hasAuthorFavorited($this->_tpl_vars['authors'][$this->_sections['author']['index']]['uid'])): ?><a href="#" class="removeAuthor" author="<?php echo $this->_tpl_vars['authors'][$this->_sections['author']['index']]['uid']; ?>
">Remove favorite author</a><?php else: ?><a href="#" class="addAuthor" author="<?php echo $this->_tpl_vars['authors'][$this->_sections['author']['index']]['uid']; ?>
">Add favorite author</a><?php endif; ?>)<br />

  
<?php endfor; endif; ?>    
<?php if (sizeof ( $this->_tpl_vars['poems'] ) == 0): ?>

<h3>No authors in here<br />
User need to add favorites <br />
Need to spread some love  &hearts;</h3>

<?php endif; ?>

 </div></div>   
 <br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "listpoems_favorite.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>