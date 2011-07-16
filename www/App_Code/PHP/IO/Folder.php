<?php

/**
 * PHP_IO_Folder class
 * Класс для управления папками
 * 
 * @author Frame
 * @version 0.0.1
 * @copyright (c) by VisualDesign
 */

class PHP_IO_Folder
{
	/**
	 * Возвращает список содержимого папки
	 * 
	 * @param string $folderPath путь к папке
	 * @return array of PHP_IO_Info
	 */
	public static function GetList($folderPath)
	{
		$list = array();

		// получаем список файлов и папок
		$files = glob($folderPath . PHP_IO::DirSplitter . '*');

		if (false === $files)
			return $list;
		
		// заносим в массив
		foreach ($files as $file)
			$list[] = new PHP_IO_Info($file);

		return $list;
	}

	/**
	 * Удаляет папку
	 *
	 * @param string $folderPath путь к папке
	 * @return bool
	 */
	public static function Delete($folderPath)
	{
		// очищаем папку
		self::Clear($folderPath);

		if (false === rmdir($folderPath))
			throw new PHP_IO_Exception('Ошибка при удалении папки "' . $folderPath . '"');

		return true;
	}


	/**
	 * Очищает папку
	 *
	 * @param string $folderPath путь к папке
	 * @return bool
	 */
	public static function Clear($folderPath)
	{
		// получаем список файлов
		$files = self::GetList($folderPath);

		foreach ($files as $file)
		{
			if ($file->IsFile())
				PHP_IO_File::Delete($file->Path());
			else
				self::Delete($file->Path()); //echo $file->Path().'<br>';//
		}

		return true;
	}
	
	/**
	 * Возвращает признак существования папки
	 *
	 * @param string $folderPath путь к папке
	 * @return bool
	 */
	public static function Exists($folderPath)
	{
		if (is_dir($folderPath))
			return true;

		return false;
	}


	/**
	 * Создает папку
	 *
	 * @param string $folderPath путь к папке
	 * @return bool
	 */
	public static function Create($folderPath)
	{
		if (self::Exists($folderPath))
			throw new PHP_IO_Exception('Ошибка при создании папки "' . $folderPath . '". Папка уже существует');
		
		mkdir($folderPath);
		
		return true;
	}
}