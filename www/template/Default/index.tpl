{strip}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>{$Title}</title>
        {$MetaTags}
        {foreach item=CSSFile from=$CSSFiles}
        <link href="{$CSSFile}" type="text/css" rel="stylesheet" />
        {/foreach}
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script type="text/javascript" src="{$Address}/js/buket.js"></script>
		<script type="text/javascript" src="{$Address}/js/swfobject.js"></script>
        <script type="text/javascript">
            var flashvars = {
                name1: "hello",
                name2: "world",
                name3: "foobar"
            };
            var params = {
                menu: "false",
                wmode: "transparent"
            };
            var attributes = {
                id: "myDynamicContent",
                name: "myDynamicContent",
                style:"left:220px;top:-250px;position:absolute;"
            };
            var attributes2 = {
                id: "myDynamicContent",
                name: "myDynamicContent",
                style:"left:660px;top:-250px;position:absolute;"
            };

            swfobject.embedSWF("/css/images/firefly.swf", "flash", "140", "140", "9.0.0","expressInstall.swf", flashvars, params, attributes);
            swfobject.embedSWF("/css/images/firefly.swf", "flash2", "140", "140", "9.0.0","expressInstall.swf", flashvars, params, attributes2);
        </script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="menu_top_slider_outside">
                    <div id="menu_top_slider">
                        <div id="menu_slider_1" class="menu_slider">
                            {foreach item=MenuItem from=$MenuSubItems1 name=menuitems}
                            <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                            {/foreach}
                        </div>
                        <!-- <div id="menu_slider_2" class="menu_slider">
                             {foreach item=MenuItem from=$MenuSubItems2 name=menuitems}
                             <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                             {/foreach}
                         </div>-->
                        <div id="menu_slider_3" class="menu_slider">
                            {foreach item=MenuItem from=$MenuSubItems3 name=menuitems}
                            <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                            {/foreach}
                        </div>
                        <div id="menu_slider_4" class="menu_slider">
                            {foreach item=MenuItem from=$MenuSubItems4 name=menuitems}
                            <a href="/{$MenuItem.parent_alias}/{$MenuItem.Alias}/" class="menu_sub_item">{$MenuItem.Name}</a>
                            {/foreach}
                        </div>
                    </div>
                </div>
                <div id="menu_top_outside">
                    <div id="menu_top">
                        <div id="flash" style="position:absolute;margin-left:200px;margin-top:-200px;"></div>
                        <div id="flash2" style="position:absolute;margin-left:200px;margin-top:-200px;"></div>
                        <span id="menu_1_link" class="menu_link">О компании</span>
                        <span id="menu_2_link" class="menu_link"><a href="/internet-magazin/">Интернет-магазин</a></span>
                        <span id="menu_3_link" class="menu_link">Галерея работ</span>
                        <span id="menu_4_link" class="menu_link">Услуги</span>
						<!--
												{foreach item=MenuItem from=$MenuItems name=menuitems}
												<span id="menu_{$smarty.foreach.menuitems.index+1}_link" class="menu_link">{$MenuItem.Name}</span>
												{/foreach}

						-->
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="about"></div>
                <a href="/internet-magazin/" id="gallery"></a>
                <div id="proposal"></div>
                <div id="contacts"></div>
                <div style="clear:both"></div>
                <div id="main_left_menu">
                    <p><a href="/uslugi/svadebnaja_floristika/">Свадебная флористика</a></p>
                    <p><a href="/uslugi/korporativnym_klientam/">Корпоративным клиентам</a></p>
                    <p><a href="/uslugi/oformlenie_interjerov/">Оформление интерьеров</a></p>
                    <p><a href="/uslugi/master-klassy/">Мастер-классы</a></p>
                    <p><a href="/uslugi/ekskljuzivnye_napravlenija/">Эксклюзивные направления</a></p>
                </div>
                <div id="center_place">
                    {if count($Modules)!=0}
                    {include file="../modules.tpl"}
                    {else}
                    <h2>Мы дарим вам Букет хорошего настроения!</h2>
                    {/if}
                </div>
                <div id="mashinka"><p><a href="/uslugi/dostavka_tsvetov/">Доставка букетов</a></p></div>
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