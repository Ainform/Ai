<?php

/**
 * DAL_PagesDb class
 * Класс для работы со страницами приложения
 *
 * @author MeF
 * @version PagesDb.class.php, v 1.1.1
 * @copyright (c) by VisualDesign
 */

class DAL_PagesDb extends DAL_BaseDb {
    /**
     @var string $TableName Название таблицы
     */
    protected $TableName = "pages";

    /**
     Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

     @return array структура таблицы
     */
    protected function getStructure() {
        return array(
                "PageId" => "int",
                "Name" => "string",
                "Title" => "string",
                "Keywords" => "string",
                "Description" => "string",
                "Alias" => "string",
                "Parent" => "int",
                "Visible" => "bool",
                "Order" => "int",
                "LanguageId" => "int",
                "Horizontal" => "int",
                "HideInMain" => "int",
                "WithoutDesign" => "int"
        );
    }

    /**
     Возвращает первичные ключи таблицы

     @return array ключи таблицы
     */
    protected function getKeys() {
        return array("PageId");
    }

    /**
     @return array автоинкрементные индексы таблицы
     */
    protected function getIndexes() {
        return array("PageId");
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     Помошник для управления порядком страниц
     */
    private function Order() {
        $orderHelper = new Helpers_OrderHelper();
        $orderHelper->SetInfo('pages', 'PageId', 'Parent', 'LanguageId');

        return $orderHelper;
    }

    /**
     Возвращает список страниц

     @return array
     */
    public function GetPages($parentId = 0) {
        return $this->select(array("Parent" => $parentId), "Order");
    }

    /**
     Возвращает страницу

     @return array
     */
    public function GetPage($pageId) {
        $result = $this->select(array("PageId" => $pageId));
        return $result[0];
    }

    /**
     * Возвращает полный путь до страницы одной строкой
     *
     * @id int $PageId идентификатор раздела
     * @string собственно строка, в которой по окончании будет путь со слешем в конце
     */
    function GetPageFullPath($id,&$string='') {
        $parent=$this->GetParent($id);
        if($string=='' && $parent==0) {
            unset ($db);
            $result=$this->select(array("PageId"=>$id));
            $string = $result[0]['Alias']."/";
            return $string;
        }
        if($parent==0) {
            unset ($db);
            return $string;
        }

        if($string=='') {
            $result=$this->select(array("PageId"=>$id));
            $string = $result[0]['Alias']."/";
        }

        $result=$this->select(array("PageId"=>$parent));

        $string = $result[0]['Alias']."/".$string;
        $this->GetPageFullPath($parent,$string);
    }

    public function GetParent($id) {

        $pages = $this->select(array("PageId" => $id));

        if (count($pages) > 0) {
            return $pages[0]['Parent'];
        }

        return null;
    }

    public function GetSubPagesByModule($moduleId) {
        $sql = "SELECT * FROM pages WHERE Parent IN (SELECT PageId FROM pagemodules WHERE ModuleId = $moduleId)";
        $result = $this->query($sql);

        if (count($result) <= 0)
            return null;

        return $result;
    }

    /**
     Обновляет страницу

     @return array
     */
    public function UpdatePage($pageRow) {
        $this->update($pageRow);
    }

    /**
     Добавляет новую страницу

     */
    public function AddPage($pageRow) {
        if (!isset($pageRow['Parent']))
            throw new InternalException("Некорректные параметры для добавления страницы");

        // проверка существования родителя
        $parentId = $pageRow['Parent'];

        if ($parentId > 0) {
            $parent = $this->GetPage($pageRow['Parent']);

            if (!isset($parent['PageId']))
                trigger_error("Некорректные параметры для добавления страницы");
        }

        $pageRow['Order'] = $this->Order()->InsertRecord($pageRow['Parent']/*, $pageRow['LanguageId']*/);
        $this->insert($pageRow);
        return $this->db->GetLastId();
    }

    /**
     Удаляет страницу

     @param int $pageId Идентификатор страницы
     */
    public function DeletePage($pageId) {
        $this->Order()->DeleteRecord($pageId);
        $pages = $this->select(array("Parent" => $pageId));

        $modulesDb = new DAL_ModulesDb();
        $modules = $modulesDb->GetModules($pageId);

        foreach ($modules as $module)
            $modulesDb->DeleteModule($module["ModuleId"]);

        foreach ($pages as $page)
            $this->DeletePage($page['PageId']);

        $this->delete(array("PageId" => $pageId));
    }

    public function Up($pageId) {
        $this->Order()->UpRecord($pageId);
    }

    public function Down($pageId) {
        $this->Order()->DownRecord($pageId);
    }

    /**
     Возвращает список всех страниц сайта
     */
    public function GetAllPages() {
        return $this->select(null, "Name");
    }
}
?>