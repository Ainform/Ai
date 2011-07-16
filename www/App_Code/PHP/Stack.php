<?php

/**
 * PHP_Stack class
 * Класс для эмуляции стека
 * 
 * @author Frame
 * @version Stack.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class PHP_Stack
{
	/**
	 * Массив, эмулирующиц стек
	 *
	 * @var array
	 */
	private $stack = array();
	
	/**
	 * Счетчик элементов в стеке
	 *
	 * @var int
	 */
	private $count = 0;
	
	/**
	 * Добавляет объект в стек
	 *
	 * @param object $object
	 */
	public function Push($object)
	{
		// добавляем объект в стек
		$this->stack[$this->count] = $object;
		
		$this->count++;
	}
	
	/**
	 * Извлекает объект их стека по принципу "последним зашел - первым вышел"
	 * 
	 * @return object
	 */
	public function Pop()
	{
		if ($this->count > 0)
		{
			$object = $this->stack[$this->count - 1];
			unset($this->stack[$this->count - 1]);

			$this->count--;
			
			return $object;
		}
		
		return null;
	}
	
	/**
	 * Возвращает количество элементов в стеке
	 *
	 * @return int
	 */
	public function Count()
	{
		return $this->count;
	}
	
}
?>