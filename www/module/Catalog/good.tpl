<script type="text/javascript" src="/js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<link rel="stylesheet" href="/js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
{$BreadCrumbs}
<br />
<div class="good_page_left">
	{if isset($Data.Good.Images)}
	{foreach item=Image from=$Data.Good.Images}
    <div class="good_image_inside">
        {if isset($Image)}
        <a href="{$Image.Path}" rel="goodimages" class="group" title='{$Image.Title|default:$Data.Good.Title}'>
            <img src="{$Image.Path}&width=250&height=350&prop=1"  alt="{$Image.Title|default:$Data.Good.Title}" border=none/>
        </a>
        {/if}
    </div>
    {/foreach}
    {else}
    <div class="good_image">
        <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
    </div>
    {/if}
</div>
<div>
    <h1 class="goodtitle">{$Data.Good.Title}</h1>
    <p>Цена: <span style="padding-left: 0px;" class="good_price">{$Data.Good.Price}</span> руб.</p>
    <p>Описание:</p>
    <p>{$Data.Good.Abstract}</p>
    {if isset($Data.AddedToCart[$Data.Good.GoodId])}
    {$Data.AddedToCart[$Data.Good.GoodId]}
    {else}
    <p class="good_buy">
        <input type="submit" name="handlerBtnAdd" class="button" value="Заказать" />
    </p>
    {/if}
    <div class="nextprev">
        {if isset($Data.prevGood)}
        <a class="prev" href="{goodLink id=$Data.prevGood.GoodId}" >
            « Предыдущий
        </a>
        {/if}
        {if isset($Data.nextGood)}
        <a class="next" href="{goodLink id=$Data.nextGood.GoodId}" >
            Следующий »
        </a>
        {/if}
        <div style="clear:both;"></div>
    </div>

</div>

{if $Data.GoodsList}
<div class="goods_list">
    {foreach item=Gooditem from=$Data.GoodsList key=i}
    <div class="good_div">
        <div class="good_image">
            {if $Gooditem.Image!=""}
            <a href="{goodLink id=$Gooditem.GoodId}" title="{$Gooditem.Title}">
                <img src="{$Gooditem.Image}&width=111&height=111&crop=1"  alt="{$Gooditem.Title}" style="border:none;"/>
            </a>
            {else}
            <a href="{goodLink id=$Gooditem.GoodId}">
                <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
            </a>
            {/if}
        </div>
        <p class="good_title">{$Gooditem.Title}</p>
        {if $Gooditem.Price==0}
        <p class="good_price">цена не указана</p>
        {else}
        <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price">{$Gooditem.Price}</span>&nbsp;руб.</p>
        {/if}
        {if empty($Data.AddedToCart[$Gooditem.GoodId])}
        <p class="good_buy"><input type="submit" name="handlerBtnAdd:{$Gooditem.GoodId}" class="button" value="Заказать"/>
            {else}
        <p class="hello">{$Data.AddedToCart[$Gooditem.GoodId]}
            {/if}
    </div>
    {/foreach}
</div>
{/if}
<script>
	$(document).ready(function() {

		$("a.group").fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600,
			'speedOut'		:	200,
			'overlayShow'	:	false,
			'type':'image'
		});

	});
</script>
{include file="../media.tpl"}