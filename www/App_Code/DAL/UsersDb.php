<?php

/**
 * DAL_UsersDb class
 * Класс для работы с пользователями в БД
 *
 * @author Informix
 * @version 1.0
 */

class DAL_UsersDb extends DAL_BaseDb {
  /**
	@var string $TableName Название таблицы
	*/
  protected $TableName = "users";

  /**
	Возвращает данные о структуре таблицы в виде название колонки -> тип колонки
		
	@return array структура таблицы
	*/
  protected function getStructure() {
	 return array(
				"UserId" => "int",
				"FIO" => "string",
				"Email" => "string",
				"firstname" => "string",
                                "secondname" => "string",
                                "patronymic" => "string",
             "country" => "string",
             "city" => "string",
             "zipcode" => "string",
             "fax" => "string",
             "cellphone" => "string",
             "skype" => "string",
             "icq" => "string",
             "sphereofactivity" => "string",
             "dateofbirdth" => "string",
				"Pass" => "string",
				"KeyQuestion" => "string",
				"KeyAnswer"=>"string",
				"OrgName" => "string",
				"Specialization" => "string",
				"Address" => "string",
				"Position" => "string",
				"Phone" => "string",
				"Interest" => "string",
				"Activate" => "int",
				"Key" => "string",
				"KeyDate" => "string",
				"Date" => "string",
	 );
  }

  /**
	Возвращает первичные ключи таблицы
		
	@return array ключи таблицы
	*/
  protected function getKeys() {
	 return array("UserId");
  }

  /**
	@return array автоинкрементные индексы таблицы
	*/
  protected function getIndexes() {
	 return array("UserId");
  }

  /**
	* Констуктор, инициализирует соединение
	*
	*/
  function __construct() {
	 parent::__construct();
  }

  /**
	* выдает всех пользователей
	*
	* @return array
	*/
  public function GetAllUsers() {
	 return $this->select();
  }

  /**
	* Выдает информацию о конкретном пользователе
	*
	* @param unknown_type $UserId
	* @return unknown
	*/
  public function GetUser($UserId = null) {
	 return $this->select(array("UserId"=>$UserId));
  }
  public function UpdateUser($row){
		return $this->update($row);
  }

  /**
	* Возвращает список
	* @param int $moduleId Индификатор модуля
	* @param int $page номер страницы для отображения
	* @param int $recordsOnPage количество записей на страницу
	*
	* @return array
	* */
  public function GetUsersPage($moduleId, $page, $recordsOnPage) {
      return $this->query("SELECT * FROM `users` WHERE 1=1 ORDER BY OrgName, firstname LIMIT ".($page*$recordsOnPage).",$recordsOnPage");
	 /*return $this->selectPage(array(),
				"OrgName, firstname",
				true,
				$page,
				$recordsOnPage);*/
  }

  /**
	* Возвращает html для пейджинга на основе данных
	* @param int $moduleId ИД Модуля
	* @param int $page номер страницы
	* @param int $recordsOnPage количество записей на страницу
	* @return string
	* */
  public function GetPager($moduleId, $page, $recordsOnPage) {
	 //Debug($moduleId);

	 $allNews = $this->GetCount($moduleId); // количество всех новостей
	 $p = ceil($allNews/$recordsOnPage); // количество страниц
	 $pager = "<div class='pager'>";
	 if($p>1) {// если больше одной страницы
		for($i = 0;$i < $p;$i++) {
		  if($page == $i) {// выделение страниц
			 $pager .= "<span>".($i+1)."</span>&nbsp;";
		  }else {
			 //$url = $this->GetVirtualPath();
			 $pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
		  }
		}

	 }
	 return $pager."</div>";
  }

  /**
	Возвращает общее количество записей для текущего модуля
	@param int $moduleId идентификатор модуля
	*/
  public function GetCount($moduleId) {
	 return $this->selectCount(array());
  }

  // удаление пользователя по его идентификатору
  function Delete($userId) {
	 parent::delete(array("UserId" => $userId));
  }
  // добавление пользователя
  function AddUser($row) {
	 $this->insert($row);
  }

  // проверка есть ли пользователь с таким именем
  function CheckUser($data) {

	 $temp=$this->selectFirst(array("Name"=>$data['txtName']));
	 if(count($temp)>0) {
		return $temp;
	 }
	 return false;
  }
  // проверка есть ли пользователь с таким емейлом
  function CheckEmail($data) {

	 $temp=$this->selectFirst(array("Email"=>trim($data['txtEmail'])));
	 if(count($temp)>0) {
		return $temp;
	 }
	 return false;
  }
  // проверка совпадает ли ответ
  function CheckAnswer($question,$answer) {

	 $temp=$this->selectFirst(array("KeyQuestion"=>$question,"KeyAnswer"=>$answer));
	 if(count($temp)>0) {
		return $temp;
	 }
	 return false;
  }

  // ставим юзеру статус партнёра
  function Partner($UserId) {

	 $temp=$this->select(array("UserId"=>$UserId));

	 if($temp[0]['Partner']==0) {
		$this->update(array("UserId" => $UserId,'Partner'=>'1'));
	 }
	 elseif($temp[0]['Partner']==1) {
		$this->update(array("UserId" => $UserId,"Partner"=>'0'));
	 }
  }
  
  // проверка на подтверждение регистрации и активация
  function CheckKey($key) {

	 $temp=$this->selectFirst(array("Key"=>$key));
	 if(count($temp)>0) {
		$this->update(array("UserId"=>$temp['UserId'],"Activate"=>1));
		return true;
	 }
	 return false;
  }

  public function CheckUserPass($pass,$email, $activate) {
	 return $this->selectFirst(array("Pass"=>md5($pass),"Email"=>$email, "Activate"=>$activate));
  }

  public function ChangePass($UserId,$pass) {
	 return $this->update(array("UserId"=>$UserId,"Pass"=>md5($pass)));
  }

   /**
   Возвращает папку для картинок
   */
  public static function GetFolder($Id = null) {
    $folder = "user/";

    if (isset($Id))
      $folder .= $Id."/";

    return $folder;
  }
}

?>