<?php /* Smarty version 2.6.12, created on 2011-04-11 10:41:04
         compiled from V:/home/buket_new/www/module/TextPage/module.tpl */ ?>
<h1><?php echo $this->_tpl_vars['Title']; ?>
</h1>
<?php if (! empty ( $this->_tpl_vars['Data']['Text'] )): ?>
<?php echo $this->_tpl_vars['Data']['Text']; ?>

<?php else: ?>
<div>Раздел в разработке</div>
<?php endif; ?>