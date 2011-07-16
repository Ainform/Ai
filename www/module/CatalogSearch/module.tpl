<div style="margin:20px 0px 20px 0px;">
    <form action="" method="get">
    <input type="text" name="q" value="{$smarty.get.q}"><input type="submit" value="Найти товар" />
    </form>
    </div>
{if $Data.GoodsList}
{foreach item=Good from=$Data.GoodsList key=i}
<div class="good_div">
    <div class="good_image">
        {if $Good.Image!=""}
        <a href="{goodLink id=$Good.GoodId}">
            <img src="{$Good.Image}&width=111&height=111&crop=1"  alt="{$Good.Title}" style="border:none;"/>
        </a>
        {else}
        <a href="{goodLink id=$Good.GoodId}">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        {/if}
    </div>
    <p class="good_title">{$Good.Title}</p>
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price">{$Good.Price}</span>&nbsp;руб.</p>
        {if !isset($Data.AddedToCart[$Good.GoodId])}
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:{$Good.GoodId}" class="button" value="Заказать"/>
        {else}
    <p class="hello">{$Data.AddedToCart[$Good.GoodId]}
        {/if}
</div>
{/foreach}
{/if}