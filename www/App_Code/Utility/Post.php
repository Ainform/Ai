<?
class Utility_Post
{
	function __get($property)
	{
		if (isset($_POST[$property]))
			return $_POST[$property];
				
		return null;
	}
}
?>