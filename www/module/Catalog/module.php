<?php

/**
 * Catalog class
 * Список главных разделов каталога
 *
 * @author Frame
 * @version Catalog.tpl, v 1.0.1
 * @copyright (c) by VisualDesign
 */
class CatalogModule extends BMC_BaseModule {
    /**
     * Конструктор, задает параметры
     *
     * @return void
     */
    //	function __construct()
    //	{
    //		Utility_Sections::CreateBreadCrumbsSections(-1, 'Каталог продукции', '/', '', new DAL_Sections_Catalog());
    //	}

    /**
     * Функция для биндинга данных модуля
     *
     * @return void
     */
    function DataBind() {

        $smarty = PHP_Smarty::GetInstance();
		$secDb = new DAL_SectionsDb();
        $smarty->registerPlugin("function","sectionLink", array($secDb, "GetSectionLink"));
		unset($secDb);

        $smarty->assign("good_left_column", "");
        $smarty->assign("isGoodList", "0"); // выводить ли список продукции в текущей секции
        $smarty->assign("Order", "0");

        //		$smarty->registerPlugin("function","GetGoodCurrentLink", array($this, "GetGoodCurrentLink"));
        //Debug(Request());
        $this->curPage = Request("pageNum");
        $sectionId = Request("sectionId");
        $goodId = Request("goodId");
        $manufacturerId = Request("manufacturerId");
        $goodsDb = new DAL_GoodsDb();
        $smarty->registerPlugin("function","goodLink", array($goodsDb, "GetGoodLink"));
        $good = $goodsDb->GetGood($goodId);

        $smarty->assign("goodId", $goodId);
        $smarty->assign("title", $good["Title"]);
        $smarty->assign("price", $good["Price"] > 0 ? $good["Price"] : "Неизвестна");
        $smarty->assign("code", $good["Code"]);

        // Debug("test");
        $rows = array();
        //$smarty->assign('MenuLeftItems1', $rows);
//Debug($sectionId,false);
//Debug($goodId,false);

        if ($manufacturerId) {
            $this->BindPriceByManufacturer($manufacturerId);
        }
        if (!empty($sectionId)) {
            //Debug($sectionId);
            if (isset($goodId) && $goodId >= 0) {
                return $this->BindGood($goodId, $sectionId);
            }

            $smarty->assign("ShowRightColumn", false);
            return $this->BindGoods($sectionId);
        }
//Debug("test");
        $smarty->assign("ShowRightColumn", false);
        if ($manufacturerId) {
            $smarty->assign("ShowOnlyThisModuleId", $this->moduleId);
            $this->BindListByManufacturer(-1, $manufacturerId);
        } else {
            $this->BindList();
        }
    }

    /**
     * @desc Биндит хлебные крошки для данной страницы
     * @return array('Title' => 'Url')
     */
    /* function BindBreadCrumb($sectionId, $goodId = null) {
      // получаем список всех разделов
      $sectionsDb = new DAL_SectionsDb();
      $sections = $sectionsDb->GetAllSections($this->moduleId);
      $sectionRows = array();

      foreach ($sections as $section)
      $sectionRows[$section['SectionId']] = $section;

      $current = $sectionRows[$sectionId];
      $parent = $current['ParentId'];

      $breadCrumbs = BLL_BreadCrumbs::getInstance();

      $curPage = BMC_SiteMap::GetCurPage();
      //Debug($curPage);
      $curPath = $curPage->Path;

      // если мы внутри товара, то вставляем текущий раздел
      if ($goodId != null && $goodId > 0) {
      $curPath = dirname($curPath);

      $breadCrumbs->AddFirst($current['Title'], $curPath.".php");
      }

      while (isset($sectionRows[$parent])) {
      $current = $sectionRows[$parent];
      $parent = $current['ParentId'];
      $curPath = dirname($curPath);

      $breadCrumbs->AddFirst($current['Title'], $curPath.".php");
      }

      $breadCrumbs->AddFirst("Наша продукция", dirname($curPath).".php");
      } */

     function GetTopParent($id, $sectionId, &$result) {
        $db = new DAL_SectionsDb();
        $result = $id;
        $parent = $db->GetParent($id);
        if ($parent == $sectionId || $parent == -1) {
            unset($db);
            return;
        }
        $this->GetTopParent($parent, $sectionId, $result);
    }

    function GetSectionAllParents($id, &$string='') {
        $db = new DAL_SectionsDb();

        $parent = $db->GetParent($id);
        if ($parent == -1) {
            unset($db);
            return $string;
        }
        $string = $parent . "/" . $string;
        $this->GetSectionFullPath($parent, $string);
    }

