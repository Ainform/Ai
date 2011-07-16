<?

// ;дключаем классы
include_once ('config.php');

// инициализируем базу данных
        $dbManager = new DAL_DbManager ( DbHost, DbName, DbUserName, DbUserPass );

// регистрационная информация (пароль #2)
// registration info (password #2)
$mrh_pass2 = "buketufa200";

//установка текущего времени
//current date
$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

// проверка корректности подписи
// check signature
if ($my_crc !=$crc)
{
  echo "bad sign\n";
  exit();
}

// признак успешно проведенной операции
// success
echo "OK$inv_id\n";

$orderDb = new DAL_OrderDb();
$orderDb->OrderPaid($inv_id);
unset($orderDb);

// запись в файл информации о прведенной операции
// save order info to file
//$f=@fopen("order.txt","a+") or
          die("error");
//fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date\n");
//fclose($f);

?>


