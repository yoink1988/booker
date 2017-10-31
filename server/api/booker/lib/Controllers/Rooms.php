<?php
namespace Controllers;
/**
 * Description of Rooms
 *
 * @author yoink
 */
class Rooms
{
	public $model;

	public function __construct()
	{
		$this->model = new \Models\Rooms();
	}

	public function getRooms()
	{
		return $this->model->getRooms();
	}

}
