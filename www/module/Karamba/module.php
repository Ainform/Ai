<?

class KarambaModule extends BMC_BaseModule {

    const RecordsOnMainPage = 5;
    const RecordsOnPage = 2;

    //private $curPage;
    //public $curPage;

    function GetAlias($id) {
        return "?newsId=".$id;
    }
    function GetLink($params) {
        if (is_array($params))
            $id = $params['id'];
        else
            $id = $params;

        //var_dump($this->GetVirtualPath().$this->GetAlias($id).'');
        $db=new DAL_BaseDb();
        $temp=$db->query("SELECT alias,parent FROM pagemodules LEFT JOIN pages ON pagemodules.PageId=pages.PageId WHERE ModuleId=".$id);
        $temp0=$db->query("SELECT alias FROM pages WHERE PageId=".$temp[0]['parent']);
        $alias0=$temp0[0]['alias'];
        $alias=$temp[0]['alias'];


        $str = "/".$alias0."/".$alias."/";

        return $str;
        //return str_replace("http:/", "http://", $this->GetVirtualPath().$this->GetAlias($id).'');
    }

    function ToDate($date) {
        return date("dd.mm.yyyy", $date);
    }

    function DataBind() {
        $smarty = PHP_Smarty::GetInstance();
        $smarty->registerPlugin("function","newsLink", array($this, "GetLink"));

        $smarty->assign('ShowTitle', false);

        $this->curPage = intval(Request('npageNum', 0))+ intval(Request('pageNum', 0));
        $newsId = intval(Request('newsId'));

        if (Request("activityId", "") != "" || Request("commentsId", "") != "" || Request("projectsId", "") != "")
            return ;
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

    public function BindList() {
        if (isset($_REQUEST['lifestyle'])) {
            $this->data['lifestyle'] = 1;
        }
        if (isset($_REQUEST['trading'])) {
            $this->data['trading'] = 1;
        }

        $count = 0;
        $itemsOnPage = self::RecordsOnPage;

        $newsDb = new DAL_NewsDb();

        $rows0 = $newsDb->GetPage(390,$this->curPage, 1);
        $rows1 = $newsDb->GetPage(391,$this->curPage, 1);
        $rows2 = $newsDb->GetPage(392,$this->curPage, 1);

        $rows3 = $newsDb->GetPage(402,$this->curPage, $itemsOnPage);
        $rows4 = $newsDb->GetPage(383,$this->curPage, $itemsOnPage);
        $rows5 = $newsDb->GetPage(384,$this->curPage, $itemsOnPage);

        $rows6 = $newsDb->GetPage(387,$this->curPage, $itemsOnPage);
        $rows7 = $newsDb->GetPage(386,$this->curPage, $itemsOnPage);
        $rows8 = $newsDb->GetPage(388,$this->curPage, $itemsOnPage);

        unset($newsDb);

        $imagesDb = new DAL_ImagesDb();

        for($i=0;$i<9;$i++) {

            $temp = "rows".$i;
            foreach ($$temp as $key=>&$row) {

                $folder = DAL_NewsDb::GetFolder($row['id']);
                $image = $imagesDb->GetTopFromFolder($folder);

                if ($image == null)
                    continue;

                $imgPath = DAL_ImagesDb::GetImagePath($image);

                $row['Image'] = '<img src="'.$imgPath.'&width=240&height=170&crop=1" alt="'.$image['Title'].'" title="'.$image['Title'].'" class="big_news" align="left"  />';
            }
        }
        unset($imagesDb);
        //Debug($rows3);

        $this->data['List'][] = $rows0;
        $this->data['List'][] = $rows1;
        $this->data['List'][] = $rows2;
        $this->data['List'][] = $rows3;
        $this->data['List'][] = $rows4;
        $this->data['List'][] = $rows5;
        $this->data['List'][] = $rows6;
        $this->data['List'][] = $rows7;
        $this->data['List'][] = $rows8;


    }

    public function BindNews($newsId) {
        $newsDb = new DAL_NewsDb();
        $news = $newsDb->GetOne($newsId);
        unset($newsDb);

        $folder = DAL_NewsDb::GetFolder($news['id']);

        $newsFilesDb = new DAL_AnalyticsFilesDb();

        $newsfiles = $newsFilesDb->GetFromFolder($folder);

        $this->DistibuteFiles(&$newsfiles, "news$newsId");

        $this->data['News'] = $news;

        $curPage = BMC_SiteMap::GetCurPage();
        $this->breadCrumbs = array($curPage->Title=>$curPage->Path."/");
        $breadCrumbs = BLL_BreadCrumbs::getInstance();
        $breadCrumbs->Add($this->breadCrumbs);
        //Debug($breadCrumbs->Bind());
        //$smarty->assign("Header",$news['Title']);

        // РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р… РїС—Р…РїС—Р…РїС—Р… РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…
        $folder = DAL_NewsDb::GetFolder($news['id']);

        $imagesDb = new DAL_ImagesDb();

        // РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р… РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р… РїС—Р…РїС—Р…РїС—Р… РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…РїС—Р…
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

        $this->header =$news['title'];

        $this->data['Title'] = $news['title'];
        $this->data['FirstPhoto'] = count($images) != 0 ? $images[0] : null;

        $this->PageTitle = $news['title'];

        $smarty = PHP_Smarty::GetInstance();
        $smarty->assign("Title", $news['title']);


        //Debug($news['Title']);
        $this->template = "news.tpl";
    }

    public function GenerateSiteMap() {
        $newsDb = new DAL_NewsDb();

        $rows = $newsDb->GetByModuleId($this->moduleId);

        unset($newsDb);

        $xml = "";

        foreach ($rows as $row) {
            $xml .= Helpers_SiteMap::CreateNode($row["Alias"].$this->GetAlias($row["id"]).'',"", HtmlEncode($row['title']), false, $this->moduleId, array("id" => $row['id']), "", true);
        }

        return $xml;
    }

    public function OnModuleDelete() {
        $newsDb = new DAL_NewsDb();
        $newsDb->DeletePage($this->moduleId);

    }
}


?>