<?
class PHP_Validator
{
	public static function Check(&$data, $id, $rules)
	{
		//print_r($data);exit;
		
		$value = $data[$id];

		$rules = explode(" ", $rules);

		$validate = true;

		foreach ($rules as $rule)
		{
			$ruleMethod = "Rule".$rule;
			//Debug($value);
			$validate &= self::$ruleMethod($value);
		}

		if (!$validate)
		$data[$id."validator"] = "false";

		return $validate;
	}

	public static function RuleNotNull($value)
	{
		return strlen($value) > 0;
	}

	public static function RuleInt($value)
	{
		return preg_match("#^[0-9]+$#", $value) >= 1;
	}

	public static function RuleFloat($value)
	{
		return preg_match("#^[0-9]+((\.|,)[0-9]+)?$#", $value) >= 1;
	}

	public static function RuleLatin($value)
	{
		return preg_match("#^[0-9A-z_\-]+$#", $value) >= 1;
	}

	public static function RuleDate($value)
	{
		$date = strtotime($value);

		return $date > 0;
	}

	public static function RuleEmail($value)
	{
		return preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $value);
	}

	public static function RuleReally($value)
	{
		return $value>0;
	}
    public static function RuleCaptcha($value)
	{
		if(isset($_SESSION['captcha_keystring'])){
        return $value==$_SESSION['captcha_keystring']; }
        return false;
	}
}

?>