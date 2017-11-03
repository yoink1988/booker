<?php
namespace Models;

class Events
{
	private $db;

	public function __construct()
	{
		$this->db = \database\Database::getInstance();
	}

	public function getEvents($params)
	{
		$query = \database\QSelect::instance()->setColumns('e.id, ed.desc as descr, '
														. 'ed.id_employee as u_id, e.id_room as room_id,'
														. ' emp.name as u_name, r.name as room_name, ed.start, ed.end')
											  ->setTable('events e')
											  ->setJoin('left join event_details ed on e.id = ed.id '
													  . 'left join rooms r on r.id = e.id_room '
													  . 'left join employees emp on ed.id_employee = emp.id')
												->setWhere("ed.start between {$this->db->clearString($params['start'])} and {$this->db->clearString($params['end'])}"
												. "and e.id_room = {$this->db->clearString($params['id_room'])}");

		return $this->db->select($query);

	}

	public function addEvent(array $params)
	{
		$id_room = $params['id_room'];
		$id_emp = $params['details']['id_emp'];
		$desc = $params['details']['descr'];
		$mainStart = $this->getTStamp($params['details']['start']);
		$mainEnd = $this->getTStamp($params['details']['end']);


		if(!\Utils\Validator::validTimeRange($mainStart, $mainEnd))
		{
			return 'Avalibale time 8:00 - 20:00';
		}


		if($id = $this->addMainEvent($mainStart ,$mainEnd, $id_room, $id_emp, $desc))
		{
			if($this->AddEventDetails($id, $mainStart, $mainEnd, $desc, $id_emp))
			{
				if(isset($params['reccuring']))
				{
					$duration = $params['reccuring']['duration'];
					$offset = $this->getOffset($params['reccuring']['type']);
					$res = $this->makeReccuringEvents($offset, $duration, $id, $mainStart, $mainEnd, $id_room, $desc, $id_emp);
					if(count($res) > 0 )
					{
						return $res;
					}
				}
				return 'Added';
			}
			return false;
		}
		$string = 'Room already booked at this time '.$mainStart->format('Y-m-d H:i');
		return $string;
	}

	private function isTimeAvaliable(\DateTime $start, \DateTime $end, $id_room)
	{
		$q = \database\QSelect::instance()->setColumns('start, end')
										->setTable('event_details')
										->setJoin("left join events on events.id = event_details.id "
												. "left join rooms on events.id_room = rooms.id")
										->setWhere("start between '{$start->format('Y-m-d 08:00:00')}' and '{$start->format('Y-m-d 20:00:00')}' "
										. "and events.id_room = {$id_room}");

		if(!$res = $this->db->select($q))
		{
			return true;
		}
		foreach($res as $ev)
		{
			if(((new \DateTime($ev['start']) <= $start) && (new \DateTime($ev['end']) <= $start)) 
			|| ((new \DateTime($ev['start']) >= $end)   && (new \DateTime($ev['end']) >= $end)))
			{
				return true;
			}
			return false;
		}
	}

	private function addMainEvent($mainStart, $mainEnd, $id_room)//, $id_emp, $desc
	{
		if($this->isTimeAvaliable($mainStart, $mainEnd, $id_room))
		{
			$q = \database\QInsert::instance()->setTable('events')
											  ->setParams(array('id_room' => $id_room));
			if($this->db->insert($q))
			{
				return $this->db->getLastInsertID();
			}
		}
		return false;
	}

	private function getTStamp($jsTimeStamp)
	{
		$date = new \DateTime();
		$date->setTimestamp($jsTimeStamp/1000);
		return $date;
	}

	private function AddEventDetails($id, $mainStart, $mainEnd, $desc, $id_emp) //
	{
		$q = \database\QInsert::instance()->setTable('event_details')
										  ->setParams(array(
															'id'=> $id,
															'start' => $mainStart->format('Y-m-d H:i:s'),
															'end' => $mainEnd->format('Y-m-d H:i:s'),
															'id_employee' => $id_emp,
															'desc' => $desc));
		return $this->db->insert($q);
	}

	private function makeReccuringEvents($offset, $duration, $id, \DateTime $mainStart, \DateTime $mainEnd, $id_room, $desc, $id_emp)
	{
		$err = [];
		for($i=0; $i < $duration; $i++)
		{
			$mainStart->modify($offset);
			$mainEnd->modify($offset);
			if($this->isTimeAvaliable($mainStart, $mainEnd, $id_room))
			{
				$this->AddEventDetails($id, $mainStart, $mainEnd, $desc, $id_emp);
			}
			else
			{
				$err[] = 'Room already booked at this time '.$mainStart->format('Y-m-d H:i');
			}
		}
		return $err;
	}

	private function getOffset($type)
	{
		switch ($type)
		{
			case 'weekly':
				$offset = '+1 week';
				break;
			case 'be-weekly':
				$offset = '+2 weeks';
				break;
			case 'monthly':
				$offset = '+1 month';
				break;
		}
		return $offset;
	}
}

