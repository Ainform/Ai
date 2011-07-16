{$BreadCrumbs}
{foreach item=Section from=$Data.SectionList key=i}
<div class="section_div">
    <div class="section_image">
        {if $Section.Image!=""}
         <a href="{sectionLink id=$Section.SectionId}"> <img src="{$Section.Image}?width=111&height=111&crop=1" alt="" style="border:none;"/></a>
        {else}
         <a href="{sectionLink id=$Section.SectionId}"> <img src="/css/images/no_foto.png" alt="" style="border:none;" /></a>
         {/if}
    </div>
    <div>
        <a class="section_title" href="{sectionLink id=$Section.SectionId}">{$Section.Title}</a>
    </div>
    <div style="clear:both;height:10px;"></div>
</div>
{/foreach}

{if !empty($Data.Description)}
<div style="padding-top: 15px" class="goods_description">
		{$Data.Description}
</div>
{/if}
{if $Data.GoodsList}
{foreach item=Good from=$Data.GoodsList key=i}
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

{if isset($Data.EmptyGoodListText)}
<div style="text-align: center;" class="text">{$Data.EmptyGoodListText}</div>
{/if}

