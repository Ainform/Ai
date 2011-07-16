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

        if($action == "List") {
            $this->GetLister();
        }
        elseif($action == "Edit") {
            $this->Edit();
        }
        elseif($action == "Upload") {
            $this->UploadCSV();
        }
        else
        if($action == "Del")
            $this->DeleteUser();
    }

    private function DeleteUser() {
        $UserId = intval(Request("UserId",0));

        if ($UserId) {
            $usersDb = new DAL_UsersDb();
            $usersDb->Delete($UserId);
            unset($usersDb);
        }
        Redirected("/admin/modules/$this->moduleId.php");
    }

    private function Edit() {
        $this->data["ErrorMessage"] = "";

        $UserId = intval(Request("UserId",0));
        /* if(!empty($this->data["pass"]) and $this->data["pass"] == $this->data["passRetry"]) {
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

			$this->Model->update($save);
			Redirect("/admin/modules/$this->moduleId.php");
			}
			else {
			//$this->data["ErrorMessage"] = "<b style='color:red'>Пароль не введен или подтвержден неверно!</b>";
			$this->template = "editUser.tpl";
			//			echo $this->data["ErrorMessage"] ;
			}
        */
        $manDb= new DAL_ManufacturersDb();
        $allManufacturers=$manDb->GetAll();
        $sales=array();
        $discountDb=new DAL_DiscountsDb();
        $sales=$discountDb->GetSalesForUser($UserId);


        foreach($allManufacturers as &$manufacturer) {
            $currentsale='';
            foreach($sales as $sale) {
                if($sale['ManufacturerId']==$manufacturer['ManufacturerId']) {
                    $currentsale=$sale['Discount'];
                }
            }
            $manufacturer['Discount']=$currentsale;
        }
        $this->data["ManufacturersDiscount"]=$allManufacturers;
        $result = $this->Model->GetUser($UserId);
        $this->data["Users"] = $result[0];
        $this->template = "editUser.tpl";
    }


    private function GetLister() {
        $UserDb = new DAL_UsersDb();
        $this->data["Users"] = $UserDb->GetUsersPage($this->moduleId,$this->curPage,self::RecordsOnPage);
        $this->data["Pager"] = $UserDb->GetPager($this->moduleId,$this->curPage,self::RecordsOnPage);
        unset($UserDb);
    }

    function handlerPartner($UserId) {

        $UserDb = new DAL_UsersDb();
        $UserDb->Partner($UserId);
        unset($UserDb);

        $this->GetLister();
    }

    function handlerBtnUserSave($UserId) {

        $UserRow = Array();
        $UserRow['UserId'] = $UserId;
        $UserRow['FIO'] = $this->data['txtFIO'];
        $UserRow['Name'] = $this->data['txtName'];
        $UserRow['Email'] = $this->data['txtEmail'];
        $UserRow['KeyQuestion'] = $this->data['txtKeyQuestion'];
        $UserRow['KeyAnswer'] = $this->data['txtKeyAnswer'];
        $UserRow['OrgName'] = $this->data['txtOrgName'];
        $UserRow['Specialization'] = $this->data['txtSpecialization'];
        $UserRow['Address'] = $this->data['txtAddress'];
        $UserRow['Position'] = $this->data['txtPosition'];
        $UserRow['Phone'] = $this->data['txtPhone'];
        $UserRow['Interest'] = $this->data['txtInterest'];

        //тут необходим рефакторинг, а мне пора домой
        $thismanufacturers=array();
        $thisclearmanufacturers=array();

        $manDb=new DAL_ManufacturersDb();
        $allman=$manDb->GetAll();

        $discountDb=new DAL_DiscountsDb();
        $discounts=$discountDb->GetSalesForUser($UserId);

        foreach ($discounts as $discount) {
            $thismanufacturers[]=$discount['ManufacturerId'];
            $discountsId[$discount['ManufacturerId']]=$discount['id'];
        }
        foreach($allman as $man) {
            if(isset($this->data['man'][$man['ManufacturerId']])&&$this->data['man'][$man['ManufacturerId']]!='') {
                if(in_array($man['ManufacturerId'],$thismanufacturers)) {
                    $discountDb->UpdateDiscount(array('id'=>$discountsId[$man['ManufacturerId']],"UserId"=>$UserId,"Discount"=>$this->data['man'][$man['ManufacturerId']],"ManufacturerId"=>$man['ManufacturerId']));
                    $thisclearmanufacturers[]=$man['ManufacturerId'];
                }else {
                    $discountDb->InsertDiscount(array("UserId"=>$UserId,"Discount"=>$this->data['man'][$man['ManufacturerId']],"ManufacturerId"=>$man['ManufacturerId']));
                }
            }
            else {

            }
        }
        foreach($thismanufacturers as $man) {
            if(!in_array($man,$thisclearmanufacturers)) {
                $discountDb->clearDiscount($discountsId[$man]);
            }
        }
        //вот прям до этого момента

        if($this->data['pass'] != "" && $this->data['pass']==$this->data['passRetry']) {
            $UserRow['Pass']=md5($this->data['pass']);
        }

        $UsersDb = new DAL_UsersDb();
        $UsersDb->UpdateUser($UserRow);
        unset($UserDb);

        Header("Location: ".$this->Url);

    }

    function handlerBtnCancel() {
        RedirectToModuleEdit($this->moduleId);

        die();
    }

    private function UploadCSV() {

        $ReportsDb = new DAL_ReportsDb;

        //$res = $ReportsDb->GetAllReportsByUser(Request("UserId",0),$this->moduleId)

        if(!empty($this->data["step"]) and $this->data["step"] == 2) {

            //получаем файл
            $content = Utility_FileUtility::GetUploadedFile($_FILES["userfile"]["tmp_name"]);

            $parser = new BLL_CatalogParser();

            //начинаем процесс парсинга
            $parser->StartParsing($_FILES["userfile"]["tmp_name"],$_POST['priceType'],@$_GET['UserId'],$_POST['currency']);
        }
        $this->template = "uplaodForm.tpl";
    }
}

?>