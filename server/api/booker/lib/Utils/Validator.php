<?php
namespace Utils;

/**
 * Description of Validator
 *
 * @author yoink
 */
class Validator
{

	public function __construct(){}


	public static function validEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		return false;
	}
	public static function validName($name)
	{
		if(!preg_match('/^[a-zA-Z][a-zA-Z\.\s]{3,20}$/', $name))
		{
			return false;
		}
		return true;
	}
	public static function validPassword($pass)
	{
		if (strlen($pass) > 20 || strlen($pass) < 8)
		{
			return false;
		}
		if(!preg_match("/^[a-z0-9]+$/i", $pass))
		{
			return false;
		}
		return true;
	}

	public static function validCount($cnt)
	{
		if(!preg_match("/^[0-9]+$/", $cnt))
		{
			return false;
		}
		if((int)$cnt < 0)
		{
			return false;
		}
		return true;		
	}

	public static function validAuthName($name)
	{
		if(!preg_match('/^[a-zA-Z][a-zA-Z\.\s]{3,30}$/', $name))
		{
			return false;
		}
		return true;
	}
	public static function validGenreName($name)
	{
		if(!preg_match('/^[a-zA-Z][a-zA-Z\.\s]{3,30}$/', $name))
		{
			return false;
		}
		return true;
	}
	public static function validBookName($name)
	{
		if(!preg_match('/^[a-zA-Z][a-zA-Z0-9\.\s\-]{3,40}$/', $name))
		{
			return false;
		}
		return true;
	}
	
	public static function validDescript($text)
	{
		if(mb_strlen($text) < 400 && mb_strlen($text) > 5)
		{
			return true;
		}
		return false;
	}

	public static function validPrice($price)
	{
		if(!preg_match("/\b\d+\.\d{0,2}\b/", $price))
		{
			return false;
		}
		return true;
	}

	public static function validDiscount($disc)
	{
		if(!preg_match("/^[0-9]+$/", $disc))
		{
			return false;
		}
		if( ((int)$disc < 0) || ((int)$disc > 50))
		{
			return false;
		}
		return true;
	}
}
