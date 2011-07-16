<?php /* Smarty version 2.6.12, created on 2011-07-03 15:14:19
         compiled from /home/u184419/dev2.ainform.com/www/module/Cart/step_2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'goodLink', '/home/u184419/dev2.ainform.com/www/module/Cart/step_2.tpl', 51, false),array('function', 'math', '/home/u184419/dev2.ainform.com/www/module/Cart/step_2.tpl', 64, false),array('modifier', 'string_format', '/home/u184419/dev2.ainform.com/www/module/Cart/step_2.tpl', 63, false),)), $this); ?>
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
<h1>Проверка заказа &mdash; Шаг 3</h1> <a href="/cart/?step=delivery">Вернуться ко второму шагу</a>
<h1>Состав заказа</h1>

<?php echo $this->_tpl_vars['Data']['HeadText']; ?>


<table class="table_basket">
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Кол-во</th>
        <th class="table_cart_price">Цена, руб. за&nbsp;ед.</th>
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
        <td>
            <div class="good_image">
                <?php if ($this->_tpl_vars['Good']['Image'] != ""): ?>
                <a href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Good']['GoodId']), $this);?>
" title='<?php echo $this->_tpl_vars['Good']['Title']; ?>
'>
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
        </td>
        <td class="table_cart_good_title"><?php echo $this->_tpl_vars['Good']['Title']; ?>
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

<h1>Получатель букета</h1>
<table style="color:#55493F;">
    <tr><td style="width:150px;"><label for="fio">ФИО</label></td><td><?php echo $_SESSION['recipient_fio']; ?>
</td></tr>
    <tr><td style="width:150px;"><label>Телефон</label></td><td><?php echo $_SESSION['recipient_phone']; ?>
</td></tr>
    <tr><td style="width:150px;">Полный адрес и пожелания по доставке</td><td>
            <?php echo $_SESSION['address_full']; ?>
</td></tr>
    <tr><td style="width:150px;">Дата доставки</td><td><?php echo $_SESSION['address_date']; ?>
</td></tr>
    <tr><td style="width:150px;">Время доставки</td><td><?php echo $_SESSION['address_time']; ?>
</td></tr>
</table>

<h1>Ваши контактные данные</h1>
<table style="width:50%;color:#55493F;">
    <tr><td style="width:150px;">ФИО</td><td><?php echo $_SESSION['contact_fio']; ?>
</td></tr>
    <tr><td style="width:150px;">Телефон</td><td><?php echo $_SESSION['contact_phone']; ?>
</td></tr>
    <tr><td style="width:150px;">Емейл</td><td><?php echo $_SESSION['contact_email']; ?>
</td></tr>
    <tr><td style="width:150px;">Дата рождения</td><td><?php echo $_SESSION['contact_birthdate']; ?>
</td></tr>
</table>
<p><input type="checkbox" name="discounts_and_actions" id="discounts_and_actions" <?php if (isset ( $_SESSION['discounts_and_actions'] )): ?>checked="checked"<?php endif; ?> disabled/>
    <label for="discounts_and_actions">Хочу получать информацию о скидках и акциях</label></p>
<p><input type="checkbox" name="card" id="card" <?php if (isset ( $_SESSION['card'] )): ?>checked="checked"<?php endif; ?> disabled/>
    <label for="card">Оформить карту почётного гостя</label></p>
<p><input type="checkbox" name="delivery" id="delivery" <?php if (isset ( $_SESSION['delivery'] )): ?>checked="checked"<?php endif; ?> disabled/>
    <label for="delivery">Я согласен с <a href="#">условиями доставки</a></label></p>
</div>
<table class="cart_buttons">
    <tr>
        <td><input class="button" type="submit" value="Оплатить" name="handlerMakeOrder"  /></td>
    </tr>
</table>
<?php else: ?>
	<?php if (empty ( $this->_tpl_vars['Data']['HeadText'] )): ?>
<p>В корзине пока нет товаров.</p>
	<?php else: ?>
		<?php echo $this->_tpl_vars['Data']['HeadText']; ?>

	<?php endif; ?>

<?php endif; ?>