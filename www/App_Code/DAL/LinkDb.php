<?php 
class DAL_LinkDb extends DAL_BaseDb{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Links";

	/**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"Id" => "int",
				"ModuleId" => "int",
				"Url" => "string",
		);
	}
	 
	/**
	Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
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
	
	
	public function getLink($moduleId = null){
		return $this->select(array("ModuleId"=>$moduleId));
	}
	
	
	public function up($array){
		return $this->update($array);
	}
	
	public function ins($array){
		return $this->insert($array);
	}
	
	
	
	
}
	
	