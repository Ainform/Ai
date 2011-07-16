<?
class SubPagesModule extends BMC_BaseModule
{

	#region Module Constructor
	function __construct($moduleId)
	{
		$this->cssClass = "Subpages";
		parent::__construct($moduleId);		
	}
	#endregion
	
	private $menuType = 2; // тип меню. 1 - горизонтальное, 2 - вертикальное, 3 - смешанное

	#region Data Binding
	/**
		Биндим данные
	*/
	public function DataBind()
	{
		$this->data['MenuType'] = $this->menuType;
		$this->data['Url'] = $this->GetVirtualPath();
		
		return $this->BindSubPages($this->moduleId);
	}
	
	public function BindSubPages($moduleId)
	{
		$pagesDb = new DAL_PagesDb();
		$subpages = $pagesDb->GetSubPagesByModule($moduleId);
		
		$this->data['Pages'] = $subpages;
	}
}
?>