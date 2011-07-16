<?php

/**
 * BLL_AppEngine class
 * Класс для генерации html кода страницы
 *
 * @author Frame
 * @version AppEngine.class.php, v 1.0.1
 * @copyright (c) by Ainform
 */

class BLL_AppEngine
{

	/**
	Находимся в админке?

	@var bool
	 */
	public static $isAdmin;
	private $isfront;

	/**
	Дополнительные файлы стилей
	 */
	private static $cssFiles = array();

	/**
	 * Модуль по умолчанию
	 *
	 */
	const DefaultModule = 'Default';

	/**
	 * Тип модуля
	 *
	 * @var string
	 */
	private $moduleType;

	/**
	 * Идентификатор модуля
	 *
	 * @var string
	 */
	private $moduleId;

	/**
	 * текущая страница
	 *
	 * @var siteMapNode
	 */
	private $curPage;
	#endregion
	/**
	Прошла ли форма валидацию
	 */
	private static $isFormValid = true;

	/**
	Прошла ли форма валидацию
	 * @return bool
	 */
	public static function IsFormValid()
	{
		return self::$isFormValid;
	}

	#region "Data binding"
	/**
	Биндит меню
	 */

	private function LoadMenu()
	{
		$smarty = PHP_Smarty::GetInstance();

		if (self::$isAdmin) {
			$pages = BMC_SiteMap::GetPagesForAdminMenu();
			$smarty->assign("AdminMenu", BMC_SiteMap::GetAdminModulesFromDir());
		} else {
			$pages = BMC_SiteMap::GetPagesForMenu();
		}

		/* Ищем последний показываемый элемент */
		$end = "";
		$lastPageId = 1;

		foreach ($pages as $data) {

			/* if ($data->HideInMain && $_SERVER['REQUEST_URI'] == "/")
			  continue;
			  elseif(!$data->Visible && !$data->HideInMain)
			  continue;
			 */
			$end = $data->Title;

			if ($data->Path)
				$data->IsCurrent = strpos(strtolower($_SERVER['REQUEST_URI']), strtolower($data->Path));

			if ($data->Visible != false && $data->Level == 1 && ($data->Horizontal != 1 || ($data->Horizontal == 1 && isset($_SESSION['user'])))) {
				$lastPageId = $data->PageId;
			}
		}

		$smarty->assign("end", $end);
		//print_r($pages);
		$smarty->assign("Menu", $pages);


		//является ли страница страницей системы и решения
		$smarty->assign("IsSystemsAndSolutonsPage", strpos(strtolower($_SERVER['REQUEST_URI']), "sistemy_i_reshenija") !== false);

		// wtf($pages,false);
		$smarty->assign("LastPageId", $lastPageId);
	}

	/**
	 * Аутентификация
	 * хотя это авторизация на самом деле, но в будущем будет и то и то, так что для будущего
	 */
	private function Authentication()
	{
		$smarty = PHP_Smarty::GetInstance();

		if (self::$isAdmin) {

		}
			//$pages = BMC_SiteMap::GetPagesForAdminMenu();
		else {

			if (isset($_GET['logout'])) {
				BMC_Authentication::LogOut();
			}
			if (isset($_POST['password']) && isset($_POST['login']) && (!isset($_SESSION['user']) || trim($_SESSION['user']) == "")) {

				if (isset($_POST['remember']))
					$memory = 1;
				else
					$memory = 1; //FIXME для проекта TMC галки нет "запомнить меня", так что запоминаем в любом случае

				BMC_Authentication::GetUser($_POST['password'], $_POST['login'], $memory);

				$smarty->assign("login_error", "Неверный email или пароль!");
			}
		}
	}

	/**
	Грузит список языков
	 */
	private function LoadLanguages()
	{
		$langDb = new DAL_LanguagesDb();
		$languages = $langDb->GetLanguages();

		$langId = GetSiteLangId();

		$smarty = PHP_Smarty::GetInstance();
		$smarty->assign("Languages", $languages);
		$smarty->assign("CurrentLangId", $langId);

		foreach ($languages as $language) {
			if ($language["LanguageId"] == $langId)
				$smarty->assign("CurrentLangAlias", $language["Alias"]);
		}
	}

