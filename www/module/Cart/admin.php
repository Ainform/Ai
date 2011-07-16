<?php

/**
 *
 * @copyright (c) by VisualDesign
 *
 */
class CartModuleEdit extends BMC_BaseModule {
    /**
     * Количество на страницу
     */
    const RecordsOnPage = 10;

    /**
     * Конструктор, задает параметры
     *
     * @return void
     */
    function __construct($moduleId) {
        parent::__construct($moduleId);
    }

    /**
     * Номер текущей страницы
     *
     * @var int
     */
    public $curPage;

    /**
     * Функция для создания html-кода модуля
     *
     * @return void
     */
    function DataBind() {

        $this->curPage = Request('pageNum', 0);

        // если указан идентификатор
        $OrderId = Request('OrderId');
        $save = Request('Save');

        if ($save) {
            $this->handlerBtnSave();
        }

        if ($OrderId == null) {
            $this->BindOrderList();
        } else {
            $this->BindOrder($OrderId);
        }
    }

    /*
     * Биндим данные для нового материала
     */

    public function BindOrder($orderId) {

        $OrderDb = new DAL_OrderDb();
        $OrderItemsDb = new DAL_OrderItemsDb();

        $temp = $OrderDb->GetOrder($orderId);
        $items = $OrderItemsDb->GetOrderItem($orderId);

        //Debug($items);

        unset($OrderDb);
        unset($OrderItemsDb);

        $goodsDb = new DAL_GoodsDb();
        $smarty = PHP_Smarty::GetInstance();
        $smarty->registerPlugin("function","goodLink", array($goodsDb, "GetGoodLink"));
        $summ = 0;
        foreach ($items as &$item) {
            $item['goodname'] = $goodsDb->GetNameById($item['goodid']);
            $good = $goodsDb->GetGood($item['goodid']);
            $item['goodlink'] = "/" . $good['SectionId'] . "/good" . $item['goodid'];
            $summ+= ( ($item['price'] * $item['count']));
        }
        unset($goodsDb);

        $this->data['Order'] = $temp;
        $this->data['Order']['Summ'] = $summ;
        $this->data['Order']['Items'] = $items;

        $this->template = "order.tpl";
    }

    public function BindOrderList() {

        $count = 0;

        $OrderDb = new DAL_OrderDb();
        $rows = $OrderDb->GetPage($this->curPage + 1, 10, $count);

        if (0 == count($rows))
            return;


        $allOrder = $OrderDb->GetCount(); // количество всех

        unset($OrderDb);

        $p = ceil($allOrder / self::RecordsOnPage); // количество страниц
        $pager = "<div class='pagination'>";
        if ($p > 1) {// если больше одной страницы
            for ($i = 0; $i < $p; $i++) {
                if ($this->curPage == $i) {// выделение страниц
                    $pager .= "<a href='#' class='number current'>" . ($i + 1) . "</a>";
                } else {
                    $url = $this->GetVirtualPath();
                    $pager .= "<a href='?pageNum=" . $i . "' class='number'>" . ($i + 1) . "</a>";
                }
            }
        }

        $configDb = new DAL_ConfigDb();
        $temp = $configDb->configSelect(array("key" => "OrderEmail"));
        $this->data["email"] = $temp['value'];
        unset($configDb);

        $goodsDb = new DAL_GoodsDb();
        $this->data["euro"] = $goodsDb->GetRate(2);
        $this->data["dollar"] = $goodsDb->GetRate(1);
        unset($goodsDb);
        $this->data["Pager"] = $pager . "</div>";



        $this->data['OrderList'] = $rows;
    }

