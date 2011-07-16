<?php

/**
* BLL_Uploader_Image class
* Класс для загрузки изображений
* @author Frame
* @version Image.php, v 0.1.0
* @copyright (c) by Frame
*/

class BLL_Uploader_Image
{
	/**
	 * Имя папки с изображениями
	 */
	const FileFolder = 'file';


	/**
	 * Имя папки для загрузки изображений
	 */
	const UploadFolder = 'upload';


	/**
	 * Индекс для пути к загруженным файлам
	 */
	const IndexUploadPath = 'UploadImageFolderPath';


	/**
	 * Имя файла с информацией об изображениях
	 */
	const ImageListFile = 'Image.list';
	
	/**
	 * Идентификатор элемента
	 *
	 * @var int
	 */
	protected $itemId;


	/**
	 * Тип изображения
	 *
	 * @var int
	 */
	protected $typeId;


	/**
	 * Путь к папке с загруженными изображениями
	 *
	 * @var string
	 */
	private $uploadFolder;


	/**
	 * Путь к папке с временными файлами
	 *
	 * @var ыекштп
	 */
	private $rootUploadFolder;


	/**
	 * Путь к основной папке с изображениями
	 *
	 * @var string
	 */
	private $imageFolder;


	/**
	 * Конструктор, принимает параметры
	 *
	 * @param int $typeId тип изображения
	 * @param int $itemId идентификатор элемента
	 */
	function __construct($typeId, $itemId)
	{
		if (empty($_SESSION))
			session_start();

		$this->typeId = $typeId;
		$this->itemId = $itemId;

		$this->imageFolder = App_Info::GetFolderPath('file', false);
		$this->rootUploadFolder = App_Info::GetFolderPath('upload', false);
	}


	/**
	 * Создает временную папку для редактирования файлов
	 * 
	 * @param string $imageUploadFolder путь к временной папке
	 */
	public function SetDirectory($imageUploadFolder)
	{
		// создаем имя временной папки для загрузки изображений
		$this->imageUploadFolder = $imageUploadFolder . '_type' . $this->typeId . '_' . $this->itemId;

		// удаляем старые папки
		$this->DeleteOldDirectories();

		// создаем папку для изображений
		$this->CreateFolder();

		// устанавливает папку в сессии
		$this->SetSession();
		
		// устанавливаем пустой список
		$this->SetList(array());
	}


	/**
	 * Удаляет старые папки
	 */
	private function DeleteOldDirectories()
	{
		$files = PHP_IO_Folder::GetList($this->rootUploadFolder);

		foreach ($files as $file)
			// если это папка и время жизни больше 3 часов, то удаляем
			if ($file->IsFolder() && (time() - $file->ModificationTime() > 5400))
				PHP_IO_Folder::Delete($file->Path());
	}


	/**
	 * Создает папку для изображений
	 */
	private function CreateFolder()
	{
		// полный путь к временной папке
		$imageUploadFolder = $this->rootUploadFolder . PHP_IO::DirSplitter . $this->imageUploadFolder;

		// если папки нет, то создаем
		if (!PHP_IO_Folder::Exists($imageUploadFolder))
			PHP_IO_Folder::Create($imageUploadFolder);
		else if (!IsPostBack()) // иначе, если не PostBack, очищаем папку
			PHP_IO_Folder::Clear($imageUploadFolder);
	}

	/**
	 * Устанавливает папку в сессии
	 */
	private function SetSession()
	{
		// проверяем на наличие переменной сессии
                //FIXME сейшн регистер не работает в пхп 5.3
		//if (!session_is_registered(self::IndexUploadPath))
			//session_register(self::IndexUploadPath);

		// запоминаем имя папки в сессии
		$_SESSION[self::IndexUploadPath] = $this->rootUploadFolder . PHP_IO::DirSplitter . $this->imageUploadFolder;
	}

	/**
	 * Создает форму для загрузки файлов
	 * 
	 * @return string
	 */
	public function GetForm()
	{
		$this->CopyFilesToEdit();

		// создаем путь к папке с данным компонентом
		$url = App_Info_Url::AppCode() . 'BLL' . PHP_Url::AddressSplitter . 'Uploader' . PHP_Url::AddressSplitter;

		return '<script language="javascript" src="' . $url . 'uploader.js"></script>
				<form id="uploadForm" method="post" enctype="multipart/form-data" action="' . $url . 'upload.php" target="uploadProcessor">
					<table width="100%" cellpadding="0" cellspacing="0" class="admin_table">
						<tr>
							<td width="50">Название:&nbsp;</td>
							<td colspan="2">
								<input id="txtIName" type="text" name="txtIName" value="" style="width: 100%" />
							</td>
						</tr>
						<tr>
							<td width="50">Файл:&nbsp;</td>
							<td>
								<input id="uploader" name="uploader" type="file" />
							</td>
							<td width="100">
								<input id="upload_submit" name="upload_submit" value="Загрузить" type="button" onClick="return uploadFile(this)" />
							</td>
						</tr>
					</table>
					<img src="/admin/img/0.gif" width="500" height="2" /><br />
					<span id="uploadProcess"></span>
					<span id="uploadResult"></span>
					<input type="hidden" id="directory" value="' . $url . '">
					<iframe name="uploadProcessor" src="' . $url . 'upload.php" width="100%" height="300" style="display: none"></iframe>
				</form>
				<script language="javascript">
				//<!--
					UpdatePictures();
				//-->
				</script>';
	}


