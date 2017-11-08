<?php
namespace Models;
/**
 * Description of UsersTest
 *
 * @author yoink
 */
class UsersTest  extends \PHPUnit_Framework_TestCase
{

	private $dbManager, $users;

	public function setUp()
	{
		$this->dbManager = new \DBManager();

		$this->users = new \Models\Users();
	}

	public function tearDown()
	{
		$this->dbManager->clear();
	}

    public function testGetUsersFalse()
    {
		$this->assertEquals($this->users->getUsers(), array());
    }

    public function testGetUsersTrue()
    {
		$this->dbManager->addDBRecord("insert into employees set id = 1, name = 'User'", "delete from employees where id = 1");
		$res = $this->users->getUsers(1);
		$this->assertTrue(count($res) > 0);
    }

    public function testAddUserInvalidName()
    {
		$res = $this->users->addUser(array('name' => '!@$R#','login' => '','pass' => ''));
		$this->assertEquals($res , 'Invalid Name');
    }
    public function testAddUserInvalidEmail()
    {
		$res = $this->users->addUser(array('name' => 'Iluha','login' => 'dasqee.ru','pass' => ''));
		$this->assertEquals($res , 'Invalid Email');
    }
	
    public function testAddUserInvalidPass()
    {
		$res = $this->users->addUser(array('name' => 'Iluha','login' => 'iluha@mail.ru','pass' => 'qwe11'));
		$this->assertEquals($res , 'Invalid Password');
    }

    public function testAddUserTrue()
    {
		$this->dbManager->addToClear("delete from employees where name = 'Iluha' and login = 'iluha@mail.ru'");
		$this->assertTrue($this->users->addUser(array('name' => 'Iluha','login' => 'iluha@mail.ru','pass' => 'qwqwewe11')));
    }

	public function testUpdateUserInvalidName()
	{
		$this->dbManager->addDBRecord("insert into employees"
										. " set id = 1, name = 'User', login = 'user@user.ru', pass = 'qweqwe11'",
										"delete from users where id = 1");

		$res = $this->users->updateUser(array('name' => '!@$R#','login' => '','pass' => ''));
		$this->assertEquals($res, 'Invalid Name');
	}

	public function testUpdateUserInvalidEmail()
	{
		$this->dbManager->addDBRecord("insert into employees"
										. " set id = 1, name = 'User', login = 'user@user.ru', pass = 'qweqwe11'",
										"delete from employees where id = 1");

		$res = $this->users->updateUser(array('name' => 'Iluha','login' => 'qweqwe','pass' => ''));
		$this->assertEquals($res, 'Invalid Email');
	}

}
