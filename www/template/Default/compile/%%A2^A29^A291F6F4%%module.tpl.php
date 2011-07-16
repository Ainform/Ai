<?php /* Smarty version 2.6.12, created on 2011-05-04 23:14:30
         compiled from /home/u184419/dev2.ainform.com/www/module/CatalogSearch/module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'goodLink', '/home/u184419/dev2.ainform.com/www/module/CatalogSearch/module.tpl', 11, false),)), $this); ?>
<div style="margin:20px 0px 20px 0px;">
    <form action="" method="get">
    <input type="text" name="q" value="<?php echo $_GET['q']; ?>
"><input type="submit" value="Найти товар" />
    </form>
    </div>
<?php if ($this->_tpl_vars['Data']['GoodsList']): ?>
<?php $_from = $this->_tpl_vars['Data']['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['Good']):
?>
<div class="good_div">
    <div class="good_image">
        <?php if ($this->_tpl_vars['Good']['Image'] != ""): ?>
        <a href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Good']['GoodId']), $this);?>
">
            <img src="<?php echo $this->_tpl_vars['Good']['Image']; ?>
&width=111&height=111&crop=1"  alt="<?php echo $this->_tpl_vars['Good']['Title']; ?>
" style="border:none;"/>
        </a>
        <?php else: ?>
        <a href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Good']['GoodId']), $this);?>
">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        <?php endif; ?>
    </div>
    <p class="good_title"><?php echo $this->_tpl_vars['Good']['Title']; ?>
</p>
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price"><?php echo $this->_tpl_vars['Good']['Price']; ?>
</span>&nbsp;руб.</p>
        <?php if (! isset ( $this->_tpl_vars['Data']['AddedToCart'][$this->_tpl_vars['Good']['GoodId']] )): ?>
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:<?php echo $this->_tpl_vars['Good']['GoodId']; ?>
" class="button" value="Заказать"/>
        <?php else: ?>
    <p class="hello"><?php echo $this->_tpl_vars['Data']['AddedToCart'][$this->_tpl_vars['Good']['GoodId']]; ?>

        <?php endif; ?>
</div>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>