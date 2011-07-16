<?php

/**
    * 
 */

class DAL_RedirectDb extends DAL_BaseDb
{
    /**
        @var string $TableName Название таблицы
    */
    protected $TableName = "redirect";
    
    /**
        Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
        
        @return array структура таблицы
    */
    protected function getStructure()
    {
        return array(
                "ModuleId" => "int",
                "URL" => "string"
                );
    }
    
    /**
        Возвращает первичные ключи таблицы
        
        @return array ключи таблицы
    */
    protected function getKeys()
    {
        return array("ModuleId");
    }
    
    /**
        @return array автоинкрементные индексы таблицы
    */
    protected function getIndexes()
    {
        return array();
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    
    public function GetUrl($moduleId)
    {
        return $this->selectFirst(array("ModuleId" => $moduleId));
    }
    
    public function UpdateUrl($moduleId,$text)
    {
        $this->update(array("ModuleId" => $moduleId,"URL"=>$text));
    }
    public function DeleteURL($moduleId)
    {
        $this->Delete(array("ModuleId" => $moduleId));
    }
    
    public function InsertUrl($moduleId)
    {
        $this->insert(array("ModuleId" => $moduleId,"URL"=>""));
    }
    

}
?>