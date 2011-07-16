<?php

/**
 * App_Info class
 * Класс содержит информацию о приложении
 * 
 * @author Frame
 * @version 0.0.1
 * @copyright (c) by VisualDesign
 */

class App_Info
{
	/**
	 * Возвращает путь к папке с приложением
	 *
	 * @param bool $ensSlash установить последний слеш
	 * @return string
	 */
	public static function Folder($endSlash = true)
	{
		// разделитель пути
		$dirSplitter = PHP_IO::DirSplitter;

		$endPath = ($endSlash) ? $dirSplitter : '';

		// получаем путь
		$path = $_SERVER['DOCUMENT_ROOT'];

		// проверяем правильность слешей
		if (strpos($path, $dirSplitter) === false)
		{
			$convertSplitter = 	($dirSplitter == PHP_IO::WinSplitter) ? PHP_IO::UnixSplitter : PHP_IO::WinSplitter;
			
			$path = str_replace($convertSplitter, $dirSplitter, $path);
		}

		// проверяем и убираем последний слеш
		if (strpos($path, $dirSplitter, strlen($path)))
			$path = substr($path, 0, strlen($path) - 1);

		return $path.$endPath;
	}


	/**
	 * Возвращает адрес приложения
	 *
	 * @param bool $ensSlash установить последний слеш
	 * @return string
	 */
	public static function Url($endSlash = true)
	{
		$endPath = ($endSlash) ? PHP_Url::AddressSplitter : '';

		return 'http://'.$_SERVER['HTTP_HOST'].$endPath;
	}
	
	/**
	 * Возвращает адрес запрошенной страницы (без QUERY STRING)
	 *
	 * @return string
	 */
	public static function RequestedPage($stripQuery = true)
	{
		$queryPos = false;
		
		if ($stripQuery)
			$queryPos = strpos($_SERVER['REQUEST_URI'], "?");
		
		if ($queryPos === false)
			$reqPage = $_SERVER['REQUEST_URI'];
		else
			$reqPage = substr($_SERVER['REQUEST_URI'], 0, $queryPos);
			
/*		if ($stripLangId)
		{
			$reqPage = str_replace("/en/", "/", $reqPage);
			$reqPage = str_replace("/fr/", "/", $reqPage);
		}
/*		if (BLL_AppEngine::$isAdmin)
			$reqPage = str_replace("/admin", "", $reqPage);
*/			

		return $reqPage;
	}
	
	/**
		Возвращает идентификатор языка по урлу
	*/
	public static function GetLangIdFromUrl()
	{
		static $langId = null;
	
		if ($langId == null)
		{
			$langId = 1;
		
			$languagesDb = new DAL_LanguagesDb();
			$languages = $languagesDb->GetLanguages();
			$request = self::RequestedPage();
			
			foreach ($languages as $language)
			{
				if (stripos($request, "/".$language["Alias"]."/") !== false)
					$langId = $language["LanguageId"];
			}
		}
		
		return $langId;
	}


	/**
	 * Возвращает путь к папке находящейся в папке с приложением
	 *
	 * @param string $folderName имя папки
	 * @param bool $ensSlash установить последний слеш
	 */
	public static function GetFolderPath($folderName, $endSlash = true)
	{
		$endPath = ($endSlash) ? PHP_IO::DirSplitter : '';
		
		return self::Folder() . $folderName . $endPath;
	}
}