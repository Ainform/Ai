<?


/**
 * @copyright (c) by VisualDesign
 */
class FilesModuleEdit extends BMC_BaseModule 
{
	public $moduleId;
	
	function DataBind()
	{
		$smarty = PHP_Smarty::GetInstance();
		$smarty->registerPlugin("function","GetFileLink", array($this, "GetFilePath"));
		$smarty->registerPlugin("function","GetFileSize", array($this, "GetFileSize"));
		
		$this->GetAllFiles($this->moduleId);
	}
	
	/**
	 * РІС‹РґР°РµС‚ СЃРїРёСЃРѕРє РІСЃРµС… С„Р°Р№Р»РѕРІ
	 *
	 * @param unknown_type $moduleId
	 */
	public function GetAllFiles($moduleId)
	{
		$files = new DAL_FilesDb();
		$fileList = $files->GetRepositFileList($moduleId);
        foreach($fileList as &$file){
          $file['Path']=$files->GetFilePath(array("id"=>$file['FileId']));
        }
		
		$this->data["Files"] = $fileList;
	}
	
	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}
	
	/**
	 * Р”Р°Р±РѕРІР»СЏРµРј С„Р°Р№Р» Рє С…СЂР°РЅРёР»РёС‰Сѓ
	 *
	 */
	public function handlerBtnSave($fileId)
	{
		
		// РїСЂРѕРІРµСЂРёРј, РІС‹Р±СЂР°Р» Р»Рё РїРѕР»СЊР·РѕРІР°С‚РµР»СЊ РєР°РєРѕР№ Р»РёР±Рѕ С„Р°Р№Р» РЅР° Р·Р°РіСЂСѓР·РєСѓ
		if (empty($_FILES["document"]["name"]))
		{// Рё РµСЃРё РЅРµС‚ :)!
			$smarty = PHP_Smarty::GetInstance();
			$smarty->assign("documentNo" , "Р­С‚Рѕ С„Р°Р№Р»РѕРІС‹Р№ Р°СЂС…РёРІ! Р—Р°РіСЂСѓР·РёС‚Рµ С„Р°Р№Р»!");
			$this->template = "fileedit.tpl";
			return 0;
		}
		
		// РїСЂРѕРІРµСЂРёРј, РІРІРµР» Р»Рё РїРѕР»СЊР·РѕРІР°С‚РµР»СЊ РёРјСЏ РґРѕРєСѓРјРµРЅС‚Р°
		if (empty($this->data["txtDocName"]))
		{// Рё РµСЃРё РЅРµС‚ :)!
			$this->handlerAddFile();
			return 0;
		}
		
		$filedb = new DAL_FilesDb();		

		if ($fileId == 0)
			$filedb->AddFile($this->data["txtDocName"],
							 $this->moduleId,
							 $this->data["fckDescription"]);
	 	unset($_FILES['document']);
	}
	
	public function handlerBtnCancel()
	{
		RedirectToPageEdit($this->moduleId);		
	}
	
	/**
	 * Р’РѕР·РІСЂР°С‰Р°РµС‚ СЂР°Р·РјРµСЂ С„Р°Р№Р»Р°
	 *
	 */
	function GetFileSize($params)
	{
      $filedb = new DAL_FilesDb();
      $path=$filedb->GetFilePath(array("id"=>$params["id"]));
		if (file_exists($path))
			return round(filesize($path)/1024 , 2);
		else 
			return 0;
	}

	/**
	 * РЈРґР°Р»РµРЅРёРµ С„Р°Р№Р»Р° РёР· Р±Рґ Рё С…СЂР°РЅРёР»РёС‰Р°
	 *
	 * @param int $fileId - Р°Р№РґРё С„Р°Р№Р»Р° РІ Р±Рґ
	 */
	
	public function handlerBtnDelete($fileId)
	{
		$filedb = new DAL_FilesDb();
		$filedb->DeleteFile($fileId);		
	}

	/**
	 * РїРµСЂРµС…РѕРґ РЅР° СЃС‚СЂР°РЅРёС†Сѓ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ РёРЅС„РѕСЂРјР°С†РёРё Рѕ С„Р°Р№Р»Рµ
	 *
	 * @param unknown_type $fileId
	 */
	public function handlerBtnEdit($fileId)
	{
		$smatry = PHP_Smarty::GetInstance();
		$smatry->assign("fileId" , $fileId);
		$smatry->assign("is_edit" , "1");
		
		$filedb = new DAL_FilesDb();
		$file = $filedb->GetRepositFile($fileId);
		
		//$this->data["txtFileName"] = $file[0]["ArchiveName"];
		$this->data["txtDocName"]  = $file[0]["DocName"];
		$this->data["fckDescription"] = $file[0]["Description"];
		$this->template = "fileedit.tpl";
	}
	
	/**
	 * РџРµСЂРµС…РѕРґ РЅР° СЃС‚СЂР°РЅРёС†Сѓ РґРѕР±Р°РІР»РµРЅРёСЏ РЅРѕРІРѕРіРѕ С„Р°Р№Р»Р° РІ Р°СЂС…РёРІ
	 *
	 */
	public function handlerAddFile()
	{
		$smarty = PHP_Smarty::GetInstance();
		$smarty->assign("ButtonName" , "Р”РѕР±Р°РІРёС‚СЊ");
		$this->template = "fileedit.tpl";
	}
	
	/**
	 * РћР±РЅРѕРІР»РµРЅРёРµ СЃРІРµРґРµРЅРёР№ Рѕ С„Р°Р№Р»Рµ РІ Р°СЂС…РёРІРµ 
	 *  
	 * 	! РЅРµСЂР°Р±РѕС‡Р°СЏ ! 
	 *
	 * @param unknown_type $fileId
	 */
	public function handlerBtnUpdate($fileId)
	{
		$filedb = new DAL_FilesDb();
		
		$filedb->UpdateFile($fileId, $this->data["txtDocName"], 
									 $this->moduleId,
 									 $this->data["fckDescription"]);		
	}
}
?>