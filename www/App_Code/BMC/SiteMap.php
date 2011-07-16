<?

/**
 * BMC_SiteMap class
 * Класс для работы с сайтмапом
 *
 * @author MeF Dei Corvi
 * @version SiteMap.php, v 0.0.5
 * @copyright (c) by Ainform
 */
class BMC_SiteMap {

	public $sub = array();

	/**
	  @var siteMapNode Корневой узел карты сайта
	 */
	private static $siteMap;

	/**
	  @var siteMapNode Текущий узел
	 */
	private static $curNode;

	/**
	  @var array массив закешированных сайтмапов
	 */
	private static $cached;

	/**
	  @var array ассоциативный массив страниц по id
	 */
	private static $pages = array();

	/**
	 * Возвращает все корневые страницы
	 *
	 * @param siteMapNode $rootPage
	 * @return array
	 */
	public static function GetRootPages($siteMap = null) {
		if (null == $siteMap)
			$siteMap = self::$siteMap;

		$rootPage = $siteMap;

		$pages = Array();

		if ($rootPage->HasChild()) {
			foreach ($rootPage->Childs as $page) {
				$pages[] = $page;
			}
		}

		return $pages;
	}

	/**
	 * Возвращает все дочерние страницы узла
	 *
	 * @param siteMapNode $rootPage
	 * @return array
	 */
	public static function GetPages($rootPage = null) {
		if (!isset(self::$siteMap))
			self::$siteMap = self::LoadSiteMap();

		if (!isset($rootPage))
			$rootPage = self::$siteMap;

		$pages = Array();


		if ($rootPage->HasChild()) {
			foreach ($rootPage->Childs as $page) {
				$pages[] = $page;
				$pages = array_merge($pages, self::GetPages($page));
			}
		}

		return $pages;
	}

	/**
	  Возвращает список страниц для администраторского меню
	 */
	public static function GetPagesForAdminMenu() {
		$menu = self::GetPagesForMenu();

		/* 		$siteMap = self::LoadSiteMap("SiteMap.xml");
		  $siteMenu = self::GetSiteMenuForAdmin($siteMap);

		  $separator = new BMC_SiteMapNode();
		  $separator->SetSeparator();
		  $siteMenu[] = $separator;

		  $menu = array_merge($siteMenu, $menu); */

		return $menu;
	}

	/**
	  Возвращает измененнное для админки меню сайта
	 */
	/* 	public static function GetSiteMenuForAdmin($rootItem)
	  {
	  $pages = array();
	  $modulesDb = new DAL_ModulesDb();

	  foreach ($rootItem->Childs as $item)
	  {
	  $pages[] = $item;

	  $modules = $modulesDb->GetModules($item->PageId);

	  foreach ($modules as $module)
	  {
	  $moduleItem = new BMC_SiteMapNode();
	  $moduleItem->Title = $;
	  $moduleItem->Url = $module['ModuleId'];
	  $moduleItem->Level = $item->Level + 1;
	  $pages[] = $moduleItem;
	  }

	  $item->Url = "/admin/modules";
	  $pages = array_merge($pages, self::GetSiteMenuForAdmin($item));
	  }

	  return $pages;
	  } */

