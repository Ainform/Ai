<?php

/**
 * BLL_ImageUploader class
 * Класс для загрузки изображений
 * 
 * @abstract На странице использующей данный класс необходимо реализовать обработчики:
 * handlerBtnUploadFile() и handlerBtnDeleteFile()
 * 
 * @author Frame
 * @version ImageUploader.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class BLL_ImageUploader
{
	/**
	 * Количество загружаемых изображений
	 *
	 * @var int
	 */
	public $imageCount = 1;

	/**
	 * Путь для просмотра изображения
	 *
	 * @var string
	 */
	private $imageSource = '/ImageHandler.php?upload=1&height=150&id=';

	/**
	 * Путь к папке с файлами изображений
	 *
	 * @var string
	 */
	public $imageFolder;
	
	/**
	 * Путь к папке с загруженными изображениями
	 *
	 * @var string
	 */
	public $imageUploadFolder;
	
	/**
	 * Имя временного изображения
	 *
	 * @var string
	 */
	public $imageTempName;

	/**
	 * Имя файла старого изображения
	 *
	 * @var string
	 */
	private $imageOldName;
	
	/**
	 * Признак загруженного изображения
	 *
	 * @var bool
	 */
	private $imageUploaded = false;
	
	/**
	 * Признак обновления изображения
	 *
	 * @var bool
	 */
	private $newImageUploaded = false;

	/**
	 * Конструктор, задает параметры
	 */
	function __construct()
	{
		$this->LoadData();
	}

	/**
	 * Возвращает путь для просмотра изображения
	 *
	 * @return sting
	 */
	public function ImageSource()
	{
		return $this->imageSource.$this->imageTempName;
	}
	
	/**
	 * Возвращает валидатор на JavaScirpt
	 *
	 * @return string
	 */
	public function GetJavaScriptValidator()
	{
		return '<script language="JavaScript">
				function CheckSubmitFile()
				{
					if(!adminForm.userfile.value)
					{
						alert("Выберите файл для загрузки на сервер с помошью кнопки Обзор (Browse)");
						adminForm.userfile.focus();
						adminForm.userfile.select();
						return false;
					}
					else
					{
							validformFile = /(.jpg|.JPG|.jpeg|.JPEG)$/;

			            if(!validformFile.test(adminForm.userfile.value))
			            {
			              alert("Для изображений поддерживается только формат Jpeg!");

			              adminForm.userfile.focus();
			              adminForm.userfile.select();
			              
			              return false;
			          	}
			        }

					return true;
				}
				function CheckDeleteFile()
				{				
					if (!adminForm.txtOldImage.value)
					{
						if (adminForm.txtNewImageUploaded.value)
						{
							return true;
						}

						alert("Изображение не загружено!");
						return false;
					}
					else
					{	
						return confirm("Удалить изображение?");
						return true;
					}
				}
				</script>';
	}
	
	/**
	 * Устанавливает изображение
	 *
	 * @param string $imageOldName имя картинки
	 */
	public function SetImage($imageOldName = null)
	{
		$this->ClearFile();
		
		if (strlen(trim($imageOldName)) > 0)
		{
			$this->imageOldName = $imageOldName;
			$this->imageUploaded = true;
			
			// переносим изображение во временную папку
			Utility_FileUtility::CopyFile($imageOldName, $this->imageFolder, $this->imageUploadFolder, $this->imageTempName);
		}
	}

	/**
	 * Загружает данные после отправки формы
	 */
	public function LoadData()
	{
		$this->newImageUploaded = GetPostValue('txtNewImageUploaded');

		$this->imageOldName = GetPostValue('txtOldImage');
	}

	/**
	 * Сохраняет изображение
	 */
	public function SaveImage()
	{
		if (!file_exists($this->imageUploadFolder.$this->imageTempName) || filesize($this->imageUploadFolder.$this->imageTempName) == 0)
		{
			if (strlen($this->imageOldName) > 0 && file_exists($this->imageFolder.$this->imageOldName))
				Utility_FileUtility::DeleteFile($this->imageFolder.$this->imageOldName);

			return;
		}
		
		
		if (0 < strlen($this->imageOldName) && !$this->newImageUploaded)
			return $this->imageOldName;

		// удаляем старое
		if (strlen($this->imageOldName) > 0 && file_exists($this->imageFolder.$this->imageOldName))
			Utility_FileUtility::DeleteFile($this->imageFolder.$this->imageOldName);

		// переносим новое
		return Utility_FileUtility::MoveFile($this->imageTempName, $this->imageUploadFolder, $this->imageFolder);
	}

	/**
	 * Возвращает форму и изображение
	 *
	 * @return string
	 */
	public function GetForm()
	{
		$sb = $this->GetJavaScriptValidator().'
					<img id="image" src="'.$this->ImageSource().'" alt="Изображение" border="0" />
					<input type="hidden" name="txtOldImage" value="'.$this->imageOldName.'">
					<br>';

		if (!$this->imageUploaded)
			$sb .= '<input type="file" style="width: 100%" name="userfile">
						<br><input type="submit" class="button" name="handlerBtnUploadFile" value="Загрузить" title="Загружает указанный файл на сервер" onclick="return CheckSubmitFile();" />';
		else
			$sb .= '<input type="submit" class="button" name="handlerBtnDeleteFile" value="Удалить" title="Удаление изображения" onclick="return CheckDeleteFile()" />';

		$sb .= '<input type="hidden" name="txtNewImageUploaded" value="'.($this->newImageUploaded ? 1 : 0).'">';

		return $sb;
	}

	/**
	 * Загружает указанное пользователем изображение
	 */
	public function UploadImage()
	{
		// если есть загруженный файл
		if (strlen($_FILES['userfile']['tmp_name']) != 0)
		{
			// указываем о загрузке изображения
			$this->imageUploaded = true;
			$this->newImageUploaded = true;

			// читаем загруженный файл
			$content = Utility_FileUtility::GetUploadedFile($_FILES['userfile']['tmp_name']);

			// сохраняем
			Utility_FileUtility::WriteInToFile($this->imageUploadFolder.$this->imageTempName, $content, 'r+b');
		}
	}

	/**
	 * Очищает файл с изображением
	 */
	public function DeleteImage()
	{
		$this->ClearFile();
	}

	/**
	 * Очищает файл с изображением
	 */
	public function ClearFile()
	{
		Utility_FileUtility::WriteInToFile($this->imageUploadFolder.$this->imageTempName, '');
		$this->newImageUploaded = false;
		$this->imageUploaded = false;
	}
}
?>