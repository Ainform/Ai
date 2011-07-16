<?
class MapModule extends BMC_BaseModule
{

	#region Module Constructor
	function __construct($moduleId)
	{
		$this->cssClass = "pagesList";
		parent::__construct($moduleId);		
	}
	#endregion
	
	private $menuType = 3; // тип меню. 1 - горизонтальное, 2 - вертикальное, 3 - смешанное

	#region Data Binding
	/**
		Биндим данные
	*/
	public function DataBind()
	{
		$this->data['MenuType'] = $this->menuType;
		
		return $this->BindSiteTree();
	}
	
	/**
		Биндим данные для редактирования страницы
		@param int $pageId идентификатор страницы
	*/
	function BindPageEdit($pageId)
	{	
		if (!$this->isPostBack)
		{
			$pagesDb = new DAL_PagesDb();
			$page = $pagesDb->GetPage($pageId);
			unset($pagesDb);
			
			$this->data['txtName'] = $page['Name'];
			$this->data['txtTitle'] = $page['Title'];
			$this->data['txtMetaTitle'] = $page['MetaTitle'];
			$this->data['txtKeywords'] = $page['Keywords'];
			$this->data['txtDescription'] = $page['Description'];
			$this->data['txtAlias'] = empty($page['Alias']) ? "index" : $page['Alias'];
			//$this->data['ddrTheme'] = $page['ThemeId'];
			$this->data['ddrParent'] = $page['Parent'];
			
			if ($page['InHorizontal'] == 1)
				$this->data['chkInHorizontal'] = "True";
				
			if ($page['InVertical'] == 1)
				$this->data['chkInVertical'] = "True";
		}
		
		// биндим список родительские разделов
//		$this->BindParents($pageId);

		$this->BindModulesList();
		
		$modulesDb = new DAL_ModulesDb();
		$modules = $modulesDb->GetModules($pageId);
		$list = $modulesDb->GetModulesList();
		unset($modulesDb);
		
		foreach ($modules as &$module)
		{
			$moduleData = $list[$module['ModuleType']];
			$module['Title'] = (string)$moduleData['title'];
			$module['Editable'] = strtolower($moduleData['editable']) == 'true' ? true : false;
		}

		$this->data['PageModules'] = $modules;
		$this->data['NewPage'] = false;
		
		// биндим список тем
		$this->BindThemesList();
		$this->BindParents($pageId);
		$this->BindAliases($pageId);
		
		$this->template = "pageedit.tpl";
	}
	
	/**
		Биндит данные по родительским разделам
	*/
	function BindParents($pageId = null)
	{
		$parents = array(0 => "Корень сайта");
		
		$pagesDb = new DAL_PagesDb();
		$pages = $pagesDb->GetAllPages();
		unset($pagesDb);
		
		foreach ($pages as $page)
		{
			if ($pageId != null && $pageId == $page['PageId'])
				continue;
				
			$parents[$page['PageId']] = $page['Name'];
		}
		
		$this->data['Parents'] = $parents;		
	}
	
	/**
		Биндит список страниц, от которых мы можем наследовать модули
	*/
	function BindAliases($pageId = null)
	{
		$aliases = array(0 => "Без наследования");
		
		$pagesDb = new DAL_PagesDb();
		$pages = $pagesDb->GetAllPages();
		unset($pagesDb);
		
		foreach ($pages as $page)
		{
			if ($pageId != null && $pageId == $page['PageId'])
				continue;
				
			$aliases[$page['PageId']] = $page['Name'];
		}
		
		$this->data['Aliases'] = $aliases;		
	}
	
	/**
		Биндит список тем
	*/
	public function BindThemesList()
	{
		$themesDb = new DAL_ThemesDb();
		$themes = $themesDb->GetThemes();

		$this->data['Themes'] = array();
		
		foreach ($themes as $theme)
			$this->data['Themes'][$theme['ThemeId']] = $theme['ThemeName'];
	}

	/**
		Биндим данные для создания новой страницы
		@param int $parentId идентификатор родительской страницы
	*/
	function BindNewPage($parentId)
	{	
		if (!$this->isPostBack)
		{
/*			$pagesDb = new DAL_PagesDb();
			$page = $pagesDb->GetPage($pageId);
			unset($pagesDb);*/

			$this->data['txtName'] = "";
			$this->data['txtTitle'] = "";
			$this->data['txtMetaTitle'] = "";
			$this->data['txtKeywords'] = "";
			$this->data['txtDescription'] = "";
			$this->data['txtAlias'] = "";
			$this->data['chkInHorizontal'] = $this->menuType == 1 ? "True" : "False";
			$this->data['chkInVertical'] = $this->menuType > 1 ? "True" : "False";
			//$this->data['ddrTheme'] = 1; // основная тема
			$this->data['ddrParent'] = $parentId; // основная тема
			$this->data['ddrInheritance'] = 0;
		}
		
		// биндим список родительские разделов
//		$this->BindParents(null, $parentId);

		$this->BindModulesList();
		$this->data['NewPage'] = true;
		$this->template = "pageedit.tpl";	

		// биндим список тем
		$this->BindThemesList();
		$this->BindParents();
		$this->BindAliases();
	}
	
	/**
		Биндим список модулей
	*/
	function BindModulesList()
	{
		$modulesDb = new DAL_ModulesDb();
		$modules = $modulesDb->GetModulesList();
		unset($modulesDb);
		
		$arr = array();
		foreach ($modules as $module)
		{
			if ($module['isAdmin'] != "True" && strtolower($module['visible']) == "true")
				$arr[(string)$module['id']] = (string)$module['title'];
		}
		
		$this->data['Modules'] = $arr;
		return $arr;
	}
	#endregion
	
	#region TreeView Data Generator
	/**
		Биндим данные для TreeView
	*/
	function BindSiteTree()
	{
		$pages = BMC_SiteMap::GetSitePages();
		$tree = array
			(
				array("txt" => '<a class="text" href="'.SiteUrl.'">'.AppName.'</a>',
						"onclick" => "nodeSelect",
						"id" => "0")
			);

		foreach($pages as $page)
		{

            //Debug($page->Title,false);
			$title = html_entity_decode(html_entity_decode($page->Title));
			
			if ($page->IsInnerModule)
				continue;

			$title = '<a class="text" href="'.SiteUrl.substr($page->Path, 1).'/">'.$title."</a>";				

			if (!$page->visible || ($page->Horizontal && !isset($_SESSION['userId'])))
				$title = '<i>'.$title.' (скрыто)</i>';
			else				
			$tree[0]["items"][] = array(
				"id" => $page->PageId,
				"txt" => $title,
				"items" => $this->BindChild($page),
				"onclick" => "nodeSelect",
				"ondblclick" => "nodeClick"
			);
		}

		$this->data['Tree'] = $tree;
		$this->data['NewPage'] = true;
	}
	
	function BindChild($page)
	{
		$tree = array();

			foreach ($page->Childs as $child)
			{
				if ($child->Level > 2)
					continue;
				
				$title =  html_entity_decode(html_entity_decode($child->Title));
				
				$title = '<a class="text" href="'.SiteUrl.substr($child->Path, 1).'/">'.$title."</a>";

				if (isset($child->PageId))
					$tree[] = array("id" => $child->PageId,
									"txt" => $title,
									"items" => $this->BindChild($child),
									"onclick" => "nodeSelect",
									"ondblclick" => "nodeClick");
			}
		return $tree;
	}
	#endregion

}
?>