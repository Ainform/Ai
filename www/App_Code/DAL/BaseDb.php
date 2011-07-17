<?php

/**
 * DAL_BaseDb class
 * Базовый класс для всех классов, работающих с БД
 *
 * @author Frame
 * @version BaseDb.class.php, v 1.1.1
 * @copyright (c) by VisualDesign
 */
//include_once '/usr/home/web/www.riss.ru/www/new2/App_Code/Utility/SafetyUtility.php';

class DAL_BaseDb
{

	/**
	 * Менеджер БД
	 *
	 * @var DAL_DbManager
	 */
	protected $db;
	protected static $staticDb;
	protected $TableName = null;

	/**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
	 * @return array
	 */
	protected function getStructure()
	{

		$struct = array();

		$fields = $this->query("SHOW COLUMNS FROM  $this->TableName");
		foreach ($fields as $field) {
			$struct[$field['Field']] = $field['Type'];
		}
		if (count($struct) > 0) {
			return $struct;
		} else {
			trigger_error("Структура таблицы не определена");
		}

	}

	/**
	Возвращает ключи таблицы
	 */
	protected function getKeys()
	{
		$fields = array();
		$structure = array();
		$fields = $this->query("SHOW COLUMNS FROM  $this->TableName");
		foreach ($fields as $field) {
			if ($field['Key'] == "PRI") {
				$structure[] = $field['Field'];
			}
		}
		if (count($structure) > 0) {
			return $structure;
		} else {
			trigger_error("Ключи таблицы не определены");
		}
	}

	/**
	Возвращает индексы таблицы
	 */
	protected function getIndexes()
	{
		$fields = array();
		$structure = array();
		$fields = $this->query("SHOW COLUMNS FROM  $this->TableName");
		foreach ($fields as $field) {
			if ($field['Extra'] == "auto_increment") {
				$structure[] = $field['Field'];
			}
		}
		if (count($structure) > 0) {
			return $structure;
		} else {
			trigger_error("Индексы таблицы не определены");
		}
	}

	function __construct()
	{
		// если уже есть объект менеджера БД
		if (is_object($this->db) && $this->db instanceof DAL_DbManager)
			return;

		if (is_object(self::$staticDb) && self::$staticDb instanceof DAL_DbManager) {
			$this->db = self::$staticDb;
			return;
		}

		// получаем менеджер БД
		$this->db = DAL_DbManager::GetObject();

		// открываем соединение
		$this->db->OpenConnection();

		self::$staticDb = $this->db;
	}

	/**
	Осуществляет проверку данных на соответствие типу
	@param string $name название колонки
	@param mixed $value значение колонки
	 */
	private function checkData($name, &$value)
	{
		$structure = $this->getStructure();

		if (!isset($structure[$name]))
			trigger_error("Колонка с именем $name не существует в таблице");

		if (stripos($structure[$name], "int")) {
			Utility_SafetyUtility::SafeInt($value);
		} elseif (stripos($structure[$name], "float")) {
			Utility_SafetyUtility::SafeDouble($value);
		} elseif (stripos($structure[$name], "double")) {
			Utility_SafetyUtility::SafeDouble($value);
		} elseif (stripos($structure[$name], "real")) {
			Utility_SafetyUtility::SafeDouble($value);
		} else {
			Utility_SafetyUtility::SafeString($value);
		}
	}

	protected function selectIn($array = array(), $order = null, $orderInv = false, $limit = null)
	{
		if (!isset($this->TableName))
			trigger_error("Имя таблицы не указано");

		$structure = $this->getStructure();

		$query = "SELECT * FROM `" . $this->TableName . "`";
		if (!empty($array)) {
			$query .= " WHERE 1=1";
			foreach ($array as $key => $data) {
				$query .= " AND $key IN ('" . implode("','", $data) . "')";
			}
		}
		//Debug($query);


		if (isset($order) && isset($structure[$order]))
			$query .= " ORDER BY `" . $order . "` " . ($orderInv ? "DESC" : "ASC");

		if (isset($limit))
			$query .= " LIMIT 0," . intval($limit);

		return $this->db->ExecuteReader($query);
	}

