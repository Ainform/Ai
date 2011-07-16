<?php

/**
 * Utility_FileUtility class
 * Класс для работы с файлами и каталогами
 *
 * @author Frame
 * @version FileUtility.class.php, v 1.1.1
 * @copyright (c) by VisualDesign
 */

class Utility_FileUtility
{
	/**
	 * Удаляет папку со всем содержимым
	 *
	 * @param string $folderPath путь к папке
	 */
	public static function DeleteFolder($folderPath)
	{
		if (!is_dir($folderPath))
			return;

		self::ClearFolder($folderPath);

		if (is_dir($folderPath))
			rmdir($folderPath);
	}

	/**
	 * Очищает папку
	 *
	 * @param string $folderPath путь к папке для очистки
	 */
	public static function ClearFolder($folderPath)
	{
		if (!is_dir($folderPath))
			return;

		// открываем каталог и начинаем считывать его содержимое
		if ($directory = opendir($folderPath))
		{
			while (($file = readdir($directory)) !== false)
				if (($file != ".") && ($file != ".."))
				{
					// если это файл
					if (is_file($folderPath.'/'.$file))
					{
						if (file_exists($folderPath.'/'.$file))
						{
							if (!unlink($folderPath.'/'.$file))	// удаляем файл
							{
								trigger_error("Ошибка при удалении файла! Файл '".$folderPath.'/'.$file."'");
							}
						}
					} // если это папка
					else if (is_dir($folderPath.'/'.$file))
						self::DeleteFolder($folderPath.'/'.$file);
				}

			closedir($directory);
		}
	}

	/**
	 * Возвращает имена файлов из папки
	 *
	 * @param string $dirName путь к папке
	 */
	public static function GetFileNamesFromDirectory($dirName)
	{
		// получаем имена файлов в папке
		$files = glob($dirName.'*');

		$filesRet = array();

		if (is_array($files) && count($files) > 0)
			foreach ($files as $file)
			{
				$filesRet[] = str_replace($dirName, '', $file);
			}

		return $filesRet;
	}

	/**
	 * Считывает и возвращает содержимое файла
	 *
	 * @param string $filePath путь к файлу
	 * @param string $mode режим чтения
	 * @return string
	 */
	public static function ReadFile($filePath, $mode = 'rt')
	{
		// очищаем кеш с информацией о файле
		clearstatcache();

		if (!file_exists($filePath))
			trigger_error('Ошибка чтения файла '.$filePath.'. Файл не существует.');

		if (filesize($filePath) == 0)
			return null;

		// открываем файл
		if (!$fp = @fopen($filePath, $mode))
			trigger_error('Ошибка чтения файла '.$filePath.'. Файл невозможно открыть.');

		// читаем содержимое файла
		$content = fread ($fp, filesize($filePath));

		// закрываем файл
		fclose ($fp);

		// фозвращаем содержимое файла
		return $content;
	}

