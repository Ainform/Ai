<?php

/**
 * DAL_ArrayOrder class
 * Класс для управления позициями записей в массиве
 * 
 * @author Frame
 * @version 0.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_ArrayOrder
{

	/**
	 * Индекс поля сортировки в массиве
	 * 
	 * @var string
	 */
	private $sortIndex;


	/**
	 * Массив записей
	 *
	 * @var array
	 */
	private $array;


	/**
	 * Конструктор, устанавливает параметры
	 *
	 * @param array $array массив записей
	 * @param string $sortIndex индекс поля сортировки в массиве
	 */
	public function __construct($array, $sortIndex)
	{
		$this->array = $array;
		$this->sortIndex = $sortIndex;
	}
	

	/**
	 * Поднимает запись
	 *
	 * @param int $order номер позиции
	 */
	public function UpRecord($order)
	{
		if (0 == $order)
			return $this->array;

		// номера позиций
		$up = $order;
		$upIndex = null;

		$down = $order - 1;
		$downIndex = null;


		foreach ($this->array as $key => $row)
		{
			if ($up == $row[$this->sortIndex])
				$upIndex = $key;

			if ($down == $row[$this->sortIndex])
				$downIndex = $key;

			// поднимаем запись
			if (null != $upIndex && null != $downIndex)
			{
				$temp = $this->array[$downIndex];
				$this->array[$downIndex] = $this->array[$upIndex];
				$this->array[$upIndex] = $temp;

				break;
			}
		}

		return $this->array;
	}


	/**
	 * Опускает запись
	 *
	 * @param int $order номер позиции
	 */
	public function DownRecord($order)
	{
		if (count($this->array) - 1 == $order)
			return $this->array;

		// номера позиций
		$up = $order + 1;
		$upIndex = null;

		$down = $order;
		$downIndex = null;


		foreach ($this->array as $key => $row)
		{
			if ($up == $row[$this->sortIndex])
				$upIndex = $key;

			if ($down == $row[$this->sortIndex])
				$downIndex = $key;

			// поднимаем запись
			if (null != $upIndex && null != $downIndex)
			{
				$temp = $this->array[$downIndex];
				$this->array[$downIndex] = $this->array[$upIndex];
				$this->array[$upIndex] = $temp;

				break;
			}
		}

		return $this->array;
	}


	/**
	 * Удлаяет запись
	 *
	 * @param int $order номер позиции
	 */
	public function DeleteRecord($order)
	{
		foreach ($this->array as $key => $row)
		{
			// нашли совпадение
			if ($order == $row[$this->sortIndex])
				unset($this->array[$key]);
			
			// уменьшаем позиции
			if ($order < $row[$this->sortIndex])
			{
				$this->array[$key][$this->sortIndex] = $row[$this->sortIndex] - 1;
			}
		}

		return $this->array;
	}


	/**
	 * Возвращает номер позиции для новой записи
	 *
	 * @return int
	 */
	public function InsertRecord()
	{
		$position = 0;

		foreach ($this->array as $key => $row)
		{
			if ($row[$this->sortIndex] > $position)
				$position = $row[$this->sortIndex];
		}

		return $position + 1;
	}
}