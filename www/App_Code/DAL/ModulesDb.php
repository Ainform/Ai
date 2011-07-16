<?php

/**
 * DAL_ModulesDb class
 * Класс для работы с модулями приложения
 *
 * @author Frame
 * @version ModulesDb.class.php, v 1.1.1
 * @copyright (c) by VisualDesign
 */
class DAL_ModulesDb extends DAL_BaseDb {

    /**
      @var string $TableName Название таблицы
     */
    protected $TableName = "pagemodules";

    /**
      Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

      @return array структура таблицы
     */
    protected function getStructure() {
        return array(
            "PageId" => "int",
            "ModuleId" => "int",
            "ModuleType" => "string",
            "Order" => "int"
        );
    }

    /**
      Возвращает первичные ключи таблицы

      @return array ключи таблицы
     */
    protected function getKeys() {
        return array("ModuleId");
    }

    /**
      @return array автоинкрементные индексы таблицы
     */
    protected function getIndexes() {
        return array("ModuleId");
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct() {
        parent::__construct();

        $this->LoadData();
    }

    /**
      Помошник для управления порядком страниц
     */
    private function Order() {
        $orderHelper = new Helpers_OrderHelper();
        $orderHelper->SetInfo('pagemodules', 'ModuleId', 'PageId');

        return $orderHelper;
    }

    /**
      Список всех модулей

      @var array
     */
    private static $modulesList = null;

    /**
     * Загружает xml файл с модулями
     *
     */
    private function LoadData() {
        if (!isset(self::$modulesList)) {

            $modules = BMC_SiteMap::GetModulesFromDir();
            // грузим список модулей
            foreach ($modules as $module) {
                $module['isAdmin'] = "false";
                self::$modulesList[$module['alias']] = $module;
            }

            $adminmodules = BMC_SiteMap::GetAdminModulesFromDir();
            // грузим админские модули
            foreach ($adminmodules as $adminmodule) {
                $adminmodule['isAdmin'] = "True";
                self::$modulesList[$adminmodule['alias']] = $adminmodule;
            }
        }
    }

    public function GetModule($moduleId) {
        //Debug($moduleId);
        $result = $this->select(array("ModuleId" => $moduleId));
        return $result[0];
    }

    /**
      Возвращает список модулей

      @return array
     */
    public function GetModulesList() {
        return self::$modulesList;
    }

    /**
      Возвращает данные модулей для конкретной страницы
     */
    public function GetModules($pageId) {
        //var_dump($pageId);
        return $this->select(array("PageId" => $pageId), "Order");
    }

    public function GetAdminModules() {

        return $this->query("SELECT * FROM `$this->TableName` WHERE `PageId` < 0");
    }

    /**
      Возвращает инициализированные модули для страницы

      @param int $pageId идентификатор страницы
     */
    public function GetModulesObjects($pageId) {
        $modules = $this->GetModules($pageId);
        //$modulesList = $this->GetModulesList();
        $modulesObjects = array();

        foreach ($modules as $module) {
            $moduleObject = $this->GetModuleInstance($module['ModuleId'], $module['ModuleType']);

            if ($moduleObject != null)
                $modulesObjects[] = $moduleObject;
        }

        return $modulesObjects;
    }

    /**
      Возвращает инстанцированный объект модуля

      @param int $moduleId идентификатор модуля
      @param string $moduleType тип модуля
     */
    public function GetModuleInstance($moduleId, $moduleType = null) {
        $modulesList = $this->GetModulesList();

        if (!isset($moduleType)) {
            $moduleRow = $this->GetModule($moduleId);
            $moduleType = $moduleRow['ModuleType'];
        }

        // если модуля нет в списке, то игнорируем его
        if (!isset($modulesList[$moduleType]))
            return null;

        $moduleFolder = Helpers_PathHelper::GetFullPath('module');

        // для админских модулей используем другую папку
        if ($modulesList[$moduleType]['isAdmin'] == "True")
            $moduleFolder = Helpers_PathHelper::GetFullPath('admin') . 'module/';
        else
            $moduleFolder = Helpers_PathHelper::GetFullPath('module');

        $moduleFolder = $moduleFolder . $moduleType . '/';
        $modulePath = $moduleFolder . "module.php";
        //debug($modulePath);

        if (file_exists($modulePath)) {
            include_once($modulePath);
            $className = $moduleType . "Module";

            // создаём экземпляр модуля и добавляем его в список
            $moduleObject = new $className($moduleId);
            $moduleObject->folder = $moduleFolder;
            $moduleObject->template = 'module.tpl';
            $moduleObject->moduleType = $moduleType;
            //var_dump($moduleObject);exit;
            //определяем текущую страницу для того, чтобы прописать корректный путь
            $curPage = BMC_SiteMap::GetCurPage();
            if (isset($curPage->Url)) {

                $moduleObject->Url = Helpers_PathHelper::GetFullUrl('root', false) . $curPage->Url;
            }
        }
        else
            trigger_error('Не найден модуль для загрузки. Тип модуля &quot;' . $moduleType . '&quot;.');

        //var_dump($moduleObject);

        return $moduleObject;
    }

    /**
      Возвращает инстанцированный административный объект модуля

      @param int $moduleId идентификатор модуля
      @param string $moduleType тип модуля
     */
    public function GetModuleEditInstance($moduleId, $moduleType = null) {
        $modulesList = $this->GetModulesList();

        if (!isset($moduleType)) {
            $moduleRow = $this->GetModule($moduleId);
            $moduleType = $moduleRow['ModuleType'];
        }

        // если модуля нет в списке, то игнорируем его
        if (!isset($modulesList[$moduleType]))
            return null;

        $moduleFolder = Helpers_PathHelper::GetFullPath('module');

        // для админских модулей используем другую папку
        if ($modulesList[$moduleType]['isAdmin'] == "True")
            throw new InternalException("Модули панели управления сайтом редактировать запрещено.");
        else
            $moduleFolder = Helpers_PathHelper::GetFullPath('module');

        $moduleFolder = $moduleFolder . $moduleType . '/';
        $modulePath = $moduleFolder . "admin.php";

        if (file_exists($modulePath)) {
            include_once($modulePath);
            $className = $moduleType . "ModuleEdit";

            // создаём экземпляр модуля и добавляем его в список
            $moduleObject = new $className($moduleId);
            $moduleObject->folder = $moduleFolder;
            $moduleObject->template = 'admin.tpl';
            $moduleObject->moduleType = $moduleType;

            //определяем текущую страницу для того, чтобы прописать корректный путь
            $curPage = BMC_SiteMap::GetCurPage();
            if ($curPage)
                $moduleObject->Url = Helpers_PathHelper::GetFullUrl('root', false) . $curPage->Url;
        } else {
            return false;
        }


        return $moduleObject;
    }

    /**
      Добавляет новый модуль для страницы
     */
    public function AddModule($pageId, $moduleType) {
        $row = array("PageId" => $pageId, "ModuleType" => $moduleType);
        $row['Order'] = $this->Order()->InsertRecord($pageId);
        $this->insert($row);
        $moduleId = $this->db->GetLastId();

        $module = $this->GetModuleInstance($moduleId, $moduleType);

        // осуществляем вызов обработчика модуля
        $module->OnModuleAdd();

        return $moduleId;
    }

    /**
      Удаляет модуль со страницы
     */
    public function DeleteModule($moduleId) {
        try {
            $module = $this->GetModuleInstance($moduleId);
            //var_dump($module);
            // осуществляем вызов обработчика модуля
            $module->OnModuleDelete();
        } catch (Exception $e) {
            var_dump($e);
        }
        $this->Order()->DeleteRecord($moduleId);
        $this->delete(array("ModuleId" => $moduleId));
    }

    /**
      Удаляет модуль админки со страницы
     */
    public function DeleteAdminModule($pageId) {

        $this->delete(array("PageId" => $pageId));
    }

    public function UpModule($moduleId) {
        $this->Order()->UpRecord($moduleId);
    }

    public function DownModule($moduleId) {
        $this->Order()->DownRecord($moduleId);
    }

}

?>