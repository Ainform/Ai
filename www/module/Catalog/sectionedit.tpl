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
<table class="admin_table">
    <thead>
        <tr>
            <th style="width:150px;">Название товара</th>
            <th>Описание товара</th>
            <th style="width:100px;">Фотография</th>
            <th colspan="4">&nbsp;</th>
        </tr>
    </thead>
    {foreach item=Good from=$Data.Goods}
    <tr>
        <td>{$Good.Title}</td>
        <td>{$Good.Description}</td>
        <td width="100">{if isset($Good.Image) && $Good.Image != ""}<img src="{$Good.Image}&width=100&height=100&crop=1"  alt="{$Good.Title}" title="{$Good.Title}" width="100" />{/if}</td>
        <td align="center" width="30"><input type="image" name="handlerBtnGoodUp:{$Good.GoodId}" id="btnUp" src="{$Address}img/admin/arrow_up_16.png" alt="Поднять на 1 позицию" title="Поднять на 1 позицию"/></td>
        <td align="center" width="30"><input type="image" name="handlerBtnGoodDown:{$Good.GoodId}" id="btnDown" src="{$Address}img/admin/arrow_down_16.png" alt="Опустить на 1 позицию" title="Опустить на 1 позицию"/></td>
        <td align="center" width="30"><input type="image" name="handlerBtnEdit:{$Good.GoodId}" id="btnEdit" src="{$Address}img/admin/op_edit.gif" alt="Изменить" title="Изменить" onclick="editgood('{$Good.GoodId}'); return false;" /></td>
        <td align="center" width="30"><input type="image" name="handlerBtnGoodDel:{$Good.GoodId}" src="{$Address}img/admin/close_16.png" alt="Удаление" onclick="return confirm('Удалить новость?');" /></td>
    </tr>
    {foreachelse}
    <tr><td align="center" colspan="6">Товаров в данном разделе нет</td></tr>
    {/foreach}
</table>
{/ajax_update}
<p align="center">
    {if $Data.NewPage == false}
    <input type="submit" name="handlerBtnAddGood" value="Добавить товар" title="Добавить товар" width="90px" class="button" onclick="return newgood();"></input>
    {/if}
    <br />
    <br />
    {if $Data.ImageFolder}
    {OneImageUploader id="uplGoods" thumbWidth="100" thumbHeight="100" value=$Data.Images folder=$Data.ImageFolder}
    {/if}
    {if $Data.FilesFolder}
    {*FilesUploader2 id="uplfile" value=$Data.Files folder=$Data.FilesFolder*}
    {/if}
    <br/>
<div class="buttons">
    <input type="submit" name="handlerBtnSave" value="Сохранить" title="Сохранить изменения" width="90px" class="button"></input>
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" width="90px" class="button"></input>
</div>
</p>