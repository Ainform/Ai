<?php
/**
 * GalleryEdit class
 * Редактирование фотогалереи
 *
 * @copyright (c) by VisualDesign
 */

class GalleryModuleEdit extends BMC_BaseModule {
    /**
     * Конструктор, задает параметры
     *
     * @return void
     */
    function __construct($moduleId) {
        parent::__construct($moduleId);
        $this->header = 'Фотогалерея';
    }

    /**
     * Функция для биндинга данных модуля
     *
     * @return void
     */
    function DataBind() {
        $parentId = Request('parent');
        $sectionId = Request('sectionId');
        $goodId = Request('goodId');
        $goodNew = Request('goodNew');

        $this->data['SectionId'] = $sectionId;

        if (isset($goodNew) && $goodNew == 1 && isset($sectionId) && $sectionId >= 0)
            return $this->BindNewGood($sectionId);

        if (isset($goodId) && $goodId >= 0)
            return $this->BindGoodEdit($goodId);

        if (isset($sectionId) && $sectionId >= 0)
            return $this->BindSectionEdit($sectionId);

        if (isset($parentId) && $parentId >= -1)
            return $this->BindNewSection($parentId);

        return $this->BindSectionTree();
    }

    /**
     Биндим список разделов в дерево
     */
    function BindSectionTree() {
        $sectionsDb = new DAL_GalleryDb();
        $sections = $sectionsDb->GetSections(-1);
        unset($sectionsDb);

        $tree = array
                (
                array(
                    "txt" => "Разделы фотогалереи",
                    "onclick" => "nodeSelect",
                    "id" => "-1"
                    )
        );

        foreach($sections as $section) {
            if ($section['ModuleId'] != $this->moduleId)
                continue;

            $title = $section['Title'];

            $treeItem = array(
                    "id" => $section['SectionId'],
                    "txt" => $title,
                    "items" => $this->BindChild($section),
                    "onclick" => "nodeSelect",
                    "ondblclick" => "nodeClick",
            );

            if ($section['ImageId'] > 0) {
                $treeItem['img'] = "../../../ImageHandler.php?id=".$section['ImageId']."&width=32&height=32";
            }

            $tree[0]["items"][] = $treeItem;
        }
        $manufacturersDb=new DAL_ManufacturersDb();
        $this->data['Manufacturers']=$manufacturersDb->GetAll();

        $this->data['Tree'] = $tree;
    }

    function handlerBindSectionTree() {
        $sectionsDb = new DAL_GalleryDb();
        $sections = $sectionsDb->GetSections(-1,null,$this->moduleId);
        unset($sectionsDb);

        $tree = array
                (/*
                  array("title" => "Разделы каталога",
                  "onclick" => "nodeSelect",
                  "id" => "-1") */
        );

        foreach ($sections as $section) {
            if ($section['ModuleId'] != $this->moduleId)
                continue;

            $title = $section['Title'];

            $treeItem = array(
                "id" => $section['SectionId'],
                "title" => $title,
                "children" => $this->BindChild($section),
                "onclick" => "nodeSelect",
                "ondblclick" => "nodeClick",
                "url" => $this->Url . "?sectionId=" . $section['SectionId'],
                "target" => "_self",
            );

//зачем нам картинки в дереве?
//if ($section['ImageId'] > 0) {
//    $treeItem['img'] = "../../../ImageHandler.php?id=".$section['ImageId']."&width=32&height=32";
//}

            $tree[] = $treeItem;
        }
//$manufacturersDb=new DAL_ManufacturersDb();
//$this->data['Manufacturers']=$manufacturersDb->GetAll();

        $this->data['Tree'] = $tree;
//Wtf($tree);
        echo json_encode($tree);
        die(0);
    }

   function BindChild($parent) {
        $tree = array();

        $sectionsDb = new DAL_GalleryDb();
        $sections = $sectionsDb->GetSections($parent['SectionId'],null,$this->moduleId);
        unset($sectionsDb);

        if (isset($sections))
            foreach ($sections as $section) {
                if ($section['ModuleId'] != $this->moduleId)
                    continue;

                $tree[] = array("id" => $section['SectionId'],
                    "title" => $section['Title'],
                    "children" => $this->BindChild($section),
                    "url" => $this->Url . "?sectionId=" . $section['SectionId'],
                    "target" => "_self",
                );
            }
        return $tree;
    }

    function BindGoodsList($sectionId) {
        $goodsDb = new DAL_GalleryPhotoDb();
        $goods = $goodsDb->GetFromSectionSort($sectionId,"Order",false);

        $goodsDb->AddImageToGoods($goods);
        unset($goodsDb);

        $this->data['Goods'] = $goods;
    }

