<?php

/**
 * Вспомогательные функции
 *
 * @copyright (c) by Ainform
 */
// путь к папке с классами

define('AppCode', $_SERVER['DOCUMENT_ROOT'] . '/App_Code');
define('TemplateFolder', $_SERVER['DOCUMENT_ROOT'] . '/template');

/**
 * Автоматически загружает классы
 *
 * @param string $className имя класса
 */
function autoload($className) {
	$fileName = AppCode . '/' . str_replace('_', '/', $className) . '.php';
	if (file_exists($fileName)) {
		include_once($fileName);
		return;
	}
	$fileName = str_replace('_', '/', $className) . '.php';
	if (file_exists($fileName)) {
		include_once($fileName);
		return;
	}
}

spl_autoload_register("autoload");

// выводим все сообщения в браузер
if (AppDebug) {
	error_reporting(E_ALL | E_STRICT);

	Utility_PhpConsole::start();
} else {
	error_reporting(0);
}

define('D_S', DIRECTORY_SEPARATOR);
define('U_S', '/');

// убираем слеш в конце $_SERVER['DOCUMENT_ROOT']
$position = (strlen($_SERVER['DOCUMENT_ROOT']) - 1);
if ($_SERVER['DOCUMENT_ROOT'][$position] == '/') {
	$_SERVER['DOCUMENT_ROOT'] = substr($_SERVER['DOCUMENT_ROOT'], 0, $position);
}

$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'];

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline) {
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}

	switch ($errno) {
		case E_USER_ERROR:
			echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
			echo "  Fatal error on line $errline in file $errfile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...<br />\n";
			//exit(1);
			break;

		case E_USER_WARNING:
			debug("<b>My WARNING</b> [$errno] $errstr ($errfile, $errline)<br />\n");
			break;

		case E_USER_NOTICE:
			debug("<b>My NOTICE</b> [$errno] $errstr ($errfile, $errline)<br />\n");
			break;

		default:
			debug("Unknown error type: [$errno] $errstr ($errfile, $errline)<br />\n");
			break;
	}

	/* Don't execute PHP internal error handler */
	return true;
}

// set to the user defined error handler
//$old_error_handler = set_error_handler("myErrorHandler");

/**
 * Возвращает значение переменной в запросе по ее имени
 *
 * @param string $name
 * @param string $defaultValue значение по умолчанию
 * @return string
 */
function Request($name, $defaultValue = null) {

	if (isset($_REQUEST[$name]))
		return $_REQUEST[$name];

	$curPage = BMC_SiteMap::getCurPage();
	$tmp = @$curPage->$name;
	if (!empty($tmp)) {
		return (string) $tmp;
	}

	return $defaultValue;
}

/**
 * Перенаправляет клиента на новый URL.
 *
 * @param string $url адрес перенаправления
 */
function Redirected($url) {
	header("Location: " . $url);
	//die();
}

/**
 * Перенаправляет клиента на страницу с редактированием страницы
 *
 * @param int $moduleId идентификатор модуля, на редактирование страницы которого необходимо перейти
 */
function RedirectToPageEdit($moduleId) {
	$db = new DAL_ModulesDb();
	$module = $db->GetModule($moduleId);
	unset($db);

	header("Location: /admin/pageslist/?id=" . $module['PageId']);
	die();
}

function RedirectToModuleEdit($moduleId) {
	header("Location: /admin/modules/" . $moduleId . ".php");
	die();
}

/**
 * Перенаправляет клиента на URL с которого он пришел.
 *
 */
function RedirectBack() {
	Redirected($_SERVER['HTTP_REFERER']);
}

/**
 * Возвращает статус получения данных формы
 *
 * @return bool
 */
function IsPostBack() {
	// если пришли данные формы
	if (count($_POST) != 0)
		return true;

	return false;
}

/**
 * Функция для выдачи данных с формы при их присутствии, при отсутствии null или указанное значение
 *
 * @param string $index индекс в массиве данных формы
 * @param string $defaultValue значение по умолчанию
 * @return string
 */
