<?php

/**
 * DAL_DbBackup class
 * Класс для работы с резервным копированием БД MySQL
 *
 * @author Frame
 * @version DbBackup.class.php, v 1.1.1
 * @copyright (c) by VisualDesign
 */

class DAL_DbBackup extends DAL_BaseDb
{
	/**
	* Дамп БД
	* @var string
	*/
	protected $dump;

	/**
	* Номер таблицы
	* @var int
	*/
	protected $tableId = 0;

	/**
	* Номер запроса
	* @var int
	*/
	protected $insertId = 0;

	/**
	* Указатель перевода строки
	* @var string
	*/
	protected $newLine = "\n";

	/**
	* Индекс массив, под которым лежит название таблицы
	* @var string
 	*/
	protected $index;

	/**
	 * Констуктор, инициализирует соединение
	 *
	 */
	function __construct()
	{
		parent::__construct();

		$this->index = 'Tables_in_'.DbName;
	}

	/**
	 * Создает дамп БД
	 *
	 * @return void
	 */
	function CreateMySQLDump()
	{
		// получаем список таблиц
		$tables = $this->db->ExecuteReader("SHOW TABLES");

		// определяем количество таблиц
		$countTables = count($tables);

		// если количество таблиц не равно 0
		if($countTables != 0)
		{
			$this->dump = "<?php";
			$this->dump .= $this->newLine.$this->newLine;

			// в цикле получаем описание таблиц и содержимое
			for($i = 0; $i < $countTables; $i++)
			{
				$table = $tables[$i][$this->index];
				$this->dump .= $this->GetTableDefinition($table);
				$this->dump .= $this->GetTableContent($table);
			}
			$this->dump .= "?>";
		}
	}

	/**
	* Создает строку-переменную с запросом
	* @param string $sql_insert
	* @return string
	*/
	function MyHandler($sql_insert)
	{
		$return = '$data['.++$this->insertId.'] = "'.$sql_insert.'";';
		$return .= $this->newLine;

		return $return;
	}

	/**
	* Создает описание данных таблицы
	* @param string $table
	* @return string
	*/
	function GetTableContent($table)
	{
		$result = mysql_query("SELECT * FROM `$table`");
		$i = 0;
		$content = '';
		while($row = mysql_fetch_row($result))
		{
			$schema_insert = "INSERT INTO `$table` VALUES (";

			for($j=0; $j<mysql_num_fields($result);$j++)
			{
				if(!isset($row[$j]))
					$schema_insert .= " NULL,";
				elseif($row[$j] != "")
					$schema_insert .= " '".addslashes($row[$j])."',";
				else
					$schema_insert .= " '',";
			}

			$schema_insert = preg_replace("/,$/", "", $schema_insert);

			$schema_insert .= ")";

			$content .= $this->MyHandler(trim($schema_insert));

			$i++;
		}

		return $content;
	}

	/**
	* Создает описание таблицы в виде переменной со строкой
	* @param string $table
	* @return string
	*/
	function GetTableDefinition($table)
	{
		$schema_create = null;

		$schema_create .= "CREATE TABLE `$table` (";

		$result = mysql_query("SHOW FIELDS FROM `$table`");

		while($row = mysql_fetch_array($result))
		{
			$schema_create .= "   `$row[Field]` $row[Type]";

			if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
				$schema_create .= " DEFAULT '$row[Default]'";

			if($row["Null"] != "YES")
				$schema_create .= " NOT NULL";

			if($row["Extra"] != "")
				$schema_create .= " $row[Extra]";

			$schema_create .= ", ";
		}

		$schema_create = preg_replace("/, $/", "", $schema_create);

		$result = mysql_query("SHOW KEYS FROM `$table`");

		while($row = mysql_fetch_array($result))
		{
			$kname=$row['Key_name'];

			if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
				$kname="UNIQUE|$kname";
			if(!isset($index[$kname]))
				$index[$kname] = array();

			$index[$kname][] = $row['Column_name'];
		}
		if(isset($index) && is_array($index))
		{
			while(list($x, $columns) = each($index))
			{
				$schema_create .= ", ";
				if($x == "PRIMARY")
					$schema_create .= "   PRIMARY KEY (`".implode($columns, '`, `')."`)";
				elseif (substr($x,0,6) == "UNIQUE")
					$schema_create .= "   UNIQUE ".substr($x,7)." (`".implode($columns, '`, `')."`)";
				else
					$schema_create .= "   KEY `$x` (`".implode($columns, '`, `')."`)";
			}
		}

		$schema_create .= ")";

		$this->tableId++;

		$return = '$table['.$this->tableId.'] = "'.(stripslashes($schema_create)).'";';

		$return .= $this->newLine.$this->newLine;

		return $return;
	}


	/**
	* Возвращает созданный дамп
	* @return string
	*/
	function GetDump()
	{
		return $this->dump;
	}

	/**
	* Удаляет содержимое БД
	* @return void
	*/
	function DropTables()
	{
		// получаем список таблиц
		$tables = $this->db->ExecuteReader("SHOW TABLES");

		// определяем количество таблиц
		$countTables = count($tables);

		if ($countTables != 0)
			for($i = 0; $i < $countTables; $i++)
			{
				// удаляем таблицу
				$this->db->ExecuteQuery("DROP TABLE `".$tables[$i][$this->index]."`");
			}
	}

	/**
	 * Восстанавливает данные БД
	 *
	 * @param string $list список запросов для восстановления
	 */
	public function Restore($list)
	{
		if (!is_array($list) || 0 == count($list))
			return;

		foreach ($list as $query)
			$this->db->ExecuteQuery($query);
	}
}
?>