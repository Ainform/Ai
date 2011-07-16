<?

/**
 * Скрытый модуль корзины товаров
 */
class CartModule extends BMC_BaseModule {

    function DataBind() {
        $this->data["HeadText"] = "";

        if (!$this->isPostBack) {
            if (Request("step", 0) === 'delivery') {
                $this->template = "step_1.tpl";
            }
            if (Request("step", 0) === 'check') {
                $this->template = "step_2.tpl";
            }
        }

        $goodsDb = new DAL_GoodsDb();
        $smarty = PHP_Smarty::GetInstance();
        $smarty->registerPlugin("function","goodLink", array($goodsDb, "GetGoodLink"));

        //получаем список открыток
        $cardsecid = 326;
        $cards = $goodsDb->GetFromSection($cardsecid, 0, 9);
        $goodsDb->AddImageToGoods($cards);
        foreach ($cards as &$row) {
            if (is_numeric(BLL_ShoppingCartUtility::FindIndexByGoodId($row['GoodId']))) {
                $this->data['AddedToCart'][$row['GoodId']] = "Открытка уже есть в заказе";
            }
        }
        foreach ($cards as &$row) {
            $row['CurrencyName'] = $goodsDb->GetCurrencyName($row['Currency']);
            if ($row['Price'] != 0) {
                $row['Price'] = number_format($row['Price'], 2, ',', ' ');
            }
        }

        $this->data["cardslist"] = $cards;

        //получаем список подарков
        $giftsecid = 324;
        $gifts = $goodsDb->GetAllFromSectionAndSubsection($giftsecid, 0, 99);
        $goodsDb->AddImageToGoods($gifts);
        foreach ($gifts as &$row) {
            if (is_numeric(BLL_ShoppingCartUtility::FindIndexByGoodId($row['GoodId']))) {
                $this->data['AddedToCart'][$row['GoodId']] = "Товар уже есть в заказе";
            }
        }
        foreach ($gifts as &$row) {
            $row['CurrencyName'] = $goodsDb->GetCurrencyName($row['Currency']);
            if ($row['Price'] != 0) {
                $row['Price'] = number_format($row['Price'], 2, ',', ' ');
            }
        }

        $this->data["giftslist"] = $gifts;

        $this->data["eurorate"] = $goodsDb->GetRate(2);
        $this->data["dollarrate"] = $goodsDb->GetRate(1);
        unset($goodsDb);

        $goodsList = BLL_ShoppingCartUtility::GetGoodsList();

        //Debug($goodsList);


        if (isset($_SESSION['userId'])) {
            $userDb = new DAL_UsersDb();
            $temp = $userDb->GetUser($_SESSION['userId']);
            if (count($temp) > 0) {
                $this->data["User"] = $temp[0];
            }
        }
        $this->data["GoodsList"] = $goodsList;
    }

