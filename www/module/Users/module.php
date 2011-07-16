<?php

class UsersModule extends BMC_BaseModule {
    const RecordsOnPage = 10;
    public $curPage;

    function DataBind() {

        if(isset($_GET['key'])&&$_GET['key']!='') {
            if($this->Activate($_GET['key'])) {
                $this->data["request"] = "Вы успешно активировали аккаунт! Спасибо за внимание к нашему сайту.";
            }
            else {
                $this->data["request"] = "Активация аккаунта невозможна, видимо вы не активировали его в положенный срок. Вы можете зарегистрироваться ещё раз.";
            }
            return;
        }

        if(isset($_REQUEST['list'])) {
            $this->UserList();
        }
        if(isset($_REQUEST['search_result'])) {
            $this->UserSearchResult();
        }
        if(isset($_REQUEST['search'])&&!isset($_REQUEST['search_result'])) {
            $this->UserSearch();
        }
        if(isset($_GET['newpass'])) {
            $this->NewPass();
        }
        if(isset($_GET['userId'])) {
            if(isset($_SESSION['userId']) && $_SESSION['userId']==$_GET['userId'] ) {
                $this->showUserEdit($_GET['userId']);
            }
            else {
                $this->showUser($_GET['userId']);
            }
        }

        $this->data['moduleId']=$this->moduleId;

        $this->data['Specialization'][]="Private Equity";
        $this->data['Specialization'][]="Акции";
        $this->data['Specialization'][]="Валюта";
        $this->data['Specialization'][]="Векселя";
        $this->data['Specialization'][]="Денежный рынок";
        $this->data['Specialization'][]=" Евробонды";
        $this->data['Specialization'][]="Индексный арбитраж";
        $this->data['Specialization'][]="Облигации";
        $this->data['Specialization'][]="ПИФы, ОФБу";
        $this->data['Specialization'][]="Производные инструменты";
        $this->data['Specialization'][]=" РЕПО";
        $this->data['Specialization'][]="Синдицированные кредиты";
        $this->data['Specialization'][]=" Структурированные инструменты, секьюритизация";
        $this->data['Specialization'][]="Товарные рынки";
        $this->data['Specialization'][]="Фьючерсный арбитраж";
        $this->data['Specialization'][]="Другое";

        $this->data['sphereofactivity'][]="Back-office";
        $this->data['sphereofactivity'][]="IT (Служба техн. поддержки)";
        $this->data['sphereofactivity'][]="Public/Investor Relations";
        $this->data['sphereofactivity'][]="Sales";
        $this->data['sphereofactivity'][]="TOP Management";
        $this->data['sphereofactivity'][]="Trading";
        $this->data['sphereofactivity'][]=" Аналитика";
        $this->data['sphereofactivity'][]=" Государственная Служба";
        $this->data['sphereofactivity'][]="Казначейство банка";
        $this->data['sphereofactivity'][]=" Консультирование";
        $this->data['sphereofactivity'][]=" Корпоративные финансы (в банке или ИК)";
        $this->data['sphereofactivity'][]=" Маркетинг и реклама";
        $this->data['sphereofactivity'][]=" Отдел ценных бумаг";
        $this->data['sphereofactivity'][]="Риск-менеджмент";
        $this->data['sphereofactivity'][]=" Синдикация";
        $this->data['sphereofactivity'][]=" Трейдинг";
        $this->data['sphereofactivity'][]="Управление портфелем ценных бумаг";
        $this->data['sphereofactivity'][]="Управление финансами предприятия";
        $this->data['sphereofactivity'][]="Финансовая журналистика";
        $this->data['sphereofactivity'][]=" Юридическое сопровождение";
        $this->data['sphereofactivity'][]="Другое";
        //$this->data['Countries']=array("Россия","США");

        //для каптчи
        //$this->data['session_name']= session_name();
        //$this->data['session_id'] = session_id();
    }
    function UserSearch() {
        $this->template = "search.tpl";
    }
    function UserSearchResult() {

        $query="SELECT * FROM users WHERE 1=1";

        if(isset($_POST['txtfirstname'])&&trim($_POST['txtfirstname'])!='') {
            $query.=" AND `firstname` LIKE '%".mysql_escape_string($_POST['txtfirstname'])."%'";
        }
        if(isset($_POST['txtsecondname'])&&trim($_POST['txtsecondname'])!='') {
            $query.=" AND `secondname` LIKE '%".mysql_escape_string($_POST['txtsecondname'])."%'";
        }
        if(isset($_POST['txtpatronymic'])&&trim($_POST['txtpatronymic'])!='') {
            $query.=" AND `patronymic` LIKE '%".mysql_escape_string($_POST['txtpatronymic'])."%'";
        }
        if(isset($_POST['txtdateofbirdth'])&&trim($_POST['txtdateofbirdth'])!='') {
            $query.=" AND `dateofbirdth` LIKE '%".mysql_escape_string($_POST['txtdateofbirdth'])."%'";
        }
        if(isset($_POST['txtcountry'])&&trim($_POST['txtcountry'])!='') {
            $query.=" AND `country` LIKE '%".mysql_escape_string($_POST['txtcountry'])."%'";
        }
        if(isset($_POST['txtcity'])&&trim($_POST['txtcity'])!='') {
            $query.=" AND `city` LIKE '%".mysql_escape_string($_POST['txtcity'])."%'";
        }
		if(isset($_POST['txthobby'])&&trim($_POST['txthobby'])!='') {
            $query.=" AND `city` LIKE '%".mysql_escape_string($_POST['txthobby'])."%'";
        }
        if(isset($_POST['txtOrgName'])&&trim($_POST['txtOrgName'])!='') {
            $query.=" AND `OrgName` LIKE '%".mysql_escape_string($_POST['txtOrgName'])."%'";
        }
        if(isset($_POST['txtPosition'])&&trim($_POST['txtPosition'])!='') {
            $query.=" AND `Position` LIKE '%".mysql_escape_string($_POST['txtPosition'])."%'";
        }
        if(isset($_POST['txtEmail'])&&trim($_POST['txtEmail'])!='') {
            $query.=" AND `Email` LIKE '%".mysql_escape_string($_POST['txtEmail'])."%'";
        }
        if(isset($_POST['txtPhone'])&&trim($_POST['txtPhone'])!='') {
            $query.=" AND `phone` LIKE '%".mysql_escape_string($_POST['txtPhone'])."%'";
        }
        if(isset($_POST['txtfax'])&&trim($_POST['txtfax'])!='') {
            $query.=" AND `fax` LIKE '%".mysql_escape_string($_POST['txtfax'])."%'";
        }
        if(isset($_POST['txtcellphone'])&&trim($_POST['txtcellphone'])!='') {
            $query.=" AND `cellphone` LIKE '%".mysql_escape_string($_POST['txtcellphone'])."%'";
        }

        if(isset($_POST['txtskype'])&&trim($_POST['txtskype'])!='') {
            $query.=" AND `skype` LIKE '%".mysql_escape_string($_POST['txtskype'])."%'";
        }
        if(isset($_POST['txticq'])&&trim($_POST['txticq'])!='') {
            $query.=" AND `icq` LIKE '%".mysql_escape_string($_POST['txticq'])."%'";
        }
        if(isset($_POST['txtzipcode'])&&trim($_POST['txtzipcode'])!='') {
            $query.=" AND `zipcode` LIKE '%".mysql_escape_string($_POST['txtzipcode'])."%'";
        }

        if(isset($_POST['txtAddress'])&&trim($_POST['txtAddress'])!='') {
            $query.=" AND `Address` LIKE '%".mysql_escape_string($_POST['txtAddress'])."%'";
        }
        if(isset($_POST['txtSpecialization'])&&trim($_POST['txtSpecialization'])!=''&&trim($_POST['txtSpecialization'])!='0') {
            $query.=" AND `Specialization` LIKE '%".mysql_escape_string($_POST['txtSpecialization'])."%'";
        }
        if(isset($_POST['txtsphereofactivity'])&&trim($_POST['txtsphereofactivity'])!=''&&trim($_POST['txtSpecialization'])!='0') {
            $query.=" AND `sphereofactivity` LIKE '%".mysql_escape_string($_POST['txtsphereofactivity'])."%'";
        }
        $db=new DAL_BaseDb();
        $userdb=new DAL_UsersDb();
        $result=$db->query($query);
        $Photo='';
        foreach($result as &$user) {
            $folder=$userdb->GetFolder($user['UserId']);
            $folderPath = './upload/'.$folder.'/';
            if (is_dir($folderPath)) {
                if ($dh = opendir($folderPath)) {
                    while (($file = readdir($dh)) !== false) {
                        $Photo= $folder.$file;
                    }
                    closedir($dh);
                }
            }
            $user['Photo']=$Photo;
        }
        unset($db);
        unset($userdb);
        $this->data['Users'] =$result;
        $this->template = "search_result.tpl";
    }

