<?php

class BLL_ShoppingCartUtility {
    public function __construct() {

    }

    /**
     * Получает список товаров из сессии
     *
     * @return array список товаров
     */
    public static function GetGoodsList() {
        $goodsDb = new DAL_GoodsDb();
        $manufacturerDb = new DAL_ManufacturersDb();
        $goods = null;

        if (!isset($_SESSION["GoodsList"]))
            return null;

        foreach ($_SESSION["GoodsList"] as $key=>$value) {
            if ($value["Count"] > 0) {
                $goods[] = $value;
            }
        }

        if(isset($goods) && count($goods)>0) {
            foreach ($goods as &$good) {
                $good['Full']=$goodsDb->GetGood($good['GoodId']);

                $good['Price']=$good['Full']['Price'];

                $good['Full']['ManufacturerName']=$manufacturerDb->GetNameById($good['Full']['ManufacturerId']);

                $good['Full']['ManufacturerCode']=$manufacturerDb->GetCodeById($good['Full']['ManufacturerId']);
            }

            //если есть залогиненный юзер, проверяем его цену

            foreach ($goods as &$good) {

                //выставляем цену в нужной валюте, в нашем случае в рублях
                $good['Price']=$goodsDb->GetCrossPrice($good['Price'],$good['Currency'],0);

                $specDb=new DAL_SpecDb();
                $thisspec=$specDb->GetSpecOnThisDate($good['GoodId']);
                if(count($thisspec)>0) {
                    $good['Price']= $good['Price']*(1 - $thisspec[0]['Discount']/100);
                }
                unset($specDb);
            }

            $goodsDb->AddImageToGoods($goods);
           // Debug($goods);

            if(isset($_SESSION['userId'])) {
                foreach($goods as &$good) {
                    $thisdata=$goodsDb->GetPriceForUser($_SESSION['userId'],$good['Full']['Code']);
                    if($thisdata) {
                        $good['Price'] = $thisdata['Price'];
                        $good["Currency"] = $thisdata['CurrencyId'];
                    }
                    //если есть скидка на этот товар, добавляем цену со скидкой
                    $discountDb= new DAL_DiscountsDb();
                    $discount=$discountDb->GetSaleForUserByManufacturer($_SESSION['userId'],$good['Full']['ManufacturerId']);
                    if(isset($discount[0]['Discount'])) {
                        $good['PriceWithDiscount']=$good['Price']-(($good['Price']*$discount[0]['Discount'])/100);
                    }
                    else {
                        $good['PriceWithDiscount']=$good['Price'];
                    }


                }
            }
            else {
                //$good['PriceWithDiscount']=$good['Price'];
            }
        }

        unset($goodsDb);
        unset($manufacturerDb);
        return $goods;
    }

    /**
     * Добавление нового заказа в сессию
     *
     * @param int $goodId идентификатор товара
     * @param int $count количество товаров
     */
    public static function Add($goodId, $count) {
        $goodsDb = new DAL_GoodsDb();
        $good = $goodsDb->GetGood($goodId);

        if ($good == null)
            return;

        $title = $good["Title"];
        $price = $good["Price"];

        if (isset($_SESSION["GoodsList"]))
            $countGL = count($_SESSION["GoodsList"]);
        else
            $countGL = 0;

        $oldGoodIndex = self::FindIndexByGoodId($goodId);

        // если нашли старый товар, то добавим к его количеству новое число
        if ($oldGoodIndex !== null) {
            $_SESSION['GoodsList'][$oldGoodIndex]["Count"] = $_SESSION['GoodsList'][$oldGoodIndex]["Count"] + $count;

            return true;
        }

        // добавим в сессию новый товар
        $_SESSION["GoodsList"][$countGL]["GoodId"] = $goodId;
        $_SESSION["GoodsList"][$countGL]["Count"] = $count;
        $_SESSION["GoodsList"][$countGL]["Title"] = $title;
        $_SESSION["GoodsList"][$countGL]["Code"] = $good['Code'];
        $_SESSION["GoodsList"][$countGL]["Currency"] =$good['Currency'];


        return true;
    }

    /**
     *  обновление данных массива в сессии
     *
     * @param unknown_type $goodId
     * @param unknown_type $count
     * @param unknown_type $title
     * @param unknown_type $i
     */
    public static function Update($goodId, $count, $title, $price, $i = null) {
        $i = $i === null ? self::FindIndexByGoodId($goodId) : $i;

        // если индекс товара не найден
        if ($i === null || !isset($_SESSION["GoodsList"][$i]))
            return false;

        $_SESSION["GoodsList"][$i]["GoodId"] = $goodId;
        $_SESSION["GoodsList"][$i]["Count"]  = $count;
        $_SESSION["GoodsList"][$i]["Title"]  = $title;
        $_SESSION["GoodsList"][$i]["Price"] = $price;

        return true;
    }

    /**
     * Ищет товар в списке товаров по его идентификатору и возвращает его индекс
     *
     * @return unknown
     */
    public static function FindIndexByGoodId($goodId) {

        if (!isset($_SESSION["GoodsList"]))
            return null;

        foreach ($_SESSION['GoodsList'] as $key=>$value) {
            if($value["GoodId"] == $goodId) {
                return $key;
            }
        }


        return null;
    }

