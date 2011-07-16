<?

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

$dbManager = new DAL_DbManager(DbHost, DbName, DbUserName, DbUserPass);

$uploader = new BLL_Uploader_Image(0, 0);

if (!IsPostBack())
	$uploader->SetDirectory('test');

if (isset($_POST['btnSave']))
{
	$uploader->Save(0);
}
else
{
	echo $uploader->GetFileUploader();
}

echo '<form method="POST">
		<input type="submit" name="btnSave" value="отправить">
	  </form>
	';
?>
