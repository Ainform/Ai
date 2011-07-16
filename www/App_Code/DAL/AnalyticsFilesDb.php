<?php
/**
 * DAL_AnalyticsDb class
 * Класс для работы с баннерами
 *
 * @author Anakhorein
 * @version 0.q
 * @copyright (c) by Anakhorein
 */

class DAL_AnalyticsFilesDb extends DAL_BaseDb {
/**
 @var string $TableName Название таблицы
 */
    protected $TableName = "analytics_files";

    /**
     Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
     @return array структура таблицы
     */
    protected function getStructure() {
        return array("id" => "int",
        "folder" => "string",
        "filename" => "string",
        "title" => "string",
        "Order"=>"int",
        "size"=>"int",
        "ManufacturerId"=>"int"
        );
    }

    /**
     Возвращает первичные ключи таблицы
		
     @return array ключи таблицы
     */
    protected function getKeys() {
        return array("id");
    }

    /**
     @return array автоинкрементные индексы таблицы
     */
    protected function getIndexes() {
        return array("id");
    }

    /**
     * Констуктор, инициализирует соединение
     *
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Возвращает данные изображений для одного элемента
     *
     * @param string $folder Необходимая папка
     * @return array
     */
    public function GetFromFolder($folder) {
        return $this->select(array("folder" => strtolower($folder)), "Order");
    }

    /**
     * Возвращает Файл
     *
     * @param int $Id идентификатор 
     * @return array
     */
    public function Get($Id) {
        $result = $this->select(array("id" => $Id));
        return $result[0];
    }

    	/**
	 * Удаляет папку
	 *
	 * @param string $folder папка
	 */
	public function DeleteFolder($folder)
	{
		$rows = $this->GetFromFolder($folder);

		// очищаем папку с файлами
		$folderPath = Helpers_PathHelper::GetFullPath('upload').$folder;
		Utility_FileUtility::ClearFolder($folderPath);
                //если папка существует - удаляем
                if (is_dir($folderPath))
                rmdir($folderPath);
                
		$this->delete(array("folder" => strtolower($folder)));
	}


    /**
     * Возвращает запрошенную страницу с аналитикой
     *
     * @param int $page номер страницы
     * @param int $recordsOnPage количество записей на страницу
     * @param int $count общее количество записей
     * @return array
     */
    public function GetAnalyticsPage($moduleId, $page, $recordsOnPage) {
        return $this->selectPage(array("ModuleId" => $moduleId),null,null,$page,$recordsOnPage);
    }


    /**
     Возвращает общее количество новостей для текущего модуля

     @param int $moduleId идентификатор модуля
     */
    public function GetCountAnalytics($moduleId) {
        return $this->selectCount(array("ModuleId" => $moduleId));
    }

    /**
     * Добавляем аналитику
     *
     * @param row $newsRow данные по аналитике
     */
    public function AddAnalytics($newsRow) {
    //Debug($newsRow);
        $this->insert($newsRow);

        return $this->db->GetLastId();
    }

    /**
     * Обновляет аналитику
     *
     * @param row $newsRow данные по аналитике
     */
    public function UpdateAnalytics($analyticsRow) {
    //debug($analyticsRow);
        $this->update($analyticsRow);
    }

    /**
     Возвращает папку для файлов 
     */
    public static function GetFilesFolder($Id = null) {
        $folder = "analytics";

        if (isset($Id))
            $folder .= "/".$Id."/";

        return $folder;
    }

    /**
     * Возвращает один файл для одного элемента
     *
     * @param int $folder идентификатор элемента
     * @return array
     */
    public function GetTopFromFolder($folder) {
        $rows = $this->select(array("folder" => strtolower($folder)), "Order");

        if (count($rows) > 0)
            return $rows[0];
        else
            return null;
    }

    /**
     Возвращает ссылку на файл
     */
    public static function GetFilePath($fileRow) {
        return "/upload/";
    }

    /**
     * Добавляет Файл
     *
     * @param array $imageRow данные о файле
     */
    public function AddFile($fileRow) {
        $fileRow['Order'] = $this->Order()->InsertRecord($fileRow['folder']);
        $this->insert($fileRow);

        return $this->db->GetLastId();
    }

    /**
     * Возвращает экземпляр класса OrderHelper
     *
     * @return Helpers_OrderHelper
     */
    private function Order() {
        $orderHelper = new Helpers_OrderHelper();
        $orderHelper->SetInfo($this->TableName, 'id', 'folder');

        return $orderHelper;
    }

    /**
     Перемещаем файлы
     */
    public function MoveFiles($fromFolder, $toFolder) {
    // очищаем названия папок
        $toFolder = $this->db->Escape($toFolder);
        $fromFolder = $this->db->Escape($fromFolder);

        // получаем 
        $images = $this->GetFromFolder($fromFolder);

//        if (!isset($images) || count($images) == 0)
  //          return;

        // обновляем папки в базе
        $query = "UPDATE `$this->TableName` SET `folder` = '$toFolder' WHERE `folder` = '$fromFolder'";
        $this->db->ExecuteScalar($query);

        $fromFolder = Helpers_PathHelper::GetFullPath('upload').$fromFolder.'/';
        $toFolder = Helpers_PathHelper::GetFullPath('upload').$toFolder.'/';

        if (!file_exists($toFolder))
            mkdir($toFolder, 0777);

        // переносим файлы
        foreach ($images as $img) {
            $img_file = $img['filename'];
if($img_file){
            Utility_FileUtility::MoveFile($img_file, $fromFolder, $toFolder, $img_file);}
        }
        
		$mydir = opendir($fromFolder);
		while(false !== ($file = readdir($mydir))) {
			if(!is_dir($file)&&is_file($file)&& $file != "." && $file != "..") {
echo $file;
				//chmod($fromFolder.$file, 0777);
				
				Utility_FileUtility::MoveFile($file, $fromFolder, $toFolder, $file);				
			}
		}

		closedir($mydir);
    }
    /**
	 * Поднимает файл
	 *
	 * @param int $Id идентификатор файла
	 */
	public function Up($Id)
	{
		$this->Order()->UpRecord($Id);
	}

        /**
	 * Опускает файл
	 *
	 * @param int $Id идентификатор файла
	 */
	public function Down($Id)
	{
		$this->Order()->DownRecord($Id);
	}

        /**
	 * Удаляет файл
	 *
	 * @param int $id идентификатор файла
	 */
	public function DeleteFile($id)
	{
		// получаем файл
		$file = $this->Get($id);

		$this_file = Helpers_PathHelper::GetFullPath('upload').$file['folder'].'/'.$file['filename'];

		// удаляем файл, если ссылок в базе на него больше нет
		$eqFiles = $this->select(array("folder" => $file['folder'], "filename" => $file['filename']));

		if (count($eqFiles) < 2)
			@unlink($this_file);

		// удаляем запись из таблицы
		$this->Order()->DeleteRecord($id);
		$this->delete(array("id" => $id));
	}


}

?>