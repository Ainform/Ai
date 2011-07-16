<?php

/**
 * DAL_RepositoryDb class
 * Класс для работы с хранилищем файлов
 *
 * @author SanSYS
 * @version RepositoryDb.class.php, v 1.0.1
 * @copyright (c) by Inline ... - 2008
 *
 */

class DAL_FilesDb extends DAL_BaseDb {


  /**
	@var string $TableName Название таблицы
	*/
  protected $TableName = "files";

  /**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
  protected function getStructure() {
	 return array(
				"FileId"      => 'int'    ,
				"DocName"     => 'string' ,
				"Ext"         => 'string' ,
				"IsArchive"   => 'int',
				"ModuleId"	  => 'string',
				"FileName"	  => 'string',
				"Size"	  => 'string',
				"Description" => 'string'
	 );
  }

  /**
	Возвращает первичные ключи таблицы
		
	@return array ключи таблицы
	*/
  protected function getKeys() {
	 return array("FileId");
  }

  /**
	@return array автоинкрементные индексы таблицы
	*/
  protected function getIndexes() {
	 return array("FileId");
  }


  /**
	* возвращает расширение файла (без точки)
	*
	* @param unknown_type $fileName
	*/
  public function GetExtension($fileName) {
	 if (!empty($fileName)) {
		$path_parts = pathinfo($fileName);
		return $path_parts['extension'];
	 }

	 return "txt";
  }

  /**
	* возвращает имя файла по идентификатору
	*
	* @param unknown_type $fileName
	*/
  public function GetFileName($FileId) {
	 if (!empty($FileId)) {
		$temp=$this->select(array("FileId" => $FileId));
		return $temp[0]['FileName'];
	 }
	 return false;
  }

  /**
	* Возвращает путь к файлу
	*
	*/
  function GetFilePath($params) {
	 $name=$this->GetFileName($params["id"]);
	 return Helpers_PathHelper::GetFullPath('upload')."files/".$name;
  }

  /**
	*
	* @return путь, куда сохранили файл
	*/
  private function SaveDoc($FileId) {
	 if (trim($_FILES['document']['tmp_name']=="")) return false;

	 $tmpFileName = $_FILES['document']['tmp_name'];

	 $path=Helpers_PathHelper::GetFullPath('upload')."/files/".$_FILES['document']['name'];

	 copy($tmpFileName, $path);

	 if (file_exists($tmpFileName))
		unlink($tmpFileName);

	 return $path;
  }

  /**
	* Констуктор, инициализирует соединение
	*
	*/
  function __construct() {
	 parent::__construct();
  }

  /**
	* Добавляет файл в хранилище
	*
	* @param string $repositName
	* @param string $docName
	* @param bool   $isArchive
	*/
  public function AddFile($docName, $moduleId, $description, $isArchive = 0) {
	 /**
	  * добавить обработку архивирования
	  */

	 if(isset($_FILES['document']['name'])) {
		$this->insert(array("DocName"     => $docName         ,
				  "Ext"         => $this->GetExtension($_FILES['document']['name']),
				  "IsArchive"   => $isArchive,
				  "ModuleId" 	  => $moduleId,
				  "FileName" 	  => $_FILES['document']['name'],
				  "Size" 	  => $_FILES['document']['size'],
				  "Description" => $description
		));
		$this->SaveDoc($this->db->GetLastId());
	 }
	 else {
		$this->insert(array("DocName"     => $docName,
				  "IsArchive"   => $isArchive,
				  "ModuleId" 	  => $moduleId,
				  "Description" => $description
		));
	 }
  }

  /**
	* Возвращает данные файлов для одного элемента
	*
	* @param string $folder Необходимая папка
	* @return array
	*/
  public function GetFromFolder($folder) {
	 return $this->select(array("Folder" => strtolower($folder)), "Order");
  }

  /**
	* Обновляем данные о файле
	*
	* @param int $fileId - new datarow for file
	*/
  public function UpdateFile($fileId, $docName, $moduleId, $description, $isArchive = 0) {

	 //если есть файлы, то зливаем и обновляем
	 if(isset($_FILES['document']['name']) && $_FILES['document']['name']!='') {

		//Удаление файла с диска
		$path=$this->GetFilePath(array("id"=>$fileId));

		if (is_file($path))
		  unlink($path);

		$this->update(array(
				  "FileId"=>$fileId,
				  "DocName"     => $docName,
				  "IsArchive"   => $isArchive,
				  "Ext"         => $this->GetExtension($_FILES['document']['name']),
				  "FileName" 	=> $_FILES['document']['name'],
				  "Size" 	    => $_FILES['document']['size'],
				  "ModuleId" 	=> $moduleId,
				  "Description" => $description
		));

		//добавляем файл
		$this->SaveDoc($fileId);
	 }
	 else {
		$this->update(array(
				  "FileId"=>$fileId,
				  "DocName"     => $docName,
				  "IsArchive"   => $isArchive,
				  "ModuleId" 	=> $moduleId,
				  "Description" => $description
		));
	 }
  }

  /**
	* Возвращает один Документ из хранилища
	*
	* @param int $id идентификатор документа
	* @return array
	*/
  public function GetRepositFile($fileId) {
	 return $this->select(array("FileId"=>$fileId));
  }

  /**
	* Список файлов в хранилище
	*
	* @return array
	*/
  public function GetRepositFileList($moduleId) {
	 return $this->select(array("ModuleId" => $moduleId));
  }

  /**
	* Удаляет файл из хранилища
	*
	* @param int $goodId идентификатор товара
	* @param bool $fromDb и из бд
	*/
  public function DeleteFile($fileId, $delFile = true) {
	 //Удаление файла с диска
	 $path=$this->GetFilePath(array("id"=>$fileId));

	 if ($delFile)
		if (is_file($path))
		  unlink($path);

	 //Удаление файла из базы, если это не обновление
	 $this->delete(array("FileId" => $fileId));
  }

  /**
	* Удаляет файлы при удалении модуля
	*
	*/
  public function DeleteFiles($moduleId) {

	 $files=$this->select(array("ModuleId"=>$moduleId));

	 if(is_array($files)) {
		foreach($files as $file) {
		  $this->DeleteFile($file['FileId']);
		}
	 }

  }

}