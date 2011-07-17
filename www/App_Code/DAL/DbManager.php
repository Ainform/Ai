<?php

/**
 * DbManager class
 * Класс для работы с БД MySQL
 *
 * @author Frame
 * @version DbManager.class.php, v 1.1.1
 * @copyright (c) by VisualDesign
 */
class DAL_DbManager {

	/**
	 * Содержит экземпляр данного класса
	 *
	 * @var DAL_DbManager
	 */
	protected static $instance;

	/**
	 * Соединение с БД
	 *
	 * @var resource
	 */
	protected $connection = 0;

	/**
	 * Данные для подключения к БД
	 *
	 * @var string
	 */
	private $dbName;
	private $dbHost;
	private $dbUserName;
	private $dbUserPass;

	/**
	 * Конструктор, задает параметры
	 *
	 * @param string $dbHost сервер БД
	 * @param string $dbName имя БД
	 * @param string $dbUserName логин владельца БД
	 * @param string $dbUserPass пароль владельца БД
	 */
	function __construct($dbHost, $dbName, $dbUserName, $dbUserPass) {
		$this->dbHost = $dbHost;
		$this->dbName = $dbName;
		$this->dbUserName = $dbUserName;
		$this->dbUserPass = $dbUserPass;

		self::$instance = $this;
	}

	/**
	 * Возвращает экземпляр класса
	 *
	 * @return DAL_DbManager
	 */
	public static function GetObject() {
		return self::$instance;
	}

	/**
	 * Открывает соединение с базой данный
	 *
	 * @param string $dbHost
	 * @return void
	 */
	function OpenConnection() {
		if (is_resource($this->connection))
			return;

		// устанавливаем связь с сервером MySQL
		$this->connection = mysql_connect($this->dbHost, $this->dbUserName, $this->dbUserPass);

		if (!is_resource($this->connection))
			throw new DbException('Не удалось подключиться к БД');

		// указываем БД
		if (!mysql_select_db($this->dbName, $this->connection))
			throw new DbException('Не удалось выбрать БД');

		// указываем кодировку, в которой хранятся записи
		$this->ExecuteQuery("SET NAMES `utf8`");

		//$this->ExecuteQuery("set character_set_client='utf8'");
		//$this->ExecuteQuery("set character_set_results='utf8'");
		//$this->ExecuteQuery("set collation_connection='utf8_general_ci'");
	}

	/**
	 * Возвращает идентификатор только что вставленной записи
	 *
	 * @return int
	 */
	function GetLastId() {
		return mysql_insert_id($this->connection);
	}

	/**
	 * Выполняет запрос к БД
	 *
	 * @param string $query запрос
	 * @return array
	 */
	function ExecuteQuery($query) {
		//wtf($query."<br />\n",false);
		// выполняем запрос к БД
		$result = mysql_query($query, $this->connection);
		// если неправильный запрос, то отправляем сообщение

		if (!is_resource($result) && !$result) {
			$message = 'Неверный запрос "' . $query . '" (' . mysql_error($this->connection) . ')';
			Helpers_LogHelper::AddLogEntry($message);
			return $message;
		}

		return $result;
	}

	/**
	 * Выволняет запрос и возвращает массив значений
	 *
	 * @param string $query запрос
	 * @return array
	 */
	function ExecuteReader($query) {
//echo $query;
		if (!is_resource($this->connection))
			Helpers_LogHelper::AddLogEntry('Попытка выполнения запроса без подключения к БД!');

//		$frontendOptions = array(
//			'lifetime' => 3600,
//			'automatic_serialization' => true
//		);
//
//		$backendOptions = array(
//			'cache_dir' => $_SERVER['DOCUMENT_ROOT'] . '/upload/cache' // директория, в которой размещаются файлы кэша
//		);
//
//// получение объекта Zend_Cache_Core
//		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
//
//		if (!$reader = $cache->load(md5($query))) {

			// выполняем запрос и получаем результат выполнения запроса
			$result = $this->ExecuteQuery($query);

			$reader = array();

			if (!$result)
				return $reader;

			// перебираем записи если более одной и возращаем массив
			if (is_resource($result) && mysql_num_rows($result) > 0)
				while ($row = mysql_fetch_assoc($result))
					$reader[] = $row;
//
//			if (count($reader) > 100) {
//				$cache->save($reader, md5($query));
//			}

			return $reader;
//		} else {
//
//			return $reader;
//		}
	}

