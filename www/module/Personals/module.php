<?
/**
 * PersonalsModule class
 * модуль лицензий
 */

class PersonalsModule extends BMC_BaseModule
{
	/**
	 * ���������� �������� �� ��������
	 */
	const RecordsOnPage = 10;

	/**
	 * ����� ������� ��������
	 *
	 * @var int
	 */
//	private $curPage;

	/**
		���������� ��������� ��� �������� ��������
	*/
	function GetPersonalsAlias($personalsId)
	{
		return "?personalsId=".$personalsId;
	}

	/**
		���������� ������ �� ������� �� �������������� �������
	*/
	function GetPersonalsLink($params)
	{
		if (is_array($params))
			$personalsId = $params['id'];
		else
			$personalsId = $params;
			return $this->GetVirtualPath().$this->GetPersonalsAlias($personalsId).'';
	}

	/**
	 * ������� ��� �������� html-���� ������
	 *
	 * @return void
	 */
	function DataBind()
	{
		$smarty = PHP_Smarty::GetInstance();
		$smarty->registerPlugin("function","personalsLink", array($this, "GetPersonalsLink"));

		$this->curPage = intval(Request('pageNum', 0));

		// ���� ������ ������������� �������, �� ������ ���� �������, ����� ������
		$personalsId = intval(Request('personalsId'));

		if ($personalsId == null)
			$this->BindPersonalsList();
		else
			$this->BindPersonals($personalsId);
	}



	public function BindPersonalsList()
	{

//		$smarty = PHP_Smarty::GetInstance();
//		$smarty->register_function("personalsLink", array($this, "GetPersonalsLink"));

		$count = 0;

		$personalsDb = new DAL_PersonalsDb();
			$rows = $personalsDb->GetPersonalsPage($this->moduleId,
										 $this->curPage,
										 self::RecordsOnPage);


		/*Пейджинг*/
		$allPersonals = $personalsDb->GetCountPersonals($this->moduleId); // количество всех новостей
		$p = ceil($allPersonals/self::RecordsOnPage); // количество страниц
		$pager = "<div class='pager'>";
		if($p>1){// если больше одной страницы
			for($i = 0;$i < $p;$i++){
				if($this->curPage == $i){// выделение страниц
					$pager .= "<span>".($i+1)."</span>";
				}else{
					$url = $this->GetVirtualPath();
					$pager .= "<a href='".$url."?pageNum=".$i."'>".($i+1)."</a>";
				}
			}

		}
		$this->data["Pager"] = $pager."</div>";
		/*Пейджинг*/



		unset($personalsDb);
		$imagesDb = new DAL_ImagesDb();
		foreach ($rows as &$row)
		{
			// �������� ��� �������
			$folder = DAL_PersonalsDb::GetImageFolder($row['PersonalsId']);

			// �������� ����� �������
			$image = $imagesDb->GetTopFromFolder($folder);

			if ($image == null)
				continue;

			// ���������� ���� �� ��
			$imgPath = DAL_ImagesDb::GetImagePath($image);

			// ��������� � �������
			$row['Image'] = '<img src="'.$imgPath.'&width=135" alt="'.$image['Title'].'" title="'.$image['Title'].'" width="135" border=0/>';
		}
		unset($imagesDb);

		if (0 == count($rows))
			return;

		$this->data['PersonalsList'] = $rows;
	}

	public function BindPersonals($personalsId)
	{
		$personalsDb = new DAL_PersonalsDb();
			$personals = $personalsDb->GetPersonalss($personalsId);
		unset($personalsDb);

		// �������� ��� �������
		$folder = DAL_PersonalsDb::GetImageFolder($personals['PersonalsId']);

		$imagesDb = new DAL_ImagesDb();

		// �������� �������� ��� �������
		$images = $imagesDb->GetFromFolder($folder);

		foreach ($images as &$image)
			$image['Path'] = DAL_ImagesDb::GetImagePath($image);

		$this->data['Personals'] = $personals;
		$this->data['Images'] = $images;
		$this->data['toFlow'] = count($images)*110;


		$curPage = BMC_SiteMap::GetCurPage();
		$this->breadCrumbs = array($curPage->Title=>$curPage->Path."/");
		$breadCrumbs = BLL_BreadCrumbs::getInstance();
		$breadCrumbs->Add($this->breadCrumbs);
		//Debug($breadCrumbs->Bind());
		 //$smarty->assign("Header",$personals['Title']);

		$this->header =$personals['Name'];


		$this->data['Name'] = $personals['Name'];
		$this->PageTitle = $personals['Name'];

		//Debug($personals['Title']);
		$this->template = "personals.tpl";
	}

	/**
		������� ��� ��������� ����-����

		@param siteMapNode $parentNode ������������ ���� ����� �����
	*/
	public function GenerateSiteMap()
	{
		$personalsDb = new DAL_PersonalsDb();
			$rows = $personalsDb->GetAllPersonals($this->moduleId);
		unset($personalsDb);

		$xml = "";

		foreach ($rows as $row)
		{
			$xml .= Helpers_SiteMap::CreateNode($this->GetPersonalsAlias($row['PersonalsId']),"", HtmlEncode($row['Name']), false, $this->moduleId, array("personalsId" => $row['PersonalsId']));
		}

		return $xml;
	}

	/**
		������� ��� ���������� ������ � ��������
	*/
	function OnModuleAdd()
	{
	}

	/**
		������� ��� �������� ������
	*/
	/*function OnModuleDelete()
	{
		$personalsDb = new DAL_PersonalsDb();
			$personals = $personalsDb->DeleteAllPersonals($this->moduleId);
		unset($personalsDb);
	}*/

	function __construct($moduleId)
	{

		$this->cssClass = "personalsModule";
		parent::__construct($moduleId);
	}
}
?>