<?php

/**
 * Utility_FormUtility class
 * Класс для работы с элементами формы
 * 
 * @author Frame
 * @version FormUtility.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class Utility_FormUtility
{ 
	/**
	 * Функция для создания элемента select
	 * 
	 * @param string $selectName идентификатор элемента
	 * @param array $fields данные array('$key' => '$value')
	 * @param int $width ширина элемента
	 * @param string $options дополнительные опции
	 * @param string $defaultKey ключ по умолчанию
	 * @return string
	 */
	public static function GenerateSelect($selectName, $fields, $width = '100', $options = '', $defaultKey = -1)
	{
		$width = 'style="width: '.$width.'%"';
		
		$result = '<select id="'.$selectName.'" name="'.$selectName.'" '.$width.' '.$options.'>';
		
		if ($defaultKey != 0 || strlen($defaultKey) > 0 || $defaultKey == -1)
			$selectKey = $defaultKey;
		else
			$selectKey = GetPostValue($selectName);
		
		foreach ($fields as $key => $field)
		{
			$select = ($key == $selectKey) ? ' selected' : '';
			$result .= '<option value="'.$key.'"'.$select.'>'.$field.'</option>';
		}
		
		$result .= '</select>';

		return  $result;
	}
	
	/**
	 * Возвращает чек-бокс
	 *
	 * @param string $name уникальное имя
	 * @param string $title текст описания
	 */
	public static function CheckBox($name, $title, $checked = 0, $options = "")
	{
		$check = (1 == $checked) ? 'checked="checked"' : '';
		
		return '<label for="' . $name . '">'.$title.'</label><input type="checkbox" id="' . $name . '" name="' . $name . '" ' . $check . ' ' . $options . ' />';
	}
}
?>