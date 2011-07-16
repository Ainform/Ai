<?php

/**
 * DAL_CharactersDb class
 * Класс для работы с новостями в БД
 * 
 * @author Frame
 * @version 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_CharactOfGoodDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "CharactOfGood";

	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
		"GoodId" => "int",
		"Default" => "string",
		"ChId" => "int",
		"Description" => "string",
		"GoodArticle" => "string"		
		);
	}

	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("GoodId", "ChId");
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
	 * Возвращает характеристики какого-то конкретного товара
	 *
	 * @param int $GoodId идентификатор товара
	 * @return array
	 */
	public function GetCharactersOfGood($goodId)
	{
		//$result = $this->select(array("GoodId" => $GoodId));
		
		$result = $this->db->ExecuteReader("SELECT cg.GoodArticle as Article, g.Price, g.GoodId, g.SectionId, g.Description, cg.Default, g.Title as Text, ch.Title, ch.photo, ch.CharactersId as CharactId
										 FROM CharactOfGood cg
										 INNER JOIN Characteristics ch ON ch.charactersId = cg.chid
										 INNER JOIN Goods g ON g.code = cg.GoodArticle
										 WHERE cg.goodid = ".HtmlEncode($goodId)." ORDER BY ch.Order ASC, g.Price ASC");
		
			
		
		if (count($result) === 0)
			return null;
		
		return $result;
	}
	
	public function GetCharactersOfGoodByCode($code, $goodId, $chId)
	{
		$result = $this->db->ExecuteReader("SELECT cg.GoodArticle as Article, g.Price, g.GoodId, g.SectionId, g.Description, cg.Default, g.Title as Text, ch.Title, ch.photo, ch.CharactersId as CharactId
										 FROM CharactOfGood cg
										 INNER JOIN Characteristics ch ON ch.charactersId = cg.chid
										 INNER JOIN Goods g ON g.code = cg.GoodArticle
										 WHERE cg.GoodArticle = '".HtmlEncode($code)."' AND cg.GoodId = ".$goodId." AND cg.chid =".$chId." ORDER BY ch.Order ASC, g.Price ASC");
		
		
										 	
		
		if (count($result) === 0)
			return null;
		
		return $result[0];
	}

	/**
	 * Удаляетхарактеристики определенного товара
	 *
	 * @param int $GoodId идентификатор товара
	 */
	public function DeleteCharacters($GoodId)
	{
		$this->delete(array("GoodId" => $GoodId));
	}

	/**
	 * Добавляет новость
	 *
	 * @param row $CharactersRow данные по новости
	 */
	public function AddCharacters($CharactersRow)
	{
		$this->insert($CharactersRow);

		return $this->db->GetLastId();
	}

	/**
	 * Обновляет новость
	 *
	 * @param row $CharactersRow данные по новости
	 */
	public function UpdateCharacters($CharactersRow)
	{
		$this->update($CharactersRow);
	}
}