<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_CheckBox($params, &$smarty)
{
	$data = @$smarty->get_template_vars("Data");
	
	// ���� ����� �� �� ������ ��� ����������� ������, �� ���������� ������
	if (!isset($data))
	{
		$smarty->trigger_error("[CheckBox] �� ������� ������ ������", E_USER_WARNING);
		return "";
	}
	
	// ������������� ���������� ���������� � ������ ���������
	foreach ($params as $_key=>$_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key))
		{
			$smarty->trigger_error("[CheckBox] ������������ ��� ������������� �������� $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}
	
	// ���� ��� �������� �� �������, �� ������� ������
	if (!isset($id))
	{
		$smarty->trigger_error("[CheckBox] �� ������ �������� id", E_USER_WARNING);
		return "";
	}
	
	$html = "";
	
	$html .= '<input type="checkbox" name="'.$id.'" id="'.$id.'"';

	// css ����� ��������
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
		
	// ����������� ���
	$html .= ' />';
	
	if (isset($value))
		$html .= '<label for="'.$id.'">&nbsp;'.$value.'</label>';
	
	return $html;
}
?>