	/**
	 * Копирует изображения во временную папку для редктирования
	 */
	public function CopyFilesToEdit()
	{
		if (is_null($this->itemId))
			return;

		$db = new DAL_ImagesDb($this->typeId);
			$images = $db->GetForItem($this->itemId);
		unset($db);

		// сохраняем список
		$this->SetList($images);

		if (0 == count($images))
			return;

		foreach ($images as $image)
		{
			$sourceFile = $this->imageFolder . PHP_IO::DirSplitter . $image['FileName'];
			$destinationFile = $this->rootUploadFolder . PHP_IO::DirSplitter . $this->imageUploadFolder . PHP_IO::DirSplitter . $image['FileName'];

			try
			{
				PHP_IO_File::Copy($sourceFile, $destinationFile);
			}
			catch (PHP_IO_Exception $ex)
			{
				Helpers_LogHelper::LogException($ex);
			}
		}
	}


	/**
	 * Сохраняет изображения для элемента
	 *
	 * @param int $itemId идентификатор элемента
	 */
	public function Save($itemId = null)
	{
		if (null == $this->itemId)
			$this->itemId = $itemId;

		// определяем папку в сессии
		$uploadFolder = $_SESSION[self::IndexUploadPath];

		$db = new DAL_ImagesDb($this->typeId);

		// получаем старые из БД
		$oldImages = $db->GetForItem($itemId);

		// получаем файлы
		$newImages = self::GetList();

		$updateImages = array();
		
		// проходим по новым
		foreach ($newImages as $newImageKey => $newImage)
		{
			foreach ($oldImages as $oldImageKey => $oldImage)
			{
				if ($newImage['FileName'] == $oldImage['FileName'])
				{
					// запоминаем, для обновления позиций
					$updateImages[] = $newImage;
					
					// удаляем из обоих
					unset($newImages[$newImageKey]);
					unset($oldImages[$oldImageKey]);
				}
			}
		}


		// удаляем старые
		foreach ($oldImages as $oldImage)
		{
			$db->Delete($oldImage['ImageId']);

			try
			{
				PHP_IO_File::Delete($this->imageFolder . PHP_IO::DirSplitter . $oldImage['FileName']);
			}
			catch (PHP_IO_Exception $ex)
			{
				Helpers_LogHelper::LogException($ex);
			}
		}

		// обновляем оставшиеся
		foreach ($updateImages as $updateImage)
		{
			$db->UpdateImage($updateImage['ImageId'], $updateImage['Order'], $updateImage['Title']);
		}

		// добавляем новые
		foreach ($newImages as $newImage)
		{
			try
			{
				PHP_IO_File::Move($uploadFolder . PHP_IO::DirSplitter . $newImage['FileName'], $this->imageFolder . PHP_IO::DirSplitter . $newImage['FileName']);
				$db->AddImage($this->itemId, $newImage['FileName'], $newImage['Order'], $newImage['Title']);
			}
			catch (PHP_IO_Exception $ex)
			{
				Helpers_LogHelper::LogException($ex);
			}
		}

		unset($db);
	}


	/**
	 * Возвращает сохраненный список файлов
	 *
	 * @return array
	 */
	public static function GetList()
	{
		// определяем папку в сессии
		$uploadFolder = $_SESSION[self::IndexUploadPath];

		try
		{
			$object = PHP_IO_File::Read($uploadFolder . PHP_IO::DirSplitter . self::ImageListFile);
		}
		catch (PHP_IO_Exception $ex)
		{
			Helpers_LogHelper::LogException($ex);
		}

		$imageList = unserialize($object);

		return $imageList;
	}
	
	/**
	 * Устанавливает новый список файлов
	 *
	 * @param array $imageList список файлов
	 */
	public static function SetList($imageList)
	{
		// сериализуем список
		$object = serialize($imageList);

		// определяем папку в сессии
		$uploadFolder = $_SESSION[self::IndexUploadPath];

		try
		{
			PHP_IO_File::Write($uploadFolder . PHP_IO::DirSplitter . self::ImageListFile, $object);
		}
		catch (PHP_IO_Exception $ex)
		{
			Helpers_LogHelper::LogException($ex);
		}
	}

}
?>