	/**
	 * Возвращает список страниц, которые будут выведены
	 *
	 * @param siteMapNode Корневой узел
	 * @return array
	 */
	public static function GetPagesForMenu($rootPage = null) {
		if (!isset(self::$siteMap))
			self::$siteMap = self::LoadSiteMap();

		if (!isset($rootPage)) {
			$first = 1; //если это корень
			$rootPage = self::$siteMap;
		} else {
			$first = 0;
		}

		$pages = Array();
//wtf($rootPage);

		if (($rootPage->Selected || $rootPage->HasSelectedChild || $rootPage->Parent == null)) {

			/* устанавлитваем  хлебные крошки */
			// Debug(BMC_SiteMap::GetCurPage()->Path."------------".$rootPage->Path,false);
			if (BLL_AppEngine::$isAdmin && $rootPage->Parent != null && $rootPage->Path != BMC_SiteMap::GetCurPage()->Path) {

				$b = array($rootPage->Title => $rootPage->Path);
				$breadCrumbs = BLL_BreadCrumbs::getInstance();
				$breadCrumbs->Add($b);
			} elseif (!empty($rootPage->Path) and strstr(BMC_SiteMap::GetCurPage()->Path, $rootPage->Path) and BMC_SiteMap::GetCurPage()->Level > 1) {
				//TODO генерить крошки надо из модуля каталога
				//if(isset($_REQUEST['manufacturerId'])){
				//$b = array($rootPage->Title=>$rootPage->Path."/?manufacturerId=".$_REQUEST['manufacturerId']);}
				//else{
				if (BMC_SiteMap::GetCurPage()->Path == $rootPage->Path) {
					$b = array($rootPage->Title => "#");
				} else {
					$b = array($rootPage->Title => $rootPage->Path . "/");
				}
				// }
				$breadCrumbs = BLL_BreadCrumbs::getInstance();
				$breadCrumbs->Add($b);
			}



			foreach ($rootPage->Childs as $page) {
				/* 				if ($page->Visible == false)
				  continue; */
				if ($rootPage->Horizontal == 1 && $first == 0) {
					// $page->Horizontal=1;
				}

				$pages[] = $page;
				//Debug($page);
				$pages = array_merge($pages, self::GetPagesForMenu($page));
			}
		}


		return $pages;
	}

	/**
	  Возвращает карту сайта(вне зависимости от текущего загруженного сайтмапа)
	 */

	/**
	 *
	 * @param type $allPages
	 * @return type
	 */
	public static function GetSitePages($allPages = false) {
		$siteMap = self::LoadSiteMap("SiteMap.xml");
		if ($allPages)
			return self::GetPages($siteMap);
		else
			return self::GetRootPages($siteMap);
	}

	/**
	 * Загрузка сайтмапа
	 *
	 * @param $file имя файла для сайтмапа
	 * @return siteMap
	 */
	public static function LoadSiteMap($file = null) {
		self::$curNode = null;
		$cfgPath = Helpers_PathHelper::GetFullPath("config", true);

		if (null == $file)
			if (BLL_AppEngine::$isAdmin)
				$file = "AdminSiteMap.xml";
			else
				$file = "SiteMap.xml";

		$cacheKey = $file . "_" . GetSiteLangId();

		if (isset(self::$cached[$cacheKey]))
			return self::$cached[$cacheKey];

		$file = $cfgPath . $file;

		$sxe = simplexml_load_file($file);
		//wtf($sxe);
		$siteMap = new BMC_SiteMapNode();
		$siteMap->Title = "Главная страница";
		foreach ($sxe->siteMapNode as $xmlNode)
			self::LoadSiteMapNode($xmlNode, $siteMap);

		// проверяем на совпадение с query string
		//Debug($siteMap);
		/* if(App_Info::RequestedPage(false) == $siteMap->Url && !isset(self::$curNode))
		  {
		  $siteMap->Selected = true;
		  self::$curNode = $siteMap;
		  } // иначе без неё
		  else if(App_Info::RequestedPage() == $sxe->Url)
		  {
		  $siteMap->Selected = true;
		  self::$curNode = $sxe;
		  }
		 */
		self::$cached[$cacheKey] = $siteMap;
		//Debug($siteMap);
		return $siteMap;
	}

