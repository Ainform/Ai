<?
/**
 * Backup class
 * Резервное копирование
 * 
 * @author Frame
 * @version Backup.tpl, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class BackupModule extends BMC_BaseModule 
{
	/**
	 * Имя файла с дампом БД
	 */
	const SqlDumpFileName = 'db.sql';

	/**
	 * Информационное сообщение
	 *
	 * @var string
	 */
	private $litMessage;

	/**
	 * Список папок для резервного копирования array('имя папки' => 'путь к папке')
	 *
	 * @var array
	 */
	private $backupFolders;
	
	/**
	 * Конструктор, задает параметры
	 *
	 * @return void
	 */
	function __construct($moduleId)
	{
		$this->cssClass = "pagesList";
		parent::__construct($moduleId);

		$this->backupFolders = array(
										Helpers_PathHelper::GetFolderName('file') => Helpers_PathHelper::GetFullPath('file', false),
									);
									
		$this->data['litMessage'] = "";
	}

	/**
	 * Биндим данные модуля
	 * 
	 * @return void
	 */
	function DataBind()
	{
		$backupFolderPath = Helpers_PathHelper::GetFullPath('backup');

		// получаем список резервных копий
		$backups = glob($backupFolderPath.'*');

		$this->data['Backups'] = Array();
		
		if (!is_array($backups) || count($backups) == 0)
			return '';

		foreach ($backups as $key => $backup)
			if (is_dir($backup))
			{
				$backupFolder = str_replace($backupFolderPath, '', $backup);
				$date = date('d.m.Y H:i:s', $backupFolder);
				
				$this->data['Backups'][] = Array
				(
					"Id" => $key + 1,
					"Date" => $date,
					"Folder" => $backupFolder
				);
			}
	}
	
	/**
	 * Создает резервную копию
	 */
	function handlerBtnCreate()
	{
		$backupFolderPath = Helpers_PathHelper::GetFullPath('backup').time();

		if (is_dir($backupFolderPath))
		{
			$this->data['litMessage'] = '<p align="center"><b>Ошибка при создании резервной копии</b></p>';
			return;
		}

		$this->StoreSqlDump($backupFolderPath);

		$this->StoreFolders($backupFolderPath);

		$this->data['litMessage'] = 'Резервная копия успешно создана';
	}
	
	/**
	 * Создает резервную копию БД
	 *
	 * @param string $backupFolderPath путь к папке с резервной копией
	 */
	private function StoreSqlDump($backupFolderPath)
	{
		// создаем папку
		mkdir($backupFolderPath);

		// создаем дамп БД
		$db = new DAL_DbBackup();
		$db->CreateMySQLDump();

		// записываем дамп в файл
		Utility_FileUtility::WriteInToFile($backupFolderPath.'/'.self::SqlDumpFileName, $db->GetDump());
	}

	/**
	 * Создает резервную копию файлов
	 *
	 * @param string $backupFolderPath путь к папке с резервной копией
	 */
	private function StoreFolders($backupFolderPath)
	{
		// копируем файлы и папки
		foreach ($this->backupFolders as $folderName => $folderPath)
			Utility_FileUtility::CopyFolder($folderPath, $backupFolderPath.'/'.$folderName);
	}

	/**
	 * Удаляет резервную копию
	 * 
	 * @param string $folderName
	 */
	function handlerBtnDelete($folderName)
	{
		$backupFolderPath = Helpers_PathHelper::GetFullPath('backup').$folderName;

		if (is_dir($backupFolderPath))
		{
			Utility_FileUtility::DeleteFolder($backupFolderPath);
			$this->data['litMessage'] = 'Резервная копия успешно удалена';
		}
	}

	/**
	 * Восстанавливает резервную копию
	 * 
	 * @param string $folderName
	 */
	function handlerBtnRestore($folderName)
	{
		$backupFolderPath = Helpers_PathHelper::GetFullPath('backup').$folderName;

		if (is_dir($backupFolderPath))
			if ($this->handlerBtnRestoreSql($folderName) && $this->handlerBtnRestoreFiles($folderName))
			{
				$this->data['litMessage'] = 'Резервная копия успешно восстановлена';
				return;
			}

		$this->data['litMessage'] = 'Не удалось восстановить резервную копию';
	}

	/**
	 * Восстанавливает файлы из резервной копии
	 * 
	 * @param string $folderName имя папки с резервной копией
	 */
	function handlerBtnRestoreFiles($folderName)
	{
		$backupFolderPath = Helpers_PathHelper::GetFullPath('backup').$folderName;
		
		// копируем файлы и папки
		foreach ($this->backupFolders as $folderName => $folderPath)
		{
			Utility_FileUtility::ClearFolder($folderPath);
			
			Utility_FileUtility::CopyFolder($backupFolderPath.'/'.$folderName, $folderPath);
		}
	}

	/**
	 * Восстанавливает БД из резервной копии
	 * 
	 * @param string $folderName имя папки с резервной копией
	 */
	function handlerBtnRestoreSql($folderName)
	{
		$backupFolderPath = Helpers_PathHelper::GetFullPath('backup').$folderName;

		// Восстанавливаем таблицы БД
		if (is_file($backupFolderPath.'/'.self::SqlDumpFileName))
		{
			// удаляем таблицы из БД
			$db = new DAL_DbBackup();
			$db->DropTables();

			require_once($backupFolderPath.'/'.self::SqlDumpFileName);

			// восстанавливаем таблицы БД
			$db->Restore($table);

			// Восстанавливаем данные таблиц БД
			$db->Restore($data);

			$this->data['litMessage'] = 'Успешное восстановление БД из резервной копии';

			return true;
		}

		return false;
	}
}
?>