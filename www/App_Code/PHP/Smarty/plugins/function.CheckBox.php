<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_CheckBox($params, &$smarty)
{
	$data = @$smarty->get_template_vars("Data");
	
	// если вызов не из модуля или отсутствуют данные, то показываем ошибку
	if (!isset($data))
	{
		$smarty->trigger_error("[CheckBox] не найдены данные модуля", E_USER_WARNING);
		return "";
	}
	
	// автоматически определяем переменные с именем параметра
	foreach ($params as $_key=>$_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key))
		{
			$smarty->trigger_error("[CheckBox] недопустимый иди дублированный параметр $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}
	
	// если имя элемента не указано, то выводим ошибку
	if (!isset($id))
	{
		$smarty->trigger_error("[CheckBox] не найден параметр id", E_USER_WARNING);
		return "";
	}
	
	$html = "";
	
	$html .= '<input type="checkbox" name="'.$id.'" id="'.$id.'"';

	// css класс элемента
	if (isset($class))
		$html .= ' class="'.$class.'"';

	// Event's
	if (isset($onblur))
		$html .= ' onblur="'.$onblur.'"';
		
	// Post-Back
	if ((isset($data[$id]) && !empty($data[$id])))
		$html .= ' checked="checked"';
		
	if (empty($data[$id]) && isset($checked))
		$html .= ' checked="checked"';
		
	// закрывающий тэг
	$html .= ' />';
	
	if (isset($value))
		$html .= '<label for="'.$id.'">&nbsp;'.$value.'</label>';
	
	return $html;
}
?>