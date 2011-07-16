<?php

/**
 * DAL_TextPagesDb class
 * Класс для работы с текстовыми страницами в БД
 *
 * @author Frame
 * @version TextPagesDb.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_TextPagesDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "textpages";

	/**
	 * Возвращает текстовую страницу
	 *
	 * @param int $moduleId идентификатор модуля
	 * @return array
	 */
	public function GetPage($moduleId)
	{
		$result = $this->select(array("ModuleId" => $moduleId));
		return $result[0];
	}

	/**
		Добавляет новую страницу

		@param string $text Текстовые данные страницы
	*/
	public function AddPage($moduleId, $text = "")
	{
		$this->insert(array("ModuleId" => $moduleId, "Text" => $text));
	}

	/**
		Удаляет страницу

		@param int $moduleId Идентификатор модуля
	*/
	public function DeletePage($moduleId)
	{
		$this->delete(array("ModuleId" => $moduleId));
	}

	/**
	 * Обновляет данные текстовой страницы
	 *
	 * @param row $pageRow данные страницы
	 */
	public function UpdateTextPage($pageRow)
	{
		$this->update($pageRow);
	}

	/**
	 * Возвращает все текстовые страницы
	 *
	 * @return array
	 */
	public function GetPages()
	{
		return $this->select();
	}
}
?>