<?php

/**
 * App_Info_Url class
 * Класс содержит информацию о адресах приложения
 * 
 * @author Frame
 * @version 0.0.1
 * @copyright (c) by VisualDesign
 */

class App_Info_Url
{
	/**
	 * Возвращает адрес к папке с программной частью приложения
	 *
	 * @param bool $name вернуть только имя папки
	 * @param bool $endSlash установить последний слеш
	 * @return string
	 */
	public static function AppCode($name = false, $endSlash = true)
	{
		$endPath = ($endSlash) ? PHP_Url::AddressSplitter : '';

		if ($name)
			return 'App_Code'.$endPath;

		return App_Info::Url().'App_Code'.$endPath;
	}
}