	/**
	 * Возвращает true, если элемент должен остаться в меню
	 */
	public function ExludeElements($var)
	{
		return ($var->Visible || $var->Level >= 2) && $var->ModuleId != 70;
	}

	/**
	 * @param $params
	 * @return string
	 */
	function GetFileImageMine($params)
	{
		if (is_array($params))
			$id = $params['id'];
		else
			$id = $params;

		$ext2 = substr($id, strlen($id) - 3);

		if (strpos(" pdf doc docx xls zip wmv txt rar zip ppt htm", $ext2))
			return "/img/file_type/" . $ext2 . ".png";
		else
			return "/img/file_type/other.png";
	}

	function find($params)
	{
		$ext = $params['ext'];
		$filename = $params['file'];

		return strpos($filename, $ext) !== false;
	}

	function truncate_utf8($params)
	{
		if (is_array($params)) {
			$string = $params['string'];
			$len = $params['len'];
			if (isset($params['wordsafe'])) {
				$wordsafe = $params['wordsafe'];
			} else {
				$wordsafe = FALSE;
			}
			;
			if (isset($params['dots'])) {
				$dots = $params['dots'];
			} else {
				$dots = FALSE;
			}
		}
		$slen = strlen($string);
		if ($slen <= $len) {
			return $string;
		}
		if
		($wordsafe
		) {
			$end = $len;
			while (($string[--$len] != ' ') && ($len > 0)) {

			}
			;
			if ($len == 0) {
				$len = $end;
			}
		}
		if
		((ord($string[$len]) < 0x80) || (ord($string[$len]) >= 0xC0)
		) {
			return substr($string, 0, $len) . ($dots ? ' ...' : '');
		}
		while (--$len >= 0 && ord($string[$len]) >= 0x80 && ord($string[$len]) < 0xC0) {

		}
		;
		return substr($string, 0, $len) . ($dots ? ' ...' : '');
	}

