<?php
/**
 * DAL_BannerDb class
 * Класс для работы с баннерами
 *
 * @author Informix
 * @version 0.q
 * @copyright (c) by Informix
 */

class DAL_BannersDb extends DAL_BaseDb{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Banners";

	/**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

	@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"BannerId" => "int",
				"ModuleId" => "int",
				"GroupName" => "string",
				"Title" => "string",
				"File" => "string",
				"StartDate" => "int",
				"StopDate" => "int",
				"Url" => "string",
				"Width" => "int",
				"Height" => "int",
				"Type" => "string",
				"Shows" => "int",
				"Clicks" => "int",
		);
	}

	/**
	Возвращает первичные ключи таблицы

		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("BannerId");
	}

	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("BannerId");
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
	Возвращает папку для картинок новости
	*/
	public static function GetImageFolder($BannerId = null)
	{
		$folder = "banner/";

		if (isset($BannerId))
			$folder .= $BannerId."/";

		return $folder;
	}

	/**
	 * Вазвращает все группы
	 *
	 * @return array Список групп в масииве
	 */
	public function GetAllGroups(){
		return $this->query("Select GroupName from " . $this->TableName . " Group by GroupName");
	}

	/**
	 * Возвращает соджержание баннеров в группе по ее названию
	 *
	 * @param string $GroupName
	 * @return array
	 */
	public function GetGroup($GroupName = ""){
		return $this->select(array("GroupName"=>$GroupName));
	}

	/**
	 * Выполняет произвольный ЫЙД запрос к БД
	 *
	 * @param string $sql
	 * @return array результат запроса
	 */
	public function querys($sql){
		return $this->query($sql);
	}

	/**
	 * Добавлет запись в Таблицу
	 *
	 * @param array $array
	 * @return array
	 */
	public function add($array = array()){
		return $this->insert($array);
	}

	/**
	 * Обновляет данные
	 *
	 */
	public function updater($array = array()){
		return $this->update($array);
	}


	/**
	 * Выводи список актуальных баннеров
	 *
	 * @param int $now время в секундах с начало эпохи
	 * @return array
	 */
	public function GetNowBanner($now = null,$group = null){
		if(empty($now))$now = time();
		//Debug("Select * from $this->TableName where GroupName = '$group' and (StartDate < $now or StopDate < $now)");
		$result = $this->querys("Select * from $this->TableName where GroupName = '$group' and (StartDate < $now or StopDate < $now)");
		//Debug($result);
		//return $result;
		if(count($result) <= 0)
			return null;
		else
			return $result;
	}

	/**
	 * Выдает инфу по пореденному банеру
	 *
	 * @param int $BannerId
	 * @return array
	 */
	public function GetBanner($BannerId = null){
		$res = $this->select(array("BannerId"=>intval($BannerId)));
		return $res[0];
	}

	public function SetShow($BannerId = null){
		$BannerId = intval($BannerId);
		$this->query("update $this->TableName set Shows = Shows + 1 where BannerId = $BannerId limit 1");
	}

	public function SetClick($BannerId = null){
			$BannerId = intval($BannerId);
			$this->query("update $this->TableName set Clicks = Clicks + 1 where BannerId = $BannerId limit 1");
	}

}
?>