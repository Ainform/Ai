<?php
function Transliterate($string)
{
	$cyr = array(
		"Щ", "Ш", "Ч", "Ц", "Ю", "Я", "Ж", "А", "Б", "В",
		"Г", "Д", "Е", "Ё", "З", "И", "Й", "К", "Л", "М", "Н",
		"О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ь", "Ы", "Ъ",
		"Э", "Є", "Ї", "І",
		"щ", "ш", "ч", "ц", "ю", "я", "ж", "а", "б", "в",
		"г", "д", "е", "ё", "з", "и", "й", "к", "л", "м", "н",
		"о", "п", "р", "с", "т", "у", "ф", "х", "ь", "ы", "ъ",
		"э", "є", "ї", "і"
	);
	$lat = array(
		"Shch", "Sh", "Ch", "C", "Yu", "Ya", "J", "A", "B", "V",
		"G", "D", "e", "e", "Z", "I", "y", "K", "L", "M", "N",
		"O", "P", "R", "S", "T", "U", "F", "H", "",
		"Y", "", "E", "E", "Yi", "I",
		"shch", "sh", "ch", "c", "Yu", "Ya", "j", "a", "b", "v",
		"g", "d", "e", "e", "z", "i", "y", "k", "l", "m", "n",
		"o", "p", "r", "s", "t", "u", "f", "h",
		"", "y", "", "e", "e", "yi", "i"
	);
	for ($i = 0; $i < count($cyr); $i++) {
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

function encodestring($string)
{
	$string = str_replace(array(' ', '"', '&', '<', '>'), array(" "), $string);
	$string = preg_replace("/[_\s\.,?!\[\](){}]+/", "_", $string);
	$string = preg_replace("/-{2,}/", "--", $string);
	$string = preg_replace("/_-+_/", "--", $string);
	$string = preg_replace("/[_\-]+$/", "", $string);
	$string = Transliterate($string);
	$string = strtolower($string);
	$string = preg_replace("/j{2,}/", "j", $string);
	$string = preg_replace("/[^0-9a-z_\-]+/", "", $string);
	return $string;
}

include_once ($_SERVER['DOCUMENT_ROOT'] . '/util/imageTransform.php');

// Получаем параметры высоты и ширины из строки запроса
$newwidth = !empty($_REQUEST['width']) ? intval($_REQUEST['width']) : '';
$newheight = !empty($_REQUEST['heigth']) ? intval($_REQUEST['heigth']) : '';
//если какого-то параметра нет, подменяем его вторым
if (empty($newheight) && !empty($newwidth)) {
	$newheight = $newwidth;
}
if (empty($newwidth) && !empty($newheigth)) {
	$newwidth = $newheight;
}

//Пропорционально ли изменять картинку
$prop = isset($_REQUEST['prop']) ? "p" : "";
//Обрезаем картинку по меньшей стороне (кропим)
$crop = isset($_REQUEST['crop']) ? "c" : "";
$gray = isset($_REQUEST['gray']) ? "g" : "";

//путь
$src = $_REQUEST['url'];

$filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $src;
$fileinfo = pathinfo($filePath);
$ext = strtolower($fileinfo['extension']);

if (!is_file($filePath)) {
	$filePath = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . 'nophoto.png';
	header("Content-type: image/png");
	readfile($filePath);
	die();
}

if ((empty($newwidth) && empty($newheight)) || (empty($prop) && empty($crop))) {
	header("Content-type: image/" . $ext);
	readfile($filePath);
	die();
}


$cacheFile = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . "cache/" . encodestring($fileinfo['filename']) . "-w" . $newwidth . "h" . $newheight . $prop . $crop . $gray . "." . $ext;


if (!is_file($cacheFile)) {

	$imageTransform = new imageTransform();
	if (!empty($crop)) {
		$imageTransform->crop($filePath, $newwidth, $newheight, $cacheFile);
	}

	if (!empty($prop)) {
		$imageTransform->resize($filePath, $newwidth, $newheight, $cacheFile);
	}

	if (!empty($gray)) {
		$imageTransform->gray($cacheFile, $cacheFile);
	}
}

header("Content-type: image/" . $ext);
readfile($cacheFile);
?>
