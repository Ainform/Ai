<?php

/**
 * BLL_GridUtility class
 * 
 * 
 * @author Frame
 * @version 0.0.2
 * @copyright (c) by VisualDesign
 */

class BLL_GridUtility
{
	const FileName = 'gridFile';


	/**
	 * Возвращает сетку
	 * 
	 * @return array
	 */
	public static function Get()
	{
		$filePath = Helpers_PathHelper::GetFullPath('file') . self::FileName;

		if (!PHP_IO_File::Exists($filePath))
			return array(
						'size' => array('height_start' => 0,
										'height_end' => 0,
										'width_start' => 0,
										'width_end' => 0,
										'step' => 0
										),
						'grid' => array()
						);

		$content = PHP_IO_File::Read($filePath);

		return unserialize($content);
	}


	/**
	 * Устанавливает сетку
	 *
	 * @param array $grid сетка
	 */
	public static function Set($grid)
	{
		$filePath = Helpers_PathHelper::GetFullPath('file') . self::FileName;

		$content = serialize($grid);

		PHP_IO_File::Write($filePath, $content);
	}


	/**
	 * Обновляет сетку до необходимых размеров
	 *
	 * @param array $grid сетка
	 */
	public static function Update($grid)
	{
		$hStart = $grid['size']['height_start'];
		$hEnd = $grid['size']['height_end'];

		$wStart = $grid['size']['width_start'];
		$wEnd = $grid['size']['width_end'];

		$step = $grid['size']['step'];

		$old = $grid['grid'];

		$new['grid'] = array();
		$new['size'] = $grid['size'];

		for ($row = $hStart; $row <= $hEnd; $row = $row + $step)
		{
			for ($col = $wStart; $col <= $wEnd; $col = $col + $step)
			{
				if (isset($old[$row][$col]))
					$new['grid'][$row][$col] = $row*$col/10000;
				else
					$new['grid'][$row][$col] = $row*$col/10000;
			}
		}

		return $new;
	}
}