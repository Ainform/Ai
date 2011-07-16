<?php

/**
 * DAL_ServicesDb class
 * Класс для работы с услугами в БД
 * 
 * @author Frame
 * @version ServicesDb.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_ServicesDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Services";
	
	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array("servicesId"  => "int",
  					 "Caption" 	   => "string",
					 "description" => "string",
					 "price"       => "float",
					 "ModuleId"    => "int",
					 "Anons"	   => "string"
				);
	}
	
	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("servicesId");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array('servicesId');
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
	 * Возвращает текстовую страницу
	 *
	 * @param int $moduleId идентификатор услуги
	 * @return array
	 */

	public function GetServices($ServicesId = null)
	{
		$result = $this->select(array("servicesId" => $ServicesId));
		
		return $result != null ? $result[0] : null;
	}
	
	/**
	 *	Возвращает папку для картинок новости
	 */
	public static function GetImageFolder($ServicesId = null)
	{
		$folder = "Services";
		
		if (isset($ServicesId))
			$folder .= $ServicesId;
			
		return $folder;
	}

	
	/**
		Добавляет новую услугу
		
		@param int $moduleId    идентификатор вопроса/ответа
		@param int $Caption		название услуги
		@param int $Description описание
		@param int $Price       стоимость F:("10.2")
	*/
	public function AddServices($ServicesRow)
	{
		$this->insert($ServicesRow);
	}
	

	/**
		Удаляет услугу
		
		@param int $ServicesId Идентификатор услуги
	*/
	public function DeleteServices($ServicesId)
	{	
		$Service = $this->GetServices($ServicesId);
		$imageUtility = new Utility_ImageUtility();
		
		$this->delete(array("servicesId" => $ServicesId));

		// удаляем файлы из текста
		$imageUtility->SetDirectory("Service".$ServicesId);
		$imageUtility->DeleteFiles($Service['description']);
		
		$imagesDb = new DAL_ImagesDb();
			$imagesDb->DeleteFolder(self::GetImageFolder($ServicesId));
		unset($imagesDb);		
	}
	
	public function DeleteAllService($ModuleId)
	{
		$Services = $this->GetAllServices($ModuleId);
		
		foreach ($Services as $row)
			$this->DeleteServices($row["servicesId"]);
		
//		$this->delete(array("ModuleId" => $ModuleId));	
	}

	/**
	 * Обновляет данные записи услуги
	 *
	 * @param row $ServicesId идентификатор услуги
	 */
	public function UpdateServices($Services)
	{
	 	//print_r($Services);
		$this->update($Services);
	}

	/**
	 * Возвращает все текстовые страницы
	 *
	 * @return array
	 */
	public function GetAllServices($moduleId = null)
	{
			return $this->select(array("ModuleId" => $moduleId));

	}
}
?>