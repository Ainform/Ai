<?php
/**
 * Файл для логина администратора
 */
// подключаем настройки
include_once('../config.php');

$loginMessage = null;

session_start();

define('MaxLogin', 10);	 // максимальное количество попытов ввода пароля
define('KeyLogin', 'CurLoginCount'); // количество попыток ввода пароля
// если было нажатие на кнопку Войти
if (isset($_POST['Login_submit'])) {
	if (isset($_POST['Login']) && isset($_POST['Password']) && strlen($_POST['Login']) < 20 && strlen($_POST['Password']) < 20) {

		$login = trim($_POST['Login']);
		$password = trim($_POST['Password']);

		$authenticate = false;

		if (isset($_POST['Remember']) && $_POST['Remember'])
			$persistent = true;
		else
			$persistent = false;

		// проверяем количество попыток ввода пароля - если превылили - переход на сайт
		if (!isset($_SESSION[KeyLogin])) {
			$_SESSION[KeyLogin] = 0;
		} else if ($_SESSION[KeyLogin] >= MaxLogin) {
			Redirected(SiteUrl);
		}


		// проверяем не является ли пользователь администратором

		if ($login == AdminLogin && $password == AdminPass)
			$authenticate = true;

		if (!$authenticate) {
			$_SESSION[KeyLogin]++;

			// записываем сообщение об ошибке
			Helpers_LogHelper::AddLogEntry('<STRONG>Попытка несанкционированного доступа в раздел администрирования <br /> Логин "' . $login . '"<br /> Пароль "' . $password . '"<br /> IP "' . Utility_NetworkUtility::GetUserIp() . '"</STRONG>');

			$loginMessage = 'Неверный логин или пароль!';
		}
		if ($authenticate) {
			$identity = new BLL_AdminAuthentication();
			$identity->RedirectFromLoginPage($login, $password, $persistent, urldecode(Request('ReturnUrl')));
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <title>AI</title>

        <!--                       CSS                       -->

        <!-- Reset Stylesheet -->
        <link rel="stylesheet" href="/css/admin/reset.css" type="text/css" media="screen" />

        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="/css/admin/style.css" type="text/css" media="screen" />

        <!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
        <link rel="stylesheet" href="/css/admin/invalid.css" type="text/css" media="screen" />

    </head>

    <body id="login">

        <div id="login-wrapper" class="png_bg">
            <div id="login-top">

                <h1>Simpla Admin</h1>
                <!-- Logo (221px width) -->
                <img id="logo" src="/img/admin/New/logo (1).png" alt="Ainform logo" />
            </div> <!-- End #logn-top -->

            <div id="login-content">

                <form action="" method="post">

<? if (isset($loginMessage)): ?> <div class="notification information png_bg">
	                        <div>
	<?= $loginMessage ?>
	                        </div>
	                    </div>
					<? else: ?>
	                    <div class="notification information png_bg">
	                        <div>
	                            Добро пожаловать!
	                        </div>
	                    </div>
<? endif; ?>

                    <p>
                        <label>Логин</label>
                        <input class="text-input" type="text" name="Login"/>
                    </p>
                    <div class="clear"></div>
                    <p>
                        <label>Пароль</label>
                        <input class="text-input" type="password" name="Password"/>
                    </p>
                    <div class="clear"></div>
                    <p id="remember-password">
                        <input type="checkbox" name="Remember"/>Запомнить меня
                    </p>
                    <div class="clear"></div>
                    <p>
                        <input class="button" type="submit" value="Войти" name="Login_submit"/>
                    </p>

                </form>
            </div> <!-- End #login-content -->

        </div> <!-- End #login-wrapper -->

	</body>

</html>