    function UserList() {
        $userDb=new DAL_UsersDb();
        $page=isset($_REQUEST['pageNum'])?$_REQUEST['pageNum']:0;
        $allusers=$userDb->GetAllUsers();
        $usersBirdth = array();
        foreach($allusers as $user) {
            $dated=date("d",strtotime($user['dateofbirdth']));
            $datem=date("m",strtotime($user['dateofbirdth']));
            $nowd=date("d",time());
            $nowm=date("m",time());
            if($dated==$nowd && $datem==$nowm) {
                $usersBirdth[]=$user;
            }
        }
        if(count($usersBirdth)>0) {
            foreach($usersBirdth as &$user) {
                $Photo='';
                $folder=$userDb->GetFolder($user['UserId']);
                $folderPath = './upload/'.$folder.'/';
                if (is_dir($folderPath)) {
                    if ($dh = opendir($folderPath)) {
                        while (($file = readdir($dh)) !== false) {
                            $Photo= $folder.$file;
                        }
                        closedir($dh);
                    }
                }
                if($Photo!='') {
                    $user['Photo']=$Photo;
                }
                else {
                    $user['Photo']="default_user.jpg";
                }
            }
        }
        $result=$userDb->GetUsersPage(407, $page, 6);
        foreach($result as &$user) {
            $Photo='';
            $folder=$userDb->GetFolder($user['UserId']);
            $folderPath = './upload/'.$folder.'/';
            if (is_dir($folderPath)) {
                if ($dh = opendir($folderPath)) {
                    while (($file = readdir($dh)) !== false) {
                        $Photo= $folder.$file;
                    }
                    closedir($dh);
                }
            }
            if($Photo!='') {
                $user['Photo']=$Photo;
            }
            else {
                $user['Photo']="default_user.jpg";
            }
            $orgs[mb_strtolower(trim($user['OrgName']),"UTF-8")][]=$user;

        }
        unset($db);
        unset($userdb);

        /*Пейджинг*/
        $all = $userDb->GetCount(407); // количество всех
        $p = ceil($all/6); // количество страниц
        $pager = "<div class='pager'>";
        if($p>1) {// если больше одной страницы
            for($i = 0;$i < $p;$i++) {
                if($page == $i) {// выделение страниц
                    $pager .= "<span>".($i+1)."</span>&nbsp;";
                }else {
                    $url = $this->GetVirtualPath();
                    $pager .= "<a href='?list=1&pageNum=".$i."'>".($i+1)."</a>&nbsp;";

                }
            }
        }
        $this->data["Pager"] = $pager."<div>";

        //Debug($usersBirdth,false);
        //Debug($result);
        $this->data['Orgs'] =$orgs;
        $this->data['UsersBirdth'] =$usersBirdth;
        $this->data['Users'] =$result;
        $this->template = "list.tpl";
    }

