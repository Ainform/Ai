<?php /* Smarty version 2.6.12, created on 2011-06-30 14:49:47
         compiled from /home/u184419/dev2.ainform.com/www/module/Catalog/good.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'goodLink', '/home/u184419/dev2.ainform.com/www/module/Catalog/good.tpl', 35, false),)), $this); ?>
<?php echo $this->_tpl_vars['BreadCrumbs']; ?>

<br />
<div class="good_page_left">
	 <?php if (isset ( $this->_tpl_vars['Data']['Good']['Images'] )): ?>
	 <?php $_from = $this->_tpl_vars['Data']['Good']['Images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Image']):
?>
    <div class="good_image_inside">
        <?php if (isset ( $this->_tpl_vars['Image'] )): ?>
        <a href="<?php echo $this->_tpl_vars['Image']['Path']; ?>
" rel="shadowbox[goodimages];options={animate: false}" title='<?php echo $this->_tpl_vars['Image']['Title']; ?>
'>
            <span>увеличить +</span>
            <img src="<?php echo $this->_tpl_vars['Image']['Path']; ?>
&width=250&height=350&prop=1"  alt="<?php echo $this->_tpl_vars['Image']['Title']; ?>
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
    <h1 class="goodtitle"><?php echo $this->_tpl_vars['Data']['Good']['Title']; ?>
</h1>
    <p>Цена: <span style="padding-left: 0px;" class="good_price"><?php echo $this->_tpl_vars['Data']['Good']['Price']; ?>
</span> руб.</p>
    <p>Описание:</p>
    <p><?php echo $this->_tpl_vars['Data']['Good']['Abstract']; ?>
</p>
    <?php if (isset ( $this->_tpl_vars['Data']['AddedToCart'][$this->_tpl_vars['Data']['Good']['GoodId']] )): ?>
    <?php echo $this->_tpl_vars['Data']['AddedToCart'][$this->_tpl_vars['Data']['Good']['GoodId']]; ?>

    <?php else: ?>
    <p class="good_buy">
        <input type="submit" name="handlerBtnAdd" class="button" value="Заказать" />
    </p>
    <?php endif; ?>
    <div class="nextprev">
        <?php if (isset ( $this->_tpl_vars['Data']['prevGood'] )): ?>
        <a class="prev" href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Data']['prevGood']['GoodId']), $this);?>
" >
            « Предыдущий
        </a>
        <?php endif; ?>
        <?php if (isset ( $this->_tpl_vars['Data']['nextGood'] )): ?>
        <a class="next" href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Data']['nextGood']['GoodId']), $this);?>
" >
            Следующий »
        </a>
        <?php endif; ?>
        <div style="clear:both;"></div>
    </div>

</div>

<?php if ($this->_tpl_vars['Data']['GoodsList']): ?>
<div class="goods_list">
    <?php $_from = $this->_tpl_vars['Data']['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['Gooditem']):
?>
    <div class="good_div">
        <div class="good_image">
            <?php if ($this->_tpl_vars['Gooditem']['Image'] != ""): ?>
            <a href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Gooditem']['GoodId']), $this);?>
" title='<?php echo $this->_tpl_vars['Gooditem']['Image']; ?>
'>
                <img src="<?php echo $this->_tpl_vars['Gooditem']['Image']; ?>
&width=111&height=111&crop=1"  alt="<?php echo $this->_tpl_vars['Gooditem']['Title']; ?>
" style="border:none;"/>
            </a>
            <?php else: ?>
            <a href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Gooditem']['GoodId']), $this);?>
">
                <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
            </a>
            <?php endif; ?>
        </div>
        <p class="good_title"><?php echo $this->_tpl_vars['Gooditem']['Title']; ?>
</p>
        <?php if ($this->_tpl_vars['Gooditem']['Price'] == 0): ?>
        <p class="good_price">цена не указана</p>
        <?php else: ?>
        <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price"><?php echo $this->_tpl_vars['Gooditem']['Price']; ?>
</span>&nbsp;руб.</p>
        <?php endif; ?>
        <?php if (empty ( $this->_tpl_vars['Data']['AddedToCart'][$this->_tpl_vars['Gooditem']['GoodId']] )): ?>
        <p class="good_buy"><input type="submit" name="handlerBtnAdd:<?php echo $this->_tpl_vars['Gooditem']['GoodId']; ?>
" class="button" value="Заказать"/>
            <?php else: ?>
        <p class="hello"><?php echo $this->_tpl_vars['Data']['AddedToCart'][$this->_tpl_vars['Gooditem']['GoodId']]; ?>

            <?php endif; ?>
    </div>
    <?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../media.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>