	/**
	 * Выволняет команду и возвращает одну строку
	 *
	 * @param string $query запрос
	 * @return array
	 */
	function ExecuteScalar($query) {
		//wtf($query,false);
		// выполняем запрос и получаем результат выполнения запроса
		$result = $this->ExecuteQuery($query);
//Debug($result);
		$scalar = array();

		// если вернулась одна запись
		if (is_resource($result) && mysql_num_rows($result) == 1)
			$scalar = mysql_fetch_assoc($result);

		return $scalar;
	}

	/**
	 * Возвращает количество записей в таблице
	 *
	 * @param $table string имя таблицы
	 * @param $where string запрос на выборку
	 * @return int
	 */
	function GetItemsCount($table, $where) {

		$result = $this->ExecuteScalar("SELECT COUNT(*) FROM $table $where");

		return $result["COUNT(*)"];
	}

	/**
	 * Возвращает одну страницу записей из БД
	 *
	 * @param $page int номер страницы
	 * @param $table string имя таблицы
	 * @param $columns string поля для выборки
	 * @param $where string запрос на выборку
	 * @param $recordsInPage int количество записей на странице
	 * @param $idColumn string название поля с идентификаторами записей
	 * @param $orderColumn string название поля по которому необходимо сортировать
	 * @param $count int общее количество записей в БД
	 * @return array
	 */
	function GetPage($page, $table, $columns, $where, $recordsInPage, $idColumn, $orderColumn, &$count) {

		Utility_SafetyUtility::SafeInt($page);
		Utility_SafetyUtility::SafeInt($recordsInPage);

		$page--;

		// если столбцы не заданы - возвращаем все
		if (strlen(trim($columns)) == 0)
			$columns = "*";

		// запрос на выборку
		$where = (strlen(trim($where)) == 0) ? '' : 'WHERE ' . $where;

		// по умолчанию сортируем по индексу
		if (strlen(trim($orderColumn)) == 0)
			$orderColumn = $idColumn . " DESC";

		// стартовая запись
		$startRow = $recordsInPage * $page;

		// получаем результат запроса
		$rows = $this->ExecuteReader("SELECT $columns FROM $table $where ORDER BY $orderColumn LIMIT $startRow, $recordsInPage");

		// получаем общее количество записей - нужно для определения количество страниц
		$count = (is_array($rows) && count($rows) > 0) ? $this->GetItemsCount($table, $where) : 0;

		return $rows;
	}

	/**
	 * Возвращает список полей таблицы
	 *
	 * @param string $tableName
	 * @return array
	 */
	function GetTableInfo($tableName) {
		// получаем идентификатор результата
		$result = mysql_list_fields($this->dbName, $tableName, $this->connection);

		$fieldsArray = array();

		if (is_resource($result) && mysql_num_rows($result) > 0) {
			// запрашиваем идентификатор данных о полях таблицы
			$fields = mysql_num_fields($result);

			// создаем массив полей таблицы
			for ($i = 0; $i < $fields; $i++)
				$fieldsArray[] = mysql_field_name($result, $i);
		}

		return $fieldsArray;
	}

	/**
	  Обезопасивание строки
	 */
	function Escape($string) {
		return mysql_real_escape_string($string, $this->connection);
	}

	/**
	 * Закрывает соединение с БД
	 */
	function CloseConnection() {
		// если соединение открыто, т.е. connection is_resource
		if (is_resource($this->connection))
			mysql_close($this->connection);
	}

	function __destruct() {
		$this->CloseConnection();
	}

}

?>
