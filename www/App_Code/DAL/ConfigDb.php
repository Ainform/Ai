<?php
/**
 * DAL_ConfigDb class
 * Класс для работы с конфигом в базе
 *
 */

class DAL_ConfigDb extends DAL_BaseDb {
/**
 @var string $TableName Название таблицы
 */
    protected $TableName = "config";

    function configQuery($query = null) {

        return $this->query($query);
    }

    public function configUpdate($newRow) {
        $this->update($newRow);
    }

    function configSelect($where) {

        return $this->selectFirst($where);
    }
}
?>