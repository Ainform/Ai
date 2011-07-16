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

class BMC_BaseModule {
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
    public function DataBind() {
    }

    /**
     Генерирует сайтмап для модуля
     */
    public function GenerateSiteMap() {
        return "";
    }

    /**
     Событие при добавлении модуля к странице
     */
    public function OnModuleAdd() {
    }

    /**
     Событие при удалении модуля
     */
    public function OnModuleDelete() {
    }

    function getTemplatePath() {
        return $this->folder.$this->template;
    }

    /**
     Возвращает виртуальный (ЧПУ) путь до модуля
     */
    public function GetVirtualPath() {
        return dirname($this->Url).'/'.basename($this->Url, '.php').'/';
    }

    public function __construct($moduleId) {
        $this->moduleId = $moduleId;
        $this->pageVar  = "pageVar$moduleId";
        $this->curPage = intval(Request($this->pageVar, 0));
    }

    public function getData() {
        return new ModuleData($this->data);
    }

    // распределение файлов по их типу
    public function DistibuteFiles($filelist, $entityspec) {
        $videolist = null;
        $soundlist = null;
        $outFiles = null;

        $playlist = "<?xml version='1.0' encoding='UTF-8'?><xml>";

        foreach ($filelist as &$file) {
            $file['Path'] = DAL_AnalyticsFilesDb::GetfilePath($file);

            $filename = strtolower($file['filename']);

            if (strpos($filename, ".flv") !== false || strpos($filename, ".swf") !== false)
                $videolist[] = SiteUrl.$file['Path'].$file['folder'].$file['filename'];
            else
            if (strpos($filename, ".mp3") !== false) {
                $soundlist[] = array("Title" => $file["title"], "Path" => SiteUrl.$file['Path'].$file['folder'].$file['filename']);
                $playlist .= "
<track>
	<path>".SiteUrl.$file['Path'].$file['folder'].$file['filename']."</path>
	<title>".$file["title"]."</title>
</track>";
            }
            else
                $outFiles[] = $file;
        }

        $this->data['Files'] = $outFiles;
        $this->data['VideoList'] = $videolist;
        $this->data['SoundList'] = $soundlist;
        $this->data['PlayList'] = SiteUrl."playlists/playlist$entityspec.xml";
        $this->data['Mp3PlayerHeight'] = count($soundlist) >= 10 ? 337 : (100 + count($soundlist) * 20);

        if (count($soundlist)) {
            //echo "playlists/playlist$entityspec.xml";
            file_put_contents("playlists/playlist$entityspec.xml", $playlist);
        }
    }

    public function AddOrderToRows($rows, $beginIndex) {
        foreach ($rows as &$row) {
            $row["Numer"] = ++$beginIndex;
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

    public function SetPager($pageCount, $pageVar, $postfix="") {
        $pager = "<div class='pager'><span class='per'>Страница: </span>";

        $countPagingNums = 11;

        if ($pageCount > 1) {// если больше одной страницы
            if(($this->curPage)>5) {//показываем текущую страницу и по 5 слева и справа, т.е. плавающая область по 10 страниц
                for($i = $this->curPage - 5;$i < $this->curPage + 5;$i++) {
                    if (($pageCount) > $i && $i != -1 && $i>-1) {
                        if($this->curPage == $i) {// выделение страниц
                            $pager .= "<span>".($i+1).($pageCount != $i + 1 ? ", " : "")."</span>";
                        }
                        else {
                            $url = $this->GetVirtualPath();
                            $pager .= "<a href='?".$pageVar."=".$i."$postfix'>".($i + 1)."</a>".($pageCount != $i + 1 ? ", " : "");
                        }
                    }
                }
            }else {
                for($i = 0;$i < 10;$i++) {
                    if (($pageCount) > $i && $i != -1 && $i>-1) {
                        if($this->curPage == $i) {// выделение страниц
                            $pager .= "<span>".($i+1).($pageCount != $i + 1 ? ", " : "")."</span>";
                        }
                        else {
                            $url = $this->GetVirtualPath();
                            $pager .= "<a href='?".$pageVar."=".$i."$postfix'>".($i + 1)."</a>".($pageCount != $i + 1 ? ", " : "");
                        }
                    }
                }
            }
            if ($pageCount > $this->curPage + $countPagingNums - 1)
                $pager .= "<a href='?".$pageVar."=".(($pageCount % $countPagingNums) != 0 ? $this->curPage + ($pageCount % $countPagingNums) : $this->curPage + $countPagingNums - 1)."#news'>...</a>";
        }

        $this->data["Pager"] = $pageCount == 1 || $pageCount == 0 ? "" : $pager."</div>";
    }
}

class ModuleData implements ArrayAccess {
    private $data;

    function __construct($array) {
        $this->data = $array;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        if (isset($this->data[$offset]))
            return $this->data[$offset];

        return null;
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
}

?>