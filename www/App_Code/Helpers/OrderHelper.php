<?php

/**
 * Helpers_OrderHelper class
 * Класс для работы с позициями элементов в БД
 *
 * @author Frame
 * @version OrderHelper.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */
class Helpers_OrderHelper extends DAL_BaseDb {

    /**
     * Имя таблицы
     *
     * @var string
     */
    private $tableName;
    /**
     * Индекс идентификатора
     *
     * @var string
     */
    private $idIndex;
    /**
     * Индекс идентификатора родительской записи
     *
     * @var string
     */
    private $parentIdIndex;
    /**
      Третья колонка, по которой будет производиться разбиение порядков
     */
    private $thirdIdIndex;

    /**
     * Конструктор
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Задает параметры
     *
     * @param string $tableName имя таблицы
     * @param string $idIndex индекс идентификатора
     * @param string $parentIdIndex индекс идентификатора родительской записи
     */
    function SetInfo($tableName, $idIndex, $parentIdIndex = null, $thirdIdIndex = null) {
        Utility_SafetyUtility::SafeString($tableName);
        Utility_SafetyUtility::SafeString($idIndex);
        Utility_SafetyUtility::SafeString($parentIdIndex);
        Utility_SafetyUtility::SafeString($thirdIdIndex);

        $this->tableName = $tableName;
        $this->idIndex = $idIndex;
        $this->parentIdIndex = $parentIdIndex;
        $this->thirdIdIndex = $thirdIdIndex;
    }

    /**
     * Возвращает идентификатор родительской записи
     *
     * @param int $id идентификатор записи
     * @return int
     */
    private function GetParentId($id) {
        if (null != $this->parentIdIndex) {
            $result = $this->db->ExecuteScalar("SELECT `$this->parentIdIndex` FROM `$this->tableName` WHERE `$this->idIndex` = $id");
            return $result[$this->parentIdIndex];
        }

        return -1;
    }

    /**
      Идентификатор третьего индекса
     */
    private function GetThirdId($id) {
        if (null != $this->thirdIdIndex) {
            $result = $this->db->ExecuteScalar("SELECT `$this->thirdIdIndex` FROM `$this->tableName` WHERE `$this->idIndex` = $id");
            return $result[$this->thirdIdIndex];
        }

        return -1;
    }

    /**
     * Поднимает запись на одну позицию выше
     *
     * @param int $id идентификатор строки
     * @param int $parentId идентификатор родительской строки
     */
    public function UpRecord($id) {
        echo $id;
        // проверяем данные
        Utility_SafetyUtility::SafeInt($id);

        $whereParent = '1=1';

        if (null != $this->parentIdIndex) {
            $parentId = $this->GetParentId($id);
            $whereParent = "`$this->parentIdIndex` = '$parentId'";
        }

        if (null != $this->thirdIdIndex) {
            $thirdId = $this->GetThirdId($id);
            $whereParent .= " AND `$this->thirdIdIndex` = '$thirdId'";
        }

        // получаем порядковый номер поднимаемой записи
        $result = $this->db->ExecuteScalar("SELECT `Order` FROM `$this->tableName` WHERE `$this->idIndex` = '$id' AND $whereParent");
        $order = $result['Order'];

        $resultmax = $this->db->ExecuteScalar("SELECT MAX(`Order`) AS Max FROM `$this->tableName` WHERE $whereParent AND `Order`<$order");
        if (isset($resultmax['Max'])) {
            $ordermax = $resultmax['Max'];
        } else {
            $ordermax = $order;
        }

        $resultmin = $this->db->ExecuteScalar("SELECT MIN(`Order`) AS Min FROM `$this->tableName` WHERE $whereParent AND `Order`>$order");
        if (isset($resultmin['Min'])) {
            $ordermin = $resultmin['Min'];
        } else {
            $ordermin = $order;
        }
        //Debug($order."-".$ordermax."-".$ordermin);
        // если пытаемся поднять верхнюю запись, то меняем её с нижней
        /* if ($order == 0)
          {
          $last = $this->InsertRecord($parentId) - 1;

          // последнюю переносим в начало
          $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='0' WHERE `$this->idIndex`<>'$id' AND `Order` = '$last' AND $whereParent");

          // текущую переносим в конец
          $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='$last' WHERE `$this->idIndex` = '$id'");
          }
          else
          {
          // поднимаем запись
          $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`=`Order`-1 WHERE `$this->idIndex` = '$id'");

          // опускаем запись которая была выше
          $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`=`Order`+1 WHERE `$this->idIndex`<>'$id' AND `Order` = '".($order - 1)."' AND $whereParent");
          } */
        if ($order == 0) {
            $last = $this->InsertRecord($parentId) - 1;

            // последнюю переносим в начало
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='0' WHERE `$this->idIndex`<>'$id' AND `Order` = '$last' AND $whereParent");

            // текущую переносим в конец
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='$last' WHERE `$this->idIndex` = '$id'");
        } else {
            // поднимаем запись
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='" . $ordermax . "' WHERE `$this->idIndex` = '$id'");

            // опускаем запись которая была выше
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='" . $order . "' WHERE `$this->idIndex`<>'$id' AND `Order` = '" . $ordermax . "' AND $whereParent");
        }
    }