    function handlerBtnUp() {
        if (!isset($this->data['sectionId']))
            return;

        $sectionId = $this->data['sectionId'];

        $sectionsDb = new DAL_GalleryDb();
        $section = $sectionsDb->Up($sectionId);
        unset($sectionsDb);

        BMC_SiteMap::Update();

        die(0);
    }

    function handlerBtnDown() {
        if (!isset($this->data['sectionId']))
            return;

        $sectionId = $this->data['sectionId'];

        $sectionsDb = new DAL_GalleryDb();
        $section = $sectionsDb->Down($sectionId);
        unset($sectionsDb);

        BMC_SiteMap::Update();

        die(0);
    }

    function handlerBtnNew() {
        if (!isset($this->data['parentId']) || !isset($this->data['moduleId']))
            die();

        $title=isset($this->data['title'])?$this->data['title']:"Новый раздел";

        $parentId = $this->data['parentId'];

        $section = array();
        $section['ParentId'] = $parentId;
        $section['Title'] = $title;
        $section['ModuleId'] = $this->data['moduleId'];

        $sectionsDb = new DAL_GalleryDb();
        $sectionId = $sectionsDb->Add($section);
        unset($sectionsDb);

        BMC_SiteMap::Update();

        echo $this->Url . "?sectionId=" . $sectionId;

        die();
    }

    function handlerBtnDelete() {
        if (!isset($this->data['sectionId']))
            return;

        $sectionId = $this->data['sectionId'];

        $sectionsDb = new DAL_GalleryDb();
        $sectionsDb->DeleteSection($sectionId);
        unset($sectionsDb);

        BMC_SiteMap::Update();

        die(0);
    }

    /**
     Редактирование товара
     */
//TODO удаление разделов и модулей с ними связанных
    function BindNewGood($sectionId) {
        // разделы каталога
        $sectionsDb = new DAL_GalleryDb();
        $sections = $sectionsDb->GetAllSections($this->moduleId);

        $sectionsList = array();
        foreach ($sections as $section)
            $sectionsList[$section['SectionId']] = $section['Title'];

        $this->data['ddrSection'] = $sectionId;

        // подключаем класс, управляющий изображениями в тексте
        $imageUtility = new Utility_ImageUtility();

        $this->data['ImageFolder'] = DAL_GalleryPhotoDb::GetImageFolder();
        $this->data['FilesFolder'] = DAL_GalleryPhotoDb::GetFilesFolder();

        $manDb = new DAL_ManufacturersDb();
        $mans = $manDb->GetAll();

        foreach ($mans as $man)
            $ManufacturersList[$man['ManufacturerId']] = $man['Name'];

        //$this->data['ddrManufacturer'] = $good['ManufacturerId'];
        $this->data['Manufacturers'] = $ManufacturersList;

        $this->data['Sections'] = $sectionsList;



        $this->template = "goodedit.tpl";
        $this->header = "Добавление нового товара";

        // достаем все характеристики товаров
        /*$charactersDb = new DAL_CharactersDb();

			$tbl = $charactersDb-> GetCharacterList();
			unset($charactersDb);

			$characters = array();
			$i = 0;
			if(!empty($tbl)){
			foreach ($tbl as $row)
			{
			$characters[] = array("RowIndex"=>$i, "Title" => $row["Title"], "Text" => "", "CharactId" => $row["CharactersId"]);
			$i++;
			}
			}
			$this->data["CountElement"] = count($characters);
			$this->data["Caracters"] = $characters;*/
    }

