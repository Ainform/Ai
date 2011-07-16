<?php

/**
 * CatalogEdit class
 * Редактирование каталога
 *
 * @copyright (c) by VisualDesign
 */
class CatalogModuleEdit extends BMC_BaseModule
{

	//храним здесь все разделы, их всё равно не может быть очень много
	private $all_sections = array();

	/**
	 * Конструктор, задает параметры
	 *
	 * @return void
	 */
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
		$this->header = 'Каталог продукции';
	}

	/**
	 * Функция для биндинга данных модуля
	 *
	 * @return void
	 */
	function DataBind()
	{

		$parentId = Request('parent');
		$sectionId = Request('sectionId');
		$goodId = Request('goodId');
		$goodNew = Request('goodNew');

		$this->data['SectionId'] = $sectionId;
		//$this->data['txtGoodCode'] = "asd";

		if (isset($goodNew) && $goodNew == 1 && isset($sectionId) && $sectionId >= 0)
			return $this->BindNewGood($sectionId);

		if (isset($goodId) && $goodId >= 0)
			return $this->BindGoodEdit($goodId);

		if (isset($sectionId) && $sectionId >= 0)
			return $this->BindSectionEdit($sectionId);

		if (isset($parentId) && $parentId >= -1)
			return $this->BindNewSection($parentId);

		//return $this->BindSectionTree();
	}

	/**
	Биндим список разделов в дерево
	 */
	function BindSectionTree()
	{
		$sectionsDb = new DAL_SectionsDb();
		$sections = $sectionsDb->GetSections(-1, null, $this->moduleId);
		unset($sectionsDb);

		$tree = array
		(
			array("txt" => "Разделы каталога",
			      "onclick" => "nodeSelect",
			      "id" => "-1")
		);

		foreach ($sections as $section) {
			if ($section['ModuleId'] != $this->moduleId)
				continue;

			$title = $section['Title'];

			$treeItem = array(
				"id" => $section['SectionId'],
				"txt" => $title,
				"items" => $this->BindChild($section),
				"onclick" => "nodeSelect",
				"ondblclick" => "nodeClick",
				"url" => $this->Url,
			);

			$tree[0]["items"][] = $treeItem;
		}

		$this->data['Tree'] = $tree;
	}

	function handlerBindSectionTree()
	{
		$sectionsDb = new DAL_SectionsDb();
		$this->all_sections = $sectionsDb->GetAllSections($this->moduleId);
		unset($sectionsDb);

		foreach ($this->all_sections as $sec) {
			if ($sec['ParentId'] == "-1" && $sec['ModuleId'] == $this->moduleId) {
				$sections[] = $sec;
			}
		}

		$tree = array
		( /*
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
				"url" => $this->Url . "?sectionId=" . $section['SectionId'],
				"target" => "_self",
			);

			$tree[] = $treeItem;
		}


		//$this->data['Tree'] = $tree;
		echo json_encode($tree);
		die(0);
	}

	function BindChild($parent)
	{
		$tree = array();

		foreach ($this->all_sections as $sec) {
			if ($sec['ParentId'] == $parent['SectionId'] && $sec['ModuleId'] == $this->moduleId) {
				$sections[] = $sec;
			}
		}


		if (isset($sections)) {
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
		}
		return $tree;
	}

	function BindGoodsList($sectionId)
	{
		$goodsDb = new DAL_GoodsDb();
		$goods = $goodsDb->GetFromSectionSort($sectionId, "Order", false);

		$goodsDb->AddImageToGoods($goods);
		unset($goodsDb);

		$this->data['Goods'] = $goods;
	}

	function handlerBtnUp()
	{

		if (!isset($this->data['sectionId']))
			return;

		$sectionId = $this->data['sectionId'];

		$sectionsDb = new DAL_SectionsDb();
		$section = $sectionsDb->Up($sectionId);
		unset($sectionsDb);

		BMC_SiteMap::Update();

		die(0);
	}

	function handlerBtnBefore()
	{

		if (!isset($this->data['firstid']) || !isset($this->data['secondid']) || !isset($this->data['moduleId']))
			die();

		$sectionsDb = new DAL_SectionsDb();
		if ($this->data['firstid'] == -1) {
			//корневой элемент может быть только один
			die();
		} else {
			$firstsec = $sectionsDb->GetSection($this->data['firstid']);
			$sourceSec = $sectionsDb->GetSection($this->data['secondid']);

			if ($firstsec['ParentId'] == $sourceSec['ParentId']) {
				//если в той же ветке
				$sections = $sectionsDb->GetSections($sourceSec['ParentId'], null, $this->moduleId);
				foreach ($sections as $section) {
					if ($section['Order'] >= $firstsec['Order'] && $section['Order'] < $sourceSec["Order"]) {
						$sectionsDb->UpdateSection(array("SectionId" => $section['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $section['Order'] + 1));
					}
				}
				$sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $firstsec['Order']));
			} else {
				//если перемещаем в другую ветку
				$sections = $sectionsDb->GetSections($sourceSec['ParentId'], null, $this->moduleId);
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

	function handlerBtnAfter()
	{

		if (!isset($this->data['firstid']) || !isset($this->data['secondid']) || !isset($this->data['moduleId']))
			die();

		$sectionsDb = new DAL_SectionsDb();
		if ($this->data['firstid'] == -1) {
			//корневой элемент может быть только один
			die();
		} else {
			$firstsec = $sectionsDb->GetSection($this->data['firstid']);
			$sourceSec = $sectionsDb->GetSection($this->data['secondid']);

			if ($firstsec['ParentId'] == $sourceSec['ParentId']) {
				//если в той же ветке
				$sections = $sectionsDb->GetSections($firstsec['ParentId'], null, $this->moduleId);
				foreach ($sections as $section) {
					if ($section['Order'] > $sourceSec['Order'] && $section['Order'] <= $firstsec["Order"]) {
						$sectionsDb->UpdateSection(array("SectionId" => $section['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $section['Order'] - 1));
					}
				}
				$sectionsDb->UpdateSection(array("SectionId" => $sourceSec['SectionId'], "ModuleId" => $this->data['moduleId'], "Order" => $firstsec['Order']));
			} else {
				//если перемещаем в другую ветку
				$sections = $sectionsDb->GetSections($firstsec['ParentId'], null, $this->moduleId);
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

	function handlerBtnOver()
	{

		if (!isset($this->data['firstid']) || !isset($this->data['secondid']) || !isset($this->data['moduleId']))
			die();

		$sectionsDb = new DAL_SectionsDb();
		if ($this->data['firstid'] == -1) {
			$firstsec = array("SectionId" => -1);
		} else {
			$firstsec = $sectionsDb->GetSection($this->data['firstid']);
		}
		$sourceSec = $sectionsDb->GetSection($this->data['secondid']);

		$sections = $sectionsDb->GetSections($firstsec['SectionId'], null, $this->moduleId);
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

	function handlerBtnDown()
	{
		if (!isset($this->data['sectionId']))
			return;

		$sectionId = $this->data['sectionId'];

		$sectionsDb = new DAL_SectionsDb();
		$section = $sectionsDb->Down($sectionId);
		unset($sectionsDb);

		BMC_SiteMap::Update();

		die(0);
	}

	function handlerBtnNew()
	{
		if (!isset($this->data['parentId']) || !isset($this->data['moduleId']))
			die();

		$title = isset($this->data['title']) ? $this->data['title'] : "Новый раздел";

		$parentId = $this->data['parentId'];

		$section = array();
		$section['ParentId'] = $parentId;
		$section['Title'] = $title;
		$section['Alias'] = encodestring($title);
		$section['ModuleId'] = $this->data['moduleId'];

		$sectionsDb = new DAL_SectionsDb();
		$sectionId = $sectionsDb->Add($section);
		unset($sectionsDb);

		BMC_SiteMap::Update();

		echo $this->Url . "?sectionId=" . $sectionId;

		die();
	}

	function handlerBtnDelete()
	{
		if (!isset($this->data['sectionId']))
			return;

		$sectionId = $this->data['sectionId'];

		$sectionsDb = new DAL_SectionsDb();
		$sectionsDb->DeleteSection($sectionId);
		unset($sectionsDb);

		BMC_SiteMap::Update();

		die(0);
	}

	/**
	Редактирование товара
	 */
	function BindNewGood($sectionId)
	{
		// разделы каталога
		$sectionsDb = new DAL_SectionsDb();
		$sections = $sectionsDb->GetAllSections($this->moduleId);

		$sectionsList = array();
		foreach ($sections as $section)
			$sectionsList[$section['SectionId']] = $section['Title'];

		$this->data['ddrSection'] = $sectionId;

		// подключаем класс, управляющий изображениями в тексте
		$imageUtility = new Utility_ImageUtility();

		$this->data['ImageFolder'] = DAL_GoodsDb::GetImageFolder();
		$this->data['FilesFolder'] = DAL_GoodsDb::GetFilesFolder();

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
		/* $charactersDb = new DAL_CharactersDb();

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
		  $this->data["Caracters"] = $characters; */
	}

	/**
	Редактирование товара
	 */
	function BindGoodEdit($goodId)
	{
		$goodsDb = new DAL_GoodsDb();
		$good = $goodsDb->GetGood($goodId);
		unset($goodsDb);

		// достаем все характеристики товаров
		$charactersDb = new DAL_CharactOfGoodDb();
		$characters = $charactersDb->GetCharactersOfGood($good["GoodId"]);
		unset($charactersDb);

		$this->data["Caracters"] = $characters;

		// разделы каталога
		$sectionsDb = new DAL_SectionsDb();
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
		$imageUtility->SetDirectory('goods/' . $goodId);

		$this->data['ImageFolder'] = DAL_GoodsDb::GetImageFolder($goodId);
		$this->data['FilesFolder'] = DAL_GoodsDb::GetFilesFolder($goodId);

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
		$special = $specdb->GetSpec($goodId);
		if ($special) {
			$this->data['DateStart'] = $special['DateStart'];
			$this->data['DateEnd'] = $special['DateEnd'];
			$this->data['Discount'] = $special['Discount'];
			$this->data['SpecPath'] = $special['Path'];
			$this->data['DateStart'] = $special['DateStart'];
			$this->data['Spec'] = true;
		}
		unset($specdb);

		$this->header = "Редактирование товара";
		$this->template = "goodedit.tpl";
	}

	function BindSectionEdit($sectionId)
	{
		$sectionsDb = new DAL_SectionsDb();
		$section = $sectionsDb->GetSection($sectionId);
		unset($sectionsDb);

		$this->data['ImageFolder'] = DAL_SectionsDb::GetImageFolder($sectionId);
		$this->data['FilesFolder'] = DAL_SectionsDb::GetImageFolder($sectionId);

		$this->data['NewPage'] = false;
		$this->data['txtName'] = $section['Title'];
		$this->data['Description'] = $section['Description'];
		$this->header = "Редактирование раздела каталога";
		$this->BindGoodsList($sectionId);
		$this->template = "sectionedit.tpl";
	}

	function BindNewSection($parentId)
	{
		//$sectionsDb = new DAL_SectionsDb();
		//$this->data['ImageFolder'] = DAL_SectionsDb::GetImageFolder($sectionId);
		$this->data['NewPage'] = true;
		$this->header = "Добавление нового раздела каталога";
		$this->template = "sectionedit.tpl";
	}

#region SectionEdit Handlers

	function handlerBtnGoodDel($goodId)
	{
		$sectionId = Request('sectionId');

		if (!isset($sectionId) || $sectionId < -1)
			return;

		$goodsDb = new DAL_GoodsDb();
		$goodsDb->DeleteGood($goodId);
		unset($goodsDb);

		BMC_SiteMap::Update();

		$this->BindGoodsList($sectionId);
	}

	function handlerBtnSave()
	{
		if (!IsValid())
			return;

		$section = Array();

		$sectionId = Request('sectionId');
		if (isset($sectionId) && $sectionId >= 0) {
			$section['SectionId'] = $sectionId;
			$section['ModuleId'] = $this->moduleId;
			$section['Title'] = HtmlEncode($this->data['txtName']);
			$section['Alias'] = encodestring($this->data['txtName']);
			$section['Description'] = $this->data['fckText'];

			$sectionsDb = new DAL_SectionsDb();
			$sectionsDb->UpdateSection($section);
			unset($sectionsDb);

			BMC_SiteMap::Update();
			Header("Location: " . $this->Url);
			die();
		} else {
			$parentId = Request('parent');
			$sectionsDb = new DAL_SectionsDb();
			$section = $sectionsDb->GetSection($parentId);
			unset($sectionsDb);

			if ($section == null)
				$parentId = -1;

			$section['ParentId'] = $parentId;
			$section['Title'] = $this->data['txtName'];
			$section['Alias'] = encodestring($this->data['txtName']);
			$section['Description'] = $this->data['fckText'];
			$section['ModuleId'] = $this->moduleId;

			$sectionsDb = new DAL_SectionsDb();
			$sectionId = $sectionsDb->Add($section);
			unset($sectionsDb);

			BMC_SiteMap::Update();
			Header("Location: " . $this->Url . "?sectionId=" . $sectionId);
			die();
		}
	}

	/**
	Обработчик события при сохранении товара
	 */
	function handlerBtnGoodSave()
	{

		if (!IsValid())
			return;

		$good = Array();

		// подключаем класс, управляющий изображениями в тексте
		$imageUtility = new Utility_ImageUtility();

		$goodId = Request('goodId');

		$sectionId = Request('sectionId');

		$goodsDb = new DAL_GoodsDb();
		if (isset($goodId) && $goodId >= 0) {
			$thisgood = $goodsDb->GetGood($goodId);
			$checkCode = $thisgood['Code'];
		} else {
			$checkCode = 0;
		}


		/* $duplicateCode=$goodsDb->checkCode($this->data['txtGoodCode'],$checkCode);
		  if ($duplicateCode==1) {
		  $this->data['txtGoodCodevalidator'] = 'false';
		  $this->data['txtGoodCodemessage'] = 'Уже есть товар с таким артикулом!';
		  return;
		  } */

		if (isset($goodId) && $goodId >= 0) {
			$good['GoodId'] = $goodId;
			$good['Title'] = HtmlEncode($this->data['txtName']);
			$good['Price'] = HtmlEncode($this->data['txtPrice']);
			//$good['Code'] = HtmlEncode($this->data['txtGoodCode']);
			// $good['TrueCode'] = HtmlEncode($this->data['txtGoodTrueCode']);
			//$good['quantity'] = HtmlEncode($this->data['txtQuantity']);
			//$good['Onmain'] = empty($this->data['onMain']) ? 0 : 1;
			$good['Description'] = $this->data['fckDescription'];
			//$good['Properties'] = $this->data['Properties'];
			$good['Abstract'] = $this->data['fckAbstract'];
			$good['SectionId'] = $this->data['ddrSection'];
			//$good['ManufacturerId'] = $this->data['ddrManufacturer'];

			/* $specdb = new DAL_SpecDb();
			  if(isset($this->data['Spec'])) {
			  $spec['GoodId']=$goodId;
			  $spec['DateStart']=$this->data['DateStart'];
			  $spec['DateEnd']=$this->data['DateEnd'];
			  if(is_numeric($this->data['Discount'])){
			  $spec['Discount']=$this->data['Discount'];}
			  else{
			  $spec['Discount']='0';
			  }
			  $spec['Path']=$this->data['SpecPath'];

			  if($specdb->CheckPresence($goodId)) {
			  $specdb->UpdateSpec($spec);
			  }
			  else {
			  $specdb->AddSpec($spec);
			  }

			  }
			  else{
			  $specdb->DeleteSpec($goodId);
			  }
			  unset($specdb); */

			$goodsDb = new DAL_GoodsDb();
			$good['Price'] = str_replace(",", ".", $good['Price']);
			$goodsDb->UpdateGood($good);
			unset($goodsDb);

			// задаем папку под картинки и обновляем файлы
			//$imageUtility->SetDirectory('good'.$goodId);
			//$imageUtility->UpdateFiles($this->data['fckAbstract']);

			if ($sectionId != null)
				Header("Location: " . $this->Url . "?sectionId=" . $this->data['ddrSection']);
			else
				Header("Location: " . $this->Url);

			die();
		}
		else {
			$goodNew = Request('goodNew');
			$sectionId = Request('sectionId');

			if (1 != $goodNew || null == $sectionId || $sectionId < -1)
				return;

			$good['SectionId'] = $this->data['ddrSection'];
			$good['Title'] = HtmlEncode($this->data['txtName']);

			if (is_numeric($this->data['txtPrice'])) {

				$good['Price'] = ($this->data['txtPrice']);
				$good['Price'] = str_replace(",", ".", $good['Price']);
			} else {
				$good['Price'] = '0';
			}
			//$good['Code'] = HtmlEncode($this->data['txtGoodCode']);
			// $good['TrueCode'] = HtmlEncode($this->data['txtGoodTrueCode']);
			//$good['quantity'] = HtmlEncode($this->data['txtQuantity']);
			//$good['Onmain'] = empty($this->data['onMain']) ? 0 : 1;
			$good['Description'] = $this->data['fckDescription'];
			// $good['Properties'] = $this->data['Properties'];
			$good['Abstract'] = $this->data['fckAbstract'];
			// $good['ManufacturerId'] = $this->data['ddrManufacturer'];

			$goodsDb = new DAL_GoodsDb();
			$goodId = $goodsDb->AddGood($good);
			unset($goodsDb);

			/* if(isset($this->data['Spec'])) {
			  $spec['GoodId']=$goodId;
			  $spec['DateStart']=$this->data['DateStart'];
			  $spec['DateEnd']=$this->data['DateEnd'];
			  $spec['Discount']=$this->data['Discount'];
			  $spec['Path']=$this->data['SpecPath'];

			  $specdb = new DAL_SpecDb();

			  if($specdb->CheckPresence($goodId)) {
			  $specdb->UpdateSpec($spec);
			  }
			  else {
			  $specdb->AddSpec($spec);
			  }

			  unset($specdb);

			  } */

			// перемещаем картинки в нужную папку
			$imagesDb = new DAL_ImagesDb();
			$imagesDb->MoveImages(DAL_GoodsDb::GetImageFolder(), DAL_GoodsDb::GetImageFolder($goodId));
			unset($imagesDb);

			// характеристики товара
			/* $charactersDb = new DAL_CharactersDb();
			  $characters = $charactersDb->GetCharacterList();

			  $charOfGoodDb = new DAL_CharactOfGoodDb();

			  if(empty($characters))$characters = array();

			  foreach ($characters as $value)
			  {
			  if (isset($_POST["charact".$value["CharactersId"]]))
			  {
			  $charOfGood = array("GoodId" => $goodId,
			  "ChId" => $value["CharactersId"],
			  "Description" => HtmlEncode($_POST["charact".$value["CharactersId"]]));

			  $charOfGoodDb->AddCharacters($charOfGood);
			  }
			  }

			  unset($charactersDb);
			  unset($charOfGoodDb); */
			// конец характеристикам товара
			// задаем папку под картинки и обновляем файлы
			//$imageUtility->MoveFiles($this->data['fckAbstract'], 'good'.$goodId);
			//$good['Abstract'] = $this->data['fckAbstract'];
			//$good['GoodId'] = $goodId;
			// обновляем текст товаров с учетом перемещенных картинок
			//$goodsDb = new DAL_GoodsDb();
			//$goodId = $goodsDb->UpdateGood($good);
			//unset($goodsDb);

			Header("Location: " . $this->Url . "?sectionId=" . $this->data['ddrSection']);
		}
	}

	function handlerBtnCancel()
	{
		$sectionId = Request('sectionId');
		$goodId = Request('goodId');

		if ($goodId != null) {
			Header("Location: " . $this->Url . "?sectionId=" . $sectionId);
			die();
		} else {
			Header("Location: " . $this->Url);
			die();
		}
	}

	function handlerBtnGoodUp($goodId)
	{
		$sectionId = Request('sectionId');

		if (!isset($sectionId) || $sectionId < -1)
			return;

		$goodsDb = new DAL_GoodsDb();
		$goodsDb->Up($goodId);

		//unset($goodsDb);
		//BMC_SiteMap::Update();

		$this->BindGoodsList($sectionId);
	}

	function handlerBtnGoodDown($goodId)
	{
		$sectionId = Request('sectionId');

		if (!isset($sectionId) || $sectionId < -1)
			return;

		$goodsDb = new DAL_GoodsDb();
		$goodsDb->Down($goodId);

		//unset($goodsDb);
		//BMC_SiteMap::Update();

		$this->BindGoodsList($sectionId);
	}

	function handlerBtnUploadFile()
	{
		//получаем файл
		//$content = Utility_FileUtility::GetUploadedFile($_FILES["userfile"]["tmp_name"]);
		if (isset($_POST['manufacturer'])) {

			//если вдруг захотят чтобы прайс имел имя производителя
			$manDb = new DAL_ManufacturersDb();
			$name = $manDb->GetNameById($_POST['manufacturer']);
			unset($manDb);

			if (!file_exists(Helpers_PathHelper::GetFullPath('upload') . "manufacturer_prices/"))
				mkdir(Helpers_PathHelper::GetFullPath('upload') . "manufacturer_prices/", 0777);

			$toFolder = Helpers_PathHelper::GetFullPath('upload') . "manufacturer_prices/" . $_POST['manufacturer'] . "/";

			if (!file_exists($toFolder))
				mkdir($toFolder, 0777);

			copy($_FILES["userfile"]["tmp_name"], $toFolder . "price.xls");
		}

		$parser = new BLL_CatalogParser();
		//начинаем процесс парсинга
		$parser->StartParsing($_FILES["userfile"]["tmp_name"], $_POST['priceType'], null, $_POST['currency'], @$_POST['manufacturer']);
	}

#endregion
}

?>