	/**
	 * Загружает страницу
	 */
	public function LoadPage()
	{

		// BMC_SiteMap::Update();
		$smarty = PHP_Smarty::GetInstance();

		//определяем на главной ли мы
		if ($_SERVER['REQUEST_URI'] == "/" || $_SERVER['REQUEST_URI'] == "/main/")
			$this->isfront = 1;
		else
			$this->isfront = 0;

		$smarty->assign('isfront', $this->isfront);

		if (!self::$isAdmin) {
			$baseDb = new DAL_BaseDb();

			//первые четыре корневых раздела
			$rows = $baseDb->query("SELECT Name FROM pages WHERE Parent='0' AND Visible='1' ORDER BY `Order` ASC LIMIT 0,4");
			$smarty->assign('MenuItems', $rows);

			$rows = $baseDb->query("SELECT *, (SELECT Alias FROM pages WHERE PageId='166') AS parent_alias FROM pages WHERE Parent='166' AND Visible='1' ORDER BY `Order` ASC");
			$smarty->assign('MenuSubItems1', $rows);

			$rows = $baseDb->query("SELECT *, (SELECT Alias FROM pages WHERE PageId='244') AS parent_alias FROM pages WHERE Parent='244' AND Visible='1' ORDER BY `Order` ASC");
			$smarty->assign('MenuSubItems2', $rows);

			$rows = $baseDb->query('SELECT *, (SELECT Alias FROM pages WHERE PageId=\'176\') AS parent_alias FROM pages WHERE Parent=\'176\' AND Visible=\'1\' ORDER BY `Order` ASC');
			$smarty->assign('MenuSubItems3', $rows);

			$rows = $baseDb->query("SELECT *, (SELECT Alias FROM pages WHERE PageId='181') AS parent_alias FROM pages WHERE Parent='181' AND Visible='1' ORDER BY `Order` ASC");
			$smarty->assign('MenuSubItems4', $rows);

			//Debug($this->curPage->Parent->Title);
			/* foreach($this->curPage->Parent as $key => $value) {
			  print $key."<br>" ;
			  } */
			$rows = array();

			if (isset($this->curPage->Parent)) {
				$smarty->assign('ParentTitle', $this->curPage->Parent->Title);
				//Debug($this->curPage);
				foreach ($this->curPage->Parent->Childs as $row) {
					if ($row->Visible) {
						$temprow['Url'] = "";
						$temprow['Selected'] = false;
						if (strstr($_SERVER['REQUEST_URI'], $row->Alias)) {
							$temprow['Selected'] = true;
						}
						$pagesDb = new DAL_PagesDb();
						$temprow['Name'] = $row->Title;
						$temprow['depth'] = 1;
						$temprow['SectionId'] = $row->PageId;
						$temprow['ParentId'] = $row->PageId;
						$pagesDb->GetPageFullPath($row->PageId, $temprow['Url']);
						$rows[] = $temprow;
					}
				}
			}
			$smarty->assign('MenuLeftItems1', $rows);

			if ($this->curPage->Alias == "oplata_i_dostavka") {
				$page_full_url = "internet-magazin/";
				$secdb = new DAL_SectionsDb();
				$rows1 = $secdb->GetTree();

				foreach ($rows1 as &$row) {
					$section_full_url = "";
					if (strstr($_SERVER['REQUEST_URI'], $row['SectionId'])) {
						$row['Selected'] = true;
					}
					$row['Alias'] = $row['SectionId'];
					foreach ($rows1 as $subrow) {
						if ($subrow['ParentId'] == $row['SectionId']) {
							$row['hasChild'] = true;
						}
					}
					$row['Name'] = $row['Title'];
					$secdb->GetSectionFullPath($row['SectionId'], $section_full_url);
					$row['Url'] = $page_full_url . $section_full_url;
				}
				//Debug($rows1);
				$smarty->assign('MenuLeftItems1', $rows1);
				$smarty->assign('shop', true);
				$smarty->assign('oplata_i_dostavka', true);
			}
		}

		$smarty->assign("Data", array());


		if (AppDebug) {
			$smarty->force_commpile = true;
		}
		//$smarty->caching=true;
		//$smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
		$smarty->setCompileCheck(true);
		$smarty->php_handling = Smarty::PHP_ALLOW;
		$smarty->allow_php_tag = true;

		$query = preg_replace("![^a-z0-9а-я\s]!isu", "", Request("q", null));
		$smarty->assign("SearchQuery", $query);
		$smarty->registerPlugin("function", "fileimagemine", array($this, "GetFileImageMine"));
		$smarty->registerPlugin("function", "find", array($this, "find"));
		$smarty->registerPlugin("function", "truncate_utf8", array($this, "truncate_utf8"));

		$this->moduleId = Request('module', self::DefaultModule);

		$smarty->assign("login_error", "");

		$this->Authentication();

		if (isset($_SESSION['user']) && isset($_SESSION['userId'])) {
			$smarty->assign("username", $_SESSION['user']);
			$smarty->assign("userId", $_SESSION['userId']);
		}

		/* $mdb = new DAL_ManufacturersDb();
		  $smarty->assign('Manufacturers', $mdb->GetAll());
		  unset($mdb);
		 */
		$sdb = new DAL_SectionsDb();
		$smarty->assign('RootCatalogs', $sdb->GetRootSections());
		unset($sdb);

		$smarty->assign('Address', Helpers_PathHelper::GetFullUrl("root"));

		$smarty->assign('PageAddress', Helpers_PathHelper::GetFullUrl('root', false) . $this->curPage->Url);
		$PageAddress = Helpers_PathHelper::GetFullUrl('root', false) . $this->curPage->Url;
		$smarty->assign('SitePath', $this->curPage->Url);


		$this->LoadMenu();
		//wtf(BLL_BreadCrumbs::getInstance()->Bind(),false);
		// получаем данные страницы
		$smarty->assign('BreadCrumbs', BLL_BreadCrumbs::getInstance()->Bind());
		$smarty->assign('MetaTags', "");


		$title = html_entity_decode($this->curPage->Title, ENT_QUOTES, 'UTF-8');

		$smarty->assign('Title', $title);

		// загружаем список модулей
		$modules = $this->GetModules();

		if (!empty($modules[0]->header))
			$smarty->assign('Header', str_replace("&amp;", "&", $modules[0]->header));
		else
			$smarty->assign('Header', str_replace("&amp;", "&", $this->curPage->Title));

		//Количество товаров в корзине
		$cart_count = 0;
		if (isset($_SESSION["GoodsList"])) {
			foreach ($_SESSION["GoodsList"] as $key => $value) {
				if ($value["Count"] > 0) {
					$cart_count += $value["Count"];
				}
			}
		}
		$smarty->assign("Cart_count", $cart_count . " " . declension($cart_count, array("товар", "товара", "товаров")));

		$smarty->assign('Keywords', $this->curPage->Keywords);
		$smarty->assign('Description', $this->curPage->Description);

		$smarty->assign('Modules', $modules);
		$smarty->assign('CSSFiles', self::$cssFiles);
		$smarty->assign('startDate', time());

		$smarty->assign('ShowTitle', strpos($_SERVER["REQUEST_URI"], "contacts") === false);
		$smarty->assign('IsToShowRightColumn', strpos($_SERVER["REQUEST_URI"], "contacts") === false);
		$smarty->assign('IsToShowRightColumn', strpos($_SERVER["REQUEST_URI"], "cart") === false);

		//$this->LoadLanguages();
	}

