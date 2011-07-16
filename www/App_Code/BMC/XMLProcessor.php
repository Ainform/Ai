<?php

/**
 * BMC_XMLProcessor class
 * Базовый класс для работы с XML
 * 
 * @author Frame
 * @version XMLProcessor.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

abstract class BMC_XMLProcessor
{
	/**
	 * Ищет узел по идентификатору
	 *
	 * @param DOMElement $parentNode корневой узел
	 * @param string $nodeId идентификатор узла
	 */
	protected static function FindNodeById(DOMElement $parentNode, $nodeId)
	{
		// перебираем все узлы алгоритмом с затравкой чтобы найти необходимый
		$stack = new PHP_Stack();

		$stack->Push($parentNode);
		
		//var_dump($stack);
		
		while (0 != $stack->Count())
		{
			$node = $stack->Pop();
			
			// проверяем аттрибут
			if ($nodeId == $node->getAttribute('Id'))
			{
				return $node;
			}
			
			if ($node->hasChildNodes())
			{
				// получаем список узлов
				$nodeList = $node->childNodes;
		
				// добавляем все дочерние элементы в список
				for ($i = 0; $i < $nodeList->length; $i++)
					$stack->Push($nodeList->item($i));
			}
		}
	
		return null;
	}
	
	/**
	 * Преобразует XML элемент в массив
	 *
	 * @param DOMElement $node
	 * @return array
	 */
	protected static function ConvertNodeToArray(DOMElement $node)
	{
		$result = array();

		for ($i = 0; $i < $node->attributes->length; $i++)
		{
			$attribut = $node->attributes->item($i);
			
			$result[$attribut->nodeName] = $attribut->nodeValue;
		}

		return $result;
	}
	
	/**
	 * Возвращает признак дочернего узла
	 *
	 * @param DOMElement $parentNode
	 * @param string $searchId
	 * @return bool
	 */
	protected static function IsChildNode(DOMElement $parentNode, $searchId)
	{
		if ($parentNode->hasChildNodes())
		{
			// признак дочернего узла
			$isChildNode = false;
			
			// получаем список узлов
			$nodeList = $parentNode->childNodes;
			
			// проходим по списку в поисках необходимого узла
			for ($i = 0; $i < $nodeList->length; $i++)
			{
				// получаем узел
				$node = $nodeList->item($i);

				if (self::IsCurNode($node->getAttribute('Id'), $searchId))
					return true;

				if ($node->hasChildNodes())
				{
					$isChildNode = self::IsChildNode($node, $searchId);
					
					if ($isChildNode)
						return true;
				}
			}
		}

		return false;
	}

	/**
	 * Возвращает признак сравнения идентфикатора текущего узла с заданным
	 *
	 * @param string $nodeId идентификатор узла
	 * @param string $searchId искомый идентификатор
	 * @return bool
	 */
	protected static function IsCurNode($nodeId, $searchId)
	{
		if ($nodeId == $searchId)
			return true;

		return false;
	}
}

?>