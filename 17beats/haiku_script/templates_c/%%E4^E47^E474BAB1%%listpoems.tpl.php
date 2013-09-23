<?php /* Smarty version 2.6.25, created on 2010-04-19 03:53:35
         compiled from listpoems.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'listpoems.tpl', 23, false),array('modifier', 'date_format', 'listpoems.tpl', 27, false),array('modifier', 'escape', 'listpoems.tpl', 28, false),)), $this); ?>
<?php if (sizeof ( $this->_tpl_vars['poems'] ) == 0): ?>

<div class="white round full">
<div class="pad">
<h3>No haiku in here<br />
Maybe you write one yourself? <br />
Would make children smile :)</h3>
</div>
</div><br />

<?php endif; ?>

<?php unset($this->_sections['poem']);
$this->_sections['poem']['name'] = 'poem';
$this->_sections['poem']['loop'] = is_array($_loop=$this->_tpl_vars['poems']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['poem']['show'] = true;
$this->_sections['poem']['max'] = $this->_sections['poem']['loop'];
$this->_sections['poem']['step'] = 1;
$this->_sections['poem']['start'] = $this->_sections['poem']['step'] > 0 ? 0 : $this->_sections['poem']['loop']-1;
if ($this->_sections['poem']['show']) {
    $this->_sections['poem']['total'] = $this->_sections['poem']['loop'];
    if ($this->_sections['poem']['total'] == 0)
        $this->_sections['poem']['show'] = false;
} else
    $this->_sections['poem']['total'] = 0;
if ($this->_sections['poem']['show']):

            for ($this->_sections['poem']['index'] = $this->_sections['poem']['start'], $this->_sections['poem']['iteration'] = 1;
                 $this->_sections['poem']['iteration'] <= $this->_sections['poem']['total'];
                 $this->_sections['poem']['index'] += $this->_sections['poem']['step'], $this->_sections['poem']['iteration']++):
$this->_sections['poem']['rownum'] = $this->_sections['poem']['iteration'];
$this->_sections['poem']['index_prev'] = $this->_sections['poem']['index'] - $this->_sections['poem']['step'];
$this->_sections['poem']['index_next'] = $this->_sections['poem']['index'] + $this->_sections['poem']['step'];
$this->_sections['poem']['first']      = ($this->_sections['poem']['iteration'] == 1);
$this->_sections['poem']['last']       = ($this->_sections['poem']['iteration'] == $this->_sections['poem']['total']);
?>
<div class="white round full">
<div class="pad">
	<?php if ($this->_tpl_vars['poems'][$this->_sections['poem']['index']]['title'] != ""): ?>
    	<h4><?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['title']; ?>
</h4>
        <?php else: ?>
        <br />
    <?php endif; ?>
    <div class="full center">
    
<?php echo ((is_array($_tmp=$this->_tpl_vars['poems'][$this->_sections['poem']['index']]['haiku'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div><br />

	<div class="pfoot">
	    <div class="pinfo">
    		On <?php echo ((is_array($_tmp=$this->_tpl_vars['poems'][$this->_sections['poem']['index']]['approved'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%b %d %Y at %I:%M %p') : smarty_modifier_date_format($_tmp, '%b %d %Y at %I:%M %p')); ?>
 by 
            <?php if ($this->_tpl_vars['poems'][$this->_sections['poem']['index']]['anonymous']): ?><i>Anonymous</i><?php else: ?><a href="/users/<?php echo ((is_array($_tmp=$this->_tpl_vars['poems'][$this->_sections['poem']['index']]['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['username']; ?>
</a><?php endif; ?> in <a href="/categories/<?php echo ((is_array($_tmp=$this->_tpl_vars['poems'][$this->_sections['poem']['index']]['category'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['category']; ?>
</a>
          </div>
	 <a href="/poems/<?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['comments']; ?>
 comment<?php if ($this->_tpl_vars['poems'][$this->_sections['poem']['index']]['comments'] != 1): ?>s<?php endif; ?></a> | <a class="rate" href="#" like="<?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['likes']; ?>
 like<?php if ($this->_tpl_vars['poems'][$this->_sections['poem']['index']]['likes'] != 1): ?>s<?php endif; ?></a>
        
          <?php if ($this->_tpl_vars['user']->isValid() && $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['uid'] != $this->_tpl_vars['user']->getInfo('id')): ?> 
          
 | 
 <?php if ($this->_tpl_vars['user']->hasAuthorFavorited($this->_tpl_vars['poems'][$this->_sections['poem']['index']]['uid'])): ?>
<a href="#" class="removeAuthor" author="<?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['uid']; ?>
">Remove favorite author</a>
<?php else: ?>
<a href="#" class="addAuthor" author="<?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['uid']; ?>
">Add favorite author</a>
<?php endif; ?> | 
 <?php if ($this->_tpl_vars['user']->hasHaikuFavorited($this->_tpl_vars['poems'][$this->_sections['poem']['index']]['id'])): ?>
<a href="#" class="removeHaiku" haiku="<?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['id']; ?>
">Remove favorite haiku</a>
<?php else: ?>
<a href="#" class="addHaiku" haiku="<?php echo $this->_tpl_vars['poems'][$this->_sections['poem']['index']]['id']; ?>
">Add favorite haiku</a>
<?php endif; ?>
<?php endif; ?>
<br>
    </div>
</div>
</div> <br />

<?php endfor; endif; ?>