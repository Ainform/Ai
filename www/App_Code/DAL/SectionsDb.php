<?php

/**
 * DAL_SectionsDb class
 * Класс для работы с разделами товаров в БД
 *
 * @author Frame
 * @version SectionsDb.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */
class DAL_SectionsDb extends DAL_BaseDb {

	/**
	  @var string $TableName Название таблицы
	 */
	protected $TableName = "sections";

	/**
	 * Возвращает один раздел
	 *
	 * @param int $sectionId идентификатор раздела
	 * @return array
	 */
	public function GetSection($sectionId) {
		$rows = $this->select(array("SectionId" => $sectionId));

		if (count($rows) > 0)
			return $rows[0];
		else
			return null;
	}

	public function GetParent($id) {

		$sections = $this->select(array("SectionId" => $id));

		if (count($sections) > 0)
			return $sections[0]['ParentId'];

		return null;
	}

	/**
	  Возвращает ссылку на раздел по его идентификатору
	 */
	function GetSectionLink($params) {

		//по умолчанию
		$shopname = "/internet-magazin/";

		if (is_array($params))
			$id = $params['id'];
		else
			$id = $params;

		$sectionpath = '';

		$this->GetSectionFullPath($id, $sectionpath);
		$section = $this->GetSection($id);

		$modDb = new DAL_ModulesDb;
		$module = $modDb->GetModule($section['ModuleId']);
		$pageDb = new DAL_PagesDb;
		$page = $pageDb->GetPage($module['PageId']);
		$shopname = "/" . $page["Alias"] . "/";
		unset($pageDb);
		unset($modDb);

		return $shopname . $sectionpath;
	}

