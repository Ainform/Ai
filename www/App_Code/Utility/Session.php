<?
class Utility_Session
{
	function __get($property)
	{
		if (isset($_SESSION[$property]))
			return $_SESSION[$property];
				
		return null;
	}
	
	function __set($property, $value)
	{
		$_SESSION[$property] = $value;
	}
}
?>