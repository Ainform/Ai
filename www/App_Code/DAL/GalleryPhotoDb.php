<?php

/**
 * DAL_GalleryPhotoDb class
 * Класс для работы с фотографиями в БД
 *
 */

class DAL_GalleryPhotoDb extends DAL_BaseDb
{
	/**
	 @var string $TableName Название таблицы
	 */
	protected $TableName = "gallery_photo";

	/**
	 Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

	 @return array структура таблицы
	 */
	protected function getStructure()
	{
		return array(
		"GoodId" => "int",
		"SectionId" => "int",
		"Title" => "string",
		"Description" => "string",
		"Price" => "float",
		"Abstract" => "string",
		"Order" => "int",
		"Onmain" => "int",
		"Code" => "string",
		"TrueCode" => "string",
		"Quantity" => "int",
		"GoodsAtWarehouse" => "string",
		"ManufacturerId" => "int",
		"Properties" => "string",
		"Currency" => "int"
		);
	}

	/**
	 Возвращает первичные ключи таблицы

	 @return array ключи таблицы
	 */
	protected function getKeys()
	{
		return array("GoodId");
	}

	/**
	 @return array автоинкрементные индексы таблицы
	 */
	protected function getIndexes()
	{
		return array("GoodId");
	}

	/**
	 * Констуктор, инициализирует соединение
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Возвращает один товар
	 *
	 * @param int $goodId идентификатор товара
	 * @return array
	 */
	public function GetGood($goodId)
	{
		return $this->selectFirst(array("GoodId" => $goodId));
	}

	public function GetNameById($goodId)
	{
		$result=$this->selectFirst(array("GoodId" => $goodId));
		return $result['Title'];
	}

	public function GetGoodByCode($code)
	{
		return $this->selectFirst(array("Code" => $code));
	}

	/**
	 * Возвращает товары на главной
	 */
	public function GetGoodsOnMain()
	{
		$goods = $this->select(array("Onmain" => 1), "Title", null, 10);

		return $goods;
	}

	/**
	 * Удаляет товар
	 *
	 * @param int $goodId идентификатор товара
	 */
	public function DeleteGood($goodId)
	{
		$good = $this->GetGood($goodId);
		if (null == $good)
			return;

		$imageUtility = new Utility_ImageUtility();

		$this->delete(array("GoodId" => $goodId));

		$imagesDb = new DAL_ImagesDb();
		$imagesDb->DeleteFolder(self::GetImageFolder($goodId));
		unset($imagesDb);
	}

	/**
	 * Возвращает товары из раздела
	 *
	 * @param int $sectionId идентификатор раздела
	 * @return array
	 */
	public function GetFromSection($sectionId, $page=0, $count=10)
	{
		return $this->selectPage(array("SectionId" => $sectionId), "Order", false, $page, $count);
	}

        public function GetPageAliasByGoodId($id){
            return $this->query("SELECT pages.Alias FROM goods LEFT JOIN sections ON goods.SectionId=sections.SectionId LEFT JOIN pagemodules ON sections.ModuleId=pagemodules.ModuleId LEFT JOIN pages ON pagemodules.PageId=pages.PageId WHERE goods.GoodId='".$id."'");
        }

	/**
	 * Возвращает товары из раздела если у них производитель такой-то
	 *
	 * @param int $sectionId идентификатор раздела
	 * @return array
	 */
	public function GetFromSectionByManufacturer($sectionId,$manufacturerId, $page=0, $count=10)
	{
		return $this->selectPage(array("SectionId" => $sectionId,"ManufacturerId"=>$manufacturerId), "Order", false, $page, $count);
	}

	public function GetFromSectionSort($sectionId,$order,$superorder)
	{
		return $this->select(array("SectionId" => $sectionId), $order,$superorder);
	}

	public function GetCountFromSection($sectionId)
	{
		return $this->selectCount(array("SectionId" => $sectionId));
	}

	public function GetCountFromSectionByManufacturer($sectionId, $manufacturerId)
	{
		return $this->selectCount(array("SectionId" => $sectionId,"ManufacturerId"=>$manufacturerId));
	}

	/**
	 Возвращает ключ-папку в которой хранятся картинки для товара
	 */
	public static function GetImageFolder($goodId = null)
	{
		$folder = "galleryphoto/";

		if (isset($goodId))
			$folder .= $goodId;

		return $folder;
	}

	public static function GetFilesFolder($goodId = null)
	{
		$folder = "galleryphoto/";

		if (isset($goodId))
			$folder .= $goodId;

		return $folder;
	}

	/**
	 Возвращает самую верхнюю картинку для товара
	 */
	public function GetGoodImage($goodId)
	{
		$imagesDb = new DAL_ImagesDb();
		$folder = self::GetImageFolder($goodId);

		$image = $imagesDb->GetTopFromFolder($folder);

		if ($image == null)
			return null;

		return $image;
	}

	/**
	 Возвращает самую картинки для товара
	 */
	public function GetGoodImages($goodId)
	{
		$imagesDb = new DAL_ImagesDb();
		$folder = self::GetImageFolder($goodId);

		$images = $imagesDb->GetFromFolder($folder);

		return $images;
	}

	/**
	 Добавляет в таблицу товаров дополнительную колонку с адресом картинки
	 */
	public function AddImageToGoods(&$goods)
	{
		$imagesDb = new DAL_ImagesDb();

		foreach ($goods as &$good)
		{
			$good['Image'] = "";

			$folder = self::GetImageFolder($good['GoodId']);

			$image = $imagesDb->GetTopFromFolder($folder);

			if ($image == null)
				continue;

			$good['Image'] = DAL_ImagesDb::GetImagePath($image);
		}
	}

