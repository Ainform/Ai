<?php
/**
 * CatalogEdit class
 * Редактирование каталога
 * 
 * @copyright (c) by VisualDesign
 */

class SubpagesModuleEdit extends BMC_BaseModule 
{
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
		$this->header = '';
	}

	function DataBind()
	{
		$this->tmaplate = "empty.tpl";
	}
}

?>