    /**
     * Создает список товаров
     *
     * @return string
     */
    private function BindList($sectionId = -1) {


        $smarty = PHP_Smarty::GetInstance();
        $smarty->assign('secid', $sectionId);


        $db = new DAL_SectionsDb();


        if (!$sectionId == -1) {
            $rootsectionimage = $db->GetSectionImage($sectionId);
            if (isset($rootsectionimage)) {
                $smarty->assign('rootsectionimage', Helpers_Image::GetImage($rootsectionimage, 0, 0, "", true));
            } else {
                $smarty->assign('rootsectionimage', '<img id="imageContainerImg" title="" alt="" src="/img/default.gif" align="left" hspace="10">');
            }
        }


        // биндим данные текущей секции
        if ($sectionId != -1) {

            $row = $db->GetSection($sectionId);

            if ($row != null)
                $this->data["Description"] = $row['Description'];

            $rows = $db->GetSections($sectionId);


            /* $rows1=$db->GetSections($db->GetParent($sectionId));
              foreach($rows1 as &$row) {
              $section_full_url="";
              $row['Alias']=$row['SectionId'];
              $row['Name']=$row['Title'];
              $db->GetSectionFullPath($row['SectionId'], $section_full_url);
              $row['Url']=$page_full_url.$section_full_url;
              }
              $smarty->assign('MenuLeftItems1', $rows1); */

            //генерим меню
            $page_full_url = "";
            $curPage = BMC_SiteMap::GetCurPage();
            $pagesDb = new DAL_PagesDb();
            $pagesDb->GetPageFullPath($curPage->PageId, $page_full_url);
            // Debug($curPage->PageId,false);
            //Debug($curPage->Parent->PageId,false);
            unset($pagesDb);

            $rows1 = $db->GetTree();
            foreach ($rows1 as &$row) {
                $section_full_url = "";
                if (strstr($_SERVER['REQUEST_URI'], $row['SectionId'])) {
                    $row['Selected'] = true;
                }

                foreach ($rows1 as $subrow) {
                    if ($subrow['ParentId'] == $row['SectionId']) {
                        $row['hasChild'] = true;
                    }
                }

                $row['Alias'] = $row['SectionId'];
                $row['Name'] = $row['Title'];
                $db->GetSectionFullPath($row['SectionId'], $section_full_url);
                $row['Url'] = $page_full_url . $section_full_url;
            }
            $smarty->assign('MenuLeftItems1', $rows1);
            $smarty->assign('shop', true);
            //законичли генерить меню

            if (0 == count($rows)) {
                unset($db);
                return;
            }

            foreach ($rows as &$section) {
                $section["childSections"] = $db->GetSections($section["SectionId"],null,$this->moduleId);
            }

            foreach ($rows as &$row) {
                $image = $db->GetSectionImage($row['SectionId']);
                if (isset($image)) {
                    $imgPath = DAL_ImagesDb::GetImagePath($image);
                    $row['Image'] = $imgPath;
                } else {
                    $row['Image'] = '';
                }
            }

            $this->data['isnotroot'] = 1;

            $this->data["SectionList"] = $rows;

            return;
        }

        // биндим корневые разделы для данного модуля
        $rows = $db->GetRootSections($this->moduleId);
        unset($db);

        if (0 == count($rows))
            return;

        // скроем разделы, в которых нет товаров
        $gooddb = new DAL_GoodsDb();
        foreach ($rows as &$val) {
            $gds = $gooddb->GetFromSection($val["SectionId"]);

            $val["CountChilds"] = count($gds);
        }

        unset($gooddb);

        $db = new DAL_SectionsDb();

        foreach ($rows as &$section) {
            $section["childSections"] = $db->GetSections($section["SectionId"],null,$this->moduleId);
        }

        foreach ($rows as &$row) {
            $image = $db->GetSectionImage($row['SectionId']);
            if (isset($image)) {
                $imgPath = DAL_ImagesDb::GetImagePath($image);
                $row['Image'] = $imgPath;
            } else {
                $row['Image'] = '';
            }
        }


        //генерим меню
        $page_full_url = "";
        $curPage = BMC_SiteMap::GetCurPage();
        $pagesDb = new DAL_PagesDb();
        $pagesDb->GetPageFullPath($curPage->PageId, $page_full_url);
        unset($pagesDb);

        $rows1 = $db->GetTree();

        foreach ($rows1 as &$row) {
            $section_full_url = "";
            if (strstr($_SERVER['REQUEST_URI'], $row['SectionId'])) {
                $row['Selected'] = true;
            }
            $row['Alias'] = $row['SectionId'];
            foreach ($rows1 as $subrow) {
                if ($subrow['ParentId'] == $row['SectionId']) {
                    $row['hasChild'] = true;
                }
            }
            $row['Name'] = $row['Title'];
            $db->GetSectionFullPath($row['SectionId'], $section_full_url);
            $row['Url'] = $page_full_url . $section_full_url;
        }
        //Debug($rows1);
        $smarty->assign('MenuLeftItems1', $rows1);
        $smarty->assign('shop', true);
        //законичли генерить меню

        unset($db);

        $this->data["SectionList"] = $rows;
    }