	/**
	 Добавляет в таблицу товаров дополнительную колонку с адресом картинки
	 */
	public function AddImageAllToGoods(&$goods)
	{
		$imagesDb = new DAL_ImagesDb();

		foreach ($goods as &$good)
		{
			$good['Image'] = array();
			$folder = self::GetImageFolder($good['GoodId']);
			$image = $imagesDb->GetTopFromFolder($folder);

			if ($image == null)
				continue;

			$good['Image'] = DAL_ImagesDb::GetImagePath($image);
		}

	}

	/**
	 * Добавляет товар
	 *
	 * @param array $goodRow данные товара
	 */
	public function AddGood($goodRow)
	{
		$baseDb=new DAL_BaseDb();
		$result=$baseDb->query("SELECT MAX(`Order`) FROM $this->TableName WHERE `SectionId`='".$goodRow['SectionId']."'");
		if($result){
		$goodRow['Order']=($result[0]['MAX(`Order`)']+1);}
		
		$this->insert($goodRow);

		$goodId = $this->db->GetLastId();
		$goodRow['GoodId'] = $goodId;

		//$this->CheckSectionImage($goodRow);

		return $goodId;
	}

	/**
	 * Обновляет товар
	 *
	 * @param array $goodRow данные товара
	 */
	public function UpdateGood($goodRow)
	{
		$this->update($goodRow);

		$this->CheckSectionImage($goodRow);
	}

	/**
	 Возвращает список товаров для конкретного модуля
	 */
	public function GetGoodsForModule($moduleId)
	{
		$query = "SELECT * FROM goods WHERE SectionId IN (SELECT SectionId FROM sections WHERE ModuleId = ".intval($moduleId).")";
		return $this->db->ExecuteReader($query);
	}

	/**
	 Возвращает список картинок для фотогалереи
	 */
	public function GetAllFoto()
	{
		$query = "SELECT * FROM goods WHERE `Abstract`=''";
		return $this->db->ExecuteReader($query);
	}

	/**
	 Проверяет картинку для родительской секции и обновляет в случае необходимости
	 */
	public function CheckSectionImage($goodRow)
	{
		// получаем полный данные по товару
		$goodRow = $this->GetGood($goodRow['GoodId']);

		// получаем изображение товара
		$images = $this->GetGoodImages($goodRow['GoodId']);

		if (count($images) == 0)
			return;

		// получаем данные раздела товара
		$sectionsDb = new DAL_SectionsDb();
		$section = $sectionsDb->GetSection($goodRow['SectionId']);

		if (!isset($section))
			return;

		if ($section['ImageId'] > 0)
			return;

		$section['ImageId'] = $images[0]['ImageId'];
		$sectionsDb->UpdateSection($section);
	}

	/**
	 * Возвращает экземпляр класса OrderHelper
	 *
	 * @return Helpers_OrderHelper
	 */
	private function Order()
	{
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo('gallery_photo', 'GoodId', 'SectionId');

		return $orderHelper;
	}

	/**
	 * Поднимает товар
	 *
	 * @param int $goodId идентификатор товара
	 */
	public function Up($goodId)
	{
		$this->Order()->UpRecord($goodId);
	}

	public function Down($goodId)
	{
		$this->Order()->DownRecord($goodId);
	}

	/**
	 * В ходе эволюции, зде происходит просто выполнение сикл запроса
	 *
	 * @param string $sql запрос
	 * @return идентификатор последней вставленной записи
	 */
	public function InsertGoods($sql)
	{

		$this->db->ExecuteQuery($sql);

		return $this->db->GetLastId();
	}

	public function ClearTable()
	{
		$sql = "TRUNCATE TABLE `goods`";
		$this->db->ExecuteScalar($sql);
	}	

	public function GetPriceForUser($UserId, $Code){

		$result=$this->query("SELECT Price, CurrencyId FROM prices WHERE UserId=".$UserId." AND GoodCode=\"".$Code."\"");
		if(count($result)>0) return $result[0];
		else
			return false;
	}
	public function GetPriceByCode($Code){

		$result=$this->selectFirst(array("Code"=>$Code));
		if(count($result)>0) return $result['Price'];
		else
			return 0;
	}
	public function checkCode($Code,$currentCode){

		$result=$this->query("SELECT * FROM $this->TableName WHERE `Code`!='$currentCode' AND `Code`='$Code'");
		if(count($result)>0) return 1;
		else
			return 0;
	}
	public function GetCurrencyName($CurrencyId){

		$result=$this->query("SELECT Name FROM currency WHERE CurrencyId=".$CurrencyId);
		if(count($result)>0) return $result[0]['Name'];
		else
			return false;
	}
	public function GetCrossPrice($Price,$PrevCurrency,$CurCurrency){
		//TODO вот нифига тут кроскурса нет пока, только рубли считаем, должна быть возможность например перевести из долларов в евро

		$result=$this->query("SELECT Value FROM currency WHERE CurrencyId=".$PrevCurrency);
		if(count($result)>0) return round($Price*$result[0]['Value'],2);
		else
			return false;
	}
	public function GetRate($id){

		$result=$this->query("SELECT Value FROM currency WHERE CurrencyId=".$id);
		if(count($result)>0) return $result[0]['Value'];
		else
			return false;
	}
	public function SetRate($id,$rate){

		$result=$this->query("UPDATE currency SET value=".$rate." WHERE CurrencyId=".$id);
	}

}