	#endregion

	public function DelAmp($str)
	{
		return str_replace("&amp;", "&", $str);
	}

	/**
	 * Проверяет на необходимость выполнения обработчика
	 * и выполняет его, если совершено событие
	 *
	 * @link BMC_BaseModule $module загруженный модуль
	 * @param $module
	 * @return
	 */

	private function EventHandlerModule(&$module)
	{

		$module->isPostBack = false;
		$smarty = PHP_Smarty::GetInstance();
		//var_dump($module);
		if (empty($_POST))
			return;


		// AJAX обработка
		$ajax = False;

		if (isset($_POST['ajaxHandler']) && isset($_POST['ajaxId'])) {
			//$ajax_dir = '/usr'.$smarty->template_dir."ajax/";
			$ajax_dir = $smarty->template_dir . "ajax/";
			$ajax_id = substr($_POST['ajaxId'], 4);
			$ajax_file = $ajax_dir . $ajax_id . '.ajax';

			if (file_exists($ajax_file))
				$ajax = True;
		}
		// \\ AJAX

		if (!isset($_POST['moduleId']) || $module->moduleId != $_POST['moduleId'])
			return;

		$module->isPostBack = true;
		$module->data = array_merge($module->data, $_POST);

		$validate = true;

		// Валидация
		foreach ($_POST as $key => $value) {
			if (strstr($key, 'validator') && strrchr($key, ":") !== false) {
				$param = substr($key, strpos($key, ":") + 1, strlen($key));
				$key = substr($key, 0, strpos($key, ":"));
				$validate &= PHP_Validator::Check($module->data, $param, $value);
			}
		}

		self::$isFormValid = $validate;

		// если не прошли валидацию, то event'ы не обрабатываем
		//		if (!$validate)
		//			return;
		// Обработка Event'ов
		foreach ($_POST as $key => $value) {
			if (strstr($key, 'handler')) {
				$param = null;

				if (strrchr($key, "_x") !== false)
					$key = substr($key, 0, strpos($key, "_"));

				if (strrchr($key, ":") !== false) {
					$param = substr($key, strpos($key, ":") + 1, strlen($key));
					$key = substr($key, 0, strpos($key, ":"));
				}

				if (method_exists($module, $key)) {
					if ($ajax) {
						$module->data['Module'] = $module;
						$module->$key($param);
						$smarty->assign("Data", $module->getData());
						$smarty->display($ajax_file);
						die(0);
					}

					$module->$key($param);
					break;
				}
			}
		}
		if ($ajax) {
			$module->data['Module'] = $module;
			$module->DataBind();
			$smarty->assign("Data", $module->getData());
			$smarty->display($ajax_file);
		}
	}

