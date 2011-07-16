{foreach item=LastNews from=$Data.NewsList name=lastnews}
<table class="news"><tr valign="top"><td class="news_left">&nbsp;</td><td>
            {if isset($LastNews.Image)}<div style="margin: 0px 0px 10px 0px;width:160px;"><a href="{$LastNews.Url}">{$LastNews.Image}</a></div>{/if}
            </td><td>
            <span class="newsdate">
				{$LastNews.date|date_format:"%d.%m.%Y"}
            </span>
            <h3><a href="{$LastNews.Url}">{$LastNews.title}</a></h3>
            {$LastNews.anons} 
            <p class="go"><a href="{$LastNews.Url}">далее</a>
            </p>
            </td></tr>
</table>
{/foreach}