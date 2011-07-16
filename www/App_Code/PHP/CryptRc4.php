<?php

/**
 * PHP_CryptRc4 class
 * Класс для шифрования
 * 
 * @author Frame
 * @version CryptRc4.class.php, v 1.0.1
 * @copyright (c) by VisualDesign
 */


class PHP_CryptRc4
{
	/**
	 * Шифрует строку
	 *
	 * @param string $password пароль, максимум 255 символов
	 * @param string $data строка для шифрования
	 * @return string
	 */
	public static function EncryptString($password, $data)
	{
		$key[] = null;
		$box[] = null;
		$cipher = null;

		$password_length = strlen($password);
		$data_length = strlen($data);

		for ($i = 0; $i < 256; $i++)
		{
			$key[$i] = ord($password[$i % $password_length]);
			$box[$i] = $i;
		}

		for ($j = $i = 0; $i < 256; $i++)
		{
			$j = ($j + $box[$i] + $key[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for ($a = $j = $i = 0; $i < $data_length; $i++)
		{
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;

			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;

			$k = $box[(($box[$a] + $box[$j]) % 256)];
			$cipher .= chr(ord($data[$i]) ^ $k);
		}

		return $cipher;
	}

	/**
	 * Расшифровывает строку
	 *
	 * @param string $password пароль, максимум 255 символов
	 * @param string $data строка для расшифровывания
	 * @return string
	 */
	public static function DecryptString ($password, $data)
	{
		return self::EncryptString($password, $data);
	}
}

?>