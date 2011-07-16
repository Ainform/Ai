<script language="JavaScript" type="text/javascript">
function editpersonals(personalsId)
{
	window.location.href = "{$PageAddress}?personalsId=" + personalsId;
	return false;
}
function newpersonals()
{
	window.location.href = "{$PageAddress}?newPersonals=1";
	return false;
}
</script>
{ajax_update}
<table class="admin_table" id="uplPersonals_table">
<thead><tr>
<th>Имя</th>
<th>Должность</th>
<th colspan="4">Опции</th>
</tr></thead>
<tbody id="uploadPersonals_tbody">
    {*между TR и foreach не должно быть никаких пробелов или перноса строки, иначе сортировка крешится*}
{foreach item=LeaderItem from=$Data.PersonalsList key=i}<tr id="upload_personals{$LeaderItem.PersonalsId}">
	<td>{$LeaderItem.Name}</td>
	<td>{$LeaderItem.Position}</td>
	{*
	<td width="16"><input type="image" name="handlerBtnUp:{$LeaderItem.PersonalsId}" src="/admin/img/op_up.gif" alt="Поднять лицензию" onclick="upUplPersonals({$LeaderItem.PersonalsId},{$LeaderItem.PersonalsId}); return false;" /></td>
	<td width="16"><input type="image" name="handlerBtnDown:{$LeaderItem.PersonalsId}" src="/admin/img/op_down.gif" alt="Опустить лицензию"  onclick="downUplPersonals({$LeaderItem.PersonalsId}, {$LeaderItem.PersonalsId}); return false;" /></td>
	*}
	<td width="16">
		{if $i != 0}
			<input type="image" name="handlerBtnUp:{$LeaderItem.PersonalsId}" src="/admin/img/op_up.gif" alt="Поднять лицензию" />
		{/if}
	</td>
	<td width="16">
		{if $i != (count($Data.PersonalsList) - 1)}
			<input type="image" name="handlerBtnDown:{$LeaderItem.PersonalsId}" src="/admin/img/op_down.gif" alt="Опустить лицензию" />
		{/if}
	</td>
	<td align="center" width="30"><input type="image" name="handlerBtnEdit:{$LeaderItem.PersonalsId}" id="btnEdit" src="{$Address}img/op_edit.gif" alt="Изменить лицензию" title="Изменить лицензию" onclick="editpersonals('{$LeaderItem.PersonalsId}'); return false;" /></td>
	<td align="center" width="30"><input type="image" name="handlerBtnDel:{$LeaderItem.PersonalsId}" src="/img/admin/close_16.png" alt="Удаление лицензии"  onclick="return confirm('Удалить лицензию?');" /></td>
</tr>{/foreach}
</tbody>
</table>
{/ajax_update}
{$Data.Pager}
<p align="center">
	<input type="submit" name="handlerBtnNewPersonals" value="Добавить" title="Добавить новость" width="90px" class="button" onclick="return newpersonals();"></input>
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>