<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_Validator($params, &$smarty)
{
	$data = @$smarty->get_template_vars("Data");
	if (!isset($data))
	{
		$smarty->trigger_error("[Validator] �� ������� ������ ������.", E_USER_WARNING);
		return "";
	}
	
	$rules = $params['rule'];
	$objId = $params['for'];
	$id = $objId.'validator';
	 //чтобы выдавать свои сообщения, делаем вот такую вот штуку
	 $message=$objId.'message';
	$validated = true;

	if (isset($data[$id]) && $data[$id] == 'false')
		$validated = false;

	 if (isset($data[$message]) && $data[$message] != '')
		$params['message'] = $data[$message];
		
	return '<div style="display: '.($validated ? "none" : "block").'" id="'.$id.'" class="validateError">'.$params['message'].'</div>
	<input type="hidden" name="validator:'.$objId.'" value="'.$rules.'" />
	<script language="JavaScript" type="text/javascript">initValidator("'.$objId.'", "'.$rules.'");</script>';
}
?>