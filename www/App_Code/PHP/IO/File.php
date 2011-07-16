<?php

/**
 * PHP_IO_File class
 * Класс для управления файлами
 * 
 * @author Frame
 * @version 0.0.1
 * @copyright (c) by VisualDesign
 */

class PHP_IO_File
{

	/**
	 * Копирует файл $sourceFile в файл $destinationFile
	 *
	 * @param string $sourceFile путь к файлу источнику
	 * @param string $destinationFile путь к файлу приемнику
	 */
	public static function Copy($sourceFile, $destinationFile)
	{
		// проверяем наличие файла источника
		if (!self::Exists($sourceFile))
			throw new PHP_IO_Exception('Ошибка при копировании файла. Файл "' . $sourceFile . '" не существует.');

		// удаляем файл приемник, если таковой имеется
		if (self::Exists($destinationFile))
			self::Delete($destinationFile);

		// пытаемся копировать
		if (false === @copy($sourceFile, $destinationFile))
			throw new PHP_IO_Exception('Ошибка при копировании файла. Не удалось скопировать файл "' . $sourceFile . '" в файл "' . $destinationFile . '"');

		return true;
	}


	/**
	 * Перемещает файл $sourceFile в файл $destinationFile
	 *
	 * @param string $sourceFile путь к файлу источнику
	 * @param string $destinationFile путь к файлу приемнику
	 */
	public static function Move($sourceFile, $destinationFile)
	{
		// копируем
		if (true === self::Copy($sourceFile, $destinationFile))
			self::Delete($sourceFile);

		return true;
	}


	/**
	 * Возвращает содержимое файла
	 *
	 * @param string $filePath путь к файлу
	 * @param string $mode режим открытия
	 * @return string
	 */
	public static function Read($filePath, $mode = 'rt')
	{
		if (!self::Exists($filePath))
			throw new PHP_IO_Exception('Ошибка чтения файла "'.$filePath.'". Файл не существует.');

		if (filesize($filePath) == 0)
			return null;

		// открываем файл
		$fp = @fopen($filePath, $mode);

		if (false === $fp)
			throw new PHP_IO_Exception('Ошибка чтения файла '.$filePath.'. Файл невозможно открыть.');

		// читаем содержимое файла
		$content = fread ($fp, filesize($filePath));

		// закрываем файл
		fclose ($fp);

		// фозвращаем содержимое файла
		return $content;
	}


	/**
	 * Возвращает признак существования файла
	 *
	 * @param string $filePath
	 * @return bool
	 */
	public static function Exists($filePath)
	{
		if (file_exists($filePath))
			return true;

		return false;
	}
	
	/**
	 * Удаляет файл
	 *
	 * @param string $filePath
	 * @return bool
	 */
	public static function Delete($filePath)
	{
		// проверяем наличие файла
		if (!self::Exists($filePath))
			throw new PHP_IO_Exception('Ошибка при удалении файла. Файл "' . $filePath . '" не существует.');

		// удаляем файл
		if (false === @unlink($filePath))
			throw new PHP_IO_Exception('Ошибка при удалении файла. Файл "' . $filePath . '" не удалось удалить.');

		return true;
	}
	
	/**
	 * Записывает информацию в файл
	 * 
	 * @param string $filePath путь к файлу
	 * @param string $content информация для записи
	 * @param string $mode режим записи
	 */
	public static function Write($filePath, $content, $mode = 'r+t')
	{
		if (!self::Exists($filePath))
			fclose(fopen($filePath, 'a+b'));

		// открываем файл
		if (!$fp = @fopen($filePath, $mode))
			throw new PHP_IO_Exception('Ошибка чтения файла '.$filePath.'. Файл невозможно открыть.');

		// блокируем файл
		flock($fp, LOCK_EX);

		// очищаем файл
		ftruncate($fp, 0);

		// записываем в файл
		fwrite($fp, $content);

		// снимаем блокировку с файла
		flock($fp, LOCK_UN);

		// закрываем файл
		fclose($fp);
	}
}