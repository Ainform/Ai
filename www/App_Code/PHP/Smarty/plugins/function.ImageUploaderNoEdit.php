<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_ImageUploaderNoEdit($params, &$smarty)
{	
	$data = @$smarty->get_template_vars("Data");
	
	// если вызов не из модуля или отсутствуют данные, то показываем ошибку
	if (!isset($data))
	{
		$smarty->trigger_error("[ImageUploader] не найдены данные модуля", E_USER_WARNING);
		return "";
	}
	
	// автоматически определяем переменные с именем параметра
	foreach ($params as $_key=>$_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key))
		{
			$smarty->trigger_error("[ImageUploader] недопустимый иди дублированный параметр $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}
	
	// если имя элемента не указано, то выводим ошибку
	if (!isset($id))
	{
		$smarty->trigger_error("[ImageUploader] не найден параметр id", E_USER_WARNING);
		return "";
	}
	
	// если имя элемента не указано, то выводим ошибку
	if (!isset($folder))
	{
		$smarty->trigger_error("[ImageUploader] не указана папка картинок", E_USER_WARNING);
		return "";
	}
	
	// если не задан размер превьюшек, то используем стандартный
	if (!isset($thumbwidth))
		$thumbwidth = 100;

	$imagesDb = new DAL_ImagesDb();
		$images = $imagesDb->GetFromFolder($folder);
	unset($imagesDb);
	
	$html = '<table cellpadding="7" cellspacing="7" id="'.$id.'_table">';
	
	// выводим картинки
	if (isset($images))
	{
		//print_r($images);

		$i = 0; // для расстановки <tr></tr>
		
		foreach ($images as $image)
		{
			$i++;
			$imgPath = DAL_ImagesDb::GetImagePath($image);
			
			$fileSize = (int)($image['FileSize'] / 1024);
			
			if ($i > 5) $html .= '<tr id="upload_img'.$image['ImageId'].'">';

			$height = isset($thumbheight) ? "&height=".$thumbheight : "";
			
			// картинка
			$html .= '<td width="'.$thumbwidth.'" align="center"><a href="'.$imgPath.'" class="sectionLink" target="_blank"><img src="'.$imgPath.'&width='.$thumbwidth.$height.'" width="'.$thumbwidth.'" alt="'.$image['Title'].'" title="'.$image['Title'].'" /><br>'.$image['Title'].'</a></td>';

			if ($i > 5) $html .= '</tr>';
		}
	}
	
	$html .= '</table>';
	
	return $html;
}
?>