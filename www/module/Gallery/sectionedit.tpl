<script language="JavaScript" type="text/javascript">
    function editgood(goodId)
    {
        window.location.href = "{$PageAddress}?sectionId={$Data.SectionId}&goodId=" + goodId;
        return false;
    }
    function newgood()
    {
        window.location.href = "{$PageAddress}?sectionId={$Data.SectionId}&goodNew=1";
        return false;
    }
</script>
{if $Data.ImageFolder}
{OneImageUploader id="uplGoods" thumbWidth="100" thumbHeight="100" title="Изображение раздела" value=$Data.Images folder=$Data.ImageFolder}
{/if}
<br />
<table class="admin_table">
    <tr>
        <td style="width:150px;">Заголовок</td>
        <td>
		{TextBox id="txtName" maxlength="100" class="text-input small-input"}
		{Validator for="txtName" rule="NotNull" message="Введите заголовок раздела"}
    </tr>
    <tr>
        <td>Описание раздела</td>
        <td>
		{FCKEditor id="fckText" height="150" value=$Data.Description simple="True"}
    </tr>
</table>
{ajax_update}
<table class="admin_table" border="1">
    <tr><th width="150">Название</th><th>Описание</th><th width="100">Фотография</th><th colspan="4">&nbsp;</th></tr>
    {foreach item=Good from=$Data.Goods}
    <tr>
        <td>{$Good.Title}</td>
        <td>{$Good.Description}</td>
        <td width="100">
            {if isset($Good.Image) && $Good.Image != ""}
            <img src="{$Good.Image}&width=100&height=100&crop=1"  alt="{$Good.Title}" title="{$Good.Title}" width="100" />
            {/if}
        </td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnGoodUp:{$Good.GoodId}" id="btnUp" src="{$Address}img/admin/arrow_up_16.png" alt="Поднять на 1 позицию" title="Поднять на 1 позицию" height="16" width="16"  /></td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnGoodDown:{$Good.GoodId}" id="btnDown" src="{$Address}img/admin/arrow_down_16.png" alt="Опустить на 1 позицию" title="Опустить на 1 позицию" height="16" width="16"  /></td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnEdit:{$Good.GoodId}" id="btnEdit" src="{$Address}img/admin/op_edit.gif" alt="Изменить" title="Изменить" height="16" width="16" onclick="editgood('{$Good.GoodId}'); return false;" /></td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnGoodDel:{$Good.GoodId}" src="{$Address}img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td>
    </tr>
    {foreachelse}
    <tr><td align="center" colspan="6">Фотографий в данном разделе нет</td></tr>
    {/foreach}
</table>
{/ajax_update}

{if $Data.NewPage == false}
<div style="text-align:center">
<br />
<input type="submit" name="handlerBtnAddGood" value="Добавить фото" title="Добавить фото" class="button" onclick="return newgood();" />
</div>
{/if}

<br />
<div class="buttons">
<input type="submit" name="handlerBtnSave" value="Сохранить" title="Сохранить изменения"  class="button" />
<input type="submit" name="handlerBtnCancel" value="Назад" title="Назад"  class="button" />
</div>