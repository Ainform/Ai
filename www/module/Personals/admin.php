<?php
/**
 * PersonalsEdit class
 * Редактирование лицензий
 * 
 * @copyright (c) by VisualDesign
 */

class PersonalsModuleEdit extends BMC_BaseModule
{
	/**
	 * Конструктор, задает параметры
	 *
	 * @return void
	 */
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}

	/**
	 * Количество новостей на страницу
	 */
	const RecordsOnPage = 10;

	/**
	 * Номер текущей страницы
	 *
	 * @var int
	 */
	//public $curPage;

	/**
	 * Функция для создания html-кода модуля
	 * 
	 * @return void
	 */
	function DataBind()
	{
		$this->curPage = Request('pageNum', 0);

		// если указан идентификатор новости, то грузим саму новость, иначе список
		$personalsId = Request('personalsId');

		// пытаемся добавить новую новость
		$isNewPersonals = Request('newPersonals');

		if ($isNewPersonals == 1)
		$this->BindNewPersonals();
		else
		{
			if ($personalsId == null)
			$this->BindPersonalsList();
			else
			$this->BindPersonals($personalsId);
		}
	}

	public function BindPersonalsList()
	{
		$count = 0;

		$personalsDb = new DAL_PersonalsDb();
		$rows = $personalsDb->GetPersonalsPage($this->moduleId, $this->curPage, self::RecordsOnPage);
		

		if (0 == count($rows))
		return;

		/*Пейджинг*/
		$allPersonals = $personalsDb->GetCountPersonals($this->moduleId); // количество всех новостей
		unset($personalsDb);
		$p = ceil($allPersonals/self::RecordsOnPage); // количество страниц
		$pager = "<div class='pager'>";
		if($p>1){// если больше одной страницы
			for($i = 0;$i < $p;$i++){
				if($this->curPage == $i){// выделение страниц
					$pager .= "<span>".($i+1)."</span>&nbsp;";
				}else{
					$url = $this->GetVirtualPath();
					$pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
				}
			}
			
		}
		$this->data["Pager"] = $pager."<div>";
		/*Пейджинг*/
		
		
		$this->data['PersonalsList'] = $rows;
	}

	public function BindNewPersonals()
	{
		// подключаем класс, управляющий изображениями в тексте
		$imageUtility = new Utility_ImageUtility();

		$this->data['ImageFolder'] = DAL_PersonalsDb::GetImageFolder();
		$this->data['Personals'] = array("Name" => "", "Anons" => "", "Position" => "");
		$this->data['Personals']['Date'] = date("d.m.Y", time());
		$this->data['Personals']['ModuleId'] = $this->moduleId;

		$this->template = "personalsedit.tpl";

		BMC_SiteMap::Update();
	}

	public function BindPersonals($personalsId)
	{
		$personalsDb = new DAL_PersonalsDb();
		$personals = $personalsDb->GetPersonalss($personalsId);
		unset($personalsDb);

		if (!isset($personals))
		return;
//Debug($personals);
		$this->data['Personals'] = $personals;

		// подключаем класс, управляющий изображениями в тексте
		$imageUtility = new Utility_ImageUtility();

		// задаем папку под картинки
		$imageUtility->SetDirectory('personals/'.$personalsId);

		$this->data['ImageFolder'] = DAL_PersonalsDb::GetImageFolder($personalsId);

		$this->template = "personalsedit.tpl";
	}

	function handlerBtnDel($personalsId)
	{
		$personalsDb = new DAL_PersonalsDb();
		$personalsDb->DeletePersonals($personalsId);
		unset($personalsDb);

		BMC_SiteMap::Update();

		$this->BindPersonalsList();
	}

	function handlerBtnCancel()
	{
		$personalsId = Request('personalsId');
		$isNewPersonals = Request('newPersonals');

		if ($personalsId != null || $isNewPersonals)
		{
			Header("Location: ".$this->Url);

			// отмена создания новости
			if ($isNewPersonals)
			{
				// удаляем старые картинки
				$imagesDb = new DAL_ImagesDb();
				$imagesDb->DeleteFolder(DAL_PersonalsDb::GetImageFolder());
				unset($imagesDb);

				// подключаем класс, управляющий изображениями в тексте
				$imageUtility = new Utility_ImageUtility();

				$imageUtility->DeleteFiles($this->data['fckText']);
			}
		}
		else
		RedirectToPageEdit($this->moduleId);

		die();
	}

	function handlerBtnPersonalsSave()
	{
		if (!IsValid())
		return;

		$personalsId = Request('personalsId');

		// пытаемся добавить новую новость
		$isNewPersonals = Request('newPersonals');

		// подключаем класс, управляющий изображениями в тексте
		$imageUtility = new Utility_ImageUtility();
		
		if (!empty($personalsId))
		{
$personalsRow = Array();
			$personalsRow['PersonalsId'] = $personalsId;
			$personalsRow['Name'] = HtmlEncode($this->data['txtName']);
                        $personalsRow['Position'] = HtmlEncode($this->data['txtPosition']);
			$personalsRow['Anons'] = $this->data['fckText'];

			// задаем папку под картинки и обновляем файлы
			//$imageUtility->SetDirectory('personals/'.$personalsId);
			//$imageUtility->UpdateFiles($this->data['fckText']);

			$personalsDb = new DAL_PersonalsDb();
			$personalsDb->UpdatePersonals($personalsRow);
			unset($personalsDb);

			Header("Location: ".$this->Url);
		}elseif (!empty($isNewPersonals)){
			$personalsRow = Array();
			$personalsRow['ModuleId'] = $this->moduleId;
			$personalsRow['Name'] = HtmlEncode($this->data['txtName']);
                        $personalsRow['Position'] = HtmlEncode($this->data['txtPosition']);
			$personalsRow['Anons'] = $this->data['fckText'];
			// добавяляем новость и получаем её идентификатор
			$personalsDb = new DAL_PersonalsDb();
			$personalsId = $personalsDb->AddPersonals($personalsRow);
			unset($personalsDb);

			// перемещаем картинки в нужную папку
			$imagesDb = new DAL_ImagesDb();
			$imagesDb->MoveImages(DAL_PersonalsDb::GetImageFolder(), DAL_PersonalsDb::GetImageFolder($personalsId));
			unset($imagesDb);

			// задаем папку под картинки и обновляем файлы
			//$imageUtility->MoveFiles($this->data['fckText'], 'personals/'.$personalsId);
			//$personalsRow['Text'] = $this->data['fckText'];
			//$personalsRow['PersonalsId'] = $personalsId;
			//Debug($personalsRow);
			// обновляем текст новости с учетом перемещенных картинок
			//$personalsDb = new DAL_PersonalsDb();
			//$personalsId = $personalsDb->UpdatePersonals($personalsRow);
			//unset($personalsDb);

			// возвращаемся к списку новостей
			Header("Location: ".$this->Url);
		}

		BMC_SiteMap::Update();
		die();
	}

	function handlerBtnUp($personalsId)
	{
		$db = new DAL_PersonalsDb();
		$db->Up($personalsId);

		unset($db);

		$this->BindPersonalsList();
	}

	function handlerBtnDown($personalsId)
	{
		$db = new DAL_PersonalsDb();
		$db->Down($personalsId);

		unset($db);

		$this->BindPersonalsList();
	}
}

?>