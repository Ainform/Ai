<?php /* Smarty version 2.6.12, created on 2011-07-12 13:13:38
         compiled from ../modules.tpl */ ?>

<?php $_from = $this->_tpl_vars['Modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Module']):
?>
	<?php $this->assign('Data', $this->_tpl_vars['Module']->getData()); ?>
	<?php if (isset ( $this->_tpl_vars['ShowOnlyThisModuleId'] )): ?>
		<?php if ($this->_tpl_vars['ShowOnlyThisModuleId'] == $this->_tpl_vars['Module']->moduleId): ?>
			<form method="post" action="" name="form<?php echo $this->_tpl_vars['Module']->moduleId; ?>
" id="form<?php echo $this->_tpl_vars['Module']->moduleId; ?>
" enctype="multipart/form-data">
				<input type="hidden" name="moduleId" value="<?php echo $this->_tpl_vars['Module']->moduleId; ?>
"/>
				<div class="module <?php echo $this->_tpl_vars['Module']->cssClass; ?>
">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['Module']->getTemplatePath(), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</div>
			</form>
		<?php endif; ?>
	<?php else: ?>
		<?php if (! isset ( $this->_tpl_vars['Module']->noform )): ?><form method="post" action="" name="form<?php echo $this->_tpl_vars['Module']->moduleId; ?>
" id="form<?php echo $this->_tpl_vars['Module']->moduleId; ?>
" enctype="multipart/form-data"><?php endif; ?>
			<input type="hidden" name="moduleId" value="<?php echo $this->_tpl_vars['Module']->moduleId; ?>
"/>
			<div class="module <?php echo $this->_tpl_vars['Module']->cssClass; ?>
">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['Module']->getTemplatePath(), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>
		<?php if (! isset ( $this->_tpl_vars['Module']->noform )): ?></form><?php endif; ?>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>