	/**
	 * Возвращает полный путь до раздела одной строкой, полезно при выводе ссылок на товары в каталоге
	 *
	 * @id int $sectionId идентификатор раздела
	 * @string собственно строка, в которой по окончании будет путь со слешем в конце
	 */
	function GetSectionFullPath($id, &$string='') {
		$parent = $this->GetParent($id);

		$thissec = $this->GetSection($id);
		if (!empty($thissec['Alias'])) {
			$id = $thissec['Alias'];
		}
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
	  Возвращает изображение для раздела
	 */
	/* public function GetSectionImage($sectionId)
	  {
	  $section = $this->GetSection($sectionId);

	  if (null == $section['ImageId'])
	  return "";

	  $imagesDb = new DAL_ImagesDb();
	  $image = $imagesDb->Get($section['ImageId']);

	  return DAL_ImagesDb::GetImagePath($image);
	  } */

	public function GetSectionImage($sectionId) {
		$imagesDb = new DAL_ImagesDb();
		$folder = self::GetImageFolder($sectionId);

		$image = $imagesDb->GetTopFromFolder($folder);

		if ($image == null)
			return null;

		return $image;
	}

	/**
	 * Удаляет раздел
	 *
	 * @param int $sectionId идентификатор раздела
	 */
	public function DeleteSection($sectionId) {
		// удаляем изображения
		// обновляем позиции
		$this->Order()->DeleteRecord($sectionId);

		// удаляем
		$this->delete(array("SectionId" => $sectionId));
		$this->DeleteGoodsFromSection($sectionId);
		$this->DeleteChildSections($sectionId);
	}

	/**
	 * Удаляет дочерние разделы
	 *
	 * @param int $sectionId идентификатор раздела
	 */
	private function DeleteChildSections($sectionId) {
		$sections = $this->GetSections($sectionId, null);

		if (0 < count($sectionId))
			foreach ($sections as $section)
				$this->DeleteSection($section['SectionId']);
	}

	/**
	 * Удаляет товары раздела
	 *
	 * @param int $sectionId идентификатор раздела
	 */
	private function DeleteGoodsFromSection($sectionId) {
		$goodsDb = new DAL_GoodsDb();
		$goods = $goodsDb->GetFromSection($sectionId);

		if (0 < count($goods))
			foreach ($goods as $good)
				$goodsDb->DeleteGood($good['GoodId']);
	}

	/**
	 * Возвращает дочерние разделы
	 *
	 * @param int $sectionId идентификатор раздела
	 * @param int $limit количество возвращаемых разделов
	 * @return array
	 */
	public function GetSections($sectionId, $limit = null, $ModuleId=null) {
		return $this->select(array("ParentId" => $sectionId, "ModuleId" => $ModuleId), "Order", false, $limit);
	}

	public function GetSectionsByPage($sectionId, $page, $recordsOnPage) {
		return $this->selectPage(array("ParentId" => $sectionId), "date", true, $page, $recordsOnPage);
	}

	/**
	 * Возвращает дочерние разделы с учётом производителя
	 *
	 * @param int $sectionId идентификатор раздела
	 * @param int $limit количество возвращаемых разделов
	 * @return array
	 */
	public function GetSectionsByManufacturer($sectionId, $manufacturerId, $limit = null) {
		if (!$sectionId) {
			return $this->query("SELECT * FROM sections s JOIN (select distinct SectionId from goods where ManufacturerId=" . $manufacturerId . ") g on s.sectionid=g.sectionid ORDER BY `Order`");
		} else {
			return $this->query("SELECT * FROM sections s JOIN (select distinct SectionId from goods where ManufacturerId=" . $manufacturerId . ") g on s.sectionid=g.sectionid WHERE s.ParentId=" . $sectionId . " ORDER BY `Order`");
			//return $this->select(array("ParentId" => $sectionId), "Order", false, $limit);
		}
	}

	public function GetSectionsForGoods($sectionId, $module) {
		//$sql = "SELECT s.* FROM Sections s
		//WHERE s.ParentId = `$sectionId` AND s.ModuleId = `$module`
		//AND (SELECT COUNT(*) FROM Goods g WHERE g.SectionId = s.SectionId) > 0";
		//return $this->db->ExecuteReader($sql);
		return $this->select(array("ParentId" => $sectionId, "ModuleId" => $module), "Order", false, null);
	}

	/**
	  Возвращает корневые категории

	  @param int $moduleId идентификатор модуля
	 */
	public function GetRootSections($moduleId = null) {
		if (null == $moduleId)
			return $this->select(array("ParentId" => -1), "Order");
		else
			return $this->select(array("ModuleId" => $moduleId, "ParentId" => -1), "Order");
	}

	public function GetRootSectionsByPage($moduleId, $sectionId, $page, $recordsOnPage) {
		return $this->selectPage(array("ParentId" => $sectionId, "ModuleId" => $moduleId), "date", true, $page, $recordsOnPage);
	}

	/**
	  Возвращает корневые категории

	  @param int $moduleId идентификатор модуля
	 */
	public function GetRootSectionsByManufacturer($manufacturerId) {
		/* if (null == $moduleId)
		  return $this->select(array("ParentId" => -1), "Order");
		  else
		  return $this->select(array("ModuleId" => $moduleId, "ParentId" => -1), "Order"); */
		return $this->query("SELECT * FROM sections s JOIN (select distinct SectionId from goods where ManufacturerId=" . $manufacturerId . ") g on s.sectionid=g.sectionid ORDER BY `Order`");
	}

	/**
	 * Возвращает все разделы
	 *
	 * @return array
	 */
	public function GetAllSections($moduleId = null) {
		if (null == $moduleId)
			return $this->select(null, "Order");
		else
			return $this->select(array("ModuleId" => $moduleId), "Order");
	}

	public function GetTree($moduleId = null, $sectionId=-1) {
		if (null == $moduleId)
			$results = $this->select(null, "Order");
		else
			$results = $this->select(array("ModuleId" => $moduleId), "Order");

		function recursion(&$results, &$tree, $sect=-1, $depth=0) {
			global $iteration;
			$depth++;
			foreach ($results as $result) {
				if ($result['ParentId'] == $sect) {
					$result['depth'] = $depth;
					//$result['parent']=$sect;
					$tree[] = $result;
					recursion($results, $tree, $result['SectionId'], $depth);
				}
			}
		}

		$tree = array();
		recursion($results, $tree, $sectionId);

		return $tree;
	}

	/**
	 * Удаляет все разделы по модулю
	 *
	 * @return array
	 */
	public function DeleteAllSections($moduleId) {
		$sections = $this->GetAllSections($moduleId);

		foreach ($sections as $section)
			$this->DeleteSection($section['SectionId']);
	}

	/**
	 * Добавляет раздел
	 */
	public function Add($sectionRow) {
		$sectionRow['Order'] = $this->Order()->InsertRecord($sectionRow['ParentId']);
		$this->insert($sectionRow);

		return $this->db->GetLastId();
	}

	/**
	 * Обновляет раздел
	 */
	public function UpdateSection($sectionRow) {
		$this->update($sectionRow);
	}

	/**
	 * Возвращает экземпляр класса OrderHelper
	 *
	 * @return Helpers_OrderHelper
	 */
	private function Order() {
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo('sections', 'SectionId', 'ParentId');

		return $orderHelper;
	}

	/**
	 * Поднимает раздел
	 *
	 * @param int $sectionId идентификатор раздела
	 */
	public function Up($sectionId) {
		$this->Order()->UpRecord($sectionId);
	}

	/**
	 * Опускает раздел
	 *
	 * @param int $sectionId идентификатор раздела
	 */
	public function Down($sectionId) {
		$this->Order()->DownRecord($sectionId);
	}

	/**
	  Возвращает папку для картинок раздела товаров
	 */
	public static function GetImageFolder($sectionId = null) {
		$folder = "sections/";

		if (isset($sectionId))
			$folder .= $sectionId . "/";

		return $folder;
	}

	/**
	 * В ходе эволюции, зде происходит просто выполнение сикл запроса
	 *
	 * @param string $sql запрос
	 * @return идентификатор последней вставленной записи
	 */
	public function InsertSections($sql) {
		$this->db->ExecuteQuery($sql);
		return $this->db->GetLastId();
	}

	/**
	 * Обновляет поля родительских секций после загрузки
	 *
	 */
	public function UpdateSectionsStructureAfterImport() {
		$sql = "UPDATE sections s SET s.ParentId = (SELECT ss.SectionId FROM sections WHERE ss.Code = s.ParentCode)";
	}

	public function GetSectionByCode($code) {
		$sections = $this->select(array("Code" => $code));

		if (count($sections) > 0)
			return $sections[0];

		return null;
	}

	public function GetCount($sectionId) {
		return $this->selectCount(array("ParentId" => $sectionId));
	}

	public function GetCountForModule($moduleid, $sectionId) {
		return $this->selectCount(array("ParentId" => $sectionId, "ModuleId" => $moduleid));
	}

}