<?php

/**
 * DAL_RepositoryDb class
 * Класс для работы с хранилищем файлов
 *
 * @author SanSYS
 * @version RepositoryDb.class.php, v 1.0.1
 * @copyright (c) by Inline ... - 2008
 *
 */

class DAL_ManufacturersDb extends DAL_BaseDb {
  /**
	@var string $TableName Название таблицы
	*/
  protected $TableName = "manufacturers";

  /**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
	@return array структура таблицы
	*/
  protected function getStructure() {
	 return array(
				"ManufacturerId"      => 'int'    ,
				"Code"      => 'string'    ,
				"Name"     => 'string',
				"Description"     => 'string'
		);
  }

  /**
	Возвращает первичные ключи таблицы
	@return array ключи таблицы
	*/
  protected function getKeys() {
	 return array("ManufacturerId");
  }

  /**
	@return array автоинкрементные индексы таблицы
	*/
  protected function getIndexes() {
	 return array("ManufacturerId");
  }

  /**
	* Констуктор, инициализирует соединение
	*
	*/
  function __construct() {
	 parent::__construct();
  }

  public function Add($row) {
		$this->insert($row);
  }
  public function ManufacturerUpdate($row) {

		$this->update($row);
  }

  public function GetAll()
  {
	return $this->select(array(), "Name", false);
  }

	 /**
	* Возвращает запрошенную страницу
	*
	* @param int $page номер страницы
	* @param int $recordsOnPage количество записей на страницу
	* @param int $count общее количество записей
	* @return array
	*/
  public function GetPage($page, $recordsOnPage) {
	 $rows =  $this->selectPage(array(),"date",null,$page,$recordsOnPage);

	 foreach($rows as &$row) {
		$row['Url'] = "/?manufacturerId=".$row["ManufacturerId"];
	 }

	 return $rows;
  }

	 /**
	* Возвращает материал
	*
	* @param int $Id идентификатор материала
	* @return array
	*/
  public function GetOne($Id) {
	 $result = $this->select(array("ManufacturerId" => $Id));
	 return $result[0];
  }

  public function GetNameById($Id) {
	 $result = $this->select(array("ManufacturerId" => $Id));
	 if($result){
	 return $result[0]['Name'];}
	 else{
		  return "";
	 }
  }

	 public function GetCodeById($Id) {
	 $result = $this->select(array("ManufacturerId" => $Id));
	 if($result){
	 return $result[0]['Code'];}
	 else{
		  return "";
	 }
  }

	 /**
	Возвращает общее количество для текущего модуля

	*/
  public function GetCount() {
		return $this->selectCount(array());
  }

	 /**
	* Удаляем
	*
	* @param int $Id идентификатор
	*/
  public function Remove($Id) {

	 $this->delete(array("ManufacturerId" => $Id));
  }
	 /**
	Возвращает папку для материала
	*/
  public static function GetFolder($id = null) {
	 $folder = "manufacturer";

	 //внутри создаём подпапку с именем равными id материала
	 if (isset($id))
		$folder .= "/".$id."/";

	 return $folder;
  }

}
/*
 * SELECT *
FROM
Sections s JOIN
(select distinct SectionId from goods where ManufacturerId=361) g on s.sectionid=g.sectionid
 */
?>
