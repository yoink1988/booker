<?php
namespace Models;

class Events
{
	private $db;

	public function __construct()
	{
		$this->db = \database\Database::getInstance();
	}

	public function getEvent($id, $count = false, $uId = false)
	{
				
		$q = \database\QSelect::instance()->setColumns('e.id, ed.desc as descr, e.submit, ed.id_employee as u_id, '
													 . 'emp.name as u_name, ed.start, ed.end')
											->setTable('events e')
											->setJoin('left join event_details ed on e.id = ed.id '
													. 'left join employees emp on ed.id_employee = emp.id')
											->setWhere("e.id = {$this->db->clearString($id)}"
													 . " and ed.start > NOW()");
//										

		if($uId)
		{
			$q->setWhere($q->getWhere()." and emp.id = {$this->db->clearString($uId)}");
		}

		if($count)
		{
//				dump($q->getStringQuery());exit;
			return $this->db->selectCount($q);
		}
		return $this->db->select($q);
	}

	public function getEvents(array $params)
	{
		$query = \database\QSelect::instance()->setColumns('e.id, ed.desc as descr, e.submit, '
														. 'ed.id_employee as u_id, e.id_room as room_id, '
														. 'emp.name as u_name, r.name as room_name, '
														. 'ed.start, ed.end')
											  ->setTable('events e')
											  ->setJoin('left join event_details ed on e.id = ed.id '
													  . 'left join rooms r on r.id = e.id_room '
													  . 'left join employees emp on ed.id_employee = emp.id')
												->setWhere("ed.start between {$this->db->clearString($params['start'])} "
														. "and {$this->db->clearString($params['end'])}"
														. "and e.id_room = {$this->db->clearString($params['id_room'])}");

		return $this->db->select($query);

	}

	private function checkEventForm(array $params)
	{
		$mainStart = $this->getTStamp($params['details']['start']);
		$mainEnd = $this->getTStamp($params['details']['end']);

		if(\Utils\Validator::isWeekend($mainStart))
		{
			return ERR_WEEKEND_DAY;
		}

		if(!\Utils\Validator::validTimeRange($mainStart, $mainEnd))
		{
			return ERR_AVALIABLE_TIME;
		}

		if(\Utils\Validator::isPastTime($mainStart))
		{
			return ERR_PAST_TIME;
		}
		
		if(!\Utils\Validator::validDescript($params['details']['descr']))
		{
			return ERR_DESCRIPTION;
		}
	}