	/**
	Делает выборку из таблицы

	@param array $where Массив фильтров выборки
	@param string $order Название колонки по которой будут сортироваться записи
	@param bool $orderInv Направление сорировки — в обратном порядке
	 */
	protected function select($where = null, $order = null, $orderInv = false, $limit = null)
	{
		//Debug($where,false);
		if (!isset($this->TableName))
			trigger_error("Имя таблицы не указано");

		$structure = $this->getStructure();

		$query = "SELECT * FROM `" . $this->TableName . "`";

		if (isset($where)) {
			$query .= " WHERE 1=1";

			if (!is_array($where))
				trigger_error("Некорректные параметры");

			foreach ($where as $key => $type) {
				if (!isset($where[$key]))
					continue;

				$this->checkData($key, $where[$key]);

				$query .= " AND `" . $key . "` = '" . $where[$key] . "'";
			}
		}

		if (isset($order) && isset($structure[$order]))
			$query .= " ORDER BY `" . $order . "` " . ($orderInv ? "DESC" : "ASC");

		if (isset($limit))
			$query .= " LIMIT 0," . intval($limit);


		//Debug($query,false);
		return $this->db->ExecuteReader($query);
	}

	/**
	Выберает первую из записей
	 */
	protected function selectFirst($where = null, $order = null, $orderInv = false)
	{
		$rows = $this->select($where, $order, $orderInv);

		if (count($rows) > 0)
			return $rows[0];
		else
			return null;
	}

	/**
	 *
	 * выполнение произвольного запроса к бд
	 * */
	public function query($query = null)
	{
		//Debug($query,false);
		return $this->db->ExecuteReader($query);
	}

	/**
	 * Делает постраничную выборку из таблицы
	 * @param $where
	 * @param $order
	 * @param string $order_dir
	 * @param $page
	 * @param $recordsInPage
	 * @return #M#P#C\DAL_BaseDb.db.ExecuteReader|?
	 */
	protected function selectPage($where, $order, $order_dir = "DESC", $page = 1, $recordsInPage)
	{
		if (!isset($this->TableName))
			trigger_error("Имя таблицы не указано");

		$structure = $this->getStructure();

		$query = "SELECT * FROM `" . $this->TableName . "`";

		if (isset($where)) {
			$query .= " WHERE 1=1";

			if (!is_array($where))
				trigger_error("Некорректные параметры");

			foreach ($where as $key => $type) {
				if (!isset($where[$key]))
					continue;

				$this->checkData($key, $where[$key]);

				$query .= " AND `" . $key . "` = '" . $where[$key] . "'";
			}
		}

		if (isset($order) && isset($structure[$order]))
			$query .= " ORDER BY `" . $order . "` " . $order_dir;

		$startRow = $recordsInPage * ($page - 1);

		$query .= " LIMIT " . intval($startRow) . ',' . intval($recordsInPage);
		return $this->db->ExecuteReader($query);
	}

	/**
	Возвращает количество записей, удовлетворяющих выражению

	@param array $where ассоциативный массив фильтра данных
	 */
	protected function selectCount($where = array())
	{
		$query = "SELECT COUNT(*) FROM `" . $this->TableName . "`";
		if (isset($where)) {
			$query .= " WHERE 1=1";

			if (!is_array($where))
				trigger_error("Некорректные параметры");

			foreach ($where as $key => $type) {
				if (!isset($where[$key]))
					continue;

				$this->checkData($key, $where[$key]);

				$query .= " AND `" . $key . "` = '" . $where[$key] . "'";
			}
		}

		$row = $this->db->ExecuteScalar($query);
		return @$row['COUNT(*)'];
	}

