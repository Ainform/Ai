<?php

/**
 * Utility_Sections class
 * Класс для работы с разделами
 * 
 * @author Frame
 * @version Sections.php, v 1.0.1
 * @copyright (c) by Frame
 */

class Utility_Sections
{
	/**
	 * Создает список разделов
	 *
	 * @param array $rows список разделов
	 */
	public static function BindSectionList($rows, $moduleName)
	{
		if (0 == count($rows))
			return;

		$first = true;
		$html = null;

		foreach ($rows as $row)
		{
			$html .= (true === $first) ? '<table width="100%"><tr><td width="50%">' : '<td width="50%">';

			$html .= '<table width="100%" class="text">
							<tr valign="top">
								<td align="center">
									<a href="' . $moduleName . $row['SectionId'] . '.php"><img width="100" src="/ImageHandler.php?id='.$row['Photo'].'&width=100" hspace="10" alt="'.$row['Title'].'">
									<br />
									<h2>' . $row['Title'] . '</h2></a>
								</td>
							</tr>
						   </table>
						   <br />';

			$html .= (true === $first) ? '</td>' : '</td></tr></table>';

			$first = (true === $first) ? false : true;
		}

		if (true !== $first)
			$html .= '</tr></table>';

		return $html;
	}
	
	/**
	 * Создает хлебные крошки для раздела продукции
	 *
	 * @param int $sectionId идентификатор раздела
	 * @param string $title название раздела
	 */
	public static function CreateBreadCrumbsSections($sectionId, $catalogTitle, $catalogUrl, $sectionPage = null, $db, $breadCrumbsArray = null)
	{
		if (is_null($breadCrumbsArray))
			$breadCrumbsArray = array();
		
		$breadCrumbs = BLL_BreadCrumbs::getInstance();

		if (-1 != $sectionId)
		{
			$row = $db->GetSection($sectionId);
			self::BindBreadCrumbsSections($row['SectionId'], $db, $breadCrumbsArray, $sectionPage);
		}

		unset($db);
		
		$breadCrumbsArray = array_reverse($breadCrumbsArray);

		$breadCrumbs->Add(array($catalogTitle => (0 == count($breadCrumbsArray) ? '' : $catalogUrl)));

		foreach ($breadCrumbsArray as $title => $url)
			$breadCrumbs->Add(array($title => $url));
	}
	
	private static function BindBreadCrumbsSections($sectionId, $db, &$breadCrumbsArray, $sectionPage)
	{
		$row = $db->GetSection($sectionId);

		if (0 == count($row))
			return;

		if (0 == count($breadCrumbsArray))
			$breadCrumbsArray["{$row['Title']}"] = '';
		else
			$breadCrumbsArray["{$row['Title']}"] = $sectionPage . $row['SectionId'] . '.php';

		if (-1 != $row['ParentId'])
		{
			self::BindBreadCrumbsSections($row['ParentId'], $db, $breadCrumbsArray, $sectionPage);
		}
	}
}