    /**
     * Возвращает true, если элемент должен остаться в меню
     */
    public function ExludeElements($var) {
        return $var["CountChilds"] > 0;
    }

    function GetGoodTitle($id) {
        $goodsDb = new DAL_GoodsDb();
        $good = $goodsDb->GetGood($id);

        return $good["Title"];
    }

    /**
      Биндим данные по конкретному товару
     */
    private function BindGood($goodId, $sectionId = null) {
        $smarty = PHP_Smarty::GetInstance();
        //$smarty->register_function("GetGoodTitle", array($this, "GetGoodTitle"));
        $smarty->assign("good_left_column", "none");
        $smarty->assign("isGoodList", "1");

        $title = $this->GetGoodTitle($goodId);
        $smarty->assign("title", $title);


        if ($sectionId != null) {
            $this->BindGoods($sectionId);
            $smarty->assign("Data", $this->data);
        } else {
            $smarty->assign("Data", null);
        }

        // проходимся по списку товаров
        $goodsDb = new DAL_GoodsDb();
        $good = $goodsDb->GetGood($goodId);

        // если на получили не массив, то выходим
        if (!is_array($good))
            return;

        $folder = DAL_GoodsDb::GetFilesFolder($goodId);

        $gFilesDb = new DAL_AnalyticsFilesDb();

        $gfiles = $gFilesDb->GetFromFolder($folder);

        $this->DistibuteFiles($gfiles, "good$goodId");

        // получаем изображения ассоциированные с товаром
        $images = $goodsDb->GetGoodImages($goodId);

        $flv = array();
        $pdf = array();

        /* Вытаскиваем пдфки и флвешки */
        foreach ($images as $i => $row) {
            if (substr($row["FileName"], -3) == "pdf") {
                $row["src"] = md5($row["Folder"]) . "/" . md5($row["FileName"]) . ".pdf";

                $pdf[] = $row;
                unset($images[$i]);
                continue;
            }

            if (substr($row["FileName"], -3) == "flv") {
                $row["src"] = md5($row["Folder"]) . "/" . md5($row["FileName"]) . ".flv";
                $flv[] = $row;
                unset($images[$i]);
            }
        }
        //print_r($flv);
        $this->data["flv"] = $flv;
        $this->data["pdf"] = $pdf;


        if (count($images) > 0) {
            $good['Images'] = $images;
            foreach ($good['Images'] as &$image) {
                $image['Path'] = DAL_ImagesDb::GetImagePath($image);
            }
        }




        //делаем массив из наших характеристик
        if ($good['Properties'] != '') {
            $var = preg_split("/[\n\r]+/s", $good['Properties']);
            unset($good['Properties']);

            foreach ($var as $v) {
                $good['Properties'][] = preg_split("/[:]+/s", $v);
            }
        }

        $good['Abstract'] = preg_replace("#<table([^>]*?)>#i", "<table\\1>", $good['Abstract']);

        //Если пользователь залогинен
        if (isset($_SESSION['userId'])) {
            $thisdata = $goodsDb->GetPriceForUser($_SESSION['userId'], $good['Code']);
            if ($thisdata) {
                $good['Price'] = $thisdata['Price'];
                $good['Currency'] = $thisdata['CurrencyId'];
            }
        }

        $good['CurrencyName'] = $goodsDb->GetCurrencyName($good['Currency']);
        if ($good['Price'] != 0) {
            $good['Price'] = number_format($good['Price'], 2, ',', ' ');
        }

        $manufacturerDb = new DAL_ManufacturersDb();
        $good['ManufacturerName'] = $manufacturerDb->GetNameById($good['ManufacturerId']);
        unset($manufacturerDb);
        // передаём данные по товару в шаблон
        $this->data['Good'] = $good;

        if (isset($_SESSION["GoodsList"])) {
            $basket = $_SESSION["GoodsList"];
            $smarty->assign("basket", $basket);
        }



        if (is_int(BLL_ShoppingCartUtility::FindIndexByGoodId($good['GoodId']))) {

            $this->data['AddedToCart'][$good['GoodId']] = "Товар добавлен в <a href='/cart/'>корзину</a>";
        } else {

            $this->data['AddedToCart'][$good['GoodId']] = null;
        }

        $smarty->assign("IsGoodShow", 1);


        //goods list
        $sectionId = $good['SectionId'];

        $smarty = PHP_Smarty::GetInstance();

        $goodsDb = new DAL_GoodsDb();

        $rows = $goodsDb->GetFromSection($sectionId, 0, 10000000);

        $goodsDb->AddImageToGoods($rows);

        //usort($rows, array($this, "getSort"));

        $goodssort = array();
        $cleangoods = array();

        foreach ($rows as &$row) {

            if (is_int(BLL_ShoppingCartUtility::FindIndexByGoodId($row['GoodId']))) {

                $this->data['AddedToCart'][$row['GoodId']] = "Товар добавлен в <a href='/cart/'>корзину</a>";
            } else {
                $this->data['AddedToCart'][$row['GoodId']] = null;
            }


            $row['CurrencyName'] = $goodsDb->GetCurrencyName($row['Currency']);
            if ($row['Price'] == 0) {
                $row['Price'] = number_format($row['Price'], 2, ',', ' ');
            }
            if ($row['GoodId'] != $goodId) {
                $cleangoods[] = $row;
            }
            $goodssort[] = $row;
        }
        $goodssortcount = count($goodssort);
        $nextgood = $goodId;
        $prevgood = $goodId;
        for ($i = 0; $i < $goodssortcount; $i++) {
            if ($goodssort[$i]['GoodId'] == $goodId) {
                if ($i == $goodssortcount - 1) {
                    $nextgood = null; //$goodssort[0];
                    if (isset($goodssort[$i - 1])) {
                        $prevgood = $goodssort[$i - 1];
                    } else {
                        $prevgood = null;
                    }
                } elseif ($i == 0) {
                    if (isset($goodssort[$i + 1])) {
                        $nextgood = $goodssort[$i + 1];
                    } else {
                        $nextgood = null;
                    }
                    $prevgood = null; //$goodssort[$goodssortcount - 1];
                } else {
                    $nextgood = $goodssort[$i + 1];
                    $prevgood = $goodssort[$i - 1];
                }
            }
        }
        $this->data["nextGood"] = $nextgood;
        $this->data["prevGood"] = $prevgood;

        $this->data["GoodsList"] = $cleangoods;

        if (count($rows) === 0) {
            $this->data["EmptyGoodListText"] = " ";
        }



        // перенаправляем шаблон
        $this->template = "good.tpl";

        unset($goodsDb);
    }

