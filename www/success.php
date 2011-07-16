<?

// ;дключаем классы
include_once ('config.php');

// инициализируем базу данных
        $dbManager = new DAL_DbManager ( DbHost, DbName, DbUserName, DbUserPass );

// регистрационная информация (пароль #1)
// registration info (password #1)
$mrh_pass1 = "buketufa00";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

// проверка корректности подписи
// check signature
if ($my_crc != $crc)
{
  echo "bad sign\n";
  exit();
}


$orderDb = new DAL_OrderDb();
$curorder=$orderDb->GetOrder($inv_id);
unset($orderDb);

if($curorder[0]['paid']==1){
Redirected("/internet-magazin/uspeshnaja_oplata/");
}
// проверка наличия номера счета в истории операций
// check of number of the order info in history of operations
/*$f=@fopen("order.txt","r+") or die("error");

while(!feof($f))
{
  $str=fgets($f);

  $str_exp = explode(";", $str);
  if ($str_exp[0]=="order_num :$inv_id")
  {
	echo "Операция прошла успешно\n";
	echo "Operation of payment is successfully completed\n";
  }
}
fclose($f);*/
?>


