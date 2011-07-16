{if count($Data.Backups) == 0}
<p align="center"><b>Резервные копии отсутствуют</b></p>
{else}
<table class="admin_table">
    <thead>
<tr>
	<th>Номер</th>
	<th>Дата создания</th>
	<th colspan="2">Востановить</th>
	<th>Востановление</th>
	<th>Удаление</th>
</tr>
</thead>
{foreach item=Backup from=$Data.Backups}
<tr>
	<td width="50">{$Backup.Id}</td>
	<td>{$Backup.Date}</td>
	<td width="30" align="center"><input class="button" type="submit" name="handlerBtnRestoreSql:{$Backup.Folder}" value="БД" title="Востановление базы данных" onclick="return confirm('Восстановить БД?');" /></td>
	<td width="50" align="center"><input class="button" type="submit" name="handlerBtnRestoreFiles:{$Backup.Folder}" value="Файлы" title="Востановление файлов" onclick="return confirm('Восстановить файлы?');" /></td>
	<td width="70" align="center"><input class="button" type="submit" name="handlerBtnRestore:{$Backup.Folder}" value="Восстановить" title="Востановление резервной копии" onclick="return confirm('Восстановить резервную копию?');" /></td>
	<td width="70" align="center"><input type="image" name="handlerBtnDelete:{$Backup.Folder}" src="/img/admin/close_16.png" alt="Удаление резервной копии" height="16" width="16" onclick="return confirm('Удалить резервную копию?');" /></td>
</tr>
{/foreach}
</table>
{/if}
<p align="center">
	<input class="button" type="submit" name="handlerBtnCreate" value="Создать резервную копию" title="Создать резервную копию информации на сайте" />
</p>
{$Data.litMessage}