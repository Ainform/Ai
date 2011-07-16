<?php 

	include("../../App_Code/BLL/ShoppingCartUtility.php");
	include("OrderConfirm.tpl");
	
	if (!isset($_SESSION["session_start"]))
	{
		session_start();
	}


//	include("BasketList.php");
	
//	print_r($_SESSION);
//	print_r($_POST);
	
	$spng_goods_cart = new BLL_ShoppingCartUtility();
	
	if (isset($_REQUEST["goodIdDel"]))
		$spng_goods_cart->Delete($_REQUEST["goodIdDel"]); 
	else if (isset($_POST["goodId"]))
		$spng_goods_cart->add($_POST["goodId"], $_POST["count"], $_POST["title"], $_POST["price"]);

		
	$orderConfirm = new OrderConfirm();
	$orderConfirm->orderConfirmed = isset($_POST["contact_face"]);
	
	$orderConfirm->Render();

	if (isset($_POST["contact_face"]))
		$orderConfirm->MailerSend(htmlspecialchars($_POST["contact_face"]),
								  htmlspecialchars($_POST["email"]) , 
								  htmlspecialchars($_POST["phone"]));
	
		echo $orderConfirm->html;
	
	//HttpUtility::Redirect(SiteAddress);
	
//	$basketlist = new Basket();
//	$basketlist->DataBind();
	
?>

<form action="AddGoods.php" method="POST">
<table  class="text" align="center">
				<tr>
					<td align="right"><b>Контактное лицо<span style="color:red;">*</span>:</b></td>

					<td><input type="text" name="contact_face" value="" /></td>
				</tr>
				<tr>
					<td align="right"><b>Контактный телефон<span style="color:red;">*</span>:</b></td>
					<td><input type="text" name="phone" value="" /></td>
				</tr>
				<tr>

					<td align="right"><b>E-mail<span style="color:red;">*</span>:</b></td>
					<td><input type="text" name="email" value="" /></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input class="button" type="submit" value="Подтвердить" name="handlerMakeOrder_x" /></td>
				</tr>
			</table>
</form>