    function handlerBtnArchiveSave() {

        if (!IsValid())
            return;

        $archiveId = Request('ArchiveId');

        // пытаемся добавить
        $isNew = Request('newArchive');

        // подключаем класс, управляющий файлами
        $filesUtility = new Utility_AnalyticFilesUtility();
        // подключаем класс, управляющий картинками
        $imageUtility = new Utility_ImageUtility();

        if (!empty($archiveId)) {
            $archiveRow = Array();
            $archiveRow['id'] = $archiveId;
            $archiveRow['title'] = HtmlEncode($this->data['txtTitle']);
            $archiveRow['text'] = $this->data['fckText'];
            $archiveRow['date'] = strtotime($this->data['txtDate']);
            $newsRow['onfront'] = $this->data['selectOnFront'];

            // задаем папку под картинки и обновляем файлы
            $filesUtility->SetDirectory($this->moduleFolder);
            $filesUtility->UpdateFiles($this->data['fckText']);

            $archiveDb = new DAL_ArchiveDb();
            $archiveDb->Update($archiveRow);
            unset($archiveDb);

            Header("Location: " . $this->Url);
        } elseif (!empty($isNew)) {
            $archiveRow = Array();
            $archiveRow['moduleid'] = $this->moduleId;
            $archiveRow['title'] = HtmlEncode($this->data['txtTitle']);
            $archiveRow['date'] = strtotime($this->data['txtDate']);
            $archiveRow['text'] = $this->data['fckText'];
            $archiveRow['anons'] = $this->data['fckAnons'];
            $newsRow['onfront'] = $this->data['selectOnFront'];

            // добавяляем материал и получаем её идентификатор
            $archiveDb = new DAL_ArchiveDb();
            $archiveId = $archiveDb->Add($archiveRow);
            unset($archiveDb);

            // перемещаем файлы в нужную папку
            $filesDb = new DAL_AnalyticsFilesDb();
            $filesUtility->SetDirectory($this->moduleFolder); //если папка ещё не создана, создаём
            $filesDb->MoveFiles(DAL_ArchiveDb::GetFolder(), DAL_ArchiveDb::GetFolder($archiveId));
            unset($filesDb);

            // перемещаем картинки в нужную папку
            $imagesDb = new DAL_ImagesDb();
            $imageUtility->SetDirectory($this->moduleFolder); //если папка ещё не создана, создаём
            $imagesDb->MoveImages(DAL_ArchiveDb::GetFolder(), DAL_ArchiveDb::GetFolder($archiveId));
            unset($imagesDb);

            // задаем папку под картинки и обновляем файлы
            //$imageUtility->MoveFiles($this->data['fckText'], DAL_ArchiveDb::GetFilesFolder($archiveId));
            //$archiveRow['Text'] = $this->data['fckText'];
            //$archiveRow['Id'] = $archiveId;
            // обновляем текст новости с учетом перемещенных картинок
            //$archiveDb = new DAL_ArchiveDb();
            //$archiveId = $archiveDb->UpdateArchive($archiveRow);
            // unset($archiveDb);
            // возвращаемся к списку новостей
            Header("Location: " . $this->Url);
        }

        BMC_SiteMap::Update();
    }

    function handlerBtnCancel() {
        $archiveId = Request('archiveId');
        $isNewArchive = Request('newArchive');

        if ($archiveId != null || $isNewArchive) {


            // отмена создания
            if ($isNewArchive) {
                // удаляем файлы
                //$filesDb = new DAL_ArchiveFilesDb();
                //$filesDb->DeleteFolder(DAL_ArchiveDb::GetFolder());
                //unset($filesDb);
                // подключаем класс, управляющий изображениями в тексте
                //$filesUtility = new Utility_FilesUtility();
                //$filesUtility->DeleteFiles($this->data['fckText']);
            }
            Header("Location: " . $this->Url);
        }
        else
            RedirectToPageEdit($this->moduleId);

        die();
    }

    function handlerBtnDel($Id) {
        $orderDb = new DAL_OrderDb();
        $orderDb->OrderDelete($Id);
        unset($orderDb);

        $orderitDb = new DAL_OrderItemsDb();
        $orderitDb->OrderItemsDelete(array("orderid" => $Id));
        unset($orderitDb);

        $this->BindOrderList();
    }

    function handlerBtnSave() {

        $configDb = new DAL_ConfigDb();
        $temp = $configDb->query("UPDATE config SET value=\"" . $this->data['email'] . "\" WHERE `key`=\"OrderEmail\"");
        unset($configDb);

        $goodsDb = new DAL_GoodsDb();
        $goodsDb->SetRate(2, $this->data['euro']);
        $goodsDb->SetRate(1, $this->data['dollar']);
        unset($goodsDb);

        //BMC_SiteMap::Update();

        $this->BindOrderList();
    }

}

?>