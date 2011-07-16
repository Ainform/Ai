{php}
$all_tpl_vars = $this->get_template_vars('Data');

// take a look at them
// 2.
// Оплата заданной суммы с выбором валюты на сайте ROBOKASSA
// Payment of the set sum with a choice of currency on site ROBOKASSA

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
$mrh_login = "buketufa";
$mrh_pass1 = "buketufa00";

// номер заказа
// number of order
$inv_id = $all_tpl_vars['orderId'];

// описание заказа
// order description
$inv_desc = "Заказ с сайта, №".$all_tpl_vars['orderId'];

// сумма заказа
// sum of order
$out_summ = $all_tpl_vars['Summ'];

// тип товара
// code of goods
$shp_item = "2";

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "";

// язык
// language
$culture = "ru";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// форма оплаты товара
// payment form
print "<p><form name='robo' id='robo' action='http://test.robokassa.ru/Index.aspx' method=POST>".
      "<input type=hidden name=MrchLogin value=$mrh_login>".
      "<input type=hidden name=OutSum value=$out_summ>".
      "<input type=hidden name=InvId value=$inv_id>".
      "<input type=hidden name=Desc value='$inv_desc'>".
      "<input type=hidden name=SignatureValue value=$crc>".
      "<input type=hidden name=Shp_item value='$shp_item'>".
      "<input type=hidden name=IncCurrLabel value=$in_curr>".
      "<input type=hidden name=Culture value=$culture>".
      "<input type=submit value='Оплатить на сайте Робокассы'>".
      "</form><script>document.forms['robo'].submit()</script></p>";
{/php}