<?

class PagesListModule extends BMC_BaseModule {
    #region Data Binding
    /**
      Биндим данные
     */

    public function DataBind() {

        $pageId = Request('id');

        if (isset($pageId) && $pageId >= 0)
            return $this->BindPageEdit($pageId);

        $parentId = Request('parent');

        if (isset($parentId) && $parentId >= 0)
            return $this->BindNewPage($parentId);

        BMC_SiteMap::Update();

    }

    /**
      Биндим данные для редактирования страницы
      @param int $pageId идентификатор страницы
     */
    function BindPageEdit($pageId) {

        if (!$this->isPostBack) {
            $pagesDb = new DAL_PagesDb();
            $page = $pagesDb->GetPage($pageId);

            unset($pagesDb);

            $this->data['txtName'] = $page['Name'];
            $this->data['txtTitle'] = $page['Title'];
            $this->data['txtKeywords'] = $page['Keywords'];
            $this->data['txtDescription'] = $page['Description'];
            $this->data['txtAlias'] = empty($page['Alias']) ? "index" : $page['Alias'];

            if ($page['Visible'] == 1)
                $this->data['chkVisible'] = "True";

            if ($page['Horizontal'] == 1)
                $this->data['chkHorizontal'] = "True";

            if ($page["HideInMain"] == 1)
                $this->data['chkHideInMain'] = "True";

            if ($page["WithoutDesign"] == 1)
                $this->data['chkWithoutDesign'] = "True";
        }

        // биндим список родительские разделов
//		$this->BindParents($pageId);

        $list = $this->BindModulesList();

        $modulesDb = new DAL_ModulesDb();
        $modules = $modulesDb->GetModules($pageId);
        unset($modulesDb);
        //Debug($page,false);
        foreach ($modules as &$module)
            $module['Title'] = $list[$module['ModuleType']];

        $this->data['PageModules'] = $modules;
        $this->data['NewPage'] = false;
        //FIXME чёт здесь с page дурит
        $this->data['HasParent'] = @$page['Parent'] == 0 ? false : true;
        $this->template = "pageedit.tpl";
    }

    /**
      Биндит данные по родительским разделам
     */
    /* 	function BindParents($pageId = null, $parentId = null)
      {
      $parents = array();
      $pages = BMC_SiteMap::GetSitePages(true);
      foreach ($pages as $page)
      {
      if ($pageId != null && $page->PageId == $pageId)
      continue;

      if(!$page->IsInnerModule)
      {
      $parents[$page->Title] = str_repeat("&nbsp;", 3 * ($page->Level - 1)).$page->Title;
      }
      }
      $this->data['Parents'] = $parents;
      } */

    /**
      Биндим данные для создания новой страницы
      @param int $parentId идентификатор родительской страницы
     */
    function BindNewPage($parentId) {
        if (!$this->isPostBack) {
            /* 			$pagesDb = new DAL_PagesDb();
              $page = $pagesDb->GetPage($pageId);
              unset($pagesDb); */

            $this->data['txtName'] = "";
            $this->data['txtTitle'] = "";
            $this->data['txtKeywords'] = "";
            $this->data['txtDescription'] = "";
            $this->data['txtAlias'] = "";
            $this->data['chkVisible'] = "True";
            $this->data['chkHideInMain'] = "0";
            $this->data['chkHorizontal'] = "0";
            $this->data['chkWithoutDesign'] = "0";
        }

        // биндим список родительские разделов
//		$this->BindParents(null, $parentId);

        $this->BindModulesList();
        $this->data['NewPage'] = true;
        $this->template = "pageedit.tpl";
    }

    /**
      Биндим список модулей
     */
    function BindModulesList() {
        $modulesDb = new DAL_ModulesDb();
        $modules = $modulesDb->GetModulesList();
        unset($modulesDb);

        $arr = array();
        foreach ($modules as $module) {
            if ($module['isAdmin'] != "True")
                $arr[(string) $module['alias']] = (string) $module['title'];
        }

        $this->data['Modules'] = $arr;
        return $arr;
    }

    #endregion
    #region TreeView Data Generator
    /**
      Биндим данные для TreeView
     */

