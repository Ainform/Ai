<?php
//define("START_TIME", microtime(true));
//echo "Память на начало: ".memory_get_usage();
//echo "<br>Пиковая память на начало: ".memory_get_peak_usage();
//ob_start("ob_gzhandler");

// подключаем настройки
include_once('config.php');

try
{
	// подключаем менеджер БД
	$dbManager = new DAL_DbManager(DbHost, DbName, DbUserName, DbUserPass);

	$appEngine = new BLL_AppEngine();

	// загружаем данные страницы
	$appEngine->LoadPage();

	// выводим страницу
	$appEngine->Render();

	// закрываем соединение с БД
	$dbManager->CloseConnection();
}
catch (Exception $ex)
{
	echo '<b>Ошибка создания страницы</b>';

	Helpers_LogHelper::LogException($ex);
}

//ob_flush();
//echo "Память после выполнения: ".memory_get_usage().";";
//echo "<br>Пиковая память после выполнения: ".memory_get_peak_usage().";";
//echo "<br>Время генерации: ".sprintf("%.5f", microtime(true) - START_TIME);

?>
