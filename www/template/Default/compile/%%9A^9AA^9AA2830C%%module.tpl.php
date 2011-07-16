<?php /* Smarty version 2.6.12, created on 2011-07-08 22:47:33
         compiled from V:/home/buket_debug/www/module/TextPage/module.tpl */ ?>
<h1 class="textpage_title"><?php echo $this->_tpl_vars['Title']; ?>
</h1>
<?php if (! empty ( $this->_tpl_vars['Data']['Text'] )): ?>
<?php echo $this->_tpl_vars['Data']['Text']; ?>

<?php else: ?>
<div>Раздел в разработке</div>
<?php endif; ?>