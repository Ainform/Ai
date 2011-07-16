<?php

/**
 * DAL_Orders class
 * Класс для работы с текстовыми страницами в БД
 *
 * @author Vadim Petrov
 * @version OrderDb.class.php, v 1.0.0
 * @copyright (c) by Inline
 */
class DAL_OrderDb extends DAL_BaseDb {

    /**
      @var string $TableName Название таблицы
     */
    protected $TableName = "orders";

    /**
      Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

      @return array структура таблицы
     */
    protected function getStructure() {
        return array(
            "id" => "int",
            "date" => "string",
            "contact_face" => "string",
            "phone" => "string",
            "email" => "string",
            "description" => "string",
            "recipient_fio" =>"string",
        "recipient_phone" =>"string",
        "postcard" =>"string",
        "postcard_text" =>"string",
        "address_full" =>"string",
        "address_date" =>"string",
        "address_time" =>"string",
        "contact_fio" =>"string",
        "contact_phone" =>"string",
        "contact_email" =>"string",
        "contact_birthdate" =>"string",
        "discounts_and_actions" =>"int",
        "card"=>"int"
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
     * Добавляет соответствующие записи о заказе в таблицы Orders, OrderItems
     *
     * @param array $order
     * @param array $orderItems
     * @param string $section
     * @return bool
     */
    public function AddOrder($recipient_fio, $recipient_phone, $postcard, $postcard_text, $address_full, $address_date, $address_time, $contact_fio, $contact_phone, $contact_email, $contact_birthdate, $discounts_and_actions, $card) {
        $this->insert(array("date" => date("Y-m-d"),
        "recipient_fio" => strip_tags($recipient_fio),
        "recipient_phone" => strip_tags($recipient_phone),
        "postcard" => strip_tags($postcard),
        "postcard_text" => strip_tags($postcard_text),
        "address_full" => strip_tags($address_full),
        "address_date" => strip_tags($address_date),
        "address_time" => strip_tags($address_time),
        "contact_fio" => strip_tags($contact_fio),
        "contact_phone" => strip_tags($contact_phone),
        "contact_email" => strip_tags($contact_email),
        "contact_birthdate" => strip_tags($contact_birthdate),
        "discounts_and_actions" => strip_tags($discounts_and_actions),
        "card" => strip_tags($card))
            );

        return $this->db->GetLastId();
    }

    /* public function AddTempOrder($order)
      {
      } */

    /**
     * Возвращает запрошенную страницу с Заказами
     *
     * @param int $page номер страницы
     * @param int $recordsOnPage количество записей на страницу
     * @param int $count общее количество записей
     * @return array
     */
    public function GetPage($page, $recordsOnPage, &$count, $where = "") {
        return $this->db->GetPage($page, '`orders`', '*', $where, $recordsOnPage, '`id`', '`id` DESC', $count);
    }

    /**
     * Возвращает заказ
     *
     * @param int $id идентификатор дилера
     * @return array
     */
    public function GetOrder($orderId) {
        return $this->select(array('id' => $orderId));
    }

    public function GetCount() {
        return $this->selectCount();
    }

    public function OrderDelete($orderId) {
        $this->delete(array('id' => $orderId));
    }

    public function OrderPaid($orderId) {
        $this->update(array('id' => $orderId,
            'paid' => 1));
    }

}

?>