    function handlerBindSiteTree() {
        $pages = BMC_SiteMap::GetSitePages();
        $tree = array
                (
                /* array("title" => "Структура сайта",
                  "onclick" => "nodeSelect",
                  "id" => "0") */
        );

        foreach ($pages as $page) {
            $title = $page->Title;

            if ($page->IsInnerModule)
                continue;

            if (!$page->Visible)
                $title = '<i>' . $title . ' (скрыто)</i>';

            $tree[] = array(
                "id" => $page->PageId,
                "title" => $title,
                "children" => $this->BindChild($page),
                "url" => $this->Url . "?id=" . $page->PageId,
                "target" => "_self",
            );
        }

        $this->data['Tree'] = $tree;
        $this->data['NewPage'] = true;
        echo json_encode($tree);
        die();
    }

    function BindChild($page) {
        $tree = array();

        if ($page->HasChild())
            foreach ($page->Childs as $child) {
                $title = $child->Title;

                if ($child->IsInnerModule)
                    continue;

                if (!$child->Visible)
                    $title = '<i>' . $title . ' (скрыто)</i>';

                if (isset($child->PageId))
                    $tree[] = array("id" => $child->PageId,
                        "title" => $title,
                        "children" => $this->BindChild($child),
                        "url" => $this->Url . "?id=" . $child->PageId,
                        "target" => "_self",
                    );
            }
        return $tree;
    }

    function handlerBtnBefore() {

        if (!isset($this->data['firstid']) || !isset($this->data['secondid']) )
            die();

        $pagesDb = new DAL_PagesDb();
        if ($this->data['firstid'] == -1) {
            //корневой элемент может быть только один
            die();
        } else {
            $firstsec = $pagesDb->GetPage($this->data['firstid']);
            $sourceSec = $pagesDb->GetPage($this->data['secondid']);

            if ($firstsec['Parent'] == $sourceSec['Parent']) {
//если в той же ветке
                $pages = $pagesDb->GetPages($sourceSec['Parent']);
                foreach ($pages as $page) {
                    if ($page['Order'] >= $firstsec['Order'] && $page['Order'] < $sourceSec["Order"]) {
                        $pagesDb->UpdatePage(array("PageId" => $page['PageId'], "Order" => $page['Order'] + 1));
                    }
                }
                $pagesDb->UpdatePage(array("PageId" => $sourceSec['PageId'], "Order" => $firstsec['Order']));
            } else {
//если перемещаем в другую ветку
                $pages = $pagesDb->GetPages($sourceSec['Parent']);
                foreach ($pages as $page) {
                    if ($page['Order'] >= $firstsec['Order']) {
                        $pagesDb->UpdatePage(array("PageId" => $page['PageId'], "Order" => $page['Order'] + 1));
                    }
                }
                $pagesDb->UpdatePage(array("PageId" => $sourceSec['PageId'], "Order" => $firstsec['Order'], "Parent" => $firstsec['Parent']));
            }

            unset($pagesDb);
            BMC_SiteMap::Update();
            die(0);
        }
    }

    function handlerBtnAfter() {

        if (!isset($this->data['firstid']) || !isset($this->data['secondid']))
            die();

        $pagesDb = new DAL_PagesDb();
        if ($this->data['firstid'] == -1) {
            //корневой элемент может быть только один
            die();
        } else {
            $firstsec = $pagesDb->GetPage($this->data['firstid']);
            $sourceSec = $pagesDb->GetPage($this->data['secondid']);

            if ($firstsec['Parent'] == $sourceSec['Parent']) {
//если в той же ветке
                $pages = $pagesDb->GetPages($firstsec['Parent']);
                foreach ($pages as $page) {
                    if ($page['Order'] > $sourceSec['Order'] && $page['Order'] <= $firstsec["Order"]) {
                        $pagesDb->UpdatePage(array("PageId" => $page['PageId'], "Order" => $page['Order'] - 1));
                    }
                }
                $pagesDb->UpdatePage(array("PageId" => $sourceSec['PageId'], "Order" => $firstsec['Order']));
            } else {
//если перемещаем в другую ветку
                $pages = $pagesDb->GetPages($firstsec['Parent']);
                foreach ($pages as $page) {
                    if ($page['Order'] > $firstsec['Order']) {
                        $pagesDb->UpdatePage(array("PageId" => $page['PageId'], "Order" => $page['Order'] + 1));
                    }
                }
                $pagesDb->UpdatePage(array("PageId" => $sourceSec['PageId'], "Order" => $firstsec['Order'] + 1, "Parent" => $firstsec['Parent']));
            }

            unset($pagesDb);
            BMC_SiteMap::Update();
            die(0);
        }
    }

