<table>
<tr><td>Дата заказа:</td><td>{$Data.Order.0.date}</td></tr>
<tr><td>ФИО:</td><td> {$Data.Order.0.recipient_fio}</td></tr>
<tr><td>Телефон:</td><td> {$Data.Order.0.recipient_phone}</td></tr>
<tr><td>Полный адрес:</td><td>{$Data.Order.0.address_full}</td></tr>
<tr><td>Дата доставки:</td><td> {$Data.Order.0.address_date}</td></tr>
<tr><td>Время доставки:</td><td> {$Data.Order.0.address_time}</td></tr>
<tr><th colspan="2">Персональные данные для карты</th></tr>
<tr><td>Оформить карту почётного гостя:</td><td>{if $Data.Order.0.card==1}да{else}нет{/if}</td></tr>
<tr><td>ФИО:</td><td> {$Data.Order.0.contact_fio}</td></tr>
<tr><td>Телефон:</td><td>{$Data.Order.0.contact_phone}</td></tr>
<tr><td>Емайл:</td><td> {$Data.Order.0.contact_email}</td></tr>
<tr><td>Дата рождения:</td><td> {$Data.Order.0.contact_birthdate}</td></tr>
<tr><th colspan="2">Открытка</th></tr>
<tr><td>Нужна:</td><td> {if $Data.Order.0.postcard==1}да{else}нет{/if}</td></tr>
<tr><td>Текст к открытке:</td><td>{$Data.Order.0.postcard_text}</td></tr>
<tr><th colspan="2">Прочее</th></tr>
<tr><td>Сообщать о скидках и акциях:</td><td> {if $Data.Order.0.discounts_and_actions==1}да{else}нет{/if}</td></tr>
</table>
<h1>Заказ:</h1>
<table class="admin_table">
  <tr>
	 <th>№</th>
	 <th>Товар</th>
	 <th>Количество</th>
	 <th>Цена</th>
	 </tr>
{foreach item=Item from=$Data.Order.Items}
<tr>
	<td>{$Item.id}</td>
	 <td><a href="{goodLink id=$Item.goodid}">{$Item.goodname}</a></td>
	 <td>{$Item.count}</td>
	 <td>{$Item.price}</td>
</tr>
{/foreach}
<tr><td colspan="3" align="right"><strong>Итого:</strong></td><td>{$Data.Order.Summ}</td></tr>

</table>