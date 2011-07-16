<?
/**
 * TextPageModule class
 * 
 * @author Frame
 * @version TextPage.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class FilesModule extends BMC_BaseModule 
{

	function __construct($moduleId)
	{
		parent::__construct($moduleId);
	}
	
	function DataBind()
	{		
		$smarty = PHP_Smarty::GetInstance();
		//$smarty->registerPlugin("function","GetFileLink", array($this, "GetFilePath"));
		//$smarty->registerPlugin("function","GetFileSize", array($this, "GetFileSize"));

		//$smarty->assign("readMore", Request("readMore"));
		
		/*if (Request("fileId")!==null)
		{	
			//РІС‹РґР°РµРј РїРѕР»СЊР·РѕРІР°С‚РµР»СЋ С„Р°Р№Р» СЃ РЅРѕСЂРјР°Р»СЊРЅС‹Рј РёРјРµРЅРµРј :)
			
			$filedb = new DAL_FilesDb();
			$file = $filedb->GetRepositFile(Request("fileId"));
			header('Content-type: application/'.$file[0]["Ext"]); 
			header('Content-Disposition: attachment; filename="'.$file[0]["DocName"].".".$file[0]["Ext"].'"');
			header("Content-Length: ".$this->GetFileSize(FilesFolder.$file[0]["FileId"].".".$file[0]["Ext"]));
			
			readfile(FilesFolder.$file[0]["FileId"].".".$file[0]["Ext"]);
			die();
		}*/

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
          $file['Path']=$this->GetFilePath(array("id"=>$file['FileId']));
        }

		$this->data["Files"] = $fileList;
        
        //$pageVar = "pageNum";
        
        //$pageCount = ceil($allFiles/2); // РєРѕР»РёС‡РµСЃС‚РІРѕ СЃС‚СЂР°РЅРёС†

        //$this->SetPager($pageCount, $pageVar, "files");
	}
	
	/**
	 * Р’РѕР·РІСЂР°С‰Р°РµС‚ РїСѓС‚СЊ Рє С„Р°Р№Р»Сѓ
	 *
	 */
    function GetFilePath($params)
	{
       $files = new DAL_FilesDb();
          $name=$files->GetFileName($params["id"]);

		return "/upload/files/".$name;
	}

	function OnModuleAdd()
	{
	}
	
	function OnModuleDelete()
	{
      $files = new DAL_FilesDb();
      $files->DeleteFiles($this->moduleId);
	}
}
?>