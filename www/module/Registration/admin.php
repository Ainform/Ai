<?php
class UsersModuleEdit extends BMC_BaseModule {
/**
 * Конструктор, задает параметры
 *
 * @return void
 */
    function __construct($moduleId) {
        parent::__construct($moduleId);
    }

    /**
     * Количество новостей на страницу
     */
    const RecordsOnPage = 10;

    /**
     * Номер текущей страницы
     *
     * @var int
     */
    public $curPage;


    /**
     * Модель работы с БД
     */
    var $Model;

    /**
     * Функция для создания html-кода модуля
     *
     * @return void
     */
    function DataBind() {
        $this->Model = new DAL_UsersDb;
        $this->curPage = intval(Request("pageNum",0));

        $this->data["ErrorMessage"] = "";

        $action = CharOnly(Request("act","List"));

        //Debug($action,false);
        // Debug($_POST,false);
        if(isset($_POST['archive_upload'])) {
            $this->UploadArchive();
        }
        elseif($action == "List") {
            $this->GetLister();
        }elseif($action == "Edit") {
            $this->Edit();
        }elseif($action == "Add") {
            $this->Add();
        }elseif($action == "Upload") {
            $this->UploadCSV();
        }
        else
            if($action == "Del")
                $this->DeleteUser();

    //unset($this->Model);


    }

    private function Add() {
        if(!empty($this->data["pass"])) {
            $save = array();
            $save["FIO"] = $this->data["txtFIO"];
            $save["Phone"] = $this->data["txtPhone"];
            $save["Email"] = $this->data["txtEmail"];

            $save["ModuleId"] = $this->moduleId;

            //if(!empty($this->data["pass"]) and !empty($this->data["passRetry"])) {
            if($this->data["pass"] == $this->data["passRetry"]) {
                $save["Password"] = md5($this->data["pass"]);
                $this->Model->insert($save);
                Redirected("/admin/modules/$this->moduleId.php");
            }
            else {
                $this->data["ErrorMessage"] = "<b style='color:red'>Пароль подтвержден неверно!</b>";
                $this->template = "editUser.tpl";
            }
        //}

        //var_dump($this->data["ErrorMessage"]);
        //die();
        }
        else
            if (!intval(Request("UserId",0))) {
                $this->data["hideReport"] = 1;
            }


        $this->template = "editUser.tpl";
    }

    private function DeleteUser() {
        $UserId = intval(Request("UserId",0));

        if ($UserId) {
        //echo 123;
        //$this->Model->delete(array("UserId" => $UserId));
        //echo 321;
            $usersDb = new DAL_UsersDb();
            $usersDb->Delete($UserId);
            unset($usersDb);
        }

        //print_r($this->Model);
        //die();

        Redirected("/admin/modules/$this->moduleId.php");
    }

    private function Edit() {
        $this->data["ErrorMessage"] = "";

        $UserId = intval(Request("UserId",0));
        if(!empty($this->data["pass"]) and $this->data["pass"] == $this->data["passRetry"]) {
            $save = array();
            $save["FIO"] = $this->data["txtFIO"];
            $save["Phone"] = $this->data["txtPhone"];
            $save["Email"] = $this->data["txtEmail"];
            $save["ModuleId"] = $this->moduleId;
            $save["UserId"] = $this->data["UserId"];

            if(!empty($this->data["pass"]) and !empty($this->data["passRetry"])) {
                if($this->data["pass"] == $this->data["passRetry"]) {
                    $save["Password"] = md5($this->data["pass"]);
                }
            }

            //print_r($this->Model->Update);

            $this->Model->update($save);
            Redirected("/admin/modules/$this->moduleId.php");
        }
        else {
            $this->data["ErrorMessage"] = "<b style='color:red'>Пароль не введен или подтвержден неверно!</b>";
            $this->template = "editUser.tpl";
        //			echo $this->data["ErrorMessage"] ;
        }

        $result = $this->Model->GetUser($UserId);
        $this->data["Users"] = $result[0];
        $this->template = "editUser.tpl";
    }


