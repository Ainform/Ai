<?php

/**
 * DAL_OrderItemsDb class
 * Класс для работы с текстовыми страницами в БД
 * 
 * @author Vadim Petrov
 * @version OrderDb.class.php, v 1.0.0
 * @copyright (c) by Inline
 */

class DAL_OrderItemsDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "orderitems";
	
	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"id" => "int",
				"orderid" => "int",
				"goodid" => "int",
				"count" => "int",
				"price" => "float"
				);
	}
	
	/**
		Возвращает первичные ключи таблицы
		
		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("id");
	}
	
	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("id");
	}

	
	/**
	 * Констуктор, инициализирует соединение
	 *
	 */
	function __construct()
	{
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
	public function AddOrderItem($orderId, $goodName, $count, $price)
	{
		$this->insert(array("orderid" => $orderId,
							"goodid" => $goodName,
							"count" => $count,
							"price" => $price));
		
		return $this->db->GetLastId();			
	}
	
	/*public function AddTempOrder($order)
	{
	}*/
	
	/**
	 * Возвращает запрошенную страницу с Заказами
	 *
	 * @param int $page номер страницы
	 * @param int $recordsOnPage количество записей на страницу
	 * @param int $count общее количество записей
	 * @return array
	 */
	public function GetPage($page, $recordsOnPage, &$count, $where)
	{
		return $this->db->GetPage($page, '`orderitems`', '*', $where , $recordsOnPage, '`id`', '`id` DESC', $count);
	}

	/**
	 * Возвращает заказ
	 *
	 * @param int $id идентификатор дилера
	 * @return array
	 */
	public function GetOrderItem($orderItemId)
	{
		return $this->select(array('orderid' => $orderItemId));
	}
	
	/**
	 * Enter description here...
	 *
	 */
	/*public function GetOrderItemsInfo($order_id)
	{
		Utility_SafetyUtility::SafeInt($order_id);
		$query = "SELECT * FROM `OrderItems` WHERE order_id=$order_id";
		return $this->db->ExecuteReader($query);
	}*/
	
	public function OrderItemsDelete($where)
	{
		$this->delete($where);		
	}
}
?>