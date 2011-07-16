<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_TextBox($params, &$smarty)
{
	$data = @$smarty->get_template_vars("Data");
	
	// ���� ����� �� �� ������ ��� ����������� ������, �� ���������� ������
	if (!isset($data))
	{
		$smarty->trigger_error("[TextBox] �� ������� ������ ������", E_USER_WARNING);
		return "";
	}
	
	// ������������� ���������� ���������� � ������ ���������
	foreach ($params as $_key=>$_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key))
		{
			$smarty->trigger_error("[TextBox] ������������ ��� ������������� �������� $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}
	
	// ���� ��� �������� �� �������, �� ������� ������
	if (!isset($id))
	{
		$smarty->trigger_error("[TextBox] �� ������ �������� name", E_USER_WARNING);
		return "";
	}
	
	$html = "";
	
	$html .= '<input name="'.$id.'" id="'.$id.'"';

	// ��������������� �������� �� POST �������
	if (isset($data[$id]))
		$value = $data[$id];
	
	// css ����� ��������
	if (isset($class))
		$html .= ' class="'.$class.'"';

	// ������������ �����
	if (isset($maxlength))
		$html .= ' maxlength="'.$maxlength.'"';

	// ������������ �����
	if (isset($type))
		$html .= ' type="'.$type.'"';
	else
		$html .= ' type="text"';
	


	// Event's
	if (isset($onblur))
		$html .= ' onblur="'.$onblur.'"';

	if (isset($onkey))
		$html .= ' onkeypress="'.$onkey.'" onkeydown="'.$onkey.'" onkeyup="'.$onkey.'"';

	if (isset($onkeypress))
		$html .= ' onkeypress="'.$onkeypress.'"';

	if (isset($onkeydown))
		$html .= ' onkeydown="'.$onkeydown.'"';

	if (isset($onkeyup))
		$html .= ' onkeyup="'.$onkeyup.'"';

		// ����� �� ���������� ������� � html ��������?
	if (isset($htmlencode) && $htmlencode == false)
		$value = HtmlEncode($value);
		
	// ��������� ��������
	if (isset($value) && !empty($value))
		$html .= ' value="'.$value.'"';
		
	// ����������� ���
	$html .= ' />';
	
	return $html;
}
?>