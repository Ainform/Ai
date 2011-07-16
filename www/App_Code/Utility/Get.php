<?
class Utility_Get
{
	function __get($property)
	{
		if (isset($_GET[$property]))
			return $_GET[$property];
				
		return null;
	}
}
?>