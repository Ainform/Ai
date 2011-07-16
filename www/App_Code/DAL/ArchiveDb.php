<?php
/**
 * DAL_ArchiveDb class
 * Класс для работы с новостями
 *
 * @author Anakhorein
 * @version 0.q
 * @copyright (c) by Anakhorein
 */

class DAL_ArchiveDb extends DAL_BaseDb {
/**
 @var string $TableName Название таблицы
 */
    protected $TableName = "archive";

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
    public function GetPage($moduleId, $page, $recordsOnPage) {
        return $this->selectPage(array("moduleid" => $moduleId),null,null,$page,$recordsOnPage);
    }
    
   /*
    * Запрашиваем данные для главной страницы
     */
    public function GetOnFront() {

        $result = $this->query("SELECT * FROM $this->TableName WHERE onfront>0 ORDER BY onfront ASC");
        /*
        * Из ТЗ:
        * Должны выводится все новости, отмеченные для вывода на главной странице
        * (в соответствии с приоритетом, указанным в системе управления сайтом).
        * Если таких новостей менее 3-х, то список новостей должен быть дополнен последними новостями (до 3-х новостей)
        */
        if(count($result)<3){
            $result_other = $this->select(array("onfront" => 0),"date");
            for ($i=0;$i<(3-count($result));$i++){
                if (isset($result_other[$i]))
                $result[]=$result_other[$i];
            }
        }
        return $result;
    }

    /**
     Возвращает общее количество новостей для текущего модуля

     @param int $moduleId идентификатор модуля
     */
    public function GetCount($moduleId) {
        return $this->selectCount(array("moduleid" => $moduleId));
    }

    /**
     * Добавляем данные
     *
     * @param row $Row данные
     */
    public function Add($Row) {
        $this->insert($Row);

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
    }

    /**
     Возвращает папку для материала
     */
    public static function GetFolder($id = null) {
        $folder = "archive";

        //внутри создаём подпапку с именем равными id материала
        if (isset($id))
            $folder .= "/".$id."/";

        return $folder;
    }

}

?>