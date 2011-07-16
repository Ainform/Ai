<?php

/**
 * PHP_Tree class
 * Класс для создания дерева
 * 
 * @author Frame
 * @version Tree.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class PHP_Tree
{
	/**
	 * Данные для построения дерева
	 * 
	 * @var array
	 */
	public $dataSource;

	/**
	 * Xml данные для построения дерева
	 * 
	 * @var DOMElement
	 */
	public $xmlDataSource;

	/**
	 * Признак Xml данных
	 *
	 * @var unknown_type
	 */
	public $isXML = false;

	/**
	 * Идентификатор выбранного элемента
	 *
	 * @var string
	 */
	public $selectedId;
	
	/**
	 * Индекс идентификатора элемента
	 *
	 * @var string
	 */
	public $dataId = 'Id';
	
	/**
	 * Индекс идентификатора родительского элемента
	 *
	 * @var string
	 */
	public $dataParentId = 'ParentId';	
	
	/**
	 * Индекс названия элемента
	 *
	 * @var string
	 */
	public $dataName = 'Title';
	
	/**
	 * Созданное дерево
	 *
	 * @var string
	 */
	private $tree;
	
	/**
	 * Создает дерево
	 *
	 * @return string
	 */
	public function BindData()
	{
		
	}

	/**
	 * Создает дерево на основе данных в массиве
	 */
	function BuildTreeFromArray()
	{
		$sb = '<table class="admtext" cellspacing="0" cellpadding="0" width="100%">
				<tr><td><select style="width: 100%" name="lstControls" size="25">';

		$sb .= '<option value="-1" '.(HttpUtility::IsPostBack() ? '' : 'selected="true"').' >'.$section['Name'].'</option>';
		
		foreach ($controls as $row)
		{
			if (isset($_POST['lstControls']) && $_POST['lstControls'] == $row['ControlId'])
				$sb .= "<option value='".$row['ControlId']."' selected='true'>".$row['Name']."</option>";
			else 	
				$sb .= "<option value='".$row['ControlId']."'>".$row['Name']."</option>";
		}
								
		$sb .= '</select></td></tr></table>';
		
		return 	$sb;
	}
	
	/**
	 * Создает дерево на основе XML данных
	 */
	function BuildTreeFromXML()
	{
		
	}
}