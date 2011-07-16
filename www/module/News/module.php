<?
/**
 * newsModule class
 * Вывод данных модуля
 *
 *
 */

class newsModule extends BMC_BaseModule
{

	const RecordsOnMainPage = 5;
	const RecordsOnPage = 10;

	//private $curPage;
	//public $curPage;

	function GetAlias($id)
	{
		return "?newsId=" . $id;
	}

	function GetLink($params)
	{
		if (is_array($params))
			$id = $params['id'];
		else
			$id = $params;

		//var_dump($this->GetVirtualPath().$this->GetAlias($id).'');

		$str = str_replace("http:/", "http://", $this->GetVirtualPath() . $this->GetAlias($id) . '');
		$str = str_replace("http:///", "http://", $str);

		return $str;
		//return str_replace("http:/", "http://", $this->GetVirtualPath().$this->GetAlias($id).'');
	}

	function ToDate($date)
	{
		return date("dd.mm.yyyy", $date);
	}

	function DataBind()
	{
		$smarty = PHP_Smarty::GetInstance();
		$smarty->registerPlugin("function", "newsLink", array($this, "GetLink"));

		$smarty->assign('ShowTitle', false);

		$this->curPage = intval(Request('npageNum', 0)) + intval(Request('pageNum', 0));
		$newsId = intval(Request('newsId'));

		if (Request("activityId", "") != "" || Request("commentsId", "") != "" || Request("projectsId", "") != "")
			return;
		else
			$this->data["isshow"] = false;

		if ($newsId == null) {
			$this->BindList();
		}
		else {
			$smarty->assign("IsNews", $newsId);
			$this->BindNews($newsId);
		}
	}

	public function BindList()
	{

		$itemsOnPage = self::RecordsOnPage;

		$newsDb = new DAL_NewsDb();

		//if (isfront)
		//$rows=$newsDb->GetOnFront($this->moduleId,$this->curPage * $itemsOnPage, $itemsOnPage);
		// else
		$rows = $newsDb->GetPage($this->moduleId, $this->curPage, $itemsOnPage);

		/*Пейджинг*/
		$allNews = $newsDb->GetCount($this->moduleId); // количество всех новостей

		$pageVar = "pageNum";

		$pageCount = ceil($allNews / $itemsOnPage); // количество страниц

		$this->SetPager($pageCount, $pageVar, null);
		unset($newsDb);

		$newsFilesDb = new DAL_AnalyticsFilesDb();

		foreach ($rows as &$row) {

			$folder = $newsFilesDb->GetFilesFolder($row['id']);
			$file = $newsFilesDb->GetTopFromFolder($folder);
			if ($file == null)
				continue;
			$filePath = $newsFilesDb->GetFilePath($file);
			$row['file'] = '<a href="' . $filePath . '" alt="' . $file['title'] . '" title="' . $file['title'] . '" />';
		}

		unset($newsFilesDb);

		$imagesDb = new DAL_ImagesDb();

		foreach ($rows as $key => &$row) {

			$folder = DAL_NewsDb::GetFolder($row['id']);
			$image = $imagesDb->GetTopFromFolder($folder);

			if ($image == null)
				continue;

			$imgPath = DAL_ImagesDb::GetImagePath($image);

			$row['Image'] = $imgPath;

		}
		unset($imagesDb);

		if (0 == count($rows))
			return;
		//Debug($rows);
		$this->data['ModuleId'] = $rows[0]['moduleid'];
		$this->data['NewsList'] = $rows;


	}

	public function BindNews($newsId)
	{
		$newsDb = new DAL_NewsDb();
		$news = $newsDb->GetOne($newsId);
		unset($newsDb);

		$folder = DAL_NewsDb::GetFolder($news['id']);

		$newsFilesDb = new DAL_AnalyticsFilesDb();

		$newsfiles = $newsFilesDb->GetFromFolder($folder);

		$this->DistibuteFiles($newsfiles, "news$newsId");

		$this->data['News'] = $news;

		$curPage = BMC_SiteMap::GetCurPage();
		$this->breadCrumbs = array($curPage->Title => $curPage->Path . "/");
		$breadCrumbs = BLL_BreadCrumbs::getInstance();
		$breadCrumbs->Add($this->breadCrumbs);
		//Debug($breadCrumbs->Bind());
		//$smarty->assign("Header",$news['Title']);

		// �������� ��� �������
		$folder = DAL_NewsDb::GetFolder($news['id']);

		$imagesDb = new DAL_ImagesDb();

		// �������� �������� ��� �������
		$images = $imagesDb->GetFromFolder($folder);


		foreach ($images as &$image)
			$image['Path'] = DAL_ImagesDb::GetImagePath($image);

		$count = count($images);

		$smallPhotos = array();
		if ($count > 1) {
			for ($i = 1; $i < $count; $i++)
				$smallPhotos[] = $images[$i];
		}

		$this->data['Images'] = $smallPhotos;

		$this->header = $news['title'];

		$this->data['Title'] = $news['title'];
		$this->data['FirstPhoto'] = count($images) != 0 ? $images[0] : null;

		$this->PageTitle = $news['title'];

		$smarty = PHP_Smarty::GetInstance();
		$smarty->assign("Title", $news['title']);


		//Debug($news['Title']);
		$this->template = "news.tpl";
	}

	public function GenerateSiteMap()
	{
		$newsDb = new DAL_NewsDb();

		$rows = $newsDb->GetByModuleId($this->moduleId);

		unset($newsDb);

		$xml = "";

		foreach ($rows as $row) {
			$xml .= Helpers_SiteMap::CreateNode($row["Alias"] . $this->GetAlias($row["id"]) . '', "", HtmlEncode($row['title']), false, $this->moduleId, array("id" => $row['id']), "", true);
		}

		return $xml;
	}

	public function OnModuleDelete()
	{
		$newsDb = new DAL_NewsDb();
		$newsDb->DeletePage($this->moduleId);

	}
}


?>
