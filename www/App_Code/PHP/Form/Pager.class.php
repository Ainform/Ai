<?php

/**
 * PHP_Form_Pager class
 * Класс для создания пейджинга
 * 
 * @author Frame
 * @version Pager.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class PHP_Form_Pager
{
	/**
	 * Начальный путь к странице (без номера)
	 *
	 * @var string
	 */
	public $urlStart;
	
	/**
	 * Конечный путь к странице (без номера)
	 *
	 * @var string
	 */
	public $urlEnd;
	
	/**
	 * Количество записей на странице (по умолчанию 10)
	 *
	 * @var int
	 */
	public $recordsOnPage = 10;

	/**
	 * Общее количество записей
	 *
	 * @var int
	 */
	public $recordsCount;

	/**
	 * Номер текущей страницы
	 *
	 * @var int
	 */
	public $curPage = 1;

	/**
	 * Возвращает ссылки для перехода на другие страницы при постраничном разбиении
	 * 
	 * @return string html код
	 */
	public function BindPageLinks()
	{
		// заводим переменную результата
		$html = null;

		// количество страниц
		$pagesCount = ceil($this->recordsCount / $this->recordsOnPage);

		if ($pagesCount > 1)
		{
			$html .= 'Страницы: ';

			// переход на предыдущую страницу
			if ($this->curPage > 1)
				$html .= '<span class="prev"><a href="'.$this->urlStart.($this->curPage - 1).$this->urlEnd.'" title="Предыдущая страница">&lt;&lt;</a></span> ';
			else
				$html .= '&lt;&lt; ';

			$html .= '&nbsp;&nbsp;&nbsp;';

			//if ($this->urlStart[6] != '/' && strpos($this->urlStart, "http:/") !== false)
				//str_replace("http:/", "http://", $this->urlStart);
			
			// ссылки на страницы
			for ($i = 1; $i < $pagesCount + 1; $i++)
			{
				if ($i == $this->curPage)
				$html .= ' <span class="page_link_selected">'.$i.'</span>';
				else
				$html .= ' <span class="page_link"><a href="'.$this->urlStart.$i.$this->urlEnd.'" title="Страница номер '.$i.'">'.$i.'</a></span>';
			}

			$html .= '&nbsp;&nbsp;&nbsp;';

			// переход на следующую страницу
			if ($this->curPage < $pagesCount)
				$html .= ' <span class="next"><a href="'.$this->urlStart.($this->curPage + 1).$this->urlEnd.'" title="Следующая страница">&gt;&gt;</a></span>';
			else
				$html .= ' &gt;&gt;';

		}

		// если создали ссылки, то возвращаем в таблице
		if (null != $html) 
			return '<div id="pager">'.$html.'</div>';

		return null;
	}

}
?>