<?php

/**
 * DAL_PersonalsDb class
 * Класс для работы с лицензиями в БД
 *
 * @author Frame
 * @version 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_PersonalsDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "personals";

	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"PersonalsId" => "int",
				"ModuleId" => "int",
				"Name" => "string",
				"Order" => "int",
				"Anons" => "string",
                                "Position" => "string"
				);
	}

	/**
		Возвращает первичные ключи таблицы

		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("PersonalsId");
	}

	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("PersonalsId");
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
	 * Возвращает лицензию
	 *
	 * @param int $personalsId идентификатор лицензии
	 * @return array
	 */
	public function GetPersonalss($personalsId)
	{
		$result = $this->select(array("PersonalsId" => $personalsId));
		return $result[0];
	}

	/**
		Возвращает папку для картинок лицензии
	*/
	public static function GetImageFolder($personalsId = null)
	{
		$folder = "personals/";

		if (isset($personalsId))
			$folder .= $personalsId."/";

		return $folder;
	}

	/**
	 * Удаляет лицензию
	 *
	 * @param int $personalsId идентификатор лицензии
	 */
	public function DeletePersonals($personalsId)
	{
		$personals = $this->GetPersonalss($personalsId);
		$imageUtility = new Utility_ImageUtility();

		$this->delete(array("PersonalsId" => $personalsId));

		// удаляем файлы из текста
		//$imageUtility->SetDirectory('personals'.$personalsId);
		//$imageUtility->DeleteFiles($personals['Text']);

		$imagesDb = new DAL_ImagesDb();
			$imagesDb->DeleteFolder(self::GetImageFolder($personalsId));
		unset($imagesDb);
	}

	/**
	 * Удаляет лицензии
	 *
	 * @param int $moduleId идентификатор лицензии
	 */
	public function DeleteAllPersonalss($moduleId)
	{
		$personalsList = $this->GetAllPersonalss($moduleId);

		foreach ($personalsList as $personals)
			$this->DeletePersonals($personals['PersonalsId']);
	}

	/**
	 * Возвращает все новости для модуля
	 *
	 * @return array
	 */
	public function GetAllPersonals($moduleId)
	{
		//Debug($moduleId);
		return $this->select(array("ModuleId" => $moduleId), "Date", true);
	}


	/**
	 * Возвращает запрошенную страницу с лицензиями
	 *
	 * @param int $page номер страницы
	 * @param int $recordsOnPage количество записей на страницу
	 * @param int $count общее количество записей
	 * @return array
	 */
	public function GetPersonalsPage($moduleId, $page, $recordsOnPage)
	{
		return $this->selectPage(array("ModuleId" => $moduleId),"Order","ASC",$page,$recordsOnPage);
	}

	/**
		Возвращает общее количество лицензий для текущего модуля

		@param int $moduleId идентификатор модуля
	*/
	public function GetCountPersonals($moduleId)
	{
		return $this->selectCount(array("ModuleId" => $moduleId));
	}

	/**
	 * Добавляет лицензию
	 *
	 * @param row $personalsRow данные по лицензии
	 */
	public function AddPersonals($personalsRow)
	{
		//Debug($newsRow);
                $personalsRow['Order'] = $this->Order()->InsertRecord($personalsRow['ModuleId']);
		$this->insert($personalsRow);

		return $this->db->GetLastId();
	}


	/**
	 * Обновляет лицензии
	 *
	 * @param row $personalsRow данные по лицензиям
	 */
	public function UpdatePersonals($personalsRow)
	{
		$this->update($personalsRow);
	}

        /**
	 * Возвращает экземпляр класса OrderHelper
	 *
	 * @return Helpers_OrderHelper
	 */
	private function Order()
	{
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo('personals', 'PersonalsId','ModuleId');

		return $orderHelper;
	}

	/**
	 * Поднимает лицензию
	 *
	 * @param int $imageId идентификатор лицензии
	 */
	public function Up($personalsId)
	{
   		$this->Order()->UpRecord($personalsId);
	}

	/**
	 * Опускает лицензию
	 *
	 * @param int $imageId идентификатор лицензии
	 */
	public function Down($personalsId)
	{
		$this->Order()->DownRecord($personalsId);
	}
}