    /**
     Редактирование товара
     */
    function BindGoodEdit($goodId) {
        $goodsDb = new DAL_GalleryPhotoDb();
        $good = $goodsDb->GetGood($goodId);
        unset($goodsDb);

        // достаем все характеристики товаров
        $charactersDb = new DAL_CharactOfGoodDb();
        $characters = $charactersDb->GetCharactersOfGood($good["GoodId"]);
        unset($charactersDb);

        $this->data["Caracters"] = $characters;

        // разделы каталога
        $sectionsDb = new DAL_GalleryDb();
        $sections = $sectionsDb->GetAllSections($this->moduleId);

        $sectionsList = array();
        foreach ($sections as $section)
            $sectionsList[$section['SectionId']] = $section['Title'];

        $this->data['ddrSection'] = $good['SectionId'];
        $this->data['Sections'] = $sectionsList;

        $manDb = new DAL_ManufacturersDb();
        $mans = $manDb->GetAll();

        foreach ($mans as $man)
            $ManufacturersList[$man['ManufacturerId']] = $man['Name'];

        $this->data['ddrManufacturer'] = $good['ManufacturerId'];
        $this->data['Manufacturers'] = $ManufacturersList;

        // подключаем класс, управляющий изображениями в тексте
        $imageUtility = new Utility_ImageUtility();

        // задаем папку под картинки
        //$imageUtility->SetDirectory('good'.$goodId);

        $this->data['ImageFolder'] = DAL_GalleryPhotoDb::GetImageFolder($goodId);
        $this->data['FilesFolder'] = DAL_GalleryPhotoDb::GetFilesFolder($goodId);

        $this->data['NewPage'] = false;
        $this->data['Good'] = $good;
        $this->data['txtName'] = html_entity_decode($good['Title']);
        $this->data['Properties'] = $good['Properties'];
        $this->data['txtPrice'] = $good['Price'];
        $this->data['txtGoodCode'] = $good['Code'];
        $this->data['txtGoodTrueCode'] = $good['TrueCode'];
        $this->data['txtQuantity'] = $good['Quantity'];
        //$this->data['onMain'] = $good['Onmain'] == 1;

        $specdb = new DAL_SpecDb();
        $special=$specdb->GetSpec($goodId);
        if($special){
            $this->data['DateStart'] = $special['DateStart'];
            $this->data['DateEnd'] = $special['DateEnd'];
            $this->data['Discount'] = $special['Discount'];
            $this->data['SpecPath'] = $special['Path'];
            $this->data['DateStart'] = $special['DateStart'];
            $this->data['Spec']=true;
        }
        unset($specdb);

        $this->header = "Редактирование товара";
        $this->template = "goodedit.tpl";
    }

    function BindSectionEdit($sectionId) {
        $sectionsDb = new DAL_GalleryDb();
        $section = $sectionsDb->GetSection($sectionId);
        unset($sectionsDb);

        $this->data['ImageFolder'] = DAL_GalleryDb::GetImageFolder($sectionId);
        $this->data['FilesFolder'] = DAL_GalleryDb::GetImageFolder($sectionId);

        $this->data['NewPage'] = false;
        $this->data['txtName'] = $section['Title'];
        $this->data['Description'] = $section['Description'];
        $this->header = "Редактирование раздела каталога";
        $this->BindGoodsList($sectionId);
        $this->template = "sectionedit.tpl";
    }

    function BindNewSection($parentId) {
        //$sectionsDb = new DAL_GalleryDb();
        //$this->data['ImageFolder'] = DAL_GalleryDb::GetImageFolder($sectionId);
        $this->data['NewPage'] = true;
        $this->header = "Добавление нового раздела каталога";
        $this->template = "sectionedit.tpl";
    }

    #region SectionEdit Handlers
    function handlerBtnGoodDel($goodId) {
        $sectionId = Request('sectionId');

        if (!isset($sectionId) || $sectionId < -1)
            return;

        $goodsDb = new DAL_GalleryPhotoDb();
        $goodsDb->DeleteGood($goodId);
        unset($goodsDb);

        BMC_SiteMap::Update();

        $this->BindGoodsList($sectionId);
    }

    function handlerBtnSave() {
        if (!IsValid())
            return;

        $section = Array();

        $sectionId = Request('sectionId');
        if (isset($sectionId) && $sectionId >= 0) {
            $section['SectionId'] = $sectionId;
            $section['ModuleId'] = $this->moduleId;
            $section['Title'] = HtmlEncode($this->data['txtName']);
            $section['Description'] = $this->data['fckText'];

            $sectionsDb = new DAL_GalleryDb();
            $sectionsDb->UpdateSection($section);
            unset($sectionsDb);

            BMC_SiteMap::Update();
            Header("Location: ".$this->Url);
            die();
        }
        else {
            $parentId = Request('parent');
            $sectionsDb = new DAL_GalleryDb();
            $section = $sectionsDb->GetSection($parentId);
            unset($sectionsDb);

            if ($section == null)
                $parentId = -1;

            $section['ParentId'] = $parentId;
            $section['Title'] = $this->data['txtName'];
            $section['Description'] = $this->data['fckText'];
            $section['ModuleId'] = $this->moduleId;

            $sectionsDb = new DAL_GalleryDb();
            $sectionId = $sectionsDb->Add($section);
            unset($sectionsDb);

            BMC_SiteMap::Update();
            Header("Location: ".$this->Url."?sectionId=".$sectionId);
            die();
        }
    }

