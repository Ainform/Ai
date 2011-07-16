<?php
/**
 * DAL_JuryDb class
 */
class DAL_BlogsDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "BlogPosts";
	
	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"BlogPostId" => "int",
				"ModuleId" => "int",
				"Title" => "string",
				"Content" => "string",
				"CreatedDate" => 'string'
				);
	}
	
	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("BlogPostId");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("BlogPostId");
	}

	/**
	 * Констуктор, инициализирует соединение
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	*	Returns posts for module
	*/
	function GetPosts($moduleId)
	{
		return $this->select(array("ModuleId" => $moduleId));
	}
}
?>