	/**
	 * Загрузка дочерних узлов элемента в сайтмап
	 * @param XmlNode $xmlNode
	 * @param siteMapNode $siteMapNode родитель
	 *
	 */
	public static function LoadSiteMapNode($xmlNode, $siteMapNode) {
		// if(isset(self::$curNode->Title))
		//Debug(self::$curNode->Title,false);

		$siteMap = new BMC_SiteMapNode($xmlNode);

		// Данные о странице
		$siteMap->Title = (string) $xmlNode['title'];
		$siteMap->Description = (string) $xmlNode['description'];
		$siteMap->Alias = (string) $xmlNode['alias'];
		$siteMap->TextAlias = (string) $xmlNode['textalias'];
		$siteMap->HideInMain = (string) $xmlNode['HideInMain'];
		$siteMap->HideInMenu = (string) $xmlNode['HideInMenu'];
		$siteMap->WithoutDesign = (string) $xmlNode['WithoutDesign'];

		if (isset($xmlNode['thisIsModule'])) {
			$siteMap->thisIsModule = (string) $xmlNode['thisIsModule'];
		}
		if (!isset($xmlNode['pageId'])) {
			//Debug($siteMapNode->PageId,false);
			// если не указан идентификатор страницы, то считаем данный узел, как внутренней страницей с указанием дополнительных параметров
			$siteMap->PageId = $siteMapNode->PageId;

			$siteMap->IsInnerModule = true;
		}
		else
			$siteMap->PageId = (int) $xmlNode['pageId'];

		self::$pages[$siteMap->PageId] = $siteMap;

		$siteMap->Visible = $xmlNode['visible'] == "True" || $xmlNode['visible'] == "true";

		$siteMap->Horizontal = $xmlNode['horizontal'] == "True" || $xmlNode['horizontal'] == "true";

		//wtf($siteMapNode);
		if(!empty($siteMap->TextAlias)) {
			$siteMap->Path = $siteMapNode->Path . "/" . $siteMap->TextAlias;
		} elseif(!empty($siteMap->Alias)) {
			$siteMap->Path = $siteMapNode->Path . "/" . $siteMap->Alias;
		} else {
			$siteMap->Path = $siteMap->Url;
		}

		//Debug($siteMapNode,false);
		$siteMap->Parent = $siteMapNode;
		$siteMap->Level = $siteMapNode->Level + 1;

		// проверяем на совпадение с query string
		//wtf($siteMap->Url."----".App_Info::RequestedPage(false),false);
		//wtf($siteMap->Url."----".App_Info::RequestedPage()."/",false);
		//в админке модуль по умолчанию Index
		if (BLL_AppEngine::$isAdmin && App_Info::RequestedPage(false) == '/admin/' && $siteMap->Url == "/admin/Index/") {
			$siteMap->Selected = true;
			self::$curNode = $siteMap;
		}
		if ((App_Info::RequestedPage(false) == $siteMap->Url || App_Info::RequestedPage() . "/" == $siteMap->Url) && !isset(self::$curNode)) {
			$siteMap->Selected = true;
			self::$curNode = $siteMap;
		} // иначе без неё
		elseif (App_Info::RequestedPage() == $siteMap->Url) {
			$siteMap->Selected = true;
			self::$curNode = $siteMap;
		} elseif (App_Info::RequestedPage() == "/" && $siteMap->Url == "/main/") {
			$siteMap->Selected = true;
			self::$curNode = $siteMap;
		} elseif (strpos(App_Info::RequestedPage(), "good") &&
				(stripos(App_Info::RequestedPage(), (string) $xmlNode["alias"])||stripos(App_Info::RequestedPage(), (string) $xmlNode["textalias"])) &&
				!strpos(App_Info::RequestedPage(), "good" . (string) $xmlNode["alias"]) &&
				(string) $xmlNode["goodId"] == null) {
			$goodId = substr(App_Info::RequestedPage(), strpos(App_Info::RequestedPage(), "good") + 4, -1);

			$db = new DAL_GoodsDb();
			$goodInfo = $db->GetGood($goodId);
			unset($db);

			$sectionsDb = new DAL_SectionsDb();
			$thissection = $sectionsDb->GetSection($goodInfo['SectionId']);

			$good = $xmlNode->addChild('good');
			$good->addAttribute('title', $goodInfo["Title"]);
			$good->addAttribute('alias', "good$goodId");
			$good->addAttribute('goodId', $goodId);
			$good->addAttribute('moduleId', $thissection['ModuleId']);
			$good->addAttribute('sectionId', $goodInfo["SectionId"]);

			self::LoadSiteMapNode($good, $siteMap);
		} elseif ((string) $xmlNode["goodId"] != null) {
			//Debug((string)$xmlNode["goodId"]."----".$siteMap->Url,false);
			//$siteMap->Selected = true;
			//self::$curNode = $siteMap;
		}


		//var_dump(self::$curNode);
		// Добавляем текущий узел к списку детей родителя
		$siteMapNode->Childs[] = $siteMap;

		// Если есть подэлементы, то добавляем их в качестве детей текущего
		if (count($xmlNode->siteMapNode) > 0)
			foreach ($xmlNode->siteMapNode as $child)
				self::LoadSiteMapNode($child, $siteMap);
	}

