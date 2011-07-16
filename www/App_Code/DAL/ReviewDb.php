<?php

/**
 *
 * @author Frame
 * @version 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_ReviewDb extends DAL_BaseDb {
  /**
   @var string $TableName Название таблицы
   */
  protected $TableName = "review";

  /**
   Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
   @return array структура таблицы
   */
  protected function getStructure() {
    return array(
            "ReviewId" => "int",
            "ModuleId" => "int",
            "FIO" => "string",
            "Fone" => "string",
            "Show" => "int",
            "Text" => "string",
            "Date" => "int",
            "Order" => "int",
            "IP" =>"string"
    );
  }

  /**
   Возвращает первичные ключи таблицы
		
   @return array ключи таблицы
   */
  protected function getKeys() {
    return array("ReviewId");
  }

  /**
   @return array автоинкрементные индексы таблицы
   */
  protected function getIndexes() {
    return array("ReviewId");
  }

  /**
   * Констуктор, инициализирует соединение
   *
   */
  function __construct() {
    parent::__construct();
  }


  /**
   * Возвращает новость
   *
   * @param int $ReviewId идентификатор новости
   * @return array
   */
  public function GetReview($ReviewId) {
    $result = $this->select(array("ReviewId" => $ReviewId));
    return $result[0];
  }


  /**
   * Возвращает запрошенную страницу с новостями
   *
   * @param int $page номер страницы
   * @param int $recordsOnPage количество записей на страницу
   * @param int $count общее количество записей
   * @return array
   */
  public function GetReviewPage($moduleId, $page, $recordsOnPage) {
    return $this->selectPage(array("ModuleId" => $moduleId),
            "Order",
            true,
            $page,
            $recordsOnPage);
  }

  /**
   Возвращает общее количество для текущего модуля

   @param int $moduleId идентификатор модуля
   */
  public function GetCountReview($moduleId) {
    return $this->selectCount(array("ModuleId" => $moduleId));
  }


  /**
   * Удаляет новость
   *
   * @param int $ReviewId идентификатор новости
   */
  public function DeleteReview($ReviewId) {
    $this->delete(array("ReviewId" => $ReviewId));
  }

  public function Show($ReviewId) {
    $temp=$this->select(array("ReviewId"=>$ReviewId));

    if($temp[0]['Show']==0) {
      $this->update(array("ReviewId" => $ReviewId,'Show'=>'1'));
    }
    elseif($temp[0]['Show']==1) {
      $this->update(array("ReviewId" => $ReviewId,"Show"=>'0'));
    }

  }

  /**
   * Возвращает все новости для модуля
   *
   * @return array
   */
  public function GetAllReview($moduleId) {
    return $this->select(array("ModuleId" => $moduleId), "Order", true);
  }


  public function AddReview($ReviewRow) {
    $this->insert($ReviewRow);
    return $this->db->GetLastId();
  }
  
    public function UpdateReview($ReviewRow) {
    $this->update($ReviewRow);
    return $this->db->GetLastId();
  }

    /**
	 * Возвращает экземпляр класса OrderHelper
	 *
	 * @return Helpers_OrderHelper
	 */
	private function Order()
	{
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo('review', 'ReviewId','ModuleId');

		return $orderHelper;
	}

	/**
	 * Поднимает лицензию
	 *
	 * @param int $imageId идентификатор лицензии
	 */
	public function Up($Id)
	{
   		$this->Order()->UpRecord($Id);
	}

	/**
	 * Опускает лицензию
	 *
	 * @param int $imageId идентификатор лицензии
	 */
	public function Down($Id)
	{
		$this->Order()->DownRecord($Id);
	}

}