	public function addEvent(array $params)
	{
		if($error = $this->checkEventForm($params))
		{
			return $error;
		}
		if(isset($params['reccuring']))
		{
			if(!\Utils\Validator::validDuration($params['reccuring']['type'], $params['reccuring']['duration']))
			{
				return ERR_DURATION;
			}
		}
		$id_room = $params['id_room'];
		$id_emp = $params['details']['id_emp'];
		$desc = $params['details']['descr'];
		$mainStart = $this->getTStamp($params['details']['start']);
		$mainEnd = $this->getTStamp($params['details']['end']);

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
				return true;
			}
			return false;
		}
		$string = ERR_ALREADY_BOOKED.$mainStart->format('Y-m-d H:i');
		return $string;
	}

	private function isTimeAvaliable(\DateTime $start, \DateTime $end, $id_room, $id_event = null)
	{
		$q = \database\QSelect::instance()->setColumns('start, end')
										 ->setTable('event_details')
										 ->setJoin("left join events on events.id = event_details.id "
												 . "left join rooms on events.id_room = rooms.id")
										 ->setWhere("start between '{$start->format('Y-m-d '.SQL_START_TIME)}' "
												  . "and '{$start->format('Y-m-d '.SQL_END_TIME)}' "
												  . "and events.id_room = {$id_room}");

		if($id_event)
		{
			$q->setWhere($q->getWhere()." and event_details.id != {$id_event}");
		}
		if(!$events = $this->db->select($q))
		{
			return true;
		}
		foreach($events as $ev)
		{
			if(!(((new \DateTime($ev['start']) < $start) && (new \DateTime($ev['end']) <= $start))
			|| ((new \DateTime($ev['start']) >= $end)   && (new \DateTime($ev['end']) > $end))))
			{
				return false;
			}
		}
		return true;
	}

	private function addMainEvent($mainStart, $mainEnd, $id_room)
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

	private function AddEventDetails($id, $mainStart, $mainEnd, $desc, $id_emp) 
	{
		$q = \database\QInsert::instance()->setTable('event_details')
										  ->setParams(array(
															'id'=> $id,
															'start' => $mainStart->format(SQL_FORMAT),
															'end' => $mainEnd->format(SQL_FORMAT),
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
			if(!\Utils\Validator::isWeekend($mainStart))
			{
				if($this->isTimeAvaliable($mainStart, $mainEnd, $id_room))
				{
					$this->AddEventDetails($id, $mainStart, $mainEnd, $desc, $id_emp);
				}
				else
				{
					$err[] = ERR_ALREADY_BOOKED.$mainStart->format(SQL_FORMAT).', not booked';
				}
			}
			else
			{
				$err[] = ERR_WEEKEND_DAY.$mainStart->format('Y-m-d').', not booked';
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
			default :
				throw new \Exception(400);
		}
		return $offset;
	}




	public function updateEvents(array $params, $uId = null)
	{
		if($error = $this->checkEventForm(array ('details' => array('start' => $params['details']['timeStart'],
																	'end' => $params['details']['timeEnd'],
																	'descr' => $params['details']['desc']))))
		{
			return $error;
		}
		$occur = false;
		$id = $params['id'];
		$idRoom = $params['id_room'];
		if(isset($params['occur']))
		{
			$occur = true;
		}

		$params = $params['details'];
		$params['timeStart'] = $this->getTStamp($params['timeStart']);
		$params['timeEnd'] = $this->getTStamp($params['timeEnd']);
		$params['startPoint'] = $this->getTStamp($params['startPoint']);
		if(!$occur)
		{
			return $this->updateEvent($id, $idRoom, $params, $uId);
		}

		$res = $this->updateOccurEvents($id, $idRoom, $params, $uId);
		if(!$res)
		{
			return true;
		}
		return $res;
	}

	private function updateOccurEvents($id, $idRoom, array $params, $uId = null)
	{
		if($events = $this->getOccurentEvents($params['startPoint'], $id, $uId))
		{
			$err = [];
			foreach($events as $event)
			{
				$tmpArr['timeStart'] = new \DateTime($event['start']);
				$tmpArr['timeStart']->setTime((int)$params['timeStart']->format('H'), (int)$params['timeStart']->format('i'));
				$tmpArr['timeEnd'] = new \DateTime($event['end']);
				$tmpArr['timeEnd']->setTime((int)$params['timeEnd']->format('H'), (int)$params['timeEnd']->format('i'));

				$tmpArr['desc'] = $params['desc'];
				$tmpArr['id_user'] = $params['id_user'];
				$tmpArr['startPoint'] = new \DateTime($event['start']);

				$res = $this->updateEvent($id, $idRoom, $tmpArr, $uId);
				if(!is_bool($res))
				{
					$err[] = $res;
				}
			}
			return $err;
		}
		return false;
	}
	
	private function getOccurentEvents(\DateTime $start ,$id, $uId = null)
	{
		$q = \database\QSelect::instance()->setTable('event_details')
										->setColumns('start, end, `desc`, id_employee')
										->setWhere("id = {$id} and start > now() "
												 . "and start >= '{$start->format(SQL_FORMAT)}'");
		if($uId)
		{
			$q->setWhere($q->getWhere()." and id_employee = {$this->db->clearString($uId)}");
		}

		return $this->db->select($q);
	}

	private function updateEvent($id, $idRoom, array $params, $uId=null)
	{
		if($this->isTimeAvaliable($params['timeStart'], $params['timeEnd'], $idRoom, $id))
		{
			$q = \database\QUpdate::instance()->setTable('event_details')
												->setParams(array(
													'start' => $params['timeStart']->format(SQL_FORMAT),
													'end' => $params['timeEnd']->format(SQL_FORMAT),
													'id_employee' => $params['id_user'],
													'desc' => $params['desc']))
													->setWhere("start = '{$params['startPoint']->format(SQL_FORMAT)}'");
		if($uId)
		{
			$q->setWhere($q->getWhere()." and id_employee = {$this->db->clearString($uId)}");
		}

			return $this->db->update($q);
		}
		else
		{
			return ERR_ALREADY_BOOKED.$params['timeStart']->format('Y-m-d H:i');
		}
	}

	public function deleteEvents(array $params, $uId = null)
	{
		$id = $this->db->clearString($params['id']);
		$startPoint = $this->getTStamp($params['from']);

		$q = \database\QDelete::instance()->setTable('event_details')
										->setWhere("id = {$params['id']}");

		if(!isset($params['all']))
		{
			$q->setWhere($q->getWhere()." and start = '{$startPoint->format(SQL_FORMAT)}'")->setLimit('1');
		}
		else
		{
			$q->setWhere($q->getWhere()." and start > now() and start >= '{$startPoint->format(SQL_FORMAT)}'");
			if($uId)
			{
				$q->setWhere($q->getWhere()." and id_employee = {$this->db->clearString($uId)}");
			}
		}

		if($this->db->delete($q))
		{
			return $this->clearEvents($id);
		}
		return false;
	}

	private function clearEvents($id)
	{
		$qs = \database\QSelect::instance()->setTable('event_details')->setColumns('id')->setWhere("id = {$id}");

		$res = $this->db->select($qs);
		if(!$res)
		{
			$qd = \database\QDelete::instance()->setTable('events')->setWhere("id = {$id}");
			return $this->db->delete($qd);
		}
		return true;
	}

}

