<table class="admin_table" border="1">
    <tr>
        <td align="right" width="150">
            <span id="nameStr"></span>Название</td>
        <td>
		{TextBox id="txtName" maxlength="100" class="text-input small-input"}
		{Validator for="txtName" rule="NotNull" message="Введите название"}
    </tr>
    <tr>
        <td align="right" width="150">
            <span id="nameStr"></span>Раздел</td>
        <td>
		{DropDownList id="ddrSection" values=$Data.Sections}
    </tr>
</table>
<table class="admin_table">
    <tr>
        <th align="center">Краткое описание</th>
    </tr>
    <tr>
        <td>
			{FCKEditor id="fckDescription" height="100" simple="True" value=$Data.Good.Description}
        </td>
    </tr>
</table>

<br />

{OneImageUploader id="uplGoods" thumbWidth="100" thumbHeight="100" value=$Data.Images folder=$Data.ImageFolder}

<br />

<p align="center">
	{if $Data.NewPage == true}
    <input type="submit" name="handlerBtnGoodSave" value="Добавить" title="Добавить страницу" width="90px" class="button" />
	{else}
    <input type="submit" name="handlerBtnGoodSave" value="Сохранить" title="Сохранить изменения" width="90px" class="button" />
	{/if}
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" width="90px" class="button" />
</p>