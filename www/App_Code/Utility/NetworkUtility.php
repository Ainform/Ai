<?php

/**
 * Utility_NetworkUtility class
 * Класс для работы с сетью
 * 
 * @author Frame
 * @version NetworkUtility.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */

class Utility_NetworkUtility
{
	/**
	 * Возращает Ip пользователя
	 * 
	 * @return string
	 */
	public static function GetUserIp()
	{
		$remoteInfo = self::GetRemoteInfo();
		return $remoteInfo[0];
	}
	
	/**
	 * Возвращает информацию о пользователе
	 * 
	 * @return array
	 */
	private static function GetRemoteInfo()
	{
		$proxy = null;
		$ip = null;

		if (isset($_SERVER))
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				$proxy  = $_SERVER['REMOTE_ADDR'];
			}
			elseif (isset($_SERVER['HTTP_CLIENT_IP']))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			else
				$ip = $_SERVER['REMOTE_ADDR'];
		}

		if (strstr($ip, ','))
		{
			$ips = explode(',', $ip);
			$ip = $ips[0];
		}

		$RemoteInfo[0] = $ip;
		$RemoteInfo[1] = @gethostbyaddr($ip);
		$RemoteInfo[2] = $proxy;

		return $RemoteInfo;
	}
}

?>