    /**
     * Опускает запись на одну позицию ниже
     * @param int $id
     * @param int $parentId
     */
    public function DownRecord($id) {
        // проверяем данные
        Utility_SafetyUtility::SafeInt($id);

        $whereParent = '1=1';

        if (null != $this->parentIdIndex) {
            $parentId = $this->GetParentId($id);
            $whereParent = "`$this->parentIdIndex` = '$parentId'";
        }

        if (null != $this->thirdIdIndex) {
            $thirdId = $this->GetThirdId($id);
            $whereParent .= " AND `$this->thirdIdIndex` = '$thirdId'";
        }


        // определяем количество записей т.к. мы не должны опускать самую нижнюю запись
        $count = $this->db->GetItemsCount("`$this->tableName`", "WHERE $whereParent");

        // получаем порядковый номер поднимаемой записи
        $result = $this->db->ExecuteScalar("SELECT `Order` FROM `$this->tableName` WHERE `$this->idIndex` = '$id' AND $whereParent");
        $order = $result['Order'];

        $resultmax = $this->db->ExecuteScalar("SELECT MAX(`Order`) AS Max FROM `$this->tableName` WHERE $whereParent AND `Order`<$order");
        if (isset($resultmax['Max'])) {
            $ordermax = $resultmax['Max'];
        } else {
            $ordermax = $order;
        }

        $resultmin = $this->db->ExecuteScalar("SELECT MIN(`Order`) AS Min FROM `$this->tableName` WHERE $whereParent AND `Order`>$order");
        if (isset($resultmin['Min'])) {
            $ordermin = $resultmin['Min'];
        } else {
            $ordermin = $order;
        }
       // wtf($order, false);
        //wtf($count - 1, false);
        // самую нижнюю запись переносим наверх
        if ($order >= $count - 1) {
            //$last = $count - 1;
           // wtf("********************************************", false);
            // сдвигаем все на 1, тем самым освобождаем 0
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`=`Order`+1 WHERE $whereParent");

            // текущую переносим в начало, присваивая 0
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='0' WHERE `$this->idIndex` = '$id'");
        } else {
            // опускаем запись
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='" . $ordermin . "' WHERE `$this->idIndex` = '$id'");

            // поднимаем запись которая была ниже
            $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order`='" . $order . "' WHERE `$this->idIndex`<>'$id' AND `Order` = '" . $ordermin . "' AND $whereParent");
        }
    }

    /**
     * Возвращает позицию, для вставки новой записи
     *
     * @return int
     */
    public function InsertRecord($parentId = null, $thirdId = null) {
        $whereParent = '1=1';

        if (null != $this->parentIdIndex && null != $parentId)
            $whereParent = "`$this->parentIdIndex` = '$parentId'";

        if (null != $this->thirdIdIndex && null != $thirdId)
            $whereParent .= " AND `$this->thirdIdIndex` = '$thirdId'";

        return $this->db->GetItemsCount("`$this->tableName`", "WHERE $whereParent");
    }

    /**
     * Уменьшает значения позиций следующие за удаляемой записью
     *
     * @param int $id
     */
    public function DeleteRecord($id) {
        // проверяем данные
        Utility_SafetyUtility::SafeInt($id);

        $whereParent = '1=1';

        if (null != $this->parentIdIndex) {
            $parentId = $this->GetParentId($id);
            $whereParent = "`$this->parentIdIndex` = '$parentId'";
        }

        if (null != $this->thirdIdIndex) {
            $thirdId = $this->GetThirdId($id);
            $whereParent .= " AND `$this->thirdIdIndex` = '$thirdId'";
        }

        // получаем порядковый номер удаляемой записи
        $result = $this->db->ExecuteScalar("SELECT `Order` FROM `$this->tableName` WHERE `$this->idIndex` = '$id'");
        $order = $result['Order'];

        $this->db->ExecuteQuery("UPDATE `$this->tableName` SET `Order` = `Order`-1 WHERE `Order` > '$order' AND $whereParent");
    }

}

?>