    /**
     Обработчик события при сохранении товара
     */
    function handlerBtnGoodSave() {

        if (!IsValid())
            return;

        $good = Array();

        // подключаем класс, управляющий изображениями в тексте
        $imageUtility = new Utility_ImageUtility();

        $goodId = Request('goodId');

        $sectionId = Request('sectionId');

        if (isset($goodId) && $goodId >= 0) {
            $good['GoodId'] = $goodId;
            $good['Title'] = HtmlEncode($this->data['txtName']);
            //$good['Price'] = HtmlEncode($this->data['txtPrice']);
            //$good['Code'] = HtmlEncode($this->data['txtGoodCode']);
            //$good['TrueCode'] = HtmlEncode($this->data['txtGoodTrueCode']);
            //$good['quantity'] = HtmlEncode($this->data['txtQuantity']);
            //$good['Onmain'] = empty($this->data['onMain']) ? 0 : 1;
            $good['Description'] = $this->data['fckDescription'];
            //$good['Properties'] = $this->data['Properties'];
            //$good['Abstract'] = $this->data['fckAbstract'];
            $good['SectionId'] = $this->data['ddrSection'];
            //$good['ManufacturerId'] = $this->data['ddrManufacturer'];

            $goodsDb = new DAL_GalleryPhotoDb();
            //$good['Price']=str_replace(",",".",$good['Price']);
            $goodsDb->UpdateGood($good);
            unset($goodsDb);

            // задаем папку под картинки и обновляем файлы
            //$imageUtility->SetDirectory('good'.$goodId);
            //$imageUtility->UpdateFiles($this->data['fckAbstract']);

            if ($sectionId != null)
                Header("Location: ".$this->Url."?sectionId=".$this->data['ddrSection']);
            else
                Header("Location: ".$this->Url);

            die();
        }
        else {
            $goodNew = Request('goodNew');
            $sectionId = Request('sectionId');

            if (1 != $goodNew || null == $sectionId || $sectionId < -1)
                return;

            $good['SectionId'] = $this->data['ddrSection'];
            $good['Title'] = HtmlEncode($this->data['txtName']);
            //$good['Onmain'] = empty($this->data['onMain']) ? 0 : 1;
            $good['Description'] = $this->data['fckDescription'];
            //$good['Properties'] = $this->data['Properties'];
           // $good['Abstract'] = $this->data['fckAbstract'];
            //$good['ManufacturerId'] = $this->data['ddrManufacturer'];

            //print_r($good);
            $goodsDb = new DAL_GalleryPhotoDb();
            $goodId = $goodsDb->AddGood($good);
            unset($goodsDb);

            // перемещаем картинки в нужную папку
            $imagesDb = new DAL_ImagesDb();
            $imagesDb->MoveImages(DAL_GalleryPhotoDb::GetImageFolder(), DAL_GalleryPhotoDb::GetImageFolder($goodId));
            unset($imagesDb);

            Header("Location: ".$this->Url."?sectionId=".$this->data['ddrSection']);
        }
    }

    function handlerBtnCancel() {
        $sectionId = Request('sectionId');
        $goodId = Request('goodId');

        if ($goodId != null) {
            Header("Location: ".$this->Url."?sectionId=".$sectionId);
            die();
        }
        else {
            Header("Location: ".$this->Url);
            die();
        }
    }

    function handlerBtnGoodUp($goodId) {
        $sectionId = Request('sectionId');

        if (!isset($sectionId) || $sectionId < -1)
            return;

        $goodsDb = new DAL_GalleryPhotoDb();
        $goodsDb->Up($goodId);

        $this->BindGoodsList($sectionId);
    }

    function handlerBtnGoodDown($goodId) {
        $sectionId = Request('sectionId');

        if (!isset($sectionId) || $sectionId < -1)
            return;

        $goodsDb = new DAL_GalleryPhotoDb();
        $goodsDb->Down($goodId);

        //unset($goodsDb);

        //BMC_SiteMap::Update();

        $this->BindGoodsList($sectionId);
    }

    function handlerBtnUploadFile() {
        //получаем файл
        //$content = Utility_FileUtility::GetUploadedFile($_FILES["userfile"]["tmp_name"]);
        if(isset($_POST['manufacturer'])) {

            //если вдруг захотят чтобы прайс имел имя производителя
            $manDb=new DAL_ManufacturersDb();
            $name=$manDb->GetNameById($_POST['manufacturer']);
            unset($manDb);

            if (!file_exists(Helpers_PathHelper::GetFullPath('upload')."manufacturer_prices/"))
                mkdir(Helpers_PathHelper::GetFullPath('upload')."manufacturer_prices/", 0777);

            $toFolder = Helpers_PathHelper::GetFullPath('upload')."manufacturer_prices/".$_POST['manufacturer']."/";

            if (!file_exists($toFolder))
                mkdir($toFolder, 0777);

            copy($_FILES["userfile"]["tmp_name"],$toFolder."price.xls");
        }

        $parser = new BLL_CatalogParser();
        //начинаем процесс парсинга
        $parser->StartParsing($_FILES["userfile"]["tmp_name"],$_POST['priceType'],null,$_POST['currency'],@$_POST['manufacturer']);
    }
    #endregion