    /**
     * Удаление из списка заказанных товаров одного товара
     */
    public static function Delete($goodId) {
        if (!isset($_SESSION["GoodsList"]))
            return null;

        //$index = self::FindIndexByGoodId($goodId);
        //echo $index."---------------<br>";

        $newGoodsList = array();

        foreach ($_SESSION["GoodsList"] as $key=>$value)
            if ($value["GoodId"] != $goodId)
                $newGoodsList[$key] = $value;

        $_SESSION['GoodsList'] = $newGoodsList;

    }

    private static function GetMailText() {
        $summ = 0;
        $goods = self::GetGoodsList();

        $mailtext =
                "<table class=\"text order_confirm_table\" cellspacing=\"0\" align=\"center\" width=\"60%\">
		  <tHead class=\"order_confirm_table_head\">
                  <th> Фото </th>
		  <th> Наименование </th>
		  <th> Цена, руб. </th>
		  <th> Требуемое количество </th>
		  <th> Сумма, руб </th>
		  </tHead>";

        if (count($goods) > 0) {
            foreach ($goods as $value) {
                $mailtext .=
                        "<tr>
                            <td><img src='".SiteUrl.$value['Image']."&width=111&height=111&crop=1' /></td>
					 <td>".                  $value["Title"]   ."</td>
					 <td align=\"center\">". $value["Price"]   ." </td>
					 <td align=\"center\">". $value["Count"] ."</td>
					 <td align=\"center\">". $value["Count"] * $value["Price"] ."</td>
					 </tr>";

                $summ += $value["Count"] * $value["Price"];
            }
        }
        $mailtext .= "
		  <tr>
		  <td colspan=\"5\" align=\"right\">Итого:<b> ".$summ." руб.</b></td>
		  </tr>
		  </table>";
        return $mailtext;
    }

    private static function GetMailTextForUser() {
        $summ = 0;
        $goods = self::GetGoodsList();

        $mailtext =
                "<table class=\"text order_confirm_table\" cellspacing=\"0\" align=\"center\" width=\"60%\">
		  <tHead class=\"order_confirm_table_head\">
		  <th> Наименование </th>
		  <th> Цена, руб. </th>
		  <th> Требуемое количество </th>
		  <th> Сумма, руб </th>
		  </tHead>";

        if (count($goods) > 0) {
            foreach ($goods as $value) {
                $mailtext .=
                        "<tr>
					 <td>".                  $value["Title"]   ."</td>
					 <td align=\"center\">". $value["Price"]   ." </td>
					 <td align=\"center\">". $value["Count"] ."</td>
					 <td align=\"center\">". $value["Count"] * $value["Price"] ."</td>
					 </tr>";

                $summ += $value["Count"] * $value["Price"];
            }
        }
        $mailtext .= "
		  <tr>
		  <td colspan=\"5\" align=\"right\">Итого: <b>".$summ." руб.</b></td>
		  </tr>
		  </table>";
        $newsDb=new DAL_NewsDb();
        $lastnews=$newsDb->GetLastNews(3, Null);
        unset($newsDb);
        $mailtext .="<p style='font-size:9pt;'>Последняя информация с сайта:</p>";
        foreach($lastnews as $news){
        $mailtext .="<p style='font-size:8pt;'><a href='".SiteUrl.$news['Url']."'><b>".strip_tags($news['title'])."</b></a></p>";
        $mailtext .="<p style='font-size:8pt;'>".strip_tags($news['anons'])."</p><br />";
        }

        return $mailtext;
    }


    public static function MailerSend($orderid , $email , $tel,$additional='',$name='') {
        $PHP_Mailer = new PHP_Mailer();

        $PHP_Mailer->IsHTML(true);
        $PHP_Mailer->AddAddress($email, "");

        $PHP_Mailer->FromName =$from;
        $PHP_Mailer->Subject  ="Новый заказ в интернет-магазине";
        $PHP_Mailer->Body  = "Поступил <a href='".SiteUrl."admin/modules/483.php?OrderId=".$orderid."'>новый заказ</a>.";
        $PHP_Mailer->Body .= self::GetMailText();
        $PHP_Mailer->Body .= "<p><strong>Контактная информация:</strong></p> <p>имя: $name,</p><p>телефон: $tel.</p>";
        $PHP_Mailer->Body .= "<p>Описание к заказу: $additional</p>";
        //Debug($PHP_Mailer->Body);

        $PHP_Mailer->Send();
    }

    public static function MailerSendToUser($from , $email , $orderid) {
        $PHP_Mailer = new PHP_Mailer();

        $PHP_Mailer->IsHTML(true);
        $PHP_Mailer->AddAddress($email, "");

        $PHP_Mailer->FromName =$from;
        $PHP_Mailer->Subject  ="Ваш заказ";
        $PHP_Mailer->Body  = "Вы заказали на сайте ".AppName.":<br>";
        $PHP_Mailer->Body .= "Номер заказа: $orderid.";
        $PHP_Mailer->Body .= self::GetMailTextForUser();
        //Debug($PHP_Mailer->Body);

        $PHP_Mailer->Send();
    }

    //Очищает корзину товаров
    public static function ClearCart() {
        unset($_SESSION['GoodsList']);
    }
}