	/**
	  Преобразует html сущности в ascii

	  @return siteMapNode
	 */
	public static function xmlEntities($str) {
		$xml = array('&#34;', '&#38;', '&#38;', '&#60;', '&#62;', '&#160;', '&#161;', '&#162;', '&#163;', '&#164;', '&#165;', '&#166;', '&#167;', '&#168;', '&#169;', '&#170;', '&#171;', '&#172;', '&#173;', '&#174;', '&#175;', '&#176;', '&#177;', '&#178;', '&#179;', '&#180;', '&#181;', '&#182;', '&#183;', '&#184;', '&#185;', '&#186;', '&#187;', '&#188;', '&#189;', '&#190;', '&#191;', '&#192;', '&#193;', '&#194;', '&#195;', '&#196;', '&#197;', '&#198;', '&#199;', '&#200;', '&#201;', '&#202;', '&#203;', '&#204;', '&#205;', '&#206;', '&#207;', '&#208;', '&#209;', '&#210;', '&#211;', '&#212;', '&#213;', '&#214;', '&#215;', '&#216;', '&#217;', '&#218;', '&#219;', '&#220;', '&#221;', '&#222;', '&#223;', '&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244;', '&#245;', '&#246;', '&#247;', '&#248;', '&#249;', '&#250;', '&#251;', '&#252;', '&#253;', '&#254;', '&#255;');
		$html = array('&quot;', '&amp;', '&amp;', '&lt;', '&gt;', '&nbsp;', '&iexcl;', '&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&shy;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;', '&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;', '&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;');
		$str = str_replace($html, $xml, $str);
		$str = str_ireplace($html, $xml, $str);
		return $str;
	}

	/**
	  Возвращает странцу и данные по ней по идентификатору

	  @return siteMapNode
	 */
	public static function GetPage($pageId) {
		if (isset(self::$pages[$pageId]))
			return self::$pages[$pageId];

		return self::GetErrorPage();
	}

	/**
	 * 	Выгружает текущий сайтмап
	 */
	public static function Clear() {
		self::$siteMap = null;
	}

	/**
	 * Возвращает текущую страницу сайта
	 *
	 * @return siteMapNode узел сайтмапа
	 */
	static function GetCurPage() {
		if (!isset(self::$siteMap)) {
			self::$siteMap = self::LoadSiteMap();
		}
		return self::$curNode;
	}

	/**
	  Возвращает страницу ошибки

	  @return siteMapNode страница ошибки
	 */
	static function GetErrorPage() {
		header("HTTP/1.1 404 Not Found");

		$siteMap = new BMC_SiteMapNode();

		// Данные о странице
		$siteMap->Title = "Ошибка, страница не найдена";
		$siteMap->Description = "Ошибка, страница не найдена";
		$siteMap->Alias = "error";

		$siteMap->PageId = -404;

		$siteMap->Visible = true;
		$siteMap->Path = "";
		$siteMap->Parent = null;
		$siteMap->Level = 0;

		return $siteMap;
	}