    function handlerBtnBefore() {

        if (!isset($this->data['firstid']) || !isset($this->data['secondid']) || !isset($this->data['moduleId']))
            die();

        $sectionsDb = new DAL_GalleryDb();
        if ($this->data['firstid'] == -1) {
            //корневой элемент может быть только один
            die();
        } else {
            $firstsec = $sectionsDb->GetSection($this->data['firstid']);
            $sourceSec = $sectionsDb->GetSection($this->data['secondid']);

            if ($firstsec['ParentId'] == $sourceSec['ParentId']) {
//если в той же ветке
                $sections = $sectionsDb->GetSections($sourceSec['ParentId'],null,$this->moduleId);
                foreach ($sections as $section) {
                    if ($section['Order'] >= $firstsec['Order'] && $section['Order'] < $sourceSec["Order"]) {
                        $sectionsDb->UpdateSection(array("SectionId" => $section['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $section['Order'] + 1));
                    }
                }
                $sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $firstsec['Order']));
            } else {
//если перемещаем в другую ветку
                $sections = $sectionsDb->GetSections($sourceSec['ParentId'],null,$this->moduleId);
                foreach ($sections as $section) {
                    if ($section['Order'] >= $firstsec['Order']) {
                        $sectionsDb->UpdateSection(array("SectionId" => $section['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $section['Order'] + 1));
                    }
                }
                $sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $firstsec['Order'], "ParentId" => $firstsec['ParentId']));
            }

            unset($sectionsDb);
            BMC_SiteMap::Update();
            die(0);
        }
    }

    function handlerBtnAfter() {

        if (!isset($this->data['firstid']) || !isset($this->data['secondid']) || !isset($this->data['moduleId']))
            die();

        $sectionsDb = new DAL_GalleryDb();
        if ($this->data['firstid'] == -1) {
            //корневой элемент может быть только один
            die();
        } else {
            $firstsec = $sectionsDb->GetSection($this->data['firstid']);
            $sourceSec = $sectionsDb->GetSection($this->data['secondid']);

            if ($firstsec['ParentId'] == $sourceSec['ParentId']) {
//если в той же ветке
                $sections = $sectionsDb->GetSections($firstsec['ParentId'],null,$this->moduleId);
                foreach ($sections as $section) {
                    if ($section['Order'] > $sourceSec['Order'] && $section['Order'] <= $firstsec["Order"]) {
                        $sectionsDb->UpdateSection(array("SectionId" => $section['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $section['Order'] - 1));
                    }
                }
                $sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $firstsec['Order']));
            } else {
//если перемещаем в другую ветку
                $sections = $sectionsDb->GetSections($firstsec['ParentId'],null,$this->moduleId);
                foreach ($sections as $section) {
                    if ($section['Order'] > $firstsec['Order']) {
                        $sectionsDb->UpdateSection(array("SectionId" => $section['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $section['Order'] + 1));
                    }
                }
                $sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $firstsec['Order'] + 1, "ParentId" => $firstsec['ParentId']));
            }

            unset($sectionsDb);
            BMC_SiteMap::Update();
            die(0);
        }
    }

    function handlerBtnOver() {

        if (!isset($this->data['firstid']) || !isset($this->data['secondid']) || !isset($this->data['moduleId']))
            die();

        $sectionsDb = new DAL_GalleryDb();
        if ($this->data['firstid'] == -1) {
            $firstsec = array("SectionId" => -1);
        } else {
            $firstsec = $sectionsDb->GetSection($this->data['firstid']);
        }
        $sourceSec = $sectionsDb->GetSection($this->data['secondid']);

        $sections = $sectionsDb->GetSections($firstsec['SectionId'],null,$this->moduleId);
        $temp = -1;
        foreach ($sections as $section) {
            if ($section['Order'] > $temp) {
                $temp = $section['Order'];
            }
        }

        $sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $temp + 1, "ParentId" => $firstsec['SectionId']));


        unset($sectionsDb);
        BMC_SiteMap::Update();
        die(0);
    }
}

?>