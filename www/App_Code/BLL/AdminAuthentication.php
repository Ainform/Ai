<?php

/**
 * BLL_AdminAuthentication class
 * Класс отвечающий за идентификацию администратора
 *
 * @author Frame
 * @version AdminAuthentication.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class BLL_AdminAuthentication extends BMC_Authentication
{
	/**
	 * Конструктор, задает параметры
	 */
	function __construct()
	{
		parent::__construct();

		$this->cryptPassword = '1';
		$this->cookieLogin = 'UidAdminKey';
		$this->cookiePassword = 'MacAdminKey';
	}

	/**
	 * Проводит аутентификацию администратора
	 */
	public function Authenticate()
	{
            //wtf($_COOKIE);
		// не нашли ключи в cookie
		if (!isset($_COOKIE[$this->cookiePassword]) || !isset($_COOKIE[$this->cookieLogin]))
			return;

		// получаем хеши пароля и логина
		$login = $this->Decrypt($_COOKIE[$this->cookieLogin]);
		$password = $_COOKIE[$this->cookiePassword];

		// проверяем на главного администратора
		if (AdminLogin == $login && md5(AdminPass) == $password)
		{
			$this->userInfo = array('AdminId' => '-1', 'Name' => AdminName, 'Email' => AdminEmail);
			$this->isAuthenticated = true;

			return;
		}
	}
}
?>