function GetPostValue($index, $defaultValue = null) {
	return (sizeof($_POST) > 0 && isset($_POST[$index]) && (!is_null($_POST[$index]) || strlen(trim($_POST[$index])) != 0)) ? $_POST[$index] : $defaultValue;
}

function SetMagicQuotesOff() {
	if (get_magic_quotes_gpc()) {
		foreach ($_POST as $param => $value)
			$_POST[$param] = stripslashes($value);
	}
}

/**
  Делает html безопасную строку
 */
function HtmlEncode($value) {
	return htmlentities($value, ENT_QUOTES, "UTF-8");
}

/**
 * Возвращает хэш, завязанный на стек вызовов

 * @return #Fsha1|?
 */
function GetBackTraceHash() {
	$backtrace = serialize(debug_backtrace());
	return sha1($backtrace);
}

/**
  Прошла ли страничка валидацию
 */
function IsValid() {
	return BLL_AppEngine::IsFormValid();
}

/**
  Возвращает язык сайта
 */
function GetSiteLangId() {
	if (isset($_REQUEST['langId'])) {
		$_SESSION['LangId'] = $_REQUEST['langId'];
	} else {
		$_SESSION['LangId'] = 1;
	}

	return $_SESSION['LangId'];
}

/**
Устанавливает язык сайта
 * @param $languageId
 */
function SetSiteLangId($languageId) {
	$_SESSION['LangId'] = $languageId;
}

/**
Возвращает алиас для языка
 * @param $languageId
 * @return
 */
function GetAliasForLanguage($languageId) {
	static $languages = null;

	if ($languages == null) {
		$languages = Array();
		$langDb = new DAL_LanguagesDb();
		$langs = $langDb->GetLanguages();

		foreach ($langs as $language) {
			$languages[$language["LanguageId"]] = $language["Alias"];
		}
	}

	return $languages[$languageId];
}

