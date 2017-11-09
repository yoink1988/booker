<?php
namespace Utils;
/**
 * Description of ValidatorTest
 *
 * @author yoink
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	
	public  function testValidEmailFalse()
	{
		$this->assertFalse(\Utils\Validator::validEmail('iluhamail.ru'));
		$this->assertFalse(\Utils\Validator::validEmail('iluha@mailru'));
	}
	public  function testValidEmailTrue()
	{
		$this->assertTrue(\Utils\Validator::validEmail('iluha@mail.ru'));
	}

	public  function testValidNameFalse()
	{
		$this->assertFalse(\Utils\Validator::validName('@!$#'));
		$this->assertFalse(\Utils\Validator::validName('qq'));
	}
	public  function testValidNameTrue()
	{
		$this->assertTrue(\Utils\Validator::validName('Iluha'));
	}

	public  function testValidPasswordFalse()
	{
		$this->assertFalse(\Utils\Validator::validPassword('sadd'));
		$this->assertFalse(\Utils\Validator::validPassword('@!$!%'));
	}
	public  function testValidPasswordTrue()
	{
		$this->assertTrue(\Utils\Validator::validPassword('tempwwq2'));
	}

	public  function testValidDescriptFalse()
	{
		$this->assertFalse(\Utils\Validator::validDescript('wq'));
	}
	
	public  function testValidDescriptTrue()
	{
		$this->assertTrue(\Utils\Validator::validDescript('Green peace meeting'));
	}

	public function testValidTimeRangeTrue()
	{
		$timeStart = new \DateTime();
		$timeEnd = new \DateTime();
		$timeStart->setTime(9,20,0);
		$timeEnd->setTime(10,20,0);

		$this->assertTrue(\Utils\Validator::validTimeRange($timeStart, $timeEnd));

	}
	public function testValidTimeRangeFalseTimeEquals()
	{
		$timeStart = new \DateTime();
		$timeEnd = new \DateTime();
		$timeStart->setTime(9,20,0);
		$timeEnd->setTime(9,20,0);
		$this->assertFalse(\Utils\Validator::validTimeRange($timeStart, $timeEnd));
	}
	public function testValidTimeRangeFalseTimeEndLessStart()
	{
		$timeStart = new \DateTime();
		$timeEnd = new \DateTime();
		$timeStart->setTime(9,20,0);
		$timeEnd->setTime(8,20,0);
		$this->assertFalse(\Utils\Validator::validTimeRange($timeStart, $timeEnd));
	}
	public function testValidTimeRangeFalseTimeNotInDayBorders()
	{
		$timeStart = new \DateTime();
		$timeEnd = new \DateTime();
		$timeStart->setTime(6,20,0);
		$timeEnd->setTime(7,20,0);
		$this->assertFalse(\Utils\Validator::validTimeRange($timeStart, $timeEnd));
	}

	public function testIsWeekendTrue()
	{
		$day = new \DateTime();
		$day->setDate(2017,11,11);
		$this->assertTrue(\Utils\Validator::isWeekend($day));
	}
	public function testIsWeekendFalse()
	{
		$day = new \DateTime();
		$day->setDate(2017,11,8);
		$this->assertFalse(\Utils\Validator::isWeekend($day));
	}

	public function testIsPastTimeTrue()
	{
		$time = new \DateTime();
		$time->setDate(1971,12,12);
		$this->assertTrue(\Utils\Validator::isPastTime($time));
	}

	public function testIsPastTimeFalse()
	{
		$time = new \DateTime();
		$time->modify('+1 minute');
		$this->assertFalse(\Utils\Validator::isPastTime($time));
	}

	public function testValidDurationTrue()
	{
		$type ='weekly';
		$value= 2;
		$this->assertTrue(\Utils\Validator::validDuration($type,$value));
	}
	
	public function testValidDurationFalse()
	{
		$type ='weekly';
		$value= 'wqeqwe';
		$this->assertFalse(\Utils\Validator::validDuration($type,$value));
	}

	public function testValidDurationException()
	{
		$this->setExpectedException(\Exception::class);
		$type ='wedasdekly';
		$value= '2';
		\Utils\Validator::validDuration($type,$value);
	}

}
