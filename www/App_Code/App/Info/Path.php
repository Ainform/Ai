<?php

/**
 * App_Info_Path class
 * Класс содержит информацию о путях приложения
 * 
 * @author Frame
 * @version 0.0.1
 * @copyright (c) by VisualDesign
 */

class App_Info_Path
{
	/**
	 * Возвращает путь к папке с программной частью приложения
	 *
	 * @param bool $name вернуть только имя папки
	 * @param bool $endSlash установить последний слеш
	 * @return string
	 */
	public static function AppCode($name = false, $endSlash = true)
	{
		$endPath = ($endSlash) ? PHP_IO::DirSplitter : '';

		if ($name)
			return 'App_Code'.$endPath;
		
		return App_Info::Folder().'App_Code'.$endPath;
	}
}