function print_nice($elem, $max_level=10, $print_nice_stack=array()) {
	if (is_array($elem) || is_object($elem)) {
		if (in_array($elem, $print_nice_stack, true)) {
			echo "<font color=red>&nbsp;рекурсия</font>";
			return;
		}
		$print_nice_stack[] = &$elem;
		if ($max_level < 1) {
			echo "<font color=red>достигнут предел вывода</font>";
			return;
		}
		$max_level--;
		echo "<table border=0 cellspacing=0 cellpadding=0 style='font-size:8pt;background:#fff;font-family:\"Trebuchet MS\";width:100%;'>";
		$cur = md5(microtime());
		if (is_array($elem)) {
			$thiscount = count($elem);
			if ($thiscount == 0) {
				echo '<tr><td colspan=2 style="background-color:#666;padding:2px;"><strong><font color=white>пустой массив</font></strong></td></tr><tr><td colspan=2><div id="' . $cur . '" style="height:0px;overflow:hidden"><table cellspacing=0 cellpadding=0>';
			} else {
				echo '<tr><td colspan=2 style="background-color:#666;padding:2px;"><strong><font color=white><span style="cursor:pointer" onclick="if(document.getElementById(\'' . $cur . '\').style.height==\'0px\'){document.getElementById(\'' . $cur . '\').style.height=\'auto\';this.innerHTML=\'&nbsp;-&nbsp;\'}else{document.getElementById(\'' . $cur . '\').style.height=\'0px\';this.innerHTML=\'&nbsp;+&nbsp;\'}">&nbsp;+&nbsp;</span>массив (' . $thiscount . " " . declension($thiscount, array("элемент", "элемента", "элементов")) . ')</font></strong></td></tr><tr><td colspan=2><div id="' . $cur . '" style="height:0px;overflow:hidden"><table border=0 cellspacing=0 cellpadding=0 style="font-size:8pt;font-family:\'Trebuchet MS\';width:100%;">';
			}
		} else {
			echo '<tr><td colspan=2 style="background-color:#666;padding:2px;"><strong><font color=white><span style="cursor:pointer" onclick="if(document.getElementById(\'' . $cur . '\').style.height==\'0px\'){document.getElementById(\'' . $cur . '\').style.height=\'auto\';this.innerHTML=\'&nbsp;-&nbsp;\'}else{document.getElementById(\'' . $cur . '\').style.height=\'0px\';this.innerHTML=\'&nbsp;+&nbsp;\'}">&nbsp;+&nbsp;</span>объект типа: ' . get_class($elem) . '</font></strong></td></tr><tr><td colspan=2><div id="' . $cur . '" style="height:0px;overflow:hidden"><table border=0 cellspacing=0 cellpadding=0 style="font-size:8pt;font-family:\'Trebuchet MS\';width:100%;">';
		}
		$color = 0;
		foreach ($elem as $k => $v) {
			if ($max_level % 2) {
				$rgb = ($color++ % 2) ? "#999" : "#ccc";
			} else {
				$rgb = ($color++ % 2) ? "#b7db59" : "#92bc3c";
			}
			echo '<tr><td valign="top" style="width:20px;padding:2px;background-color:' . $rgb . ';">';
			echo $k . "</td><td style=''>";
			print_nice($v, $max_level, $print_nice_stack);
			echo "</td></tr>";
		}
		echo "</table></div></td></tr></table>";
		return;
	}
	if ($elem === null) {
		echo "<font color=#92bc3c>&nbsp;NULL</font>";
	} elseif ($elem === 0) {
		echo "0";
	} elseif ($elem === true) {
		echo '<font color=#92bc3c>&nbsp;true</font>';
	} elseif ($elem === false) {
		echo '<span style="color:#92bc3c">&nbsp;false</span>';
	} elseif ($elem === "") {
		echo '<span style=\'color:#92bc3c\'>&nbsp;пустая строка</span>';
	} else {
		echo "&nbsp;" . str_replace("\n", "<strong><font color=red>*</font></strong><br>\n", $elem);
	}
}

/**
 * Choost russion word declension based on numeric.
 * Example for $expressions: array("ответ", "ответа", "ответов")
 *
 * @param $int
 * @param $expressions
 * @return
 *
 */
function declension($int, $expressions) {
	if (count($expressions) < 3)
		$expressions[2] = $expressions[1];
	settype($int, "integer");
	$count = $int % 100;
	if ($count >= 5 && $count <= 20) {
		$result = $expressions['2'];
	} else {
		$count = $count % 10;
		if ($count == 1) {
			$result = $expressions['0'];
		} elseif ($count >= 2 && $count <= 4) {
			$result = $expressions['1'];
		} else {
			$result = $expressions['2'];
		}
	}
	return $result;
}

/**
 * Функция для выдачи данных из масиива или объекта
 *
 * @param  $params объект, массив или строка
 * @param bool $exit остновить работу скрипта
 * @param int $count
 * @count int сколько раз выполнится вывод
 */
function wtf($params = null, $exit = true, $count=1) {
	global $debug_count;

	$debug = debug_backtrace();

	echo "<span style='font-family:\"Trebuchet MS\";font-size:9pt'>Отладка в: " . $debug[0]["file"] . ", на строке: <b>" . $debug[0]["line"] . "</b></span><br>";
	if (!empty($params)) {
		if (is_array($params)) {
			echo "<div style='position:absolute;background:#fff;z-index:9999;width:auto;height:auto;top:0;left:0;'>";
			print_nice($params);
			echo "</div>";
		} elseif (is_object($params)) {
			echo "<div style='position:absolute;background:#fff;z-index:9999;width:auto;height:auto;top:0;left:0;'>";
			print_nice($params);
			echo "</div>";
		} elseif (is_string($params)) {
			echo $params . "<br><br><br>";
		} elseif (is_numeric($params)) {
			echo $params . "<br><br><br>";
		} else {
			echo "<pre>";
			var_dump($params);
			echo "</pre>";
		}
	}

	if (($debug_count <= $count || $count == 1) && $exit) {
		exit;
	}
	$debug_count++;
}

