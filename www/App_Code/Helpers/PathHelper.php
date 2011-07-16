<?php

/**
 * Helpers_PathHelper class
 * Класс для работы с путями на сайте
 * 
 * @author Frame
 * @version PathHelper.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class Helpers_PathHelper extends BMC_XMLProcessor
{
	/**
	 * Xml файл с папками приложения
	 *
	 * @var DOMDocument
	 */
	private static $xmlDocument;

	/**
	 * Хранит запрошенные пути
	 *
	 * @var array
	 */
	private static $paths = array();
	
	/**
	 * Хранит запрошенные адреса
	 *
	 * @var array
	 */
	private static $urls = array();
	
	/**
	 * Возвращает полный путь к папке
	 *
	 * @param string $searchId идентификатор папки
	 * @param bool $endSlash признак установки последнего слеша
	 * @return string
	 */
	public static function GetFullPath($searchId, $endSlash = true)
	{
		// если есть в кеше
		if (isset(self::$paths[$searchId]))
			return self::$paths[$searchId].($endSlash ? '/' : '');

		self::LoadData();

		// ищем папку
		$searchedNode = self::FindNodeById(self::$xmlDocument->documentElement, $searchId);

		// сохраняем в кеше
		self::$paths[$searchId] = self::BindFullPath($searchedNode);

		// возвращаем путь к папке
		return self::$paths[$searchId].($endSlash ? '/' : '');
	}
	
	/**
	 * Возвращает имя папки
	 *
	 * @param string $searchId идентификатор папки
	 * @return string
	 */
	public static function GetFolderName($searchId)
	{
		self::LoadData();

		// ищем папку
		$searchedNode = self::FindNodeById(self::$xmlDocument->documentElement, $searchId);

		// если нашли, то возвращаем ее имя
		if (null != $searchedNode && XML_ELEMENT_NODE == $searchedNode->nodeType)
			return $searchedNode->getAttribute('Name');
		
		return null;
	}
	
	/**
	 * Создает полный путь к найденной папке
	 *
	 * @param DOMElement $node узел с необходимой папкой
	 * @return string
	 */
	private static function BindFullPath($node)
	{
		if (null != $node && XML_ELEMENT_NODE == $node->nodeType)
		{
			if (null != $node->parentNode && XML_ELEMENT_NODE == $node->parentNode->nodeType)
				return self::BindFullPath($node->parentNode).'/'.$node->getAttribute('Name');
			else
				return $_SERVER['DOCUMENT_ROOT'];
		}
		
		return null;
	}
	
	/**
	 * Возвращает адрес к папке
	 *
	 * @param string $searchId идентификатор папки
	 * @param bool $endSlash признак установки последнего слеша
	 * @return string
	 */
	public static function GetFullUrl($searchId, $endSlash = true)
	{
		// если есть в кеше
		if (isset(self::$urls[$searchId]))
			return self::$urls[$searchId].($endSlash ? '/' : '');

		self::LoadData();

		// ищем папку
		$searchedNode = self::FindNodeById(self::$xmlDocument->documentElement, $searchId);
	
		// сохраняем в кеше
		self::$urls[$searchId] = self::BindFullUrl($searchedNode);

		// возвращаем адрес к папке
		return self::$urls[$searchId].($endSlash ? '/' : '');
	}

	/**
	 * Создает полный путь к найденной папке
	 *
	 * @param DOMElement $node узел с необходимой папкой
	 * @return string
	 */
	private static function BindFullUrl($node)
	{
		if (null != $node && XML_ELEMENT_NODE == $node->nodeType)
		{
			if ($node->parentNode instanceof DOMElement)
				return self::BindFullUrl($node->parentNode).'/'.$node->getAttribute('Name');
			else
			{
				$preifx = (0 != strpos($_SERVER['REQUEST_URI'], 'www.')) ? 'wwww.' : '';
				
				return 'http://'.$preifx.$_SERVER['HTTP_HOST'];
			}
		}

		$preifx = (0 != strpos($_SERVER['REQUEST_URI'], 'www.')) ? 'wwww.' : '';
				
		return 'http://'.$preifx.$_SERVER['HTTP_HOST'];
	}

	/**
	 * Загружает xml файл с путями
	 */
	private static function LoadData()
	{
		// документ уже загружен, выходим
		if (self::$xmlDocument instanceof DOMDocument)
			return;
			
		self::$xmlDocument = new domDocument();
		self::$xmlDocument->preserveWhiteSpace = false;
		self::$xmlDocument->load($_SERVER['DOCUMENT_ROOT'].'/config.paths.xml');
	}
}
?>