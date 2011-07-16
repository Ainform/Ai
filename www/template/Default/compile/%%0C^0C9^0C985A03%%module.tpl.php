<?php /* Smarty version 2.6.12, created on 2011-04-05 12:40:00
         compiled from /home/u197068/buketufa.ru/www/module/TextPage/module.tpl */ ?>
<h1><?php echo $this->_tpl_vars['Title']; ?>
</h1>
<?php if (! empty ( $this->_tpl_vars['Data']['Text'] )): ?>
<?php echo $this->_tpl_vars['Data']['Text']; ?>

<?php else: ?>
<div>Раздел в разработке</div>
<?php endif; ?>