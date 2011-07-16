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

{if isset($Data.GoodsList) && count($Data.GoodsList) > 0 && empty($Data.HeadText)}
<h1>Проверка заказа &mdash; Шаг 3</h1> <a href="/cart/?step=delivery">Вернуться ко второму шагу</a>
<h1>Состав заказа</h1>

{$Data.HeadText}

<table class="table_basket">
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Кол-во</th>
        <th class="table_cart_price">Цена, руб. за&nbsp;ед.</th>
        <th>Сумма</th>
	{if isset($Data.User) && $Data.User.Partner==1}
        <th>Сумма со скидкой</th>
	{/if}
        <th>&nbsp;</th>
    </tr>
	{assign var="commonPrice" value=0}
	{assign var="commonPriceWithDiscount" value=0}
	{foreach item=Good from=$Data.GoodsList name=goods}
    <tr>
        <td>
            <div class="good_image">
                {if $Good.Image!=""}
                <a href="{goodLink id=$Good.GoodId}" title='{$Good.Title}'>
                    <img src="{$Good.Image}&width=111&height=111&crop=1"  alt="{$Good.Title}" style="border:none;"/>
                </a>
                {else}
                <a href="{goodLink id=$Good.GoodId}">
                    <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
                </a>
                {/if}
            </div>
        </td>
        <td class="table_cart_good_title">{$Good.Title}</td>
        <td><input type="text" size="3" value="{$Good.Count}" name="count{$Good.GoodId}" onchange="$('#recalculate').trigger('click');"/></td>
        <td id="one_price">{$Good.Price|string_format:"%.2f"}</td>
        <td id="some_price">{math equation="x * y" x=$Good.Price y=$Good.Count format="%.2f"}</td>
		{if isset($Data.User) && $Data.User.Partner==1}
        <td>{math equation="x * y" x=$Good.PriceWithDiscount y=$Good.Count format="%.2f"}</td>
		{/if}
        <td>
            <input type="image" src="/img/admin/close_16.png" name="handlerBtnDelete:{$Good.GoodId}" title="Удалить из корзины" onclick="return confirm('Удалить?');" />
        </td>
    </tr>
		{math equation="sum+price*count" sum=$commonPrice price=$Good.Price count=$Good.Count assign="commonPrice"}
		{if isset($Data.User) && $Data.User.Partner==1}
		{math equation="sum+price*count" sum=$commonPriceWithDiscount price=$Good.PriceWithDiscount count=$Good.Count assign="commonPriceWithDiscount"}
		{/if}
	{/foreach}
    <tr>
		{if isset($Data.User) && $Data.User.Partner==1}
        <td class="left_bottom"></td>
        <td class="itogo" style="text-align: right;" colspan="3">Итого, <span id="current_currency">руб.</span> :</td>
        <td id="current_price"><b>{$commonPriceWithDiscount|string_format:"%.2f"}</b></td>
		{else}
        <td class="left_bottom"></td>
        <td class="itogo" style="text-align: right;" colspan="3">Итого, <span id="current_currency">руб.</span> :</td>
        <td id="current_price">{$commonPrice|string_format:"%.2f"}</td>
		{/if}
    </tr>
</table>
<div style="visibility:hidden"><input id="recalculate" class="button" type="submit" value="Пересчитать" name="handlerRecalculate" /></div>
<h1>Получатель букета</h1>
<table style="color:#55493F;">
    <tr><td style="width:150px;"><label for="fio">ФИО</label></td><td>{$smarty.session.recipient_fio}</td></tr>
    <tr><td style="width:150px;"><label>Телефон</label></td><td>{$smarty.session.recipient_phone}</td></tr>
    <tr><td style="width:150px;">Полный адрес и пожелания по доставке</td><td>
            {$smarty.session.address_full}</td></tr>
    <tr><td style="width:150px;">Дата доставки</td><td>{$smarty.session.address_date}</td></tr>
    <tr><td style="width:150px;">Время доставки</td><td>{$smarty.session.address_time}</td></tr>
</table>

<h1>Ваши контактные данные</h1>
<table style="width:50%;color:#55493F;">
    <tr><td style="width:150px;">ФИО</td><td>{$smarty.session.contact_fio}</td></tr>
    <tr><td style="width:150px;">Телефон</td><td>{$smarty.session.contact_phone}</td></tr>
    <tr><td style="width:150px;">Емейл</td><td>{$smarty.session.contact_email}</td></tr>
    <tr><td style="width:150px;">Дата рождения</td><td>{$smarty.session.contact_birthdate}</td></tr>
</table>
<p><input type="checkbox" name="discounts_and_actions" id="discounts_and_actions" {if isset($smarty.session.discounts_and_actions)}checked="checked"{/if} disabled/>
    <label for="discounts_and_actions">Хочу получать информацию о скидках и акциях</label></p>
<p><input type="checkbox" name="card" id="card" {if isset($smarty.session.card)}checked="checked"{/if} disabled/>
    <label for="card">Оформить карту почётного гостя</label></p>
<p><input type="checkbox" name="delivery" id="delivery" {if isset($smarty.session.delivery)}checked="checked"{/if} disabled/>
    <label for="delivery">Я согласен с <a href="#">условиями доставки</a></label></p>
</div>
<table class="cart_buttons">
    <tr>
        <td><input class="button" type="submit" value="Оплатить" name="handlerMakeOrder"  /></td>
    </tr>
</table>
{else}
	{if empty($Data.HeadText)}
<p>В корзине пока нет товаров.</p>
	{else}
		{ $Data.HeadText }
	{/if}

{/if}