    function handlerBtnOver() {

        if (!isset($this->data['firstid']) || !isset($this->data['secondid']))
            die();

        $pagesDb = new DAL_PagesDb();
        if ($this->data['firstid'] == 0) {
            $firstsec = array("PageId" => 0);
        } else {
            $firstsec = $pagesDb->GetPage($this->data['firstid']);
        }
        $sourceSec = $pagesDb->GetPage($this->data['secondid']);

        $pages = $pagesDb->GetPages($firstsec['PageId']);
        $temp = -1;
        foreach ($pages as $page) {
            if ($page['Order'] > $temp) {
                $temp = $page['Order'];
            }
        }

        $pagesDb->UpdatePage(array("PageId" => $sourceSec['PageId'], "Order" => $temp + 1, "Parent" => $firstsec['PageId']));


        unset($pagesDb);
        BMC_SiteMap::Update();
        die(0);
    }

     function handlerBtnNew() {
        if (!isset($this->data['parentId']))
            die();

        $title=isset($this->data['title'])?$this->data['title']:"Новый раздел";

        $parentId = $this->data['parentId'];

        $page = array();
        $page['Parent'] = $parentId;
        $page['Name'] = $title;

        $pagesDb = new DAL_PagesDb();
        $pageId = $pagesDb->AddPage($page);
        unset($pagesDb);

        BMC_SiteMap::Update();

        echo $this->Url . "?id=" . $pageId;
        die();
    }

    #endregion
    #region TreeView Handlers

    function handlerBtnUpdate() {
        BMC_SiteMap::Update();

        Header("Location: " . $this->Url);
    }

    function handlerBtnDelete() {
        if (!isset($this->data['pageId']))
            return;

        $pageId = $this->data['pageId'];

        $pagesDb = new DAL_PagesDb();
        $page = $pagesDb->DeletePage($pageId);
        unset($pagesDb);

        BMC_SiteMap::Update();

        die(0);
    }

    function handlerBtnUp() {
        if (!isset($this->data['pageId']))
            return;

        $pageId = $this->data['pageId'];

        $pagesDb = new DAL_PagesDb();
        $page = $pagesDb->Up($pageId);
        unset($pagesDb);

        BMC_SiteMap::Update();

        die(0);
    }

    function handlerBtnDown() {
        if (!isset($this->data['pageId']))
            return;

        $pageId = $this->data['pageId'];

        $pagesDb = new DAL_PagesDb();
        $page = $pagesDb->Down($pageId);
        unset($pagesDb);

        BMC_SiteMap::Update();

        die(0);
    }

    function handlerBtnMove() {
        if (!isset($this->data['pageMoveId']) || !isset($this->data['pageDropId']))
            return;

        $pageMoveId = $this->data['pageMoveId'];
        $pageDropId = $this->data['pageDropId'];

        $pagesDb = new DAL_PagesDb();
        $page = $pagesDb->UpdatePage(array("PageId" => $pageMoveId, "Parent" => $pageDropId));
        unset($pagesDb);

        BMC_SiteMap::Update();

        die(0);
    }

    function handlerBtnCancel() {
        Header("Location: " . $this->Url);
    }

    #endregion
    #region PageEdit Handlers