	/**
	 * Записывает информацию в файл
	 *
	 * @param string $filePath путь к файлу
	 * @param string $content информация для записи
	 * @param string $mode режим записи
	 */
	public static function WriteInToFile($filePath, $content, $mode = 'r+t')
	{
		// очищаем кеш с информацией о файле
		clearstatcache();

		if (!file_exists($filePath))
			fclose(fopen($filePath, 'a+b'));

		// открываем файл
		if (!$fp = @fopen($filePath, $mode)){
                        //FIXME
			//throw new FileSystemException('Ошибка чтения файла '.$filePath.'. Файл невозможно открыть.');
                }
                else{
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

	/**
	 * Сериализует объект и записывает его в файл
	 *
	 * @param object $object объект для записи
	 * @param string $filePath путь к файлу
	 */
	public static function SerializeObjectToFile($object, $filePath)
	{
		// сериализуем данные
		$string = serialize($object);

		// упаковываем со средней степенью копрессии
		$data = gzcompress($string, 5);

		// записываем в файл
		self::WriteInToFile($filePath, $data, 'r+b');
	}

	/**
	 * Десериализует объект из файла
	 *
	 * @param string $filePath путь к файлу
	 * @return object
	 */
	public static function UnserializeObjectFromFile($filePath)
	{
		// считываем данные из файла
		$data = self::ReadFile($filePath, 'rb');

		// если есть данные
		if (strlen($data) > 0)
		{
			// распаковываем
			$string = gzuncompress($data);

			// десериализуем и возвращаем
			return unserialize($string);
		}

		return null;
	}

	/**
	 * Возвращает массив байтов из файла, загруженного пользователем
	 *
	 * @param string $filePath путь к файлу
	 * @return string
	 */
	public static function GetUploadedFile($filePath)
	{
		return self::ReadFile($filePath, 'r+b');
	}

	/**
	 * Удаляет файлы из списка
	 *
	 * @param array $files список файлов
	 * @param string $folder путь к папке с файлами
	 */
	public static function DeleteFiles($files, $folder)
	{
		if (is_array($files))
			foreach ($files as $file)
				self::DeleteFile($folder.$file);
	}

	/**
	 * Копирует файлы из списка из папки $fromFolder в папку $toFolder
	 *
	 * @param array $files список файлов для копирования
	 * @param string $fromFolder
	 * @param string $toFolder
	 */
	public static function CopyFiles($files, $fromFolder, $toFolder)
	{
		if (0 == count($files))
			return;

		// проходим по списку
		foreach ($files as $file)
			if (is_file($fromFolder.$file))		// проверяем файл откуда копируем
				self::CopyFile($file, $fromFolder, $toFolder, $file); // копируем
	}

	/**
	 * Копирует файл из папки $fromFolder в папку $toFolder и если $newFileName не задано, то создает новое имя для файла
	 *
	 * @param string $fileName имя файла
	 * @param string $fromFolder папка-источник
	 * @param string $toFolder папка-приемник
	 * @param string $newFileName имя файла, в который производится копирование
	 * @return string
	 */
	public static function CopyFile($fileName, $fromFolder, $toFolder, $newFileName = null)
	{
		// если файл не существует
		if (!file_exists($fromFolder.$fileName))
			return;

		// если он существует, но имеет нулевой размер
		if (filesize($fromFolder.$fileName) == 0)
			return;

		// имя файла для сохранения
		$newFileName = (!is_null($newFileName)) ? $newFileName : self::GenerateUniqueFileName('file_');

		// в папке-приемнике есть файл с таким же именем, удаляем его
		if (file_exists($toFolder.$newFileName))
			self::DeleteFile($toFolder.$newFileName);

		if (!copy($fromFolder.$fileName, $toFolder.$newFileName))
                    trigger_error ('Ошибка при копировании файла "'.$fileName.'" из папки "'.$fromFolder.'" в папку "'.$toFolder.'" под именем "'.$newFileName.'"');

		return $newFileName;
	}

	/**
	 * Перемещает файл из папки $fromFolder в папку $toFolder и если задано, то создает новое имя для файла
	 *
	 * @param string $fileName имя файла
	 * @param string $fromFolder папка-источник
	 * @param string $toFolder папка-приемник
	 * @param string $newFileName имя файла, в который производится копирование
	 * @return string
	 */
	static function MoveFile($fileName, $fromFolder, $toFolder, $newFileName = null)
	{
		// если скопировали
		$newFileName = self::CopyFile($fileName, $fromFolder, $toFolder, $newFileName);

		self::DeleteFile($fromFolder.$fileName);

		return $newFileName;
	}

	/**
	 * Создает уникальное имя для файла
	 *
	 * @param string $prefix префикс для файла
	 * @return string
	 */
	public static function GenerateUniqueFileName($prefix)
	{
		return uniqid($prefix, true);
	}

	/**
	 * Возвращает расширение файла
	 *
	 * @param string $fileName имя файла
	 * @return string
	 */
	public static function GetFileExtension($fileName)
	{
		if (strlen($fileName) == 0)
			return null;

		// переводим в нижний регистр
		$fileName = strtolower($fileName);

		// разбираем в массив
		$fileNameArray = explode('.', $fileName);

		// возвращаем последнюю часть имени файла
		return $fileNameArray[count($fileNameArray)-1];
	}

	/**
	 * Удаляет файл с диска
	 *
	 * @param string $filePath путь к файлу
	 * @param bool $existError признак выдачи ошибки при невозможности удаления
	 */
	public static function DeleteFile($filePath, $existError = false)
	{
		// если файл существует, то пробуем удаляем
		if (file_exists($filePath) && !@unlink($filePath))
		{
			if($existError)
				throw new FileSystemException('Ошибка при удаления файла "'.$filePath.'"');
		}
		else
		{
			if($existError)
				throw new FileSystemException('Ошибка при удаления файла. Файл "'.$filePath.'" отсутствует.');
		}
	}

	/**
	 * Удаляет файлы из директории
	 *
	 * @param string $dirName путь к папке
	 * @return bool
	 */
	public static function DeleteFilesFromDirectory($dirName)
	{
		if (!is_dir($dirName))
			return false;

		// получаем имена файлов в папке
		$files = glob($dirName.'/*');

		if (is_array($files) && count($files) > 0)
			foreach ($files as $file)
			{
				// если это файл, то удаляем
				if (file_exists($file))
					self::DeleteFile($file);
			}

		return true;
	}

	/**
	 * Копирует папку $fromFolder в папку $toFolder
	 *
	 * @param string $fromFolder
	 * @param string $toFolder
	 * @return void
	 */
	public static function CopyFolder($fromFolder, $toFolder)
	{
		if (!is_dir($fromFolder))
			throw new FileSystemException('Ошибка при копировании. Папка '.$fromFolder.' отсутствует.');

		if (!is_dir($toFolder))
			mkdir($toFolder);

		// получаем имена файлов в папке
		$files = glob($fromFolder.'/*');

		// если нет файлов, то выходим
		if (!is_array($files) || 0 == count($files))
			return;

		foreach ($files as $file)
		{
			// получаем имя файла или папки
			$name = str_replace($fromFolder.'/', '', $file);

			// копируем файл
			if (is_file($file))
				self::CopyFile($name, $fromFolder.'/', $toFolder.'/', $name);
			// копируем папку
			else if (is_dir($file) && $file != '..' && $file != '.')
				self::CopyFolder($fromFolder.'/'.$name, $toFolder.'/'.$name);
		}
	}
}
?>