	/**
	 * Возвращает список экземпляров модулей, размещенных на странице
	 *
	 * @return array
	 */
	private function GetModules()
	{

		$modulesDb = new DAL_ModulesDb();

		$modulesObjects = array();

		if (!isset($this->curPage))
			return array();

		// получаем список модулей
		// $modules = $modulesDb->GetModules($this->curPage->PageId);
		// Если указан идентификатор модуля в узле сайтмапа, то грузим его на редактирование
		if ($this->curPage->ModuleEdit != null) {
			$allmodules = $modulesDb->GetModulesObjects($this->curPage->PageId);
			$module = $modulesDb->GetModuleEditInstance($this->curPage->ModuleEdit);
			if ($module)
				$modulesObjects = array($module);
		} else {
			// если текущая страница является внутренней для модуля, то грузим только модуль
			// var_dump($this->curPage);
			if ($this->curPage->ModuleId != null) {
				$module = $modulesDb->GetModuleInstance($this->curPage->ModuleId);
				$modulesObjects = array($module);
			} else {
				$modulesObjects = $modulesDb->GetModulesObjects($this->curPage->PageId);
			}
		}
		unset($modulesDb);

		// список экземпляров
		foreach ($modulesObjects as $moduleObject) {

			$cssPath = $_SERVER['DOCUMENT_ROOT'] . "/module/" . $moduleObject->moduleType . "/module.css"; /* informix patch 06.05.2009 */
			if (file_exists($cssPath))
				self::$cssFiles[] = Helpers_PathHelper::GetFullUrl("module") . $moduleObject->moduleType . "/module.css"; /* informix patch 06.05.2009 */

			// проверяем необходимость вызыва обработчика для модуля
			$this->EventHandlerModule($moduleObject);

			// биндим данные модуля

			$moduleObject->DataBind();
		}

		return $modulesObjects;
	}

	#endregion
	#region "Render Function"
	/**
	 * Возвращает html представление страницы
	 *
	 * @return string
	 */

	public function Render()
	{
		$smarty = PHP_Smarty::GetInstance();

		if ($this->curPage->WithoutDesign == 1) {
			$smarty->display("index_blank.tpl");
		} elseif ($this->isfront == 0 && !self::$isAdmin) {
			$smarty->display("index-inside.tpl");
		} else {
			$smarty->display("index.tpl");
		}
	}

	#endregion
	#region "Constructor"
	/**
	 * Конструктор, инициализирует класс
	 *
	 * @param string $isAdmin Тип приложения админка или нет
	 */

	function __construct($isAdmin = false)
	{
		session_start();
		self::$isAdmin = $isAdmin;

		$smarty = PHP_Smarty::GetInstance();
		if (!self::$isAdmin)
			PHP_Smarty::SetTheme("Default");
		else
			PHP_Smarty::SetTheme("Admin");

		// Определяем текущую страницу
		$this->curPage = BMC_SiteMap::GetCurPage();

		if (!isset($this->curPage)) {
			//обновляем сайтмап и пробуем ещё раз
			BMC_SiteMap::Update();
			if (isset($this->curPage)) {
				if ($this->curPage->Path != $_SERVER["REQUEST_URI"]) {
					foreach ($this->curPage->Childs as $child)
						if ($child->Path == $_SERVER["REQUEST_URI"]) {
							$this->curPage = $child;
							break;
						}
				}
			} else {
				$this->curPage = BMC_SiteMap::GetErrorPage();
			}
		} else {
			if ($this->curPage->Path != $_SERVER["REQUEST_URI"]) {
				foreach ($this->curPage->Childs as $child)
					if ($child->Path == $_SERVER["REQUEST_URI"]) {
						$this->curPage = $child;
						break;
					}
			}
		}
	}

}

?>
