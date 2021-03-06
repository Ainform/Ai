<?
/**
 * TextPageModule class
 * ????????? ???????? ?????
 * 
 * @author Frame
 * @version TextPage.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class RedirectModule extends BMC_BaseModule 
{
	/**
	 * ??????? ??? ???????? html-???? ??????
	 * 
	 * @return void
	 */
	function DataBind()
	{
        $RedirectDb = new DAL_RedirectDb(); 
        $URL=$RedirectDb->GetUrl($this->moduleId);
        Header("Location:".$URL['URL']);

	}
	
	/**
		??????? ??? ?????????? ?????? ? ????????
	*/
	function OnModuleAdd()
	{
		$RedirectDb = new DAL_RedirectDb();
		$RedirectDb->InsertURL($this->moduleId);
	}
	
	/**
		??????? ??? ???????? ??????
	*/
	function OnModuleDelete()
	{
		$RedirectDb = new DAL_RedirectDb();
		$RedirectDb->DeleteURL($this->moduleId);		
	}
	
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}
}
?>