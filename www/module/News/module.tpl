<h1>{$Title}</h1>
{if count($Data.NewsList)>0}
<table>
    {foreach item=News from=$Data.NewsList name=news}
        <tr>
            <td colspan="2">
                <h3 class="news_title"><a href="{newsLink id=$News.id}">{$News.title}</a></h3>
            </td>
        </tr>
        <tr>
            <td class="news_preview_image">
                {if isset($News.Image)}
                    <a href="{newsLink id=$News.id}">
                        <img src="{$News.Image}&width=150&height=100&crop=1" alt="" title="" class="news_preview_image"
                             align="left"/>
                    </a>
                {/if}
            </td>
            <td class="news_preview_text">
                {$News.anons}
            </td>
        </tr>
    {/foreach}
</table>
    {else}
<div class="">Раздел в разработке</div>
{/if}
{$Data.Pager}
