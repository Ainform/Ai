<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?
        include_once("config.php");
        // создаем экземпляр класса аутентификации
        $identity = new BLL_AdminAuthentication();

        // проводим аутентификацию
        $identity->Authenticate();

        // проводим авторизацию пользователя
        if (!$identity->IsAuthenticated()) {
            die("Not logged");
        }

        // инициализируем базу данных
        $dbManager = new DAL_DbManager(DbHost, DbName, DbUserName, DbUserPass);

        // если необходимо удалить файл, то удаляем его
        if (isset($_POST['deleteFile'])) {
            $filesDb = new DAL_AnalyticsFilesDb();
            $filesDb->DeleteFile(intval($_POST['deleteFile']));
            unset($filesDb);
            die();
        }
        // если необходимо поднять файл, то поднимаем его
        if (isset($_POST['upFile'])) {
            $filesDb = new DAL_AnalyticsFilesDb();
            $filesDb->Up(intval($_POST['upFile']));
            unset($filesDb);
            die();
        }
        // если необходимо опустить файл, то поднимаем его
        if (isset($_POST['downFile'])) {
            $filesDb = new DAL_AnalyticsFilesDb();
            $filesDb->Down(intval($_POST['downFile']));
            unset($filesDb);
            die();
        }

        // если необходимо удалить картинку, то удаляем её
        if (isset($_POST['deleteImg'])) {
            $imagesDb = new DAL_ImagesDb();
            $imagesDb->DeleteImage(intval($_POST['deleteImg']));
            unset($imagesDb);
            die();
        }
        // если необходимо поднять картинку, то поднимаем её
        if (isset($_POST['upImg'])) {
            $imagesDb = new DAL_ImagesDb();
            $imagesDb->Up(intval($_POST['upImg']));
            unset($imagesDb);
            die();
        }
        // если необходимо опустить картинку, то поднимаем её
        if (isset($_POST['downImg'])) {
            $imagesDb = new DAL_ImagesDb();
            $imagesDb->Down(intval($_POST['downImg']));
            unset($imagesDb);
            die();
        }

        // получаем идентификатор
        $id = "";
        if (isset($_GET['id']))
            $id = $_GET['id'];


        if (empty($id) || !isset($_FILES[$id . '_file']))
            die("ID not founded");

        //Если загружается файл, а не картинка

        if ($id == "upltfifile" || $id == "uplfile") {

            $file = $_FILES[$id . '_file'];

            $title = isset($_POST[$id . '_title']) ? $_POST[$id . '_title'] : "";
            $title = isset($_POST[$id . '_title']) ? $_POST[$id . '_title'] : "";
            $ManufacturerId = isset($_POST[$id . '_ManufacturerId']) ? $_POST[$id . '_ManufacturerId'] : 0;
            $folder = isset($_POST[$id . '_folder']) ? $_POST[$id . '_folder'] : "";

            $folderPath = './upload/' . $folder . '/';
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time().".".$ext;

            //echo $folderPath;
            //die();
            // если папка для файлов не существует, то создаём её и проставляем права
            //Debug($folderPath,false);
            if (!file_exists($folderPath))
                mkdir($folderPath, 0777);

            $file_temp = array();
            $file_temp['folder'] = $folder;
            $file_temp['filename'] = $file['name'];

            //если тайтла нет - выводим название файла непосредственно
            if ($title == "") {
                $file_temp['title'] = $filename;
            } else {
                $file_temp['title'] = $title;
            }
            $file_temp['size'] = $file['size'];

            if (isset($ManufacturerId)) {
                $file_temp['ManufacturerId'] = $ManufacturerId;
            } else {
                $file_temp['ManufacturerId'] = 0;
            }
            $file_temp['ManufacturerId'] = $ManufacturerId;
            $mandb = new DAL_ManufacturersDb();
            $manname = $mandb->GetNameById($ManufacturerId);
            unset($mandb);
            $filesDb = new DAL_AnalyticsFilesDb();
            $file_temp['id'] = $filesDb->AddFile($file_temp);
            unset($filessDb);

            $filePath = DAL_AnalyticsFilesDb::GetFilePath($file_temp);

            // перемещаем файл в папку картинок
            move_uploaded_file($file['tmp_name'], $folderPath . $filename);

            $fileSize = (int) ($file['size'] / 1024);

            // закрываем базу данных
            $dbManager->CloseConnection();
            ?>
            <script language="JavaScript" type="text/javascript">
                window.parent.onFileUploadFinished("<?= $id ?>", '<?= $filePath ?>', '<?= $file_temp['id'] ?>', '<?= $file_temp['title'] ?>', '<?= $fileSize ?>','<?= $manname ?>');
            </script>

        <?
        } else {
            $thumbWidth = isset($_POST[$id . '_tw']) ? intval($_POST[$id . '_tw']) : 100;
            $thumbHeight = isset($_POST[$id . '_th']) ? intval($_POST[$id . '_th']) : "";
//Debug($_FILES);
            $file = $_FILES[$id . '_file'];

            $title = isset($_POST[$id . '_title']) ? $_POST[$id . '_title'] : "";
            $folder = isset($_POST[$id . '_folder']) ? $_POST[$id . '_folder'] : "";

            $clear = 0;
            if (isset($_POST[$id . '_one'])) {
                $imagesDb = new DAL_ImagesDb();
                $imagesDb->DeleteFolder($folder);
                unset($imagesDb);
                $clear = 1;
            }

            $folderPath = './upload/' . $folder . '/';

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time().".".$ext;


            // если папка для файлов не существует, то создаём её и проставляем права
            if (!file_exists($folderPath))
                mkdir($folderPath, 0777, true);

            $image = array();
            $image['Folder'] = $folder;
            $image['FileName'] = $filename;
            $image['Title'] = $title;
            $image['FileSize'] = $file['size'];
//Debug($file['tmp_name']);
            $size = @getimagesize($file['tmp_name']);
            if (isset($size[0])) {
                $image['Width'] = $size[0];
                $image['Height'] = $size[1];
            }
            else
                die("It's not image");

            $imagesDb = new DAL_ImagesDb();
            $image['ImageId'] = $imagesDb->AddImage($image);
            unset($imagesDb);

            $imgPath = DAL_ImagesDb::GetImagePath($image);

            // перемещаем файл в папку картинок
            move_uploaded_file($file['tmp_name'], $folderPath . $filename);

            $fileSize = (int) ($file['size'] / 1024);

            // закрываем базу данных
            $dbManager->CloseConnection();
            ?>
            <script language="JavaScript" type="text/javascript">
                window.parent.onUploadFinished("<?= $id ?>", '<?= $imgPath ?>', '<?= $image['ImageId'] ?>', '<?= $image['Title'] ?>', '<?= $image['Width'] ?>', '<?= $image['Height'] ?>', '<?= $fileSize ?>', '<?= $thumbWidth ?>', '<?= $thumbHeight ?>',<?= $clear ?>);
            </script>
<? } ?>
    </body>
</html>
