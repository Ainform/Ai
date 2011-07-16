<script language="JavaScript" type="text/javascript">
    function editArchive(orderId)
    {
        window.location.href = "{$PageAddress}?OrderId=" + orderId;
        return false;
    }
</script>
<table class="admin_table">
    <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Дата доставки</th>
            <th>Оплачен</th>
            <th colspan="2">Опции</th>
        </tr>
    </thead>
    {foreach item=Item from=$Data.OrderList}
    <tr>
        <td>{$Item.id}</td>
        <td>{$Item.date}</td>
        <td>{$Item.recipient_fio}</td>
        <td>{$Item.recipient_phone}</td>
        <td>{$Item.address_date}</td>
        <td>{if $Item.paid!=0}<span class="paid">Оплачен</span>{else}Не оплачен{/if}</td>
        <td align="center" width="30"><input type="image" name="handlerBtnEdit:{$Item.id}" id="btnEdit" src="/img/admin/op_edit.gif" alt="Изменить" title="Изменить" height="16" width="16" onclick="editArchive('{$Item.id}'); return false;" /></td>
        <td align="center" width="30"><input type="image" name="handlerBtnDel:{$Item.id}" src="/img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td></tr>
    {/foreach}
</table>

{$Data.Pager}
<div class="buttons">
    <label for="email">Адрес для отправки заказов</label>
    <input type="text" class="text-input small-input" value="{$Data.email}" id="email" name="email">

    <!--Курс доллара <input size=4 type="text" value="{$Data.dollar}" name="dollar">
    Курс евро    <input size=4 type="text" value="{$Data.euro}" name="euro">-->
    <input class="button" type="submit" name="Save" value="сохранить изменения">
</div>
<!--<p align="center">
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>-->
