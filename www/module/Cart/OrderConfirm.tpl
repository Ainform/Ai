<?php
/**
 * Enter description here...
 *
 */

class OrderConfirm
{
	public $html = "";
	
	public $orderConfirmed;
	private $errorMessage = "";
	private $orderId;
	
	private $formData=array
	(
		'goodId' => '',
		'count'  => '',
		'title'  => '',
		'price'  => ''
	);
	
	public  function __construct()
	{
		$this->orderConfirmed = false;
	}
	
	/**
	 * Возвращает массив товаров
	 * товары хранятся в сессии
	 *
	 * @return array
	 */
	private function SelectGoodsFromSession()
	{
		$goods = null;
		
		foreach ($_SESSION["GoodsList"] as $key=>$value)
			if ($value["count"] > 0)
				$goods[] = $value;
		
		return $goods;
	}
	
	/**
	 *  вывод содержимого сессии-корзины
	 */
	public function Render()
	{
		$goods = $this->SelectGoodsFromSession();
		
		$section = 0;
		//$section = Request('section');
		$summ = 0;
		$this->html = "";
		$this->html .= "<center><b>Вы хотите заказать следующие товары:</b></center><br /><br />";
		
		$this->html .= "
		<table class=\"text order_confirm_table\" cellspacing=\"0\" align=\"center\" width=\"60%\">
			<tHead class=\"order_confirm_table_head\">
				<th> Наименование </th>
				<th> Заводская цена, руб. </th>
				<th> Требуемое количество </th>
				<th> Сумма, руб </th>
				<th> &nbsp; </th>
			</tHead>";

		//var_dump($_REQUEST);
		if (count($goods) > 0)
		{		
			foreach ($goods as $value)
			{
				//$good = GetGood($value[0]);//probably needs conversion to int
				$this->html .= "
				<tr>
					<td>$section".          $value["title"]   ."</td>
					<td align=\"center\">". $value["price"]   ." </td>
					<td align=\"center\">". $value["count"] ."</td>
					<td align=\"center\">". $value["count"] * $value["price"] ."</td> <td>";

				if (!isset($_POST["contact_face"]))			
					$this->html .= "<a href='http://master/module/Catalog/AddGoods.php?goodIdDel=".$value["goodId"]."'> Удалить </a>";
	
				$this->html .= "</td> </tr>";
				$summ += $value["count"] * $value["price"];
			}
		}	
		$this->html .= "
		<tr>
			<td colspan=\"5\" align=\"right\"><b>Итого: ".$summ." руб.</b></td>
		</tr>
		</table>";
	}

	/**
	 * Отправляет оповещение об отправке
	 */
	public function MailerSend($from , $email , $tel)
	{
		$this->html .= "<center><b>Большое спасибо, заявка принята. С Вами свяжутся в ближайшее время.</b></center>";
		
		$PHP_Mailer = new PHP_Mailer();

		$PHP_Mailer->IsHTML(true);
		$PHP_Mailer->AddAddress("vadimopetrov@bk.ru", "Test");
		
		$PHP_Mailer->FromName ="Profilpvh, система оповещения";
		$PHP_Mailer->Subject  ="Новый заказ";
		$PHP_Mailer->Body  = "Поступил новый заказ.";
		$PHP_Mailer->Body .= $this->html;
		$PHP_Mailer->Body .= "Контактная информация: $from $email $tel";
		//echo $PHP_Mailer->Body;
		
		$PHP_Mailer->Send();
	}

	/**
	 * хэндлер кнопки Заказать на странице заказа.
	 */
	public function handlerMakeOrder()
	{
		$section = Request('section');
		
		$this->formData['contact_face'] = Request('contact_face');
		$this->formData['phone'] 		= Request('phone');
		$this->formData['email'] 		= Request('email');
		
		if (empty($this->formData['contact_face'])||
		    empty($this->formData['phone']) || 
		    empty($this->formData['email']))
		  $this->errorMessage .= "Поля отмеченные звёздочкой обязательны для заполнения<br>";
		
		$this->errorMessage .= Utility_VerifyUtility::VerefyEmail($this->formData['email']);
		
		if (!empty($this->errorMessage)) return;
		
		//$auth = new BLL_CustomerAuthentification();
		//$auth->Authenticate();
		
		//$userInfo = $auth->UserInfo();
		
/*
		$this->orderConfirmed = true;

		$orderDb = new DAL_OrderDb();

		$goods = $this->SelectGoodsFromSession();

		$order['date'] 		   = date("Y-m-d");
		$order['contact_face'] = $this->formData['contact_face'];
		$order['customer_id']  = 0;//$userInfo['CustomerId'];
		$order['phone'] 	   = $this->formData['phone'];
		$order['email'] 	   = $this->formData['email'];
		$order['description']  = '';
		
		$orderDb->AddOrder($order, $goods, $section, $this->orderId);
*/		
	}
}
?>

