<?php
class DAL_LanguagesDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "languages";
	
	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"LanguageId" => "int",
				"Alias" => "string",
				"Name" => "string"
				);
	}
	
	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("LanguageId");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("LanguageId");
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
	 * Возвращает все языки
	 */
	public function GetLanguages()
	{
		return $this->select();
	}
	
	public function GetLanguage($langId)
	{
		return $this->select(array("LanguageId" => $langId));
	}
}
?>