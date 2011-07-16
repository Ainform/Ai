<?php

class KarambaModuleEdit extends BMC_BaseModule {


/**
 * Количество новостей на страницу
 */
    const RecordsOnPage = 20;

    /**
     * Конструктор, задает параметры
     *
     * @return void
     */

    function __construct($moduleId) {
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
    function DataBind() {
      //Debug("test");
        $this->Model = new DAL_NewsDb;
             // Debug("test");
        $this->curPage = Request('pageNum', 0);
      //Debug("test");
        $action = CharOnly(Request("act","List"));
     // Debug("test");
        if($action == "List") {
                  //Debug("test");
            $this->Lister();
        }
      //Debug("test");
        // если указан идентификатор аналитики, то грузим саму аналитику, иначе список
        $newsId = Request('NewsId');

		$this->data['ModuleId'] = $this->moduleId;

        // пытаемся добавить аналитику
        $isNewNews = Request('newNews');

        if ($isNewNews == 1)
            $this->BindNew();
        else {
            if ($newsId == null){
                 $this->BindNewsList();  }
            else{
                 $this->Bindnews($newsId);  }
        }

    }

    public function Lister() {

    //$this->data["Info"] = $this->Model->GetAnaliticsByPage($this->moduleId,$this->curPage,self::RecordsOnPage);

    }
/*
    *Биндим данные для нового материала
 */
    public function BindNew() {

        Utility_FileUtility::DeleteFilesFromDirectory(Helpers_PathHelper::GetFullPath('upload').DAL_NewsDb::GetFolder());

        $this->data = array("title" => "", "text" => "","anons"=>"");
        $this->data['ImagesFolder'] = DAL_NewsDb::GetFolder();
        $this->data['FilesFolder'] = DAL_NewsDb::GetFolder();
        $this->data['date'] = date("d.m.Y", time());
        $this->data['ModuleId'] = $this->moduleId;

        $this->template = "newsEdit.tpl";

        BMC_SiteMap::Update();
    }

    public function BindNewsList() {
        $count = 0;

        $NewsDb = new DAL_NewsDb();
        $rows = $NewsDb->GetPage($this->moduleId, $this->curPage, self::RecordsOnPage);

        if (0 == count($rows))
            return;

		/*Пейджинг*/
        $allNews = $NewsDb->GetCount($this->moduleId); // количество всех новостей

        unset($NewsDb);
        $p = ceil($allNews/self::RecordsOnPage); // количество страниц
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

        $this->AddOrderToRows($rows, $this->curPage * self::RecordsOnPage);

        $this->data['NewsList'] = $rows;
    }

    public function BindNews($newsId) {
        $newsDb = new DAL_NewsDb();
        $news = $newsDb->GetOne($newsId);
        unset($newsDb);

        if (!isset($news))
            return;

        $this->data = $news;
        //приводим дату к удобочитаемому формату
        $this->data['date']=date("d.m.Y",$this->data['date']);

        //подключаем класс, управляющий файлами
        $analyticFilesUtility = new Utility_AnalyticFilesUtility();

        //задаем папку под файлы
        $analyticFilesUtility->SetDirectory($this->moduleFolder);

        //if ($news['onfront'] == 1)
				//$this->data['chkOnFront'] = "True";

        $this->data['FilesFolder'] = DAL_NewsDb::GetFolder($newsId);
        $this->data['ImagesFolder'] = DAL_NewsDb::GetFolder($newsId);
		$this->data['selectOnFront'] = $news['onfront'];

        $this->template = "newsEdit.tpl";
    }

    function handlerBtnNewsSave() {
        if (!IsValid()) return;

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
            $imageUtility->SetDirectory($this->moduleFolder);//если папка ещё не создана, создаём
            $imagesDb->MoveImages(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
            unset($imagesDb);

            // задаем папку под картинки и обновляем файлы
            $filesUtility->SetDirectory($this->moduleFolder);
            $filesUtility->UpdateFiles($this->data['fckText'], false);
            $filesUtility->UpdateFiles($this->data['fckAnons'], false);

            //$fulltext = $this->data['fckAnons'] + $this->data['fckText'];
            //$filesUtility->UpdateFiles($fulltext, true);

            // перемещаем файлы в нужную папку
            $filesDb = new DAL_AnalyticsFilesDb();
            $filesUtility->SetDirectory($this->moduleFolder);//если папка ещё не создана, создаём
            $filesDb->MoveFiles(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
            unset($filesDb);

			//include_once '../generaterss.php';

            Header("Location: ".$this->Url);
        }
        elseif (!empty($isNew)) {
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
            $filesUtility->SetDirectory($this->moduleFolder);//если папка ещё не создана, создаём
            $filesDb->MoveFiles(DAL_NewsDb::GetFolder(), DAL_NewsDb::GetFolder($newsId));
            unset($filesDb);

            // перемещаем картинки в нужную папку
            $imagesDb = new DAL_ImagesDb();
            $imageUtility->SetDirectory($this->moduleFolder);//если папка ещё не создана, создаём
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
            Header("Location: ".$this->Url);
        }

        BMC_SiteMap::Update();
    }

    function handlerBtnCancel() {
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
            Header("Location: ".$this->Url);
        }
        else
            RedirectToPageEdit($this->moduleId);

        die();
    }

    function handlerBtnDel($Id) {
        $newsDb = new DAL_NewsDb();
        $newsDb->Remove($Id);
        unset($newsDb);

        BMC_SiteMap::Update();

        $this->BindNewsList();
    }

}
?>