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
	die('<?xml version="1.0" encoding="utf-8"?><root>Error 4</root>');

$folder = $_SESSION[BLL_Uploader_Image::IndexUploadPath];
$fileName = Request('fileName');

// получаем список файлов
$images = BLL_Uploader_Image::GetList();

$upped = false;

foreach ($images as $image)
{
	if ($image['FileName'] == $fileName)
	{
		// обновляем позиции
		$order = new DAL_ArrayOrder($images, 'Order');
		$imageList = $order->DownRecord($image['Order']);

		// устанавливаем новый список
		BLL_Uploader_Image::SetList($imageList);

		$upped = true;

		break;
	}
}

die('<?xml version="1.0" encoding="utf-8"?><root>File '.($upped ? '' : 'Not').' Downed</root>');

?>