    function handlerBtnSave() {
        if (!IsValid())
            return;

        $page = Array();

        $pageId = Request('id');
        if (isset($pageId) && $pageId >= 0) {
            $page['PageId'] = Request('id');
            $page['Name'] = HtmlEncode($this->data['txtName']);
            $page['Title'] = HtmlEncode($this->data['txtTitle']);
            $page['Keywords'] = HtmlEncode($this->data['txtKeywords']);
            $page['Description'] = HtmlEncode($this->data['txtDescription']);
            $page['Visible'] = empty($this->data['chkVisible']) ? 0 : 1;
            $page['Horizontal'] = empty($this->data['chkHorizontal']) ? 0 : 1;
            $page['HideInMain'] = empty($this->data['chkHideInMain']) ? 0 : 1;
            $page['WithoutDesign'] = empty($this->data['chkWithoutDesign']) ? 0 : 1;

            if ('index' == $this->data['txtAlias'])
                $page['Alias'] = '';
            else
                $page['Alias'] = $this->data['txtAlias'];

            $pagesDb = new DAL_PagesDb();
            $page = $pagesDb->UpdatePage($page);
            unset($pagesDb);

            BMC_SiteMap::Update();
            Header("Location: " . $this->Url);
            die();
        }
        else {
            $parentId = Request('parent');
            if (isset($parentId) && $parentId >= 0) {
                $page['Parent'] = $parentId;
                $page['Name'] = $this->data['txtName'];
                $page['Title'] = $this->data['txtTitle'];
                $page['Keywords'] = $this->data['txtKeywords'];
                $page['Description'] = $this->data['txtDescription'];
                $page['Alias'] = $this->data['txtAlias'];
                $page['Visible'] = empty($this->data['chkVisible']) ? 0 : 1;
                $page['Horizontal'] = empty($this->data['chkHorizontal']) ? 0 : 1;
                $page['LanguageId'] = GetSiteLangId();
                $page['HideInMain'] = empty($this->data['chkHideInMain']) ? 0 : 1;
                $page['WithoutDesign'] = empty($this->data['chkWithoutDesign']) ? 0 : 1;

                $pagesDb = new DAL_PagesDb();
                $pageId = $page = $pagesDb->AddPage($page);
                unset($pagesDb);

                BMC_SiteMap::Update();
                Header("Location: " . $this->Url . "?id=" . $pageId);
                die();
            }
        }
    }

    function handlerBtnAddModule() {
        if (!isset($this->data['drdModulesList']))
            return;

        $pageId = Request('id');

        if (!isset($pageId) || $pageId <= 0)
            return;

        $modulesDb = new DAL_ModulesDb();
        $modules = $modulesDb->GetModulesList();

        $moduleType = $this->data['drdModulesList'];

        if (!isset($modules[$moduleType]))
            trigger_error("Модуль $moduleType не установлен.");

        if ($modules[$moduleType]['isAdmin'] != "False")
            trigger_error("Невозможно добавлять администраторские модули на страницу.");

        $module = $modules[$moduleType];

        // добавляем модуль к странице
        $modulesDb->AddModule($pageId, $moduleType);

        unset($modulesDb);
        BMC_SiteMap::Update();

        $this->BindPageEdit($pageId);
    }

    function handlerBtnModuleDelete($moduleId) {
        if ($moduleId <= 0)
            return;

        $pageId = Request('id');

        if (!isset($pageId) || $pageId <= 0)
            return;

        $modulesDb = new DAL_ModulesDb();
        $modulesDb->DeleteModule($moduleId);
        unset($modulesDb);

        return $this->BindPageEdit($pageId);
    }

    function handlerModuleBtnUp($moduleId) {
        if ($moduleId <= 0)
            return;

        $pageId = Request('id');

        if (!isset($pageId) || $pageId <= 0)
            return;

        $modulesDb = new DAL_ModulesDb();
        $modulesDb->UpModule($moduleId);
        unset($modulesDb);

        return $this->BindPageEdit($pageId);
    }

    function handlerModuleBtnDown($moduleId) {
        if ($moduleId <= 0)
            return;

        $pageId = Request('id');

        if (!isset($pageId) || $pageId <= 0)
            return;

        $modulesDb = new DAL_ModulesDb();
        $modulesDb->DownModule($moduleId);
        unset($modulesDb);

        return $this->BindPageEdit($pageId);
    }

    #endregion
    #region Module Constructor

    function __construct($moduleId) {
        $this->cssClass = "pagesList";
        parent::__construct($moduleId);
    }

    #endregion
}

?>