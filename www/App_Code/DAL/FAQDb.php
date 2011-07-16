<?php

/**
 * DAL_TextPagesDb class
 * Класс для работы с текстовыми страницами в БД
 * 
 * @author Frame
 * @version TextPagesDb.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_FAQDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "FAQ";
	
	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array("QuestionId" => "int",
				"ModuleId" => "int",
				"Question" => "string",
				"Date" => "string",
				"Answer" => "string"
				);
	}
	
	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("QuestionId");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array();
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
	 * @param int $moduleId идентификатор вопроса
	 * @return array
	 */

	public function GetQuestion($QuestionId = null)
	{
		$result = $this->select(array("QuestionId" => $QuestionId));
		
		return $result != null ? $result[0] : null;
	}
	
	/**
		Добавляет новый вопрос
		
		@param int $moduleId    идентификатор вопроса/ответа
		@param int $Question текст вопроса
		@param int $Answer   текст ответа
				
	*/
	public function AddFAQ($moduleId, $Question = "" , $Answer = "", $Date = "")
	{
		$this->insert(array("QuestionId" => 0,
							"ModuleId" => $moduleId, 
							"Question" => $Question , 
							"Date" => $Date ,
							"Answer" => $Answer));
		echo "begin"; 
	//	$this->GenerateSiteMap($moduleId);
	}
	

	/**
		Удаляет вопрос
		
		@param int $moduleId Идентификатор вопроса
	*/
	public function DeleteFAQ($QuestionId)
	{
		$this->delete(array("QuestionId" => $QuestionId));
	}

	/**
	 * Обновляет данные записи вопроса
	 *
	 * @param row $FAQRow данные страницы
	 */
	public function UpdateFAQ($QuestionId)
	{
		$this->update($QuestionId);
	}

	/**
	 * Возвращает все текстовые страницы
	 *
	 * @return array
	 */
	public function GetFAQ_all($moduleId = null)
	{
			return $this->select(array("ModuleId" => $moduleId));

	}
}
?>