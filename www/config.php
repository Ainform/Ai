<?php

// Общие настройки
// название сайта
define(   'AppName', 'buket');
// логин и пароль для входа в администрирование
define('AdminLogin', 'admin');
define( 'AdminPass', 'buket123');
// емейл и имя для почты уходящей с сайта
define('AdminEmail', 'info@buketufa.ru');
define( 'AdminName', 'Администратор сайта');


// Технические настройки
// SiteUrl — полный путь к сайту, оканчивающийся слешем (/), например http://site.com/
define("SiteUrl", "http://buket_debug/");
// режим отладки
define('AppDebug', true);
define('ErrorLoggerMail', 'error@ainform.com');
// данные для подключения к БД
define(    'DbHost', 'localhost');
define(    'DbName', 'buket_debug');
define('DbUserName', 'root');
define('DbUserPass', '');

// подключаем остальные настройки
require_once('functions.php');

if (function_exists("date_default_timezone_set"))
	date_default_timezone_set('Asia/Yekaterinburg');