/**
 * Функция возвращает только Буквенно-числовое значение
 *
 * @param string $string Текст для очистки
 */
function CharOnly($string = null) {
	return preg_replace("![^0-9а-яa-z]!uis", "", $string);
}

function reverse($object) {
	$out = array();
	if (is_a($object, 'XmlNode')) {
		$out = $object->toArray();
		return $out;
	} else if (is_object($object)) {
		$keys = get_object_vars($object);
		if (isset($keys['_name_'])) {
			$identity = $keys['_name_'];
			unset($keys['_name_']);
		}
		$new = array();
		foreach ($keys as $key => $value) {
			if (is_array($value)) {
				$new[$key] = (array) reverse($value);
			} else {
				if (isset($value->_name_)) {
					$new = array_merge($new, reverse($value));
				} else {
					$new[$key] = reverse($value);
				}
			}
		}
		if (isset($identity)) {
			$out[$identity] = $new;
		} else {
			$out = $new;
		}
	} elseif (is_array($object)) {
		foreach ($object as $key => $value) {
			$out[$key] = reverse($value);
		}
	} else {
		$out = $object;
	}
	return $out;
}

function fill($count=0) {
	$string = '';
	for ($i = 0; $i <= $count; $i++) {
		$string.=' ';
	}
	return $string . $count;
}

function debug($message, $tags = 'debug') {
	Utility_PhpConsole::debug($message, $tags);
}

function Transliterate($string){
  $cyr=array(
     "Щ", "Ш", "Ч","Ц", "Ю", "Я", "Ж","А","Б","В",
     "Г","Д","Е","Ё","З","И","Й","К","Л","М","Н",
     "О","П","Р","С","Т","У","Ф","Х","Ь","Ы","Ъ",
     "Э","Є", "Ї","І",
     "щ", "ш", "ч","ц", "ю", "я", "ж","а","б","в",
     "г","д","е","ё","з","и","й","к","л","м","н",
     "о","п","р","с","т","у","ф","х","ь","ы","ъ",
     "э","є", "ї","і"
  );
  $lat=array(
     "Shch","Sh","Ch","C","Yu","Ya","J","A","B","V",
     "G","D","e","e","Z","I","y","K","L","M","N",
     "O","P","R","S","T","U","F","H","",
     "Y","" ,"E","E","Yi","I",
     "shch","sh","ch","c","Yu","Ya","j","a","b","v",
     "g","d","e","e","z","i","y","k","l","m","n",
     "o","p","r","s","t","u","f","h",
     "", "y","" ,"e","e","yi","i"
  );
  for($i=0; $i<count($cyr); $i++)  {
     $c_cyr = $cyr[$i];
     $c_lat = $lat[$i];
     $string = str_replace($c_cyr, $c_lat, $string);
  }
  $string =
  	preg_replace(
  		"/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/",
  		"\${1}e", $string);
  $string =
  	preg_replace(
  		"/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/",
  		"\${1}'", $string);
  $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
  $string = preg_replace("/^kh/", "h", $string);
  $string = preg_replace("/^Kh/", "H", $string);
  return $string;
}
function encodestring($string){
  $string = str_replace(array(' ','"','&','<','>'),array(" "), $string);
  $string = preg_replace("/[_\s\.,?!\[\](){}]+/", "_", $string);
  $string = preg_replace("/-{2,}/", "--", $string);
  $string = preg_replace("/_-+_/","--",$string);
  $string = preg_replace("/[_\-]+$/", "", $string);
  $string = Transliterate($string);
  $string = strtolower($string);
  $string = preg_replace("/j{2,}/", "j", $string);
  $string = preg_replace("/[^0-9a-z_\-]+/", "", $string);
  return $string;
}


