<script language="JavaScript" type="text/javascript">
function edit(Id)
{
	window.location.href = "{$PageAddress}?Id=" + Id;
	return false;
}
</script>
{ajax_update}
<table class="admin_table">
<tr>
<th width="20">Id</th>
<th width="20">Имя</th>
<th width="90">Дата начала</th>
<th width="90">Дата окончания</th>
<th>Скидка</th>
<th>Пути</th>
<th colspan="2">Функции</th>
</tr>
{foreach item=Item from=$Data.List}
<tr>
	<td width="20">{$Item.GoodId}</td>
        <td>{$Item.Name}</td>
	<td>{$Item.DateStart}</td>
        <td>{$Item.DateEnd}</td>
        <td>{$Item.Discount}</td>
        <td>{$Item.Path}</td>
	<td align="center" width="30">
		<input type="image" name="handlerBtnEdit:{$Item.GoodId}" id="btnEdit" src="{$Address}img/op_edit.gif" alt="Изменить" title="Изменить" height="16" width="16" onclick="edit('{$Item.GoodId}'); return false;" />
	</td>
	<td align="center" width="30"><input type="image" name="handlerBtnDel:{$Item.GoodId}" src="/img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td></tr>
{/foreach}
</table>
{/ajax_update}
{$Data.Pager}
