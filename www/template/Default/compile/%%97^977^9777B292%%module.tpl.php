<?php /* Smarty version 2.6.12, created on 2010-05-14 12:25:04
         compiled from Z:/home/buket/www/module/TextPage/module.tpl */ ?>
<h1><?php echo $this->_tpl_vars['Title']; ?>
</h1>
<?php if (! empty ( $this->_tpl_vars['Data']['Text'] )): ?>
<?php echo $this->_tpl_vars['Data']['Text']; ?>

<?php else: ?>
<div>Раздел в разработке</div>
<?php endif; ?>