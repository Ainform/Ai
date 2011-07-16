<table width="100%" class="admin_table" cellpadding="5">
<tr>
	<th width="20%">Название</th>
	<th width="40%">Описание</th>
	<th width="115px">Размер файла(Кб) </th>
	<th colspan="2">&nbsp;</th>
</tr>
	{foreach item=File from=$Data.Files}
		<tr>
			<td><a href="{$File.Path}"  target='_blank'>{$File.DocName}</a></td>
			<td>{$File.Description}</td>
			<td>{$File.Size/1000|string_format:"%.2f"}&nbsp;</td>
			<td><input type="image" src="/admin/img/op_edit.gif" name="handlerBtnEdit:{$File.FileId}"/></td>
			<td><input type="image" src="/admin/img/op_delete.gif" name="handlerBtnDelete:{$File.FileId}"/></a></td>
		</tr>		
	{/foreach}	
	<tr>
		<td align="center" colspan="5">
			<p align="center">
				<input type="submit" class="button" value="Добавить" name="handlerAddFile" title="Добавить файл" />&nbsp;
				<input type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" />
			</p>
		</td>
	</tr>
</table>