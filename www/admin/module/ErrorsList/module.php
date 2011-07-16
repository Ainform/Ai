<?
class ErrorsListModule extends BMC_BaseModule
{
	public function DataBind()
	{
		$errorsList = Helpers_LogHelper::GetErrors();

		if (isset($errorsList))
			$this->data['ErrorsList'] = nl2br(htmlentities($errorsList, ENT_NOQUOTES, "UTF-8"));
	}
	
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}
	
	/**
	 * Удаляет журнал ошибок
	 */
	function handlerBtnClearClick()
	{
		Helpers_LogHelper::ClearErrorFile();

		RedirectBack();
	}
}
?>