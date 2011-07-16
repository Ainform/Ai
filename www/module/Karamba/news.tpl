{if isset($Data.isshow)}
{if isset($Data.FirstPhoto)}
	<div style="float:left;margin-right:12px;margin-bottom:12px;">
		<a href="{$Data.FirstPhoto.Path}" rel="shadowbox[news]" title="{$Data.FirstPhoto.Title}"><img src="{$Data.FirstPhoto.Path}&width=323" alt="{$Data.FirstPhoto.Title}" title="{$Data.FirstPhoto.Title}" width="323"/></a>

{if count($Data.Images) > 0}
	<table cellpadding="0" cellspacing="0" style="margin-top: 10px;"><tr>
	{assign var="common" value=0}
	{foreach item=Image from=$Data.Images}
	  {if $common==3}
	   {math equation="sum-3" sum=$common count=1 assign="common"}
	   </tr>
	   <tr valign="top">
	  {/if}
	  {math equation="sum+count" sum=$common count=1 assign="common"}
	  <td style="padding:4px 8px 4px 0px;"><a href="{$Image.Path}" rel="shadowbox[news]" title="{$Image.Title}"><img src="{$Image.Path}&width=100&border=1" alt="{$Image.Title}" title="{$Image.Title}" width="100" /></a></td>
	{/foreach}
    </tr>
	</table>
{/if}

	</div>
{/if}

<div class="news_date">{$Data.News.date|date_format:"%d.%m.%Y"}</div>
{*<h1 class="news_title" style="text-decoration: none;">{$Data.News.title}</h1>*}

<div class="news_text">
{$Data.News.text}

{include file="../media.tpl"}

</div>

{/if}