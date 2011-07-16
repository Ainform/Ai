<?
class SignOutModule extends BMC_BaseModule
{
	public function DataBind()
	{
		$identity = BLL_AdminAuthentication::GetObject();
		$identity->LogOut();
	}
	
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}
}
?>