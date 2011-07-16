<table cellspacing="0" cellpadding="0" width="100%" class="table">
{foreach item=PersonalsItem from=$Data.PersonalsList}
<tr valign="top"><td width="114" style="padding: 0px 0px 15px 0px;">
	{if isset($PersonalsItem.Image)}
        <a href="{personalsLink id=$PersonalsItem.PersonalsId}" class="newsImg">{$PersonalsItem.Image}</a>
		{else}
		<a href="{personalsLink id=$PersonalsItem.PersonalsId}" class="newsImg"><img alt="" src="/img/no_photo.jpg" width="135px" /></a>
        {/if}
        </td><td style="padding: 7px 0px 15px 0px;">
<a style="margin: 0px 0px 3px 0px; display: block;" href="{personalsLink id=$PersonalsItem.PersonalsId}"><b>{$PersonalsItem.Name}</b></a>
<p>{$PersonalsItem.Position}</p>
            </td></tr>
{/foreach}
{$Data.Pager}
</table>
