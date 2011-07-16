<p><a href="javascript:history.go(-1)">Назад</a></p>
{if isset($Data.isshow)}
    {if isset($Data.FirstPhoto)}
    <div style="float:left;margin-right:12px;margin-bottom:12px;">
        <a href="{$Data.FirstPhoto.Path}" rel="shadowbox[news]" title="{$Data.FirstPhoto.Title}">
            <img style="border:1px solid #fff" src="{$Data.FirstPhoto.Path}&width=315" alt="{$Data.FirstPhoto.Title}"
                 title="{$Data.FirstPhoto.Title}"/>
        </a>
        {if count($Data.Images) > 0}
            <table cellpadding="0" cellspacing="0" style="margin-top: 10px;">
            <tr>
                {assign var="common" value=0}
                {foreach item=Image from=$Data.Images}
                    {if $common==3}
                        {math equation="sum-3" sum=$common count=1 assign="common"}
                    </tr>
                    <tr valign="top">
                    {/if}
                    {math equation="sum+count" sum=$common count=1 assign="common"}
                    <td style="padding:4px 8px 4px 0px;">
                        <a href="{$Image.Path}" rel="shadowbox[news]" title="{$Image.Title}">
                            <img src="{$Image.Path}&width=100&border=1" alt="{$Image.Title}" title="{$Image.Title}"
                                 width="100"/>
                        </a>
                    </td>
                {/foreach}
            </tr>
            </table>
        {/if}
    </div>
    {/if}
<h3 class="news_title">{$Data.News.title}</h3>
<div class="news_text_page">
    {$Data.News.text}
    {include file="../media.tpl"}
</div>
{/if}
