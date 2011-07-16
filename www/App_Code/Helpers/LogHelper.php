<?php

/**
 * Helpers_LogHelper class
 * Класс для работы с ошибками
 *
 * @author Frame
 * @version LogHelper.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class Helpers_LogHelper
{
	/**
	 * Максимальный размер файла ошибки в байтах
	 *
	 */
	private static $MaxFileSize = 512000;

	/**
	 * Имя файла журнала ошибок
	 *
	 */
	private static $LogFileName = 'errors.log';

	/**
	 * E-mail, куда отправляются сообщения об ошибках
	 *
	 */
	private static $ErrorEmail = ErrorLoggerMail;

	/**
	 * Записывает исключение в файл
	 *
	 * @param Exception $ex исключение
	 * @param bool $emailSend разрешение на отправку e-mail с ошибкой
	 */
	public static function LogException(Exception $ex, $emailSend = false)
	{
		// форматируем информацию об исключение
		$message = self::FormatException($ex, $msg);

		if (AppDebug)
			echo nl2br($msg);

		// записываем в лог
		self::AddLogEntry($message, $emailSend);
	}

	/**
	 * Форматирует информцию об исключении в текстовую строку
	 *
	 * @param Exception $ex исключение
	 * @return string $error информация об ошибке
	 */
	private static function FormatException($ex, &$msg)
	{
		$error = "<error>";
		$error .= "<code>".$ex->getCode()."</code>";
		$error .= "<date time='".time()."'>".date("m.d.y - H:i:s")."</date>";
		$error .= "<file>".$ex->getFile()."</file>";
		$error .= "<line>".$ex->getLine()."</line>";
		$error .= "<message>".HtmlEncode($ex->getMessage())."</message>";
		$error .= "<stack>".HtmlEncode(self::GetBackTrace($ex->getTrace()))."</stack>";
		$error .= "<url>".$_SERVER['REQUEST_URI']."</url>";
		$error .= "<ip>".Utility_NetworkUtility::GetUserIp()."</ip>";
		$error .= "</error>";

		$msg = self::FormatHtmlException($ex);

		return $error;
	}

	private static function FormatHtmlException($ex)
	{
		$error = "<p><b>Дата</b>: ".date("m.d.y - H:i:s")."<br />";
		$error .= "<b>Ошибка</b> №".$ex->getCode()."<br />";
		$error .= "Источник ошибки: файл ".$ex->getFile().", строка ". $ex->getLine()."<br />";
		$error .= "Сообщение об ошибке: ".HtmlEncode($ex->getMessage())."<br /><br />";
		$error .= "<b>Стек функций</b>:<br />".self::GetBackTrace($ex->getTrace())."<br />";
		$error .= "URL: ".$_SERVER['REQUEST_URI']."<br />";
		$error .= "IP посетителя: ".Utility_NetworkUtility::GetUserIp()."</p>";

		return $error;
	}

	/**
	 * Возращает отформатированную строку со стеком выполненных функций
	 *
	 * @param array $stack массив с выполненными функциями
	 * @return string
	 */
	private static function GetBackTrace($stack)
	{
		$sb = null;

		$count = sizeof($stack);

		for ($i = 0; $i < $count; $i++)
		{
			$sb .= isset($stack[$i]['class']) ? $stack[$i]['class'].'::' : '';

			if ('uncknown' != $stack[$i]['function'])
			{
				// выводим имя функции
				$sb .= $stack[$i]['function'].'(';

				// отображаем типы аргументов
				if (isset($stack[$i]['args']))
					for ($k = 0; $k < sizeof($stack[$i]['args']); ++$k)
					{
						$sb .= gettype($stack[$i]['args'][$k]);

						$sb .= $k != (sizeof($stack[$i]['args']) - 1) ? ', ' : '';
					}

				$sb .=') ';
			}

			// имя файла и номер строки, где выполняется функция
			$sb .= isset($stack[$i]['file']) ? ' файл '.$stack[$i]['file'].' :' : '';
			$sb .= isset($stack[$i]['line']) ? ' строка '.$stack[$i]['line'] : '';
			$sb .= "\r\n";
		}

		return $sb;
	}

	/**
	 * Добавляет сообщение в журнал
	 *
	 * @param string $message сообщение
	 * @param bool $emailSend разрешение на отправку письма с ошибкой
	 * @return void
	 */
	public static function AddLogEntry($message, $emailSend = true)
	{
		$errorFilePath = self::ErrorFilePath();

		if (file_exists($errorFilePath))
			if (filesize($errorFilePath) > self::$MaxFileSize)
				self::ClearErrorFile();

		// сохраняем событие ошибки в системном журнале
		error_log($message."\r\n", 3, $errorFilePath);

		/*if ($emailSend)
			mail(self::$ErrorEmail, 'Сообщение с сайта', $message);*/
	}

	/**
	 * Возвращает текст файла с ошибками
	 *
	 * @return string
	 */
	public static function GetErrors()
	{
		$errorFilePath = Helpers_PathHelper::GetFullPath('log')."errors.log";

		if (!file_exists($errorFilePath))
			return;

		$result = file_get_contents($errorFilePath);

		return $result;
	}

	/**
	 * Очищает файл с ошибками
	 */
	public static function ClearErrorFile()
	{
		Utility_FileUtility::DeleteFile(self::ErrorFilePath());
	}

	/**
	 * Возвращает путь к файлу с ошибками
	 *
	 * @return string
	 */
	private static function ErrorFilePath()
	{
		return Helpers_PathHelper::GetFullPath('log').self::$LogFileName;
	}
}
?>