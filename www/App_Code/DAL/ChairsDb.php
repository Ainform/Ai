<?php
/**
 * DAL_ChairsDb class
 * Класс для работы с кафедрами в БД
 * 
 * @author Informix
 * @version 0.q
 * @copyright (c) by Informix
 */

class DAL_ChairsDb extends DAL_BaseDb{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Chairs";
	
	/**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"ChairId" => "int",
				"ModuleId" => "int",
				"isFaculty" => "int",
				"Title" => "string",
				"Anons" => "string",
				"Body" => "string",
		);
	}
	
	/**
	Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("ChairId");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("ChairId");
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
	public static function GetImageFolder($ChairtId = null)
	{
		$folder = "chair/";
		
		if (isset($ChairId))
			$folder .= $ChairId."/";
			
		return $folder;
	}
	
	/**
	 * Возвращает список 
	 * @param int $moduleId Индификатор модуля
	 * @param int $page номер страницы для отображения
	 * @param int $recordsOnPage количество записей на страницу
	 * 
	 * @return array
	 * */
	public function GetChairPage($moduleId, $page, $recordsOnPage){
		return $this->selectPage(array("ModuleId" => $moduleId), 
								"ChairId", 
								true, 
								$page, 
								$recordsOnPage);
	}
	
	
	/**
	 * Возвращает html для пейджинга на основе данных
	 * @param int $moduleId ИД Модуля 
	 * @param int $page номер страницы
	 * @param int $recordsOnPage количество записей на страницу
	 * @return string
	 * */
	public function GetPager($moduleId, $page, $recordsOnPage){
		//Debug($moduleId);
		
		$allNews = $this->GetCountChair($moduleId); // количество всех новостей
		$p = ceil($allNews/$recordsOnPage); // количество страниц
		$pager = "<div class='pager'>";
		if($p>1){// если больше одной страницы
			for($i = 0;$i < $p;$i++){
				if($page == $i){// выделение страниц
					$pager .= "<span>".($i+1)."</span>&nbsp;";
				}else{
					//$url = $this->GetVirtualPath();
					$pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
				}
			}
		}
		return $pager."</div>";
	}
	
	/**
	Возвращает общее количество записей для текущего модуля
	@param int $moduleId идентификатор модуля
	*/
	public function GetCountChair($moduleId)
	{
		return $this->selectCount(array("ModuleId" => $moduleId));
	}
	
	/**
	 * Обновляет Запись о кафедре
	 *
	 * @param row $saveRow данные по кафедре
	 */
	public function UpdateChair($saveRow)
	{
		$this->update($saveRow);
	}
	
	/**
	 * Добавляет Запись о кафедре
	 *
	 * @param row $SaveRow данные по кафедре
	 */
	public function AddChair($SaveRow)
	{
		$this->insert($SaveRow);
		return $this->db->GetLastId();
	}
	
	/**
	 * Удаляет Запись о кафедре
	 *
	 * @param int $LeaderId идентификатор кафедре
	 */
	public function DeleteChair($ChairId)
	{
		$Chair = $this->GetChairInfo($ChairId);
		//Debug($Faculty);
		$imageUtility = new Utility_ImageUtility();
		$this->delete(array("ChairId" => $ChairId));

		// удаляем файлы из текста
		$imageUtility->SetDirectory('Chair'.$ChairId);
		$imageUtility->DeleteFiles($Chair[0]['Body']);
		
		$imagesDb = new DAL_ImagesDb();
			$imagesDb->DeleteFolder(self::GetImageFolder($ChairId));
		unset($imagesDb);
	}

	/**
	 * Возвращает Информацию о Кафедре
	 *
	 * @param int $LeaderId ID Кафедре
	 */
	public function GetChairInfo($ChairId = null)
	{
		return $this->select(array("ChairId" => $ChairId));
	}
	
	
	
	public function GetAllChairs($moduleId = null){
		if(!$moduleId){
			return $this->select(array("ModuleId"=>$moduleId));
		}else{
			return $this->select();
		}
		
	}
	
	public function GetFacultyChairs($id){
		return $this->select(array("isFaculty"=>$id));
	}
	
	
	public function GetUrlFrontSite(){
		//$module = "Chairs";
		//$pageModule = $this->query("select * from PageModules where ModuleType = '$module'");
		//$u = $this->query("select * from Pages where PageId = '".$pageModule[0]["PageId"]."';");
		//Debug($u);
		return "/faculty/chairs/";
		//return "/".$u[0]["Alias"]."/";
	}
	
	
}
?>