	/**
	  Генерирует xml данные по списку страниц

	  @param array $pages;
	 */
	static function GenPagesXML($pages = null, $level=0) {

		$pagesDb = new DAL_PagesDb();
		$modulesDb = new DAL_ModulesDb();

		if ($pages == null)
			$pages = $pagesDb->GetPages();

		$xml = "";
		foreach ($pages as $page) {
			$modules = $modulesDb->GetModules($page['PageId']);

			$visible = $page['Visible'] == 0 ? "False" : "True";
			$horizontal = $page['Horizontal'] == 0 ? "False" : "True";

			$childs = $pagesDb->GetPages($page['PageId']);
			$start = array("\"", "&ldquo;", "&rdquo;");
			$end = array("&quot;", "&#8220;", "&#8221;");

			$xml .= "\n" . '<siteMapNode title="' . self::xmlEntities($page['Name']) . '" alias="' . $page['Alias'] . '" visible="' . $visible . '" horizontal="' . $horizontal . '" pageId="' . $page['PageId'] . '" languageId="' . $page['LanguageId'] . '" HideInMain="' . $page['HideInMain'] . '" WithoutDesign="' . $page['WithoutDesign'] . '" HideInMenu="0">' . "\n";

			foreach ($modules as $module) {
				$moduleObj = $modulesDb->GetModuleInstance($module['ModuleId'], $module['ModuleType']);

				try {
					if ($moduleObj != null)
						$xml .= $moduleObj->GenerateSiteMap();
				} catch (Exception $e) {
					var_dump($module['ModuleId'] . "---" . $module['ModuleType'] . "---" . $e->getMessage());
					die();
				}
			}

			if (count($childs) > 0)
				$xml .= self::GenPagesXML($childs, $level + 2);

			$xml .= "</siteMapNode>";
		}

		unset($pagesDb);
		unset($modulesDb);

		return $xml;
	}

	/**
	  Генерирует xml данные для администраторского сайтмапа по списку страниц

	  @param array $pages;
	 */
	static function GenAdminPagesXML($pages = null) {
		$pagesDb = new DAL_PagesDb();
		$modulesDb = new DAL_ModulesDb();
		$modulesList = $modulesDb->GetModulesList();

		if ($pages == null)
			$pages = $pagesDb->GetPages();

		$xml = "";
		foreach ($pages as $page) {
			$modules = $modulesDb->GetModules($page['PageId']);

			// страницы без модулей пропускаем
			//if (!isset($modules) || count($modules) == 0)
			//  continue;
			// получаем дочерние страницы
			$childs = $pagesDb->GetPages($page['PageId']);
			$start = array("\"", "&ldquo;", "&rdquo;");
			$end = array("&quot;", "&#8220;", "&#8221;");
			//wtf($childs, false);

			if (count($childs) == 0 && count($modules) == 1) {
				//если есть админская часть модуля
				if ($modulesDb->GetModuleEditInstance($modules[0]['ModuleId']))
					$xml .= "\n  " . '<siteMapNode title="' . self::xmlEntities($page['Name']) . '" visible="True" url="/admin/modules/' . $modules[0]['ModuleId'] . '.php" moduleEdit="' . $modules[0]['ModuleId'] . '" languageId="' . $page['LanguageId'] . '" />';
			} else {/*
			  $xml .= "\n\t" . '<siteMapNode title="' . self::xmlEntities($page['Name']) . '" visible="True" url="/admin/pageslist.php?id=' . $page['PageId'] . '" moduleEdit="' . $modules[0]['ModuleId'] . '" languageId="' . $page['LanguageId'] . '">';
			 */
				if (count($modules) == 0) {
					$xml .= "\n  " . '<siteMapNode title="' . self::xmlEntities($page['Name']) . '" visible="True" url="" languageId="' . $page['LanguageId'] . '" >';
				} else {
					$xml .= "\n  " . '<siteMapNode title="' . self::xmlEntities($page['Name']) . '" visible="True" url="/admin/modules/' . $modules[0]['ModuleId'] . '.php" moduleEdit="' . $modules[0]['ModuleId'] . '" languageId="' . $page['LanguageId'] . '" >';
				}
				if (count($childs) > 0)
					$xml .= self::GenAdminPagesXML($childs);

				foreach ($modules as $module) {
					if ($modulesDb->GetModuleEditInstance($module['ModuleId']))
						$xml .= "\n    " . '<siteMapNode title="' . self::xmlEntities($modulesList[$module['ModuleType']]['title']) . '" visible="True" url="/admin/modules/' . $module['ModuleId'] . '.php" moduleEdit="' . $module['ModuleId'] . '" thisIsModule="1" />';
				}

				$xml .= "\n  </siteMapNode>";
			}
		}
		unset($pagesDb);

		return $xml;
	}

