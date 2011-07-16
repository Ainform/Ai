<?

/**
 * BMC_SiteMapNode class
 * Класс для работы с узлами сайтмапа
 *
 * @author MeF Dei Corvi
 * @version SiteMapNode.php, v 0.0.1
 * @copyright (c) by VisualDesign
 */
class BMC_SiteMapNode {

	/**
	 * Заголовок страницы
	 *
	 * @var string
	 */
	public $Title = "Главная страница";

	/**
	 * Meta теги заголовка страницы
	 *
	 * @var string
	 */
	public $MetaTitle = "";

	/**
	 * Описание страницы
	 *
	 * @var string
	 */
	public $Description = "";

	/**
	 * Ключеваые слова страницы
	 *
	 * @var string
	 */
	public $Keywords = "";

	/**
	 * Флаг видимости страницы
	 *
	 * @var bool
	 */
	public $Visible = true;

	/**
	 * Флаг нахождения страницы в горизонтальном меню
	 *
	 * @var bool
	 */
	public $Horizontal = true;

	/**
	 * Путь до страницы в карте
	 *
	 * @var string
	 */
	public $Path = "";

	/**
	 * Вложенные подстраницы
	 *
	 * @var string
	 */
	public $Childs = Array();

	/**
	 * Родительская страница
	 *
	 * @var string
	 */
	public $Parent = null;

	/**
	 * Псевдоним страницы (идентификатор)
	 *
	 * @var string
	 */
	public $Alias = "";
	public $TextAlias = "";

	/**
	 * Идентификатор страницы
	 *
	 * @var int
	 */
	public $PageId = null;

	/**
	  Является ли узел "внутренним" модулем
	 */
	public $IsInnerModule = false;

	/**
	 * Текущая страница
	 *
	 * @var bool
	 */
	public $selected = false;

	/**
	 * Уровень вложенности
	 *
	 * @var int
	 */
	public $Level = 0;

	/**
	 * XML узел
	 *
	 * @var xmlNode
	 */
	private $xmlNode;
	public $IsCurrent = false;
	public $HideInMain = false;
	public $HideInMenu = false;
	public $WithoutDesign = false;
	public $thisIsModule = false;

	/**
	 * Выделена ли одна из подстраниц
	 *
	 * @var bool
	 */
	private $hasSelectedChild = false;
	private $url = null;
	private $isSeparator = null;

	/**
	  Является ли данный узел разделителем
	 */
	public function IsSeparator() {
		if ($this->isSeparator == null)
			$this->isSeparator = isset($this->xmlNode['separator']);

		return $this->isSeparator;
	}

	/**
	  Установить текущий элемент, как сепаратор
	 */
	public function SetSeparator() {
		$this->isSeparator = true;
	}

	function __construct($xmlNode = null) {
		$this->xmlNode = $xmlNode;
	}

	function __get($property) {
		if ($property == "ModuleId") {
			if (isset($this->xmlNode) && isset($this->xmlNode['moduleId']))
				return (int) $this->xmlNode['moduleId'];

			return null;
		}
		if ($property == "ModuleEdit") {
			if (isset($this->xmlNode) && isset($this->xmlNode['moduleEdit']))
				return (int) $this->xmlNode['moduleEdit'];

			return null;
		}
		if ($property == "TextAlias") {
			if (isset($this->xmlNode) && isset($this->xmlNode['TextAlias']))
				return $this->xmlNode['TextAlias'];

			return null;
		}
		if ($property == "Url") {
			if ($this->url != null)
				return $this->url;

			if (isset($this->xmlNode['url']))
				return (string) $this->xmlNode['url'];

			return $this->GenURL();
		}
		if ($property == "LanguageId") {
			if (isset($this->xmlNode['languageId']))
				return (int) $this->xmlNode['languageId'];

			return -1;
		}
		if ($property == "HideInMenu") {
			if (isset($this->xmlNode['hideInMenu']))
				return (int) $this->xmlNode['hideInMenu'];

			return 0;
		}
		else if ($property == "Selected") {
			return $this->selected;
		} else if ($property == "HasSelectedChild") {
			return $this->hasSelectedChild;
		}
		// Если запрошенного свойства не существует в классе, то проверяем аттрибуты xml узла
		else if (isset($this->xmlNode) && isset($this->xmlNode[$property])) {
			return (string) $this->xmlNode[$property];
		}

		//FIXME
		trigger_error("Свойства " . $property . " не существует");
		//throw new ArgumentException("Свойства " . $property . " не существует");
	}

	function __set($property, $value) {
		if ($property == "Url") {
			$this->url = $value;
		} else if ($property == "Selected" && $value === true) {
			$this->selected = true;

			if (isset($this->Parent))
				$this->Parent->HasSelectedChild = $value;
		}
		else if ($property == "HasSelectedChild" && $value === true) {
			$this->hasSelectedChild = true;

			if (isset($this->Parent))
				$this->Parent->HasSelectedChild = $value;
		}
		else
			trigger_error("Свойства " . $property . " не существует");
	}

	/**
	 * Возвращает ссылку на узел сайтмапа
	 *
	 * @return string
	 */
	private function GenURL() {
		if ($this->IsSeparator())
			return;

		$pagePath = $this->Path;
		if (substr($pagePath, -1) == '/')
			$pagePath = substr($pagePath, 0, -1);

		if (empty($pagePath)) {
			if (BLL_AppEngine::$isAdmin)
				return "/admin/";

			//return $this->LanguageId > 1 ? "/".GetAliasForLanguage($this->LanguageId)."/" : "/";
		}

		$pagePath .= "/";

		if (BLL_AppEngine::$isAdmin)
			$pagePath = "/admin" . $pagePath;
		else
		//$pagePath = ($this->LanguageId > 1 ? "/".GetAliasForLanguage($this->LanguageId) : "").$pagePath;
			$this->url = $pagePath;

		return $pagePath;
	}

	/**
	  Имеются ли подстраницы
	  @return bool
	 */
	public function HasChild() {
		return count($this->Childs) > 0;
	}

}

?>