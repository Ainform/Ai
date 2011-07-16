<?php /* Smarty version Smarty-3.0.8, created on 2011-07-16 20:13:05
         compiled from "V:/home/buket_debug/www/module/Cart/module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:225564e219c710e64f6-26273992%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3598ffe40e6c74043bacdacaec98f2754f37001f' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Cart/module.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '225564e219c710e64f6-26273992',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_math')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.math.php';
?><script>
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

<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['GoodsList'])&&count($_smarty_tpl->getVariable('Data')->value['GoodsList'])>0&&empty($_smarty_tpl->getVariable('Data',null,true,false)->value['HeadText'])){?>
<h1>Оформление товара &mdash; Шаг 1</h1> <a href="javascript:history.go(-1)">Вернуться к выбору товаров</a>
<h1>Состав заказа</h1>

<?php echo $_smarty_tpl->getVariable('Data')->value['HeadText'];?>


<table class="table_basket">
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Кол-во</th>
        <th class="table_cart_price">Цена, руб. за&nbsp;ед.</th>
        <th>Сумма</th>
	<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['User'])&&$_smarty_tpl->getVariable('Data')->value['User']['Partner']==1){?>
        <th>Сумма со скидкой</th>
	<?php }?>
        <th>&nbsp;</th>
    </tr>
	<?php $_smarty_tpl->tpl_vars["commonPrice"] = new Smarty_variable(0, null, null);?>
	<?php $_smarty_tpl->tpl_vars["commonPriceWithDiscount"] = new Smarty_variable(0, null, null);?>
	<?php  $_smarty_tpl->tpl_vars['Good'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Good']->key => $_smarty_tpl->tpl_vars['Good']->value){
?>
    <tr>
        <td>
            <div class="good_image">
        <?php if ($_smarty_tpl->tpl_vars['Good']->value['Image']!=''){?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
" title='<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
'>
            <img src="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Image'];?>
&width=111&height=111&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" style="border:none;"/>
        </a>
        <?php }else{ ?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        <?php }?>
    </div>
        </td>
        <td class="table_cart_good_title"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
</td>
        <td><input type="text" size="3" value="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Count'];?>
" name="count<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" onchange="$('#recalculate').trigger('click');"/></td>
        <td id="one_price"><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['Good']->value['Price']);?>
</td>
        <td id="some_price"><?php echo smarty_function_math(array('equation'=>"x * y",'x'=>$_smarty_tpl->tpl_vars['Good']->value['Price'],'y'=>$_smarty_tpl->tpl_vars['Good']->value['Count'],'format'=>"%.2f"),$_smarty_tpl);?>
</td>
		<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['User'])&&$_smarty_tpl->getVariable('Data')->value['User']['Partner']==1){?>
        <td><?php echo smarty_function_math(array('equation'=>"x * y",'x'=>$_smarty_tpl->tpl_vars['Good']->value['PriceWithDiscount'],'y'=>$_smarty_tpl->tpl_vars['Good']->value['Count'],'format'=>"%.2f"),$_smarty_tpl);?>
</td>
		<?php }?>
        <td>
            <input type="image" src="/img/admin/close_16.png" name="handlerBtnDelete:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" title="Удалить из корзины" onclick="return confirm('Удалить?');" />
        </td>
    </tr>
		<?php echo smarty_function_math(array('equation'=>"sum+price*count",'sum'=>$_smarty_tpl->getVariable('commonPrice')->value,'price'=>$_smarty_tpl->tpl_vars['Good']->value['Price'],'count'=>$_smarty_tpl->tpl_vars['Good']->value['Count'],'assign'=>"commonPrice"),$_smarty_tpl);?>

		<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['User'])&&$_smarty_tpl->getVariable('Data')->value['User']['Partner']==1){?>
		<?php echo smarty_function_math(array('equation'=>"sum+price*count",'sum'=>$_smarty_tpl->getVariable('commonPriceWithDiscount')->value,'price'=>$_smarty_tpl->tpl_vars['Good']->value['PriceWithDiscount'],'count'=>$_smarty_tpl->tpl_vars['Good']->value['Count'],'assign'=>"commonPriceWithDiscount"),$_smarty_tpl);?>

		<?php }?>
	<?php }} ?>
    <tr>
		<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['User'])&&$_smarty_tpl->getVariable('Data')->value['User']['Partner']==1){?>
        <td class="left_bottom"></td>
        <td class="itogo" style="text-align: right;" colspan="3">Итого, <span id="current_currency">руб.</span> :</td>
        <td id="current_price"><b><?php echo sprintf("%.2f",$_smarty_tpl->getVariable('commonPriceWithDiscount')->value);?>
</b></td>
		<?php }else{ ?>
        <td class="left_bottom"></td>
        <td class="itogo" style="text-align: right;" colspan="3">Итого, <span id="current_currency">руб.</span> :</td>
        <td id="current_price"><?php echo sprintf("%.2f",$_smarty_tpl->getVariable('commonPrice')->value);?>
</td>
		<?php }?>
    </tr>
</table>
<div style="visibility:hidden"><input id="recalculate" class="button" type="submit" value="Пересчитать" name="handlerRecalculate" /></div>
<h1>Открытка к букету</h1>
<input type="checkbox" name="postcard" id="postcard"/ <?php if (isset($_SESSION['postcard'])){?>checked="checked"<?php }?>><label for="postcard">Добавить открытку к заказу (бесплатно)</label>
<div style="clear:both;"></div>
<div style="float:left;width:45%;margin:20px 0px;">
    <textarea style="width:100%;height:100px;" name="postcard_text">
<?php echo $_SESSION['postcard_text'];?>

    </textarea></div>
<div style="float:right;width:50%;margin:20px 0px;">
    <p>Напишите текст своего поздравления к открытке.</p>
    <p>Если вы не подпишите открытку, получатель не будет знать от кого подарок.</p></div>
<div style="clear:both">
    <p>Также вы можете выбрать одну из открыток ручной работы из нашего <a href="/internet-magazin/326/">каталога</a> и добавить её к заказу (платная опция, зависит от цены открытки)</p>
</div>

<?php if ($_smarty_tpl->getVariable('Data')->value['cardslist']){?>
<?php  $_smarty_tpl->tpl_vars['Good'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['cardslist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Good']->key => $_smarty_tpl->tpl_vars['Good']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Good']->key;
?>
<div class="good_div">
    <div class="good_image">
        <?php if ($_smarty_tpl->tpl_vars['Good']->value['Image']!=''){?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
" title='<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
'>
            <img src="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Image'];?>
&width=111&height=111&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" style="border:none;"/>
        </a>
        <?php }else{ ?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        <?php }?>
    </div>
    <p class="good_title"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
</p>
    <?php if ($_smarty_tpl->tpl_vars['Good']->value['Price']==0){?>
    <p class="good_price">цена не указана</p>
    <?php }else{ ?>
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Price'];?>
</span>&nbsp;руб.</p>
    <?php }?>
    <?php if (!isset($_smarty_tpl->getVariable('Data',null,true,false)->value['AddedToCart'][$_smarty_tpl->tpl_vars['Good']->value['GoodId']])){?>
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" class="button" value="Заказать"/>
        <?php }else{ ?>
    <p class="hello"><?php echo $_smarty_tpl->getVariable('Data')->value['AddedToCart'][$_smarty_tpl->tpl_vars['Good']->value['GoodId']];?>

        <?php }?>
</div>
<?php }} ?>
<div style="clear:both"></div>
	<?php echo $_smarty_tpl->getVariable('Data')->value['Pager'];?>

<?php }?>
<h1>Дополнение к букету</h1>
<p>Дополнением к выбранному вам товару может стать любой букет или подарок из каталога продукции.</p>

<?php if ($_smarty_tpl->getVariable('Data')->value['giftslist']){?>
<?php  $_smarty_tpl->tpl_vars['Good'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['giftslist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Good']->key => $_smarty_tpl->tpl_vars['Good']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Good']->key;
?>
<div class="good_div">
    <div class="good_image">
        <?php if ($_smarty_tpl->tpl_vars['Good']->value['Image']!=''){?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
" title='<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
'>
            <img src="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Image'];?>
&width=111&height=111&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" style="border:none;"/>
        </a>
        <?php }else{ ?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        <?php }?>
    </div>
    <p class="good_title"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
</p>
    <?php if ($_smarty_tpl->tpl_vars['Good']->value['Price']==0){?>
    <p class="good_price">цена не указана</p>
    <?php }else{ ?>
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Price'];?>
</span>&nbsp;руб.</p>
    <?php }?>
    <?php if (!isset($_smarty_tpl->getVariable('Data',null,true,false)->value['AddedToCart'][$_smarty_tpl->tpl_vars['Good']->value['GoodId']])){?>
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" class="button" value="Заказать"/>
        <?php }else{ ?>
    <p class="hello"><?php echo $_smarty_tpl->getVariable('Data')->value['AddedToCart'][$_smarty_tpl->tpl_vars['Good']->value['GoodId']];?>

        <?php }?>
</div>
<?php }} ?>
<div style="clear:both"></div>
	<?php echo $_smarty_tpl->getVariable('Data')->value['Pager'];?>

<?php }?>
<table class="cart_buttons">
    <tr>
        <td><input type="submit" class="button next_step" name="handlerStep2Delivery" value="К шагу 2 »" /></td>
    </tr>
</table>

<?php }else{ ?>
	<?php if (empty($_smarty_tpl->getVariable('Data',null,true,false)->value['HeadText'])){?>
<p>В корзине пока нет товаров.</p>
	<?php }else{ ?>
		{ $Data.HeadText }
	<?php }?>

<?php }?>
