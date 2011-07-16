<script language="JavaScript" type="text/javascript">
function Check()
{
	return true;
}
</script>
<table class="admin_table">
	<tr>
		<td align="right" width="140">Дата Лицензии</td>
		<td>
			{TextBox id="txtPersonalsDate" value=$Data.Personals.Date class="textBox"}
			{Validator for="txtPersonalsDate" rule="NotNull" message="Некорректная дата"}
		</td>
	</tr>
	<tr>
		<td align="right" width="140">Название&nbsp;</td>
		<td>
			{TextBox id="txtTitle" value=$Data.Personals.Title class="textBox"}
			{Validator for="txtTitle" rule="NotNull" message="Введите заголовок"}
		</td>
	</tr>
</table>
<br />
<table class="admin_table">
	<tr>
		<th align="center">Текст</th>
	</tr>
	<tr>
		<td>
			{FCKEditor id="fckText" height="400" value=$Data.Personals.Text}
		</td>
	</tr>
</table>
<br />
{ImageUploader id="uplPersonals" thumbWidth="100" thumbHeight="100" value=$Data.Images folder=$Data.ImageFolder}
<p align="center">
	<INPUT type="submit" class="button" value="Сохранить" name="handlerBtnPersonalsSave" title="Сохранить изменения" onclick="return Check();" />&nbsp;&nbsp;&nbsp;
	<INPUT type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" />
</p>