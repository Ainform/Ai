<?php /* Smarty version 2.6.12, created on 2011-04-04 22:54:36
         compiled from /home/u197068/buketufa.ru/www/module/Cart/module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', '/home/u197068/buketufa.ru/www/module/Cart/module.tpl', 51, false),array('function', 'math', '/home/u197068/buketufa.ru/www/module/Cart/module.tpl', 52, false),array('function', 'TextBox', '/home/u197068/buketufa.ru/www/module/Cart/module.tpl', 82, false),array('function', 'Validator', '/home/u197068/buketufa.ru/www/module/Cart/module.tpl', 83, false),)), $this); ?>
<script>
    jQuery('document').ready(function(){
        jQuery('#rubl').click(function(){
            if(jQuery("#summwithdiscount").attr('value')!=''){
                res=jQuery("#summwithdiscount").attr('value')}
            else{
                res=jQuery("#summ").attr('value')
            }
            numObj = new Number(res)
            jQuery("#current_price").html(numObj.toFixed(2));
            jQuery("#current_currency").html('руб.');
        })
        jQuery('#dollar').click(function(){
            res=jQuery("#summ").attr('value')/jQuery("#dollar_rate").attr('value')
            jQuery("#current_price").html(res.toFixed(2));
            jQuery("#current_currency").html('долларов');
        })
        jQuery('#euro').click(function(){
            res=jQuery("#summ").attr('value')/jQuery("#euro_rate").attr('value')
            jQuery("#current_price").html(res.toFixed(2));
            jQuery("#current_currency").html('евро');
        })
    });
</script>

<?php if (isset ( $this->_tpl_vars['Data']['GoodsList'] ) && count ( $this->_tpl_vars['Data']['GoodsList'] ) > 0 && empty ( $this->_tpl_vars['Data']['HeadText'] )): ?>
<h1>Состав заказа</h1>
<p><a href="javascript:history.go(-1)">Вернуться к выбору товаров</a></p>

<?php echo $this->_tpl_vars['Data']['HeadText']; ?>


<table class="table_basket">
    <tr>
        <th>№</th>
        <th>Наименование</th>
        <th>Кол-во</th>
        <th>Цена, руб. за&nbsp;ед.</th>
        <th>Сумма</th>
	<?php if (isset ( $this->_tpl_vars['Data']['User'] ) && $this->_tpl_vars['Data']['User']['Partner'] == 1): ?>
        <th>Сумма со скидкой</th>
	<?php endif; ?>
        <th>&nbsp;</th>
    </tr>
	<?php $this->assign('commonPrice', 0); ?>
	<?php $this->assign('commonPriceWithDiscount', 0); ?>
	<?php $_from = $this->_tpl_vars['Data']['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Good']):
        $this->_foreach['goods']['iteration']++;
?>
    <tr>
        <td><?php echo ($this->_foreach['goods']['iteration']-1); ?>
</td>
        <td><?php echo $this->_tpl_vars['Good']['Title']; ?>
</td>
        <td><input type="text" size="3" value="<?php echo $this->_tpl_vars['Good']['Count']; ?>
" name="count<?php echo $this->_tpl_vars['Good']['GoodId']; ?>
" onchange="$('#recalculate').trigger('click');"/></td>
        <td id="one_price"><?php echo ((is_array($_tmp=$this->_tpl_vars['Good']['Price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
        <td id="some_price"><?php echo smarty_function_math(array('equation' => "x * y",'x' => $this->_tpl_vars['Good']['Price'],'y' => $this->_tpl_vars['Good']['Count'],'format' => "%.2f"), $this);?>
</td>
		<?php if (isset ( $this->_tpl_vars['Data']['User'] ) && $this->_tpl_vars['Data']['User']['Partner'] == 1): ?>
        <td><?php echo smarty_function_math(array('equation' => "x * y",'x' => $this->_tpl_vars['Good']['PriceWithDiscount'],'y' => $this->_tpl_vars['Good']['Count'],'format' => "%.2f"), $this);?>
</td>
		<?php endif; ?>
        <td>
            <input type="image" src="/img/admin/close_16.png" name="handlerBtnDelete:<?php echo $this->_tpl_vars['Good']['GoodId']; ?>
" title="Удалить из корзины" onclick="return confirm('Удалить?');" />
        </td>
    </tr>
		<?php echo smarty_function_math(array('equation' => "sum+price*count",'sum' => $this->_tpl_vars['commonPrice'],'price' => $this->_tpl_vars['Good']['Price'],'count' => $this->_tpl_vars['Good']['Count'],'assign' => 'commonPrice'), $this);?>

		<?php if (isset ( $this->_tpl_vars['Data']['User'] ) && $this->_tpl_vars['Data']['User']['Partner'] == 1): ?>
		<?php echo smarty_function_math(array('equation' => "sum+price*count",'sum' => $this->_tpl_vars['commonPriceWithDiscount'],'price' => $this->_tpl_vars['Good']['PriceWithDiscount'],'count' => $this->_tpl_vars['Good']['Count'],'assign' => 'commonPriceWithDiscount'), $this);?>

		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
    <tr>
		<?php if (isset ( $this->_tpl_vars['Data']['User'] ) && $this->_tpl_vars['Data']['User']['Partner'] == 1): ?>
        <td class="left_bottom"></td>
        <td class="itogo" style="text-align: right;" colspan="3">Итого, <span id="current_currency">руб.</span> :</td>
        <td id="current_price"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['commonPriceWithDiscount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</b></td>
		<?php else: ?>
        <td class="left_bottom"></td>
        <td class="itogo" style="text-align: right;" colspan="3">Итого, <span id="current_currency">руб.</span> :</td>
        <td id="current_price"><?php echo ((is_array($_tmp=$this->_tpl_vars['commonPrice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
		<?php endif; ?>
    </tr>
</table>

<?php if (! isset ( $this->_tpl_vars['username'] )): ?>
    <table class="cart_form">
        <tr>
            <td width="160" align="left">Контактное лицо:</td>
            <td width="200"><?php echo smarty_function_TextBox(array('id' => 'txtOrderName','maxlength' => '100','class' => 'text_box_contact','value' => ""), $this);?>
</td>
            <td class="cart_validator"><?php echo smarty_function_Validator(array('for' => 'txtOrderName','rule' => 'NotNull','message' => "Введите имя"), $this);?>
</td>
        </tr>
        <tr>
            <td align="left">Контактный телефон:</td>
            <td><?php echo smarty_function_TextBox(array('id' => 'txtOrderPhone','maxlength' => '100','class' => 'text_box_contact','value' => ""), $this);?>
</td>
            <td class="cart_validator"><?php echo smarty_function_Validator(array('for' => 'txtOrderPhone','rule' => 'NotNull','message' => "Введите номер телефона"), $this);?>
</td>
        </tr>
        <tr>
            <td align="left">E-mail:</td>
            <td><?php echo smarty_function_TextBox(array('id' => 'txtOrderEmail','maxlength' => '100','class' => 'text_box_contact','value' => ""), $this);?>
</td>
            <td class="cart_validator"><?php echo smarty_function_Validator(array('for' => 'txtOrderEmail','rule' => 'NotNull Email','message' => "Введите e-mail"), $this);?>
</td>
        </tr>
    </table>
<?php endif; ?>

<p style="padding:0!important">Дополнительные пожелания и комментарии:</p>
<textarea cols="50" rows="7" name="additional" class="comments"></textarea>

<div style="visibility:hidden"><input id="recalculate" class="button" type="submit" value="Пересчитать" name="handlerRecalculate" /></div>
<table class="cart_buttons">
    <tr>
        <td><input class="button" type="submit" value="Очистить" name="handlerClearCart" /></td>
        <td><input class="button" type="submit" value="Заказать" name="handlerMakeOrder"  /></td>
    </tr>
</table>
<?php else: ?>
	<?php if (empty ( $this->_tpl_vars['Data']['HeadText'] )): ?>
		<p>В корзине пока нет товаров.</p>
	<?php else: ?>
		<?php echo $this->_tpl_vars['Data']['HeadText']; ?>

	<?php endif; ?>

<?php endif; ?>