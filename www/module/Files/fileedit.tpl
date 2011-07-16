<table width="100%" class="admin_table">
	<tr>	
		<td>Название</td>
		<td>{TextBox id="txtDocName" maxlength="150" class="textBox"}
			{Validator for="txtDocName" rule="NotNull" message="Введите название"}
			{if isset($dname)}{$dname}{/if}
		</td>
	</tr>
	
	<tr>		
		<td valign="top">Описание</td>
		
		<td>{FCKEditor id="fckDescription" height="200" value=$Data.News.Text}</td>
	</tr>
	
	<tr>
		<td>Выберите файл:</td>
		<td> <input type="file" name="document" style="width:250px;" value="{if isset($isNewFile)}{$isNewFile}{else}0{/if}"/>
			{if isset($documentNo)}
				<br />
				<div class="validateError">
					{$documentNo}
				</div>
			{/if}
		</td>
	</tr>
	
	<tr>
		<td colspan="2" align="center">
			<p align="center">
			{if isset($is_edit)}
				<input type="submit" class="button" value="Обновить" name="handlerBtnUpdate:{if isset($fileId)}{$fileId}{else}0{/if}" title="Сохранить изменения" />&nbsp;&nbsp;&nbsp;
			{else}		
				<input type="submit" class="button" value="{if isset($ButtonName)} {$ButtonName} {else}Сохранить{/if}" name="handlerBtnSave:{if isset($fileId)}{$fileId}{else}0{/if}" title="Добавить запись" />&nbsp;&nbsp;&nbsp;
			{/if}
				<input type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" />
			</p>
		</td>
	</tr>
</table>