	/**
	  Генерирует SiteMap.xml для разделов сайта
	 */
	static function Update() {
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= "\n<sitemap>";

		$xml .= self::GenPagesXML();

		$xml .= "\n</sitemap>";

		file_put_contents(Helpers_PathHelper::GetFullPath("config", true) . "SiteMap.xml", $xml);

		// вызываем обновление административного сайтмапа
		self::AdminUpdate();
	}

	/**
	 *
	 */
	static function GetAdminModulesFromDir() {
		$path = $_SERVER['DOCUMENT_ROOT'] . "/admin/module";
		$d = dir($path);
		$i = 0;
		$modules = array();
		while (false !== ($entry = $d->read())) {
			if ($entry != '.' && $entry != '..' && is_dir($path . "/" . $entry)) {
				$i++;
				$icon = is_file($path . "/" . $entry . "/" . $entry . ".png") ? "/admin/module/" . $entry . "/" . $entry . ".png" : "/upload/noicon.png";
				if (is_file($path . "/" . $entry . "/info.ini")) {
					$ini = parse_ini_file($path . "/" . $entry . "/info.ini");
					$title = $ini['name'];
				} else {
					$title = $entry;
				}
				$modules[] = array('title' => $title, 'alias' => $entry, 'pageid' => -$i, 'url' => '/admin/' . $entry, 'icon' => $icon);
			}
		}
		$d->close();
		return($modules);
	}

	/**
	 *
	 */
	static function GetModulesFromDir() {
		$path = $_SERVER['DOCUMENT_ROOT'] . "/module";
		$d = dir($path);
		$i = 0;
		$modules = array();
		while (false !== ($entry = $d->read())) {
			if ($entry != '.' && $entry != '..' && is_dir($_SERVER['DOCUMENT_ROOT'] . "/module/" . $entry)) {
				$i++;
				if (is_file($path . "/" . $entry . "/info.ini")) {
					$ini = parse_ini_file($path . "/" . $entry . "/info.ini");
					$title = $ini['name'];
				} else {
					$title = $entry;
				}
				$modules[] = array('title' => $title, 'alias' => $entry, 'pageid' => -$i);
			}
		}
		$d->close();
		return($modules);
	}

	/**
	  Генерирует AdminSiteMap.xml для разделов панели администрирования
	 */
	static function AdminUpdate() {
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= "\n<sitemap>";

		$xml .= self::GenAdminPagesXML();

		$adminmodules = self::GetAdminModulesFromDir();

		$modulesDb = new DAL_ModulesDb;
		$adminmodulesfrombase = $modulesDb->GetAdminModules();

		$xml .="\n";

		//удаляем модули из базы, которых нет в папке
		foreach ($adminmodulesfrombase as $modulefrombase) {
			$exist = false;
			foreach ($adminmodules as $module) {
				if ($modulefrombase['ModuleType'] == $module['alias'] && $modulefrombase['PageId'] == $module['pageid']) {
					$exist = true;
				}
			}
			if (!$exist) {
				$modulesDb->DeleteAdminModule($modulefrombase['PageId']);
			}
		}

		foreach ($adminmodules as $module) {
			$xml .= "\n  <siteMapNode title='" . $module['title'] . "' alias='" . $module['alias'] . "' pageId='" . $module['pageid'] . "' visible='False' />";
			$exist = false;
			foreach ($adminmodulesfrombase as $modulefrombase) {
				if ($modulefrombase['ModuleType'] == $module['alias']) {
					$exist = true;
				}
			}
			if (!$exist) {
				$modulesDb->AddModule($module['pageid'], $module['alias']);
			}
		}

		unset($modulesDb);

		$xml .= "\n</sitemap>";

		file_put_contents(Helpers_PathHelper::GetFullPath("config", true) . "AdminSiteMap.xml", $xml);
	}

}

?>
