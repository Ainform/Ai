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
<h1>Оформление товара &mdash; Шаг 1</h1> <a href="javascript:history.go(-1)">Вернуться к выбору товаров</a>
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
{*}
{if !isset($username)}
<table class="cart_form">
    <tr>
        <td width="160" align="left">Контактное лицо:</td>
        <td width="200">{TextBox id="txtOrderName" maxlength="100" class="text_box_contact" value=""}</td>
        <td class="cart_validator">{Validator for="txtOrderName" rule="NotNull" message="Введите имя"}</td>
    </tr>
    <tr>
        <td align="left">Контактный телефон:</td>
        <td>{TextBox id="txtOrderPhone" maxlength="100" class="text_box_contact" value=""}</td>
        <td class="cart_validator">{Validator for="txtOrderPhone" rule="NotNull" message="Введите номер телефона"}</td>
    </tr>
    <tr>
        <td align="left">E-mail:</td>
        <td>{TextBox id="txtOrderEmail" maxlength="100" class="text_box_contact" value=""}</td>
        <td class="cart_validator">{Validator for="txtOrderEmail" rule="NotNull Email" message="Введите e-mail"}</td>
    </tr>
</table>
{/if}

<p style="padding:0!important">Дополнительные пожелания и комментарии:</p>
<textarea cols="50" rows="7" name="additional" class="comments"></textarea>



<table class="cart_buttons">
    <tr>
        <td><input class="button" type="submit" value="Очистить" name="handlerClearCart" /></td>
        <td><input class="button" type="submit" value="Оплатить" name="handlerMakeOrder"  /></td>
    </tr>
</table>
{*}
<div style="visibility:hidden"><input id="recalculate" class="button" type="submit" value="Пересчитать" name="handlerRecalculate" /></div>
<h1>Открытка к букету</h1>
<input type="checkbox" name="postcard" id="postcard"/ {if isset($smarty.session.postcard)}checked="checked"{/if}><label for="postcard">Добавить открытку к заказу (бесплатно)</label>
<div style="clear:both;"></div>
<div style="float:left;width:45%;margin:20px 0px;">
    <textarea style="width:100%;height:100px;" name="postcard_text">
{$smarty.session.postcard_text}
    </textarea></div>
<div style="float:right;width:50%;margin:20px 0px;">
    <p>Напишите текст своего поздравления к открытке.</p>
    <p>Если вы не подпишите открытку, получатель не будет знать от кого подарок.</p></div>
<div style="clear:both">
    <p>Также вы можете выбрать одну из открыток ручной работы из нашего <a href="/internet-magazin/326/">каталога</a> и добавить её к заказу (платная опция, зависит от цены открытки)</p>
</div>

{if $Data.cardslist}
{foreach item=Good from=$Data.cardslist key=i}
<div class="good_div">
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
    <p class="good_title">{$Good.Title}</p>
    {if $Good.Price==0}
    <p class="good_price">цена не указана</p>
    {else}
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price">{$Good.Price}</span>&nbsp;руб.</p>
    {/if}
    {if !isset($Data.AddedToCart[$Good.GoodId])}
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:{$Good.GoodId}" class="button" value="Заказать"/>
        {else}
    <p class="hello">{$Data.AddedToCart[$Good.GoodId]}
        {/if}
</div>
{/foreach}
<div style="clear:both"></div>
	{$Data.Pager}
{/if}
<h1>Дополнение к букету</h1>
<p>Дополнением к выбранному вам товару может стать любой букет или подарок из каталога продукции.</p>

{if $Data.giftslist}
{foreach item=Good from=$Data.giftslist key=i}
<div class="good_div">
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
    <p class="good_title">{$Good.Title}</p>
    {if $Good.Price==0}
    <p class="good_price">цена не указана</p>
    {else}
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price">{$Good.Price}</span>&nbsp;руб.</p>
    {/if}
    {if !isset($Data.AddedToCart[$Good.GoodId])}
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:{$Good.GoodId}" class="button" value="Заказать"/>
        {else}
    <p class="hello">{$Data.AddedToCart[$Good.GoodId]}
        {/if}
</div>
{/foreach}
<div style="clear:both"></div>
	{$Data.Pager}
{/if}
<table class="cart_buttons">
    <tr>
        <td><input type="submit" class="button next_step" name="handlerStep2Delivery" value="К шагу 2 »" /></td>
    </tr>
</table>

{else}
	{if empty($Data.HeadText)}
<p>В корзине пока нет товаров.</p>
	{else}
		{ $Data.HeadText }
	{/if}

{/if}
