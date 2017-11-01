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
		$query = \database\QSelect::instance()->setColumns('e.id, e.description as descr, e.id_employee as u_id, e.id_room as room_id,'
				. ' emp.name as u_name, r.name as room_name, ed.start, ed.end')
											  ->setTable('events e')
											  ->setJoin('left join employees emp on e.id_employee = emp.id '
													  . 'left join rooms r on e.id_room = r.id '
													  . 'left join event_details ed on e.id = ed.id')
												->setWhere("ed.start between '{$params['start']}' and '{$params['end']}'"
												. "and e.id_room = {$params['id_room']}");

		return $this->db->select($query);

	}
}

