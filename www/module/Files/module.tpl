{if !empty($Data.Files)}
<table width="100%" class="file_list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th width="270" style="border-bottom:1px solid #68AFFF;border-right:1px solid #68AFFF;">Имя документа</th>
	<th width="50%" style="border-bottom:1px solid #68AFFF;border-right:1px solid #68AFFF;">Описание</th>
	<th width="180" style="border-bottom:1px solid #68AFFF;">Размер файла (Кб) </th>
</tr>
	{foreach item=File from=$Data.Files}
		<tr>
			<td>&nbsp;<a href="{$File.Path}"><b>{$File.DocName}</b></a></td>
			<td>{$File.Description}</td>
			<td >{$File.Size/1000|string_format:"%.2f"}&nbsp;</td>
		</tr>
	{/foreach}	
</table>
{else}
<div style="text-align: center; font-weight: bold">Раздел в разработке</div>
{/if}