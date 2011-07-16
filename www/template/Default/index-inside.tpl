{strip}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{$Title}</title>
        {$MetaTags}
        {foreach item=CSSFile from=$CSSFiles}
        <link href="{$CSSFile}" type="text/css" rel="stylesheet" />
        {/foreach}
        <link href="/css/style_inside.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/css/shadowbox.css">
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
			<script type="text/javascript" src="{$Address}/js/buket.js"></script>
			<script type="text/javascript" src="{$Address}/js/shadowbox/shadowbox.js"></script>
			<script>
				/*$(document).ready(function(){
					$(".menu_left_link").toggle(function(){
						$(".id"+$(this).attr("id")).show();
					},
					function(){
						$(".id"+$(this).attr("id")).hide();
					});
				});*/
			</script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="logo_outside">
                    <div id="logo">
                        <div onclick="window.location='/'"></div>
                    </div>
                </div>
                <div id="top_cart_outside">
                    <div id="top_cart">
                        <p class="top_cart_link"><a href="/cart/">Ваша корзина</a></p>
                        <p class="top_cart_count">В корзине {$Cart_count}</p>
                    </div>
                </div>
                <div id="menu_top_slider_outside">
                    <div id="menu_top_slider" class="inside">
                        <div id="menu_slider_2" class="menu_slider">
                            {foreach item=MenuItem from=$MenuSubItems1 name=menuitems}
                            <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                            {/foreach}
                        </div>
                        <!-- <div id="menu_slider_2" class="menu_slider">
                             {foreach item=MenuItem from=$MenuSubItems2 name=menuitems}
                             <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                             {/foreach}
                         </div>-->
                        <div id="menu_slider_4" class="menu_slider">
                            {foreach item=MenuItem from=$MenuSubItems3 name=menuitems}
                            <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                            {/foreach}
                        </div>
                        <div id="menu_slider_5" class="menu_slider">
                            {foreach item=MenuItem from=$MenuSubItems4 name=menuitems}
                            <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                            {/foreach}
                        </div>
                    </div>
                </div>
                <div id="menu_top_outside">
                    <div id="menu_top" class="inside">
                        <span id="menu_1_link" class="menu_link"><a href="/">Главная</a></span>
                        <span id="menu_2_link" class="menu_link">О компании</span>
                        <span id="menu_3_link" class="menu_link"><a href="/internet-magazin/">Интернет-магазин</a></span>
                        <span id="menu_4_link" class="menu_link">Галерея работ</span>
                        <span id="menu_5_link" class="menu_link">Услуги</span>
                        <!--
                                                {foreach item=MenuItem from=$MenuItems name=menuitems}
                                                <span id="menu_{$smarty.foreach.menuitems.index+1}_link" class="menu_link">{$MenuItem.Name}</span>
                                                {/foreach}

                        -->
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="menu_left_inside">
                    {if isset($ParentTitle)}
                    <h1>{$ParentTitle}</h1>
                    {/if}

                    {if !isset($shop)}

                    {foreach item=MenuLeftItem from=$MenuLeftItems1}
                    {if isset($MenuLeftItem.Selected) && $MenuLeftItem.Selected==true}
                    <a href="/{$MenuLeftItem.Url}" id="{$MenuLeftItem.SectionId}" class="menu_left_link selected id{$MenuLeftItem.ParentId}" style="padding-left:{$MenuLeftItem.depth*10}px;">{$MenuLeftItem.Name}</a>
                    {else}
                    <a href="/{$MenuLeftItem.Url}" id="{$MenuLeftItem.SectionId}" class="menu_left_link id{$MenuLeftItem.ParentId}" style="padding-left:{$MenuLeftItem.depth*10}px;{if $MenuLeftItem.depth>1}display:none;{/if}">{$MenuLeftItem.Name}</a>
                    {/if}
                    {/foreach}

                    {else}
                    <div class="catalog_search">
						<form action="/search/" method="get">
							<input type="text" name="q" value="{if isset($smarty.get.q)}{$smarty.get.q}{/if}"><input type="submit" value="Найти" />
						</form>
					</div>
                    {foreach item=MenuLeftItem from=$MenuLeftItems1}

                    {if $MenuLeftItem.ParentId==-1}

                    {if isset($MenuLeftItem.hasChild) && $MenuLeftItem.hasChild==true}<div class="plus" id="menu{$MenuLeftItem.SectionId}">+</div>{/if}
                    {if isset($MenuLeftItem.Selected) && $MenuLeftItem.Selected==true}
                    <a href="/{$MenuLeftItem.Url}" id="{$MenuLeftItem.SectionId}" class="menu_left_link selected id{$MenuLeftItem.ParentId}" style="padding-left:{$MenuLeftItem.depth*10}px;">{$MenuLeftItem.Name}</a>
                    {else}
                    <a href="/{$MenuLeftItem.Url}" id="{$MenuLeftItem.SectionId}" class="menu_left_link id{$MenuLeftItem.ParentId}" style="padding-left:{$MenuLeftItem.depth*10}px;{if false && $MenuLeftItem.depth>1}display:none;{/if}">{$MenuLeftItem.Name}</a>
                    {/if}
                    <div class="sub{$MenuLeftItem.SectionId} submenu {if isset($MenuLeftItem.Selected) && $MenuLeftItem.Selected==true}active{/if}">
                        {foreach item=MenuLeftItem1 from=$MenuLeftItems1}
                        {if $MenuLeftItem1.ParentId==$MenuLeftItem.SectionId}

                        {if isset($MenuLeftItem1.hasChild) && $MenuLeftItem1.hasChild==true}<div class="plus" id="menu{$MenuLeftItem1.SectionId}">+</div>{/if}
                        {if isset($MenuLeftItem1.Selected) && $MenuLeftItem1.Selected==true}
                        <a href="/{$MenuLeftItem1.Url}" id="{$MenuLeftItem1.SectionId}" class="menu_left_link selected id{$MenuLeftItem1.ParentId}" style="padding-left:{$MenuLeftItem1.depth*10}px;">{$MenuLeftItem1.Name}</a>
                        {else}
                        <a href="/{$MenuLeftItem1.Url}" id="{$MenuLeftItem1.SectionId}" class="menu_left_link id{$MenuLeftItem1.ParentId}" style="padding-left:{$MenuLeftItem1.depth*10}px;{if false && $MenuLeftItem1.depth>1}display:none;{/if}">{$MenuLeftItem1.Name}</a>
                        {/if}
                        <div class="sub{$MenuLeftItem1.SectionId} submenu {if isset($MenuLeftItem1.Selected) && $MenuLeftItem1.Selected==true}active{/if}">
                            {foreach item=MenuLeftItem2 from=$MenuLeftItems1}
                            {if $MenuLeftItem2.ParentId==$MenuLeftItem1.SectionId}

                            {if isset($MenuLeftItem2.hasChild) && $MenuLeftItem2.hasChild==true}<div class="plus" id="menu{$MenuLeftItem2.SectionId}">+</div>{/if}
                            {if isset($MenuLeftItem2.Selected) && $MenuLeftItem2.Selected==true}
                            <a href="/{$MenuLeftItem2.Url}" id="{$MenuLeftItem2.SectionId}" class="menu_left_link selected id{$MenuLeftItem2.ParentId}" style="padding-left:{$MenuLeftItem2.depth*10}px;">{$MenuLeftItem2.Name}</a>
                            {else}
                            <a href="/{$MenuLeftItem2.Url}" id="{$MenuLeftItem2.SectionId}" class="menu_left_link id{$MenuLeftItem2.ParentId}" style="padding-left:{$MenuLeftItem2.depth*10}px;{if false && $MenuLeftItem2.depth>1}display:none;{/if}">{$MenuLeftItem2.Name}</a>
                            {/if}
                            <div class="sub{$MenuLeftItem2.SectionId} submenu {if isset($MenuLeftItem2.Selected) && $MenuLeftItem2.Selected==true}active{/if}">
                                {foreach item=MenuLeftItem3 from=$MenuLeftItems1}
                                {if $MenuLeftItem3.ParentId==$MenuLeftItem2.SectionId}

                                {if isset($MenuLeftItem3.hasChild) && $MenuLeftItem3.hasChild==true}<div class="plus" id="menu{$MenuLeftItem3.SectionId}">+</div>{/if}
                                {if isset($MenuLeftItem3.Selected) && $MenuLeftItem3.Selected==true}
                                <a href="/{$MenuLeftItem3.Url}" id="{$MenuLeftItem3.SectionId}" class="menu_left_link selected id{$MenuLeftItem3.ParentId}" style="padding-left:{$MenuLeftItem3.depth*10}px;">{$MenuLeftItem3.Name}</a>
                                {else}
                                <a href="/{$MenuLeftItem3.Url}" id="{$MenuLeftItem3.SectionId}" class="menu_left_link id{$MenuLeftItem3.ParentId}" style="padding-left:{$MenuLeftItem3.depth*10}px;{if false && $MenuLeftItem3.depth>1}display:none;{/if}">{$MenuLeftItem3.Name}</a>
                                {/if}
                                <div class="sub{$MenuLeftItem3.SectionId} submenu {if isset($MenuLeftItem3.Selected) && $MenuLeftItem3.Selected==true}active{/if}">
                                </div>
                                {/if}
                                {/foreach}

                            </div>
                            {/if}
                            {/foreach}

                        </div>
                        {/if}
                        {/foreach}
                    </div>

                    {/if}

                    {/foreach}
					<a class="oplata_i_dostavka menu_left_link {if isset($oplata_i_dostavka)}selected{/if}" href="/internet-magazin/oplata_i_dostavka/">Оплата и доставка</a>
                    {/if}
                </div>
                <div id="inside_content">
                    {if count($Modules)!=0}
                    {include file="../modules.tpl"}
                    {else}
                    <h2>Раздел в разработке</h2>
                    {/if}
                </div>
                <div style="clear:both"></div>
            </div>
            <div id="clear"></div>
            <div id="rasporka"></div>
        </div>
        <div id="footer">
            <div id="footer_inside">
                <div id="copyright">&copy; Букет 2010</div>
                <div id="counters">
					<!-- Yandex.Metrika counter -->
					<div style="display:none;"><script type="text/javascript">
						(function(w, c) {
							(w[c] = w[c] || []).push(function() {
								try {
									w.yaCounter5960620 = new Ya.Metrika(5960620);
									yaCounter5960620.clickmap(true);
									yaCounter5960620.trackLinks(true);

								} catch(e) { }
							});
						})(window, 'yandex_metrika_callbacks');
						</script></div>
					<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
					<noscript><div><img src="//mc.yandex.ru/watch/5960620" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
					<!-- /Yandex.Metrika counter -->
                </div>
                <div id="address">Мустая Карима, 41. тел. 273-55-66.</div>
                <div id="welcome">Мы дарим вам Букет хорошего настроения!</div>
                <div id="ai"><a href="http://ainform.com">Разработка сайта</a></div>
            </div>
        </div>
    </body>
</html>
{/strip}