<?
	 /**
	 * Модуль отзывов
	 */

	 class ReviewModule extends BMC_BaseModule {

		  /**
		  * Номер текущей страницы
		  *
		  * @var int
		  */
		  public $curPage;

		  /**
		  * Количество новостей на страницу
		  */
		  const RecordsOnPage = 10;

		  function DataBind() {

				$this->curPage = Request('pageNum', 0);
				$this->BindReviewList();
				

				$this->data['session_name']= session_name();
				$this->data['session_id'] = session_id();
		  }

		  function OnModuleAdd() {
		  }

		  function OnModuleDelete() {

		  }

		  function __construct($moduleId) {
				parent::__construct($moduleId);
		  }

		  public function BindReviewList() {

				//получаем мыло

				$ReviewDb = new DAL_ReviewDb();

				$newrows= array();
				$rows = $ReviewDb->GetReviewPage($this->moduleId, $this->curPage, self::RecordsOnPage);
				foreach ($rows as $row){
					 if($row['Show']==1) {
						  $newrows[]=$row;
					 }
				}

				if (0 == count($rows))
					 return;

				//Пейджинг
				$allReview = $ReviewDb->GetCountReview($this->moduleId); // количество всех новостей
				unset($ReviewDb);

				$p = ceil($allReview/self::RecordsOnPage); // количество страниц
				$pager = "<div class='pager'>";
				if($p>1) {// если больше одной страницы
					 for($i = 0;$i < $p;$i++) {
						  if($this->curPage == $i) {// выделение страниц
								$pager .= "<span>".($i+1)."</span>&nbsp;";
						  }else {
								$pager .= "<a href='?pageNum=".$i."'>".($i+1)."</a>&nbsp;";
						  }
					 }

				}
				$this->data["Pager"] = $pager."</div>";
				/*Пейджинг*/
				
				foreach($newrows as &$row){
				
				$row['Text']=nl2br($row['Text']);				
				}

				$this->data['ReviewList'] = $newrows;
		  }

		  /**
		  * Отправка сообщения от пользователя
		  *
		  */
		  function handlerSendMessage	() {

				if (!IsValid())
					 return;

				if(count($_POST)>0) {
					 if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['keystring']) {
					 }else {
						  $this->data["request"] = "Вы неверно ввели текст изображённый на картинке!";
						  return;
					 }
				}
				unset($_SESSION['captcha_keystring']);

				/*try
				{*/
				$ReviewRow = Array();
				$ReviewRow['ModuleId'] = $this->moduleId;
				$ReviewRow['FIO'] = $this->data['txtFIO'];
				//$ReviewRow['Email'] = $this->data['txtEmail'];
				$ReviewRow['Text'] = strip_tags($this->data['txtMessage']);
				$ReviewRow['Fone'] = $this->data['txtFone'];
				$ReviewRow['IP'] = $_SERVER['REMOTE_ADDR'];
				$ReviewRow['Date'] = time();

				$ReviewDb = new DAL_ReviewDb();
				$ReviewId = $ReviewDb->AddReview($ReviewRow);
				unset($ReviewDb);

				$mail = new PHP_Mailer();

				//получаем мыло
				$configDb = new DAL_ConfigDb();
				$where = array("key" => "Reviewemail");
				$Reviewemail = $configDb->configSelect($where);

				$mail->AddAddress($Reviewemail['value']);
				$mail->Subject = "Отзыв с сайта ".AppName;
				$mail->Body = HtmlEncode($this->data["txtMessage"]);
				$mail->ContentType = "text/html";
				$mail->CharSet = "UTF-8";
				//$mail->From = $this->data["txtEmail"];//SiteUrl;
				$mail->FromName = $this->data["txtFIO"];

				//TODO настроить SMTP в php.ini и протестить
				//$mail->Send();

                                mail($Reviewemail['value'], "Отзыв с сайта ".AppName, $this->data["txtMessage"]);
				//mail(RequestMail, 'request', HtmlEncode($this->data["txtMessage"]), "From:".$this->data["txtEmail"]."<".$this->data["txtFIO"].">", "Content-type: text/html; charset=UTF=8");
				$this->data["request"] = "Ваше сообщение отправлено, спасибо!";
				/*}
				catch (Exception $e)
				{
				$this->data["request"] = "Ошибка отправки сообщения";
				Helpers_LogHelper::AddLogEntry($e->getMessage());
				}*/
		  }
	 }
?>