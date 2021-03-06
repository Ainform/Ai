<?
/**
 * ChildsModule class
 * 
 */

class TextPageModule extends BMC_BaseModule 
{
	/**
	 * ??????? ??? ???????? html-???? ??????
	 * 
	 * @return void
	 */
	function DataBind()
	{
		$textPagesDb = new DAL_TextPagesDb();
		$page = $textPagesDb->GetPage($this->moduleId);
		//var_dump($_SERVER["REQUEST_URI"]);
		$this->data["Text"] = str_replace('<table ', '<table class="table" ', $page['Text']);
		$this->data["IsToShowStripe"] = strpos($_SERVER["REQUEST_URI"], "contacts") === false;
	}
	
	/**
		??????? ??? ?????????? ?????? ? ????????
	*/
	function OnModuleAdd()
	{
		$textPagesDb = new DAL_TextPagesDb();
		$textPagesDb->AddPage($this->moduleId);
	}
	
	/**
		??????? ??? ???????? ??????
	*/
	function OnModuleDelete()
	{
		$textPagesDb = new DAL_TextPagesDb();
		$textPagesDb->DeletePage($this->moduleId);		
	}
	
	function __construct($moduleId)
	{
		$this->cssClass = "textPage";
		parent::__construct($moduleId);
	}
}
?>