<table class="admin_table" border="1">
    <tr>
        <td style="width:150px;">Название</td>
        <td>
		{TextBox id="txtName" maxlength="100" class="text-input small-input"}
		{Validator for="txtName" rule="NotNull" message="Введите название товара"}
    </tr>
    <tr>
        <td>Цена</td>
        <td>
		{TextBox id="txtPrice" maxlength="100" class="text-input small-input"}
		{*Validator for="txtPrice" rule="NotNull" message="Введите цену товара"*}
    </tr>
    <!--<tr>
        <td>Артикул</td>
        <td>
		{TextBox id="txtGoodCode" maxlength="100" class="text-input small-input"}
		{Validator for="txtGoodCode" rule="NotNull" message="Введите артикул товара"}
    </tr>
    <tr>
        <td>Код
        <td>
		{TextBox id="txtGoodTrueCode" maxlength="100" class="text-input small-input"}
    </tr>-->
    <tr>
        <td>
            Раздел товара</td>
        <td>
		{DropDownList id="ddrSection" values=$Data.Sections}
    </tr>
    <!--<tr>
        <td>Производитель товара</td>
        <td>
		{DropDownList id="ddrManufacturer" values=$Data.Manufacturers}
    </tr>-->
</table>
<!--<table class="admin_table">
    <tr>
        <th align="center">Характеристики</th>
    </tr>
    <tr>
        <td>
            <textarea id="Properties" name="Properties" rows="10" cols="50" style="width:100%">{$Data.Properties}</textarea>
            <p style="font-size:8pt;color:$999;margin-bottom:5px;">Каждая характеристика должна быть на <strong>отдельной строке</strong>, название характеристики и её значение должно разделять <strong>двоеточие</strong>.</p>
        </td>
    </tr>
</table>-->
<table class="admin_table">
    <thead>
    <tr>
        <th>Краткое описание</th>
    </tr>
    </thead>
    <tr>
        <td>
			{FCKEditor id="fckDescription" height="100" simple="True" value=$Data.Good.Description}
        </td>
    </tr>
</table>
<table class="admin_table">
    <thead>
    <tr>
        <th>Описание товара</th>
    </tr>
    </thead>
    <tr>
        <td>
            <input type="hidden" name="txtCode" value="" />
            <input type="hidden" name="onMain" value="" />
			{FCKEditor id="fckAbstract" height="300" value=$Data.Good.Abstract}
        </td>
    </tr>
</table>

<br />

{ImageUploader id="uplGoods" thumbWidth="100" thumbHeight="100" value=$Data.Images folder=$Data.ImageFolder}

<br />

{*AnalyticFilesUploader id="uplfile" value=$Data.Files folder=$Data.FilesFolder*}

<p align="center">
	{if $Data.NewPage == true}
    <input type="submit" name="handlerBtnGoodSave" value="Добавить" title="Добавить страницу" class="button" />
	{else}
    <input type="submit" name="handlerBtnGoodSave" value="Сохранить" title="Сохранить изменения" class="button" />
	{/if}
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" class="button" />
</p>