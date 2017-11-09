<?php
namespace Models;
/**
 * Description of Rooms
 * Room model
 * @author yoink
 */
class Rooms
{
	/** @var \database\Database */
	private $db;

	/**
	 * gets instance of \database\Database
	 */
	public function __construct()
	{
		$this->db = \database\Database::getInstance();
	}

	/**
	 * gets rooms from db
	 *
	 * @return array of rooms/empty array if not found
	 */
	public function getRooms()
	{
		$query = \database\QSelect::instance()->setColumns('id, name')
											  ->setTable('rooms');

		return $this->db->select($query);
	}
	
}
