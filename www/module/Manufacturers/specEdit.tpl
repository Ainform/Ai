<script language="JavaScript" type="text/javascript">
    function Check()
    {
        return true;
    }
</script>
<table class="admin_table">
    <tr>
        <td align="right" width="140">Наименование</td>
        <td>
			{$Data.Name}
        </td>
    </tr>
    <tr>
        <td align="right" width="140">Дата начала<br> <span style="white-space: nowrap;color:#000;">(формат: <b>1970-12-31 05:00:00</b><br>часы и минуты можно опускать)</span></td>
        <td>
			{TextBox id="DateStart" value=$Data.DateStart class="textBox"}
        </td>
    </tr>
        <tr>
        <td align="right" width="140">Дата окончания <br> <span style="white-space: nowrap;color:#000;">(формат: <b>1970-12-31 05:00:00</b><br>часы и минуты можно опускать)</span></td>
        <td>
			{TextBox id="DateEnd" value=$Data.DateEnd class="textBox"}
        </td>
    </tr>
        <tr>
        <td align="right" width="140">Пути</td>
        <td>
			{TextBox id="Path" value=$Data.Path class="textBox"}
			{Validator for="Path" rule="NotNull" message="Заполните поле"}
        </td>
    </tr>
            <tr>
        <td align="right" width="140">Скидка</td>
        <td>
			{TextBox id="Discount" value=$Data.Discount class="textBox"}
			{Validator for="Discount" rule="NotNull" message="Заполните поле"}
        </td>
    </tr>
</table>
<p align="center">
    <INPUT type="submit" class="button" value="Сохранить" name="handlerBtnSave" title="Сохранить изменения" onclick="return Check();" />&nbsp;&nbsp;&nbsp;
    <INPUT type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" />
</p>