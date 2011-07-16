<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_FilesUploader2($params, &$smarty)
{
	$data = @$smarty->get_template_vars("Data");

	// если вызов не из модуля или отсутствуют данные, то показываем ошибку
	if (!isset($data))
	{
		$smarty->trigger_error("[FilesUploader] не найдены данные модуля", E_USER_WARNING);
		return "";
	}

	// автоматически определяем переменные с именем параметра
	foreach ($params as $_key=>$_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key))
		{
			$smarty->trigger_error("[FilesUploader] недопустимый иди дублированный параметр $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}

	// если имя элемента не указано, то выводим ошибку
	if (!isset($id))
	{
		$smarty->trigger_error("[FilesUploader] не найден параметр id", E_USER_WARNING);
		return "";
	}

	// если имя элемента не указано, то выводим ошибку
        //Debug($folder,false);
	if (!isset($folder))
	{
		$smarty->trigger_error("[FilesUploader] не указана папка c файлами", E_USER_WARNING);
		return "";
	}

	$filesDb = new DAL_AnalyticsFilesDb();
		$files = $filesDb->GetFromFolder($folder);
	unset($filesDb);

        
	$html = '<table width="100%" cellpadding="0" cellspacing="0" class="admin_table" id="'.$id.'_table">';
	$html .= '<thead><tr><th align="center" colspan="6">Файлы</th></tr></thead><tbody id="'.$id.'_tbody">';

	// выводим файлы
	if (isset($files))
	{
		foreach ($files as $file)
		{
			//$imgPath = DAL_FilesDb::GetFilePath($file);

			$filesize = (int)($file['size'] / 1024);

			// не вводи удаленные файлы
			$physFileName = Helpers_PathHelper::GetFullPath('upload').$file["folder"]."/".$file["filename"];

			if (!file_exists($physFileName))
				continue;			
			
			$html .= '<tr id="upload_file'.$file['id'].'">';

			// кнопка удаление
			$html .= '<td width="16"><input type="image" name="handlerBtnDel:'.$file['id'].'" src="/admin/img/op_delete.gif" alt="Удаление файла" height="16" width="16" onclick="delUplFile(\''.$file['id'].'\'); return false;" /></td>';

			// картинка
			$html .= '<td><img style="vertical-align:middle" src="/img/default.jpg" alt="'.$file['title'].'" title="'.$file['title'].'" />'.$file['title'].'</td>';

                        $mandb = new DAL_ManufacturersDb();
                        $name = $mandb->GetNameById(@$file['ManufacturerId']);
                        // картинка
			$html .= '<td>Производитель:'.$name.'</td>';

			// кнопка поднятия
			$html .= '<td width="16"><input type="image" name="handlerBtnUp:'.$file['id'].'" src="/admin/img/op_up.gif" alt="Поднять файл" height="16" width="16" onclick="upUplFile(\''.$id.'\', \''.$file['id'].'\'); return false;" /></td>';

			// кнопка опускания изображения
			$html .= '<td width="16"><input type="image" name="handlerBtnDown:'.$file['id'].'" src="/admin/img/op_down.gif" alt="Опустить изображение" height="16" width="16" onclick="downUplFile(\''.$id.'\', \''.$file['id'].'\'); return false;" /></td>';

			// описание картинки
			$html .= '<td>'.$file['filename'].' ('.$filesize.' кб)</td>';

			$html .= '</tr>';
		}
	}

	$html .= '</tbody></table>';

	$html .= '<table width="100%" cellpadding="0" cellspacing="0" class="admin_table">';
	$html .= '<th colspan="2">Загрузить файл</th>';

	$html .= '<tr><td width="130" align="right">';
	$html .= 'Название файла</td><td><input type="text" width="100%" id="'.$id.'_title" name="'.$id.'_title" class="textbox" />';
	$html .= '</td></tr>';

	$html .= '<tr><td align="right">';
	$html .= 'Файл</td><td><input type="file" width="100%" id="'.$id.'_file" name="'.$id.'_file" size="60" /><input type="hidden" name="'.$id.'_folder" value="'.$folder.'">';

	$html .= '</td></tr>';
        $html .= '<tr><td align=right>Файл для производителя</td><td>';
        $html .="<select name='".$id."_ManufacturerId'>";
        $manDb = new DAL_ManufacturersDb();
        $manufacturers = $manDb->GetAll();
        foreach($manufacturers as $manufacturer){
            $html .="<option value='".$manufacturer['ManufacturerId']."'>".$manufacturer['Name']."</option>";
        }
        $html .= '</td></tr>';

	$html .= '<tr><td align="right">Загрузить</td><td align="left"><input type="button" id="'.$id.'_btn" name="btnUploadImgUp" value="Загрузить на сайт" class="button" title="Загрузить на сайт" onclick="uploadFile(this, \''.$id.'\', 1);" /><img src="/admin/img/load.gif" alt="Загрузка..." style="display: none" id="'.$id.'_progress" /><input type="submit" name="'.$id.'_submit" id="'.$id.'_submit" style="display: none" /></td></tr>';

	$html .= '</table>';
	$html .= '<iframe name="'.$id.'_upload" id="'.$id.'_upload" src="/blank.html" style="border: 0; height: 0; width: 0; padding: 0; position: absolute;"></iframe>';
	return $html;
}
?>