<?php
/**
 * DAL_CommentsDb class
 * Класс для работы с новостями
 *
 * @author Anakhorein
 * @version 0.q
 * @copyright (c) by Anakhorein
 */

class DAL_CommentsDb extends DAL_BaseDb {
/**
 @var string $TableName Название таблицы
 */
    protected $TableName = "comments";

    /**
     Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

     @return array структура таблицы
     */
    protected function getStructure() {
        return array("id" => "int",
        "moduleid" => "int",
        "title" => "string",
        "text" => "string",
        "anons" => "string",
        "date" => "string",
        "onfront"=>"string"
        );
    }

    /**
     Возвращает первичные ключи таблицы

     @return array ключи таблицы
     */
    protected function getKeys() {
        return array("id");
    }

    /**
     @return array автоинкрементные индексы таблицы
     */
    protected function getIndexes() {
        return array("id");
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Удаляем материал
     *
     * @param int $Id идентификатор
     */
    public function Remove($Id) {
        $analytics = $this->GetOne($Id);

        $this->delete(array("id" => $Id));

        $analyticsFilesDb = new DAL_AnalyticsFilesDb();
        $analyticsFilesDb->DeleteFolder(self::GetFolder($Id));
        unset($analyticsFilesDb);
    }

    /**
     * Возвращает материал
     *
     * @param int $Id идентификатор материала
     * @return array
     */
    public function GetOne($Id) {
        $result = $this->select(array("id" => $Id));
        return $result[0];
    }


    /**
     * Возвращает запрошенную страницу
     *
     * @param int $page номер страницы
     * @param int $recordsOnPage количество записей на страницу
     * @param int $count общее количество записей
     * @return array
     */
    public function GetPage($moduleId, $page, $recordsOnPage, $isArchive=false) {
    	$rows = null;
		
    	if (!$isArchive)
			$rows = $this->selectPage(array("moduleid" => $moduleId),"date",true,$page,$recordsOnPage);
        else
        	$rows = $this->selectPage(array(),"date",true,$page,$recordsOnPage);
		
		 foreach($rows as &$row){
			$row['Url'] = "/?commentsId=".$row["id"];
        }
		
		return $rows;
    }

            /**
     * Возвращает запрошенную страницу на определённую дату
     *
     * @param int $page номер страницы
     * @param int $recordsOnPage количество записей на страницу
     * @param int $count общее количество записей
     * @return array
     */
    public function GetPageOnDate($moduleId, $page, $recordsOnPage, $date, $isArchive=false) {
    	if (!$isArchive)
        $rows=$this->selectPage(array("moduleid" => $moduleId),"date",true,$page,$recordsOnPage);
        else
        	$rows=$this->selectPage(array(),"date",true,$page,$recordsOnPage);

        $newrow=array();
        foreach($rows as $row){
            //Debug(date("d.m.Y",$row['date']));
            if(date("d.m.Y",$row['date'])==$date){
				$row["Url"] = "/?commentsId=".$row["id"];
                $newrow[]=$row;				
            }
        }
		
        return $newrow;
    }
    
   /*
    * Запрашиваем данные для главной страницы
     */
    public function GetOnFront($moduleId, $page, $recordsOnPage) {

        //$result = $this->query("SELECT * FROM $this->TableName WHERE onfront>0 ORDER BY date desc");
        //$result = $this->query("SELECT * FROM $this->TableName WHERE onfront > 0 and moduleid=$moduleId order by onfront, date desc limit $page, $recordsOnPage");
        //$result = $this->query("SELECT * FROM $this->TableName WHERE onfront > 0 order by onfront, date desc limit $page, $recordsOnPage");
    	$result = $this->query("
SELECT n.*, p.name as PageTitle, p.PageId, CONCAT('".SiteUrl."', p.Alias, '/') as Alias
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
        /*if(count($result)<3){
            $result_other = $this->select(array("onfront" => 0), "date", true);
            for ($i=0;$i<(3-count($result));$i++){
                if (isset($result_other[$i]))
                $result[]=$result_other[$i];
            }
        }*/
        return $result;
    }

        public function GetByModuleId($moduleId) {
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
     Возвращает общее количество новостей для текущего модуля

     @param int $moduleId идентификатор модуля
     */
  /*  public function GetCount($moduleId) {
        return $this->selectCount(array("moduleid" => $moduleId));
    }*/

    public function GetCount($moduleId, $onFront=false, $isArchive=false) {
    	if ($onFront)
		{
			//return $this->selectCount(array("moduleid" => $moduleId, "onfront" => 3),null,null,$page,$recordsOnPage);
			//$result = $this->query("SELECT count(*) as cnt FROM $this->TableName WHERE onfront>0 and moduleid=$moduleId order by date desc");
			$result = $this->query("SELECT count(*) as cnt FROM $this->TableName WHERE onfront>0");

			if (count($result))
				return $result[0]["cnt"];
			return 0;
		}
		else
		{
			if (!$isArchive)
			return $this->selectCount(array("moduleid" => $moduleId));
			else
				return $this->selectCount(array());
		}
    }

    public function GetCountOnDate($moduleId,$date,$isArchive=false) {
    	if (!$isArchive)
        return $this->selectCount(array("moduleid" => $moduleId,"date"=>$date));
        else
        	return $this->selectCount(array("date"=>$date));
    }

    /**
     * Добавляем данные
     *
     * @param row $Row данные
     */
    public function Add($Row) {
        $this->insert($Row);

		include_once '../generaterss.php';
        return $this->db->GetLastId();
    }

    /**
     * Обновляет
     *
     * @param row $Row данные
     */
    public function Update($Row) {
    //Полиморфизм на
        parent::update($Row);
		include_once '../generaterss.php';
    }

    /**
     Возвращает папку для материала
     */
    public static function GetFolder($id = null) {
        $folder = "comments";

        //внутри создаём подпапку с именем равными id материала
        if (isset($id))
            $folder .= "/".$id."/";

        return $folder;
    }

    public function GetRssNews($moduleId, $limit)
    {
		return $this->query("SELECT * FROM $this->TableName where onfront>0 order by date desc");
    }
}

?>