<script>
function Adder()
{
	window.location.href = "{$PageAddress}?act=Add";
	return false;
}
</script>
{ajax_update}
<table cellpadding="0" cellspacing="0" class="admin_table">
	<tr>
        <th>Имя</th>
		<th>E-mail</th>
        <th>Организация</th>
        <!--<th>Партнёр</th>-->
        <th>Активирован</th>
		<th colspan="2">Опции</th>
	</tr>
	{foreach item=User from=$Data.Users}
		<tr>
			<td>{$User.firstname} {$User.secondname}</td>
			<td>{$User.Email}</td>
            <td>{$User.OrgName}</td>
           <!-- <td align="center"><input type="checkbox" {if $User.Partner==1}checked{/if} name="handlerPartner:{$User.UserId}" /></td>-->
            <td width="70" align="center">{if $User.Activate==1}да{else}нет{/if}</td>
			<td width="20" align="center"><a href="?act=Edit&UserId={$User.UserId}"><img border="0" src="/img/admin/op_edit.gif" /></a></td>
			<td width="20" align="center"><a onclick="return confirm('Вы действительно хотите удалить пользователя?')" href="?act=Del&UserId={$User.UserId}"><img src="/img/admin/close_16.png" border="0" /></a></td>
		</tr>
	{/foreach}
</table>
{/ajax_update}
{$Data.Pager}
<p align="center">
	<!--<input type="submit" name="handlerBtnNewLeder" value="Добавить" title="Добавить факультет" width="90px" class="button" onclick="return Adder();"></input>-->
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>
