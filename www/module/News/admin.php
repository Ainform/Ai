<?php
/**
 *
 * @copyright (c) by Ainform
 *
 */
class NewsModuleEdit extends BMC_BaseModule
{
	/**
	 * Конструктор, задает параметры
	 *
	 * @param $moduleId
	 * @return \NewsModuleEdit
	 */
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}

	/**
	 * Номер текущей страницы
	 *
	 * @var int
	 */
	public $curPage;

	/**
	 * Папка для файлов модуля
	 */
	private $moduleFolder = "news";
	private $Model;

	/**
	 * Функция для создания html-кода модуля
	 *
	 * @return void
	 */
	function DataBind()
	{

		$this->Model = new DAL_NewsDb;
		$this->curPage = Request('page', 1);
		$action = CharOnly(Request("act", "List"));

		if ($action == "List") {
			$this->Lister();
		}

		// если указан идентификатор аналитики, то грузим саму аналитику, иначе список
		$newsId = Request('NewsId');

		$this->data['ModuleId'] = $this->moduleId;

		// пытаемся добавить аналитику
		$isNewNews = Request('newNews');

		if ($isNewNews == 1)
			$this->BindNew();
		else {
			if ($newsId == null) {
				$this->BindNewsList();
			} else {
				$this->Bindnews($newsId);
			}
		}
	}

	public function Lister()
	{

		//$this->data["Info"] = $this->Model->GetAnaliticsByPage($this->moduleId,$this->curPage,self::RecordsOnPage);
	}

	/*
* Биндим данные для нового материала
*/

	public function BindNew()
	{

		Utility_FileUtility::DeleteFilesFromDirectory(Helpers_PathHelper::GetFullPath('upload') . DAL_NewsDb::GetFolder());

		$this->data = array("title" => "", "text" => "", "anons" => "");
		$this->data['ImagesFolder'] = DAL_NewsDb::GetFolder();
		$this->data['FilesFolder'] = DAL_NewsDb::GetFolder();
		$this->data['date'] = date("d.m.Y", time());
		$this->data['ModuleId'] = $this->moduleId;

		$this->template = "Edit.tpl";

		BMC_SiteMap::Update();
	}

	/**
	 * @return void
	 */
	public function BindNewsList()
	{

		$NewsDb = new DAL_NewsDb();
		$rows = $NewsDb->GetPage(array("moduleid" => $this->moduleId), @$_REQUEST['sort'], @$_REQUEST['sort_dir'], $this->curPage, $this->RecordsOnPage);

		if (0 == count($rows))
			return;

		$rows['data'] = $rows;

		unset($_GET['page']);
		$path = $this->GetVirtualPath() . ".php?" . http_build_query($_GET) . "&page=";

		$allNews = $NewsDb->GetCount($this->moduleId); // количество всех новостей
		$p = ceil($allNews / $this->RecordsOnPage); // количество страниц
		$this->SetPager($p, $path, $this->curPage);

		unset($NewsDb);

		$rows["pager"] = $this->data['Pager'];

		foreach ($rows[0] as $key => $value) {
			$rows['headers'][$key] = $key;
		}

		foreach ($rows as $id => $value) {
			$rows['links'][$id] = @$value['Url'];
		}
		$rows['titles'] = array("title" => "Заголовок", "date" => "Дата", "Order" => "п/п");
		$rows['exceptions'] = array("text", "anons", "moduleid", "Url", "onfront");
		$rows['types'] = array("date" => "date", "onfront" => "bool", "title" => "link");
		$this->data['NewsList'] = $rows;
	}

	/**
	 * @param $newsId
	 * @return
	 */
	public function handlerBtnEdit($newsId)
	{
		$newsDb = new DAL_NewsDb();
		$news = $newsDb->GetOne($newsId);
		unset($newsDb);

		if (!isset($news))
			return;

		$this->data = $news;
		//приводим дату к удобочитаемому формату
		$this->data['date'] = date("d.m.Y", $this->data['date']);

		//подключаем класс, управляющий файлами
		$analyticFilesUtility = new Utility_AnalyticFilesUtility();

		//задаем папку под файлы
		$analyticFilesUtility->SetDirectory($this->moduleFolder);

		// подключаем класс, управляющий картинками
		$imageUtility = new Utility_ImageUtility();
		$imageUtility->SetDirectory(DAL_NewsDb::GetFolder($newsId));

		//if ($news['onfront'] == 1)
		//$this->data['chkOnFront'] = "True";

		$this->data['FilesFolder'] = DAL_NewsDb::GetFolder($newsId);
		$this->data['ImagesFolder'] = DAL_NewsDb::GetFolder($newsId);
		$this->data['selectOnFront'] = $news['onfront'];

		$this->template = "Edit.tpl";
	}

	function handlerBtnNewsSave()
	{

		if (!IsValid())
			return;

		$newsId = Request('NewsId');

		// пытаемся добавить
		$isNew = Request('newNews');

		// подключаем класс, управляющий файлами
		$filesUtility = new Utility_AnalyticFilesUtility();
		// подключаем класс, управляющий картинками
		$imageUtility = new Utility_ImageUtility();

		if (!empty($newsId)) {
			$newsRow = Array();
			$newsRow['id'] = $newsId;
			$newsRow['title'] = HtmlEncode($this->data['txtTitle']);
			$newsRow['text'] = $this->data['fckText'];
			$newsRow['anons'] = $this->data['fckAnons'];
			$newsRow['date'] = strtotime($this->data['txtDate']);
			// $newsRow['onfront'] = $this->data['selectOnFront'];

			$newsDb = new DAL_NewsDb();
			$newsDb->Update($newsRow);
			unset($newsDb);

			// перемещаем картинки в нужную папку
			$imagesDb = new DAL_ImagesDb();
			$imageUtility->SetDirectory(DAL_NewsDb::GetFolder($newsId)); //если папка ещё не создана, создаём
			//$imagesDb->MoveImages(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
			unset($imagesDb);

			// задаем папку под картинки и обновляем файлы
			$filesUtility->SetDirectory($this->moduleFolder);
			$filesUtility->UpdateFiles($this->data['fckText'], false);
			$filesUtility->UpdateFiles($this->data['fckAnons'], false);

			//$fulltext = $this->data['fckAnons'] + $this->data['fckText'];
			//$filesUtility->UpdateFiles($fulltext, true);
			// перемещаем файлы в нужную папку
			$filesDb = new DAL_AnalyticsFilesDb();
			$filesUtility->SetDirectory($this->moduleFolder); //если папка ещё не создана, создаём
			$filesDb->MoveFiles(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
			unset($filesDb);

			//include_once '../generaterss.php';

			Header("Location: " . $this->Url);
		} elseif (!empty($isNew)) {
			$newsRow = Array();
			$newsRow['moduleid'] = $this->moduleId;
			$newsRow['title'] = HtmlEncode($this->data['txtTitle']);
			$newsRow['date'] = strtotime($this->data['txtDate']);
			$newsRow['text'] = $this->data['fckText'];
			$newsRow['anons'] = $this->data['fckAnons'];
			//$newsRow['onfront'] = $this->data['selectOnFront'];
			// добавяляем материал и получаем её идентификатор
			$newsDb = new DAL_NewsDb();
			$newsId = $newsDb->Add($newsRow);
			unset($newsDb);

			// перемещаем файлы в нужную папку
			$filesDb = new DAL_AnalyticsFilesDb();
			$filesUtility->SetDirectory($this->moduleFolder); //если папка ещё не создана, создаём
			$filesDb->MoveFiles(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
			unset($filesDb);

			// перемещаем картинки в нужную папку
			$imagesDb = new DAL_ImagesDb();
			$imageUtility->SetDirectory($this->moduleFolder); //если папка ещё не создана, создаём
			$imagesDb->MoveImages(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
			unset($imagesDb);

			// задаем папку под картинки и обновляем файлы
			//$imageUtility->MoveFiles($this->data['fckText'], DAL_NewsDb::GetFilesFolder($newsId));
			//$newsRow['Text'] = $this->data['fckText'];
			//$newsRow['Id'] = $newsId;
			// обновляем текст новости с учетом перемещенных картинок
			//$newsDb = new DAL_NewsDb();
			//$newsId = $newsDb->UpdateNews($newsRow);
			// unset($newsDb);
			//include_once '../generaterss.php';
			// возвращаемся к списку новостей
			RedirectToModuleEdit($this->moduleId);
		}

		BMC_SiteMap::Update();
	}

	function handlerBtnCancel()
	{
		$newsId = Request('newsId');
		$isNewNews = Request('newNews');

		if ($newsId != null || $isNewNews) {


			// отмена создания
			if ($isNewNews) {
				// удаляем файлы
				//$filesDb = new DAL_NewsFilesDb();
				//$filesDb->DeleteFolder(DAL_NewsDb::GetFolder());
				//unset($filesDb);
				// подключаем класс, управляющий изображениями в тексте
				//$filesUtility = new Utility_FilesUtility();
				//$filesUtility->DeleteFiles($this->data['fckText']);
			}
			Header("Location: " . $this->Url);
		}
		else
			RedirectToPageEdit($this->moduleId);

		die();
	}

	/**
	 * @param $Id
	 * @return void
	 */
	function handlerBtnDel($Id)
	{
		$newsDb = new DAL_NewsDb();
		$newsDb->Remove($Id);
		unset($newsDb);

		BMC_SiteMap::Update();

		$this->BindNewsList();
	}

	function handlerBtnUp($id)
	{
		$newsDb = new DAL_NewsDb();
		$newsDb->Up($id);
		unset($newsDb);

		$this->BindNewsList();
	}

	function handlerBtnDown($id)
	{
		$newsDb = new DAL_NewsDb();
		$newsDb->Down($id);
		unset($newsDb);

		$this->BindNewsList();
	}

}

?>
