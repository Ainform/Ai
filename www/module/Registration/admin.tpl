<script>
function Adder()
{
	window.location.href = "{$PageAddress}?act=Add";
	return false;
}
</script>
<table cellpadding="0" cellspacing="0" class="admin_table">
	<tr>
		<th>ФИО</th>
		<th>Телефон</th>
		<th>E-mail</th>
		<th colspan="2">Опции</th>
	</tr>
	{foreach item=User from=$Data.Users}
		<tr>
			<td>{$User.FIO}</td>
			<td>{$User.Phone}</td>
			<td>{$User.Email}</td>
			<td width="20" align="center"><a href="?act=Edit&UserId={$User.UserId}"><img border="0" src="/admin/img/op_edit.gif" /></a></td>
			<td width="20" align="center"><a href="?act=Del&UserId={$User.UserId}"><img src="/admin/img/op_delete.gif" border="0" /></a></td>
		</tr>
	{/foreach}
</table>

{$Data.Pager}
<p align="center">
	<input type="submit" name="handlerBtnNewLeder" value="Добавить" title="Добавить факультет" width="90px" class="button" onclick="return Adder();"></input>
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>
<form action="" method="post" enctype="application/x-www-form-urlencoded">
	<table cellpadding="0" cellspacing="0" class="admin_table" width="100%">
		<tr>
			<th>Архив с отчетами(CSV)</th>
			<td><input type="file" name="archive"></td>
                        <td>{$Data.Message}</td>
		</tr>
	</table>
	<p align="center">
		<input type="submit" class="button" name="archive_upload" value="Залить архив">
	</p>
</form>