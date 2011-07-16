<?php
ob_start("ob_gzhandler");
define("START_TIME", microtime(true));
//echo "Память на начало: ".memory_get_usage();
//echo "Пиковая память на начало: ".memory_get_peak_usage();
// подключаем настройки
include_once('../config.php');

try
{
	// создаем экземпляр класса аутентификации
	$identity = new BLL_AdminAuthentication();

	// проводим аутентификацию
	$identity->Authenticate();

        //Debug($identity);

	// проводим авторизацию пользователя
	if (!$identity->IsAuthenticated())
	{
		// получаем адрес текущей страницы, для перехода на нее в случае успешной аутентификации
		$returnUrl = urlencode($_SERVER['REQUEST_URI']);

		// адрес страницы для логина
		$loginUrl = Helpers_PathHelper::GetFullUrl('admin').'login.php?ReturnUrl='.$returnUrl;

		Redirected($loginUrl);
	}

	// подключаем менеджер БД
	$dbManager = new DAL_DbManager(DbHost, DbName, DbUserName, DbUserPass);

	$appEngine = new BLL_AppEngine(true);

	$appEngine->LoadPage();

	$appEngine->Render();

	// закрываем соединение с БД
	$dbManager->CloseConnection();
}
catch (Exception $ex)
{
	echo '<b>Ошибка создания страницы</b>';

	Helpers_LogHelper::LogException($ex);
}
ob_flush();
//echo "Память после выполнения: ".memory_get_usage().";";
//echo "Пиковая память после выполнения: ".memory_get_peak_usage().";";
//echo sprintf("%.5f", microtime(true) - START_TIME);
?>
