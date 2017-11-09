<?php
namespace Models;
/**
 * Description of RoomsTest
 *
 * @author yoink
 */
class RoomsTest extends \PHPUnit_Framework_TestCase
{
	private $dbManager, $rooms;

	public function setUp()
	{
		$this->dbManager = new \DBManager();

		$this->rooms = new \Models\Rooms();
	}

	public function tearDown()
	{
		$this->dbManager->clear();
	}

	public function testGetRoomsTrue()
	{
		$this->dbManager->addDBRecord("insert into rooms set name = 'Room', id = '1'", "delete from rooms where id = '1'");
		$res = $this->rooms->getRooms();
		$this->assertTrue(count($res) > 0);
	}

	public function testGetRoomsFalse()
	{
		$res = $this->rooms->getRooms();
		$this->assertEquals($res, array());
	}
}