    function Activate($key) {
        //TODO сделать удаление неактивированных юзеров
        $userDb = new DAL_UsersDb();
        return $userDb->CheckKey($key);
    }

    function showUser($userId) {
        $db=new DAL_UsersDb();
        $result = $db->GetUser($userId);

        $folder=$db->GetFolder($userId);
        $folderPath = './upload/'.$folder.'/';
        $Photo='';
        if (is_dir($folderPath)) {
            if ($dh = opendir($folderPath)) {
                while (($file = readdir($dh)) !== false) {
                    $Photo= $folder.$file;
                }
                closedir($dh);
            }
        }

        $this->data["Users"] = $result[0];
        if($Photo!='') {
            $this->data["Users"]["Photo"]=$Photo;
        }
        else {
            $this->data["Users"]["Photo"]="default_user.jpg";
        }
        unset($db);
        $this->template = "user.tpl";
    }

    function showUserEdit($userId) {
        $db=new DAL_UsersDb();
        $result = $db->GetUser($userId);

        $folder=$db->GetFolder($userId);
        $folderPath = './upload/'.$folder.'/';
        $Photo='';
        if (is_dir($folderPath)) {
            if ($dh = opendir($folderPath)) {
                while (($file = readdir($dh)) !== false) {
                    $Photo= $folder.$file;
                }
                closedir($dh);
            }
        }

        $this->data["Users"] = $result[0];
		if($Photo!='') {
            $this->data["Users"]["Photo"]=$Photo;
        }
        else {
            $this->data["Users"]["Photo"]="default_user.jpg";
        }
        unset($db);
        $this->template = "editmyself.tpl";
    }


