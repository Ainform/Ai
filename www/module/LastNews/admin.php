<?php
/**
 *
 * @copyright (c) by VisualDesign
 *
 */

class LastNewsModuleEdit extends BMC_BaseModule {


/**
 * Количество новостей на страницу
 */
    const NewsOnPage = 5;

    /**
     * Конструктор, задает параметры
     *
     * @return void
     */

    function __construct($moduleId) {
        parent::__construct($moduleId);
    }



    /**
     * Функция для создания html-кода модуля
     *
     * @return void
     */
    function DataBind() {

       /* $this->Model = new DAL_NewsDb;

        $this->curPage = Request('pageNum', 0);

        $action = CharOnly(Request("act","List"));

        if($action == "List") {
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
            if ($newsId == null){
                 $this->BindNewsList();  }
            else{
                 $this->Bindnews($newsId);  }
        }*/

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

			include_once '../generaterss.php';
            
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

			include_once '../generaterss.php';

			// возвращаемся к списку новостей
            Header("Location: ".$this->Url);
        }
                
        BMC_SiteMap::Update();
    }


}
?>