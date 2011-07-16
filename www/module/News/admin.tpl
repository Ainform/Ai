{scaffolding list=$Data.NewsList}
{*}<!--/*<script language="JavaScript" type="text/javascript">
function editNews(newsId)
{
	window.location.href = "{$PageAddress}?NewsId=" + newsId;
	return false;
}
function newNews()
{
	window.location.href = "{$PageAddress}?newNews=1";
	return false;
}
</script>
<table class="admin_table">
    <thead>
<tr>
<th width="50">Дата</th>
<th>Заголовок</th>
<th colspan="2">Опции</th>
</tr>
</thead>
{foreach item=LeaderItem from=$Data.NewsList}
<tr>
	<td width="50">{$LeaderItem.date|date_format:"%d.%m.%Y"}</td>
	<td>{$LeaderItem.title}</td>
	<td align="center" width="30">
		<input type="image" name="handlerBtnEdit:{$LeaderItem.id}" id="btnEdit" src="{$Address}img/admin/op_edit.gif" alt="Изменить" title="Изменить" height="16" width="16" onclick="editNews('{$LeaderItem.id}'); return false;" />
		<a href="../../admin/modules/{$Data.ModuleId}.php?NewsId={$LeaderItem.id}"><img src="{$Address}img/admin/op_edit.gif"  /></a>
	</td>
	<td align="center" width="30"><input type="image" name="handlerBtnDel:{$LeaderItem.id}" src="/img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td></tr>
{/foreach}
</table>
{$Data.Pager}
<p align="center">
	<input type="submit" name="handlerBtnNewNews" value="Добавить" title="Добавить" width="90px" class="button" onclick="return newNews();"></input>
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>-->
{*}
