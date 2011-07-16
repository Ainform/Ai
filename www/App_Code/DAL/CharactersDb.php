<?php

/**
 * DAL_CharactersDb class
 * Класс для работы с новостями в БД
 * 
 * @author Frame
 * @version 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_CharactersDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Characteristics";

	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array("CharactersId"  => "int",
		"ModuleId" 	   => "int",
		"Title" => "string",
		"Photo"       => "string",
		"Text"    => "string",
		"Order" => "int"
		);
	}

	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("CharactersId");
	}

	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("CharactersId");
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
	 * Возвращает новость
	 *
	 * @param int $CharactersId идентификатор новости
	 * @return array
	 */
	public function GetCharacters($CharactersId)
	{
		$result = $this->select(array("CharactersId" => $CharactersId));
		return $result[0];
	}
	
	
	/**
	 * Возвращает новость
	 *
	 * @param int $CharactersId идентификатор новости
	 * @return array
	 */
	public function GetCharacterList()
	{
		$result = $this->select(array(), "Order");
		return count($result) == 0 ? null : $result;
	}
	
	
	/**
		Возвращает папку для картинок новости
	*/
	public static function GetImageFolder($CharactersId = null)
	{
		$folder = "Characters/";

		if (isset($CharactersId))
		$folder .= $CharactersId."/";

		return $folder;
	}

	/**
	 * Удаляет новость
	 *
	 * @param int $CharactersId идентификатор новости
	 */
	public function DeleteCharacters($CharactersId)
	{
		$this->delete(array("CharactersId" => $CharactersId));
	}

	/**
	 * Удаляет новости
	 *
	 * @param int $moduleId идентификатор новости
	 */
	public function DeleteAllCharacters($moduleId)
	{
		$CharactersList = $this->GetAllCharacters($moduleId);

		foreach ($CharactersList as $Characters)
		$this->DeleteCharacters($Characters['CharactersId']);
	}

	/**
	 * Возвращает все новости для модуля
	 *
	 * @return array
	 */
	public function GetAllCharacters($moduleId)
	{
		return $this->select(array("ModuleId" => $moduleId), "Date", true);
	}


	/**
	 * Возвращает последние новости
	 *
	 * @param int $count количество новостей
	 * @return array
	 */
	public function GetLastCharacters($count)
	{
		return $this->select(null, "Date", true, $count);
	}


	/**
	 * Возвращает запрошенную страницу с новостями
	 *
	 * @param int $page номер страницы
	 * @param int $recordsOnPage количество записей на страницу
	 * @param int $count общее количество записей
	 * @return array
	 */
	public function GetCharactersPage($moduleId, $page, $recordsOnPage)
	{
		return $this->selectPage(array("ModuleId" => $moduleId),
										"Order",
										true,
										$page,
										$recordsOnPage
								);
	}

	/**
		Возвращает общее количество новостей для текущего модуля
	
		@param int $moduleId идентификатор модуля
	*/
	public function GetCountCharacters($moduleId)
	{
		return $this->selectCount(array("ModuleId" => $moduleId));
	}

	/**
	 * Добавляет новость
	 *
	 * @param row $CharactersRow данные по новости
	 */
	public function AddCharacters($CharactersRow)
	{
		$this->insert($CharactersRow);

		return $this->db->GetLastId();
	}


	/**
	 * Обновляет новость
	 *
	 * @param row $CharactersRow данные по новости
	 */
	public function UpdateCharacters($CharactersRow)
	{
		$this->update($CharactersRow);
	}
	
	/**
	* Возвращает экземпляр класса OrderHelper
	*
	* @return Helpers_OrderHelper
	*/
	private function Order()
		{
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo('Characteristics', 'CharactersId', 'ModuleId');

		return $orderHelper;
	}	
	
	/**
	* Поднимает характеристику на запись вверх
	*
	* @param int $сhId идентификатор характеристики
	*/
	public function Up($chId)
	{
		$this->Order()->UpRecord($chId);
	}

	/**
	* опускает характеристику на запись вниз
	*
	* @param int $сhId идентификатор характеристики
	*/	
	public function Down($chId)
	{
		$this->Order()->DownRecord($chId);
	}
	
}