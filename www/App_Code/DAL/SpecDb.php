<?php

/**
 *
 */

class DAL_SpecDb extends DAL_BaseDb {
    /**
     @var string $TableName Название таблицы
     */
    protected $TableName = "special";

    /**
     Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

     @return array структура таблицы
     */
    protected function getStructure() {
        return array(
                "GoodId" => "int",
                "DateStart" => "string",
                "DateEnd" => "string",
                "Discount" => "float",
                "Path" => "string"
        );
    }

    /**
     Возвращает первичные ключи таблицы

     @return array ключи таблицы
     */
    protected function getKeys() {
        return array("GoodId");
    }

    /**
     @return array автоинкрементные индексы таблицы
     */
    protected function getIndexes() {
        return array();
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Добавляет товар
     *
     * @param array $specRow данные товара
     */
    public function AddSpec($specRow) {
        $this->insert($specRow);
        $goodId = $this->db->GetLastId();
        return $goodId;
    }

    public function UpdateSpec($specRow) {
        $this->update($specRow);
        $goodId = $this->db->GetLastId();
        return $goodId;
    }

    public function GetSpec($id) {
        $result=$this->selectFirst(array("GoodId"=>$id));
        return $result;
    }

    public function GetSpecOnThisDate($goodId) {
        $rows =  $this->query("SELECT * FROM $this->TableName WHERE ((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `Datestart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL) AND GoodId='".$goodId."'");
        return $rows;
    }
    public function DeleteSpec($id) {
        $result=$this->delete(array("GoodId"=>$id));
        return $result;
    }

    public function GetPage($page, $recordsOnPage) {
        $startRow = $recordsOnPage * $page;
		
        $rows =  $this->query("SELECT *, (CASE WHEN `DateEnd` IS NULL THEN 1 ELSE 0 END) as IsDateNull FROM $this->TableName WHERE ((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `DateStart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL) ORDER BY IsDateNull, `DateEnd` LIMIT $startRow,$recordsOnPage");
        return $rows;
    }

    public function GetAdminPage($page, $recordsOnPage) {
        $startRow = $recordsOnPage * $page;
        $rows =  $this->query("SELECT *, (CASE WHEN `DateEnd` IS NULL THEN 1 ELSE 0 END) as IsDateNull FROM $this->TableName ORDER BY IsDateNull, `DateEnd` LIMIT $startRow,$recordsOnPage");
        return $rows;
    }

    public function GetPageByManufacturer($manid,$page, $recordsOnPage) {
        $startRow = $recordsOnPage * $page;
        $rows =  $this->query("SELECT *, (CASE WHEN `DateEnd` IS NULL THEN 1 ELSE 0 END) as IsDateNull FROM $this->TableName LEFT JOIN goods ON special.GoodId=goods.GoodId WHERE goods.ManufacturerId=".$manid." AND ((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `Datestart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL) ORDER BY IsDateNull, `DateEnd` LIMIT $startRow,$recordsOnPage");
        return $rows;
    }
    public function GetCountPageByManufacturer($manid) {
        $rows =  $this->query("SELECT COUNT(*) FROM $this->TableName LEFT JOIN goods ON special.GoodId=goods.GoodId WHERE goods.ManufacturerId=".$manid." AND ((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `Datestart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL)");
        return @$rows['COUNT(*)'];
    }

    public function GetPageBySearch($search,$page, $recordsOnPage) {
        $startRow = $recordsOnPage * $page;
        $rows =  $this->query("SELECT *, (CASE WHEN `DateEnd` IS NULL THEN 1 ELSE 0 END) as IsDateNull FROM $this->TableName LEFT JOIN goods ON special.GoodId=goods.GoodId WHERE UPPER(goods.Title) LIKE UPPER('%".$search."%') OR UPPER(goods.Description) LIKE UPPER('%".$search."%') OR UPPER(goods.Code) LIKE UPPER('%".$search."%') AND ((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `Datestart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL) ORDER BY IsDateNull, `DateEnd` LIMIT $startRow,$recordsOnPage");
        return $rows;
    }
    public function GetCountPageBySearch($search) {
        $rows =  $this->query("SELECT COUNT(*) FROM $this->TableName LEFT JOIN goods ON special.GoodId=goods.GoodId WHERE UPPER(goods.Title) LIKE UPPER('%".$search."%') OR UPPER(goods.Description) LIKE UPPER('%".$search."%') OR UPPER(goods.Code) LIKE UPPER('%".$search."%') AND ((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `Datestart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL)");
        return @$rows['COUNT(*)'];
    }

    public function GetAll() {
        $rows =  $this->select(array());
        return $rows;
    }

    public function GetCount() {
        $rows =  $this->query("SELECT COUNT(*) FROM $this->TableName WHERE (((NOT `DateStart` IS NULL AND `DateStart`<'".date("Y-m-d H:i:s")."') OR `Datestart` IS NULL) AND ((NOT `DateEnd` IS NULL AND `DateEnd`>'".date("Y-m-d H:i:s")."') OR `DateEnd` IS NULL)");
        return @$rows[0]['COUNT(*)'];
    }

    public function GetAdminCount() {
        $rows =  $this->query("SELECT COUNT(*) FROM $this->TableName");
        return @$rows[0]['COUNT(*)'];
    }

    public function CheckPresence($goodid) {
        return $this->selectCount(array("GoodId"=>$goodid));
    }

}