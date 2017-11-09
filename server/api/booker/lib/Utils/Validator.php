<?php
namespace Utils;

/**
 * Description of Validator
 * Validator Class
 * @author yoink
 */
class Validator
{
	/**
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function validEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		return false;
	}

	/**
	 *
	 * @param string $name
	 * @return boolean
	 */
	public static function validName($name)
	{
		if(!preg_match('/^[a-zA-Z][a-zA-Z\.\s]{3,20}$/', $name))
		{
			return false;
		}
		return true;
	}

	/**
	 *
	 * @param string $pass
	 * @return boolean
	 */
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

	/**
	 *
	 * @param string $text
	 * @return boolean
	 */
	public static function validDescript($text)
	{
		if(mb_strlen($text) < 400 && mb_strlen($text) > 5)
		{
			return true;
		}
		return false;
	}

	/**
	 * cheks that start and end of event cant be the same time
	 * end cant be earlier than start
	 * and start and end of event must be in 8-20 hours of day(defined by constants)
	 *
	 * @param \DateTime $tStart
	 * @param \DateTime $tEnd
	 * @return boolean
	 */
	public static function validTimeRange(\DateTime $tStart,\DateTime $tEnd)
	{
		$valStart = clone $tStart;
		$valEnd = clone $tEnd;
		if($tEnd->getTimestamp() <= $tStart->getTimestamp())
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

	/**
	 * check is weekend day
	 *
	 * @param \DateTime $tStart
	 * @return boolean
	 */
	public static function isWeekend(\DateTime $tStart)
	{
		return (date('N', strtotime($tStart->format('Y-m-d'))) >= 6);
	}

	/**
	 * checks that input dateTime not earlier than current dateTime
	 *
	 * @param \DateTime $tStart
	 * @return boolean
	 */
	public static function isPastTime(\DateTime $tStart)
	{
		$now = new \DateTime();
		return ($tStart->getTimestamp() < $now->getTimestamp());
	}

	/**
	 * checks on max duration with selected recurring mode
	 *
	 * @param string $type
	 * @param string\int $value
	 * @return boolean
	 * @throws \Exception if type not supported
	 */
	public static function validDuration($type, $value)
	{
		if((int)$value < 1)
		{
			return false;
		}
		switch ($type)
		{
			case 'weekly':
				if((int)$value > 3)
				{
					return false;
				}
				return true;
			case 'be-weekly':
				if((int)$value > 2)
				{
					return false;
				}
				return true;
			case 'monthly':
				if((int)$value != 1)
				{
					return false;
				}
				return true;
			default :
				throw new \Exception(400);
		}
	}
}
