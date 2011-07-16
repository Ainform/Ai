<?php

/**
 * Класс для работы со скидками в БД
 *
 */

class DAL_DiscountsDb extends DAL_BaseDb {
  /**
	@var string $TableName Название таблицы
	*/
  protected $TableName = "discounts";

  /**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
  protected function getStructure() {
	 return array(
				"id" => "int",
				"UserId" => "int",
				"ManufacturerId" => "int",
				"Discount" => "string",
	 );
  }

  /**
	Возвращает первичные ключи таблицы
		
	@return array ключи таблицы
	*/
  protected function getKeys() {
	 return array("id");
  }

  /**
	@return array автоинкрементные индексы таблицы
	*/
  protected function getIndexes() {
	 return array("id");
  }

  /**
	* Констуктор, инициализирует соединение
	*
	*/
  function __construct() {
	 parent::__construct();
  }


  
	 // загружаем скидки для юзера
  function GetSalesForUser($UserId) {

	$temp=$this->select(array('UserId'=>$UserId));
	return $temp;  
  }
  
		// загружаем скидку юзера для конкретного производителя
  function GetSaleForUserByManufacturer($UserId,$ManufacturerId) {
	$temp=$this->select(array('UserId'=>$UserId,'ManufacturerId'=>$ManufacturerId));
	return $temp;  
  }
  
		// инсертим скидку
  function InsertDiscount($row) {
	$temp=$this->insert($row);
	return $temp;  
  }
		// обновляем скидку
  function UpdateDiscount($row) {

	$temp=$this->update($row);
	return $temp;  
  }
	function clearDiscount($id) {

	$temp=$this->delete(array('id'=>$id));
	return $temp;  
  }


}

?>