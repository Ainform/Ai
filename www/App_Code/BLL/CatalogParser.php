<?php
	 //класс для обработки каталога из xml файла
	 class  BLL_CatalogParser {
		  const SectionTag = "Группа";
		  const GoodTag = "Элемент";
		  const NomenclatureTag = "Номенклатуры";

		  // запрос для вставки данных в таблицу разделов
		  private	 $sectionQuery = "INSERT INTO Sections (ModuleId, Title, ParentTitle, Code, ParentCode) Values ";

		  // запрос для вставки данных в таблицу товаров
		  private	 $goodsQuery = "INSERT INTO Goods (SectionId, Title, Price, Code, GoodsAtWarehouse) Values ";

		  //начинаем процемм парсинга
		  public function StartParsing($filename,$priceType,$userId=null,$currency=0,$manufacturer='') {
				//var_dump(file_get_contents($filename));

				if (!file_exists($filename)) {
					 var_dump("Error!");
				}

				require_once $_SERVER['DOCUMENT_ROOT'].'/util/Excel/reader.php';
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('UTF-8');
				$data->setRowColOffset(0);
				$data->read($filename);

				switch ($priceType) {
					 case 1:
						  /*шаблон типа Panduit
						  ищем на втором листе(индекс 1) все строки в которых три заполненных ячейки*/
						  foreach ($data->sheets[1]['cells'] as $key=>$row){
								if(count($row)==3 && isset($row[3])&& isset($row[4])&& isset($row[5])){
									 $tempGoods[$key]['Code']=$row[3];
									 $tempGoods[$key]['Description']=$row[4];
									 $tempGoods[$key]['Currency']=$currency;//$this->CheckCurrency($row[5]);
									 $cleanPrice=floatval(str_replace(array("$","€"),'',$row[5]));
									 $tempGoods[$key]['Price']=$cleanPrice;
								}
						  }
					 case 2:
						  //Шаблон типа Kyland
						  foreach ($data->sheets[1]['cells'] as $key=>$row){
								if(count($row)==5 && isset($row[2])&& isset($row[3])&& isset($row[4])&& isset($row[5])&& isset($row[6])){
									 $tempGoods[$key]['Code']=$row[2];
									 $tempGoods[$key]['Description']=$row[3];
									 $tempGoods[$key]['Currency']=$currency;//$this->CheckCurrency($row[5]);
									 $cleanPrice=floatval(str_replace(array("$","€"),'',$row[5]));
									 $tempGoods[$key]['Price']=$cleanPrice;
								}
						  }
						  
					 case 3:
						  /*шаблон типа Legrand*/
						  foreach ($data->sheets[1]['cells'] as $key=>$row){
								if(count($row)==3 && isset($row[2])&& isset($row[4])&& isset($row[5])){
									 $tempGoods[$key]['Code']=$row[2];
									 $tempGoods[$key]['Description']=$row[4];
									 $tempGoods[$key]['Currency']=$currency;//$this->CheckCurrency($row[5]);
									 $cleanPrice=floatval(str_replace(array("$","€"),'',$row[5]));
									 $tempGoods[$key]['Price']=$cleanPrice;
								}
						  }
				}

				if($userId) {
					 $this->UpdatePrice($tempGoods,$userId,$manufacturer);
				}else {
					 $this->UpdatePrice($tempGoods,null,$manufacturer);
				}
		  }

		  function UpdatePrice($prices,$userId=null,$manufacturer) {
				$db = new DAL_BaseDb();
				if($userId) {
					 $currentprices=array();
					 $currentprices_new=array();
					 $currentprices=$db->query("SELECT GoodCode FROM prices WHERE UserId=".$userId);          

					 //генерим массив с товарами цены для которых уже выставлены для этого пользователя, их будем обновлять
					 foreach($currentprices as $current){
						  $currentprices_new[]=$current['GoodCode'];
					 } 
					 foreach($prices as $price) {
						  if(in_array($price['Code'],$currentprices_new)){
								$result=$db->query("UPDATE LOW_PRIORITY IGNORE prices SET ManufacturerId=\"".$manufacturer."\", CurrencyId=\"".$price['Currency']."\", Price=\"".$price['Price']."\" WHERE GoodCode=\"".$price['Code']."\" AND UserId=\"".$userId."\"");
						  }
						  else{
								$result=$db->query("INSERT LOW_PRIORITY IGNORE INTO prices (`CurrencyId`, `GoodCode`, `Price`, `UserId`, `ManufacturerId`) VALUES (\"".$price['Currency']."\",\"".$price['Code']."\",\"".$price['Price']."\",\"".$userId."\", \"".$manufacturer."\")");
						  }
					 }      
				}else {
					 foreach($prices as $price) {
						  $result=$db->query("UPDATE LOW_PRIORITY IGNORE goods SET ManufacturerId=\"".$manufacturer."\", Currency=\"".$price['Currency']."\", Description=\"".$price['Description']."\", Price=\"".$price['Price']."\" WHERE Code=\"".$price['Code']."\"");
					 }
				}

		  }
		  /*
		  * проверяем валюту
		  */
		  function CheckCurrency($string) {
				if(is_int(mb_strpos($string,'$'))){
					 return '1';
				}
				elseif(is_int(mb_strpos($string,'€'))){
					 return '2';
				}
				elseif(is_int(mb_strpos($string,'звоните'))){
					 return '3';
				}
				else{
					 return '0';
				}     
		  }
		  /**
		  * Импорт данных их XML файла в MySQL базу
		  * ! Внимание !
		  * если все данные о товарах в одном запросе фигарить, то может не хватить памяти !
		  *
		  * @param unknown_type $parent
		  */
		  public function ImportSubSectionsWithGoods(& $parent) {
				$scobe = array("«","»","'", '"', "&quot;", "&apos;");
				$lastSectionId = 0;

				$SectionsDb = new DAL_SectionsDb();
				$GoodDb = new DAL_GoodsDb();

				$i = 0;
				//проходим по всем дочерним элементам
				foreach ($parent->children() as $child) {
					 // идентификатор вставленной секции
					 $attrs = $child->attributes();

					 if ($child->getName() == self::SectionTag) {
						  $lastSectionId = $SectionsDb->InsertSections($this->sectionQuery."(79, '".str_replace($scobe,"", $attrs["Наименование"])."', '".str_replace($scobe,"<<", $attrs["НаименованиеРодителя"])."' ,'".str_replace($scobe,"<<", $attrs["Код"])."' , '". $attrs["КодРодителя"] ."' )");

						  // перебираем товары
						  foreach ($child->children() as $subChild) {
								$subAttrs = $subChild->attributes();
								$GoodDb->InsertGoods($this->goodsQuery."(".$lastSectionId.", '".str_replace($scobe,"<<", $subAttrs["Наименование"])."' ,'".str_replace($scobe,"<<", $subAttrs["Цена"])."', '".str_replace($scobe,"<<",$subAttrs["Код"])."', '". $subAttrs["Остатки"]."' )");
						  }
					 }
				}

				$this->UpdateSectionsAfterImport();
		  }

		  /**
		  * Импорт данных их XML файла в MySQL базу
		  * ! Внимание !
		  * если все данные о товарах в одном запросе фигарить, то может не хватить памяти !
		  *
		  * @param unknown_type $parent
		  */
		  private function ImportSubSectionsWithGoods1(& $parent) {
				$scobe = array("«","»","'", '"', "&quot;", "&apos;");
				$lastSectionId = 0;

				$SectionsDb = new DAL_SectionsDb();
				$GoodDb = new DAL_GoodsDb();


				//проходим по всем дочерним элементам
				foreach ($parent->children() as $child) {
					 // идентификатор вставленной секции
					 $attrs = $child->attributes();

					 if ($child->getName() == self::SectionTag) {
						  $lastSectionId = $SectionsDb->InsertSections($this->sectionQuery."(79, '".HtmlEncode(str_replace($scobe,"", $attrs["Наименование"]))."', '".HtmlEncode(str_replace($scobe,"", $attrs["НаименованиеРодителя"]))."' ,'".HtmlEncode(str_replace($scobe,"", $attrs["Код"]))."' )");

						  // перебираем товары
						  foreach ($child->children() as $subChild) {
								$subAttrs = $subChild->attributes();
								$GoodDb->InsertGoods($this->goodsQuery."(".$lastSectionId.", '".HtmlEncode(str_replace($scobe,"", $subAttrs["Наименование"]))."' ,'".HtmlEncode(str_replace("-",".", $subAttrs["Цена"]) == "" ? "0" : str_replace("-",".", $subAttrs["Цена"]))."', '".HtmlEncode(str_replace($scobe,"",$subAttrs["Код"]))."')");
						  }
					 }
				}
		  }

		  private function ClearTable() {
				//$SectionsDb = new DAL_SectionsDb();
				//$SectionsDb->ClearTable();

				//$GoodsDb = new DAL_GoodsDb();
				//$GoodsDb->ClearTable();
		  }

		  private function InsertSections() {
				if ($this->sectionQuery[strlen($this->sectionQuery) - 1] == ",")
					 $this->sectionQuery = substr($this->sectionQuery, 0, strlen($this->sectionQuery) - 1);

				//var_dump(utf8_encode($this->sectionQuery));
				//die();
				$SectionsDb = new DAL_SectionsDb();
				$SectionsDb->InsertSections($this->sectionQuery);
				//		$SectionsDb->InsertSections(utf8_encode($this->sectionQuery));
		  }

		  private function UpdateSectionsAfterImport() {
				$sectionsDb = new DAL_SectionsDb();
				$sections = $sectionsDb->GetAllSections();

				if ($sections == null || count($sections) <= 0)
					 return;

				foreach ($sections as &$section) {
					 if (isset($section["ParentCode"] ) && $section["ParentCode"] != "" && strlen($section["ParentCode"]) > 0) {
						  $parentSection = $sectionsDb->GetSectionByCode($section["ParentCode"]);

						  if ($parentSection != null) {
								$section["ParentId"] = $parentSection["SectionId"];
								$sectionsDb->UpdateSection($section);
						  }
					 }
				}
		  }
	 }
?>
