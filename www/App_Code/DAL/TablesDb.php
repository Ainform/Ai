<?php
/**
 * DAL_TablesDb class
 * Класс для работы с модулем "Таблицы" в БД
 * 
 * @author Informix
 * @version 0.q
 * @copyright (c) by Informix
 */

class DAL_TablesDb extends DAL_BaseDb{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Tables";
	
	/**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"Id" => "int",
				"ModuleId" => "int",
				"TableName" => "string",
				"Title" => "string",
				"Type" => "string",
				"Position" => "int",
				"Display" => "string"
		);
	}
	
	/**
	Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("Id","ModuleId");
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
	 * @param int $ModuleId ID Модуля
	 * @return array
	 * */
	public function GetTableSchema($ModuleId,$Format = "Informix"){
		$return = array();
		$result = $this->select(array("ModuleId"=>$ModuleId),"Position");
		if(!empty($result)){
			if($Format == "Informix"){
				foreach ($result as $row){
					$return[$row["Id"]] = $row["Title"];
				}
			}else{
				$return = $result;
			}
		}
		return $return;
	}
	
	
	/**
	 * Достаем строки таблицы используя ID инкремент таблицы
	 * @param int $TableId ID таблицы
	 * @return array
	 * */
	
	public function GetRows($TablesId){
		$TableRows = new DAL_TableRowsDb();
		$return = $TableRows->GetRows($TablesId,"Row");
		unset($TableRows);
		$re = array();
		foreach ($return as $i=>$data){
			$re[$data["Row"]][$data["TableId"]] = $data["Value"];
		}
		return $re;
	}
	
	
	/**
	 * Достаем всю таблицу по модулю
	 * @param array $ModuleId инкремент модуля
	 * 
	 * 
	 * */
	
	public function GetTable($ModuleId = null){
		$Schemas = $this->GetTableSchema($ModuleId);
		$Table = array();
		$TableIds = array("TableId"=>array_keys($Schemas));
		
		$Rows = $this->GetRows($TableIds);
		
		
		foreach ($Schemas as $Id => $Title){
			foreach ($Rows as $Row=>$Data){
				$Table[$Row][$Title] = (!isset($Data[$Id]))?"&nbsp;":$Data[$Id];
			}
		}
		//Debug($Table);
		return $Table; 
	}
	
	public function GetRowFromTable($ModuleId = null,$Row = null){
		$allData = $this->GetTable($ModuleId);
		if(isset($allData[$Row])){
			return $allData[$Row];
		}else{
			return false;
		}
	}
	
	/**
	 * Перемещает структуру таблиц
	 * */
	public function MovieSchema($ModuleId,$SchemaId = null,$where = "up"){
		
		$Schema = $this->GetTableSchema($ModuleId,"blabla");
		foreach ($Schema as $data){
			if($data["Id"] == $SchemaId){
				//Debug($data);
				$RealPosition = $data["Position"];
			}
		}
		
		if($where == "up"){
			$RealPosition = $RealPosition - 1;
			$sql = "update `$this->TableName` set Position = Position + 1 where ModuleId = $ModuleId and Position = $RealPosition  limit 1;";
			$sql2 = "update `$this->TableName` set Position = Position - 1 where ModuleId = $ModuleId and Id = $SchemaId limit 1;";
		}else{
			$RealPosition = $RealPosition + 1;
			$sql = "update `$this->TableName` set Position = Position - 1 where ModuleId = $ModuleId and Position = $RealPosition  limit 1;";
			$sql2 = "update `$this->TableName` set Position = Position + 1 where ModuleId = $ModuleId and Id = $SchemaId limit 1;";
		}
		
		//Debug($RealPosition);
		//Debug($sql . "<br>" .$sql2);
		$this->query($sql);
		$this->query($sql2);
		
	}
	
	/**
	 * обновляет структуру
	 * */
	public function TableUpdate($Id = null,$Value = null,$Type = null,$tableName = null, $Display = "true"){
		$sql = "update `$this->TableName` set `Type`='" . $Type[$Id] ."', `Title` = '$Value', TableName = '$tableName',Display = '$Display' where Id = $Id;";
		$this->query($sql);
		//Debug($sql."<br>",false);
	}

	
	/**
	 * Добаввляет в структуру
	 * */
	public function TableInsert($ModuleId = null, $Value = null,$Type = null,$tableName = null, $Display = "true"){
		$result = $this->query("SELECT max(Position) FROM `Tables`");
		$pos = $result[0]["max(Position)"] + 1;
		$sql = "insert into `$this->TableName` (`ModuleId`,`Type`,`Title`,`Position`,`TableName`,`Display`) values ($ModuleId, '$Type', '$Value','$pos','$tableName','$Display')";
		//Debug($sql);
		$this->query($sql);
		//Debug($sql."<br>",false);
	}
	
	public function DeleteFromScheme($TableId = null){
		$res = $this->select(array("Id"=>$TableId));
		$this->delete(array("Id"=>$TableId));
		$this->query("update `$this->TableName` set Position = Position - 1 where Position > " . $res[0]["Position"]);
		$TableRows = new DAL_TableRowsDb();
		$return = $TableRows->delete(array("TableId"=>$TableId));
	}
	
	/**
	 * Сохранение в таблице
	 * */
	public function InserRow($TableId = null,$Value = null,$Row = null){
		@$Row++;
		$sql = "INSERT INTO `TableRows` (`TableId`,`Value`,`Row`) VALUES ('$TableId','$Value','$Row');";
		//Debug($sql,false);
		$this->query($sql);
	}
	
	/**
	 * Выдает максимально значения строк в списке
	 * */
	public function GetMaxRows($Schema = null){
		foreach ($Schema as $Ids){
			$Res[] = $Ids["Id"];
		}
		$sql = "select max(Row) as max FROM TableRows where TableId in (" . implode(",",$Res) . ")";
		$result = $this->query($sql);
		return $result[0]["max"];
	}
	
	
	public function UpdateRow($TableId = null,$Value = null,$RowNumber = null){
		$TableRow = new DAL_TableRowsDb();
		$Id = $TableRow->GetIds($RowNumber,$TableId);
		//Debug($Id);
		$array = array(
		"Row"=>$RowNumber,
		"Id"=>@$Id[0]["Id"],
		"Value"=>$Value
		);
		$TableRow->UpdateData($array);
		
	}
	
	
	/**
	 * 
	 * */
	public function deleteRow($RowNumber = null){
		$TableRow = new DAL_TableRowsDb();
		$Id = $TableRow->delete(array("Row"=>$RowNumber));
		$this->query("update TableRows set Row = Row - 1 where Row > $RowNumber");
	}
	
	
}

	
?>