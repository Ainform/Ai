<?php

/**
 * DAL_UsersDb class
 * Класс для работы с пользователями в БД
 *
 * @author Informix
 * @version 1.0
 */

class DAL_ReportsDb extends DAL_BaseDb {
/**
 @var string $TableName Название таблицы
 */
    protected $TableName = "Reports";

    /**
     Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
     @return array структура таблицы
     */
    protected function getStructure() {
        return array(
        "ReportId"=>"int",
        "UserId"=>"int",
        "ModuleId"=>"int",
        "DateLoad"=>"int",
        "DateUpdate"=>"int",
        "Number"=>"int",//№
        "Bid"=>"int",//№ заявки
        "Type"=>"string",//Тип операции
        "DateTime"=>"string", //Дата, время
        "NameCB"=>"string",//Наименование ЦБ
        "MarginLevel"=>"string",//Уровень маржи
        "CountCB"=>"string",//Количество ЦБ (шт.)
        "Price"=>"string",//Цена
        "CurrencyPrice"=>"string",//Валюта цены
        "SumDeal"=>"string",//Сумма сделки
        "NKD"=>"string",//НКД
        "ExchangeCommission"=>"string",//Комиссия биржи
        "BrokerCommission"=>"string",//Комиссия Брокера
        "PayDate"=>"string",//Дата оплаты
        "DatePerereg"=>"string",//Дата перерег.ЦБ
        "Place"=>"string",//Место совершения
        );
    }

    /**
     Возвращает первичные ключи таблицы
		
     @return array ключи таблицы
     *      В данном случае возращаем Number, так как номер операции единственная уникальная фигня в отчёте и так написано в ТЗ
     */
    protected function getKeys() {
        return array(
        "Number",
        "UserId"/*//№
		"Bid",//№ заявки
		"Type",//Тип операции
		"DateTime", //Дата, время
		"NameCB",//Наименование ЦБ
		"MarginLevel",//Уровень маржи
		"CountCB",//Количество ЦБ (шт.)
		"Price",//Цена
		"CurrencyPrice",//Валюта цены
		"SumDeal",//Сумма сделки
		"NKD",//НКД
		"ExchangeCommission",//Комиссия биржи
		"BrokerCommission",//Комиссия Брокера
		"PayDate",//Дата оплаты
		"DatePerereg",//Дата перерег.ЦБ
		"Place",//Место совершения
                "UserId",//Id юзера*/
        );
    }

    /**
     @return array автоинкрементные индексы таблицы
     */
    protected function getIndexes() {
        return array("ReportId");
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct() {
        parent::__construct();
    }



    /**
     * Возвращает массив отчетов для опредленного юзера
     *
     * @param int $UserId
     * @param int $ModuleId
     * @return Array
     */
    public function GetAllReportsByUser($UserId = null,$ModuleId = null) {
        $ret["UserId"] = $UserId;
        if(!empty($ModuleId)) {
            $ret["ModuleId"] = $ModuleId;
        }
        //Debug ($ret);
        //FIXME Я не придумал как бы по другому перобразовать дату и время в нормальный формат
        $reports = $this->select($ret);
        if ($reports) {
            foreach($reports as $number=>$report) {
                $reports[$number]['DateTime']=date( 'j.m.Y G:i', $report['DateTime']);
                $reports[$number]['DatePerereg']=date( 'j.m.Y G:i', $report['DatePerereg']);
                $reports[$number]['PayDate']=date( 'j.m.Y G:i', $report['PayDate']);
            }
        }
        return $reports;
    }

    /**
     * сохранение данных для отчетов
     *
     * @param Array $array
     * @param Int $UserId
     * @param Int $ModuleId
     */
    public function MegaSave($array = array(),$UserId = null,$ModuleId = null) {
    //получаем все текущие отчёты пользователя
        $res = $this->GetAllReportsByUser($UserId,$ModuleId);

        //сохраняем в массив все номера операций
        $operationNumber = array();
        foreach ($res as $i=>$data) {
            $operationNumber[] = $data['Number'];
        }
        $update = 0;
        $insert = 0;
        $now=time();

        //Debug($array,false);

        foreach ($array as $save) {
            $save["ModuleId"] = $ModuleId;
            $save["UserId"] = $UserId;
            $save["DateUpdate"] = $now;
            if(in_array($save['Number'],$operationNumber)) {
                $update++;
                $this->update($save);
            }else {
                $insert++;
                $save["DateLoad"] = $now;
                $this->insert($save);
            }
        }

        return array("update"=>$update,"insert"=>$insert);
    }

    /**
     * возвращает массив названий для полей базы данных
     *
     * @return unknown
     */
    public function getNames() {
        return array(
        "ReportId"=>"int",
        "UserId"=>"int",
        "ModuleId"=>"int",
        "DateLoad"=>"int",
        "DateUpdate"=>"int",
        "Number"=>"№",//№
        "Bid"=>"№ заявки",//№ заявки
        "Type"=>"Тип операции",//Тип операции
        "DateTime"=>"Дата, время", //Дата, время
        "NameCB"=>"Наименование ЦБ",//Наименование ЦБ
        "MarginLevel"=>"Уровень маржи",//Уровень маржи
        "CountCB"=>"Количество ЦБ (шт.)",//Количество ЦБ (шт.)
        "Price"=>"Цена",//Цена
        "CurrencyPrice"=>"Валюта цены",//Валюта цены
        "SumDeal"=>"Сумма сделки",//Сумма сделки
        "NKD"=>"НКД",//НКД
        "ExchangeCommission"=>"Комиссия биржи",//Комиссия биржи
        "BrokerCommission"=>"Комиссия Брокера",//Комиссия Брокера
        "PayDate"=>"Дата оплаты",//Дата оплаты
        "DatePerereg"=>"Дата перерег.ЦБ",//Дата перерег.ЦБ
        "Place"=>"Место совершения",//Место совершения
        );
    }

    /**
     * Возвращает запрошенную страницу с отчётом
     *
     * @param int $page номер страницы
     * @param int $recordsOnPage количество записей на страницу
     * @param int $count общее количество записей
     * @return array
     */
    public function GetReportsPage($userId, $page, $recordsOnPage) {
        return $this->selectPage(array("UserId" => $userId),null,null,$page,$recordsOnPage);
    }

    /**
     Возвращает общее количество записей в отчёте для текущего модуля и пользователя

     @param int $moduleId идентификатор модуля
     */
    public function GetReportsCount($userId) {
        return $this->selectCount(array("UserId" => $userId));
    }

}
?>