    function NewPass() {

        //проверяем наличие пользователя с такми именем или емейлом
        if(isset($_POST['name'])&&isset($_POST['newpass'])&&$_POST['name']!='') {
            $userDb = new DAL_UsersDb();
            $temp=$userDb->CheckUser(array("txtName"=>trim($_POST['name'])));
            $temp2=$userDb->CheckEmail(array("txtEmail"=>trim($_POST['name'])));
            if($temp||$temp2) {//FIXME говнокод
                if($temp2) {
                    $this->data['Question']=$temp2['KeyQuestion'];
                }
                else {
                    $this->data['Question']=$temp['KeyQuestion'];
                }
                $this->template = "answer.tpl";
                return;
            }
            else {
                $this->data['NameError']="На сайте не существует пользователя с таким email или именем!";
            }
        }

        //проверяем соответствует ли ответ вопросу
        if(isset($_POST['answertext'])&&isset($_POST['answer'])&&isset($_POST['question'])&&$_POST['answertext']!=''&&$_POST['question']!='') {

            $userDb = new DAL_UsersDb();
            $temp=$userDb->CheckAnswer($_POST['question'],trim($_POST['answertext']));
            unset($userDb);

            if($temp) {
                $pass=$this->GenPassword();

                $to  = $temp['Email'];
                $subject = 'Новый пароль к авторизации на сайте '.AppName;

                $message = '
			 <html><head>
			 <title>Новый пароль к авторизации на сайте '.AppName.'</title>
			 </head><body>
				<p>Здравствуйте, '.$temp['FIO'].'!</p>
				<p>Мы сгенерировали для вас новый пароль на сайте '.SiteUrl.'.</p>
				<p>Вот он: <strong>'.$pass.'</strong></p>
			 </body></html>';

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                $headers .= 'From: '.AppName.' <'.AdminEmail.'>' . "\r\n";

                if(mail($to, $subject, $message, $headers)) {
                    $userDb = new DAL_UsersDb();
                    $userDb->ChangePass($temp['UserId'],$pass);
                    unset($userDb);
                }

                $this->data["request"] = "Ответ верный! На вашу электронную почту выслан новый пароль.";
                return;
            }
        }

        $this->template = "newpass.tpl";
    }

