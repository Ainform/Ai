<?php

/**
 * PHP_IO_Info class
 * Класс содержащий информацию об объекте
 * 
 * @author Frame
 * @version 0.0.2
 * @copyright (c) by VisualDesign
 */

class PHP_IO_Info
{
	/**
	 * Имя файла
	 *
	 * @var string
	 */
	protected $name;


	/**
	 * Путь к файлу
	 *
	 * @var string
	 */
	protected $path;


	/**
	 * Дата последнего изменения файла
	 *
	 * @var string
	 */
	protected $modificationTime;


	/**
	 * Путь к папке с файлом
	 *
	 * @var string
	 */
	protected $folderPath;


	/**
	 * Размер файла
	 *
	 * @var int
	 */
	protected $size = 0;


	/**
	 * Признак файла
	 *
	 * @var bool
	 */
	protected $isFile = false;


	/**
	 * Признак папки
	 *
	 * @var bool
	 */
	protected $isFolder = false;


	/**
	 * Конструктор, принимает путь к объекту
	 *
	 * @param string $path путь к объекту
	 */
	public function __construct($path)
	{
		// проверяем наличие объекта
		if (!PHP_IO_File::Exists($path))
			throw new PHP_IO_Exception('Ошибка при получении информации о файле. Объект "' . $path . '" не существует.');

		// устанавливаем признак объекта
		if (is_file($path))
			$this->isFile = true;
		else if (is_dir($path))
			$this->isFolder = true;
		else
			throw new PHP_IO_Exception('Неизвестный объект "' . $path .'"');

		$this->path = $path;
		$this->modificationTime = filemtime($path);
		$this->folderPath = dirname($path);
		$this->name = basename($path);
		
		if ($this->isFile)
			$this->size = filesize($path);
	}


	/**
	 * Возвращает имя объекта
	 *
	 * @return string
	 */
	public function Name()
	{
		return $this->name;
	}


	/**
	 * Возвращает путь к объекту
	 *
	 * @return string
	 */
	public function Path()
	{
		return $this->path;
	}


	/**
	 * Возвращает дату последнего изменения объекта
	 *
	 * @return int
	 */
	public function ModificationTime()
	{
		return $this->modificationTime;
	}


	/**
	 * Возвращает путь к папке с объектом
	 *
	 * @return string
	 */
	public function FolderPath()
	{
		return $this->folderPath;
	}


	/**
	 * Возвращает размер файла
	 *
	 * @return string
	 */
	public function Size()
	{
		return $this->size;
	}


	/**
	 * Возвращает признак файла
	 *
	 * @return bool
	 */
	public function IsFile()
	{
		return $this->isFile;
	}
	
	/**
	 * Возвращает признак папки
	 *
	 * @return bool
	 */
	public function IsFolder()
	{
		return $this->isFolder;
	}
}