<?php
	/**
	 * Smarty plugin
	 * @package Smarty
	 * @subpackage plugins
	 */

	function smarty_function_DropDownList($params, &$smarty)
	{
		$data = @$smarty->get_template_vars("Data");

		// если вызов не из модуля или отсутствуют данные, то показываем ошибку
		if (!isset($data))
		{
			$smarty->trigger_error("[DropDownList] не найдены данные модуля", E_USER_WARNING);
			return "";
		}

		// автоматически определяем переменные с именем параметра
		foreach ($params as $_key=>$_value)
		{
			$_key = strtolower($_key);
			if (isset($$_key))
			{
				$smarty->trigger_error("[DropDownList] недопустимый иди дублированный параметр $_key", E_USER_WARNING);
				continue;
			}
			$$_key = $_value;
		}

		// если имя элемента не указано, то выводим ошибку
		if (!isset($id))
		{
			$smarty->trigger_error("[DropDownList] не найден параметр id", E_USER_WARNING);
			return "";
		}

		// если не заданы значения
		if (!isset($values))
		{
			$smarty->trigger_error("[DropDownList] не найден параметр values", E_USER_WARNING);
			return "";
		}

		$html = "";

		$html .= '<select name="'.$id.'" id="'.$id.'"';

		if (isset($class))
			$html .= ' class="'.$class.'"';

		$html .= '>';
		
		//Если нет текущих данных, то добавляем пустую строку
		if(!isset($data[$id])||$data[$id]==0){
			$html .= '<option value="" selected>&nbsp;</option>';
		}

		foreach ($values as $key => $value)
		{
			$selected = $data[$id] == $key ? " selected" : "";
			$html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
		}

		$html .= '</select>';

		return $html;
	}
?>