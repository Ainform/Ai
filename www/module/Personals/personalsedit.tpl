<script language="JavaScript" type="text/javascript">
function Check()
{
	return true;
}
</script>
<table class="admin_table">
	<tr>
		<td align="right" width="140">Имя</td>
		<td>
			{TextBox id="txtName" value=$Data.Personals.Name class="textBox"}
			{Validator for="txtName" rule="NotNull" message="Некорректное имя"}
		</td>
	</tr>
	<tr>
		<td align="right" width="140">Должность</td>
		<td>
			{TextBox id="txtPosition" value=$Data.Personals.Position class="textBox"}
			{Validator for="txtPosition" rule="NotNull" message="Введите должность"}
		</td>
	</tr>
</table>
<br />
<table class="admin_table">
	<tr>
		<th align="center">Краткое описание</th>
	</tr>
	<tr>
		<td>
			{FCKEditor id="fckText" height="400" value=$Data.Personals.Anons}
		</td>
	</tr>
</table>
<br />
{ImageUploader id="uplPersonals" thumbWidth="100" thumbHeight="100" value=$Data.Images folder=$Data.ImageFolder}
<p align="center">
	<INPUT type="submit" class="button" value="Сохранить" name="handlerBtnPersonalsSave" title="Сохранить изменения" onclick="return Check();" />&nbsp;&nbsp;&nbsp;
	<INPUT type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" />
</p>