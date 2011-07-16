
<script language="JavaScript" type="text/javascript">
    function edit(Id)
    {
        window.location.href = "{$PageAddress}?Id=" + Id;
        return false;
    }
</script>
<table style="margin-bottom:20px;width:100%;"><tr>
        <td style="width:100px">Производитель:</td>
        <td style="width:200px">
            <select name="manufacturers" onchange="this.form.submit()">
                <option></option>
                {foreach item=Manufacturer from=$Data.Manufacturers}
                {if isset($Data.ThisManufacturer) && $Data.ThisManufacturer==$Manufacturer.ManufacturerId}
                <option value="{$Manufacturer.ManufacturerId}" selected>{$Manufacturer.Name}</option>
                {else}
                <option value="{$Manufacturer.ManufacturerId}">{$Manufacturer.Name}</option>
                {/if}
                {/foreach}
            </select></td>
        <td>&nbsp;</td>
        <td style="width:180px"><input name="specsearch" value="{if isset($Data.SpecSearch)}{$Data.SpecSearch}{/if}"></td>
        <td style="width:100px"><input type="submit" value="Искать"></td>
    </tr></table>
{if count($Data.List)>0}
<table class="table_basket" width="100%">
    <tr>
        <th width="20">№<br>п/п</th>
        <th>Код производителя</th>
        <th>Производитель</th>
        <th>Модель, артикул</th>
        <th >Наименование, краткие характеристики</th>
        <th>Цена</th>
        <th>Цена со скидкой</th>
        <th>Процент скидки</th>
        <th>Дата окончания акции</th>
        <th>&nbsp;</th>
    </tr>
	{php}
		$i = 1;
	{/php}
    {foreach item=Item from=$Data.List}
	
    <tr>
        <td width="20">{$Item.Index}</td>
        <td>{$Item.Good.TrueCode}</td>
        <td>{$Item.ManufacturerName}</td>
        <td><a title='Перейти на страницу товара' href='{$Item.Url}'>{$Item.Good.Code}</a></td>
        <td style="width: 200px !important;">{$Item.Good.Title}<br>{$Item.Good.Description|truncate:80:"..."}</td>
        <td>{$Item.Good.Price}</td>
        <td>{$Item.PriceWithDiscount}</td>
        <td style="width: 80px !important;">{$Item.Discount}</td>
        <td style="width: 150px !important;">{$Item.DateEnd}</td>        
        <td align="center" width="30">
            {if !isset($Item.AddedToCart)}
            <p><input type="submit" name="handlerBtnAdd:{$Item.GoodId}" class="button" value="" style="height:25px;width:25px;cursor:pointer;background:url(/img/cart.gif) no-repeat;padding-left:25px;color:#67afff;border:none;"/>
	 {else}
            <p>{$Item.AddedToCart}
	 {/if}</td></tr>
    {/foreach}
</table>

{$Data.Pager}
{else}
Спецпредложения не найдены
{/if}