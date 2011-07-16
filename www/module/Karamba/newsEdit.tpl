<script language="JavaScript" type="text/javascript">
function Check()
{
	return true;
}
</script>
<table class="admin_table">
	<tr>
		<td align="right" width="140">Заголовок</td>
		<td>
			{TextBox id="txtTitle" value=$Data.title class="textBox"}
			{Validator for="txtTitle" rule="NotNull" message="Введите заголовок"}
		</td>
	</tr>
</table>
<table class="admin_table">
	<tr>
		<td align="right" width="140">Дата</td>
		<td>
			{TextBox id="txtDate" value=$Data.date class="textBox"}
			{Validator for="txtDate" rule="NotNull" message="Введите дату"}
		</td>
	</tr>
</table>
<!--<table class="admin_table">
	<tr>
		<td align="right" width="140">Показывать на главной</td>
		<td>
			<select name="selectOnFront">
                <option value="0" {if $Data.selectOnFront == 0}selected="true"{/if}>Не выводить на главную</option>
                <option value="1" {if $Data.selectOnFront == 1}selected="true"{/if}>Высший приоритет</option>
                <option value="2" {if $Data.selectOnFront == 2}selected="true"{/if}>Средний приоритет</option>
                <option value="3" {if $Data.selectOnFront == 3}selected="true"{/if}>Низкий приоритет</option>
			</select>
		</td>
	</tr>
</table>-->
<table class="admin_table">
	<tr>
		<th align="center">Анонс (краткое описание)</th>
	</tr>
	<tr>
		<td>
			{FCKEditor id="fckAnons" height="200" value=$Data.anons}
		</td>
	</tr>
</table>
<table class="admin_table">
	<tr>
		<th align="center">Текст</th>
	</tr>
	<tr>
		<td>
			{FCKEditor id="fckText" height="400" value=$Data.text}
		</td>
	</tr>
</table>
{ImageUploader id="uplimg" thumbWidth="100" thumbHeight="100" value=$Data.Images folder=$Data.ImagesFolder}
{AnalyticFilesUploader id="uplfile" value=$Data.Files folder=$Data.FilesFolder}
<p align="center">
	<INPUT type="submit" class="button" value="Сохранить" name="handlerBtnNewsSave" title="Сохранить изменения" onclick="return Check();" />&nbsp;&nbsp;&nbsp;
	<INPUT type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" />
</p>