    function handlerMakeOrder() {
        if (isset($_SESSION['userId'])) {

            $userDb = new DAL_UsersDb();
            $user = $userDb->GetUser($_SESSION['userId']);

            // заносим данные в таблицу для админки
            $order = new DAL_OrderDb();

            $orderId = $order->AddOrder(
                            $user[0]['Name'], $user[0]['Phone'], $user[0]['Email'], @Request("additional"));

            $this->data["HeadText"] = "<center><b>Большое спасибо, заявка принята (номер заявки: $orderId). С Вами свяжутся в ближайшее время.</b></center>";

            // получаем все товары
            $goodsList = BLL_ShoppingCartUtility::GetGoodsList();

            $orderItems = new DAL_OrderItemsDb();

            // и добавлям их в таблицу
            foreach ($goodsList as $value) {
                $orderItems->AddOrderItem($orderId, $value["GoodId"], $value["Count"], trim($value["Price"]));
            }

            $configDb = new DAL_ConfigDb();
            $temp = $configDb->configSelect(array("key" => "OrderEmail"));

            BLL_ShoppingCartUtility::MailerSendToUser($temp['value'], $user[0]['Email'], $orderId);
            BLL_ShoppingCartUtility::MailerSend($user[0]['Email'], $temp['value'], $user[0]['Phone'], @Request("additional"), $user[0]['Name']);
            BLL_ShoppingCartUtility::ClearCart();
        } else {

            //проверка на корректность введенного почтового адреса
            //$is_mail_ok = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', Request("txtOrderEmail"));
            //if ($is_mail_ok && Request('txtOrderName') && Request('txtOrderPhone')) {
            // заносим данные в таблицу для админки
            $order = new DAL_OrderDb();
            $OrderItemsDb = new DAL_OrderItemsDb();
            $orderId = $order->AddOrder(
                            $_SESSION['recipient_fio'], $_SESSION['recipient_phone'], $_SESSION['postcard'], $_SESSION['postcard_text'], $_SESSION['address_full'], $_SESSION['address_date'], $_SESSION['address_time'], $_SESSION['contact_fio'], $_SESSION['contact_phone'], $_SESSION['contact_email'], $_SESSION['contact_birthdate'], $_SESSION['discounts_and_actions'], $_SESSION['card']);

            $this->data["HeadText"] = "<p>Большое спасибо, заказ принят (номер заказа:<strong> $orderId </strong>).</p><p> С Вами свяжутся в ближайшее время.</p>";



            // получаем все товары
            $goodsList = BLL_ShoppingCartUtility::GetGoodsList();

            //Debug($goodsList);
            //Debug($orderId);

            $orderItems = new DAL_OrderItemsDb();

            // и добавлям их в таблицу
            foreach ($goodsList as $value) {
                $orderItems->AddOrderItem($orderId, $value["GoodId"], $value["Count"], trim($value["Price"]));
            }


            $this->data["orderId"] = $orderId;

            $items = $OrderItemsDb->GetOrderItem($orderId);

            $goodsDb = new DAL_GoodsDb();
            $summ = 0;
            foreach ($items as &$item) {
                $item['goodname'] = $goodsDb->GetNameById($item['goodid']);
                $good = $goodsDb->GetGood($item['goodid']);
                $item['goodlink'] = "/" . $good['SectionId'] . "/good" . $item['goodid'];
                $summ+= ( ($item['price'] * $item['count']));
            }
            unset($goodsDb);

            $this->data['Summ'] = $summ;




            $configDb = new DAL_ConfigDb();
            $temp = $configDb->configSelect(array("key" => "OrderEmail"));

            //BLL_ShoppingCartUtility::MailerSendToUser($temp['value'], $this->data['txtOrderEmail'], $orderId);
            $text =" <table>".
"<tr><td>ФИО:</td><td>".$_SESSION['recipient_fio']."</td></tr>".
"<tr><td>Телефон:</td><td>".$_SESSION['recipient_phone']."</td></tr>".
"<tr><td>Полный адрес:</td><td>".$_SESSION['address_full']."</td></tr>".
"<tr><td>Дата доставки:</td><td>".$_SESSION['address_date']."</td></tr>".
"<tr><td>Время доставки:</td><td>".$_SESSION['address_time']."</td></tr>".
"<tr><td colspan='2'>Персональные данные для карты</td></tr>".
"<tr><td>Оформить карту почётного гостя:</td><td>".($_SESSION['card']==1?'да':'нет')."</td></tr>".
"<tr><td>ФИО:</td><td>".$_SESSION['contact_fio']."</td></tr>".
"<tr><td>Телефон:</td><td>".$_SESSION['contact_phone']."</td></tr>".
"<tr><td>Емайл:</td><td>".$_SESSION['contact_email']."</td></tr>".
"<tr><td>Дата рождения:</td><td>".$_SESSION['contact_birthdate']."</td></tr>".
"<tr><td colspan='2'>Открытка</td></tr>".
"<tr><td>Нужна:</td><td>". ($_SESSION['postcard']==1?'да':'нет')."</td></tr>".
"<tr><td>Текст к открытке:</td><td>".$_SESSION['postcard_text']."</td></tr>".
"<tr><td colspan='2'>Прочее</td></tr>".
"<tr><td>Сообщать о скидках и акциях:</td><td>".($_SESSION['discounts_and_actions']==1?'да':'нет')."</td></tr>".
"</table>";

            BLL_ShoppingCartUtility::MailerSend($orderId, $temp['value'], $_SESSION['recipient_phone'], $text, $_SESSION['recipient_fio']);
            //BLL_ShoppingCartUtility::ClearCart();
            //unset ($_SESSION['recipient_fio'], $_SESSION['recipient_phone'], $_SESSION['postcard'], $_SESSION['postcard_text'], $_SESSION['address_full'], $_SESSION['address_date'], $_SESSION['address_time'], $_SESSION['contact_fio'], $_SESSION['contact_phone'], $_SESSION['contact_email'], $_SESSION['contact_birthdate'], $_SESSION['discounts_and_actions'], $_SESSION['card']);

            $this->noform = true;
            $this->template = "robokassa.tpl";
            //}
        }
    }

    function OnModuleAdd() {

    }

