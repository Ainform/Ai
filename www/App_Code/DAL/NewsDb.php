<?php
/**
 * DAL_NewsDb class
 * Класс для работы с новостями
 *
 * @author Anakhorein
 * @version 0.q
 * @copyright (c) by Anakhorein
 */

class DAL_NewsDb extends DAL_BaseDb
{
	/**
	@var string $TableName Название таблицы
	 */
	protected $TableName = "news";

	/**
	 * Удаляем материал
	 *
	 * @param int $Id идентификатор
	 */
	public function Remove($Id)
	{
		$analytics = $this->GetOne($Id);

		$this->delete(array("id" => $Id));

		$analyticsFilesDb = new DAL_AnalyticsFilesDb();
		$analyticsFilesDb->DeleteFolder(self::GetFolder($Id));
		unset($analyticsFilesDb);

		//include_once '../generaterss.php';
	}

	/**
	 * Возвращает материал
	 *
	 * @param int $Id идентификатор материала
	 * @return array
	 */
	public function GetOne($Id)
	{
		$result = $this->select(array("id" => $Id));
		return $result[0];
	}

	/*
* Запрашиваем данные для главной страницы
*/
	public function GetOnFront($moduleId, $page, $recordsOnPage)
	{

		//$result = $this->query("SELECT * FROM $this->TableName WHERE onfront > 0 and moduleid=$moduleId order by onfront, date desc limit $page, $recordsOnPage");
		//$result = $this->query("SELECT * FROM $this->TableName WHERE onfront > 0 order by onfront, date desc limit $page, $recordsOnPage");
		$result = $this->query("
SELECT n.*, p.name as PageTitle, p.PageId, CONCAT('" . SiteUrl . "', p.Alias, '/') as Alias
FROM $this->TableName n
 join PageModules pm
   on n.moduleid=pm.moduleid
join Pages p on pm.pageid = p.pageid
WHERE onfront > 0 order by onfront, date desc limit $page, $recordsOnPage");

		/*
* Из ТЗ:
* Должны выводится все новости, отмеченные для вывода на главной странице
* (в соответствии с приоритетом, указанным в системе управления сайтом).
* Если таких новостей менее 3-х, то список новостей должен быть дополнен последними новостями (до 3-х новостей)
*/
		/*if(count($result)<6){
	$result_other = $this->select(array("onfront" => 0),"date");
	for ($i=0;$i<(6-count($result));$i++){
		if (isset($result_other[$i]))
		$result[]=$result_other[$i];
	}
}*/

		return $result;
	}

	/**
	Возвращает папку для картинок новости
	 */
	public static function GetImageFolder($newsId = null)
	{
		$folder = "news/";

		if (isset($newsId))
			$folder .= $newsId . "/";

		return $folder;
	}

	/**
	 * Возвращает запрошенную страницу
	 *
	 * @param int $page номер страницы
	 * @param int $recordsOnPage количество записей на страницу
	 * @param int $count общее количество записей
	 * @return array
	 */
	public function GetPage($terms = array(), $sort = "Order", $sort_dir = "DESC", $page, $recordsOnPage)
	{
		$rows = $this->selectPage($terms, $sort, $sort_dir, $page, $recordsOnPage);

		foreach ($rows as &$row) {
			$row['Url'] = "/?newsId=" . $row["id"];
		}
		return $rows;
	}

	public function GetLastNews($recordsOnPage, $moduleId)
	{

		if (is_int($moduleId)) {
			$rows = $this->select(array("moduleid" => $moduleId), "date", true, $recordsOnPage);
		}
		else {
			$rows = $this->select(array(), "date", true, $recordsOnPage);
		}

		foreach ($rows as &$row) {
			//TODO рекурсивно надо получать путь
			$PageAlias = $this->query("SELECT pages.Alias, pages.Parent as Parent FROM pagemodules INNER JOIN pages ON pagemodules.PageId=pages.PageId WHERE pagemodules.ModuleId=" . $row['moduleid']);
			$parentAlias = $this->query("SELECT Alias FROM pages WHERE PageId=" . $PageAlias[0]['Parent']);
			$row['Url'] = $parentAlias[0]['Alias'] . "/" . $PageAlias[0]['Alias'] . "/?newsId=" . $row["id"];
		}

		return $rows;
	}

	public function GetByModuleId($moduleId)
	{
		//$rows =  $this->select(array("moduleid" => $moduleId),"date",true);

		//foreach($rows as &$row)
		//$row['Url'] = "/?newsId=".$row["id"];

		$rows = $this->query("
SELECT n.*, p.Alias as Alias
FROM $this->TableName n
 join PageModules pm
   on n.moduleid=pm.moduleid AND n.moduleid=$moduleId
join Pages p on pm.pageid = p.pageid
order by date desc");

		return $rows;
	}

	/**
	 *
	 *
	 * Возвращает запрошенную страницу на определённую дату
	 *
	 * @param int $page номер страницы
	 * @param int $recordsOnPage количество записей на страницу
	 * @param int $count общее количество записей
	 * @return array
	 */
	public function GetPageOnDate($moduleId, $page, $recordsOnPage, $date, $isArchive = false)
	{

		$rows = null;

		if (!$isArchive)
			$rows = $this->selectPage(array("moduleid" => $moduleId), "date", null, $page, $recordsOnPage);
		else
			$rows = $this->selectPage(array(), "date", null, $page, $recordsOnPage);

		$newrow = array();

		foreach ($rows as $row) {
			//Debug(date("d.m.Y",$row['date']),false);
			if (date("d.m.Y", $row['date']) == $date) {
				$row["Url"] = "/?newsId=" . $row["id"];
				$newrow[] = $row;
			}
		}
		return $newrow;
	}

	/**
	Возвращает общее количество новостей для текущего модуля

	@param int $moduleId идентификатор модуля
	 */
	public function GetCount($moduleId, $onFront = false)
	{
		if ($onFront) {
			//return $this->selectCount(array("moduleid" => $moduleId, "onfront" => 3),null,null,$page,$recordsOnPage);
			//$result = $this->query("SELECT count(*) as cnt FROM $this->TableName WHERE onfront>0 and moduleid=$moduleId order by date desc");
			$result = $this->query("SELECT count(*) as cnt FROM $this->TableName WHERE onfront>0");

			if (count($result))
				return $result[0]["cnt"];
			return 0;
		}
		else
			return $this->selectCount(array("moduleid" => $moduleId));
	}

	public function GetCountOnDate($moduleId, $date, $isArchive = false)
	{
		if (!$isArchive)
			return $this->selectCount(array("moduleid" => $moduleId, "date" => $date));
		else
			return $this->selectCount(array("date" => $date));
	}


	/**
	 * Добавляем данные
	 *
	 * @param row $Row данные
	 */
	public function Add($Row)
	{
		$this->insert($Row);

		return $this->db->GetLastId();
		//include_once '../generaterss.php';
	}

	/**
	 * Обновляет
	 *
	 * @param row $Row данные
	 */
	public function Update($Row)
	{
		//Полиморфизм на
		parent::update($Row);

		//include_once '../generaterss.php';
	}

	/**
	Возвращает папку для материала
	 */
	public static function GetFolder($id = null)
	{
		$folder = "news";

		//внутри создаём подпапку с именем равными id материала
		if (isset($id))
			$folder .= "/" . $id . "/";

		return $folder;
	}

	/**
	 *
	 * @param $moduleId - идентификатор модуля
	 * @param $limit - количество возращаемых новостей
	 * @return unknown_type - список новостей
	 */
	public function GetRssNews($moduleId, $limit)
	{
		//return $this->select(array("moduleid" => $moduleId), "Date", true, $limit);
		//return $this->select(array(), "date", true, $limit);
		return $this->query("SELECT * FROM $this->TableName where onfront>0 order by date desc"); // решено забирать новости из всех разделов

	}

	public function GetAllNews()
	{
		return $this->select(array());
	}

	/**
	Удаляет страницу

	 * @param $moduleId
	 */
	public function DeletePage($moduleId)
	{
		$this->delete(array("moduleid" => $moduleId));
	}

	/**
	 * Поднимает
	 *
	 * @param $id
	 */
	public function Up($id)
	{
		$this->Order($this->TableName, 'id', 'ModuleId')->UpRecord($id);
	}

	/**
	 * Опускает
	 *
	 * @param $id
	 */
	public function Down($id)
	{
		$this->Order($this->TableName, 'id', 'ModuleId')->DownRecord($id);
	}
}

?>
