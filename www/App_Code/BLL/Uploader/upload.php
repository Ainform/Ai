<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/' . 'config.php';

// если пришел файл
if (!empty($_FILES))
{
	// закачиваем
	$file = MoveUplodedFile();

	// обновляем окно с картинками
	echo '<script language="JavaScript">
		  	parent.UpdatePictures();
		  </script>';
}

function MoveUplodedFile()
{
	// получаем загруженный файл
	$fileInfo = $_FILES['uploader'] ;

	// получаем информацию о загруженном файле
	$fileName = $fileInfo['name'] ;
	$fileExtension = Utility_FileUtility::GetFileExtension($fileName);

	// создаем новое имя файла
	$newFileName = uniqid('file_', true).'.'.$fileExtension;

	// определяем папку в сессии
	session_start();

	// проверяем на наличие переменной сессии
	if (!session_is_registered(BLL_Uploader_Image::IndexUploadPath))
		throw new Exception('Ошибка. Не найден путь к временной папке.');

	$uploadFolder = $_SESSION[BLL_Uploader_Image::IndexUploadPath];

	// переносим из временной в основную папку
	$moved = move_uploaded_file($fileInfo['tmp_name'], $uploadFolder . PHP_IO::DirSplitter . $newFileName);

	if (!$moved) // не удалось загрузить изображение, выходим
		return false;


	// получаем список файлов
	$imagesList = BLL_Uploader_Image::GetList();

	// получаем позицию
	$order = new DAL_ArrayOrder($imagesList, 'Order');
	$position = $order->InsertRecord();

	// название изображения
	$title = GetPostValue('txtIName', "");

	// добавляем запись
	$imagesList[] = array('FileName' => $newFileName, 'Order' => $position, 'Title' => $title);

	// сохраняем
	BLL_Uploader_Image::SetList($imagesList);

	return true;
}
?>
