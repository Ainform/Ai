<?
include_once('config.php');

$dbManager = new DAL_DbManager(DbHost, DbName, DbUserName, DbUserPass);

$rtDb = new DAL_BaseDb();

$manufacturersdb=$rtDb->query("SELECT * FROM Manufacturers");
foreach($manufacturersdb as $manufaturer){
  $manufacturers[]=$manufaturer['ManufacturerId'];
};
$sectionsdb=$rtDb->query("SELECT * FROM sections");
foreach($sectionsdb as $section){
  $sections[]=$section['SectionId'];
};
for($i=0;$i<200;$i++){

  $rtDb->query("INSERT INTO Goods (Title,Description,Price,Abstract,SectionId,ManufacturerId,`Order`,OnMain,Code,Quantity,GoodsAtWarehouse) VALUES (\"товар".$i."\",\"описание".$i."\",".mt_rand(100,100000).",\"текст".$i."\",".$sections[rand(1,count($sections)-1)].",".$manufacturers[rand(1,count($manufacturers)-1)].",".$i.",0,".$i.",1,1)");
}
unset($rtDb);
?>
