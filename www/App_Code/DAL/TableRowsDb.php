<?php
/**
 * DAL_TablesDb class
 * Класс для работы с модулем "Таблицы" в БД
 * 
 * @author Informix
 * @version 0.q
 * @copyright (c) by Informix
 */

class DAL_TableRowsDb extends DAL_BaseDb{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "TableRows";
	
	/**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"Id" => "int",
				"TableId" => "int",
				"Row" => "int",
				"Type" => "string"
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

	/**
	 * Констуктор, инициализирует соединение
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Достаем Схему таблицы по ID Модуля
	 * 
	 * @param array $TableIds ID необходимые для получение данных из таблицы
	 * @return array
	 * */
	public function GetRows($TableIds = array(),$order = null, $orderInv = false, $limit = null){
		return $this->selectIn($TableIds,$order,$orderInv,$limit);
	}
	
	public function UpdateData($data){
		$this->update($data);
	}
	
	public function GetIds($row,$TableId){
		return $this->select(array("TableId"=>$TableId,"Row"=>$row));
	}
	
	public function SetUp($ModuleId = null, $RowNumber = null){
		$Schema = new DAL_TablesDb();
		$res = array_keys($Schema->GetTableSchema($ModuleId));
		$ID = $this->selectIn(array("TableId"=>$res));
		$first = array();
		$second = array();
		$preRow = $RowNumber - 1;
		foreach ($ID as $row=>$data){
			if($data["Row"] == $preRow){
				$first[] = $data["Id"];
			}
			if($data["Row"] == $RowNumber){
				$second[] = $data["Id"];
			}
		}
		
		$sql = "Update `$this->TableName` set Row = $RowNumber where Id = " . implode(" or Id = ",$first) . ";";
		$sql2 = "Update `$this->TableName` set Row = $preRow where Id = " . implode(" or Id = ",$second) . ";";
		//Debug($sql . $sql2);
		$this->query($sql);
		$this->query($sql2);
	}
	
	
	public function SetDown($ModuleId = null, $RowNumber = null){
		$Schema = new DAL_TablesDb();
		$res = array_keys($Schema->GetTableSchema($ModuleId));
		$ID = $this->selectIn(array("TableId"=>$res));
		$first = array();
		$second = array();
		$preRow = $RowNumber + 1;
		foreach ($ID as $row=>$data){
			if($data["Row"] == $preRow){
				$first[] = $data["Id"];
			}
			if($data["Row"] == $RowNumber){
				$second[] = $data["Id"];
			}
		}
		
		$sql = "Update `$this->TableName` set Row = $RowNumber where Id = " . implode(" or Id = ",$first) . ";";
		$sql2 = "Update `$this->TableName` set Row = $preRow where Id = " . implode(" or Id = ",$second) . ";";
		//Debug($sql . $sql2);
		$this->query($sql);
		$this->query($sql2);
	}
	
}
?>