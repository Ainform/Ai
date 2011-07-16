<?
/**
 * newsModule class
 * Вывод данных модуля
 *
 *
 */

class LastNewsModule extends BMC_BaseModule {
  
  function ToDate($date) {
    return date("dd.mm.yyyy", $date);
  }


  function DataBind() {
    $smarty = PHP_Smarty::GetInstance();

    $this->BindList();

  }

  public function BindList() {

    $ItemsOnPage = 5;

    $newsDb = new DAL_NewsDb();

    $rows = $newsDb->GetLastNews($ItemsOnPage,'');

    unset($newsDb);

    $imagesDb = new DAL_ImagesDb();

    foreach ($rows as $key=>&$row) {

      $folder = DAL_NewsDb::GetFolder($row['id']);
      $image = $imagesDb->GetTopFromFolder($folder);

      if ($image == null)
        continue;

      $imgPath = DAL_ImagesDb::GetImagePath($image);

      $row['Image'] = '<img src="'.$imgPath.'&width=150" alt="'.$image['Title'].'" title="'.$image['Title'].'" class="big_news" align="left" width="150" />';


    }
    unset($imagesDb);

    if (0 == count($rows))
      return;

    $this->data['NewsList'] = $rows;


  }
}


?>