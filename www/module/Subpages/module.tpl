{foreach item=page from=$Data.Pages}
<div style='padding: 5px 20px;'>
		<a title='' href='{$Data.Url}{$page.Alias}/'>{$page.Name}</a>		
</div>
{/foreach}