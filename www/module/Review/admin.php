<?php
/**
 *
 * @copyright (c) by VisualDesign
 */

class ReviewModuleEdit extends BMC_BaseModule {
/**
 * Конструктор, задает параметры
 *
 * @return void
 */
    function __construct($moduleId) {
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
    public $curPage;

    /**
     * Функция для создания html-кода модуля
     *
     * @return void
     */
    function DataBind() {

      $this->curPage = Request('pageNum', 0);

        //изменяем емэйл

        $changeemail=Request('changeemail');

        if($changeemail) {

            $this->ChangeEmail();
        }

        // если указан идентификатор, то грузим сущность, иначе список
        $ReviewId = Request('ReviewId');

        // пытаемся добавить новую новость
        $isNewReview = Request('newReview');

        if ($isNewReview == 1)
            $this->BindNewReview();
        else {
            if ($ReviewId == null)
                $this->BindReviewList();
            else
                $this->BindReview($ReviewId);
        }
    }

    function ChangeEmail() {

        $configDb = new DAL_ConfigDb();
        $newRow=array("value"=>Request('EditReviewEmail'),"key"=>"Reviewemail","id"=>Request('ReviewEmailId'));
        $configDb->configUpdate($newRow);

    }

    public function BindReviewList() {
        $count = 0;

		//получаем мыло
        $configDb = new DAL_ConfigDb();
        $where = array("key" => "Reviewemail");
        $Reviewemail = $configDb->configSelect($where);
        $this->data['ReviewEmail'] = $Reviewemail['value'];
        $this->data['ReviewEmailId'] = $Reviewemail['id'];

        

        $ReviewDb = new DAL_ReviewDb();

        $rows = $ReviewDb->GetReviewPage($this->moduleId, $this->curPage, self::RecordsOnPage);

        if (0 == count($rows))
            return;

		/*Пейджинг*/
        $allReview = $ReviewDb->GetCountReview($this->moduleId); // количество всех новостей
        unset($ReviewDb);
        $p = ceil($allReview/self::RecordsOnPage); // количество страниц
        $pager = "<div class='pager'>";
        if($p>1) {// если больше одной страницы
            for($i = 0;$i < $p;$i++) {
                if($this->curPage == $i) {// выделение страниц
                    $pager .= "<span>".($i+1)."</span>&nbsp;";
                }else {
                    $url = $this->GetVirtualPath();
                    $pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
                }
            }

        }
        $this->data["Pager"] = $pager."<div>";
		/*Пейджинг*/
		
		$this->data['ReviewList'] = $rows;
    }

    public function BindNewReview() {
    // подключаем класс, управляющий изображениями в тексте
        $imageUtility = new Utility_ImageUtility();

        $this->data['ImageFolder'] = DAL_ReviewDb::GetImageFolder();
        $this->data['Review'] = array("Title" => "", "Anons" => "", "Text" => "");
        $this->data['Review']['Date'] = date("d.m.Y", time());
        $this->data['Review']['ModuleId'] = $this->moduleId;

        $this->template = "Reviewedit.tpl";

        BMC_SiteMap::Update();
    }

    public function BindReview($ReviewId) {
        $ReviewDb = new DAL_ReviewDb();
        $Review = $ReviewDb->GetReview($ReviewId);
        // Debug($Review);
        unset($ReviewDb);

        if (!isset($Review))
            return;

        $this->data['Review'] = $Review;
        $this->template = "admin_show_item.tpl";
    }

    function handlerBtnDel($ReviewId) {
        $ReviewDb = new DAL_ReviewDb();
        $ReviewDb->DeleteReview($ReviewId);
        unset($ReviewDb);

        BMC_SiteMap::Update();

        $this->BindReviewList();
    }

    function handlerShow($ReviewId) {

        $ReviewDb = new DAL_ReviewDb();
        $ReviewDb->Show($ReviewId);
        unset($ReviewDb);

        BMC_SiteMap::Update();

        $this->BindReviewList();
    }

    function handlerBtnUp($ReviewId)
	{
		$db = new DAL_ReviewDb();
		$db->Up($ReviewId);

		unset($db);

		$this->BindReviewList();
	}

	function handlerBtnDown($ReviewId)
	{
		$db = new DAL_ReviewDb();
		$db->Down($ReviewId);

		unset($db);

		$this->BindReviewList();
	}

    //TODO сделать возможность сортировки вверх и вниз
    function handlerBtnSave($ReviewId) {
            $ReviewRow = Array();
            $ReviewRow['ModuleId'] = $this->moduleId;
             $ReviewRow['Text'] = strip_tags($this->data['fckText']);
             $ReviewRow['ReviewId'] = $ReviewId; 
 
            $ReviewDb = new DAL_ReviewDb();
            $ReviewId = $ReviewDb->UpdateReview($ReviewRow);
            unset($ReviewDb);

    Header("Location: ".$this->Url);    
    }

    function handlerBtnReviewSave() {
        if (!IsValid())
            return;

        $ReviewId = Request('ReviewId');

        // пытаемся добавить новую новость
        $isNewReview = Request('newReview');

        // подключаем класс, управляющий изображениями в тексте
        $imageUtility = new Utility_ImageUtility();

        if (!empty($ReviewId)) {
            $ReviewRow = Array();
            $ReviewRow['ReviewId'] = $ReviewId;
            $ReviewRow['Title'] = HtmlEncode($this->data['txtTitle']);
            $ReviewRow['Anons'] = $this->data['fckAnons'];
            $ReviewRow['Text'] = $this->data['fckText'];
            $ReviewRow['Date'] = strtotime($this->data['txtReviewDate']);

            // задаем папку под картинки и обновляем файлы
            $imageUtility->SetDirectory('Review'.$ReviewId);
            $imageUtility->UpdateFiles($this->data['fckText']);

            $ReviewDb = new DAL_ReviewDb();
            $ReviewDb->UpdateReview($ReviewRow);
            unset($ReviewDb);

            Header("Location: ".$this->Url); 
        }elseif (!empty($isNewReview)) {
            $ReviewRow = Array();
            $ReviewRow['ModuleId'] = $this->moduleId;
            $ReviewRow['Title'] = HtmlEncode($this->data['txtTitle']);
            $ReviewRow['Email'] = $this->data['txtEmail'];
            $ReviewRow['Topic'] = $this->data['fckTopic'];
            $ReviewRow['Text'] = $this->data['fckMessage'];
            //Debug($ReviewRow);
            // добавяляем новость и получаем её идентификатор
            $ReviewDb = new DAL_ReviewDb();
            //Debug($ReviewRow);
            $ReviewId = $ReviewDb->AddReview($ReviewRow);
            // Debug($ReviewId);
            unset($ReviewDb);

            // перемещаем картинки в нужную папку
            $imagesDb = new DAL_ImagesDb();
            $imagesDb->MoveImages(DAL_ReviewDb::GetImageFolder(), DAL_ReviewDb::GetImageFolder($ReviewId));
            unset($imagesDb);

            // задаем папку под картинки и обновляем файлы
            $imageUtility->MoveFiles($this->data['fckText'], 'Review'.$ReviewId);
            $ReviewRow['Text'] = $this->data['fckText'];
            $ReviewRow['ReviewId'] = $ReviewId;
            //Debug($ReviewRow);
            // обновляем текст новости с учетом перемещенных картинок
            $ReviewDb = new DAL_ReviewDb();
            $ReviewId = $ReviewDb->UpdateReview($ReviewRow);
            unset($ReviewDb);

            // возвращаемся к списку новостей
            Header("Location: ".$this->Url);
        }

        BMC_SiteMap::Update();
        die();
    }
}

?>