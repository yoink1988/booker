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
}
