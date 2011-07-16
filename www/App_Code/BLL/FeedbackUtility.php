<?php

/**
 * FeedbackUtility class
 * Класс для форматирования сообщения от пользователя
 * 
 * @author Frame
 * @version FeedbackUtility.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class BLL_FeedbackUtility
{
	/**
	 * Форматирует данные сообщения в таблицу
	 *
	 * @param string $author ф.и.о. пользователя
	 * @param string $email e-mail
	 * @param string $phone телефон
	 * @param string $city город
	 * @param string $company название фирмы
	 * @param string $message сообщение
	 * @return string
	 */
	public static function FormatMesage($author, $email, $phone, $city, $company, $message)
	{
		$result = '<table class="text">
						<tr>
							<td width="150">Ф.И.О.</td>
							<td>'.$author.'</td>
						</tr>
						<tr>
							<td width="150">Контактный e-mail</td>
							<td>'.$email.'</td>
						</tr>
						<tr>
							<td width="150">Контактный телефон</td>
							<td>'.$phone.'</td>
						</tr>
						<tr>
							<td width="150">Город</td>
							<td>'.$city.'</td>
						</tr>
						<tr>
							<td width="150">Название фирмы</td>
							<td>'.$company.'</td>
						</tr>
						<tr>
							<td width="150">Сообщение</td>
							<td>'.$message.'</td>
						</tr>
					</table>';
		
		return $result;
	}
}