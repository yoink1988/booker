<?php
namespace Models;
/**
 * Description of Rooms
 *
 * @author yoink
 */
class Rooms
{
	private $db;

	public function __construct()
	{
		$this->db = \database\Database::getInstance();
	}

	public function getRooms()
	{
		$query = \database\QSelect::instance()->setColumns('id, name')
											  ->setTable('rooms');

		return $this->db->select($query);
	}
	
}
