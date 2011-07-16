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
	die('<?xml version="1.0" encoding="utf-8"?><root>Error 2</root>');

$folder = $_SESSION[BLL_Uploader_Image::IndexUploadPath];
$fileName = Request('fileName');

// получаем список файлов
$images = BLL_Uploader_Image::GetList();

foreach ($images as $image)
{
	if ($image['FileName'] == $fileName)
	{
		// обновляем позиции
		$order = new DAL_ArrayOrder($images, 'Order');
		$imageList = $order->DeleteRecord($image['Order']);

		// удаляем файл
		if (PHP_IO_File::Exists($folder . PHP_IO::DirSplitter . $fileName))
			PHP_IO_File::Delete($folder . PHP_IO::DirSplitter . $fileName);

		// устанавливаем новый список
		BLL_Uploader_Image::SetList($imageList);

		break;
	}
}

die('<?xml version="1.0" encoding="utf-8"?><root>File Deleted</root>');

?>
