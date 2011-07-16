<?

class CatalogSearchModule extends BMC_BaseModule {

     /**
      Возвращает псевдоним для товара
     */
    function GetGoodAlias($goodId) {
        return "good" . $goodId;
    }

    /**
      Возвращает ссылку на товар по его идентификатору
     */
    function GetGoodLink($params) {
        if (is_array($params))
            $id = $params['id'];
        else
            $id = $params;

        $goodsDb = new DAL_GoodsDb();
        $good = $goodsDb->GetGood($id);
        unset($goodsDb);

        $sectionpath = '';

        $this->GetSectionFullPath($good['SectionId'], $sectionpath);

        return "/internet-magazin/" . $sectionpath . $this->GetGoodAlias($id) . '/';
    }

    function GetSectionFullPath($id, &$string='') {
        $db = new DAL_SectionsDb();
        $parent = $db->GetParent($id);
        if ($string == '' && $parent == -1) {
            unset($db);
            $string = $id . "/";
            return $string;
        }
        if ($parent == -1) {
            unset($db);
            return $string;
        }

        if ($string == '') {
            $string = $id . "/";
        }

        $string = $parent . "/" . $string;
        $this->GetSectionFullPath($parent, $string);
    }

    /**
     * ??????? ??? ???????? html-???? ??????
     *
     * @return void
     */
    function DataBind() {
        $this->noform=true;
        $smarty = PHP_Smarty::GetInstance();
        $smarty->registerPlugin("function","goodLink", array($this, "GetGoodLink"));
        $goodsDb = new DAL_GoodsDb();
        $rows = array();
        if (isset($_GET['q'])) {

            $rows = $goodsDb->query("SELECT * from `goods` WHERE `Title` LIKE '%" . $_GET['q'] . "%' OR `Description` LIKE '%" . $_GET['q'] . "%' OR `Abstract` LIKE '%" . $_GET['q'] . "%'");
        }

        foreach ($rows as $row) {

            if (is_numeric(BLL_ShoppingCartUtility::FindIndexByGoodId($row['GoodId']))) {

                $this->data['AddedToCart'][$row['GoodId']] = "Товар добавлен в <a href='/cart/'>корзину</a>";
            }
        }

        foreach ($rows as &$row) {
            $row['CurrencyName'] = $goodsDb->GetCurrencyName($row['Currency']);
            if ($row['Price'] == 0) {
                $row['Price'] = "цена не указана";
            } else {
                $row['Price'] = number_format($row['Price'], 2, ',', ' ');
            }
        }
        $goodsDb->AddImageToGoods($rows);
      //  Debug($rows,false);

        $this->data["GoodsList"] = $rows;
        unset($goodsDb);
    }

       /**
     * Обработчик нажатия на клавишу заказа
     */
    function handlerBtnAdd($GoodId=null) {

        if (IsValid ()) {
            if ($GoodId) {
                $goodId = $GoodId;
            } elseif (Request("goodId")) {
                $goodId = Request("goodId");
            } else {
                return false;
            }

            $count = 1; //intval($this->data['txtOrderCount']);

            if ($count > 0)
                BLL_ShoppingCartUtility::Add($goodId, $count);

            //Redirect("/cart.php");
            //$this->data['BtnAddPostBack'] = true;
            //$this->data['AddedToCart'][$goodId] = "Товары добавлены в <a href='/cart/'>корзину</a>";
        }
    }

}

?>