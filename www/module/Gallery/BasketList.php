<?php 


	include("/App_Code/PHP/Smarty.php");

	class Basket extends  BMC_BaseModule 
	{
	
		public function DataBind()
		{		
			$smarty = PHP_Smarty::GetInstance();
			$smarty->assign("basket" , $_SESSION["GoodsList"]);
			$this->template = "BasketList.tpl";
		}
	
	}
?>