    /**
     * Отправка сообщения
     *
     */
    function handlerSend() {

        if (!IsValid())
            return;

        //проверка на уникальность имени и емейла
        $userDb = new DAL_UsersDb();
        /* $temp=$userDb->CheckUser($this->data);
        if($temp) {
            $this->data['txtNamevalidator'] = 'false';
            $this->data['txtNamemessage'] = 'Такое имя уже занято!';
        }*/

        $temp2=$userDb->CheckEmail($this->data);
        if($temp2) {
            $this->data['txtEmailvalidator'] = 'false';
            $this->data['txtEmailmessage'] = 'Такой email уже занят!';
        }

        $temp3 = $this->data['txtPass'] != $this->data['txtPass2'];
        if ($temp3) {
            $this->data['txtPassvalidator'] = 'false';
            $this->data['txtPassmessage'] = 'Пароли не совпадают!';
            $this->data['txtPass'] = "";
            $this->data['txtPass2'] = "";
        }

        if (@$temp||$temp2||$temp3)
            return;

        $UserRow = Array();
        $UserRow['firstname'] = $this->data['txtfirstname'];
        $UserRow['secondname'] = $this->data['txtsecondname'];
        $UserRow['patronymic'] = $this->data['txtpatronymic'];
        $UserRow['dateofbirdth'] = $this->data['txtdateofbirdth'];
        $UserRow['country'] = $this->data['txtcountry'];
        $UserRow['city'] = $this->data['txtcity'];

        $UserRow['Pass'] = md5($this->data['txtPass']);
        $UserRow['KeyQuestion'] = $this->data['txtKeyQuestion'];
        $UserRow['KeyAnswer'] = trim($this->data['txtKeyAnswer']);

        $UserRow['hobby'] = $this->data['txthobby'];
		$UserRow['OrgName'] = $this->data['txtOrgName'];
        $UserRow['Position'] = $this->data['txtPosition'];
        $UserRow['Email'] = $this->data['txtEmail'];
        $UserRow['Specialization'] = $this->data['txtSpecialization'];
        $UserRow['Address'] = $this->data['txtAddress'];
        $UserRow['zipcode'] = $this->data['txtzipcode'];

        $UserRow['Phone'] = $this->data['txtJobPhone'];
        $UserRow['fax'] = $this->data['txtfax'];
        $UserRow['cellphone'] = $this->data['txtcellphone'];
        $UserRow['skype'] = $this->data['txtskype'];
        $UserRow['icq'] = $this->data['txticq'];
        $UserRow['sphereofactivity'] = $this->data['txtsphereofactivity'];
        $UserRow['Activate'] = 0;
        //генерируем ключ для подверждения регистрации
        $UserRow['Key'] = md5(time());
        //и дату проставляем, не активированные аккаунты после определённого срока удаляем
        $UserRow['KeyDate'] = time();
        $UserRow['Date'] = time();

        $userDb = new DAL_UsersDb();
        $userId = $userDb->AddUser($UserRow);
        unset($UserDb);

        //отсылаем емейл с подтверждением
        $to  = $this->data['txtEmail'];
        $subject = 'Подтверждение регистрации на сайте '.AppName;

        $message = '
	 <html><head>
	  <title>Подтверждение регистрации на сайте '.AppName.'</title>
	 </head><body>
	 <p>Здравствуйте!

<p>В сайте по адресу '.SiteUrl.' появилась регистрационная запись,
в которой был указал ваш электронный адрес ('.$UserRow['Email'].').

<p>При заполнении регистрационной формы было указано следующее имя пользователя: '.$UserRow['firstname']." ".$UserRow['secondname'] .
                '<p>Если вы не регистрировались на сайте — просто проигнорируйте это сообщение!

<p>Если же именно вы решили зарегистрироваться сайте по адресу '.SiteUrl.',
то вам следует подтвердить свою регистрацию и тем самым активировать вашу учетную запись.
Чтобы активировать вашу учетную запись, необходимо перейти по ссылке:
<p><a href="'.$this->Url.'?key='.$UserRow['Key'].'">'.$this->Url.'?key='.$UserRow['Key'].'</a>
<p>Активация произойдет автоматически.

<p>Спасибо за то, что зарегистрировались на нашем сайте.
<p>--
<p>С уважением,
Администрация '.AppName.'.
</body></html>'
        ;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: '.AppName.' <'.AdminEmail.'>' . "\r\n";

        mail($to, $subject, $message, $headers);

        $this->data["request"] = "На вашу электронную почту выслано сообщение с информацией для подтверждения регистрации, спасибо!";
    }

