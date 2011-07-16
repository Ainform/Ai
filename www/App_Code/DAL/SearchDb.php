<?php

/**
 * DAL_SearchDb class
 * Класс для работы с поиском
 * 
 * @author Informix
 */

class DAL_SearchDb extends DAL_BaseDb
{
	
	
	protected $TableName = "searchindex";
	
	
	protected function getStructure()
	{
		return array(
				"Id" => "int",
				"ModuleId" => "int",
				"Date" => "string",
				"Title" => "string",
				"Url" => "string",
				"Body" => "string",
		);
	}
	
	protected function getKeys()
	{
		return array("Id");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("Id");
	}
	
	/**
	 * Выполнение произвольного запроса к БД
	 * 
	 * @param string SQL запрос
	 * @return array Результат выполнения запроса
	 * */
	public function sql($query){
		return $this->query($query);
	}
	
	
	public function SaveIndex($array){
		//echo __FILE__;
		$this->insert($array);
	}
	
	
}