    function OnModuleDelete() {

    }

    function __construct($moduleId) {
        $this->cssClass = "cart";
        parent::__construct($moduleId);
    }

    /**
     * Обработка кнопки удаления товара
     */
    function handlerBtnDelete($goodId) {
        if (intval($goodId) > 0)
            BLL_ShoppingCartUtility::Delete($goodId);

        $this->DataBind();
    }

    function handlerClearCart() {
        BLL_ShoppingCartUtility::ClearCart();
    }

    function handlerRecalculate() {

        $goodsList = BLL_ShoppingCartUtility::GetGoodsList();
        foreach ($goodsList as &$good) {
            if (isset($this->data['count' . $good['GoodId']])) {
                $good['Count'] = $this->data['count' . $good['GoodId']];
                BLL_ShoppingCartUtility::Update($good['GoodId'], $this->data['count' . $good['GoodId']], $good['Title'], $good['Price']);
            }
        }

        $this->data["GoodsList"] = $goodsList;
    }

    function handlerBtnAdd($GoodId=null) {

        //if (IsValid()) {
        if ($GoodId) {
            $goodId = $GoodId;
        } elseif (Request("goodId")) {
            $goodId = Request("goodId");
        } else {
            return false;
        }

        $count = 1; //intval($this->data['txtOrderCount']);

        if ($count > 0) {
            BLL_ShoppingCartUtility::Add($goodId, $count);
        }

        //Redirect("/cart.php");
        //$this->data['BtnAddPostBack'] = true;
        //$this->data['AddedToCart'][$goodId] = "Товары добавлены в <a href='/cart/'>корзину</a>";
        //}
    }

    function handlerStep2Delivery() {
        if (isset($this->data['postcard'])) {
            $_SESSION['postcard'] = true;
            $_SESSION['postcard_text'] = $this->data['postcard_text'];
        } else {
            unset($_SESSION['postcard']);
            $_SESSION['postcard_text'] = NULL;
        }
        unset($_SESSION['delivery']);
        $this->template = "step_1.tpl";
    }

    function handlerStep3Delivery() {

        $this->template = "step_2.tpl";

        if (isset($this->data['recipient_fio'])) {
            $_SESSION['recipient_fio'] = $this->data['recipient_fio'];
        }
        if (isset($this->data['recipient_phone'])) {
            $_SESSION['recipient_phone'] = $this->data['recipient_phone'];
        }

        if (isset($this->data['address_full'])) {
            $_SESSION['address_full'] = $this->data['address_full'];
        }
        if (isset($this->data['address_date'])) {
            $_SESSION['address_date'] = $this->data['address_date'];
        }
        if (isset($this->data['address_time'])) {
            $_SESSION['address_time'] = $this->data['address_time'];
        }

        if (isset($this->data['contact_fio'])) {
            $_SESSION['contact_fio'] = $this->data['contact_fio'];
        }
        if (isset($this->data['contact_phone'])) {
            $_SESSION['contact_phone'] = $this->data['contact_phone'];
        }
        if (isset($this->data['contact_email'])) {
            $_SESSION['contact_email'] = $this->data['contact_email'];
        }
        if (isset($this->data['contact_birthdate'])) {
            $_SESSION['contact_birthdate'] = $this->data['contact_birthdate'];
        }

        if (isset($this->data['discounts_and_actions'])) {
            $_SESSION['discounts_and_actions'] = true;
        } else {
            unset($_SESSION['discounts_and_actions']);
        }
        if (isset($this->data['card'])) {
            $_SESSION['card'] = true;

            $contact_error = false;
            if (empty($_SESSION['contact_fio'])) {
                $contact_error = true;
            }
            if (empty($_SESSION['contact_phone'])) {
                $contact_error = true;
            }
            if (empty($_SESSION['contact_email'])) {
                $contact_error = true;
            }
            if (empty($_SESSION['contact_birthdate'])) {
                $contact_error = true;
            }
            if ($contact_error == true) {
                $this->data['error'] = "Вы должны заполнить все <a href='#contact_a'>контактные данные</a> для получения карты почётного гостя";
                $this->template = "step_1.tpl";
            }
        } else {
            unset($_SESSION['card']);
        }
        if (isset($this->data['delivery'])) {
            $_SESSION['delivery'] = true;
        } else {
            $this->data['error'] = "Вы должны согласиться с <a href='#delivery_a'>условиями доставки</a> для перехода к следующему шагу!";
            $this->template = "step_1.tpl";
        }
        //wtf($this, false);
    }

}

?>