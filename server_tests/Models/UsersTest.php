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
		$res = $this->users->getUsers(array('id' => 1));
		$this->assertTrue(count($res) > 0);
    }

    public function testAddUserInvalidName()
    {
		$res = $this->users->addUser(array('name' => '!@$R#','email' => '','pass' => ''));
		$this->assertEquals($res , 'Incorrect Name');
    }
    public function testAddUserInvalidEmail()
    {
		$res = $this->users->addUser(array('name' => 'Iluha','email' => 'dasqee.ru','pass' => ''));
		$this->assertEquals($res , 'Incorrect Email');
    }

    public function testAddUserInvalidPass()
    {
		$res = $this->users->addUser(array('name' => 'Iluha','email' => 'iluha@mail.ru','pass' => 'qwe11'));
		$this->assertEquals($res , 'Incorrect Password');
    }

    public function testAddUserTrue()
    {
		$this->dbManager->addToClear("delete from employees where name = 'Iluha' and email = 'iluha@mail.ru'");
		$this->assertTrue($this->users->addUser(array('name' => 'Iluha','email' => 'iluha@mail.ru','pass' => 'qwqwewe11')));
    }

	public function testEditUserInvalidName()
	{
		$this->dbManager->addDBRecord("insert into employees"
										. " set id = 1, name = 'User', email = 'user@user.ru', pass = 'qweqwe11'",
										"delete from employees where id = 1");

		$res = $this->users->editUser(array('name' => '!@$R#','email' => '','pass' => ''));
		$this->assertEquals($res, 'Incorrect Name');
	}

	public function testEditUserInvalidEmail()
	{
		$this->dbManager->addDBRecord("insert into employees"
										. " set id = 1, name = 'User', email = 'user@user.ru', pass = 'qweqwe11'",
										"delete from employees where id = 1");

		$res = $this->users->editUser(array('name' => 'Iluha','email' => 'qweqwe','pass' => ''));
		$this->assertEquals($res, 'Incorrect Email');
	}

	public function testEditUserTrue()
	{
		$this->dbManager->addDBRecord("insert into employees"
										. " set id = 1, name = 'User', email = 'user@user.ru', pass = 'qwqwewqee'",
										"delete from employees where id = 1");

		$this->assertTrue($this->users->editUser(array('id' => 1, 'name' => 'Iluha','email' => 'user@user.ru')));
	}

	public function testDeleteUser()
	{
		$this->dbManager->addDBRecord("insert into employees set id = 1, name = 'User'");
		$this->users->deleteUser('1');

		$res = $this->dbManager->getDBRecord('select name from employees where id = 1');
		$this->assertEquals($res, array());
	}

	public function testDeleteUserClearEvents()
	{
		$this->dbManager->addDBRecord("insert into employees set id = 1, name = 'User'");
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1");
		$start = new \DateTime();
		$end = new \DateTime();
		$start->modify('+1 day');
		$start->setTime(9,0,0);
		$end->setTime(10,0,0);

		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d H:i:s')}', "
				. "end = '{$end->format('Y-m-d H:i:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1");
		$this->users->deleteUser(1);

		$res = $this->dbManager->getDBRecord('select * from events where id = 1');
		$this->assertEquals($res, array());
	}

}
