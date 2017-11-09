<?php
namespace Controllers;
/**
 * Description of Rooms
 * Rooms Controller
 * @author yoink
 */
class Rooms
{
	/** @var \Models\Rooms */
	public $model;

	/**
	 * gets \Models\Rooms instance
	 */
	public function __construct()
	{
		$this->model = new \Models\Rooms();
	}

	/**
	 * main GET request logic
	 * @return type
	 */
	public function getRooms()
	{
		return $this->model->getRooms();
	}

}
