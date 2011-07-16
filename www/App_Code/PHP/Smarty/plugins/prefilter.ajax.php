<?php
function smarty_prefilter_ajax($source, &$smarty)
{
	global $tmp_dir;
	$tmp_dir = $smarty->template_dir."ajax/";
	
	$module = @$smarty->get_template_vars("Module");
	
	// если вызов идёт не из шаблона модуля, то выходим
	if (!isset($module))
		return $source;
	
	$source = preg_replace_callback("#\{\{ajax_update\}\}(.*?)\{\{/ajax_update\}\}#ms", "ajax_block", $source);
	
	return $source;
}

/**
	Функция производит сохранение AJAX блока и дописание обработчиков
*/
function ajax_block($source)
{
	global $tmp_dir;
		
	if (!isset($source[1]))
		return "";
	
	if (!file_exists($tmp_dir))
		mkdir($tmp_dir, 0777);
		
	$id = sha1($source[1]);
	$tmp_file = $tmp_dir.$id.'.ajax';
	
	file_put_contents($tmp_file, $source[1]);
	
	$script = '<script language="JavaScript" type="text/javascript">var ajax'.$id.' = new AjaxPanel("'.$id.'", "{{$Module->moduleId}}")</script>';
	
	return '<div id="ajax'.$id.'">'.$source[1].'</div>'.$script;
}
?>
