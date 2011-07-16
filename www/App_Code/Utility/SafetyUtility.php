<?php

/**
 * Utility_SafetyUtility class
 * Класс для обработки данных, в целях безопасности
 *
 * @author Frame
 * @version SafetyUtility.class.php, v 1.0.1
 * @copyright (c) by Frame
 */

class Utility_SafetyUtility
{
	/**
	 * Экранирует опасные символы для добавления в БД
	 *
	 * @param string $value
	 */
	static function SafeString(&$value)
	{
		if (!get_magic_quotes_gpc())
			$value = mysql_escape_string($value);
		//else
			//Helpers_LogHelper::AddLogEntry('На сайте включены магические ковычки!', false);
	}

	/**
	 * Проверяет входное значение на содержание целого числа
	 *
	 * @param string $value
	 * @return int number
	 */
	static function SafeInt(&$value)
	{
		if (is_null($value) || strlen($value) > 11 || !is_numeric($value)){
			//trigger_error ("Входящая строка '".$value."' несоответсвует формату целого числа.");
                }
                
	}

	/**
	* Проверяет входное значение на содержание дробного числа
	* @param string $value
	* @return double
	*/
	static function SafeDouble($value)
	{
		if (is_null($value) || strlen($value) == 0 || strlen($value) > 16 || $value > 9999999999999999)
			trigger_error ("Входящая строка '".$value."' несоответсвует дробного целого числа.");

		for ($i = 0; $i < strlen($value); ++$i)
		{
			if ($value[$i] == '.')
				continue;
			else if (!is_numeric($value[$i]))
				trigger_error ("Входящая строка '".$value."' несоответсвует формату дробного числа.");
		}

		return $value;
	}
}
?>
