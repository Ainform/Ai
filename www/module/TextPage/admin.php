<?
/**
 * TextPageModuleEdit class
 * �������������� ��������� �������� �����
 *
 * @copyright (c) by VisualDesign
 */
class TextPageModuleEdit extends BMC_BaseModule
{
	/**
	 * ������� ��� �������� html-���� ������
	 *
	 * @return void
	 */
	function DataBind()
	{
		$textPagesDb = new DAL_TextPagesDb();
		$page = $textPagesDb->GetPage($this->moduleId);
		$this->data['Page'] = $page;

		// ���������� �����, ����������� ������������� � ������
		$imageUtility = new Utility_ImageUtility();
		$imageUtility->SetDirectory('pages/'.$this->moduleId);
	}

	function __construct($moduleId)
	{
		$this->cssClass = "textPage";
		parent::__construct($moduleId);
	}

	/**
	 * ��������� ������
	 */
	public function handlerBtnSave()
	{
		// �������� ������
		$text = $this->data['fckText'];

		// ���������� �����, ����������� ������������� � ������
		$imageUtility = new Utility_ImageUtility();

		// ������ ����� ��� �������� � ��������� �����
		//$imageUtility->SetDirectory('page'.$this->moduleId);
		//$imageUtility->UpdateFiles($this->data['fckText']);

		// ��������� � ��
		$textPagesDb = new DAL_TextPagesDb();
		$textPagesDb->UpdateTextPage(array("ModuleId" => $this->moduleId, "Text" => $text));

		// ���������� �� �������������� ��������
		RedirectToModuleEdit($this->moduleId);
	}

	/**
	 * ������ ��������������
	 */
	public function handlerBtnCancel()
	{
		// ���������� �� �������������� ��������
		RedirectToPageEdit($this->moduleId);
	}
}
?>