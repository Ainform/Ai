<?php

/**
 * PHP_Timer class
 * Класс для подсчета времени выполнения скриптов
 * 
 * @author Frame
 * @version Timer.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class PHP_Timer
{
	/**
	 * Стартовое время
	 *
	 * @var int
	 */
	private static $startTime = 0;

	/**
	 * Список остановок таймера
	 *
	 * @var array
	 */
	private static $times = array();
	
	/**
	 * Запускает таймер
	 *
	 */
	public static function Start()
	{
		if (0 != self::$startTime)
			trigger_error('Таймер уже запущен');

		self::$startTime = (defined('START_TIME')) ? START_TIME : microtime(true);
	}
	
	/**
	 * Сохраняет отметку времени
	 *
	 * @param string $note информационное сообщение о том, где ставится отметка
	 */
	public static function NoteTime($note)
	{
		self::$times[$note] = sprintf("%.5f", microtime(true) - self::$startTime);
		self::$startTime = microtime(true);
	}
	
	/**
	 * Останавливает таймер
	 *
	 */
	public static function StopTime()
	{
		if (0 == self::$startTime)
			trigger_error('Таймер уже остановлен');
		
		self::NoteTime('Остановка таймера');
		self::$startTime = 0;
	}
	
	/**
	 * Возвращает информацию о запусках и времени
	 *
	 */
	public static function GetTimes()
	{
		$result = null;
		
		foreach (self::$times as $note => $time)
			$result .= $note.' ['.$time.']<br />';

		return '<p align="center">'.$result.'</p>';
	}
}