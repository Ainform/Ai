<?php

/**
 * BMC_BaseModule class
 * Базовый класс для модулей сайта
 *
 * @abstract имена обработчиков компмонентов должны
 * начинаться с handler, например, handlerBtnSaveClick();
 * jбработчики выполняются первыми, только затем Render()
 *
 *
 * @author Frame
 * @version BaseModule.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class BMC_BaseModule
{
	/**
	 * Хлебные крошки
	 *
	 * @var string
	 */
	public $breadCrumbs;

	/**
	 * Заголовок страницы
	 *
	 * @var string
	 */
	public $header;
	public $Title;
	public $PageTitle;
	public $curPage;
	public $pageVar;

	/**
	 * Мета теги страницы
	 *
	 * @var string
	 */
	public $metaTags;

	/**
	Тип модуля

	@var string
	 */
	public $moduleType;

	/**
	Путь до шаблона страницы

	@var string
	 */
	public $template;

	/**
	Путь до папки

	@var string
	 */
	public $folder;

	/**
	Url страницы с модулем

	@var string
	 */
	public $Url;

	/**
	Инициализируем данные модуля или берем их из POST запроса?

	@var bool
	 */
	public $isPostBack = false;

	/**
	Массив данных страницы

	@var array
	 */
	public $data = array();

	/**
	Идентификатор модуля

	@var int
	 */
	public $moduleId;

	/**
	 * Количество материалов на страницу
	 */
	public $RecordsOnPage;

	/**
	Название CSS класса модуля

	@var string
	 */
	public $cssClass = "module";

	/**
	 * Разрешение на кеширование
	 *
	 * @var bool
	 */
	public $propertyAllowCache = false;

	/**
	 * Загрузка данных модуля
	 *
	 * @return void
	 */
	public function DataBind()
	{
	}

	/**
	Генерирует сайтмап для модуля
	 */
	public function GenerateSiteMap()
	{
		return "";
	}

	/**
	Событие при добавлении модуля к странице
	 */
	public function OnModuleAdd()
	{
	}

	/**
	Событие при удалении модуля
	 */
	public function OnModuleDelete()
	{
	}

	function getTemplatePath()
	{
		return $this->folder . $this->template;
	}

	/**
	Возвращает виртуальный (ЧПУ) путь до модуля
	 * @return string
	 */
	public function GetVirtualPath()
	{
		return dirname($this->Url) . '/' . basename($this->Url, '.php') ;
	}

	public function __construct($moduleId)
	{
		$this->moduleId = $moduleId;
		//TODO если несколько педжингов на странице, то нужно передевать что-то типа page.$this->ModuleId
		$this->pageVar = "page";
		$this->RecordsOnPage = isset($_REQUEST['count'])?intval($_REQUEST['count']):10;
		$this->curPage = isset($_REQUEST[$this->pageVar])?intval($_REQUEST[$this->pageVar]):0;
	}

	public function getData()
	{
		return new ModuleData($this->data);
	}

	// распределение файлов по их типу
	public function DistibuteFiles($filelist, $entityspec)
	{
		$videolist = null;
		$soundlist = null;
		$outFiles = null;

		$playlist = "<?xml version='1.0' encoding='UTF-8'?><xml>";

		foreach ($filelist as &$file) {
			$file['Path'] = DAL_AnalyticsFilesDb::GetfilePath($file);

			$filename = strtolower($file['filename']);

			if (strpos($filename, ".flv") !== false || strpos($filename, ".swf") !== false)
				$videolist[] = SiteUrl . $file['Path'] . $file['folder'] . $file['filename'];
			else
				if (strpos($filename, ".mp3") !== false) {
					$soundlist[] = array("Title" => $file["title"], "Path" => SiteUrl . $file['Path'] . $file['folder'] . $file['filename']);
					$playlist .= "
<track>
	<path>" . SiteUrl . $file['Path'] . $file['folder'] . $file['filename'] . "</path>
	<title>" . $file["title"] . "</title>
</track>";
				}
				else
					$outFiles[] = $file;
		}

		$this->data['Files'] = $outFiles;
		$this->data['VideoList'] = $videolist;
		$this->data['SoundList'] = $soundlist;
		$this->data['PlayList'] = SiteUrl . "playlists/playlist$entityspec.xml";
		$this->data['Mp3PlayerHeight'] = count($soundlist) >= 10 ? 337 : (100 + count($soundlist) * 20);

		if (count($soundlist)) {
			//echo "playlists/playlist$entityspec.xml";
			file_put_contents("playlists/playlist$entityspec.xml", $playlist);
		}
	}


	/*
* Задаёт разбивку на страницы
*
* @pagecount Количество страниц
* @pageVar Имя переменной с текущей страницей
* @postfix Любая дополнительная информация
* TODO сделать несколько видов пейджинга с возможностью выбрать нужный
*/

	public function SetPager($pageCount, $url, $pagecur = 1)
	{

			$this->data["Pager"] = "";
			$this->data["Pager"] .= '<link rel="stylesheet" type="text/css" href="/js/paginator3000/paginator3000.css" />
	<script type="text/javascript" src="/js/paginator3000/paginator3000.js"></script>';
			$this->data["Pager"] .= '<div class="paginator" id="paginator"></div>
						<div class="paginator_pages">' . $pageCount . " " . declension($pageCount, array("страница", "страницы", "страниц")) . '</div>
						<script type="text/javascript">
						pag1 = new Paginator(\'paginator\', ' . $pageCount . ',10, ' . $pagecur . ',"'. $url.'");
						</script>';
		}

	public function getRecordsOnPage()
	{
		return $this->recordsOnPage;
	}

	public function setRecordsOnPage($recordsOnPage)
	{
		$this->recordsOnPage = $recordsOnPage;
	}

}

class ModuleData implements ArrayAccess
{
	private $data;

	function __construct($array)
	{
		$this->data = $array;
	}

	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	public function offsetGet($offset)
	{
		if (isset($this->data[$offset]))
			return $this->data[$offset];

		return null;
	}

	public function offsetSet($offset, $value)
	{
		$this->data[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}
}

?>
