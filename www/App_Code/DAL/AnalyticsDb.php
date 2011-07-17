<?php
/**
 * DAL_AnalyticsDb class
 * Класс для работы с баннерами
 *
 * @author Anakhorein
 * @version 0.q
 * @copyright (c) by Anakhorein
 */

class DAL_AnalyticsDb extends DAL_BaseDb {
/**
 @var string $TableName Название таблицы
 */
    protected $TableName = "text";

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
        "date" => "string");
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
     * Возвращает запрошенную страницу с аналитикой
     *
     * @param int $page номер страницы
     * @param int $recordsOnPage количество записей на страницу
     * @param int $count общее количество записей
     * @return array
     */
    public function GetAnalyticsPage($moduleId, $page, $recordsOnPage) {
        return $this->selectPage(array("moduleid" => $moduleId),"date","DESC",$page,$recordsOnPage);
    }


    /**
     Возвращает общее количество новостей для текущего модуля

     @param int $moduleId идентификатор модуля
     */
    public function GetCountAnalytics($moduleId) {
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
     * Обновляет аналитику
     *
     * @param row $newsRow данные по аналитике
     */
    public function UpdateAnalytics($analyticsRow) {
//Debug($analyticsRow,false);
        $this->update($analyticsRow);
    }

    /**
     Возвращает папку для материала
     */
    public static function GetFolder($Id = null) {
        $folder = "tfi";

        //внутри создаём подпапку с именем равными id материала
        if (isset($Id))
            $folder .= "/".$Id."/";

        return $folder;
    }

}

?>
