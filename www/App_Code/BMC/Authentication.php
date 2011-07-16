<?php

/**
 * BMC_AppEngine class
 * Класс отвечающий за идентификацию пользователя
 *
 * @author Frame
 * @version Authentication.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

abstract class BMC_Authentication {
    /**
     * Экземпляр класса
     *
     * @var BMC_Authentication
     */
    static $instance;

    /**
     * Имя cookie для логина
     *
     * @var string
     */
    protected $cookieLogin;

    /**
     * Имя cookie для пароля
     *
     * @var string
     */
    protected $cookiePassword;

    /**
     * Пароль для шифрования
     *
     * @var string
     */
    protected $cryptPassword;

    /**
     * Данные авторизованного пользователя
     *
     * @var array
     */
    protected $userInfo = array();

    /**
     * Признак авторизации
     *
     * @var bool
     */
    protected $isAuthenticated = false;

    /**
     * Проводит аутентификацию пользователя
     */
    abstract public function Authenticate();

    /**
     * Конструктор, сохраняет экземпляр класса
     */
    function __construct() {
        self::$instance = $this;
    }

    /**
     * Переводит авторизованного пользователя на певоночально запрошенную страницу URL
     *
     * @param string $login логин
     * @param string $password пароль
     * @param bool $createPersistentCookie создавать длительные cookie
     * @param string $returnUrl запрошенный урл, переводим по нему при удачном логине
     */
    function RedirectFromLoginPage($login, $password, $createPersistentCookie, $returnUrl) {
        if (is_null($login) || is_null($password))
            return;

        // устанвливает срок жизни cookie
        if ($createPersistentCookie)
            $time = time() + 10000000; // на очень большой срок жизни (до 10 лет)
        else
            $time = time() + 3600;	// жизнь до конца сессии

        // помещаем в cookie зашифрованные данные аунтефицированного пользователя
        setcookie($this->cookieLogin, $this->Encrypt($login), $time, "/");
        setcookie($this->cookiePassword, md5($password), $time, "/");

        // перенаправляем пользователя на первоночально запрошенную страницу URL
        Redirected($returnUrl); // $returnUrl
    }


    /**
     * Получаем пользователя
     *
     */
    static function GetUser($pass,$email,$memory=null) {

        //TODO навести порядок в AuthDb
        $userdb = new DAL_UsersDb();
        $user = $userdb->CheckUserPass($pass, $email, 1);
        //печеньки!
        if($memory) {
            //TODO имя печенек лучше передавать извне, чтоб класс гибче был
            $time = time() + 3600*24*7;//на неделю
             setcookie("Email", $email, $time, "/");
            setcookie("Password", md5($pass), $time, "/");
        }
        if($user['UserId']) {
            $_SESSION['user']=$user['firstname']." ".$user['secondname'];
            $_SESSION['userId']=$user['UserId'];
        }
        return $user['firstname']." ".$user['secondname'];
    }

    /**
     * Логаут
     *
     */
    static function LogOut() {
        session_destroy();

        setcookie("UidAdminKey", "", time()-72000,"/");
        setcookie("MacAdminKey", "", time() - 72000,"/");
        setcookie("Email", "", time() - 7200,"/");
        setcookie("Password", "", time() - 7200,"/");

        Header("Location: /admin");
    }

    /**
     * Возвращает данные пользователя
     *
     * @return array
     */
    public function UserInfo() {
        return $this->userInfo;
    }

    /**
     * Статус аутентификации
     *
     * @return bool
     */
    public function IsAuthenticated() {
        return $this->isAuthenticated;
    }

    /**
     * Шифрует строку
     *
     * @param string $string
     * @return string
     */
    protected function Encrypt($string) {
        return base64_encode($string);

        //PHP_CryptRc4::EncryptString($this->cryptPassword, $string)
    }

    /**
     * Расшифровывает строку
     *
     * @param string $string
     * @return string
     */
    protected function Decrypt($string) {
        return base64_decode($string);

        //PHP_CryptRc4::DecryptString($this->cryptPassword, $string)
    }

    /**
     * Возвращает экземпляр класса
     *
     * @return BMC_Authentication
     */
    public static function GetObject() {
        return self::$instance;
    }
}
?>