    /**
      Биндим товары в разделе
     */
    private function BindGoods($sectionId) {

        $itemsOnPage = 24;

        $manufacturerId = Request("manufacturerId");

        if ($manufacturerId) {
            $this->BindListByManufacturer($sectionId, $manufacturerId);
        } else {
            $this->BindList($sectionId);
        }
        $smarty = PHP_Smarty::GetInstance();

        $goodsDb = new DAL_GoodsDb();

        if ($manufacturerId) {
            //Debug("test");
            $rows = $goodsDb->GetFromSectionByManufacturer($sectionId, $manufacturerId, $this->curPage, $itemsOnPage);
        } else {
            $rows = $goodsDb->GetFromSection($sectionId, $this->curPage, $itemsOnPage);
        }

        // если товар только один, то переходим на него
        /* if (count($rows) == 1) {
          $this->BindGood($rows[0]['GoodId']);

          // выводить ли список продукции в текущей секции
          $smarty = PHP_Smarty::GetInstance();
          $smarty->assign("isGoodList" , "0");

          unset($goodsDb);

          return;
          } */

        $goodsDb->AddImageToGoods($rows);

        /* Пейджинг */

        $allGoodsCount = $goodsDb->GetCountFromSection($sectionId);

        $pageCount = ceil($allGoodsCount / $itemsOnPage);

        $pageVar = "pageNum";

        $this->SetPager($pageCount, $pageVar, null);

        //usort($rows, array($this, "getSort"));

        foreach ($rows as $row) {

            if (is_numeric(BLL_ShoppingCartUtility::FindIndexByGoodId($row['GoodId']))) {

                $this->data['AddedToCart'][$row['GoodId']] = "Товар добавлен в <a href='/cart/'>корзину</a>";
            }
        }

        //Если пользователь залогинен
        if (isset($_SESSION['userId'])) {
            foreach ($rows as &$row) {
                $thisdata = $goodsDb->GetPriceForUser($_SESSION['userId'], $row['Code']);
                if ($thisdata) {
                    $row['Price'] = $thisdata['Price'];
                    $row['Currency'] = $thisdata['CurrencyId'];
                }
            }
        }

        foreach ($rows as &$row) {
            $row['CurrencyName'] = $goodsDb->GetCurrencyName($row['Currency']);
            if ($row['Price'] != 0) {
                $row['Price'] = number_format($row['Price'], 2, ',', ' ');
            }
        }

        $this->data["GoodsList"] = $rows;

        if (count($rows) === 0) {
            $this->data["EmptyGoodListText"] = " ";
        }

        unset($goodsDb);
    }

