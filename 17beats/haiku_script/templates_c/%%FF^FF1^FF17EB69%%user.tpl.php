<?php /* Smarty version 2.6.25, created on 2010-04-19 13:12:09
         compiled from user.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="white round"><div class="pad">
<h2><?php echo $this->_tpl_vars['uinfo']['username']; ?>
</h2> 
<?php if ($this->_tpl_vars['user']->isValid() && $this->_tpl_vars['uinfo']['id'] != $this->_tpl_vars['user']->getInfo('id')): ?> 
<?php if ($this->_tpl_vars['user']->hasAuthorFavorited($this->_tpl_vars['uinfo']['id'])): ?>
(<a href="" class="removeAuthor" author="<?php echo $this->_tpl_vars['uinfo']['id']; ?>
">Remove favorite author</a>)
<?php else: ?>
(<a href="" class="addAuthor" author="<?php echo $this->_tpl_vars['uinfo']['id']; ?>
">Add  favorite author</a>)
<?php endif; ?><?php endif; ?><br />

</div></div>
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "listpoems_user.tpl", 'smarty_include_vars' => array()));
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