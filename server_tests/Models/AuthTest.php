<?php
namespace Models;
/**
 * Description of AuthTest
 *
 * @author yoink
 */
class AuthTest extends \PHPUnit_Framework_TestCase
{
	private $dbManager, $auth;

	public function setUp()
	{
		$this->dbManager = new \DBManager();

		$this->auth = new \Models\Auth();
	}

	public function tearDown()
	{
		$this->dbManager->clear();
	}

	/**
	 * @dataProvider providerCheckLogDataFalse
	 */
	public function testCheckLogDataFalse($params)
	{
		$this->assertFalse($this->auth->checkLogData($params));
	}
	public function providerCheckLogDataFalse()
	{
		return array(
			array(
				array(
					'login' => '',
					'pass' => ''
				)
			),
			array(
				array(
					'login' => 'valid@email.com',
					'pass' => ''
				)
			),
			array(
				array(
					'login' => 'valid@email.com',
					'pass' => 'qweuqweu12312'
				)
			)
		);
	}

	public function testCheckLogDataTrue()
	{
		$pass = md5('qwe123asd');
		$this->dbManager->addDBRecord(
				"insert into users set login='asd@asd.com',pass='$pass',"
					. "status='1'",
				"delete from users where login='asd@asd.com' and pass='$pass'");

		$this->assertTrue($this->auth->checkLogData(array(
			'login' => 'asd@asd.com', 'pass' => 'qwe123asd')));
	}

	public function testLoginFalse()
	{
		$result = $this->auth->login(array('login'=> '', 'pass' => ''));
		$this->assertTrue(is_array($result) && count($result) == 0);
	}

	public function testLoginValid()
	{
		$pass = md5('qweqwe11');
		$this->dbManager->addDBRecord("insert into users set login = 'qwee@qwe.rr', pass = '$pass', role='user', discount = 0", "delete from users where login= 'qwee@qwe.rr' and pass= '$pass'");

		$result = $this->auth->login(array('login'=> 'qwee@qwe.rr', 'pass' => 'qweqwe11'));
		$this->assertTrue(is_array($result) && count($result) != 0);

	}

	public function testCheckAuthFalse()
	{
		$result = $this->auth->checkAuth(array('id'=> '', 'hash' => ''));
		$this->assertFalse($result);
	}

	public function testCheckAuthValid()
	{
		$pass = md5('qweqwe11');

		$this->dbManager->addDBRecord("insert into users set id=1, hash= '$pass', login = 'qwee@qwe.rr', pass = '$pass', role='user', discount = 0", "delete from users where login= 'qwee@qwe.rr' and pass= '$pass'");

		$this->assertTrue($this->auth->checkAuth(array('id'=> '1', 'hash' => $pass)));
	}

}