    private function GetLister() {
        $this->data["Users"] = $this->Model->GetUsersPage($this->moduleId,$this->curPage,self::RecordsOnPage);
        $this->data["Pager"] = $this->Model->GetPager($this->moduleId,$this->curPage,self::RecordsOnPage);
    }

    private function UploadCSV() {
    //Debug($_FILES["csv"]);
        $ReportsDb = new DAL_ReportsDb;

        //$res = $ReportsDb->GetAllReportsByUser(Request("UserId",0),$this->moduleId)

        if(!empty($this->data["step"]) and $this->data["step"] == 2) {
            $uploadfile = $_SERVER['DOCUMENT_ROOT'] . "/upload/CSV/". basename($_FILES["csv"]["name"]);

            if(!is_dir(dirname($uploadfile))) {
                mkdir(dirname($uploadfile),0777);
            }
            if (move_uploaded_file($_FILES["csv"]['tmp_name'], $uploadfile)) {
				/*Подключаем CSV Reader*/
                require_once $_SERVER['DOCUMENT_ROOT'] . "/App_Code/PHP/CSV.php";
                $csv = new CSV($uploadfile);
                $save = $csv->return;
                //Debug ($save,false);
                $ReportsDb = new DAL_ReportsDb;
                $Message = $ReportsDb->MegaSave($save,intval(Request("UserId",0)),$this->moduleId);
                $this->data["Message"]= "Обновлено:".$Message['update']."; Добавлено: ".$Message['insert'].".";
            //Debug($csv->return);
            }
        }
        $this->template = "uplaodForm.tpl";
    }
    private function UploadArchive() {
        /*Подключаем CSV Reader*/
        require_once $_SERVER['DOCUMENT_ROOT'] . "/App_Code/PHP/CSV.php";
        //Определяем папку с архивами
        $ArchivePath=$_SERVER['DOCUMENT_ROOT'] . "/upload/CSVArchive1/";

        $ReportsDb = new DAL_ReportsDb;

        $uploadfile = $_SERVER['DOCUMENT_ROOT'] . "/upload/CSVArchive1/". basename($_FILES["archive"]["name"]);

        if(!is_dir(dirname($uploadfile))) {
            mkdir(dirname($uploadfile),0777);
        }
        if (move_uploaded_file($_FILES["archive"]['tmp_name'], $uploadfile)) {
        //разархивируем при помощи сторонней библиотечки, чтоб не зависить от установленных модулей
            require_once($_SERVER['DOCUMENT_ROOT'].'/pclzip.lib.php');
            $archive = new PclZip($uploadfile);
            $list = $archive->extract(PCLZIP_OPT_PATH, $ArchivePath);
            $archive->privCloseFd();

            $updates=0;
            $inserts=0;

            $archiveDir=opendir($ArchivePath);
            //echo "папка для разархивирования:".$ArchivePath;
            while(!is_bool($dir=readdir($archiveDir))) {
                //echo $ArchivePath.$dir."<br>";
                if(($dir!="..")&&($dir!=".")&&is_dir($ArchivePath.$dir)) {
                    //echo $ArchivePath.$dir."<br>";
                    $DirWithCSV=opendir($ArchivePath.$dir);
                    while ($file = readdir($DirWithCSV)) {

                        if (($file!="..")&&($file!=".")&&!is_dir($ArchivePath.$dir.'/'.$file)) {
                            //echo "путь: ".$ArchivePath.$dir.'/'.$file."<br>";
                            $csv = new CSV($ArchivePath.$dir.'/'.$file);
                            $save = $csv->return;
                            $Message = $ReportsDb->MegaSave($save,intval($dir),$this->moduleId);
                            @$updates+=$Message['update'];
                            @$inserts+=$Message['insert'];
                        }
                    }
                    closedir($DirWithCSV);
                }
            }
            $this->data["Message"]= "Обновлено:".$updates."; Добавлено: ".$inserts.".";
            Utility_FileUtility::ClearFolder($ArchivePath);
            closedir($archiveDir);


        }
        $this->GetLister();
    }


}

?>