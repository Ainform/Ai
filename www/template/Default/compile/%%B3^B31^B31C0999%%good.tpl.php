<?php /* Smarty version 2.6.12, created on 2011-04-05 00:36:22
         compiled from /home/u197068/buketufa.ru/www/module/Catalog/good.tpl */ ?>
<?php echo $this->_tpl_vars['BreadCrumbs']; ?>

<br />
<div class="good_page_left">
	 <?php if (isset ( $this->_tpl_vars['Data']['Good']['Images'] )): ?>
	 <?php $_from = $this->_tpl_vars['Data']['Good']['Images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Image']):
?>
    <div class="good_image">
        <?php if (isset ( $this->_tpl_vars['Image'] )): ?>
        <a href="<?php echo $this->_tpl_vars['Image']['Path']; ?>
" rel="shadowbox[goodimages];options={animate: false}" title='<?php echo $this->_tpl_vars['Image']['Title']; ?>
'>
            <img src="<?php echo $this->_tpl_vars['Image']['Path']; ?>
&width=111&height=111&crop=1"  alt="<?php echo $this->_tpl_vars['Image']['Title']; ?>
" border=none/>
        </a>
        <?php endif; ?>
    </div>
    <?php endforeach; endif; unset($_from); ?>
    <?php else: ?>
    <div class="good_image">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
    </div>
    <?php endif; ?>
</div>
<div>
    <p>Цена: <span style="padding-left: 0px;" class="good_price"><?php echo $this->_tpl_vars['Data']['Good']['Price']; ?>
</span> руб.</p>
    <p>Описание:</p>
    <p><?php echo $this->_tpl_vars['Data']['Good']['Abstract']; ?>
</p>
    <?php if (isset ( $this->_tpl_vars['Data']['AddedToCart'] )): ?>
    <?php echo $this->_tpl_vars['Data']['AddedToCart']; ?>

    <?php else: ?>
    <p class="good_buy">
        <input type="submit" name="handlerBtnAdd" class="button" value="Заказать" />
    </p>
    <?php endif; ?>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../media.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>