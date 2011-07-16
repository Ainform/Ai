<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_OneImageUploader($params, &$smarty)
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

        if (!isset($title))
		$title = "Изображение";

	$imagesDb = new DAL_ImagesDb();
		$images = $imagesDb->GetFromFolder($folder);
	unset($imagesDb);

        $html='';

	$html .= '<table id="'.$id.'_table" class="admin_table">';
        $html .= '<thead><tr><th align="center" colspan="5">'.$title.'</th></tr></thead><tbody id="'.$id.'_tbody">';

	// выводим картинки
	if (isset($images))
	{
		foreach ($images as $image)
		{
			$imgPath = DAL_ImagesDb::GetImagePath($image);

			// не вводи удаленные файлы
			$physFileName = Helpers_PathHelper::GetFullPath('upload').$image["Folder"]."/".$image["FileName"];

			if (!file_exists($physFileName))
				continue;

			$fileSize = (int)($image['FileSize'] / 1024);

			$html .= '<tr id="upload_img'.$image['ImageId'].'">';

			// кнопка удаление
			$html .= '<td width="16"><input type="image" name="handlerBtnDel:'.$image['ImageId'].'" src="/img/admin/close_16.png" alt="Удаление изображение" title="Удаление изображение" height="16" width="16" onclick="delUplImg(\''.$image['ImageId'].'\'); return false;" /></td>';

			$height = isset($thumbheight) ? "&height=".$thumbheight : "";

			// картинка
			$html .= '<td width="'.$thumbwidth.'"><img src="'.$imgPath.'&width='.$thumbwidth.$height.'&crop=1" width="'.$thumbwidth.'" alt="'.$image['Title'].'" title="'.$image['Title'].'" /></td>';

			// описание картинки
			$html .= '<td>('.$image['Width'].'x'.$image['Height'].' - '.$fileSize.' кб)</td>';

			$html .= '</tr>';
		}
	}

	$html .= '</tbody></table>';
	$html .= '<table>';
	$html .= '<tr>';
	$html .= '<td><input type="file" width="100%" id="'.$id.'_file" name="'.$id.'_file" size="60" />
            <input type="hidden" name="'.$id.'_folder" value="'.$folder.'">
                 <input type="hidden" name="'.$id.'_one" value="1">
                <input type="hidden" name="'.$id.'_tw" value="'.$thumbwidth.'">';

	if (isset($thumbheight))
		$html .= '<input type="hidden" name="'.$id.'_th" value="'.$thumbheight.'">';

	$html .= '</td></tr>';

	$html .= '<tr>
               <td align="left"><input type="button" id="'.$id.'_btn" name="btnUploadImgUp" value="Загрузить на сайт" class="button" title="Добавить картинку" onclick="uploadFile(this, \''.$id.'\', 1);" />
                <img src="/img/admin/load.gif" alt="Загрузка..." style="display: none" id="'.$id.'_progress" />
                    <input type="submit" name="'.$id.'_submit" id="'.$id.'_submit" style="display: none" />
                        </td></tr>';

	$html .= '</table>';
	$html .= '<iframe name="'.$id.'_upload" id="'.$id.'_upload" src="/blank.html" style="border: 0; height: 0; width: 0; padding: 0; position: absolute;"></iframe>';
	return $html;
}
?>