<?

require_once($_SERVER['DOCUMENT_ROOT'] . '/' . 'config.php');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate( "D, d M Y H:i:s" )."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/xml; charset=utf-8");

// определяем папку в сессии
session_start();

if (!session_is_registered(BLL_Uploader_Image::IndexUploadPath))
	die('<?xml version="1.0" encoding="utf-8"?><root>Error 1</root>');

$folder = $_SESSION[BLL_Uploader_Image::IndexUploadPath];

// получаем список файлов
$images = BLL_Uploader_Image::GetList();

// создаем Xml данные для возврата.
$xml = '<?xml version="1.0" encoding="utf-8"?><root>';

$xml .= '<directory>' . str_replace(App_Info::GetFolderPath('upload'), '', $folder . PHP_Url::AddressSplitter) . '</directory>';

if (0 < count($images))
{
	// формируем список сообщений для отправки
	foreach ($images as $image)
	{
		$xml .= '<image>';
		$xml .= '<fileUrl>' . $image['FileName'] . '</fileUrl>';
		$xml .= '<fileTitle>' . (strlen($image['Title']) > 0 ? htmlspecialchars($image['Title']) : 'Без названия') . '</fileTitle>';
		$xml .= '<fileSize>' . (ceil(filesize($folder . PHP_IO::DirSplitter . $image['FileName'])/1024)) . '</fileSize>';
		$xml .= '</image>';
	}
}

$xml .= '</root>';

echo iconv('WINDOWS-1251', 'UTF-8', $xml);
?>
