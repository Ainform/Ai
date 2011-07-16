<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_FCKEditor($params, &$smarty)
{
	$data = @$smarty->get_template_vars("Data");
	
	// ���� ����� �� �� ������ ��� ����������� ������, �� ���������� ������
	if (!isset($data))
	{
		$smarty->trigger_error("[FCKEditor] �� ������� ������ ������", E_USER_WARNING);
		return "";
	}
	
	// ������������� ���������� ���������� � ������ ���������
	foreach ($params as $_key=>$_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key))
		{
			$smarty->trigger_error("[FCKEditor] ������������ ��� ������������� �������� $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}
	
	// ���� ��� �������� �� �������, �� ������� ������
	if (!isset($id))
	{
		$smarty->trigger_error("[FCKEditor] �� ������ �������� id", E_USER_WARNING);
		return "";
	}
	
	$editor = new PHP_FCK_Editor($id);
	
	if (isset($height))
		$editor->Height = $height;
		
	if (isset($simple))
	{
		$editor->ToolbarSet = "Basic";
	}
	
	if (isset($value))
		$editor->Value	= $value;

	if (isset($data[$id]))
		$editor->Value	= $data[$id];
	
	return $editor->CreateHTML();
}
?>