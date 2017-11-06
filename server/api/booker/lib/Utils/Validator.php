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

	public static function validDescript($text)
	{
		if(mb_strlen($text) < 400 && mb_strlen($text) > 5)
		{
			return true;
		}
		return false;
	}

	public static function validTimeRange(\DateTime $tStart,\DateTime $tEnd)
	{
		$valStart = clone $tStart;
		$valEnd = clone $tEnd;
		if($tEnd->getTimestamp() == $tStart->getTimestamp())
		{
			return false;
		}

		if(($tStart->getTimestamp() < $valStart->setTime(START_HOUR,START_MIN)->getTimestamp()) ||
				($tStart->getTimestamp() > $valStart->setTime(END_HOUR,END_MIN)->getTimestamp()) ||
				($tEnd->getTimestamp() > $valEnd->setTime(END_HOUR,END_MIN)->getTimestamp()) ||
				($tEnd->getTimestamp() < $valEnd->setTime(START_HOUR,START_MIN)->getTimestamp()))
		{
			return false;
		}
		return true;
	}

	public static function isWeekend(\DateTime $tStart)
	{
		return (date('N', strtotime($tStart->format('Y-m-d'))) >= 6);
	}

	public static function isPastTime(\DateTime $tStart)
	{
		$now = new \DateTime();
		return ($tStart->getTimestamp() < $now->getTimestamp());
	}

}
