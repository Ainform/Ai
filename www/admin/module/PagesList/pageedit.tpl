<script language="javascript" type="text/javascript">
    function addModule()
    {
        var modules = document.getElementById("drdModulesList");
        var module = modules.options[modules.selectedIndex].value;

        new Ajax.Request("{$PageAddress}?id={$smarty.get.id}",
        {
            postBody: 'handlerBtnAddModule=handlerBtnAddModule&drdModulesList='+module+'&moduleId={$Module->moduleId}',
            onSuccess: function(transport, json)
            {
                //			alert(1);
            }
        });


        return false;
    }
    function deleteModule()
    {
        return confirm('Удалить модуль на странице? \n Если вы нажмете OK то модуль востановить будет НЕВОЗМОЖНО');
    }
    function editModule(moduleId)
    {
        if (!moduleId)
            return false;

        document.location.href = "/admin/modules/" + moduleId + ".php";
        return false;
    }
    function genAlias(elem)
    {
        if ($F("txtAlias") == "")
        {
            trans = new Translit();
            $("txtAlias").value = trans.UrlTranslit($F(elem), false);
        }
    }
    function countChars(elem, span)
    {
        $(span).innerHTML = "<b>"+elem.value.length+"</b>";
    }
</script>
<table class="admin_table">
    <tr>
        <td style="width:150px;">Заголовок</td>
        <td class="page_header">
		{TextBox id="txtName" maxlength="100" class="text-input small-input" onblur="genAlias(this)"}
		{Validator for="txtName" rule="NotNull" message="Введите заголовок страницы"}
        </td>
    </tr>
    <tr>
        <td>Title</td>
        <td>
		{TextBox id="txtTitle" maxlength="100" class="text-input small-input"  onkey="countChars(this, 'titleStr')"}
        </td>
    </tr>
    <tr>
        <td>Keywords</td>
        <td>
		{TextBox id="txtKeywords" maxlength="100" class="text-input small-input" onkey="countChars(this, 'keywordStr')"}
        </td>
    </tr>
    <tr>
        <td>Description</td>
        <td>
                {TextBox id="txtDescription" maxlength="250" class="text-input small-input"  onkey="countChars(this, 'descriptionStr')"}
        </td>
    </tr>
    <tr>
        <td>Псевдоним страницы<br />
            <small>Только латинские буквы</small>
        </td>
        <td>
		{TextBox id="txtAlias" maxlength="100" class="text-input small-input"}
		{Validator for="txtAlias" rule="NotNull Latin" message="Введите псевдоним страницы. Псевдоним страницы может состоять только из букв латинского алфавита"}
        </td>
    </tr>
</table>
<table class="admin_table">
    <tr>
        <td style="border-top:0px">
				{CheckBox id="chkVisible" value="Отображать страницу в меню"}
				{if !$Data.HasParent }
					{*CheckBox id="chkHorizontal" value="Не показывать страницу незарегистрированным"*}
				{/if}

				{*CheckBox id="chkHideInMain" value="Отображать страницу только на внутренних"*}
				{*CheckBox id="chkWithoutDesign" value="Без дизайна"*}
        </td>
    </tr>
</table>
	{if $Data.NewPage != true}

{ajax_update}
<table class="module_add_table">
    <thead><tr><th colspan="5">Модули страницы</th></tr></thead>
    <tr>
        <td colspan="5">Тип модуля&nbsp;
            {DropDownList id="drdModulesList" class="dropdownlist" values=$Data.Modules}
            &nbsp;<input type="submit" class="button" name="handlerBtnAddModule" title="Добавить модуль на страницу" value="Добавить модуль" />
        </td>
    </tr>
	{foreach item=Item from=$Data.PageModules name=modulesList}
    <tr>
        <td width="16px" align="center">
            <input type="image" name="handlerBtnEdit" id="btnEdit" src="/img/admin/op_edit.gif" alt="Редактировать модуль" title="Редактировать модуль" onclick="return editModule({$Item.ModuleId})" />
        </td>
        <td width="16px" align="center">
					{if !$smarty.foreach.modulesList.first}
            <input type="image" name="handlerModuleBtnUp:{$Item.ModuleId}" id="btnUp" src="{$Address}img/admin/arrow_up_16.png" alt="Поднять модуль" title="Поднять модуль" />
					{/if}
        </td>
        <td width="16px" align="center">
					{if !$smarty.foreach.modulesList.last}
            <input type="image" name="handlerModuleBtnDown:{$Item.ModuleId}" id="btnDown" src="{$Address}img/admin/arrow_down_16.png" alt="Опустить модуль" title="Опустить модуль"/>
					{/if}
        </td>
        <td>{$Item.Title}</td>
        <td width="16px" align="center">
            <input type="image" name="handlerBtnModuleDelete:{$Item.ModuleId}" id="btnDelete" src="/img/admin/close_16.png" alt="Удалить модуль" onclick="return deleteModule();" title="Удалить модуль" />
        </td>
    </tr>
	{foreachelse}
    <tr><td>Модулей на данной странице пока нет</td></tr>
	{/foreach}
</table>
	{/ajax_update}

<br />
		{/if}
<p align="center">
		{if $Data.NewPage == true}
    <input type="submit" name="handlerBtnSave" value="Добавить" title="Добавить страницу" class="button"></input>
		{else}
    <input type="submit" name="handlerBtnSave" value="Сохранить" title="Сохранить изменения" class="button"></input>
		{/if}
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" class="button"></input>
</p>