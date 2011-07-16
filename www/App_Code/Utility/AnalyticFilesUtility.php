<?php

/**
 * Utility_FilesUtility class
 * Класс для работы с размещением изображений на сайте
 *
 * @author Frame
 * @version FilesUtility.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class Utility_AnalyticFilesUtility
{
	/**
	 * Индекс для пути к загруженным файлам
	 */
	const IndexUploadPath = 'UploadFolderPath';

	const FileNamesSplitter = '|||';

	/**
	 * Путь к папке с файлами изображений
	 *
	 * @var string
	 */
	public $FilesFolder;
	public $FilesFolderUrl;

	/**
	 * Путь к папке с загруженными изображениями
	 *
	 * @var string
	 */
	public $filesUploadFolder;

	/**
	 * Путь к корневой папке с загруженными изображениями
	 *
	 * @var string
	 */
	public $filesRootUploadFolder;
	public $filesRootUploadFolderUrl;

	function __construct()
	{
            //Debug("test");
		$this->filesFolder = Helpers_PathHelper::GetFullPath('upload');
		$this->filesRootUploadFolder = Helpers_PathHelper::GetFullPath('upload');

		$this->filesFolderUrl = str_replace(Helpers_PathHelper::GetFullUrl('root'), '/', Helpers_PathHelper::GetFullUrl('file'));
		$this->filesRootUploadFolderUrl = Helpers_PathHelper::GetFullUrl('upload');
		//$this->SetDirectory('temp');
	}

	/**
	 * Зачищает содержимое папки
	 * @param $dir
	 * @return unknown_type
	 */
	function destroy($dir) {
		$mydir = opendir($dir);
		while(false !== ($file = readdir($mydir))) {
			if($file != "." && $file != "..") {
				chmod($dir.$file, 0777);
				unlink($dir.$file) or die("couldn't delete $dir$file<br />");
			}
		}
		closedir($mydir);
	}

	/**
	 * Создает временную папку для редактирования файлов
	 *
	 * @param string $filesUploadFolder путь к временной папке
	 */
	public function SetDirectory($filesUploadFolder, $clearDirectory=false)
	{
		$this->filesUploadFolder = $filesUploadFolder;

		$filesUploadFolder = $this->filesRootUploadFolder.$this->filesUploadFolder;

		// если папки нет, то создаем
		if (!is_dir($filesUploadFolder))
		{
			if (!mkdir($filesUploadFolder, 0777))
				trigger_error('Невозможно создать папку!');
		}
		else if ($clearDirectory)
			$this->destroy($filesUploadFolder);

		// определяем папку в сессии
		if (empty($_SESSION))
			@session_start();

		// проверяем на наличие переменной сессии
                //TODO проверить на что это влияет и зачем это вообще, так как в php 5.3 эта штуковина не работает
		//if (!session_is_registered(self::IndexUploadPath))
			//session_register(self::IndexUploadPath);

		// запоминаем имя папки в сессии
		$_SESSION[self::IndexUploadPath] = $this->filesUploadFolder.'/';
	}

	/**
	 * Обновляет файлы в тексте
	 *
	 * @param string $text
	 * @param string $filess
	 */
	public function UpdateFiles(&$text, $deleted = true)
	{
		// получаем массивы файлов и изображений
		$filess = $this->GetImgFilesFromText($text);

		$files = $this->GetLinkFilesFromText($text);

		// задаем папки
		$toFolder = $this->filesFolder;
		$fromFolder = $this->filesRootUploadFolder.$this->filesUploadFolder.'/';

		// получаем список всех файлов
		$oldFiles = glob($fromFolder."*.*");

		if (!isset($oldFiles) || empty($oldFiles) || count($oldFiles) == 0)
			return;

		foreach ($oldFiles as &$oldFile)
		{
			$oldFile = basename($oldFile);

			if (isset($filess[$oldFile]))
				$oldFile = true;

			if (isset($files[$oldFile]))
				$oldFile = true;
		}

		foreach ($oldFiles as $file)
		{
			// если данного файла нет в тексте, то удаляем его
			if ($file !== true && $deleted)
			{
				$this->DeleteFile($file);
				//echo "$file <br />";
			}
		}

		//die();
	}

	/**
		Перемещает файлы в новую папку
	*/
	public function MoveFiles(&$text, $folder)
	{
		$fromFolder = $this->filesRootUploadFolder.$this->filesUploadFolder.'/';
		$toFolder = $this->filesRootUploadFolder.$folder.'/';

		if (!file_exists($toFolder))
			mkdir($toFolder, 0777);

		// получаем массивы файлов и изображений
		$filess = $this->GetImgFilesFromText($text);
		$files = $this->GetLinkFilesFromText($text);

		Utility_FileUtility::CopyFiles($files, $fromFolder, $toFolder);
		Utility_FileUtility::CopyFiles($filess, $fromFolder, $toFolder);

		Utility_FileUtility::ClearFolder($fromFolder);
	}

	/**
	* Возвращает имена графических файлов из текста
	* @param string $text
	* @return array
	*/
	private function GetImgFilesFromText($text)
	{
		return $this->GetFilesFromText($text, 'src');
	}

	/**
	* Возвращает имена файлов ссылок из текста
	* @param string $text
	* @return array
	*/
	private function GetLinkFilesFromText($text)
	{
		return $this->GetFilesFromText($text, 'href');
	}

	/**
	 * Возвращает имена файлов из текста
	 *
	 * @param string $text текст
	 * @param string $tag свойство, в котором хранится имя файла, например src или href
	 * @return array
	 */
	private function GetFilesFromText($text, $tag)
	{
		$files = array();
		if($tag == "src"){
			$array = array("img"=>"src");
		}elseif ($tag == "href"){
			$array = array("a"=>"href");
		}

		$searchResult = $this->GetTagsParam($text,$array);

		$getValid = array_search($tag,$searchResult);

		if(!empty($searchResult[$getValid])){
			foreach ($searchResult[$getValid] as $str){
				$strings = explode('/', $str);
				// получаем порядковый номер в массиве $strings с записью имени картинки
				$index = sizeof($strings) - 1;

				if (isset($strings[$index]))
					$files[$strings[$index]] = $strings[$index];	// добавляем в массив имя картинки
			}
		}



		return $files;
		// выполняем глобальный поиск шаблона $pattern в строке $text
		//preg_match_all('/'.$tag.'="(.*?)"/si', $text, $out);
		preg_match_all('/'.$tag.'="(.*?)"/si', $text, $out);

		// элемент $out[1] содержит массив строк, с полным путем к картике.
		if (isset($out[1]))
			foreach ($out[1] as $value)
			{
				// разбиваем строку (путь к файлу с картинкой) на массив
				$strings = explode('/', $value);
				// получаем порядковый номер в массиве $strings с записью имени картинки
				$index = sizeof($strings) - 1;

				if (isset($strings[$index]))
					$files[$strings[$index]] = $strings[$index];	// добавляем в массив имя картинки
			}
		//Debug($files);
		return $files;
	}

	/**
	 * Удаляет картинки и файлы, упоминающиеся в тексте
	 *
	 * @param string $text
	 */
	function DeleteFiles($text)
	{
		// получаем массивы файлов и изображений
		$filess = $this->GetImgFilesFromText($text);
		$files = $this->GetLinkFilesFromText($text);

		if (is_array($filess) && count($filess) > 0)
			foreach ($filess as $files)
				self::DeleteFile($files);

		if (is_array($files) && count($files) > 0)
			foreach ($files as $file)
				self::DeleteFile($file);
	}

	/**
	 * Удаляет изображение
	 *
	 * @param string $fileName название изображения
	 */
	public function DeleteFile($fileName)
	{
		// путь до файла
		$filePath = $this->filesRootUploadFolder.$this->filesUploadFolder.'/'.$fileName;

		// если файл существует, то удаляем
		if (file_exists($filePath))
			Utility_FileUtility::DeleteFile($filePath);
	}

	/**
	 * Возврощает содержание отрибутов в определнном тэге
	 *
	 * @param string $text
	 * @param array $array
	 *
	 * @return array
	 */
	function GetTagsParam($text = "",$array = array()){
		$return = array();
		foreach ($array as $tag=>$param){
			if(preg_match_all("!<$tag"."([^>]+)>!is",$text,$a)){
				//print_r($a);continue;
				foreach ($a[1] as $str){
					if(preg_match("!$param(\s?)=(\s?)([\"']|)([^\"']+)!is",$str,$b)){
						$return[$tag][] = $b[4];
					}
				}
			}
		}
		return $return;
	}
}
?>