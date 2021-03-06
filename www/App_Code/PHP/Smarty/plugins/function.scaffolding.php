<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_scaffolding($params, &$smarty)
{
	function type_transform($type = null, $value, $some = "")
	{
		switch ($type) {
			case "date":
				return date("d.m.Y h:i", $value);
				break;
			case "bool":
				return $value ? "да" : "нет";
				break;
			case "link":
				return "<a href='" . $some . "' >" . $value . "</a>";
				break;
			default:
				return $value;
				break;
		}
	}

	$data = @$smarty->get_template_vars("Data");

	// если вызов не из модуля или отсутствуют данные, то показываем ошибку
	if (!isset($data)) {
		$smarty->trigger_error("Не найдены данные модуля", E_USER_WARNING);
		return "";
	}

	// автоматически определяем переменные с именем параметра
	foreach ($params as $_key => $_value)
	{
		$_key = strtolower($_key);
		if (isset($$_key)) {
			$smarty->trigger_error("Недопустимый иди дублированный параметр $_key", E_USER_WARNING);
			continue;
		}
		$$_key = $_value;
	}

	// если имя элемента не указано, то выводим ошибку
	if (!isset($list)) {
		$smarty->trigger_error("Не найден параметр list", E_USER_WARNING);
		return "";
	}

	$html = '<table class="admin_table">';

	function sort_prepare($value)
	{
		$url_array = parse_url($_SERVER['REQUEST_URI']);
		if (!empty($url_array['query'])) {
			parse_str($url_array['query'], $all_get);
		}
		if (isset($all_get['sort']) && $all_get['sort'] == $value) {
			$sort_dir = isset($all_get['sort_dir']) && $all_get['sort_dir'] == 'DESC' ? "ASC" : "DESC";
		}
		else {
			$sort_dir = "DESC";
		}

		$temp_array = $_GET;
		$temp_array['sort'] = $value;
		$temp_array['sort_dir'] = $sort_dir;

		return $url_array['path'] . "?" . http_build_query($temp_array);
	}

	/**
	 * @param $value
	 * @return string
	 */
	function sort_pic($value)
	{
		$url_array = parse_url($_SERVER['REQUEST_URI']);
		if (!empty($url_array['query'])) {
			parse_str($url_array['query'], $all_get);
		}
		if (isset($all_get['sort']) && $all_get['sort'] == $value) {
			$sort_pic = isset($all_get['sort_dir']) ? $all_get['sort_dir'] : "DESC";
		}
		else {
			$sort_pic = "empty";
		}
		return $sort_pic;
	}

	function count_prepare($count)
	{
		//количество материалов на странице
		$url_array = parse_url($_SERVER['REQUEST_URI']);
		$temp_array = $_GET;
		unset($temp_array['page']);
		$temp_array['count'] = $count;
		if (isset($_GET['count']) && $_GET['count'] == $count) {
			$path = "<span>".$count."</span>";
		} else {
			$path = "<a href='".$url_array['path'] . "?" . http_build_query($temp_array)."'>".$count."</a>";
		}
		return $path;
	}

	if (isset($list['headers'])) {
		foreach ($list['headers'] as $item => $value) {
			if (!in_array($item, $list['exceptions'])) {
				$html .= "<col class='" . sort_pic($value) . "'/>";
			}
		}
		$html .= '<thead><tr>';
		foreach ($list['headers'] as $item => $value) {
			if (!in_array($item, $list['exceptions'])) {
				if (isset($list['titles'][$item])) {
					$html .= "<th><a class='" . sort_pic($value) . "' href='" . sort_prepare($value) . "'>" . $list['titles'][$item] . "</a></th>";
				} else {
					$html .= "<th><a class='" . sort_pic($value) . "' href='" . sort_prepare($value) . "'>" . $value . "</a></th>";
				}

			}
		}
		$html .= "<th colspan='4'>Функции</th>";
		$html .= '</tr></thead>';
	}

	// выводим
	if (isset($list['data'])) {
		$html .= '<tbody>';
		foreach ($list['data'] as $id => $row)
		{

			$html .= '<tr>';
			foreach ($row as $col => $value)
			{
				if (!in_array($col, $list['exceptions'])) {
					$html .= '<td>';
					$html .= type_transform(@$list['types'][$col], $value, @$list['links'][$id]);
					$html .= '</td>';
				}

			}
			$html .= '<td><input type="submit" name="handlerBtnDown:' . $row['id'] . '" class="btnDown" title="Опустить" value="" /></td>
					<td><input type="submit" name="handlerBtnUp:' . $row['id'] . '" class="btnUp" title="Поднять" value="" /></td>
					<td><input type="submit" name="handlerBtnEdit:' . $row['id'] . '" class="btnEdit" title="Изменить" value="" /></td>
					<td><input type="submit" name="handlerBtnDel:' . $row['id'] . '" class="btnDel" title="Удалить" value="" onclick="return confirm(\'Вы уверены?\');" /></td>';
			$html .= '</tr>';
		}
		$html .= '</tbody>';
	}

	$html .= '</table>';


	$html .= '<div class="page_count">Показывать по:' . count_prepare(5) . count_prepare(10) . count_prepare(20) . count_prepare(50) . count_prepare(100) . '</div>';
	$html .= $list['pager'];
	return $html;
}

?>
