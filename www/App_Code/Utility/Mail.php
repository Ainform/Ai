<?php

/**
* Mail class
* Класс для отправки писем сайта
* 
* @author Frame
* @version 1.0.1
* @copyright (c) by VisualDesign
*/

class Utility_Mail extends PHP_Mailer 
{
   	function __construct($toEmail, $toName = null)
   	{
   		$this->From     = AdminEmail;
    	$this->FromName = AdminName;
    	$this->WordWrap = 75;

   		$this->AddAddress($toEmail, $toName);
		$this->AddReplyTo(AdminEmail);

		$this->IsHTML(true);
   	}
}