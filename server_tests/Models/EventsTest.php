<?php
namespace Models;
/**
 * Description of EventsTest
 *
 * @author yoink
 */
class EventsTest extends \PHPUnit_Framework_TestCase
{
	private $dbManager, $events;

	public function setUp()
	{
		$this->dbManager = new \DBManager();

		$this->events = new \Models\Events();
	}

	public function tearDown()
	{
		$this->dbManager->clear();
	}

	public function testGetEventFalse()
	{
		$this->assertEquals($this->events->getEvent(1), array());
	}
	public function testGetEventTrue()
	{
		$start = new \DateTime();
		$end = new \DateTime();
		$start->modify('+1 day');
		$end->modify('+1 day');
		$this->dbManager->addDBRecord("insert into employees set id = 1, name = 'User'", "delete from employees where id = 1");
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1", "delete from events where id = 1");
		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d H:i:s')}', "
				. "end = '{$end->format('Y-m-d H:i:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1", "delete from event_details where id = 1");

		$res = $this->events->getEvent('1');
		$this->assertTrue(count($res) > 0);

	}
	public function testGetEventCountTrue()
	{
		$start = new \DateTime();
		$end = new \DateTime();
		$start->modify('+1 day');
		$end->modify('+1 day');
		$this->dbManager->addDBRecord("insert into employees set id = 1, name = 'User'", "delete from employees where id = 1");
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1", "delete from events where id = 1");
		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d H:i:s')}', "
				. "end = '{$end->format('Y-m-d H:i:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1", "delete from event_details where id = 1");

		$res = $this->events->getEvent('1', true, false);
		$this->assertTrue($res === 1);
	}

	public function testGetEventsFalse()
	{
		$start = new \DateTime();
		$start->setDate(2017,11, 1);
		$arr['start'] = $start->format('Y-m-d '.SQL_START_TIME);
		$arr['end'] = $start->format('Y-m-t '.SQL_END_TIME);
		$arr['id_room'] = 1;
		$res = $this->events->getEvents($arr);

		$this->assertEquals($res, array());
	}

	public function testGetEventsTrue()
	{
		$start = new \DateTime();
		$end = new \DateTime();
		$start->setDate(2017,11, 1);
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1", "delete from events where id = 1");
		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d 9:00:s')}', "
				. "end = '{$end->format('Y-m-d 10:00:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1", "delete from event_details where id = 1");
		$this->dbManager->addDBRecord("insert into employees"
										. " set id = 1, name = 'User', email = 'user@user.ru', pass = 'qwqwewqee'",
										"delete from employees where id = 1");
		$arr['start'] = $start->format('Y-m-d '.SQL_START_TIME);
		$arr['end'] = $start->format('Y-m-t '.SQL_END_TIME);
		$arr['id_room'] = 1;

		$res = $this->events->getEvents($arr);
		$this->assertTrue(count($res) > 0);
	}

	public function testAddEventTrue()
	{
		$start = new \DateTime();
		$start->modify('+1 day');
		$start->setTime(9,0,0);
		$end = new \DateTime();
		$end->modify('+1 day');
		$end->setTime(10,0,0);
		$params['id_room'] = '1';
		$params['details']['id_emp'] = '1';
		$params['details']['descr'] = 'dasdasdasdas';
		$params['details']['start'] = $start->getTimestamp()*1000;
		$params['details']['end'] = $end->getTimestamp()*1000;

		$this->events->addEvent($params);

		$res = $this->dbManager->getDBRecord('select * from events');
		$this->assertTrue(count($res) > 0);
		$this->dbManager->addToClear("delete from events where id_room = 1 limit 1");
		$this->dbManager->addToClear("delete from event_details where id_employee = 1 and `desc` = 'dasdasdasdas'");

	}

	public function testaddEventFalse()
	{
		$start = new \DateTime();
		$start->modify('+1 day');
		$start->setTime(9,0,0);
		$end = new \DateTime();
		$end->modify('+1 day');
		$end->setTime(10,0,0);
		$params['id_room'] = '1';
		$params['details']['id_emp'] = '1';
		$params['details']['descr'] = 'd';
		$params['details']['start'] = $start->getTimestamp()*1000;
		$params['details']['end'] = $end->getTimestamp()*1000;

		$res = $this->events->addEvent($params);
		$this->assertEquals($res, 'Check description field');
	}

	public function testUpdateEventsTrue()
	{
		$start = new \DateTime();
		$start->modify('+1 day');
		$start->setTime(9,0,0);
		$end = new \DateTime();
		$end->modify('+1 day');
		$end->setTime(10,0,0);
		$params['id_room'] = '1';
		$params['id'] = '1';
		$params['details']['id_user'] = '1';
		$params['details']['desc'] = 'wwwwwwwwwwww';
		$params['details']['timeStart'] = $start->getTimestamp()*1000;
		$params['details']['timeEnd'] = $end->getTimestamp()*1000;
		$params['details']['startPoint'] = $start->getTimestamp()*1000;
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1","delete from events where id = 1");
		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d 9:00:s')}', "
				. "end = '{$end->format('Y-m-d 10:00:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1", "delete from event_details where id = 1");

		$this->assertTrue($this->events->updateEvents($params));
	}

	public function testUpdateEventsFalse()
	{
		$start = new \DateTime();
		$start->modify('+1 day');
		$start->setTime(9,0,0);
		$end = new \DateTime();
		$end->modify('+1 day');
		$end->setTime(10,0,0);
		$params['id_room'] = '1';
		$params['id'] = '1';
		$params['details']['id_user'] = '1';
		$params['details']['desc'] = 'xx';
		$params['details']['timeStart'] = $start->getTimestamp()*1000;
		$params['details']['timeEnd'] = $end->getTimestamp()*1000;
		$params['details']['startPoint'] = $start->getTimestamp()*1000;
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1","delete from events where id = 1");
		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d 9:00:s')}', "
				. "end = '{$end->format('Y-m-d 10:00:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1", "delete from event_details where id = 1");

		$res = $this->events->updateEvents($params);
		$this->assertEquals($res, 'Check description field');
	}

	public function testDeleteEvents()
	{
		$start = new \DateTime();
		$start->modify('+1 day');
		$start->setTime(9,0,0);
		$end = new \DateTime();
		$end->modify('+1 day');
		$end->setTime(10,0,0);
		$from = clone $start;
		$this->dbManager->addDBRecord("insert into events set id = 1, id_room = 1");
		$this->dbManager->addDBRecord("insert into event_details "
				. "set id = 1, "
				. "start = '{$start->format('Y-m-d 9:00:s')}', "
				. "end = '{$end->format('Y-m-d 10:00:s')}', "
				. "`desc` = 'dasdasdasd', "
				. "id_employee = 1", "delete from event_details where id = 1");

		$this->assertTrue($this->events->deleteEvents(array('id' => '1', 'from' => $from->getTimestamp()*1000)));
	}
}