    private function BindGoodsSort($sectionId, $order, $superorder) {
        $this->BindList($sectionId);

        $goodsDb = new DAL_GoodsDb();
        $rows = $goodsDb->GetFromSectionSort($sectionId, $order, $superorder);

        // если товар только один, то переходим на него
        if (count($rows) == 1) {
            $this->BindGood($rows[0]['GoodId']);

            // выводить ли список продукции в текущей секции
            $smarty = PHP_Smarty::GetInstance();
            $smarty->assign("isGoodList", "0");


            return;
        }

        $goodsDb->AddImageToGoods($rows);
        unset($goodsDb);

        $this->data["GoodsList"] = $rows;
    }

    public function GetSectionsSiteMap($rows) {
        $xml = "";

        foreach ($rows as $row) {
            $innerXml = "";

            // получаем подразделы
            $sectionsDb = new DAL_SectionsDb();
            $subRows = $sectionsDb->GetSections($row['SectionId'],null,$this->moduleId);
            unset($sectionsDb);

            // если есть подразделы, то проходимся ещё и по ним
            if ($rows != null)
                $innerXml .= $this->GetSectionsSiteMap($subRows);

            // проходимся по списку товаров
            /* $goodsDb = new DAL_GoodsDb();
              $goods = $goodsDb->GetFromSection($row['SectionId']);
              unset($goodsDb);

              // просматриваем список товаров в данном разделе
              if ($goods != null)
              foreach ($goods as $good)
              $innerXml .= Helpers_SiteMap::CreateNode
              (
              $this->GetGoodAlias($good['GoodId']),
              $good['Title'],
              false,
              $this->moduleId,
              array("sectionId" => $row['SectionId'],
              "goodId" => $good['GoodId'])
              ); */

            $xml .= Helpers_SiteMap::CreateNode($row['SectionId'],$row['Alias'], $row['Title'], false, $this->moduleId, array("sectionId" => $row['SectionId']), $innerXml);
        }

        return $xml;
    }

    /**
      Функция для генерации сайт-мапа

      @param siteMapNode $parentNode Родительский узел карты сайта
     */
    public function GenerateSiteMap() {
        // получаем список всех разделов
        $sectionsDb = new DAL_SectionsDb();
        $rows = $sectionsDb->GetRootSections($this->moduleId);
        unset($sectionsDb);

        /* 		print_r($rows); */

        // проходимся по списку разделов товаров
        $xml = $this->GetSectionsSiteMap($rows);

        return $xml;
    }

    /**
      Событие при добавлении модуля к странице
     */
    function OnModuleAdd() {

    }

    /**
      Событие при удалении модуля
     */
    function OnModuleDelete() {
        $sectionsDb = new DAL_SectionsDb();
        $rows = $sectionsDb->DeleteAllSections($this->moduleId);
        unset($sectionsDb);
    }

    /**
     * Обработчик нажатия на клавишу заказа
     */
    function handlerBtnAdd($GoodId=null) {

        if (IsValid()) {
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

    /* Сортировка, а вы что подумали? */

    function handlerBtnSort($cols) {
        $smarty = PHP_Smarty::GetInstance();
        $sectionId = Request('sectionId');
        $smarty->assign("isGoodList", "handlerBtnSort");
        $superorder = Request('order');
        if ($superorder == 0) {
            $smarty->assign("Order", "1");
        } else {
            $smarty->assign("Order", "0");
        }
        $order = $cols;
        $this->BindGoodsSort($sectionId, $order, $superorder);
    }

    function BindPriceByManufacturer($id) {
        $dir = Helpers_PathHelper::GetFullPath('upload') . "manufacturer_prices/" . $id . "/";
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $smarty = PHP_Smarty::GetInstance();
                    $smarty->assign("Price", Helpers_PathHelper::GetFullUrl('upload') . "manufacturer_prices/" . $id . "/" . $file);
                }
                closedir($dh);
            }
        }
    }

}

?>