    function GenPassword($p="", $l=8, $f=4) {
        $d=array('a'=>'ntrsldicmzp','b'=>'euloayribsj','c'=>'oheaktirulc',
                'd'=>'eiorasydlun','e'=>'nrdsaltevcm','f'=>'ioreafl tuyc',
                'g'=>'aeohrilunsg','h'=>'eiaotruykms','i'=>'ntscmle dorg',
                'j'=>'ueoairhjklm','k'=>'eiyonashlus','l'=>'eoiyald sfut',
                'm'=>'eaoipsuybmn','n'=>'goeditscayl','o'=>'fnrzmwt ovls',
                'p'=>'earolipuths','q'=>'uuuuaecdfok','r'=>'eoiasty dgnm',
                's'=>'eothisakpuc','t'=>'hoeiarzsuly','u'=>'trsnlpg ecim',
                'v'=>'eiaosnykrlu','w'=>'aiheonrsldw','x'=>'ptciaeu ohnq',
                'y'=>'oesitabpmwc','z'=>'eaiozlryhmt');
        $a=range('a','z');
        $l%=50;
        $f%=11;
        $p=strtolower(ereg_replace("[^a-zA-Z]","",substr($p,0,$l-1)) ) or
                $p=$a[rand(0,sizeof($a)-1)];
        while(strlen($p)<$l) {
            $ff = $f;
            while(substr_count($p,substr($p,strlen($p)-1,1).
            ($k=substr($d[substr($p,strlen($p)-1,1)],rand(0,$ff%11),1))) )
                if(++$ff>10) break;
            $p.=$k;
        }
        return $p;
    }

    function handlerBtnUserSave($UserId) {

        $UserRow = Array();
        $UserRow['UserId'] = $UserId;

        $UserRow['firstname'] = $this->data['txtfirstname'];
        $UserRow['secondname'] = $this->data['txtsecondname'];
        $UserRow['patronymic'] = $this->data['txtpatronymic'];
        $UserRow['dateofbirdth'] = $this->data['txtdateofbirdth'];
        $UserRow['country'] = $this->data['txtcountry'];
        $UserRow['city'] = $this->data['txtcity'];
		$UserRow['hobby'] = $this->data['txthobby'];

        $UserRow['KeyQuestion'] = $this->data['txtKeyQuestion'];
        $UserRow['KeyAnswer'] = trim($this->data['txtKeyAnswer']);

        $UserRow['OrgName'] = $this->data['txtOrgName'];
        $UserRow['Position'] = $this->data['txtPosition'];
        $UserRow['Email'] = $this->data['txtEmail'];
        $UserRow['Specialization'] = $this->data['txtSpecialization'];
        $UserRow['Address'] = $this->data['txtAddress'];
        $UserRow['zipcode'] = $this->data['txtzipcode'];

        $UserRow['Phone'] = $this->data['txtJobPhone'];
        $UserRow['fax'] = $this->data['txtfax'];
        $UserRow['cellphone'] = $this->data['txtcellphone'];
        $UserRow['skype'] = $this->data['txtskype'];
        $UserRow['icq'] = $this->data['txticq'];
        $UserRow['sphereofactivity'] = $this->data['txtsphereofactivity'];

        if($this->data['pass'] != "" && $this->data['pass']==$this->data['passRetry']) {
            $UserRow['Pass']=md5($this->data['pass']);
        }

        $UsersDb = new DAL_UsersDb();
        if(isset($_FILES['photo']['tmp_name']) && $_FILES['photo']['tmp_name']!='') {
            $folder=$UsersDb->GetFolder($UserId);
            $folderPath = './upload/'.$folder.'/';
            if (!file_exists($folderPath))
                mkdir($folderPath, 0777);
            $filename = $_FILES['photo']['name'];

            Utility_FileUtility::ClearFolder($folderPath);

            move_uploaded_file($_FILES['photo']['tmp_name'], $folderPath.$filename);
        }

        //print_r($_FILES);


        $UsersDb->UpdateUser($UserRow);
        unset($UserDb);

    }
}
?>