	/**
	Обновляет запись в таблице

	@param array $newRow новая запись
	 */
	protected function update($newRow)
	{

		// определяем ключи
		$keys = $this->getKeys();

		// определяем индексы
		$indexes = $this->getIndexes();
		$where = array();
		$whereStr = "1 = 1"; // строка для идентификации записи по ключу (PK = PK_VALUE AND PK2 = PK2_VALUE)

		foreach ($keys as $key) {
			if (!isset($newRow[$key]))
				trigger_error("Обновление невозможно, т.к. не указан обязательный ключ $key");

			$where[$key] = $newRow[$key];
			$whereStr .= " AND `$key` = '$newRow[$key]'";
		}

		// получаем старую запись
		$oldRow = $this->select($where);
		if (count($oldRow) != 1)
			trigger_error("Обновление невозможно, т.к. запись не существует в таблице");

		$oldRow = $oldRow[0];

		// генерируем запрос в базу данных
		$query = "";

		foreach ($oldRow as $key => $value) {
			if (isset($newRow[$key]) && $newRow[$key] != $value) {
				if (!empty($query))
					$query .= ',';

				if ($newRow[$key] != "NULL")
					$query .= "`" . $key . "`='" . $this->db->Escape($newRow[$key]) . "'";
				else
					$query .= "`" . $key . "`=NULL";
			}
		}
		if (empty($query))
			return;

		return $this->db->ExecuteScalar("UPDATE `" . $this->TableName . "` SET $query WHERE $whereStr");
	}

	/**
	Осуществляет вставку строки в таблицу

	@param array $row Строка таблицы
	 */
	function insert($row)
	{
		if (!isset($this->TableName))
			trigger_error("Имя таблицы не указано");
		//Debug($row);
		// определяем ключи
		$keys_old = $this->getKeys();

		// определяем индексы
		$indexes_old = $this->getIndexes();
		$keys = array();
		foreach ($keys_old as $key)
			$keys[$key] = 0;

		$indexes = array();
		foreach ($indexes_old as $key)
			$indexes[$key] = 0;

		$columns = "";
		$values = "";
		foreach ($row as $key => $value) {
			if (!isset($indexes[$key])) {
				$this->checkData($key, $value);

				if (isset($keys[$key]))
					$keys[$key] = 1;
			}

			if (!empty($columns))
				$columns .= ",";

			$columns .= "`" . $key . "`";

			if (!empty($values))
				$values .= ",";

			if (isset($indexes[$key]) || $value === "NULL") {
				$values .= "NULL";
			} else {
				$values .= "'" . $value . "'";
			}
		}

		//Debug($indexes);
		foreach ($keys as $key => $value)
			if ($value == 0 && !isset($indexes[$key]))
				trigger_error("Не определен ключ $key");

		// генерируем запрос в базу данных
		$query = "INSERT INTO `" . $this->TableName . "` ($columns) VALUES ($values);";

		//Debug($query."\n\r\n\r\n\r\n\r");
		$this->db->ExecuteScalar($query);
	}

	/**
	Удаляет запись из таблицы
	@param array $where Фильтр для записей
	 */
	protected function delete($where)
	{
		if (!isset($this->TableName))
			trigger_error("Имя таблицы не указано");

		$query = "DELETE FROM `" . $this->TableName . "`";

		if (isset($where)) {
			$query .= " WHERE 1=1";

			if (!is_array($where))
				trigger_error("Некорректные параметры");

			foreach ($where as $key => $type) {
				if (!isset($where[$key]))
					continue;

				$this->checkData($key, $where[$key]);

				$query .= " AND `" . $key . "` = '" . $where[$key] . "'";
			}
		}
		return $this->db->ExecuteScalar($query);
	}

	/**
	 * Деструктор, закрывает соединение с БД
	 */
	function __destruct()
	{
		//$this->db->CloseConnection();
	}

	/**
	 * Возвращает экземпляр класса OrderHelper
	 *
	 * @param $tableName
	 * @param $idIndex
	 * @param $parentIdIndex
	 * @param string $thirdindex
	 * @return Helpers_OrderHelper
	 */
	public function Order($tableName, $idIndex, $parentIdIndex, $thirdindex = null)
	{
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo($tableName, $idIndex, $parentIdIndex, $thirdindex);

		return $orderHelper;
	}

}
