<?php

/**
 * DAL_UsersDb class
 * Класс для работы с пользователями в БД
 *
 * @author Informix
 * @version 1.0
 */

class DAL_AuthDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "Users";

	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"UserId" => "int",
				"ModuleId" => "int",
				"FIO" => "string",
				"Phone" => "string",
				"Email" => "string",
				"Password" => "string",
                                "Activate"=>"int"
				);
	}

	/**
		Возвращает первичные ключи таблицы

		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("UserId");
	}

	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("UserId");
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
	 * выдает всех пользователей
	 *
	 * @return array
	 */
	public function GetAllUsers(){
		return $this->select();
	}

	/**
	 * Выдает информацию о конкретном пользователе
	 *
	 * @param unknown_type $UserId
	 * @return unknown
	 */
	public function GetUser($UserId = null){
		return $this->select(array("UserId"=>$UserId));
	}

        /**
	 * Авторизуем пользователя
	 *
	 * @param unknown_type $UserId
	 * @return unknown
	 */
	public function CheckUser($pass,$name){
		return $this->selectFirst(array("Password"=>md5($pass),"Name"=>$name));
	}

}

?>