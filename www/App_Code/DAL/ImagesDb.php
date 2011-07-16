<?php

/**
 * DAL_ImagesDb class
 * Класс для работы с изображениями в БД
 *
 * @author Frame
 * @version ImagesDb.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class DAL_ImagesDb extends DAL_BaseDb
{
	/**
		@var string $TableName Название таблицы
	*/
	protected $TableName = "images";

	/**
		Возвращает данные о структуре таблицы в виде название колонки -> тип колонки

		@return array структура таблицы
	*/
	protected function getStructure()
	{
		return array(
				"ImageId" => "int",
				"Folder" => "string",
				"FileName" => "string",
				"Title" => "string",
				"Order" => "int",
				"Width" => "int",
				"Height" => "int",
				"FileSize" => "int"
				);
	}

	/**
		Возвращает первичные ключи таблицы

		@return array ключи таблицы
	*/
	protected function getKeys()
	{
		return array("ImageId");
	}

	/**
		@return array автоинкрементные индексы таблицы
	*/
	protected function getIndexes()
	{
		return array("ImageId");
	}

	/**
	 * Констуктор, инициализирует соединение
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Возвращает данные одного изображения
	 *
	 * @param int $id идентификатор изображения
	 * @return array
	 */
	public function Get($id)
	{
		$rows = $this->select(array("ImageId" => $id));

		if (count($rows) > 0)
			return $rows[0];
	}

	/**
	 * Возвращает данные изображений для одного элемента
	 *
	 * @param string $folder Необходимая папка
	 * @return array
	 */
	public function GetFromFolder($folder)
	{
		return $this->select(array("Folder" => strtolower($folder)), "Order");
	}

	/**
	 * Возвращает одно изображение для одного элемента
	 *
	 * @param int $folder идентификатор элемента
	 * @return array
	 */
	public function GetTopFromFolder($folder)
	{
		$rows = $this->select(array("Folder" => strtolower($folder)), "ImageId","DESC");

		if (count($rows) > 0)
			return $rows[0];
		else
			return null;
	}

	/**
		Перемещаем картинки
	*/
	public function MoveImages($fromFolder, $toFolder)
	{
		// очищаем названия папок
		$toFolder = $this->db->Escape($toFolder);
		$fromFolder = $this->db->Escape($fromFolder);

		// получаем изображения
		$images = $this->GetFromFolder($fromFolder);

		//Debug(var_dump($fromFolder));

		//if (!isset($images) || count($images) == 0)
			//return;

		// обновляем папки в базе
		$query = "UPDATE `$this->TableName` SET `Folder` = '$toFolder' WHERE `Folder` = '$fromFolder'";

		$this->db->ExecuteScalar($query);

		$fromFolder = Helpers_PathHelper::GetFullPath('upload').$fromFolder.'/';
		$toFolder = Helpers_PathHelper::GetFullPath('upload').$toFolder.'/';

		if (!file_exists($toFolder))
			mkdir($toFolder, 0777);

		// переносим файлы
		foreach ($images as $img)
		{
			$img_file = $img['FileName'];

			//echo $img_file."---".$fromFolder."---". $toFolder."---". $img_file;
			//die();

			Utility_FileUtility::MoveFile($img_file, $fromFolder, $toFolder, $img_file);
		}

		$mydir = opendir($fromFolder);

		while(false !== ($file = readdir($mydir))) {
			if(!is_dir($file)&&is_file($file)&&$file != "." && $file != "..") {
				//chmod($fromFolder.$file, 0777);

				Utility_FileUtility::MoveFile($file, $fromFolder, $toFolder, $file);
			}
		}

		closedir($mydir);
	}

	/**
	 * Удаляет изображение
	 *
	 * @param int $id идентификатор изображения
	 */
	public function DeleteImage($id)
	{
		// получаем файл изображения
		$img = $this->Get($id);

		$img_file = Helpers_PathHelper::GetFullPath('upload').$img['Folder'].'/'.$img['FileName'];

		// удаляем файл картинки, если ссылок в базе на него больше нет
		$eqImages = $this->select(array("Folder" => $img['Folder'], "FileName" => $img['FileName']));

		if (count($eqImages) < 2)
			@unlink($img_file);

		// удаляем запись из таблицы
		$this->Order()->DeleteRecord($id);
		$this->delete(array("ImageId" => $id));
	}

	/**
	 * Удаляет изображения из папки
	 *
	 * @param string $folder папка
	 */
	public function DeleteFolder($folder)
	{
		$rows = $this->GetFromFolder($folder);

		// очищаем папку с файлами
		$folderPath = Helpers_PathHelper::GetFullPath('upload').$folder.'/';
		Utility_FileUtility::ClearFolder($folderPath);

		$this->delete(array("Folder" => strtolower($folder)));
	}

	/**
	 * Добавляет изображение
	 *
	 * @param array $imageRow данные об изображении
	 */
	public function AddImage($imageRow)
	{
		$imageRow['Order'] = $this->Order()->InsertRecord($imageRow['Folder']);
		$this->insert($imageRow);

		return $this->db->GetLastId();
	}


	/**
	 * Обновляет изображение
	 *
	 * @param array $imageRow данные об изображении
	 */
	public function UpdateImage($imageRow)
	{
		$this->update($imageRow);
	}

	/**
		Возвращает ссылку на изображение
	*/
	public static function GetImagePath($imageRow)
	{
		return "/upload/".$imageRow['Folder'].$imageRow['FileName'];
	}

        /**
	 * Возвращает экземпляр класса OrderHelper
	 *
	 * @return Helpers_OrderHelper
	 */
	private function Order()
	{
		$orderHelper = new Helpers_OrderHelper();
		$orderHelper->SetInfo('images', 'ImageId', 'Folder');

		return $orderHelper;
	}

	/**
	 * Поднимает изображение
	 *
	 * @param int $imageId идентификатор изображения
	 */
	public function Up($imageId)
	{
		$this->Order()->UpRecord($imageId);
	}

	/**
	 * Опускает изображение
	 *
	 * @param int $imageId идентификатор изображения
	 */
	public function Down($imageId)
	{
		$this->Order()->DownRecord($imageId);
	}
}
